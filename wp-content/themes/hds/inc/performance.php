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
 *
 * SVG support is intentionally not registered here: it is registered —
 * and capability-gated to users with `unfiltered_html` — in
 * inc/setup.php via hds_allow_svg_uploads(). Adding it here too would
 * bypass that gate.
 */
function hds_add_webp_support( array $mimes ): array {
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
 * Add the smaller generated candidates to the hero image srcset.
 *
 * WordPress only emits a `srcset` when at least two generated candidates
 * share the aspect ratio of the requested size. The hero attachments are
 * stored at 1983×793 (ratio 2.5:1) with an 'hds-hero' size of 1600×793
 * (ratio ≈2.02:1), so the standard candidate loop matches only the
 * requested size itself and WordPress drops the srcset entirely. That made
 * every viewport download the full 1600px candidate.
 *
 * The hero is rendered with `object-fit: cover` into a fixed, full-width
 * box, so the candidate aspect ratio does not affect the visual result —
 * the smaller generated candidates can be added back safely. The 'large'
 * (1024px) candidate is intentionally left out: on viewports 1024–1599px
 * it would be selected over the current 1600×793 'hds-hero' file and change
 * the hero crop, which must stay unchanged.
 *
 * @param array  $sources      Image srcset sources, keyed by width.
 * @param array  $size_array   Requested [width, height].
 * @param string $image_src    The src of the requested size.
 * @param array  $image_meta   Attachment metadata.
 * @param int    $attachment_id Attachment ID.
 * @return array Updated sources.
 */
function hds_add_hero_srcset_candidates( array $sources, array $size_array, string $image_src, array $image_meta, int $attachment_id ): array {
	if ( array( 1600, 793 ) !== $size_array ) {
		return $sources;
	}

	if ( empty( $image_meta['sizes'] ) || empty( $image_meta['file'] ) ) {
		return $sources;
	}

	$dirname = _wp_get_attachment_relative_path( $image_meta['file'] );
	if ( $dirname ) {
		$dirname = trailingslashit( $dirname );
	}

	$upload_dir = wp_get_upload_dir();
	$base_url   = trailingslashit( $upload_dir['baseurl'] ) . $dirname;

	// Theme-registered candidates that safely fit a sub-1024px hero slot.
	$candidate_widths = array( 300, 400, 768, 800 );

	foreach ( $image_meta['sizes'] as $size_data ) {
		if ( ! is_array( $size_data ) || empty( $size_data['file'] ) ) {
			continue;
		}

		$width = (int) ( $size_data['width'] ?? 0 );
		if ( ! in_array( $width, $candidate_widths, true ) || isset( $sources[ $width ] ) ) {
			continue;
		}

		$sources[ $width ] = array(
			'url'        => $base_url . $size_data['file'],
			'descriptor' => 'w',
			'value'      => $width,
		);
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'hds_add_hero_srcset_candidates', 10, 5 );

/**
 * Build the WebP <source> for a Service Hero <img>.
 *
 * Only the eight Service Hero attachments (351–358) are eligible. The WebP
 * <source> mirrors the exact PNG hero srcset candidates (300w, 400w, 768w,
 * 800w, 1600w) with the same sizes expression. Every candidate WebP sibling
 * must exist on disk; if any is missing the whole source is omitted so the
 * PNG <img> remains the complete fallback and no broken URL is emitted.
 *
 * @param int $attachment_id Attachment ID.
 * @return string <source> markup, or '' when ineligible or incomplete.
 */
function hds_service_hero_webp_source( int $attachment_id ): string {
	$service_hero_ids = array( 351, 352, 353, 354, 355, 356, 357, 358 );
	if ( ! in_array( $attachment_id, $service_hero_ids, true ) ) {
		return '';
	}

	$meta = wp_get_attachment_metadata( $attachment_id );
	if ( empty( $meta['file'] ) || empty( $meta['sizes'] ) || ! is_array( $meta['sizes'] ) ) {
		return '';
	}

	$uploads = wp_get_upload_dir();
	$dirname = _wp_get_attachment_relative_path( $meta['file'] );
	$prefix  = $dirname ? trailingslashit( $dirname ) : '';
	$basedir = trailingslashit( $uploads['basedir'] ) . $prefix;
	$baseurl = trailingslashit( $uploads['baseurl'] ) . $prefix;

	// Widths of the PNG hero srcset candidates (see hds_add_hero_srcset_candidates()).
	$widths = array( 300, 400, 768, 800, 1600 );
	$srcset = array();

	foreach ( $widths as $width ) {
		$size_file = '';
		foreach ( $meta['sizes'] as $size_data ) {
			if ( is_array( $size_data ) && isset( $size_data['width'], $size_data['file'] ) && (int) $size_data['width'] === $width ) {
				$size_file = $size_data['file'];
				break;
			}
		}
		if ( '' === $size_file ) {
			return ''; // Candidate not registered — the mirror would be incomplete.
		}

		$webp_file = preg_replace( '/\.png$/i', '.webp', $size_file );
		if ( $webp_file === $size_file || ! file_exists( $basedir . $webp_file ) ) {
			return ''; // Missing WebP sibling — keep the full PNG fallback.
		}

		$srcset[] = $baseurl . $webp_file . ' ' . $width . 'w';
	}

	return '<source type="image/webp" srcset="' . esc_attr( implode( ', ', $srcset ) ) . '" sizes="(max-width: 1600px) 100vw, 1600px">';
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
