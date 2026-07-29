<?php
/**
 * WooCommerce foundation.
 *
 * Declares theme compatibility, WC body classes, breadcrumb integration,
 * product card styling, cart/checkout hooks, sale badges, stock display,
 * mini-cart support, and template override conventions.
 *
 * Gracefully degrades when WooCommerce is absent — all registrations
 * are guarded by `class_exists( 'WooCommerce' )`.
 *
 * @package HDS
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

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

	if ( is_account_page() ) {
		$classes[] = 'is-account';
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
 */
function hds_woocommerce_template_path(): string {
	return 'woocommerce/';
}

/**
 * Add HDS button class to WooCommerce buttons and Dutch labels.
 */
function hds_woocommerce_button_classes(): void {
	add_filter( 'woocommerce_product_single_add_to_cart_text', function () {
		return __( 'In winkelwagen', 'hds' );
	} );

	add_filter( 'woocommerce_product_add_to_cart_text', function () {
		return __( 'In winkelwagen', 'hds' );
	} );
}
add_action( 'init', 'hds_woocommerce_button_classes' );

/**
 * Ensure cart, checkout, and account pages use the theme's header/footer.
 */
function hds_woocommerce_template_loader(): void {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		add_filter( 'woocommerce_get_header', '__return_false' );
	}
}
add_action( 'template_redirect', 'hds_woocommerce_template_loader' );

/**
 * Add sale badge wrapper for consistent styling.
 */
function hds_woocommerce_sale_flash( string $html, \WP_Post $post, \WC_Product $product ): string {
	return '<span class="onsale">' . esc_html__( 'Aanbieding', 'hds' ) . '</span>';
}
add_filter( 'woocommerce_sale_flash', 'hds_woocommerce_sale_flash', 10, 3 );

/**
 * Add stock status display with Dutch labels.
 */
function hds_woocommerce_stock_html( string $html, \WC_Product $product ): string {
	if ( ! $product->is_in_stock() ) {
		return '<p class="stock out-of-stock">' . esc_html__( 'Niet op voorraad', 'hds' ) . '</p>';
	}

	if ( $product->managing_stock() ) {
		$stock_amount = $product->get_stock_quantity();
		return '<p class="stock in-stock">'
			. esc_html( sprintf( __( '%s op voorraad', 'hds' ), $stock_amount ) )
			. '</p>';
	}

	return '<p class="stock in-stock">' . esc_html__( 'Op voorraad', 'hds' ) . '</p>';
}
add_filter( 'woocommerce_get_stock_html', 'hds_woocommerce_stock_html', 10, 2 );

/**
 * Customise checkout fields — Dutch labels, simplified layout.
 */
function hds_woocommerce_checkout_fields( array $fields ): array {
	$fields['billing']['billing_company']['label']       = __( 'Bedrijfsnaam', 'hds' );
	$fields['billing']['billing_postcode']['label']       = __( 'Postcode', 'hds' );
	$fields['billing']['billing_city']['label']           = __( 'Plaats', 'hds' );
	$fields['billing']['billing_phone']['label']          = __( 'Telefoonnummer', 'hds' );

	unset( $fields['billing']['billing_address_2'] );
	unset( $fields['billing']['billing_state'] );

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'hds_woocommerce_checkout_fields' );

/**
 * Add privacy policy link to checkout.
 */
function hds_woocommerce_checkout_privacy(): void {
	if ( function_exists( 'wc_privacy_policy_page_id' ) && wc_privacy_policy_page_id() ) {
		return;
	}
	echo '<p class="checkout-privacy-note">'
		. esc_html__( 'Uw persoonlijke gegevens worden gebruikt om uw bestelling te verwerken. ', 'hds' )
		. '<a href="' . esc_url( home_url( '/privacyverklaring/' ) ) . '">'
		. esc_html__( 'Privacyverklaring', 'hds' )
		. '</a></p>';
}
add_action( 'woocommerce_review_order_before_submit', 'hds_woocommerce_checkout_privacy' );

/**
 * Add cart fragments for AJAX cart updates.
 */
function hds_woocommerce_cart_fragments( array $fragments ): array {
	ob_start();
	?>
	<span class="header-cart-count">
		<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
	</span>
	<?php
	$fragments['.header-cart-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'hds_woocommerce_cart_fragments' );

/**
 * Customise the empty cart message.
 */
function hds_woocommerce_empty_cart_message(): string {
	return '<p class="cart-empty">'
		. esc_html__( 'Uw winkelwagen is leeg.', 'hds' )
		. '</p><p><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="btn btn--primary">'
		. esc_html__( 'Verder winkelen', 'hds' )
		. '</a></p>';
}
add_filter( 'wc_empty_cart_message', 'hds_woocommerce_empty_cart_message' );

/**
 * Add structured product data enhancements.
 */
function hds_woocommerce_structured_data_product( array $markup ): array {
	if ( isset( $markup['offers'] ) ) {
		$markup['offers']['priceCurrency'] = 'EUR';
	}

	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'hds_woocommerce_structured_data_product' );
