<?php
/**
 * Theme setup — image sizes, theme supports, disabling unused features.
 *
 * @package HDS
 */

/**
 * Register custom image sizes.
 */
function hds_register_image_sizes(): void {
	add_image_size( 'hds-card',    400,  300, true );
	add_image_size( 'hds-content', 800,  600, false );
	add_image_size( 'hds-hero',   1600,  900, true );

	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
}
add_action( 'after_setup_theme', 'hds_register_image_sizes', 20 );

/**
 * Add custom image sizes to the editor size selector.
 */
function hds_add_image_sizes_to_editor( array $sizes ): array {
	return array_merge( $sizes, [
		'hds-card'    => __( 'HDS Card', 'hds' ),
		'hds-content' => __( 'HDS Content', 'hds' ),
		'hds-hero'    => __( 'HDS Hero', 'hds' ),
	] );
}
add_filter( 'image_size_names_choose', 'hds_add_image_sizes_to_editor' );

/**
 * Disable unused WordPress features.
 */
function hds_disable_unused_features(): void {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'wp_resource_hints', 'hds_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'hds_disable_unused_features' );

/**
 * Remove unnecessary DNS prefetch entries.
 */
function hds_remove_dns_prefetch( array $hints, string $relation_type ): array {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_filter( $hints, function ( $hint ) {
			return strpos( $hint, 'fonts.googleapis.com' ) === false
				&& strpos( $hint, 's.w.org' ) === false;
		} );
	}
	return $hints;
}

/**
 * Remove WordPress version generator.
 */
function hds_remove_version_generator(): void {
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );
}
add_action( 'init', 'hds_remove_version_generator' );

/**
 * Remove RSD, wlwmanifest, shortlink from head.
 */
function hds_remove_head_links(): void {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'hds_remove_head_links' );

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable Gutenberg full-screen editor by default.
 */
function hds_disable_fullscreen_editor(): void {
	$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } };";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'hds_disable_fullscreen_editor' );

/**
 * Limit post revisions to 10.
 */
function hds_limit_revisions(): void {
	if ( ! defined( 'WP_POST_REVISIONS' ) || WP_POST_REVISIONS === true || WP_POST_REVISIONS < 0 ) {
		add_filter( 'wp_revisions_to_keep', function ( $num, $post ) {
			return 10;
		}, 10, 2 );
	}
}
add_action( 'init', 'hds_limit_revisions' );

/**
 * Disable attachment year/month folder structure for cleaner URLs.
 */
function hds_attachment_folder_structure(): bool {
	return true;
}
add_filter( 'pre_option_uploads_use_yearmonth_folders', 'hds_attachment_folder_structure' );

/**
 * Allow SVG uploads (safely).
 */
function hds_allow_svg_uploads( array $mimes ): array {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'hds_allow_svg_uploads' );

/**
 * Sanitize SVG during upload.
 */
function hds_sanitize_svg( array $file ): array {
	if ( 'image/svg+xml' === $file['type'] ) {
		$svg = file_get_contents( $file['tmp_name'] );

		$svg = preg_replace( '/<script[\s\S]*?>[\s\S]*?<\/script>/i', '', $svg );
		$svg = preg_replace( '/<(\w+)\s[^>]*on\w+\s*=\s*"[^"]*"[^>]*>/i', '', $svg );

		file_put_contents( $file['tmp_name'], $svg );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'hds_sanitize_svg' );

/**
 * Extend allowed block types to include patterns.
 */
function hds_allowed_block_types( $allowed_block_types, $block_editor_context ): array {
	if ( ! is_array( $allowed_block_types ) ) {
		$allowed_block_types = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		$allowed_block_types = array_keys( $allowed_block_types );
	}

	return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'hds_allowed_block_types', 10, 2 );

/**
 * Theme activation hook — flush rewrite rules.
 */
function hds_theme_activation(): void {
	hds_register_testimonial_cpt();
	hds_register_vacancy_cpt();
}
add_action( 'after_switch_theme', 'hds_theme_activation' );
