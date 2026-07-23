<?php
/**
 * Post meta panels in the Block Editor sidebar.
 *
 * Registers PluginDocumentSettingPanel components via the block editor
 * JS API. These replace ACF metaboxes — each panel exposes the post meta
 * fields registered via register_post_meta() in inc/custom-fields.php.
 *
 * Panels appear conditionally per post type.
 *
 * @package HDS
 */

/**
 * Enqueue editor scripts for meta panels.
 */
function hds_enqueue_meta_panel_scripts(): void {
	$screen = get_current_screen();
	if ( ! $screen || ! $screen->is_block_editor() ) {
		return;
	}

	$asset_uri = HDS_URI . '/assets/js/meta-panels.js';
	$asset_ver = hds_get_asset_version();

	wp_enqueue_script(
		'hds-meta-panels',
		$asset_uri,
		[ 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-compose', 'wp-i18n', 'wp-core-data' ],
		$asset_ver,
		true
	);

	wp_set_script_translations( 'hds-meta-panels', 'hds', HDS_DIR . '/languages' );

	wp_localize_script( 'hds-meta-panels', 'hdsMetaPanelsData', [
		'postType'  => $screen->post_type ?? '',
		'serviceFields' => [
			'hds_subtitle' => [
				'label'       => __( 'Ondertitel', 'hds' ),
				'description' => __( 'Verschijnt onder de paginatitel in de hero sectie.', 'hds' ),
				'type'        => 'text',
			],
			'hds_hero_image' => [
				'label'       => __( 'Hero afbeelding', 'hds' ),
				'description' => __( 'Achtergrondafbeelding voor de hero sectie (1600×900).', 'hds' ),
				'type'        => 'image',
			],
			'hds_service_icon' => [
				'label'       => __( 'Icoon slug', 'hds' ),
				'description' => __( 'Phosphor icoon naam (bv. ph-window).', 'hds' ),
				'type'        => 'text',
			],
			'hds_cta_override' => [
				'label'       => __( 'CTA knoptekst (optioneel)', 'hds' ),
				'description' => __( 'Overschrijf de standaardtekst. Laat leeg voor "Vrijblijvende offerte".', 'hds' ),
				'type'        => 'text',
			],
		],
		'testimonialFields' => [
			'hds_author_name' => [
				'label'       => __( 'Naam', 'hds' ),
				'description' => __( 'Naam van de persoon die de referentie geeft.', 'hds' ),
				'type'        => 'text',
			],
			'hds_company_name' => [
				'label'       => __( 'Bedrijf', 'hds' ),
				'description' => __( 'Bedrijfsnaam (optioneel).', 'hds' ),
				'type'        => 'text',
			],
			'hds_star_rating' => [
				'label'       => __( 'Beoordeling', 'hds' ),
				'description' => __( 'Score van 1 tot 5 sterren.', 'hds' ),
				'type'        => 'number',
				'min'         => 1,
				'max'         => 5,
			],
			'hds_related_service' => [
				'label'       => __( 'Gerelateerde dienst', 'hds' ),
				'description' => __( 'Koppel aan een service pagina (optioneel).', 'hds' ),
				'type'        => 'post',
				'postType'    => 'page',
			],
		],
		'vacancyFields' => [
			'hds_hours_per_week' => [
				'label'       => __( 'Uren per week', 'hds' ),
				'description' => __( 'Bijv. "32–40".', 'hds' ),
				'type'        => 'text',
			],
			'hds_location' => [
				'label'       => __( 'Locatie', 'hds' ),
				'description' => __( 'Standplaats of werklocatie.', 'hds' ),
				'type'        => 'text',
			],
			'hds_start_date' => [
				'label'       => __( 'Gewenste startdatum', 'hds' ),
				'description' => __( 'Datum waarop de kandidaat kan beginnen.', 'hds' ),
				'type'        => 'text',
			],
			'hds_application_email' => [
				'label'       => __( 'Sollicitatie e-mail', 'hds' ),
				'description' => __( 'E-mailadres voor sollicitaties.', 'hds' ),
				'type'        => 'email',
			],
			'hds_deadline' => [
				'label'       => __( 'Sluitingsdatum', 'hds' ),
				'description' => __( 'Datum waarop de vacature sluit.', 'hds' ),
				'type'        => 'text',
			],
			'hds_is_active' => [
				'label'       => __( 'Actief', 'hds' ),
				'description' => __( 'Zichtbaar op de website.', 'hds' ),
				'type'        => 'toggle',
			],
		],
	] );
}
add_action( 'enqueue_block_editor_assets', 'hds_enqueue_meta_panel_scripts' );
