<?php
/**
 * Template Name: Offerte Aanvragen
 *
 * Quote request page with extended form (Gravity Forms GF-2).
 * Layout: H1 + intro content + form (via the_content / GF shortcode).
 * MPS-001 G1.2: 13 fields including multi-checkbox, file upload, postcode validation.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="quote-page">
		<div class="container">
			<h1><?php the_title(); ?></h1>

			<div class="quote-intro">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>

			<div class="quote-cta-fallback">
				<p>
					<?php esc_html_e( 'Wilt u liever direct contact? Bel ons op', 'hds' ); ?>
					<?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'of stuur een e-mail naar', 'hds' ); ?>
					<?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
