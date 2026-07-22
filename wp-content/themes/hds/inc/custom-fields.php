<?php
/**
 * Custom fields — register post meta for Service, Testimonial, and Vacancy.
 *
 * Uses register_post_meta() so values are available via REST API and
 * the Block Editor bindings API. No ACF dependency.
 *
 * @package HDS
 */

function hds_register_service_fields(): void {
	$fields = [
		'hds_subtitle'    => [ 'type' => 'string',  'description' => __( 'Ondertitel onder de paginatitel in de hero sectie.', 'hds' ) ],
		'hds_hero_image'  => [ 'type' => 'integer', 'description' => __( 'Media ID van de hero achtergrondafbeelding.', 'hds' ) ],
		'hds_service_icon'=> [ 'type' => 'string',  'description' => __( 'Icoon slug voor de service card op de homepage.', 'hds' ) ],
		'hds_cta_override'=> [ 'type' => 'string',  'description' => __( 'Overschrijf de standaard CTA knoptekst. Laat leeg voor default.', 'hds' ) ],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'page', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $args['type'],
			'description'   => $args['description'],
			'auth_callback' => function () {
				return current_user_can( 'edit_pages' );
			},
		] );
	}
}
add_action( 'init', 'hds_register_service_fields' );

function hds_register_testimonial_fields(): void {
	$fields = [
		'hds_author_name'    => [ 'type' => 'string',  'description' => __( 'Naam van de referent.', 'hds' ) ],
		'hds_company_name'   => [ 'type' => 'string',  'description' => __( 'Bedrijfsnaam van de referent.', 'hds' ) ],
		'hds_star_rating'    => [ 'type' => 'integer', 'description' => __( 'Beoordeling (1-5 sterren).', 'hds' ) ],
		'hds_related_service'=> [ 'type' => 'integer', 'description' => __( 'Gerelateerde service pagina ID.', 'hds' ) ],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'hds_testimonial', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $args['type'],
			'description'   => $args['description'],
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		] );
	}
}
add_action( 'init', 'hds_register_testimonial_fields' );

function hds_register_vacancy_fields(): void {
	$fields = [
		'hds_hours_per_week'   => [ 'type' => 'string',  'description' => __( 'Uren per week.', 'hds' ) ],
		'hds_location'         => [ 'type' => 'string',  'description' => __( 'Locatie / standplaats.', 'hds' ) ],
		'hds_start_date'       => [ 'type' => 'string',  'description' => __( 'Gewenste startdatum.', 'hds' ) ],
		'hds_application_email'=> [ 'type' => 'string',  'description' => __( 'E-mailadres voor sollicitaties.', 'hds' ) ],
		'hds_deadline'         => [ 'type' => 'string',  'description' => __( 'Sluitingsdatum vacature.', 'hds' ) ],
		'hds_is_active'        => [ 'type' => 'boolean', 'description' => __( 'Vacature actief?', 'hds' ) ],
	];

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'hds_vacancy', $key, [
			'show_in_rest'  => true,
			'single'        => true,
			'type'          => $args['type'],
			'description'   => $args['description'],
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		] );
	}
}
add_action( 'init', 'hds_register_vacancy_fields' );
