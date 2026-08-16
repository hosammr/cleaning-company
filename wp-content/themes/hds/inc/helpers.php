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
	return $phone ?: '0164-652846';
}

/**
 * Get company email.
 */
function hds_get_email(): string {
	$email = get_theme_mod( 'hds_email', '' );
	return $email ?: 'info@helderduidelijkschoon.nl';
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
	$phone   = $phone ?: hds_get_phone();
	$url     = 'tel:' . hds_esc_tel( $phone );
	$text    = esc_html( $phone );
	$attr_str = 'aria-label="' . esc_attr( sprintf( __( 'Bel %s', 'hds' ), $phone ) ) . '"';
	foreach ( $attrs as $key => $val ) {
		$attr_str .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
	}
	return sprintf( '<a href="%s" %s>%s</a>', esc_url( $url ), $attr_str, $text );
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
 * Referenties, Downloads, Offerte aanvragen.
 *
 * @param array $args wp_nav_menu() arguments.
 */
function hds_footer_about_fallback( array $args ): void {
	$links = [
		home_url( '/over-hds/' )                => __( 'Over HDS', 'hds' ),
		home_url( '/kwaliteit-en-veiligheid/' ) => __( 'Kwaliteit & Veiligheid', 'hds' ),
		home_url( '/referenties/' )             => __( 'Referenties', 'hds' ),
		home_url( '/downloads/' )               => __( 'Downloads', 'hds' ),
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
