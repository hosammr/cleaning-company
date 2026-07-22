<?php
/**
 * Security hardening.
 *
 * @package HDS
 */

/**
 * Remove WordPress version from head and feeds.
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove RSD link.
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Remove wlwmanifest link.
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Remove shortlink.
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Disable oEmbed discovery links.
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * Remove REST API user endpoint.
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
 * Disable author archives.
 */
function hds_disable_author_archives(): void {
	if ( is_author() ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'hds_disable_author_archives' );

/**
 * Disable attachment pages.
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
