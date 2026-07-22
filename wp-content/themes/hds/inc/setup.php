<?php
/**
 * Theme setup — image sizes, theme supports, disable unused features.
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

function hds_remove_dns_prefetch( array $hints, string $relation_type ): array {
	if ( $relation_type === 'dns-prefetch' ) {
		return array_filter( $hints, function ( $hint ) {
			return strpos( $hint, 'fonts.googleapis.com' ) === false && strpos( $hint, 's.w.org' ) === false;
		} );
	}
	return $hints;
}

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
	hds_register_faq_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'hds_theme_activation' );
