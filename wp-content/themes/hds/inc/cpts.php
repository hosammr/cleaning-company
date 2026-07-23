<?php
/**
 * Custom Post Types registration.
 *
 * Two CPTs per DHG §3.3:
 *   hds_testimonial — non-public, block-queried only
 *   hds_vacancy     — public, displayed via hds/job-listing block
 *
 * Block templates and editor restrictions are in inc/editor-config.php.
 *
 * @package HDS
 */

/**
 * Register hds_testimonial CPT (non-public, queryable only via blocks).
 */
function hds_register_testimonial_cpt(): void {
	$labels = [
		'name'                  => _x( 'Referenties', 'Post type general name', 'hds' ),
		'singular_name'         => _x( 'Referentie', 'Post type singular name', 'hds' ),
		'menu_name'             => _x( 'Referenties', 'Admin menu text', 'hds' ),
		'add_new'               => __( 'Nieuwe referentie', 'hds' ),
		'add_new_item'          => __( 'Nieuwe referentie toevoegen', 'hds' ),
		'edit_item'             => __( 'Referentie bewerken', 'hds' ),
		'view_item'             => __( 'Referentie bekijken', 'hds' ),
		'all_items'             => __( 'Alle referenties', 'hds' ),
		'search_items'          => __( 'Referenties zoeken', 'hds' ),
		'not_found'             => __( 'Geen referenties gevonden.', 'hds' ),
		'not_found_in_trash'    => __( 'Geen referenties in prullenbak.', 'hds' ),
		'featured_image'        => __( 'Profielfoto', 'hds' ),
		'set_featured_image'    => __( 'Profielfoto instellen', 'hds' ),
		'remove_featured_image' => __( 'Profielfoto verwijderen', 'hds' ),
	];

	register_post_type( 'hds_testimonial', [
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'has_archive'        => false,
		'supports'           => [ 'title', 'editor', 'thumbnail' ],
		'menu_icon'          => 'dashicons-format-quote',
		'menu_position'      => 25,
		'rewrite'            => false,
		'template'           => [
			[ 'core/paragraph', [
				'placeholder' => __( 'Schrijf de referentie tekst...', 'hds' ),
			] ],
		],
		'template_lock'      => 'insert',
	] );
}
add_action( 'init', 'hds_register_testimonial_cpt' );

/**
 * Register hds_vacancy CPT.
 */
function hds_register_vacancy_cpt(): void {
	$labels = [
		'name'               => _x( 'Vacatures', 'Post type general name', 'hds' ),
		'singular_name'      => _x( 'Vacature', 'Post type singular name', 'hds' ),
		'menu_name'          => _x( 'Vacatures', 'Admin menu text', 'hds' ),
		'add_new'            => __( 'Nieuwe vacature', 'hds' ),
		'add_new_item'       => __( 'Nieuwe vacature toevoegen', 'hds' ),
		'edit_item'          => __( 'Vacature bewerken', 'hds' ),
		'view_item'          => __( 'Vacature bekijken', 'hds' ),
		'all_items'          => __( 'Alle vacatures', 'hds' ),
		'search_items'       => __( 'Vacatures zoeken', 'hds' ),
		'not_found'          => __( 'Geen vacatures gevonden.', 'hds' ),
		'not_found_in_trash' => __( 'Geen vacatures in prullenbak.', 'hds' ),
	];

	register_post_type( 'hds_vacancy', [
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'has_archive'        => false,
		'supports'           => [ 'title', 'editor' ],
		'menu_icon'          => 'dashicons-businessperson',
		'menu_position'      => 26,
		'rewrite'            => [ 'slug' => 'vacatures' ],
		'template'           => [
			[ 'core/heading',   [ 'level' => 3, 'placeholder' => __( 'Functieomschrijving', 'hds' ) ] ],
			[ 'core/paragraph', [ 'placeholder' => __( 'Beschrijf de functie...', 'hds' ) ] ],
			[ 'core/heading',   [ 'level' => 3, 'placeholder' => __( 'Wat vragen wij?', 'hds' ) ] ],
			[ 'core/list',      [] ],
			[ 'core/heading',   [ 'level' => 3, 'placeholder' => __( 'Wat bieden wij?', 'hds' ) ] ],
			[ 'core/list',      [] ],
		],
		'template_lock'      => 'insert',
	] );
}
add_action( 'init', 'hds_register_vacancy_cpt' );

/**
 * FAQ is managed via Yoast/Rank Math FAQ Block on standard Page.
 * No `hds_faq` CPT. See ADR D-012.
 */

/**
 * Add orderby support for service pages (menu_order per ADR D-014).
 */
function hds_add_page_attributes_support(): void {
	add_post_type_support( 'page', 'page-attributes' );
}
add_action( 'init', 'hds_add_page_attributes_support' );
