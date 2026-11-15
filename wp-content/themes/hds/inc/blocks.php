<?php
/**
 * Custom block registration.
 *
 * Four custom blocks that query dynamic data via server-side render:
 *   hds/service-card   — renders a single service card from a Page
 *   hds/testimonial    — renders testimonial cards from hds_testimonial CPT
 *   hds/job-listing    — renders job vacancy cards from hds_vacancy CPT
 *   hds/contact-info   — renders company info from Theme Customizer
 *
 * All blocks use render_callback (dynamic). No save() function.
 * Block editor scripts in assets/js/blocks/.
 * Block metadata (title, icon, description, attributes) is
 * defined in register_block_type() for core compatibility.
 *
 * @package HDS
 */

/**
 * Register all custom HDS blocks.
 */
function hds_register_custom_blocks(): void {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$blocks_dir = HDS_DIR . '/assets/js/blocks';
	$blocks_uri = HDS_URI . '/assets/js/blocks';

	$blocks = [
		'service-card' => [
			'render'    => 'hds_render_service_card',
			'title'     => __( 'HDS Service Card', 'hds' ),
			'desc'      => __( 'Toon een service kaart met icoon, titel, excerpt en link.', 'hds' ),
			'icon'      => 'admin-page',
			'category'  => 'hds-service',
		],
		'testimonial'  => [
			'render'    => 'hds_render_testimonial',
			'title'     => __( 'HDS Referenties', 'hds' ),
			'desc'      => __( 'Toon referenties van klanten uit het hds_testimonial CPT.', 'hds' ),
			'icon'      => 'format-quote',
			'category'  => 'hds-service',
		],
		'job-listing'  => [
			'render'    => 'hds_render_job_listing',
			'title'     => __( 'HDS Vacatures', 'hds' ),
			'desc'      => __( 'Toon openstaande vacatures met details en solliciteerknop.', 'hds' ),
			'icon'      => 'businessperson',
			'category'  => 'hds-service',
		],
		'contact-info' => [
			'render'    => 'hds_render_contact_info',
			'title'     => __( 'HDS Contactgegevens', 'hds' ),
			'desc'      => __( 'Toon bedrijfsgegevens uit de Customizer (telefoon, e-mail, adres, KVK, BTW).', 'hds' ),
			'icon'      => 'phone',
			'category'  => 'hds-content',
		],
		'client-logos' => [
			'render'    => 'hds_render_client_logos',
			'title'     => __( 'HDS Opdrachtgevers', 'hds' ),
			'desc'      => __( 'Toon de bevestigde opdrachtgevers met hun logo en externe website.', 'hds' ),
			'icon'      => 'businessperson',
			'category'  => 'hds-content',
		],
	];

	foreach ( $blocks as $name => $config ) {
		$handle = 'hds-' . $name;

		wp_register_script(
			$handle,
			$blocks_uri . '/' . $name . '.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-server-side-render' ],
			HDS_VERSION,
			true
		);

		register_block_type( 'hds/' . $name, [
			'editor_script'   => $handle,
			'render_callback' => $config['render'],
			'attributes'      => hds_get_block_attributes( $name ),
		] );
	}
}
add_action( 'init', 'hds_register_custom_blocks' );

/**
 * Get block attribute definitions.
 */
function hds_get_block_attributes( string $block ): array {
	$attributes = [
		'service-card' => [
			'pageId'    => [ 'type' => 'integer', 'default' => 0 ],
			'showImage' => [ 'type' => 'boolean', 'default' => false ],
		],