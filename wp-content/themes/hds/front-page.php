<?php
/**
 * Front page template.
 *
 * Home page with 8 content sections per MPS-001 Section D2:
 *   1. Hero Banner (tagline + USP + CTA)
 *   2. Service Card Grid (7 services, conditional)
 *   3. USP Grid (via the_content block pattern)
 *   4. Client Logo Carousel (conditional — hide if empty)
 *   5. Testimonial Block (conditional — hide if empty)
 *   6. CTA Banner (all pages)
 *   7. Service Area (via the_content)
 *   8. Latest Blog Posts (conditional)
 *
 * Sections 2, 4, 5, and 8 are server-rendered because they depend
 * on dynamic data (published services, CPT entries, blog posts).
 * Sections 1, 3, 6, and 7 are rendered via the_content() (Block Editor).
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

	// Section 2: Service Card Grid — services that exist and are published
	$home_services = hds_get_visible_service_pages();
	if ( ! empty( $home_services ) ) {
		echo hds_render_service_card_grid(
			$home_services,
			__( 'Onze diensten', 'hds' ),
			__( 'Professionele schoonmaak- en onderhoudsdiensten voor uw bedrijf.', 'hds' ),
			3
		);
	}

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
					<a href="<?php echo esc_url( home_url( '/reviews/' ) ); ?>" class="btn btn--outline">
						<?php esc_html_e( 'Bekijk alle referenties', 'hds' ); ?>
					</a>
				</p>
			</div>
		</section>
		<?php
	}

	// Section 6: CTA Banner
	echo hds_cta_section(
		__( 'Wilt u een vrijblijvende offerte?', 'hds' ),
		__( 'Wij denken graag met u mee over de beste oplossing voor uw situatie.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
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
				<?php echo hds_section_header( __( 'Laatste nieuws', 'hds' ), __( 'Tips, nieuws en updates van HDS Onderhoudsdiensten.', 'hds' ), 'center' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
