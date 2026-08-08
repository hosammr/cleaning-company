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
		'vve-service'              => __( 'VVE Service', 'hds' ),
			'oplevering-schoonmaak'    => __( 'Oplevering Schoonmaak', 'hds' ),
			'industriele-schoonmaak'   => __( 'Industriële Schoonmaak', 'hds' ),
			'kantoor-schoonmaak'       => __( 'Kantoor schoonmaak', 'hds' ),
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
		'vloeronderhoud'           => [ 'reguliere-schoonmaak', 'oplevering-schoonmaak' ],
		'vve-service'              => [ 'reguliere-schoonmaak', 'glasbewassing' ],
			'oplevering-schoonmaak'    => [ 'reguliere-schoonmaak', 'glasbewassing', 'vloeronderhoud' ],
			'industriele-schoonmaak'   => [ 'reguliere-schoonmaak', 'gevelreiniging' ],
			'kantoor-schoonmaak'       => [ 'glasbewassing', 'gevelreiniging', 'vloeronderhoud' ],
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
	$image_id = get_post_meta( $post->ID, 'hds_hero_image', true );
	$excerpt  = has_excerpt( $post )
		? get_the_excerpt( $post )
		: wp_trim_words( wp_strip_all_tags( $post->post_content ), 20, '&hellip;' );

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
 * @param array  $posts       Array of WP_Post service page objects.
 * @param string $title        Optional section title.
 * @param string $subtitle     Optional section subtitle.
 * @param int    $max_columns  Maximum columns (3 default).
 * @return string HTML for the service card grid section.
 */
function hds_render_service_card_grid( array $posts, string $title = '', string $subtitle = '', int $max_columns = 3 ): string {
	if ( empty( $posts ) ) {
		return '';
	}

	$columns    = min( count( $posts ), $max_columns );
	$grid_style = '--hds-grid-columns:' . $columns;

	ob_start();
	?>
	<section class="service-card-grid-section">
		<div class="container">
			<?php if ( $title ) : ?>
				<div class="section-header section-header--center">
					<h2 class="section-header__heading"><?php echo esc_html( $title ); ?></h2>
					<?php if ( $subtitle ) : ?>
						<p class="section-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
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
 * Returns two groups matching the MPS-001 sitemap:
 *   'glas-en-gevel' => [glasbewassing, gevelreiniging]
 *   'schoonmaakdiensten' => [reguliere-schoonmaak, vloeronderhoud, vve-service, oplevering-schoonmaak, industriele-schoonmaak]
 *
 * @return array<string, WP_Post[]> Grouped service pages.
 */
function hds_get_service_page_groups(): array {
	$glazen_gevel = [
		'glasbewassing',
		'gevelreiniging',
	];

		$schoonmaak = [
			'reguliere-schoonmaak',
			'vloeronderhoud',
			'vve-service',
			'oplevering-schoonmaak',
			'industriele-schoonmaak',
			'kantoor-schoonmaak',
		];

	$groups = [
		'glas-en-gevel'        => [],
		'schoonmaakdiensten'   => [],
	];

	foreach ( $glazen_gevel as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && $page->post_status === 'publish' ) {
			$groups['glas-en-gevel'][] = $page;
		}
	}

	foreach ( $schoonmaak as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && $page->post_status === 'publish' ) {
			$groups['schoonmaakdiensten'][] = $page;
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
