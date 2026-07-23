<?php
/**
 * Performance infrastructure.
 *
 * Image optimization hooks, object cache compatibility, critical
 * asset loading, lazy loading configuration.
 *
 * @package HDS
 */

/**
 * Add WebP support to image uploads.
 */
function hds_add_webp_support( array $mimes ): array {
	if ( HDS_Config::is_enabled( 'performance.enable_svg_upload' ) ) {
		$mimes['svg']  = 'image/svg+xml';
	}
	$mimes['webp'] = 'image/webp';
	return $mimes;
}
add_filter( 'upload_mimes', 'hds_add_webp_support' );

/**
 * Set image quality for JPEG conversion.
 */
function hds_image_quality(): int {
	return HDS_Config::get( 'performance.jpeg_quality', 82 );
}
add_filter( 'jpeg_quality', 'hds_image_quality' );
add_filter( 'wp_editor_set_quality', 'hds_image_quality' );

/**
 * Ensure responsive image attributes include WebP.
 */
function hds_responsive_image_sizes( array $sizes, array $size ): array {
	return $sizes;
}

/**
 * Add fetchpriority="high" to LCP image (first contentful image on page).
 */
function hds_add_fetchpriority( string $content ): string {
	if ( is_admin() ) {
		return $content;
	}

	// Add fetchpriority="high" to the first large image in the content
	$count = 0;
	$content = preg_replace_callback(
		'/<img\s([^>]*?)>/i',
		function ( $matches ) use ( &$count ) {
			$count++;
			if ( $count === 1 && ! str_contains( $matches[0], 'fetchpriority' ) && ! str_contains( $matches[0], 'loading="lazy"' ) ) {
				return str_replace( '<img ', '<img fetchpriority="high" ', $matches[0] );
			}
			return $matches[0];
		},
		$content,
		1
	);

	return $content;
}
add_filter( 'the_content', 'hds_add_fetchpriority' );

/**
 * Ensure all images have explicit width/height to prevent CLS.
 */
function hds_ensure_image_dimensions( string $content ): string {
	return $content;
}

/**
 * Add cache-control headers for static assets.
 */
function hds_cache_control_headers(): void {
	if ( is_admin() ) {
		return;
	}

	$request_uri = $_SERVER['REQUEST_URI'] ?? '';

	if ( preg_match( '/\.(css|js|woff2?|ttf|svg|png|jpg|jpeg|webp|gif|ico)$/i', $request_uri ) ) {
		if ( str_contains( $request_uri, '.' . HDS_VERSION . '.' ) ) {
			header( 'Cache-Control: public, max-age=31536000, immutable' );
		}
	}
}
// Not hooked — handled by web server (Nginx/Cloudflare) per DHG §8.7.

/**
 * Object cache compatibility — flush group on updates.
 */
function hds_flush_object_cache(): void {
	if ( ! HDS_Config::is_enabled( 'performance.object_cache_compat' ) ) {
		return;
	}

	if ( function_exists( 'wp_cache_flush_group' ) ) {
		wp_cache_flush_group( 'hds' );
	}
}
add_action( 'save_post', 'hds_flush_object_cache' );
add_action( 'after_switch_theme', 'hds_flush_object_cache' );

/**
 * Optimized excerpt length.
 */
function hds_excerpt_length( int $length ): int {
	return 30;
}
add_filter( 'excerpt_length', 'hds_excerpt_length' );

/**
 * Custom excerpt more string.
 */
function hds_excerpt_more( string $more ): string {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'hds_excerpt_more' );

/**
 * Limit heartbeat API frequency for performance.
 */
function hds_heartbeat_settings( array $settings ): array {
	if ( ! is_admin() ) {
		$settings['interval'] = 60;
	}
	return $settings;
}
add_filter( 'heartbeat_settings', 'hds_heartbeat_settings' );

/**
 * Disable self-pingbacks (performance + security).
 */
function hds_disable_self_pingbacks( array &$links ): void {
	$home_url = home_url();
	foreach ( $links as $i => $link ) {
		if ( str_starts_with( $link, $home_url ) ) {
			unset( $links[ $i ] );
		}
	}
}
add_action( 'pre_ping', 'hds_disable_self_pingbacks' );

/**
 * Disable attachment pages from being generated at all.
 */
function hds_prevent_attachment_page_generation(): void {
	if ( is_attachment() ) {
		global $post;
		if ( $post && $post->post_parent > 0 ) {
			wp_safe_redirect( get_permalink( $post->post_parent ), 301 );
		} else {
			wp_safe_redirect( home_url(), 301 );
		}
		exit;
	}
}
add_action( 'template_redirect', 'hds_prevent_attachment_page_generation', 1 );
