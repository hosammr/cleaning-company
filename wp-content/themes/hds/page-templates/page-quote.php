<?php
/**
 * Template Name: Offerte Aanvragen
 *
 * Quote request page with Gravity Forms integration.
 * Layout: Hero → Intro → USP → Process → Form → CTA.
 * Form rendered via the_content() (Gravity Forms shortcode).
 * MPS-001 G1.2: 13 fields including multi-checkbox, file upload, postcode validation.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
	// 1. Hero — compact light variant
	$hero_title     = __( 'Vraag vrijblijvend een offerte aan', 'hds' );
	$hero_subtitle  = __( 'Ontvang binnen één werkdag een vrijblijvende offerte op maat voor uw bedrijf.', 'hds' );
	$hero_image_url = '';
	$hero_cta_text  = __( 'Direct aanvragen', 'hds' );
	$hero_cta_url   = '#offerte-formulier';
	get_template_part( 'parts/hero' );
	?>

	<div class="container">
		<div class="quote-page">
			<?php
			// 2. Introduction
			echo hds_section_header(
				__( 'Zo werkt het', 'hds' ),
				__( 'Vraag eenvoudig een vrijblijvende offerte aan. Wij nemen binnen één werkdag contact met u op.', 'hds' ),
				'center'
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<div class="quote-intro">
				<p>
					<?php esc_html_e( 'Vul het onderstaande formulier in en ontvang een offerte op maat. Heeft u een specifieke vraag of wilt u liever direct contact? Bel of mail ons dan gerust.', 'hds' ); ?>
				</p>
			</div>
		</div>
	</div>

	<?php
	// 3. Why request a quotation? — reuse hds_usp_card
	?>
	<section class="hds-usp-section" aria-labelledby="quote-usp-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="quote-usp-heading"><?php esc_html_e( 'Waarom een offerte aanvragen?', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				echo hds_usp_card(
					__( 'Vrijblijvend', 'hds' ),
					__( 'Een offerte aanvragen is geheel vrijblijvend en verplicht u tot niets.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M87.8 69.6c-13.2-14.8-32.4-23.2-54.2-22.3C14.8 48.5 0 64.4 0 83.3v89.4c0 18.9 14.8 34.8 33.6 36.1 21.8 1 41-7.4 54.2-22.3L122 152h12l34.2 34.6c13.2 14.8 32.4 23.2 54.2 22.3 18.9-1.3 33.6-17.2 33.6-36.1V83.3c0-18.9-14.8-34.8-33.6-36.1-21.8-1-41 7.4-54.2 22.3L134 104h-12L87.8 69.6Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card(
					__( 'Op maat', 'hds' ),
					__( 'Iedere offerte wordt afgestemd op uw specifieke wensen en bedrijfssituatie.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><rect x="26" y="80" width="60" height="128" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><rect x="98" y="40" width="60" height="168" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><rect x="170" y="104" width="60" height="104" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card(
					__( 'Snelle reactie', 'hds' ),
					__( 'Wij streven ernaar binnen één werkdag te reageren op uw aanvraag.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><circle cx="128" cy="128" r="96" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="128 72 128 128 168 152" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>

	<?php
	// 4. Our process — reuse from page-service.php
	?>
	<?php
	echo hds_render_process_timeline(
		__( 'Onze werkwijze', 'hds' ),
		[
			[ 'title' => __( 'Aanvraag', 'hds' ), 'description' => __( 'Neem contact met ons op en vertel ons uw wensen.', 'hds' ) ],
			[ 'title' => __( 'Vrijblijvende offerte', 'hds' ), 'description' => __( 'Wij analyseren uw situatie en sturen een duidelijke offerte.', 'hds' ) ],
			[ 'title' => __( 'Planning', 'hds' ), 'description' => __( 'Samen plannen we de werkzaamheden op een geschikt moment.', 'hds' ) ],
			[ 'title' => __( 'Uitvoering', 'hds' ), 'description' => __( 'Ons team voert de werkzaamheden zorgvuldig en volgens afspraak uit.', 'hds' ) ],
		]
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<div class="container">
		<div class="quote-page">
			<?php
			// 5. Form section — Gravity Forms via the_content()
			echo hds_section_header(
				__( 'Offerte aanvragen', 'hds' ),
				__( 'Vul onderstaand formulier in en wij nemen contact met u op.', 'hds' ),
				'center'
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<div class="quote-intro" id="offerte-formulier">
				<?php
				while ( have_posts() ) :
					the_post();
					$raw_content = get_the_content();
					if ( hds_has_plugin_form( $raw_content ) ) :
						the_content();
					else :
						echo hds_render_quote_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					endif;
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
	</div>

	<?php
	// 6. Final CTA — direct phone contact
	echo hds_cta_section(
		__( 'Liever direct contact?', 'hds' ),
		__( 'Wij zijn op werkdagen telefonisch bereikbaar van 08:00 tot 17:00.', 'hds' ),
		hds_get_phone(),
		'tel:' . hds_esc_tel( hds_get_phone() )
	);
	?>
</main>

<?php
get_footer();
