<?php
/**
 * Analytics infrastructure hooks.
 *
 * Provides hooks for GA4, Google Tag Manager, and custom event tracking.
 * No tracking IDs are hardcoded — they are loaded from wp-config.php
 * constants (HDS_GA4_ID, HDS_GTM_ID).
 *
 * Events: form submission, phone click, email click, file download,
 * WooCommerce purchase/add-to-cart.
 *
 * @package HDS
 */

/**
 * Output GTM container snippet in <head>.
 */
function hds_output_gtm_head(): void {
	$gtm_id = HDS_Config::gtm_id();
	if ( ! $gtm_id ) {
		return;
	}
	?>
	<script>
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');
	</script>
	<?php
}
add_action( 'wp_head', 'hds_output_gtm_head', 1 );

/**
 * Output GTM noscript fallback after <body>.
 */
function hds_output_gtm_body(): void {
	$gtm_id = HDS_Config::gtm_id();
	if ( ! $gtm_id ) {
		return;
	}
	printf(
		'<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>',
		esc_attr( $gtm_id )
	);
}
add_action( 'wp_body_open', 'hds_output_gtm_body', 1 );

/**
 * Output GA4 gtag snippet (falls back if GTM not active).
 */
function hds_output_ga4(): void {
	$ga4_id = HDS_Config::ga4_id();
	if ( ! $ga4_id || HDS_Config::gtm_id() ) {
		return; // Don't double-load — GTM already handles GA4
	}
	?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js( $ga4_id ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', '<?php echo esc_js( $ga4_id ); ?>', { 'anonymize_ip': true });
	</script>
	<?php
}
add_action( 'wp_head', 'hds_output_ga4', 2 );

/**
 * Push a custom event to the dataLayer (GTM).
 */
function hds_track_event( string $event_name, array $event_data = [] ): void {
	if ( ! HDS_Config::gtm_id() && ! HDS_Config::ga4_id() ) {
		return;
	}

	$data = array_merge( [ 'event' => $event_name ], $event_data );

	echo '<script>dataLayer = window.dataLayer || []; dataLayer.push(' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT ) . ');</script>' . "\n";
}
add_action( 'wp_footer', function () {
	/**
	 * Allow plugins/themes to push events before the closing body tag.
	 */
	do_action( 'hds_analytics_events' );
}, 99 );

/**
 * Track phone clicks via data attribute hook.
 */
function hds_phone_click_tracking( string $output, string $phone ): string {
	if ( ! HDS_Config::gtm_id() ) {
		return $output;
	}
	return str_replace( '<a ', '<a data-event="phone_click" data-phone="' . esc_attr( $phone ) . '" ', $output );
}
add_filter( 'hds_phone_link', 'hds_phone_click_tracking', 10, 2 );

/**
 * Track email clicks via data attribute hook.
 */
function hds_email_click_tracking( string $output, string $email ): string {
	if ( ! HDS_Config::gtm_id() ) {
		return $output;
	}
	return str_replace( '<a ', '<a data-event="email_click" data-email="' . esc_attr( $email ) . '" ', $output );
}
add_filter( 'hds_email_link', 'hds_email_click_tracking', 10, 2 );

/**
 * Track file downloads (applied to PDF links).
 */
function hds_download_tracking( string $content ): string {
	if ( ! HDS_Config::gtm_id() ) {
		return $content;
	}

	return preg_replace_callback(
		'/<a\s([^>]*?)href=["\']([^"\']*\.pdf)["\']([^>]*?)>/i',
		function ( $matches ) {
			$attrs = $matches[1] . ' ' . $matches[3];
			if ( ! str_contains( $attrs, 'data-event' ) ) {
				$attrs .= ' data-event="file_download" data-file="' . esc_attr( basename( $matches[2] ) ) . '"';
			}
			return '<a ' . $attrs . ' href="' . esc_url( $matches[2] ) . '">';
		},
		$content
	);
}
add_filter( 'the_content', 'hds_download_tracking' );
add_filter( 'hds_downloads_output', 'hds_download_tracking' );

/**
 * WooCommerce add-to-cart event tracking.
 */
function hds_woocommerce_add_to_cart_event(): void {
	hds_track_event( 'add_to_cart', [
		'product_id'   => get_the_ID(),
		'product_name' => get_the_title(),
	] );
}

/**
 * WooCommerce purchase event tracking.
 */
function hds_woocommerce_purchase_event( int $order_id ): void {
	if ( ! function_exists( 'wc_get_order' ) ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	hds_track_event( 'purchase', [
		'transaction_id' => $order->get_order_number(),
		'value'          => $order->get_total(),
		'currency'       => $order->get_currency(),
		'items'          => array_map( function ( $item ) {
			return [
				'item_name' => $item->get_name(),
				'quantity'  => $item->get_quantity(),
				'price'     => $item->get_total() / max( $item->get_quantity(), 1 ),
			];
		}, $order->get_items() ),
	] );
}
add_action( 'woocommerce_thankyou', 'hds_woocommerce_purchase_event', 10, 1 );

/**
 * Search tracking — push event on search.
 */
function hds_search_tracking(): void {
	if ( is_search() && get_search_query() ) {
		hds_track_event( 'search', [
			'search_term' => get_search_query(),
		] );
	}
}
add_action( 'wp_footer', 'hds_search_tracking', 50 );
