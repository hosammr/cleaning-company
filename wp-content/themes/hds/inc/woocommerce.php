<?php
/**
 * WooCommerce foundation.
 *
 * Declares theme compatibility, registers template override paths,
 * body classes, and minimal cart/checkout hooks.
 * Does NOT customize product pages or checkout UI.
 *
 * @package HDS
 */

/**
 * Declare WooCommerce theme support.
 */
function hds_woocommerce_support(): void {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'hds_woocommerce_support' );

/**
 * Remove default WooCommerce styles if we want full control.
 * Commented out by default — enable when implementing custom WC CSS.
 */
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Wrap WooCommerce content in our container.
 */
function hds_woocommerce_before_main_content(): void {
	echo '<div class="container">';
}

function hds_woocommerce_after_main_content(): void {
	echo '</div>';
}
add_action( 'woocommerce_before_main_content', 'hds_woocommerce_before_main_content', 5 );
add_action( 'woocommerce_after_main_content', 'hds_woocommerce_after_main_content', 5 );

/**
 * Add WooCommerce body classes.
 */
function hds_woocommerce_body_classes( array $classes ): array {
	if ( is_woocommerce() ) {
		$classes[] = 'is-woocommerce';
	}

	if ( is_shop() ) {
		$classes[] = 'is-shop';
	}

	if ( is_product() ) {
		$classes[] = 'is-single-product';
	}

	if ( is_cart() ) {
		$classes[] = 'is-cart';
	}

	if ( is_checkout() ) {
		$classes[] = 'is-checkout';
	}

	return $classes;
}
add_filter( 'body_class', 'hds_woocommerce_body_classes' );

/**
 * Change WooCommerce breadcrumb defaults.
 */
function hds_woocommerce_breadcrumb_defaults( array $defaults ): array {
	$defaults['delimiter']   = '';
	$defaults['wrap_before'] = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Kruimelpad', 'hds' ) . '"><ol class="woocommerce-breadcrumb" itemprop="breadcrumb">';
	$defaults['wrap_after']  = '</ol></nav>';
	$defaults['before']      = '<li>';
	$defaults['after']       = '</li>';
	$defaults['home']        = __( 'Home', 'hds' );

	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'hds_woocommerce_breadcrumb_defaults' );

/**
 * Change products per page.
 */
function hds_woocommerce_products_per_page( int $per_page ): int {
	return HDS_Config::get( 'woocommerce.products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'hds_woocommerce_products_per_page', 20 );

/**
 * Register WooCommerce template override directory.
 *
 * When WC templates need customization, place them in:
 *   wp-content/themes/hds/woocommerce/
 *
 * This function just documents the convention.
 */
function hds_woocommerce_template_path(): string {
	return 'woocommerce/';
}

/**
 * Hook: add HDS button class to WooCommerce buttons.
 */
function hds_woocommerce_button_classes(): void {
	add_filter( 'woocommerce_product_single_add_to_cart_text', function () {
		return __( 'In winkelwagen', 'hds' );
	} );
}
add_action( 'init', 'hds_woocommerce_button_classes' );

/**
 * Ensure cart and checkout pages use the theme's header/footer.
 */
function hds_woocommerce_template_loader(): void {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		add_filter( 'woocommerce_get_header', '__return_false' );
	}
}
add_action( 'template_redirect', 'hds_woocommerce_template_loader' );
