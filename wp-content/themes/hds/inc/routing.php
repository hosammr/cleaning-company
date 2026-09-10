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
 * 301-redirect removed pages to their approved destinations.
 */
function hds_redirect_removed_pages(): void {
	$path = untrailingslashit( (string) wp_parse_url( (string) ( $_SERVER['REQUEST_URI'] ?? '' ), PHP_URL_PATH ) );

	$redirects = [
		'/glas-en-gevel' => '/glasbewassing/',
		'/downloads'     => '/kwaliteit-en-veiligheid/',
	];

	if ( isset( $redirects[ $path ] ) ) {
		wp_safe_redirect( home_url( $redirects[ $path ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'hds_redirect_removed_pages' );
