<?php
/**
 * Centralized error handling.
 *
 * Provides user-friendly error rendering for common scenarios
 * and hooks into WordPress debug logging.
 *
 * @package HDS
 */

/**
 * Custom 404 template resolution.
 */
function hds_404_template(): string {
	return HDS_DIR . '/404.php';
}
add_filter( '404_template', 'hds_404_template' );

/**
 * Custom search template resolution.
 */
function hds_search_template( string $template ): string {
	if ( is_search() ) {
		$custom = HDS_DIR . '/search.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'search_template', 'hds_search_template' );

/**
 * Custom archive template resolution.
 */
function hds_archive_template( string $template ): string {
	if ( is_post_type_archive( 'hds_vacancy' ) ) {
		$custom = HDS_DIR . '/archive.php';
		if ( file_exists( $custom ) ) {
			return $custom;
		}
	}
	return $template;
}
add_filter( 'archive_template', 'hds_archive_template' );

/**
 * Render a user-friendly error message for template parts.
 */
function hds_render_error( string $message, string $type = 'error' ): string {
	return sprintf(
		'<div class="hds-notification hds-notification--%s" role="alert"><span class="hds-notification__message">%s</span></div>',
		esc_attr( $type ),
		esc_html( $message )
	);
}

/**
 * Suppress PHP errors from showing on frontend in production.
 */
function hds_error_reporting(): void {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) {
		return;
	}

	if ( ! is_admin() ) {
		@ini_set( 'display_errors', '0' ); // phpcs:ignore
	}
}
add_action( 'init', 'hds_error_reporting' );

/**
 * Write to debug log with context.
 */
function hds_log( string $message, array $context = [], string $level = 'info' ): void {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
		return;
	}

	$context_str = $context ? ' | ' . wp_json_encode( $context ) : '';
	$prefix      = strtoupper( $level );

	error_log( "[HDS] [{$prefix}] {$message}{$context_str}" ); // phpcs:ignore
}

/**
 * Log an exception or error.
 */
function hds_log_error( \Throwable $error, array $context = [] ): void {
	$context['file']  = $error->getFile();
	$context['line']  = $error->getLine();
	$context['trace'] = $error->getTraceAsString();

	hds_log( $error->getMessage(), $context, 'error' );
}

/**
 * Render a maintenance mode page.
 */
function hds_maintenance_mode(): void {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die(
			'<h1>' . esc_html__( 'Binnenkort terug', 'hds' ) . '</h1><p>' . esc_html__( 'De website is tijdelijk offline voor onderhoud. Probeer het later opnieuw.', 'hds' ) . '</p>',
			esc_html__( 'Onderhoud', 'hds' ),
			[ 'response' => 503 ]
		);
	}
}
// Not hooked — use wp-config.php `define('WP_MAINTENANCE_MODE', true)` instead.

/**
 * Send HTTP 410 Gone for removed content.
 */
function hds_send_410_for_removed_content(): void {
	$removed_slugs = apply_filters( 'hds_410_slugs', [] );

	if ( is_404() ) {
		$request_uri = $_SERVER['REQUEST_URI'] ?? '';
		foreach ( $removed_slugs as $slug ) {
			if ( str_contains( $request_uri, $slug ) ) {
				status_header( 410 );
				echo hds_render_error( __( 'Deze pagina is niet meer beschikbaar.', 'hds' ), 'info' );
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'hds_send_410_for_removed_content' );
