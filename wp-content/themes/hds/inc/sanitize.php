<?php
/**
 * Security utilities — nonces, sanitization, and output escaping helpers.
 *
 * Provides reusable helper functions for secure form handling,
 * data sanitization, and consistent output escaping across the theme.
 *
 * @package HDS
 */

/**
 * Generate a nonce field for HDS forms.
 */
function hds_nonce_field( string $action = 'hds_form', string $name = '_hds_nonce' ): string {
	return wp_nonce_field( $action, $name, true, false );
}

/**
 * Verify an HDS nonce.
 */
function hds_verify_nonce( string $nonce, string $action = 'hds_form' ): bool {
	if ( empty( $nonce ) ) {
		return false;
	}
	return (bool) wp_verify_nonce( $nonce, $action );
}

/**
 * Get a nonce for AJAX or REST usage.
 */
function hds_get_ajax_nonce( string $action = 'hds_ajax' ): string {
	return wp_create_nonce( $action );
}

/**
 * Sanitize a text field (single line).
 */
function hds_sanitize_text( string $value ): string {
	return sanitize_text_field( $value );
}

/**
 * Sanitize a textarea (multiline, allowed HTML).
 */
function hds_sanitize_textarea( string $value ): string {
	return sanitize_textarea_field( $value );
}

/**
 * Sanitize an email address.
 */
function hds_sanitize_email( string $value ): string {
	return sanitize_email( $value );
}

/**
 * Sanitize a URL.
 */
function hds_sanitize_url( string $value ): string {
	return esc_url_raw( $value );
}

/**
 * Sanitize an array of text fields.
 */
function hds_sanitize_text_array( array $values ): array {
	return array_map( 'sanitize_text_field', $values );
}

/**
 * Sanitize a file path (remove traversal, null bytes).
 */
function hds_sanitize_path( string $value ): string {
	$value = str_replace( [ "\0", '..', './' ], '', $value );
	return wp_normalize_path( $value );
}

/**
 * Sanitize a phone number — keep digits, +, -, (), spaces.
 */
function hds_sanitize_phone( string $value ): string {
	return preg_replace( '/[^\d+\-() ]/', '', $value );
}

/**
 * Escape a value for safe HTML output.
 */
function hds_esc_html( string $value ): string {
	return esc_html( $value );
}

/**
 * Escape a value for safe HTML attribute output.
 */
function hds_esc_attr( string $value ): string {
	return esc_attr( $value );
}

/**
 * Escape a URL.
 */
function hds_esc_url( string $value ): string {
	return esc_url( $value );
}

/**
 * Escape content with limited HTML (a, b, strong, em, br, span).
 */
function hds_esc_content( string $value ): string {
	return wp_kses(
		$value,
		[
			'a'      => [ 'href' => [], 'title' => [], 'rel' => [], 'target' => [], 'class' => [] ],
			'b'      => [],
			'strong' => [],
			'em'     => [],
			'i'      => [],
			'br'     => [],
			'span'   => [ 'class' => [], 'aria-hidden' => [] ],
			'p'      => [ 'class' => [] ],
		]
	);
}

/**
 * Escape phone number for tel: link (remove all non-digits except leading +).
 */
function hds_esc_tel( string $value ): string {
	return esc_attr( preg_replace( '/[^\d+]/', '', $value ) );
}

/**
 * Validate that a value is a positive integer.
 */
function hds_validate_positive_int( $value ): bool {
	return ctype_digit( (string) $value ) && (int) $value > 0;
}

/**
 * Validate a Dutch postcode (1234 AB format).
 */
function hds_validate_dutch_postcode( string $value ): bool {
	return (bool) preg_match( '/^[1-9]\d{3}\s?[A-Z]{2}$/i', trim( $value ) );
}

/**
 * Validate a star rating (1-5).
 */
function hds_validate_rating( $value ): bool {
	$int = (int) $value;
	return $int >= 1 && $int <= 5;
}

/**
 * Output an escaped string (echo wrapper for template files).
 */
function hds_e( string $value ): void {
	echo esc_html( $value );
}
