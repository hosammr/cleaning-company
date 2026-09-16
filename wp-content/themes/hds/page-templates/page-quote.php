<?php
/**
 * Template Name: Offerte Aanvragen
 *
 * Quote request page with Gravity Forms integration.
 * Layout: Hero → Trust strip → Form → Process → USP → CTA.
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
	// 1. Hero — compact variant with the assigned hds_hero_image as background.
	$hero_title     = __( 'Vraag vrijblijvend een offerte aan', 'hds' );
	$hero_subtitle  = __( 'Ontvang binnen één werkdag een vrijblijvende offerte op maat voor uw bedrijf.', 'hds' );
	$hero_image_id  = (int) get_post_meta( get_queried_object_id(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	$hero_cta_text  = __( 'Direct aanvragen', 'hds' );
	$hero_cta_url   = '#offerte-formulier';

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );

	get_template_part( 'parts/hero' );
	?>

	<?php
	// 2. Trust strip — compact reassurance row.
	?>
	<section class="quote-trust-strip" aria-label="<?php esc_attr_e( 'Waarom u gerust een offerte kunt aanvragen', 'hds' ); ?>">
		<div class="container">
			<ul class="quote-trust-strip__list">
				<li class="quote-trust-strip__item">
					<span class="quote-trust-strip__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
					<span class="quote-trust-strip__label"><?php esc_html_e( 'Vrijblijvend', 'hds' ); ?></span>
				</li>
				<li class="quote-trust-strip__item">
					<span class="quote-trust-strip__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
					<span class="quote-trust-strip__label"><?php esc_html_e( 'Reactie binnen één werkdag', 'hds' ); ?></span>
				</li>
				<li class="quote-trust-strip__item">
					<span class="quote-trust-strip__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
					<span class="quote-trust-strip__label"><?php esc_html_e( 'Geen verplichtingen', 'hds' ); ?></span>
				</li>
			</ul>
		</div>
	</section>

	<?php
	// 3. Form section — Gravity Forms via the_content().
	?>
	<section class="quote-form-section">
		<div class="container">
			<div class="quote-page">
				<?php
				echo hds_section_header( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					__( 'Offerte aanvragen', 'hds' ),
					__( 'Vul onderstaand formulier in en wij nemen contact met u op.', 'hds' ),
					'center'
				);
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
			</div>
		</div>
	</section>

	<?php
	// 4. Zo werkt het — compact three-step process.
	?>
	<section class="quote-process-section" aria-labelledby="quote-process-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 class="section-header__heading" id="quote-process-heading"><?php esc_html_e( 'Zo werkt het', 'hds' ); ?></h2>
				<p class="section-header__subtitle"><?php esc_html_e( 'Vraag eenvoudig een vrijblijvende offerte aan. Wij nemen binnen één werkdag contact met u op.', 'hds' ); ?></p>
			</div>
			<ol class="quote-process">
				<li class="quote-process__step">
					<span class="quote-process__number" aria-hidden="true">1</span>
					<span class="quote-process__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 256 256" fill="none"><path d="M216 48v160a16 16 0 0 1-16 16H72a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h128a16 16 0 0 1 16 16Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M96 104h64M96 136h64M96 168h32" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
					<h3 class="quote-process__title"><?php esc_html_e( 'Offerte aanvragen', 'hds' ); ?></h3>
					<p class="quote-process__desc"><?php esc_html_e( 'Vul het formulier in met uw gegevens en wensen.', 'hds' ); ?></p>
				</li>
				<li class="quote-process__step">
					<span class="quote-process__number" aria-hidden="true">2</span>
					<span class="quote-process__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 256 256" fill="none"><path d="M224 152v32a16 16 0 0 1-17.6 16C123.8 197.3 58.7 132.2 56 49.6A16 16 0 0 1 72 32h32a16 16 0 0 1 16 13.6c1.4 11.6 4.2 22.8 8.4 33.4a16 16 0 0 1-3.6 16.9l-11.2 11.2a112.6 112.6 0 0 0 54.1 54.1l11.2-11.2a16 16 0 0 1 16.9-3.6c10.6 4.2 21.8 7 33.4 8.4A16 16 0 0 1 224 152Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
					<h3 class="quote-process__title"><?php esc_html_e( 'Contact', 'hds' ); ?></h3>
					<p class="quote-process__desc"><?php esc_html_e( 'Wij nemen binnen één werkdag contact met u op.', 'hds' ); ?></p>
				</li>
				<li class="quote-process__step">
					<span class="quote-process__number" aria-hidden="true">3</span>
					<span class="quote-process__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 256 256" fill="none"><path d="M216 48v160a16 16 0 0 1-16 16H72a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h128a16 16 0 0 1 16 16Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M104 140l22 22 40-48" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
					<h3 class="quote-process__title"><?php esc_html_e( 'Offerte op maat', 'hds' ); ?></h3>
					<p class="quote-process__desc"><?php esc_html_e( 'Wij stemmen de offerte af op uw situatie en wensen.', 'hds' ); ?></p>
				</li>
			</ol>
		</div>
	</section>

	<?php
	// 5. Why request a quotation? — reuse hds_usp_card.
	?>
	<section class="hds-usp-section" aria-labelledby="quote-usp-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 class="section-header__heading" id="quote-usp-heading"><?php esc_html_e( 'Waarom een offerte aanvragen?', 'hds' ); ?></h2>
			</div>
			<div class="hds-usp-grid">
				<?php
				echo hds_usp_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					__( 'Vrijblijvend', 'hds' ),
					__( 'Een offerte aanvragen is geheel vrijblijvend en verplicht u tot niets.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M87.8 69.6c-13.2-14.8-32.4-23.2-54.2-22.3C14.8 48.5 0 64.4 0 83.3v89.4c0 18.9 14.8 34.8 33.6 36.1 21.8 1 41-7.4 54.2-22.3L122 152h12l34.2 34.6c13.2 14.8 32.4 23.2 54.2 22.3 18.9-1.3 33.6-17.2 33.6-36.1V83.3c0-18.9-14.8-34.8-33.6-36.1-21.8-1-41 7.4-54.2 22.3L134 104h-12L87.8 69.6Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				);
				echo hds_usp_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					__( 'Op maat', 'hds' ),
					__( 'Iedere offerte wordt afgestemd op uw specifieke wensen en bedrijfssituatie.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><rect x="26" y="80" width="60" height="128" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><rect x="98" y="40" width="60" height="168" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><rect x="170" y="104" width="60" height="104" rx="8" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				);
				echo hds_usp_card( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					__( 'Snelle reactie', 'hds' ),
					__( 'Wij streven ernaar binnen één werkdag te reageren op uw aanvraag.', 'hds' ),
					'<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><circle cx="128" cy="128" r="96" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="128 72 128 128 168 152" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>'
				);
				?>
			</div>
		</div>
	</section>

	<?php
	// 6. Final CTA — direct phone contact.
	?>
	<section class="quote-contact-cta" aria-labelledby="quote-contact-cta-heading">
		<div class="container">
			<div class="quote-contact-cta__grid">
				<div class="quote-contact-cta__content">
					<h2 class="quote-contact-cta__heading" id="quote-contact-cta-heading"><?php esc_html_e( 'Liever direct contact?', 'hds' ); ?></h2>
					<p class="quote-contact-cta__description"><?php esc_html_e( 'Wij zijn op werkdagen telefonisch bereikbaar van 08:00 tot 17:00.', 'hds' ); ?></p>
				</div>
				<div class="quote-contact-cta__actions">
					<span class="quote-contact-cta__icon" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 256 256" fill="none"><path d="M224 152v32a16 16 0 0 1-17.6 16C123.8 197.3 58.7 132.2 56 49.6A16 16 0 0 1 72 32h32a16 16 0 0 1 16 13.6c1.4 11.6 4.2 22.8 8.4 33.4a16 16 0 0 1-3.6 16.9l-11.2 11.2a112.6 112.6 0 0 0 54.1 54.1l11.2-11.2a16 16 0 0 1 16.9-3.6c10.6 4.2 21.8 7 33.4 8.4A16 16 0 0 1 224 152Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
					<a class="quote-contact-cta__phone" href="<?php echo esc_url( 'tel:' . hds_get_phone_intl( hds_get_phone() ) ); ?>"><?php echo esc_html( hds_format_phone( hds_get_phone() ) ); ?></a>
					<a class="btn btn-cta" href="<?php echo esc_url( 'tel:' . hds_get_phone_intl( hds_get_phone() ) ); ?>"><?php esc_html_e( 'Bel ons direct', 'hds' ); ?></a>
					<p class="quote-contact-cta__email">
						<?php esc_html_e( 'Of stuur een e-mail naar', 'hds' ); ?>
						<?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</p>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
