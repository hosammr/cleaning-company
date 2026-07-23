<?php
/**
 * Block Editor configuration.
 *
 * Controls which blocks are allowed, post-type templates (default block
 * structures), editor preferences, and block category registration.
 *
 * Replaces ACF "Option Pages" + "Flexible Content" layout management with
 * core block templates + template parts assigned at post-type level.
 *
 * @package HDS
 */

/**
 * Register custom block categories.
 */
function hds_register_block_categories( array $categories ): array {
	$custom = [
		[
			'slug'  => 'hds-service',
			'title' => __( 'HDS — Diensten', 'hds' ),
			'icon'  => 'admin-page',
		],
		[
			'slug'  => 'hds-content',
			'title' => __( 'HDS — Inhoud', 'hds' ),
			'icon'  => 'admin-post',
		],
	];

	return array_merge( $custom, $categories );
}
add_filter( 'block_categories_all', 'hds_register_block_categories' );

/**
 * Define default block template for service pages.
 *
 * Functions as the core equivalent of an ACF Flexible Content layout —
 * the editor opens with these blocks pre-inserted.
 */
function hds_get_service_block_template(): array {
	return [
		[ 'hds/service-card', [ 'lock' => [ 'remove' => false ] ] ],
		[ 'core/paragraph', [ 'placeholder' => __( 'Beschrijf de dienst...', 'hds' ) ] ],
	];
}

/**
 * Define default block template for testimonial CPT.
 */
function hds_get_testimonial_block_template(): array {
	return [
		[ 'core/paragraph', [ 'placeholder' => __( 'Schrijf de referentie tekst...', 'hds' ), 'className' => 'testimonial-quote' ] ],
	];
}

/**
 * Define default block template for vacancy CPT.
 */
function hds_get_vacancy_block_template(): array {
	return [
		[ 'core/heading', [ 'level' => 3, 'placeholder' => __( 'Functietitel...', 'hds' ) ] ],
		[ 'core/paragraph', [ 'placeholder' => __( 'Functieomschrijving...', 'hds' ) ] ],
		[ 'core/list', [ 'placeholder' => __( 'Vereisten...', 'hds' ) ] ],
		[ 'core/paragraph', [ 'placeholder' => __( 'Wat wij bieden...', 'hds' ) ] ],
	];
}

/**
 * Define default block template for the Contact page.
 */
function hds_get_contact_block_template(): array {
	return [
		[ 'hds/contact-info', [ 'lock' => [ 'remove' => true ] ] ],
	];
}

/**
 * Locked block template for the FAQ page.
 */
function hds_get_faq_block_template(): array {
	return [
		[ 'core/paragraph', [ 'placeholder' => __( 'Introductietekst voor de veelgestelde vragen...', 'hds' ) ] ],
	];
}

/**
 * Define default block template for Legal pages.
 */
function hds_get_legal_block_template(): array {
	return [
		[ 'core/heading', [ 'level' => 2, 'placeholder' => __( 'Artikel 1...', 'hds' ) ] ],
		[ 'core/paragraph', [ 'placeholder' => __( 'Inhoud...', 'hds' ) ] ],
	];
}

/**
 * Restrict blocks per post type.
 *
 * Core equivalent of ACF "Allowed Blocks Policy" — globally all blocks
 * are allowed (DHG §5.1), but specific post types get sensible defaults.
 */
function hds_get_allowed_blocks_for_post_type( string $post_type ): array|bool {
	$core_text = [
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/table',
		'core/code',
		'core/preformatted',
		'core/pullquote',
	];

	$core_media = [
		'core/image',
		'core/gallery',
		'core/cover',
		'core/video',
		'core/file',
	];

	$core_layout = [
		'core/group',
		'core/columns',
		'core/column',
		'core/separator',
		'core/spacer',
		'core/buttons',
		'core/button',
	];

	$hds_blocks = [
		'hds/service-card',
		'hds/testimonial',
		'hds/job-listing',
		'hds/contact-info',
	];

	$allowed = array_merge( $core_text, $core_media, $core_layout, $hds_blocks );

	if ( 'hds_testimonial' === $post_type ) {
		return $core_text;
	}

	if ( 'hds_vacancy' === $post_type ) {
		return array_merge( $core_text, $core_media );
	}

	return $allowed;
}

/**
 * Filter allowed blocks per post type.
 *
 * Set to true (allow all) by default per DHG §5.1, but apply curated
 * lists on testimonials and vacancies for editorial simplicity.
 */
function hds_allowed_blocks_filter( $allowed, $context ): array|bool {
	if ( ! $context->post || ! isset( $context->post->post_type ) ) {
		return $allowed;
	}

	$post_type = $context->post->post_type;

	if ( in_array( $post_type, [ 'hds_testimonial', 'hds_vacancy' ], true ) ) {
		return hds_get_allowed_blocks_for_post_type( $post_type );
	}

	return true;
}
add_filter( 'allowed_block_types_all', 'hds_allowed_blocks_filter', 10, 2 );

/**
 * Set editor preferences per post type.
 */
function hds_editor_preferences(): void {
	$screen = get_current_screen();
	if ( ! $screen || ! $screen->is_block_editor() ) {
		return;
	}

	$post_type = $screen->post_type ?? '';

	$preferences = [
		'hds_testimonial' => [
			'fullscreenMode' => false,
			'fixedToolbar'   => true,
		],
		'hds_vacancy'      => [
			'fullscreenMode' => false,
			'fixedToolbar'   => true,
		],
	];

	if ( isset( $preferences[ $post_type ] ) ) {
		wp_add_inline_script(
			'wp-blocks',
			'wp.data.dispatch("core/edit-post").toggleFeature("fullscreenMode");',
			'after'
		);
	}
}
add_action( 'current_screen', 'hds_editor_preferences' );

/**
 * Remove unwanted block editor panels for cleaner UX.
 */
function hds_remove_editor_panels(): void {
	$post_types = [ 'hds_testimonial', 'hds_vacancy' ];

	foreach ( $post_types as $pt ) {
		remove_post_type_support( $pt, 'excerpt' );
		remove_post_type_support( $pt, 'comments' );
		remove_post_type_support( $pt, 'trackbacks' );
		remove_post_type_support( $pt, 'custom-fields' );
		remove_post_type_support( $pt, 'post-formats' );
	}
}
add_action( 'init', 'hds_remove_editor_panels' );

/**
 * Register block templates for post types (replaces ACF "Content Models").
 */
function hds_register_post_type_templates(): void {
	$post_type_object = get_post_type_object( 'hds_testimonial' );
	if ( $post_type_object && ! isset( $post_type_object->template ) ) {
		$post_type_object->template = hds_get_testimonial_block_template();
	}

	$vacancy_object = get_post_type_object( 'hds_vacancy' );
	if ( $vacancy_object && ! isset( $vacancy_object->template ) ) {
		$vacancy_object->template = hds_get_vacancy_block_template();
		$vacancy_object->template_lock = 'insert';
	}
}
add_action( 'init', 'hds_register_post_type_templates', 20 );

/**
 * Register core block patterns for different page templates as editor starter content.
 */
function hds_register_template_starter_content(): array {
	return [
		'page-templates/page-contact.php' => 'hds/contact-info-block',
		'page-templates/page-faq.php'     => 'hds/faq-starter',
	];
}
