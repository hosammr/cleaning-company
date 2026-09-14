<?php
/**
 * Helper functions.
 *
 * @package HDS
 */

/**
 * Get company phone number.
 */
function hds_get_phone(): string {
	$phone = get_theme_mod( 'hds_phone', '' );
	return $phone ?: '0622272811';
}

/**
 * Get company fixed-line phone number (secondary contact number).
 */
function hds_get_phone_secondary(): string {
	$phone = get_theme_mod( 'hds_phone_secondary', '' );
	return $phone ?: '0502340009';
}

/**
 * Get the company phone number in international format (+31 …) for tel: links.
 *
 * Converts a national Dutch number (e.g. "0622272811") to the E.164-style
 * international form used in `tel:` links (e.g. "+31622272811").
 *
 * @param string $phone Optional number to convert; defaults to the header phone.
 * @return string International phone number, or '' when empty.
 */
function hds_get_phone_intl( string $phone = '' ): string {
	$phone = preg_replace( '/[^\d+]/', '', $phone ?: hds_get_phone() );

	if ( '' === $phone ) {
		return '';
	}

	if ( str_starts_with( $phone, '+' ) ) {
		return $phone;
	}

	if ( str_starts_with( $phone, '0' ) ) {
		return '+31' . substr( $phone, 1 );
	}

	return '+31' . $phone;
}

/**
 * Get a human-readable phone number for display (e.g. "+31 6 2227 2811").
 *
 * @param string $phone Optional number to format; defaults to the header phone.
 * @return string Formatted display phone number.
 */
function hds_get_phone_display( string $phone = '' ): string {
	$digits = ltrim( preg_replace( '/[^\d]/', '', $phone ?: hds_get_phone_intl() ), '+' );

	// Format Dutch numbers as "+31 6 2227 2811".
	if ( str_starts_with( $digits, '31' ) && 11 === strlen( $digits ) ) {
		return '+31 ' . $digits[2] . ' ' . implode( ' ', str_split( substr( $digits, 3 ), 4 ) );
	}

	return $phone ?: hds_get_phone();
}

/**
 * Format a Dutch phone number for display (e.g. "06 2227 2811", "050 234 0009").
 *
 * @param string $raw Phone number in any national format.
 * @return string Nationally formatted phone number, or the input when it cannot be formatted.
 */
function hds_format_phone( string $raw ): string {
	$digits = preg_replace( '/[^\d]/', '', $raw );

	if ( 10 === strlen( $digits ) && str_starts_with( $digits, '06' ) ) {
		return preg_replace( '/^(\d{2})(\d{4})(\d{4})$/', '$1 $2 $3', $digits );
	}

	if ( 10 === strlen( $digits ) ) {
		return preg_replace( '/^(\d{3})(\d{3})(\d{4})$/', '$1 $2 $3', $digits );
	}

	return $raw;
}

/**
 * Get company email.
 */
function hds_get_email(): string {
	$email = get_theme_mod( 'hds_email', '' );
	return $email ?: 'info@hamdounschoonmaak.nl';
}

/**
 * Get company address.
 */
function hds_get_address(): string {
	return get_theme_mod( 'hds_address', '' );
}

/**
 * Get postal code and city.
 */
function hds_get_postal_city(): string {
	return get_theme_mod( 'hds_postal_city', '' );
}

/**
 * Output breadcrumbs via template part.
 */
function hds_breadcrumbs(): void {
	get_template_part( 'parts/breadcrumbs' );
}

/**
 * Get asset version string (cache busting).
 *
 * Uses HDS_VERSION in dev (SCRIPT_DEBUG) or theme version.
 */
function hds_get_asset_version(): string {
	return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG
		? (string) time()
		: HDS_VERSION;
}

/**
 * Get the attachment ID for an image by its filename.
 *
 * Matches the exact basename first (e.g. "hamdoun-veilig-werken.webp"),
 * then falls back to the filename stem so renamed uploads (e.g.
 * "hamdoun-veilig-werken-1.png") still resolve.
 *
 * @param string $filename Image filename, with or without a path.
 * @return int Attachment ID, or 0 when no matching attachment exists.
 */
function hds_get_attachment_id_by_filename( string $filename ): int {
	if ( '' === $filename ) {
		return 0;
	}

	$basename = basename( $filename );
	$stem     = pathinfo( $basename, PATHINFO_FILENAME );

	$args = array(
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
		'meta_key'       => '_wp_attached_file', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'     => $basename,
		'meta_compare'   => 'LIKE',
	);

	$query = new \WP_Query( $args );

	if ( empty( $query->posts ) && '' !== $stem ) {
		$args['meta_value'] = $stem;
		$query              = new \WP_Query( $args );
	}

	if ( empty( $query->posts ) ) {
		return 0;
	}

	return (int) $query->posts[0];
}

/**
 * Get service pages ordered by `menu_order`.
 */
function hds_get_service_pages( int $count = 99 ): array {
	return get_posts( [
		'post_type'      => 'page',
		'posts_per_page' => $count,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-templates/page-service.php',
	] );
}

/**
 * Get a formatted phone link.
 */
function hds_get_phone_link( string $phone = '', array $attrs = [] ): string {
	$phone    = $phone ?: hds_get_phone();
	$display  = hds_format_phone( $phone );
	$url      = 'tel:' . hds_get_phone_intl( $phone );
	$attr_str = 'aria-label="' . esc_attr( sprintf( __( 'Bel %s', 'hds' ), $display ) ) . '"';
	foreach ( $attrs as $key => $val ) {
		$attr_str .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
	}
	return sprintf( '<a href="%s" %s>%s</a>', esc_url( $url ), $attr_str, esc_html( $display ) );
}

/**
 * Get a formatted email link.
 */
function hds_get_email_link( string $email = '', string $subject = '', array $attrs = [] ): string {
	$email = $email ?: hds_get_email();
	$url   = 'mailto:' . esc_attr( $email );
	if ( $subject ) {
		$url .= '?subject=' . rawurlencode( $subject );
	}
	$text = esc_html( $email );
	$attr_str = 'aria-label="' . esc_attr( sprintf( __( 'E-mail %s', 'hds' ), $email ) ) . '"';
	foreach ( $attrs as $key => $val ) {
		$attr_str .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
	}
	return sprintf( '<a href="%s" %s>%s</a>', esc_url( $url ), $attr_str, $text );
}

/**
 * Truncate a string without breaking words.
 */
function hds_truncate( string $text, int $length = 100, string $suffix = '...' ): string {
	$text = wp_strip_all_tags( $text );
	if ( mb_strlen( $text ) <= $length ) {
		return $text;
	}
	$truncated  = mb_substr( $text, 0, $length );
	$last_space = mb_strrpos( $truncated, ' ' );
	if ( $last_space ) {
		$truncated = mb_substr( $truncated, 0, $last_space );
	}
	return $truncated . $suffix;
}

/**
 * Fallback for the footer-about nav menu when no menu is assigned.
 *
 * Renders the Over HDS links: Over HDS, Kwaliteit & Veiligheid,
 * Referenties, Offerte aanvragen.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function hds_footer_about_fallback( array $args ): void {
	$links = [
		home_url( '/over-hds/' )                => __( 'Over Hamdoun Schoonmaak', 'hds' ),
		home_url( '/kwaliteit-en-veiligheid/' ) => __( 'Kwaliteit & Veiligheid', 'hds' ),
		home_url( '/referenties/' )             => __( 'Referenties', 'hds' ),
		home_url( '/veelgestelde-vragen/' )     => __( 'Veelgestelde Vragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )       => __( 'Offerte aanvragen', 'hds' ),
	];

	$menu_class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'footer-menu';

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $links as $url => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * Fallback for the footer-services nav menu when no menu is assigned.
 *
 * Renders the actual published service pages using the existing
 * hds_get_service_pages() helper.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function hds_footer_services_fallback( array $args ): void {
	$services      = hds_get_service_pages();
	$visible       = array_filter( $services, fn( $page ) => $page->post_status === 'publish' );
	$menu_class    = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'footer-menu';

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $visible as $service ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( get_permalink( $service ) ),
			esc_html( get_the_title( $service ) )
		);
	}
	if ( empty( $visible ) ) {
		echo '<li>' . esc_html__( 'Geen diensten beschikbaar', 'hds' ) . '</li>';
	}
	echo '</ul>';
}

/**
 * Fallback for the footer-legal nav menu when no menu is assigned.
 *
 * Renders standard legal page links.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function hds_footer_legal_fallback( array $args ): void {
	$links = [
		home_url( '/privacyverklaring/' )    => __( 'Privacyverklaring', 'hds' ),
		home_url( '/algemene-voorwaarden/' ) => __( 'Algemene voorwaarden', 'hds' ),
		home_url( '/cookiebeleid/' )         => __( 'Cookiebeleid', 'hds' ),
	];

	$menu_class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'footer-menu';

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $links as $url => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
