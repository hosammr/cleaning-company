<?php
/**
 * Template Name: Bedankt
 *
 * Thank-you page shown after form submission.
 * Dynamic message based on ?type= query parameter.
 * MPS-001 Section G1.1: Post-submit redirect to /bedankt/?type=contact or ?type=offerte.
 *
 * Graceful degradation: noindex meta prevents indexing.
 * Excluded from XML sitemap (SEO plugin handles this via noindex).
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<section class="thank-you-page">
		<div class="container">
			<?php
			$type = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'contact';

			if ( $type === 'offerte' ) :
				?>
				<header class="thank-you-header">
					<h1><?php esc_html_e( 'Bedankt voor uw offerteaanvraag', 'hds' ); ?></h1>
					<p class="thank-you-message">
						<?php esc_html_e( 'Wij hebben uw aanvraag ontvangen en nemen zo spoedig mogelijk contact met u op.', 'hds' ); ?>
					</p>
				</header>
				<?php
			else :
				?>
				<header class="thank-you-header">
					<h1><?php esc_html_e( 'Bedankt voor uw bericht', 'hds' ); ?></h1>
					<p class="thank-you-message">
						<?php esc_html_e( 'Uw bericht is succesvol verzonden. Wij streven ernaar binnen 1 werkdag te reageren.', 'hds' ); ?>
					</p>
				</header>
				<?php
			endif;
			?>

			<div class="thank-you-fallback">
				<h2><?php esc_html_e( 'Direct contact nodig?', 'hds' ); ?></h2>
				<p>
					<?php esc_html_e( 'Bel ons op', 'hds' ); ?>
					<?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</p>
			</div>

			<div class="thank-you-links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'Terug naar home', 'hds' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/schoonmaakdiensten/' ) ); ?>" class="btn btn--outline">
					<?php esc_html_e( 'Bekijk onze diensten', 'hds' ); ?>
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
