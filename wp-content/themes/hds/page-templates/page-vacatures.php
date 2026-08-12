<?php
/**
 * Template Name: Vacatures
 *
 * Vacancies page: Hero → Intro → Job listing → CTA.
 * Uses the shared parts/hero, hds/job-listing block, and hds_cta_section.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
		// 1. Hero — reuse parts/hero
		$hero_title     = get_the_title();
		$hero_eyebrow   = get_post_meta( get_the_ID(), 'hds_eyebrow', true );
		$hero_subtitle  = get_post_meta( get_the_ID(), 'hds_subtitle', true );
		$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
		$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
		$hero_cta_text  = __( 'Bekijk vacatures', 'hds' );
		$hero_cta_url   = '#openstaande-vacatures';

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_eyebrow', $hero_eyebrow );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );

	get_template_part( 'parts/hero' );
	?>

		<?php
		// 2. Editable intro — renders WordPress page editor content, or default fallback
		while ( have_posts() ) :
			the_post();
			if ( ! empty( get_the_content() ) ) :
				?>
				<div class="container">
					<div class="vacancy-intro">
						<?php the_content(); ?>
					</div>
				</div>
				<?php
			else :
				?>
				<section class="vacancy-intro" aria-labelledby="vacancy-intro-heading">
					<div class="container">
						<h2 id="vacancy-intro-heading"><?php esc_html_e( 'Werken bij HDS', 'hds' ); ?></h2>
						<p><?php esc_html_e( 'Werken bij HDS betekent werken in een team waarin kwaliteit, betrouwbaarheid en samenwerking centraal staan.', 'hds' ); ?></p>
					</div>
				</section>
				<?php
			endif;
		endwhile;
		?>

	<?php
	// 3. Job listing — hds/job-listing block queries hds_vacancy CPT
	?>
	<section class="hds-vacancy-section" id="openstaande-vacatures" aria-label="<?php esc_attr_e( 'Openstaande vacatures', 'hds' ); ?>">
		<div class="container">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo hds_section_header(
				__( 'Openstaande vacatures', 'hds' ),
				'',
				'center'
			);
			echo do_blocks( '<!-- wp:hds/job-listing {"count":10,"showAll":true} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</div>
	</section>

	<?php
	// 4. CTA — open application fallback
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo hds_cta_section(
		__( 'Geen passende vacature gevonden?', 'hds' ),
		__( 'Stuur een open sollicitatie en wie weet maken wij samen een match.', 'hds' ),
		__( 'Open solliciteren', 'hds' ),
		home_url( '/contact/' )
	);
	?>
</main>

<?php
get_footer();
