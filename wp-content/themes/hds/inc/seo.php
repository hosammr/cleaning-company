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

	$description = hds_get_meta_description();
	if ( '' !== $description ) {
		printf(
			'<meta name="description" content="%s">' . "\n",
			esc_attr( $description )
		);
	}
}
add_action( 'wp_head', 'hds_add_default_meta_description', 2 );

/**
 * Get the meta description for the current request.
 *
 * Priority:
 * 1. Approved service seo_description (service pages)
 * 2. Existing page-specific description (page subtitle meta where set)
 * 3. Curated fallback for Contact, Offerte Aanvragen, Kwaliteit & Veiligheid
 * 4. Content-derived fallback
 * 5. Site tagline (non-singular)
 *
 * @return string Description, or an empty string when none is available.
 */
function hds_get_meta_description(): string {
	if ( is_page_template( 'page-templates/page-service.php' ) ) {
		$service = hds_get_service( get_post_field( 'post_name', get_queried_object_id() ) );
		if ( $service && ! empty( $service['seo_description'] ) ) {
			return (string) $service['seo_description'];
		}
	}

	if ( is_singular() ) {
		$page_description = hds_get_page_specific_meta_description();
		if ( '' !== $page_description ) {
			return $page_description;
		}

		$content = wp_strip_all_tags( get_the_content() );
		if ( '' !== trim( $content ) ) {
			return (string) wp_trim_words( $content, 30, '...' );
		}
	}

	$description = get_bloginfo( 'description' );
	return is_string( $description ) ? $description : '';
}

/**
 * Get a page-specific meta description.
 *
 * Uses the page subtitle where it is set, falling back to a curated
 * Dutch description for the main conversion pages whose content is empty
 * (they render server-side sections, not post content).
 *
 * @return string Description, or an empty string when none applies.
 */
function hds_get_page_specific_meta_description(): string {
	if ( ! is_page() ) {
		return '';
	}

	$slug = get_post_field( 'post_name', get_queried_object_id() );

	$subtitle = get_post_meta( get_queried_object_id(), 'hds_subtitle', true );
	if ( is_string( $subtitle ) && '' !== trim( $subtitle ) ) {
		return trim( $subtitle );
	}

	$curated = [
		'contact'                 => __( 'Neem contact op met Hamdoun Schoonmaak voor een vrijblijvende offerte, een vraag of een afspraak. Telefonisch bereikbaar op werkdagen van 08:00 tot 17:00.', 'hds' ),
		'offerte-aanvragen'       => __( 'Vraag vrijblijvend een offerte aan bij Hamdoun Schoonmaak. Ontvang binnen één werkdag een offerte op maat voor uw bedrijf.', 'hds' ),
		'kwaliteit-en-veiligheid' => __( 'Hamdoun Schoonmaak werkt zorgvuldig, met duidelijke afspraken en aandacht voor veiligheid. Lees hoe wij kwaliteit en veiligheid borgen.', 'hds' ),
	];

	return $curated[ $slug ] ?? '';
}

/**
 * Add Open Graph tags (Rank Math fallback).
 */
function hds_add_open_graph(): void {
	if ( function_exists( 'rank_math_the_opengraph' ) ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = hds_get_meta_description();
	$url         = is_singular() ? get_permalink() : home_url( '/' );
	$type        = is_singular( 'post' ) ? 'article' : 'website';
	$image       = hds_get_social_image();

	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( '' !== $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
}

/**
 * Get the social sharing image for the current request.
 *
 * Uses the post's featured image when present; otherwise falls back to
 * the site logo (an existing public upload), and finally to the theme
 * screenshot. Only public, site-owned URLs are returned.
 *
 * @return string Image URL, or an empty string when none is available.
 */
function hds_get_social_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = get_the_post_thumbnail_url( null, 'large' );
		if ( $url ) {
			return (string) $url;
		}
	}

	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return (string) $url;
		}
	}

	return HDS_URI . '/screenshot.png';
}

/**
 * Add Twitter Card tags (Rank Math fallback).
 */
function hds_add_twitter_cards(): void {
	if ( function_exists( 'rank_math_the_twitter_card' ) ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = hds_get_meta_description();
	$image       = hds_get_social_image();

	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( '' !== $description ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	}

	if ( $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}
}

if ( ! function_exists( 'rank_math_the_opengraph' ) ) {
	add_action( 'wp_head', 'hds_add_open_graph', 4 );
	add_action( 'wp_head', 'hds_add_twitter_cards', 5 );
}

/**
 * Set noindex/noarchive behavior through the WordPress robots API.
 *
 * Uses the wp_robots filter so WordPress emits a single, merged robots
 * meta tag (noindex + follow + the core max-image-preview directive)
 * instead of the theme echoing a second, conflicting tag.
 *
 * @param array $robots Associative array of robots directives.
 * @return array
 */
function hds_apply_robots_noindex( array $robots ): array {
	$noindex = is_search() || is_404() || is_attachment() || is_author();
	$noindex = apply_filters( 'hds_robots_noindex', $noindex );

	if ( $noindex ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'hds_apply_robots_noindex', 10 );

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
 * Apply approved service SEO titles to service pages.
 *
 * Service pages render entirely from the service data in inc/services.php;
 * the authored seo_title values must become the document <title>.
 * Registered at priority 20 so it runs after the VvE title normalization
 * (priority 10) and replaces its generic output with the full seo_title.
 *
 * @param array $title Associative array of document title parts.
 * @return array
 */
function hds_document_title_parts( array $title ): array {
	if ( is_page_template( 'page-templates/page-service.php' ) ) {
		$service = hds_get_service( get_post_field( 'post_name', get_queried_object_id() ) );
		if ( $service && ! empty( $service['seo_title'] ) ) {
			$title = [ 'title' => $service['seo_title'] ];
		}
	}

	return apply_filters( 'hds_document_title_parts', $title );
}
add_filter( 'document_title_parts', 'hds_document_title_parts', 20 );
