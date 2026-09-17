<?php
/**
 * Frontend-only Dutch locale.
 *
 * The site is Dutch-only but WordPress is installed with an empty WPLANG
 * (en_US). This module forces the public frontend to use nl_NL without
 * touching database/settings configuration, while leaving the admin and
 * REST APIs on the stored locale.
 *
 * It also localizes the small set of WordPress-core strings and date
 * labels that the frontend actually renders (month/weekday names, search
 * and 404 document titles), so the public site is fully Dutch even
 * without the nl_NL language pack installed.
 *
 * @package HDS
 */

/**
 * Force the public frontend locale to Dutch.
 *
 * Admin and REST requests keep the configured locale so editing behavior
 * and the block editor are not unexpectedly changed.
 *
 * @param string $locale Current locale.
 * @return string
 */
function hds_frontend_locale( string $locale ): string {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $locale;
	}

	return 'nl_NL';
}
add_filter( 'locale', 'hds_frontend_locale', 10 );

/**
 * Translate the WordPress-core strings the frontend actually renders.
 *
 * With the frontend forced to nl_NL but no nl_NL core language pack
 * installed, WordPress falls back to English for 'default' domain
 * strings. Map the handful of user-facing strings the theme renders
 * (search and 404 document titles) to Dutch.
 *
 * @param string $translated Translated text.
 * @param string $text       Original text.
 * @param string $domain     Text domain.
 * @return string
 */
function hds_frontend_core_gettext( string $translated, string $text, string $domain ): string {
	if ( 'default' !== $domain || is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $translated;
	}

	$map = [
		'Search Results for &#8220;%s&#8221;' => 'Zoekresultaten voor &#8220;%s&#8221;',
		'Page not found'                      => 'Pagina niet gevonden',
	];

	return $map[ $text ] ?? $translated;
}
add_filter( 'gettext', 'hds_frontend_core_gettext', 10, 3 );

/**
 * Use Dutch month and weekday names for all date output.
 *
 * WP_Locale is instantiated before the theme loads, so its translation
 * lookups cannot be influenced by a theme gettext filter. Set the Dutch
 * names directly on the existing global so date_i18n() renders Dutch.
 */
function hds_apply_dutch_locale_data(): void {
	global $wp_locale;

	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ! $wp_locale instanceof WP_Locale ) {
		return;
	}

	$months = [ 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december' ];
	$abbrev = [ 'jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec' ];

	$month_keys = [];
	for ( $i = 1; $i <= 12; $i++ ) {
		$month_keys[] = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
	}

	$wp_locale->month          = array_combine( $month_keys, $months );
	$wp_locale->month_abbrev   = array_combine( $month_keys, $abbrev );
	$wp_locale->weekday        = [ 'zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag' ];
	$wp_locale->weekday_abbrev = [ 'zo', 'ma', 'di', 'wo', 'do', 'vr', 'za' ];
}
add_action( 'init', 'hds_apply_dutch_locale_data', 0 );

/**
 * Use a Dutch date format on the public frontend.
 *
 * Stored date strings are unchanged; only the display format switches
 * to day-month-year (e.g. "29 augustus 2026").
 *
 * @param string|false $format Configured date format.
 * @return string
 */
function hds_frontend_date_format( $format ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $format;
	}

	return 'j F Y';
}
add_filter( 'option_date_format', 'hds_frontend_date_format' );

/**
 * Use a Dutch time format on the public frontend.
 *
 * @param string|false $format Configured time format.
 * @return string
 */
function hds_frontend_time_format( $format ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $format;
	}

	return 'H:i';
}
add_filter( 'option_time_format', 'hds_frontend_time_format' );
