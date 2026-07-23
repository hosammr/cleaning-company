<?php
/**
 * Centralized configuration layer.
 *
 * All theme, SEO, analytics, contact, WooCommerce, and feature flag
 * settings are defined here. No hardcoded values in templates.
 *
 * Usage: HDS_Config::get( 'analytics.gtm_id' )
 *
 * @package HDS
 */

class HDS_Config {

	/**
	 * All configuration values.
	 */
	private static array $config = [];

	/**
	 * Initialise configuration defaults.
	 */
	public static function init(): void {
		self::$config = [
			'analytics' => [
				'ga4_measurement_id' => defined( 'HDS_GA4_ID' ) ? HDS_GA4_ID : '',
				'gtm_container_id'   => defined( 'HDS_GTM_ID' ) ? HDS_GTM_ID : '',
				'ga4_enabled'        => ! empty( self::get_raw( 'analytics.ga4_measurement_id' ) ),
				'gtm_enabled'        => ! empty( self::get_raw( 'analytics.gtm_container_id' ) ),
				'anonymize_ip'       => true,
				'data_layer'         => 'dataLayer',
			],
			'seo' => [
				'home_title'           => get_bloginfo( 'name' ) . ' — ' . get_bloginfo( 'description' ),
				'title_separator'      => ' | ',
				'meta_description'     => get_bloginfo( 'description' ),
				'og_image_default'     => HDS_URI . '/screenshot.png',
				'twitter_handle'       => '',
				'facebook_app_id'      => '',
				'google_site_verification' => '',
				'noindex_search'       => true,
				'noindex_404'          => true,
				'noindex_attachment'   => true,
				'noindex_author'       => true,
			],
			'contact' => [
				'phone_default'     => '0164-652846',
				'email_default'     => 'info@helderduidelijkschoon.nl',
				'company_name'      => 'HDS Onderhoudsdiensten',
				'country'           => 'NL',
				'service_area'      => 'West-Brabant en Zeeland',
			],
			'woocommerce' => [
				'enabled'            => defined( 'WC_PLUGIN_FILE' ) || class_exists( 'WooCommerce' ),
				'products_per_page'  => 12,
				'thumbnail_width'    => 300,
				'thumbnail_height'   => 300,
				'image_gallery_zoom' => true,
				'image_gallery_lightbox' => true,
			],
			'performance' => [
				'lazy_load_images'     => true,
				'preload_critical_fonts' => true,
				'preload_logo'         => true,
				'remove_block_css'     => true,
				'remove_global_styles' => true,
				'remove_jquery_migrate' => true,
				'remove_emoji'         => true,
				'enable_svg_upload'    => true,
				'jpeg_quality'         => 82,
				'post_revisions_max'   => 10,
				'object_cache_compat'  => true,
				'critical_css_enabled' => false,
			],
			'features' => [
				'woocommerce_integration' => false,
				'blog_enabled'            => true,
				'comments_enabled'        => false,
				'vacancies_enabled'       => true,
				'downloads_page'          => false,
				'team_members'            => false,
				'debug_toolbar'           => defined( 'WP_DEBUG' ) && WP_DEBUG,
			],
			'theme' => [
				'version'           => HDS_VERSION,
				'dir'               => HDS_DIR,
				'uri'               => HDS_URI,
				'assets_uri'        => HDS_ASSETS_URI,
				'text_domain'       => 'hds',
				'locale'            => 'nl_NL',
				'container_width'   => '1200px',
				'content_width'     => '780px',
			],
		];
	}

	/**
	 * Get a config value by dot-notation key.
	 */
	public static function get( string $key, $default = null ) {
		if ( empty( self::$config ) ) {
			self::init();
		}

		$segments = explode( '.', $key );
		$value    = self::$config;

		foreach ( $segments as $segment ) {
			if ( ! isset( $value[ $segment ] ) ) {
				return $default;
			}
			$value = $value[ $segment ];
		}

		return $value;
	}

	/**
	 * Get a raw config value (no init — used during init).
	 */
	private static function get_raw( string $key ) {
		$segments = explode( '.', $key );
		$value    = self::$config;

		foreach ( $segments as $segment ) {
			if ( ! isset( $value[ $segment ] ) ) {
				return null;
			}
			$value = $value[ $segment ];
		}

		return $value;
	}

	/**
	 * Check if a feature flag is enabled.
	 */
	public static function is_enabled( string $feature ): bool {
		return (bool) self::get( "features.{$feature}", false );
	}

	/**
	 * Get analytics tracking ID.
	 */
	public static function ga4_id(): string {
		return (string) self::get( 'analytics.ga4_measurement_id', '' );
	}

	/**
	 * Get GTM container ID.
	 */
	public static function gtm_id(): string {
		return (string) self::get( 'analytics.gtm_container_id', '' );
	}
}
