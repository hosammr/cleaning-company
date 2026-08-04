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
	// 1. Hero — reuse parts/hero (consistent with all other inner pages)
	$hero_title     = get_the_title();
	$hero_subtitle  = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
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
				echo hds_usp_card( __( 'Vrijblijvend', 'hds' ), __( 'Een offerte aanvragen is geheel vrijblijvend en verplicht u tot niets.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Op maat', 'hds' ), __( 'Iedere offerte wordt afgestemd op uw specifieke wensen en bedrijfssituatie.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Snelle reactie', 'hds' ), __( 'Wij streven ernaar binnen één werkdag te reageren op uw aanvraag.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>

	<?php
	// 4. Our process — reuse from page-service.php
	?>
	<section class="hds-process-section" aria-labelledby="quote-process-heading">
		<div class="container">
			<header class="hds-process-header">
				<h2 id="quote-process-heading"><?php esc_html_e( 'Onze werkwijze', 'hds' ); ?></h2>
			</header>
			<ol class="hds-process-steps">
				<li class="hds-process-step">
					<span class="hds-process-step__number" aria-hidden="true">1</span>
					<h3 class="hds-process-step__title"><?php esc_html_e( 'Aanvraag', 'hds' ); ?></h3>
					<p class="hds-process-step__desc"><?php esc_html_e( 'Neem contact met ons op en vertel ons uw wensen.', 'hds' ); ?></p>
				</li>
				<li class="hds-process-step">
					<span class="hds-process-step__number" aria-hidden="true">2</span>
					<h3 class="hds-process-step__title"><?php esc_html_e( 'Vrijblijvende offerte', 'hds' ); ?></h3>
					<p class="hds-process-step__desc"><?php esc_html_e( 'Wij analyseren uw situatie en sturen een duidelijke offerte.', 'hds' ); ?></p>
				</li>
				<li class="hds-process-step">
					<span class="hds-process-step__number" aria-hidden="true">3</span>
					<h3 class="hds-process-step__title"><?php esc_html_e( 'Planning', 'hds' ); ?></h3>
					<p class="hds-process-step__desc"><?php esc_html_e( 'Samen plannen we de werkzaamheden op een geschikt moment.', 'hds' ); ?></p>
				</li>
				<li class="hds-process-step">
					<span class="hds-process-step__number" aria-hidden="true">4</span>
					<h3 class="hds-process-step__title"><?php esc_html_e( 'Uitvoering', 'hds' ); ?></h3>
					<p class="hds-process-step__desc"><?php esc_html_e( 'Ons team voert de werkzaamheden zorgvuldig en volgens afspraak uit.', 'hds' ); ?></p>
				</li>
			</ol>
		</div>
	</section>

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
