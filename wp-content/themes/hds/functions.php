<?php
/**
 * Theme functions and definitions.
 *
 * @package HDS
 */

if ( ! defined( 'HDS_VERSION' ) ) {
	define( 'HDS_VERSION', '1.0.0' );
}

if ( ! defined( 'HDS_DIR' ) ) {
	define( 'HDS_DIR', get_template_directory() );
}

if ( ! defined( 'HDS_URI' ) ) {
	define( 'HDS_URI', get_template_directory_uri() );
}

if ( ! defined( 'HDS_ASSETS_URI' ) ) {
	define( 'HDS_ASSETS_URI', HDS_URI . '/assets' );
}

if ( ! defined( 'HDS_BUILD_URI' ) ) {
	define( 'HDS_BUILD_URI', HDS_URI . '/build' );
}

/**
 * Theme setup.
 */
function hds_setup(): void {
	load_theme_textdomain( 'hds', HDS_DIR . '/languages' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', [
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
		'style',
		'script',
	] );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', [
		'height'      => 80,
		'width'       => 280,
		'flex-width'  => true,
		'flex-height' => true,
		'unlink-homepage-logo' => false,
	] );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus( [
		'primary'         => __( 'Hoofdmenu', 'hds' ),
		'footer-services' => __( 'Footer - Diensten', 'hds' ),
		'footer-about'    => __( 'Footer - Over HDS', 'hds' ),
		'footer-airfixr'  => __( 'Footer - Luchtreiniging', 'hds' ),
		'footer-legal'    => __( 'Footer - Juridisch', 'hds' ),
	] );

	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'hds_setup' );

/**
 * Register block styles.
 */
function hds_register_block_styles(): void {
	register_block_style( 'core/button', [
		'name'  => 'secondary',
		'label' => __( 'Secondary', 'hds' ),
	] );
	register_block_style( 'core/button', [
		'name'  => 'cta',
		'label' => __( 'CTA', 'hds' ),
	] );
	register_block_style( 'core/group', [
		'name'  => 'card',
		'label' => __( 'Card', 'hds' ),
	] );
	register_block_style( 'core/group', [
		'name'  => 'banner',
		'label' => __( 'Banner', 'hds' ),
	] );
	register_block_style( 'core/list', [
		'name'  => 'icon-list',
		'label' => __( 'Icon List', 'hds' ),
	] );
	register_block_style( 'core/list', [
		'name'  => 'no-bullet',
		'label' => __( 'No Bullets', 'hds' ),
	] );

	register_block_style( 'core/separator', [
		'name'  => 'dots',
		'label' => __( 'Dots', 'hds' ),
	] );
}
add_action( 'init', 'hds_register_block_styles' );

/**
 * Register block pattern categories.
 */
function hds_register_block_pattern_categories(): void {
	register_block_pattern_category( 'hds-patterns', [
		'label' => __( 'HDS Patronen', 'hds' ),
	] );
}
add_action( 'init', 'hds_register_block_pattern_categories' );

/**
 * Register custom templates.
 */
function hds_register_custom_templates(): void {
	$templates = [
		'service'          => __( 'Service', 'hds' ),
		'contact'          => __( 'Contact', 'hds' ),
		'quote'            => __( 'Offerte Aanvragen', 'hds' ),
		'category-landing' => __( 'Category Landing', 'hds' ),
		'about'            => __( 'About', 'hds' ),
		'legal'            => __( 'Legal', 'hds' ),
		'faq'              => __( 'FAQ', 'hds' ),
		'bedankt'          => __( 'Bedankt', 'hds' ),
		'luchtreiniging'   => __( 'Luchtreiniging Landing', 'hds' ),
		'reviews'          => __( 'Reviews', 'hds' ),
	];

	foreach ( $templates as $slug => $label ) {
		$template_file = 'page-templates/page-' . $slug . '.php';
		if ( file_exists( HDS_DIR . '/' . $template_file ) ) {
			register_block_template( 'hds//' . $slug, [
				'title'       => $label,
				'description' => sprintf( __( 'Template voor %s pagina.', 'hds' ), $label ),
				'content'     => file_get_contents( HDS_DIR . '/' . $template_file ),
			] );
		}
	}
}
add_action( 'init', 'hds_register_custom_templates' );

/**
 * Include functionality modules.
 */
require_once HDS_DIR . '/inc/setup.php';
require_once HDS_DIR . '/inc/config.php';
require_once HDS_DIR . '/inc/helpers.php';
require_once HDS_DIR . '/inc/sanitize.php';
require_once HDS_DIR . '/inc/validation.php';
require_once HDS_DIR . '/inc/routing.php';
require_once HDS_DIR . '/inc/error-handler.php';
require_once HDS_DIR . '/inc/walker-nav.php';
require_once HDS_DIR . '/inc/seo.php';
require_once HDS_DIR . '/inc/analytics.php';
require_once HDS_DIR . '/inc/components.php';
require_once HDS_DIR . '/inc/asset-loader.php';
require_once HDS_DIR . '/inc/security.php';
require_once HDS_DIR . '/inc/performance.php';
require_once HDS_DIR . '/inc/woocommerce.php';
require_once HDS_DIR . '/inc/cpts.php';
require_once HDS_DIR . '/inc/custom-fields.php';
require_once HDS_DIR . '/inc/customizer.php';
require_once HDS_DIR . '/inc/content-models.php';
require_once HDS_DIR . '/inc/editor-config.php';
require_once HDS_DIR . '/inc/meta-panels.php';
require_once HDS_DIR . '/inc/patterns.php';
require_once HDS_DIR . '/inc/blocks.php';
require_once HDS_DIR . '/inc/schema.php';
require_once HDS_DIR . '/inc/service-functions.php';
