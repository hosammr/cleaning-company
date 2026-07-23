<?php
/**
 * Routing & Template Resolution helpers.
 *
 * Enriches template loading with body classes, template
 * suggestions, and post type archive handling.
 *
 * @package HDS
 */

/**
 * Add template-specific body classes.
 */
function hds_template_body_classes( array $classes ): array {
	if ( is_page_template() ) {
		$template = get_page_template_slug();
		if ( $template ) {
			$classes[] = 'has-page-template';
			$classes[] = 'template-' . sanitize_html_class( basename( $template, '.php' ) );
		}
	}

	if ( is_front_page() ) {
		$classes[] = 'is-front-page';
	}

	if ( is_home() ) {
		$classes[] = 'is-blog-index';
	}

	if ( is_singular() ) {
		$classes[] = 'is-singular';
		$classes[] = 'is-singular-' . get_post_type();
	}

	if ( is_archive() && ! is_post_type_archive() ) {
		$classes[] = 'is-archive';
	}

	if ( is_page() ) {
		$classes[] = 'page-slug-' . sanitize_html_class( get_post_field( 'post_name' ) );
	}

	if ( has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'body_class', 'hds_template_body_classes' );

/**
 * Determine the current template context.
 */
function hds_get_template_context(): string {
	if ( is_front_page() ) {
		return 'front-page';
	}
	if ( is_home() ) {
		return 'blog';
	}
	if ( is_singular( 'post' ) ) {
		return 'single';
	}
	if ( is_singular( 'hds_vacancy' ) ) {
		return 'vacancy';
	}
	if ( is_page() ) {
		return 'page';
	}
	if ( is_archive() ) {
		return 'archive';
	}
	if ( is_search() ) {
		return 'search';
	}
	if ( is_404() ) {
		return '404';
	}

	return 'default';
}
