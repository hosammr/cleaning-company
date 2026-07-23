<?php
/**
 * Custom Post Types registration.
 *
 * @package HDS
 */

/**
 * Register hds_testimonial CPT (non-public, queryable only via blocks).
 */
function hds_register_testimonial_cpt(): void {
	register_post_type( 'hds_testimonial', [
		'labels' => [
			'name'          => __( 'Referenties', 'hds' ),
			'singular_name' => __( 'Referentie', 'hds' ),
			'add_new'       => __( 'Nieuwe referentie', 'hds' ),
			'add_new_item'  => __( 'Nieuwe referentie toevoegen', 'hds' ),
		],
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'has_archive'         => false,
		'supports'            => [ 'title', 'editor' ],
		'menu_icon'           => 'dashicons-format-quote',
		'rewrite'             => false,
	] );
}
add_action( 'init', 'hds_register_testimonial_cpt' );

/**
 * Register hds_vacancy CPT.
 */
function hds_register_vacancy_cpt(): void {
	register_post_type( 'hds_vacancy', [
		'labels' => [
			'name'          => __( 'Vacatures', 'hds' ),
			'singular_name' => __( 'Vacature', 'hds' ),
			'add_new'       => __( 'Nieuwe vacature', 'hds' ),
			'add_new_item'  => __( 'Nieuwe vacature toevoegen', 'hds' ),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'has_archive'        => false,
		'supports'           => [ 'title', 'editor' ],
		'menu_icon'          => 'dashicons-businessperson',
		'rewrite'            => [ 'slug' => 'vacatures' ],
	] );
}
add_action( 'init', 'hds_register_vacancy_cpt' );

/**
 * FAQ is managed via Yoast/Rank Math FAQ Block on standard Page.
 * No `hds_faq` CPT. See ADR D-012.
 */
