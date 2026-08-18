<?php
/**
 * Security utilities — sanitization and output escaping helpers.
 *
 * @package HDS
 */

/**
 * Escape phone number for tel: link (remove all non-digits except leading +).
 *
 * @param string $value Phone number to escape.
 * @return string Escaped phone number.
 */
function hds_esc_tel( string $value ): string {
	return esc_attr( preg_replace( '/[^\d+]/', '', $value ) );
}
