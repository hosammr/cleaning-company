<?php
/**
 * Template Name: Reviews
 *
 * Customer reviews page with featured and full testimonial grid.
 * Layout: Hero → Intro → Featured → Grid → USP → CTA.
 * Testimonials rendered via hds/testimonial block (shared with front-page).
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
	$hero_subtitle  = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	$hero_cta_text  = __( 'Vrijblijvende offerte', 'hds' );
	$hero_cta_url   = home_url( '/offerte-aanvragen/' );
	get_template_part( 'parts/hero' );
	?>

	<div class="container">
		<div class="quote-page">
			<?php
			// 2. Introduction
			echo hds_section_header(
				__( 'Wat onze klanten zeggen', 'hds' ),
				__( 'Wij zijn trots op de samenwerking met onze klanten. Lees hier wat zij over HDS Onderhoudsdiensten zeggen.', 'hds' ),
				'center'
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>

			<?php
			while ( have_posts() ) :
				the_post();
				if ( ! empty( get_the_content() ) ) :
					?>
					<div class="quote-intro">
						<?php the_content(); ?>
					</div>
					<?php
				endif;
			endwhile;
			?>
		</div>
	</div>

	<?php
	// 3. Featured testimonials — reuse hds/testimonial block (shared with front-page)
	$featured_testimonials = get_posts( [
		'post_type'      => 'hds_testimonial',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	] );
	if ( ! empty( $featured_testimonials ) ) :
		?>
		<section class="home-testimonials" aria-labelledby="reviews-featured-heading">
			<div class="container">
				<?php
				echo hds_section_header(
					__( 'Uitgelichte referenties', 'hds' ),
					'',
					'center'
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo do_blocks( '<!-- wp:hds/testimonial {"count":3,"showRating":true} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// 4. Testimonial grid — all testimonials shared via same block
	$all_testimonials = get_posts( [
		'post_type'      => 'hds_testimonial',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'fields'         => 'ids',
	] );
	if ( ! empty( $all_testimonials ) ) :
		?>
		<section class="home-testimonials" aria-labelledby="reviews-all-heading">
			<div class="container">
				<?php
				echo hds_section_header(
					__( 'Alle referenties', 'hds' ),
					'',
					'center'
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo do_blocks( '<!-- wp:hds/testimonial {"count":12,"showRating":true} /-->' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	// 5. Why clients trust HDS — reuse hds_usp_card
	?>
	<section class="hds-usp-section" aria-labelledby="reviews-trust-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="reviews-trust-heading"><?php esc_html_e( 'Waarom klanten voor HDS kiezen', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				echo hds_usp_card( __( 'Betrouwbaarheid', 'hds' ), __( 'Afspraak is afspraak. U kunt op ons rekenen, elke dag weer.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Kwaliteit', 'hds' ), __( 'Wij leveren consequent hoge kwaliteit met oog voor detail.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Flexibiliteit', 'hds' ), __( 'Wij stemmen onze werkzaamheden af op uw planning en bedrijfsprocessen.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Persoonlijk contact', 'hds' ), __( 'Geen callcenter, maar een vaste contactpersoon die u kent.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>

	<?php
	// 6. Final CTA — reuse hds_cta_section
	echo hds_cta_section(
		__( 'Klaar om ook klant te worden?', 'hds' ),
		__( 'Vraag vandaag nog een vrijblijvende offerte aan.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</main>

<?php
get_footer();
