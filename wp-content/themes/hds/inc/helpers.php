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
 * Get a value from theme.json custom section.
 */
function hds_get_theme_json_var( string $key, $default = '' ) {
	$config = WP_Theme_JSON_Resolver::get_merged_data()->get_raw_data();
	$path   = explode( '.', $key );
	$value  = $config;

	foreach ( $path as $segment ) {
		if ( ! isset( $value[ $segment ] ) ) {
			return $default;
		}
		$value = $value[ $segment ];
	}

	return $value ?: $default;
}

/**
 * Check if the current request is for the frontend site (not REST/Admin).
 */
function hds_is_frontend(): bool {
	return ! is_admin() && ! wp_doing_ajax() && ! defined( 'REST_REQUEST' );
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
 * Check if a section has content before rendering.
 * Implements ADR D-015 — hide empty conditional sections.
 */
function hds_section_has_content( string $content, bool $is_front_page = false ): bool {
	$stripped = wp_strip_all_tags( $content );
	$stripped = trim( $stripped );

	if ( $stripped === '' ) {
		return false;
	}

	if ( $is_front_page && $stripped === get_bloginfo( 'name' ) ) {
		return false;
	}

	return true;
}

/**
 * Get a responsive image HTML string.
 */
function hds_get_image( int $attachment_id, string $size = 'large', array $attrs = [] ): string {
	if ( ! $attachment_id ) {
		return '';
	}
	$default_attrs = [ 'loading' => 'lazy', 'decoding' => 'async' ];
	$attrs = array_merge( $default_attrs, $attrs );
	return wp_get_attachment_image( $attachment_id, $size, false, $attrs );
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
 * Format a date string using WordPress date format.
 */
function hds_format_date( string $date, string $format = '' ): string {
	$format    = $format ?: get_option( 'date_format' );
	$timestamp = strtotime( $date );
	if ( ! $timestamp ) {
		return $date;
	}
	return date_i18n( $format, $timestamp );
}

/**
 * Format a currency value (EUR by default).
 */
function hds_format_currency( float $amount, string $currency = 'EUR' ): string {
	if ( function_exists( 'wc_price' ) ) {
		return wc_price( $amount );
	}
	return '&euro;' . number_format_i18n( $amount, 2 );
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
 * Get a social media URL from customizer.
 */
function hds_get_social_url( string $platform ): string {
	return get_theme_mod( "hds_{$platform}_url", '' );
}

/**
 * Get the company name.
 */
function hds_get_company_name(): string {
	return get_bloginfo( 'name' );
}

/**
 * Get the current full URL.
 */
function hds_get_current_url(): string {
	global $wp;
	return home_url( $wp->request );
}

/**
 * Check if we are on a specific page by slug.
 */
function hds_is_page_slug( string $slug ): bool {
	return is_page( $slug );
}
