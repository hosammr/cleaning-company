<?php
/**
 * Helper functions.
 *
 * @package HDS
 */

/**
 * Get header template part.
 */
function get_header( $name = null, $args = [] ): void {
	do_action( 'get_header', $name, $args );
	$templates = [];
	if ( $name ) {
		$templates[] = "parts/header-{$name}.php";
	}
	$templates[] = 'parts/header.php';
	locate_template( $templates, true );
}

/**
 * Get footer template part.
 */
function get_footer( $name = null, $args = [] ): void {
	do_action( 'get_footer', $name, $args );
	$templates = [];
	if ( $name ) {
		$templates[] = "parts/footer-{$name}.php";
	}
	$templates[] = 'parts/footer.php';
	locate_template( $templates, true );
}

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
 * Get company address (if set).
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
