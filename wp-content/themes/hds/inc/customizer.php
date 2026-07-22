<?php
/**
 * Theme Customizer settings — Company Information.
 *
 * @package HDS
 */

/**
 * Register Customizer settings for company info.
 */
function hds_customizer_register( $wp_customize ): void {
	$wp_customize->add_section( 'hds_company_info', [
		'title'       => __( 'Bedrijfsgegevens', 'hds' ),
		'priority'    => 30,
		'description' => __( 'Bedrijfsinformatie gebruikt in footer, contactpagina en schema.', 'hds' ),
	] );

	$fields = [
		'hds_address'       => __( 'Adres (straat + huisnummer)', 'hds' ),
		'hds_postal_city'   => __( 'Postcode en plaats', 'hds' ),
		'hds_phone'         => __( 'Telefoonnummer', 'hds' ),
		'hds_email'         => __( 'E-mailadres', 'hds' ),
		'hds_kvk'           => __( 'KVK-nummer', 'hds' ),
		'hds_btw'           => __( 'BTW-nummer', 'hds' ),
		'hds_facebook_url'  => __( 'Facebook URL', 'hds' ),
		'hds_instagram_url' => __( 'Instagram URL', 'hds' ),
		'hds_gbp_url'       => __( 'Google Business Profile URL', 'hds' ),
	];

	foreach ( $fields as $id => $label ) {
		$wp_customize->add_setting( $id, [
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );

		$wp_customize->add_control( $id, [
			'label'    => $label,
			'section'  => 'hds_company_info',
			'type'     => 'text',
			'settings' => $id,
		] );
	}

	$wp_customize->add_setting( 'hds_opening_hours', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	] );

	$wp_customize->add_control( 'hds_opening_hours', [
		'label'    => __( 'Openingstijden', 'hds' ),
		'section'  => 'hds_company_info',
		'type'     => 'textarea',
		'settings' => 'hds_opening_hours',
	] );
}
add_action( 'customize_register', 'hds_customizer_register' );
