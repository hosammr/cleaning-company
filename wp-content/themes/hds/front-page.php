<?php
/**
 * Front page template.
 *
 * Home page section order:
 *   1. Hero (via the_content — Block Editor)
 *   2. Trust strip (server-rendered)
 *   3. Service card grid (server-rendered, conditional)
 *   4. Why Hamdoun Schoonmaak (server-rendered)
 *   5. Branding video (server-rendered)
 *   6. Quality / safety section (server-rendered)
 *   7. Service image gallery (server-rendered, conditional)
 *   8. Client logo strip (server-rendered)
 *   9. Testimonials (conditional — hidden without real data)
 *  10. Offerte CTA banner
 *  11. Latest Blog Posts (conditional)
 *
 * ADR D-015: Conditional sections are hidden when they have no data.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	// Section 1: Hero Banner — rendered via the_content()
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;

	// Trust strip directly below the hero.
	echo hds_render_trust_strip(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// Services grid — services that exist and are published.
	$home_services = hds_get_visible_service_pages();
	if ( ! empty( $home_services ) ) {
		$services_landing = get_page_by_path( 'schoonmaakdiensten' );
		$header_action    = array();
		if ( $services_landing && 'publish' === $services_landing->post_status ) {
			$header_action = array(
				'text' => __( 'Alle diensten bekijken', 'hds' ),
				'url'  => get_permalink( $services_landing ),
			);
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Section HTML is composed from escaped fragments.
		echo hds_render_service_card_grid(
			$home_services,
			__( 'Professionele oplossingen voor elke ruimte', 'hds' ),
			__( 'Professionele schoonmaak- en onderhoudsdiensten voor uw bedrijf.', 'hds' ),
			4,
			__( 'Onze diensten', 'hds' ),
			$header_action,
			'left',
			'onze-diensten'
		);
	}

	// Why Hamdoun Schoonmaak — two-column section.
	echo hds_render_why_section(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// HAMDOUN branding video — company story after the customer-facing
	// reasons, before the quality & safety section.
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Section HTML is composed from escaped fragments.
	echo hds_render_branding_video_section();

	// Quality / safety section.
	echo hds_render_quality_section(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// Service image slider.
	echo hds_render_service_gallery(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// Client logo strip — confirmed opdrachtgevers (social proof).
	echo do_blocks( '<!-- wp:hds/client-logos {"variant":"strip"} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	// Section 4: Client Logo Carousel (conditional — renders only if testimonials CPT has entries)
	$has_testimonials = get_posts( [
		'post_type'      => 'hds_testimonial',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	] );
	if ( ! empty( $has_testimonials ) ) {
		?>
		<section class="home-testimonials">
			<div class="container">
				<?php echo hds_section_header( __( 'Wat onze klanten zeggen', 'hds' ), '', 'center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php
				echo do_blocks( '<!-- wp:hds/testimonial {"count":3,"showRating":true} /-->' );
				?>
				<p style="margin-top:var(--wp--preset--spacing--6);text-align:center">
					<a href="<?php echo esc_url( home_url( '/referenties/' ) ); ?>" class="btn btn--outline">
						<?php esc_html_e( 'Bekijk alle referenties', 'hds' ); ?>
					</a>
				</p>
			</div>
		</section>
		<?php
	}

	// Section 6: CTA Banner
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Section HTML is composed from escaped fragments.
	echo hds_cta_section(
		__( 'Wilt u een vrijblijvende offerte?', 'hds' ),
		__( 'Wij denken graag met u mee over de beste oplossing voor uw situatie.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' ),
		'light',
		array(
			__( 'Geen verplichtingen', 'hds' ),
			__( 'Reactie binnen één werkdag', 'hds' ),
			__( 'Offerte op maat', 'hds' ),
		),
		__( 'Vrijblijvende offerte', 'hds' ),
		array(
			'text' => __( 'Contact met ons opnemen', 'hds' ),
			'url'  => home_url( '/contact/' ),
		)
	);

	// Section 8: Latest Blog Posts (conditional)
	$latest_posts = get_posts( [
		'post_type'      => 'post',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
	] );
	if ( ! empty( $latest_posts ) ) {
		?>
		<section class="home-latest-posts">
			<div class="container">
				<?php echo hds_section_header( __( 'Laatste nieuws', 'hds' ), __( 'Tips, nieuws en updates van Hamdoun Schoonmaak.', 'hds' ), 'center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="hds-grid hds-grid--posts" style="--hds-grid-columns:3">
					<?php foreach ( $latest_posts as $post_item ) : ?>
						<article class="hds-post-card">
							<?php if ( has_post_thumbnail( $post_item ) ) : ?>
								<div class="hds-post-card__image">
									<a href="<?php echo esc_url( get_permalink( $post_item ) ); ?>" tabindex="-1" aria-hidden="true">
										<?php echo get_the_post_thumbnail( $post_item, 'hds-card', [ 'loading' => 'lazy' ] ); ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="hds-post-card__body">
								<h3 class="hds-post-card__title">
									<a href="<?php echo esc_url( get_permalink( $post_item ) ); ?>">
										<?php echo esc_html( get_the_title( $post_item ) ); ?>
									</a>
								</h3>
								<p class="hds-post-card__date">
									<?php echo esc_html( get_the_date( '', $post_item ) ); ?>
								</p>
								<p class="hds-post-card__excerpt">
									<?php echo esc_html( hds_truncate( get_the_excerpt( $post_item ) ?: wp_strip_all_tags( $post_item->post_content ), 100 ) ); ?>
								</p>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
	?>
</main>

<?php
get_footer();
