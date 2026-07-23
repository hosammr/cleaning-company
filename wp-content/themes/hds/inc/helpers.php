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
