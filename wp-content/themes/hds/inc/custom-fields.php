<?php
/**
 * Custom fields — register post meta for Service, Testimonial, and Vacancy.
 *
 * Uses register_post_meta() so values are available via REST API and
 * the Block Editor bindings API. No ACF dependency (ADR D-007).
 *
 * Validation callbacks are defined in inc/validation.php.
 * Editor UI panels are registered in inc/meta-panels.php.
 *
 * @package HDS
 */

/**
 * Register Service Page post meta fields.
 *
 * Fields appear on pages using the Service template.
 */
function hds_register_service_fields(): void {
	$fields = [
		'hds_subtitle'     => [
			'type'              => 'string',
			'description'       => __( 'Ondertitel onder de paginatitel in de hero sectie.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_subtitle',
		],
		'hds_hero_image'   => [
			'type'              => 'integer',
			'description'       => __( 'Media ID van de hero achtergrondafbeelding.', 'hds' ),
			'default'           => 0,
			'sanitize_callback' => 'hds_validate_attachment_id',
		],
		'hds_service_icon' => [
			'type'              => 'string',
			'description'       => __( 'Icoon slug voor de service card op de homepage.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_icon_slug',
		],
		'hds_cta_override' => [
			'type'              => 'string',
			'description'       => __( 'Overschrijf de standaard CTA knoptekst. Laat leeg voor default.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_cta_text',
		],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'page', $key, [
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => $args['type'],
			'description'       => $args['description'],
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'auth_callback'     => function () {
				return current_user_can( 'edit_pages' );
			},
		] );
	}
}
add_action( 'init', 'hds_register_service_fields' );

/**
 * Register Testimonial post meta fields.
 */
function hds_register_testimonial_fields(): void {
	$fields = [
		'hds_author_name'     => [
			'type'              => 'string',
			'description'       => __( 'Naam van de referent.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_person_name',
		],
		'hds_company_name'    => [
			'type'              => 'string',
			'description'       => __( 'Bedrijfsnaam van de referent.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		],
		'hds_star_rating'     => [
			'type'              => 'integer',
			'description'       => __( 'Beoordeling (1-5 sterren).', 'hds' ),
			'default'           => 5,
			'sanitize_callback' => 'hds_validate_star_rating',
		],
		'hds_related_service' => [
			'type'              => 'integer',
			'description'       => __( 'Gerelateerde service pagina ID.', 'hds' ),
			'default'           => 0,
			'sanitize_callback' => 'hds_validate_related_service',
		],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'hds_testimonial', $key, [
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => $args['type'],
			'description'       => $args['description'],
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		] );
	}
}
add_action( 'init', 'hds_register_testimonial_fields' );

/**
 * Register Vacancy post meta fields.
 */
function hds_register_vacancy_fields(): void {
	$fields = [
		'hds_hours_per_week'    => [
			'type'              => 'string',
			'description'       => __( 'Uren per week (bijv. "32-40").', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_hours',
		],
		'hds_location'          => [
			'type'              => 'string',
			'description'       => __( 'Locatie / standplaats.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_location',
		],
		'hds_start_date'        => [
			'type'              => 'string',
			'description'       => __( 'Gewenste startdatum.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_date',
		],
		'hds_application_email' => [
			'type'              => 'string',
			'description'       => __( 'E-mailadres voor sollicitaties.', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_email',
		],
		'hds_deadline'          => [
			'type'              => 'string',
			'description'       => __( 'Sluitingsdatum vacature (DD-MM-YYYY).', 'hds' ),
			'default'           => '',
			'sanitize_callback' => 'hds_validate_date',
		],
		'hds_is_active'         => [
			'type'              => 'boolean',
			'description'       => __( 'Vacature actief en zichtbaar op de website.', 'hds' ),
			'default'           => false,
			'sanitize_callback' => 'hds_validate_is_active',
		],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'hds_vacancy', $key, [
			'show_in_rest'      => true,
			'single'            => true,
			'type'              => $args['type'],
			'description'       => $args['description'],
			'default'           => $args['default'],
			'sanitize_callback' => $args['sanitize_callback'],
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		] );
	}
}

add_filter( 'rest_hds_vacancy_item_schema', 'hds_add_vacancy_meta_to_rest_schema' );

/**
 * Add vacancy meta fields to the REST API schema for hds_vacancy.
 *
 * Ensures the six hds_* custom fields are accepted during
 * block-editor saves via the REST API.
 *
 * @param array $schema REST item schema.
 * @return array Modified schema with meta property.
 */
function hds_add_vacancy_meta_to_rest_schema( array $schema ): array {
	$schema['properties']['meta'] = array(
		'type'       => 'object',
		'properties' => array(
			'hds_hours_per_week'    => array(
				'type'    => 'string',
				'default' => '',
			),
			'hds_location'          => array(
				'type'    => 'string',
				'default' => '',
			),
			'hds_start_date'        => array(
				'type'    => 'string',
				'default' => '',
			),
			'hds_application_email' => array(
				'type'    => 'string',
				'default' => '',
			),
			'hds_deadline'          => array(
				'type'    => 'string',
				'default' => '',
			),
			'hds_is_active'         => array(
				'type'    => 'boolean',
				'default' => false,
			),
		),
	);
	return $schema;
}
add_action( 'init', 'hds_register_vacancy_fields' );
