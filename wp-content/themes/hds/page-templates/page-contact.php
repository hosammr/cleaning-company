<?php
/**
 * Template Name: Contact
 *
 * Used for Contact (P13).
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
	$subtitle     = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image_id = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	?>

	<section class="service-hero"<?php echo $hero_image_url ? ' style="background-image:url(' . esc_url( $hero_image_url ) . ')"' : ''; ?>>
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="service-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php esc_html_e( 'Vrijblijvende offerte', 'hds' ); ?>
			</a>
		</div>
	</section>

	<div class="container">
		<div class="about-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>

	<section class="hds-usp-section" aria-labelledby="contact-info-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="contact-info-heading"><?php esc_html_e( 'Contactgegevens', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Telefoon', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'E-mail', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Adres', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc">
						<?php if ( hds_get_address() ) : ?>
							<?php echo esc_html( hds_get_address() ); ?><br>
						<?php endif; ?>
						<?php echo esc_html( hds_get_postal_city() ); ?>
					</p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Openingstijden', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc">
						<?php
						$hours = get_theme_mod( 'hds_opening_hours' );
						if ( $hours ) :
							echo nl2br( esc_html( $hours ) );
						else :
							esc_html_e( 'Maandag t/m vrijdag van 08:00 tot 17:00', 'hds' );
						endif;
						?>
					</p>
				</article>
			</div>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="contact-form-heading">
		<div class="container">
			<div class="contact-layout">
				<div class="contact-form-column">
					<header class="hds-usp-header">
						<h2 id="contact-form-heading"><?php esc_html_e( 'Stuur ons een bericht', 'hds' ); ?></h2>
					</header>
					<div class="contact-form-placeholder">
						<p class="contact-form-placeholder__note">
							<?php esc_html_e( 'Gebruik een contactformulier-plugin om hier een formulier in te voegen.', 'hds' ); ?>
						</p>
					</div>
				</div>

				<aside class="contact-info-column" role="complementary">
					<div class="contact-info-block">
						<h3><?php esc_html_e( 'Direct contact', 'hds' ); ?></h3>
						<p><?php esc_html_e( 'Heeft u een vraag of wilt u direct een afspraak maken?', 'hds' ); ?></p>
						<div class="contact-info-item">
							<p><?php echo hds_get_phone_link( '', [ 'class' => 'btn btn--primary' ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						</div>
						<div class="contact-info-item">
							<p><?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						</div>
					</div>

					<?php
					$kvk = get_theme_mod( 'hds_kvk' );
					$btw = get_theme_mod( 'hds_btw' );
					if ( $kvk || $btw ) :
						?>
						<div class="contact-info-block">
							<h3><?php esc_html_e( 'Bedrijfsgegevens', 'hds' ); ?></h3>
							<?php if ( $kvk ) : ?>
								<p class="contact-info-item"><strong><?php esc_html_e( 'KVK', 'hds' ); ?>:</strong> <?php echo esc_html( $kvk ); ?></p>
							<?php endif; ?>
							<?php if ( $btw ) : ?>
								<p class="contact-info-item"><strong><?php esc_html_e( 'BTW', 'hds' ); ?>:</strong> <?php echo esc_html( $btw ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( hds_get_address() && hds_get_postal_city() ) : ?>
						<div class="contact-map-placeholder" aria-label="<?php esc_attr_e( 'Locatie op kaart', 'hds' ); ?>">
							<div class="map-consent-wrapper">
								<div class="map-placeholder-content">
									<span class="map-placeholder-icon" aria-hidden="true">&#128205;</span>
									<p><?php esc_html_e( 'Klik om Google Maps te laden', 'hds' ); ?></p>
									<p class="map-placeholder-note">
										<?php esc_html_e( 'Door de kaart te laden accepteert u de privacyvoorwaarden van Google.', 'hds' ); ?>
									</p>
									<button type="button" class="btn btn--secondary map-load-button">
										<?php esc_html_e( 'Kaart laden', 'hds' ); ?>
									</button>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="contact-service-area-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="contact-service-area-heading"><?php esc_html_e( 'Werkgebied', 'hds' ); ?></h2>
			</header>
			<p class="contact-service-area-text">
				<?php esc_html_e( 'HDS is actief in heel West-Brabant en Zeeland. Van Bergen op Zoom tot Roosendaal, van Goes tot Middelburg — ons team staat voor u klaar. Ook voor spoedklussen of grote projecten buiten deze regio kunt u contact met ons opnemen.', 'hds' ); ?>
			</p>
		</div>
	</section>

	<?php
	echo hds_cta_section(
		__( 'Klaar om samen te werken?', 'hds' ),
		__( 'Vraag vandaag nog een vrijblijvende offerte aan.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();
