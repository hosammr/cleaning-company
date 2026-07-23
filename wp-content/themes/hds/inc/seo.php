<?php
/**
 * SEO infrastructure hooks.
 *
 * Rank Math Pro provides the primary SEO layer. These hooks ensure
 * theme-level compatibility and add defaults where Rank Math may not.
 *
 * Implements: title, meta description, canonical, OG, Twitter Cards,
 * breadcrumbs, schema, XML sitemap, robots compatibility.
 *
 * @package HDS
 */

/**
 * Default meta description via wp_head (fallback if Rank Math not active).
 */
function hds_add_default_meta_description(): void {
	if ( function_exists( 'rank_math_the_description' ) ) {
		return;
	}

	$description = is_singular()
		? wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '...' )
		: get_bloginfo( 'description' );

	if ( $description ) {
		printf(
			'<meta name="description" content="%s">' . "\n",
			esc_attr( $description )
		);
	}
}
add_action( 'wp_head', 'hds_add_default_meta_description', 2 );

/**
 * Ensure canonical URL is present (Rank Math fallback).
 */
function hds_add_canonical(): void {
	if ( function_exists( 'rank_math_the_canonical' ) ) {
		return;
	}
	if ( is_singular() ) {
		printf(
			'<link rel="canonical" href="%s">' . "\n",
			esc_url( get_permalink() )
		);
	}
}
add_action( 'wp_head', 'hds_add_canonical', 3 );

/**
 * Add Open Graph tags (Rank Math fallback).
 */
function hds_add_open_graph(): void {
	if ( function_exists( 'rank_math_the_opengraph' ) ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = is_singular()
		? wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '...' )
		: get_bloginfo( 'description' );
	$url         = is_singular() ? get_permalink() : home_url( '/' );
	$image       = is_singular() && has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'large' )
		: '';

	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
}

/**
 * Add Twitter Card tags (Rank Math fallback).
 */
function hds_add_twitter_cards(): void {
	if ( function_exists( 'rank_math_the_twitter_card' ) ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = is_singular()
		? wp_trim_words( wp_strip_all_tags( get_the_content() ), 30, '...' )
		: get_bloginfo( 'description' );
	$image       = is_singular() && has_post_thumbnail()
		? get_the_post_thumbnail_url( null, 'large' )
		: '';

	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}
}

if ( ! function_exists( 'rank_math_the_opengraph' ) ) {
	add_action( 'wp_head', 'hds_add_open_graph', 4 );
	add_action( 'wp_head', 'hds_add_twitter_cards', 5 );
}

/**
 * Add robots meta tag for pages that should not be indexed.
 */
function hds_add_robots_meta(): void {
	$noindex = false;

	if ( is_search() ) {
		$noindex = true;
	}
	if ( is_404() ) {
		$noindex = true;
	}
	if ( is_attachment() ) {
		$noindex = true;
	}
	if ( is_author() ) {
		$noindex = true;
	}

	$noindex = apply_filters( 'hds_robots_noindex', $noindex );

	if ( $noindex ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'hds_add_robots_meta', 1 );

/**
 * Add hreflang for Dutch language.
 */
function hds_add_hreflang(): void {
	if ( is_front_page() ) {
		printf(
			'<link rel="alternate" hreflang="nl" href="%s">' . "\n",
			esc_url( home_url( '/' ) )
		);
		printf(
			'<link rel="alternate" hreflang="x-default" href="%s">' . "\n",
			esc_url( home_url( '/' ) )
		);
	}
}
add_action( 'wp_head', 'hds_add_hreflang', 2 );

/**
 * Ensure attachment pages are excluded from XML sitemaps (Rank Math compatibility).
 */
function hds_exclude_attachment_from_sitemap( bool $excluded, string $post_type ): bool {
	if ( 'attachment' === $post_type ) {
		return true;
	}
	return $excluded;
}
add_filter( 'rank_math/sitemap/exclude_post_type', 'hds_exclude_attachment_from_sitemap', 10, 2 );

/**
 * Add breadcrumb schema integration hook.
 */
function hds_breadcrumb_schema_integration(): string {
	ob_start();
	get_template_part( 'parts/breadcrumbs' );
	$breadcrumbs = ob_get_clean();

	return apply_filters( 'hds_breadcrumbs_output', $breadcrumbs );
}

/**
 * Allow filtering of the title separator.
 */
function hds_document_title_separator( string $sep ): string {
	return apply_filters( 'hds_title_separator', $sep );
}
add_filter( 'document_title_separator', 'hds_document_title_separator' );

/**
 * Allow filtering of the document title parts.
 */
function hds_document_title_parts( array $title ): array {
	return apply_filters( 'hds_document_title_parts', $title );
}
add_filter( 'document_title_parts', 'hds_document_title_parts' );
