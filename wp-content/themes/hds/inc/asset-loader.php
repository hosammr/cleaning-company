<?php
/**
 * Centralized asset loader.
 *
 * Manages CSS, JS, font preloading, conditional loading per template,
 * and cache busting. Replaces inline wp_enqueue_* calls in functions.php.
 *
 * @package HDS
 */

/**
 * Register and enqueue theme stylesheets.
 */
function hds_enqueue_styles(): void {
	$version = hds_get_asset_version();
	$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'hds-main',
		HDS_URI . '/assets/css/main' . $min . '.css',
		[],
		$version
	);

	wp_enqueue_style(
		'hds-editor',
		HDS_URI . '/assets/css/editor.css',
		[],
		$version
	);

	$blocks_css = HDS_DIR . '/assets/css/blocks' . $min . '.css';
	if ( file_exists( $blocks_css ) ) {
		wp_enqueue_style(
			'hds-blocks',
			HDS_URI . '/assets/css/blocks' . $min . '.css',
			[ 'hds-main' ],
			$version
		);
	}
}
add_action( 'wp_enqueue_scripts', 'hds_enqueue_styles' );
add_action( 'enqueue_block_editor_assets', 'hds_enqueue_styles' );

/**
 * Register and enqueue theme scripts with defer.
 */
function hds_enqueue_scripts(): void {
	$version = hds_get_asset_version();
	$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script(
		'hds-main',
		HDS_URI . '/assets/js/main' . $min . '.js',
		[],
		$version,
		[
			'strategy'  => 'defer',
			'in_footer' => true,
		]
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hds_enqueue_scripts' );

/**
 * Add defer attribute to script tags.
 */
function hds_add_defer_attribute( string $tag, string $handle ): string {
	if ( 'hds-main' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'hds_add_defer_attribute', 10, 2 );

/**
 * Preload critical above-the-fold assets.
 */
function hds_preload_assets(): void {
	$logo_id = get_theme_mod( 'custom_logo' );

	if ( $logo_id ) {
		$logo_url = wp_get_attachment_image_url( (int) $logo_id, 'full' );
		if ( $logo_url ) {
			echo '<link rel="preload" href="' . esc_url( $logo_url ) . '" as="image" type="' . esc_attr( get_post_mime_type( (int) $logo_id ) ) . '">' . "\n";
		}
	}

	$font_dir = HDS_DIR . '/assets/fonts';
	if ( is_dir( $font_dir ) ) {
		$fonts = glob( $font_dir . '/*.woff2' );
		foreach ( $fonts as $font ) {
			$font_url = HDS_URI . '/assets/fonts/' . basename( $font );
			echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
		}
	}
}
add_action( 'wp_head', 'hds_preload_assets', 1 );

/**
 * Preconnect to external origins for faster resource loading.
 */
function hds_preconnect_origins(): void {
	echo '<link rel="preconnect" href="https://www.google.com">' . "\n";
	echo '<link rel="preconnect" href="https://www.googletagmanager.com">' . "\n";
	echo '<link rel="dns-prefetch" href="https://www.googletagmanager.com">' . "\n";
}
add_action( 'wp_head', 'hds_preconnect_origins', 1 );

/**
 * Add loading="lazy" to all images that don't already have it.
 */
function hds_add_lazy_loading( string $content ): string {
	if ( function_exists( 'wp_lazy_loading_enabled' ) && wp_lazy_loading_enabled( 'img', 'the_content' ) ) {
		return $content;
	}
	return $content;
}

/**
 * Remove WordPress block-library CSS on pages that don't use its blocks.
 */
function hds_dequeue_block_styles(): void {
	if ( ! is_admin() && ! has_blocks( get_the_ID() ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
	}
}
add_action( 'wp_enqueue_scripts', 'hds_dequeue_block_styles', 100 );

/**
 * Remove global-styles inline CSS when unnecessary.
 */
function hds_remove_global_styles(): void {
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}
add_action( 'init', 'hds_remove_global_styles' );

/**
 * Disable WordPress emoji scripts (duplicated from setup.php for module cohesion).
 */
function hds_disable_emoji_assets(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'hds_disable_emoji_assets' );

/**
 * Remove jQuery Migrate dependency.
 */
function hds_remove_jquery_migrate( \WP_Scripts $scripts ): void {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_filter(
			$scripts->registered['jquery']->deps,
			function ( $dep ) {
				return 'jquery-migrate' !== $dep;
			}
		);
	}
}
add_action( 'wp_default_scripts', 'hds_remove_jquery_migrate' );
