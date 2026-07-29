<?php
/**
 * Security hardening.
 *
 * REST API hardening, user enumeration prevention, attachment page redirects.
 * General head cleanup and XML-RPC disable are in inc/setup.php.
 *
 * @package HDS
 */

/**
 * Remove REST API user endpoint to prevent user enumeration.
 */
function hds_disable_rest_user_endpoint( $endpoints ): array {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'hds_disable_rest_user_endpoint' );

/**
 * Disable author archives to prevent user enumeration via ?author=N.
 */
function hds_disable_author_archives(): void {
	if ( is_author() ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'hds_disable_author_archives' );

/**
 * Disable attachment pages — redirect to parent or home.
 */
function hds_disable_attachment_pages(): void {
	if ( is_attachment() ) {
		global $post;
		if ( $post && $post->post_parent ) {
			wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
			exit;
		}
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'hds_disable_attachment_pages' );

/**
 * Add security headers via .htaccess alternative.
 * X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy.
 */
function hds_security_headers(): void {
	if ( ! headers_sent() ) {
		header( 'X-Content-Type-Options: nosniff' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
	}
}
add_action( 'init', 'hds_security_headers', 0 );

/**
 * Disable pingbacks for security (X-Pingback header + XML-RPC pingback method).
 */
function hds_disable_pingbacks( array $headers ): array {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'hds_disable_pingbacks' );

/**
 * Prevent login error messages from revealing whether username or password is wrong.
 */
function hds_login_error_message(): string {
	return __( 'Inloggegevens zijn onjuist.', 'hds' );
}
add_filter( 'login_errors', 'hds_login_error_message' );

/**
 * Restrict REST API access to authenticated users for sensitive endpoints.
 */
function hds_rest_authentication_required( \WP_Error|bool|null $result ): \WP_Error|bool|null {
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	if ( is_user_logged_in() ) {
		return $result;
	}

	$rest_route = $GLOBALS['wp']->query_vars['rest_route'] ?? '';
	$protected_prefixes = [ '/wp/v2/users', '/wp/v2/settings' ];

	foreach ( $protected_prefixes as $prefix ) {
		if ( str_starts_with( $rest_route, $prefix ) ) {
			return new \WP_Error(
				'rest_not_logged_in',
				__( 'Je moet ingelogd zijn.', 'hds' ),
				[ 'status' => 401 ]
			);
		}
	}

	return $result;
}
add_filter( 'rest_authentication_errors', 'hds_rest_authentication_required' );
