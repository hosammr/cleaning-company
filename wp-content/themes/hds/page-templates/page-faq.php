<?php
/**
 * Template Name: FAQ
 *
 * Used for Veelgestelde Vragen (P18).
 * FAQ items via Yoast/Rank Math FAQ Block (auto-generates FAQPage schema).
 * ADR D-012: No hds_faq CPT — uses standard Page + FAQ Block.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<article class="faq-page">
			<h1><?php the_title(); ?></h1>

			<div class="faq-intro">
				<p>
					<?php esc_html_e( 'Hieronder vindt u antwoorden op veelgestelde vragen. Staat uw vraag er niet bij? Neem dan gerust contact met ons op.', 'hds' ); ?>
				</p>
			</div>

			<div class="faq-content">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>

			<div class="faq-cta">
				<h2><?php esc_html_e( 'Niet gevonden wat u zocht?', 'hds' ); ?></h2>
				<p>
					<?php esc_html_e( 'Bel ons op', 'hds' ); ?>
					<?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'of stuur een e-mail naar', 'hds' ); ?>
					<?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'Contact opnemen', 'hds' ); ?>
				</a>
			</div>
		</article>
	</div>
</main>

<?php
get_footer();
