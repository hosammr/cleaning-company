<?php
/**
 * Service page helpers.
 *
 * Functions for querying, rendering, and cross-linking service pages.
 * Epic 1 — Core Pages & Conversion.
 *
 * @package HDS
 */

/**
 * Service page URL map.
 *
 * Maps service slugs to their expected URLs for cross-linking.
 * Mirrors the MPS-001 Page Inventory (P02-P08).
 */
function hds_get_service_url_map(): array {
	return [
		'glasbewassing'            => __( 'Glasbewassing', 'hds' ),
		'gevelreiniging'           => __( 'Gevelreiniging', 'hds' ),
		'reguliere-schoonmaak'     => __( 'Reguliere Schoonmaak', 'hds' ),
		'vloeronderhoud'           => __( 'Vloeronderhoud', 'hds' ),
		'vve-service'              => __( 'VvE Service', 'hds' ),
			'oplevering-schoonmaak'    => __( 'Oplevering Schoonmaak', 'hds' ),
			'kantoor-schoonmaak'           => __( 'Kantoor schoonmaak', 'hds' ),
			'scholen-en-kinderopvang-reiniging'   => __( 'Scholen en Kinderopvang reiniging', 'hds' ),
		];
}

	/**
	 * Cross-sell service mapping.
 *
 * Defines which related services to show on each service page.
 * MPS-001 Section E specifies cross-links per service.
 */
function hds_get_cross_sell_map(): array {
	return [
		'glasbewassing'            => [ 'gevelreiniging', 'reguliere-schoonmaak', 'oplevering-schoonmaak' ],
		'gevelreiniging'           => [ 'glasbewassing', 'vloeronderhoud', 'oplevering-schoonmaak' ],
		'reguliere-schoonmaak'     => [ 'vloeronderhoud', 'glasbewassing', 'vve-service' ],
		'vloeronderhoud'           => [ 'reguliere-schoonmaak', 'oplevering-schoonmaak', 'kantoor-schoonmaak' ],
		'vve-service'              => [ 'reguliere-schoonmaak', 'glasbewassing', 'vloeronderhoud' ],
			'oplevering-schoonmaak'    => [ 'reguliere-schoonmaak', 'glasbewassing', 'vloeronderhoud' ],
			'kantoor-schoonmaak'       => [ 'glasbewassing', 'gevelreiniging', 'vloeronderhoud' ],
			'scholen-en-kinderopvang-reiniging' => [ 'kantoor-schoonmaak', 'reguliere-schoonmaak', 'vloeronderhoud' ],
		];
	}

/**
 * Get cross-sell service post objects for the current service page.
 *
 * Falls back to hds_get_service_pages() if the current page slug
 * is not in the cross-sell map.
 *
 * @return WP_Post[] Array of service page post objects.
 */
function hds_get_cross_sell_services(): array {
	$slug       = get_post_field( 'post_name', get_the_ID() );
	$map        = hds_get_cross_sell_map();
	$slugs      = $map[ $slug ] ?? [];
	$current_id = get_the_ID();

	if ( empty( $slugs ) ) {
		$services = hds_get_service_pages( 4 );
		$services = array_filter( $services, function ( $page ) use ( $current_id ) {
			return $page->ID !== $current_id;
		} );
		$services = array_values( $services );
		return array_slice( $services, 0, 3 );
	}

	$posts = [];
	foreach ( $slugs as $target_slug ) {
		$page = get_page_by_path( $target_slug );
		if ( $page && $page->post_status === 'publish' ) {
			$posts[] = $page;
		}
	}

	if ( empty( $posts ) ) {
		$services = hds_get_service_pages( 4 );
		$services = array_filter( $services, function ( $page ) use ( $current_id ) {
			return $page->ID !== $current_id;
		} );
		$services = array_values( $services );
		return array_slice( $services, 0, 3 );
	}

	return $posts;
}

/**
 * Render the core service card HTML shared by templates and custom blocks.
 *
 * @param WP_Post $post       The service page post object.
 * @param bool    $show_image Whether to render the hero image.
 * @param bool    $show_icon  Whether to render the service icon.
 * @return string HTML for the service card.
 */
function hds_render_service_card_core( \WP_Post $post, bool $show_image = true, bool $show_icon = true ): string {
	$icon     = get_post_meta( $post->ID, 'hds_service_icon', true );
	$image_id = get_post_thumbnail_id( $post );

	if ( ! $image_id ) {
		$image_id = get_post_meta( $post->ID, 'hds_hero_image', true );
	}
	$excerpt  = has_excerpt( $post )
		? get_the_excerpt( $post )
		: wp_trim_words( wp_strip_all_tags( $post->post_content ), 20, '&hellip;' );

	if ( '' === $excerpt ) {
		$service = hds_get_service( $post->post_name );
		if ( $service && ! empty( $service['subtitle'] ) ) {
			$excerpt = $service['subtitle'];
		}
	}

	ob_start();
	?>
		<article class="hds-service-card">
			<?php if ( $show_image ) : ?>
				<div class="hds-service-card__image">
					<?php if ( $image_id ) : ?>
						<?php echo wp_get_attachment_image( (int) $image_id, 'hds-card', false, [
							'alt'     => get_the_title( $post ),
							'loading' => 'lazy',
						] ); ?>
					<?php else : ?>
						<div class="hds-service-card__placeholder" aria-hidden="true">
							<?php if ( $show_icon && $icon ) : ?>
								<span class="hds-service-card__placeholder-icon"><?php echo esc_html( $icon ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="hds-service-card__body">
				<?php if ( $show_icon && $icon ) : ?>
					<span class="hds-service-card__icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
				<?php endif; ?>
				<h3 class="hds-service-card__title">
					<a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
						<?php echo esc_html( get_the_title( $post ) ); ?>
					</a>
				</h3>
				<p class="hds-service-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
				<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'Lees meer', 'hds' ); ?>
					<span class="screen-reader-text">
						<?php echo esc_html( sprintf( __( 'over %s', 'hds' ), get_the_title( $post ) ) ); ?>
					</span>
				</a>
			</div>
		</article>
	<?php
	return ob_get_clean();
}

/**
 * Render a single service card (template context — always shows icon).
 *
 * @param WP_Post $post The service page post object.
 * @return string HTML for the service card.
 */
function hds_render_service_card_html( \WP_Post $post ): string {
	return hds_render_service_card_core( $post, true, true );
}

/**
 * Render a service card grid.
 *
 * @param array  $posts        Array of WP_Post service page objects.
 * @param string $title        Optional section title.
 * @param string $subtitle     Optional section subtitle.
 * @param int    $max_columns  Maximum columns (3 default).
 * @param string $eyebrow      Optional eyebrow label above the title.
 * @param array  $header_action Optional header link with 'text' and 'url' keys.
 * @param string $header_align 'center' (default) or 'left'.
 * @param string $section_id   Optional HTML id attribute for the section.
 * @return string HTML for the service card grid section.
 */
function hds_render_service_card_grid( array $posts, string $title = '', string $subtitle = '', int $max_columns = 3, string $eyebrow = '', array $header_action = array(), string $header_align = 'center', string $section_id = '' ): string {
	if ( empty( $posts ) ) {
		return '';
	}

	$columns     = min( count( $posts ), $max_columns );
	$grid_style  = '--hds-grid-columns:' . $columns;
	$align_class = 'center' === $header_align ? ' section-header--center' : '';
	$has_action  = ! empty( $header_action['text'] ) && ! empty( $header_action['url'] );

	ob_start();
	?>
	<section class="service-card-grid-section"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
		<div class="container">
			<?php if ( $title || $eyebrow ) : ?>
				<div class="section-header service-card-grid-header<?php echo esc_attr( $align_class ); ?>">
					<div class="service-card-grid-header__intro">
						<?php if ( $eyebrow ) : ?>
							<p class="section-header__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
						<?php endif; ?>
						<?php if ( $title ) : ?>
							<h2 class="section-header__heading"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>
						<?php if ( $subtitle ) : ?>
							<p class="section-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
						<?php endif; ?>
					</div>
					<?php if ( $has_action ) : ?>
						<a class="service-card-grid-header__link" href="<?php echo esc_url( $header_action['url'] ); ?>">
							<?php echo esc_html( $header_action['text'] ); ?>
							<span aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="hds-grid hds-grid--service-cards" style="<?php echo esc_attr( $grid_style ); ?>">
				<?php foreach ( $posts as $post ) : ?>
					<?php echo hds_render_service_card_html( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the cross-sell services section for a service page.
 *
 * @return string HTML for the cross-sell services section.
 */
function hds_render_cross_sell_section(): string {
	$services = hds_get_cross_sell_services();

	if ( empty( $services ) ) {
		return '';
	}

	$title = __( 'Gerelateerde diensten', 'hds' );

	ob_start();
	?>
	<section class="cross-sell-section">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 class="section-header__heading"><?php echo esc_html( $title ); ?></h2>
				<p class="section-header__subtitle">
					<?php esc_html_e( 'Ontdek ook onze andere diensten die voor u interessant kunnen zijn.', 'hds' ); ?>
				</p>
			</div>
			<?php echo hds_render_service_card_grid( $services, '', '', 3 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Get service pages grouped by category for landing pages.
 *
 * Returns one group matching the active MPS-001 sitemap:
 *   'glas-en-gevel' => [glasbewassing, gevelreiniging]
 *
 * The 'schoonmaakdiensten' category landing page was removed (F9-F);
 * its individual services are reached directly via the "Diensten" menu.
 *
 * @return array<string, WP_Post[]> Grouped service pages.
 */
function hds_get_service_page_groups(): array {
	$glazen_gevel = [
		'glasbewassing',
		'gevelreiniging',
	];

	$groups = [
		'glas-en-gevel' => [],
	];

	foreach ( $glazen_gevel as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && $page->post_status === 'publish' ) {
			$groups['glas-en-gevel'][] = $page;
		}
	}

	return $groups;
}

/**
 * Get visible service pages (pages that exist + are published).
 *
 * Wraps hds_get_service_pages() with a status check.
 *
 * @return WP_Post[]
 */
function hds_get_visible_service_pages(): array {
	$services = hds_get_service_pages();
	return array_filter( $services, function ( $page ) {
		return $page->post_status === 'publish';
	} );
}

/**
 * Get service page by slug.
 *
 * @param string $slug Page slug.
 * @return WP_Post|null
 */
function hds_get_service_by_slug( string $slug ): ?\WP_Post {
	$page = get_page_by_path( $slug );
	return ( $page && $page->post_status === 'publish' ) ? $page : null;
}

/**
 * Get the Dutch label for a single industry slug.
 *
 * Maps only the industry slugs that actually occur in hds_get_services().
 * Unknown slugs return null so callers can safely skip unsupported groups.
 *
 * @param string $slug Industry slug.
 * @return string|null Dutch label, or null when the slug is not supported.
 */
function hds_get_industry_label( string $slug ): ?string {
	$labels = hds_get_industry_data();
	return $labels[ $slug ] ?? null;
}

/**
 * Get the industry slug → Dutch label map.
 *
 * Only the slugs present in inc/services.php are mapped. No unsupported
 * industries are invented.
 *
 * @return array<string, string>
 */
function hds_get_industry_data(): array {
	return [
		'kantoren'                 => __( 'Kantoren', 'hds' ),
		'zorginstellingen'         => __( 'Zorginstellingen', 'hds' ),
		'scholen'                  => __( 'Scholen', 'hds' ),
		'retail'                   => __( 'Retail', 'hds' ),
		'overheid'                 => __( 'Overheid', 'hds' ),
		'bedrijfsverzamelgebouwen' => __( 'Bedrijfsverzamelgebouwen', 'hds' ),
		'basisscholen'             => __( 'Basisscholen', 'hds' ),
		'middelbare-scholen'       => __( 'Middelbare scholen', 'hds' ),
		'kinderopvang'             => __( 'Kinderopvang', 'hds' ),
		'woningen'                 => __( 'Woningen', 'hds' ),
		'winkelpanden'             => __( 'Winkelpanden', 'hds' ),
	];
}

/**
 * Get the canonical "Waarom Hamdoun?" company strengths.
 *
 * These are the five established company strengths used on the homepage.
 * The homepage output (hds_render_why_section) is left untouched; this
 * helper provides a single safe data source for the service pages so the
 * copy cannot drift.
 *
 * @return array<int, string> List of Dutch strength labels.
 */
function hds_get_why_hamdoun_strengths(): array {
	return [
		__( 'Vast opgeleid personeel', 'hds' ),
		__( 'Eén vast aanspreekpunt', 'hds' ),
		__( 'Flexibele dienstverlening', 'hds' ),
		__( 'Professionele werkwijze', 'hds' ),
		__( 'Duurzame dienstverlening', 'hds' ),
	];
}

/**
 * Get the approved generic service workflow steps.
 *
 * Used as the Phase-1 default for every service until service-specific
 * workflows are supplied. The steps only reflect the existing approved
 * content and do not invent any new claims.
 *
 * @return array<int, array{title:string, description:string}>
 */
function hds_get_default_workflow(): array {
	return [
		[ 'title' => __( 'Aanvraag', 'hds' ), 'description' => __( 'Neem contact met ons op en vertel ons uw wensen.', 'hds' ) ],
		[ 'title' => __( 'Vrijblijvende offerte', 'hds' ), 'description' => __( 'Wij analyseren uw situatie en sturen een duidelijke offerte.', 'hds' ) ],
		[ 'title' => __( 'Planning', 'hds' ), 'description' => __( 'Samen plannen we de werkzaamheden op een geschikt moment.', 'hds' ) ],
		[ 'title' => __( 'Uitvoering', 'hds' ), 'description' => __( 'Ons team voert de werkzaamheden zorgvuldig en volgens afspraak uit.', 'hds' ) ],
	];
}

/**
 * Normalize the VvE service display title.
 *
 * The stored page title and menu items still use the legacy all-caps
 * "VVE" abbreviation. Correct it at render time so the proper Dutch
 * abbreviation "VvE" is shown everywhere without altering stored data.
 *
 * @param string $title   The post title.
 * @param int    $post_id Post ID.
 * @return string
 */
function hds_normalize_vve_service_title( string $title, int $post_id = 0 ): string {
	if ( $post_id && 'vve-service' === get_post_field( 'post_name', $post_id ) && preg_match( '/\bVVE\b/', $title ) ) {
		return __( 'VvE service', 'hds' );
	}
	return $title;
}
add_filter( 'the_title', 'hds_normalize_vve_service_title', 10, 2 );

/**
 * Normalize the VvE service document title.
 *
 * The browser tab title (and OG title, which reuses it) still carries the
 * legacy all-caps "VVE" from the stored page title. Correct it at render
 * time so no incorrect abbreviation remains, without altering stored data.
 *
 * @param array $title Document title parts.
 * @return array
 */
function hds_normalize_vve_service_document_title( array $title ): array {
	if ( is_page() && 'vve-service' === get_post_field( 'post_name', get_queried_object_id() ) && isset( $title['title'] ) && preg_match( '/\bVVE\b/', $title['title'] ) ) {
		$title['title'] = __( 'VvE service', 'hds' );
	}
	return $title;
}
add_filter( 'document_title_parts', 'hds_normalize_vve_service_document_title' );

/**
 * Normalize the VvE menu item label.
 *
 * Mirrors hds_normalize_vve_service_title() for navigation items so the
 * header, mobile and footer menus show "VvE service" instead of "VVE Service".
 *
 * @param \WP_Post $menu_item Menu item object.
 * @return \WP_Post
 */
function hds_normalize_vve_service_menu_title( \WP_Post $menu_item ): \WP_Post {
	if ( isset( $menu_item->title, $menu_item->object_id ) && preg_match( '/\bVVE\b/', $menu_item->title ) ) {
		$linked = (int) $menu_item->object_id;
		if ( $linked && 'vve-service' === get_post_field( 'post_name', $linked ) ) {
			$menu_item->title = __( 'VvE service', 'hds' );
		}
	}
	return $menu_item;
}
add_filter( 'wp_setup_nav_menu_item', 'hds_normalize_vve_service_menu_title' );