<?php
/**
 * Template Name: Contact
 *
 * Used for Contact (P13).
 *
 * @package HDS
 */

$hds_form_mode    = isset( $_GET['type'] ) && 'sollicitatie' === $_GET['type'] ? 'sollicitatie' : 'contact';
$hds_vacancy      = isset( $_GET['vacature'] ) ? sanitize_text_field( wp_unslash( $_GET['vacature'] ) ) : '';
$hds_errors       = array();
$hds_posted       = array();
$hds_redirect_url = '';

if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['hds_contact_submit'] ) ) {

	if ( ! isset( $_POST['hds_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hds_contact_nonce'] ) ), 'hds_contact_submit' ) ) {
		$hds_errors[] = __( 'Beveiligingscontrole mislukt. Vernieuw de pagina en probeer het opnieuw.', 'hds' );
	} else {

		$hds_posted['name']    = isset( $_POST['hds_name'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_name'] ) ) : '';
		$hds_posted['company'] = isset( $_POST['hds_company'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_company'] ) ) : '';
		$hds_posted['email']   = isset( $_POST['hds_email'] ) ? sanitize_email( wp_unslash( $_POST['hds_email'] ) ) : '';
		$hds_posted['phone']   = isset( $_POST['hds_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_phone'] ) ) : '';
		$hds_consent           = ! empty( $_POST['hds_consent'] );

		if ( '' === $hds_posted['name'] ) {
			$hds_errors[] = __( 'Naam is verplicht.', 'hds' );
		}

		if ( '' === $hds_posted['email'] || ! is_email( $hds_posted['email'] ) ) {
			$hds_errors[] = __( 'Een geldig e-mailadres is verplicht.', 'hds' );
		}

		if ( ! $hds_consent ) {
			$hds_errors[] = __( 'U moet akkoord gaan met de privacyvoorwaarden.', 'hds' );
		}

		if ( 'sollicitatie' === $hds_form_mode ) {
			$hds_posted['vacancy']    = isset( $_POST['hds_vacancy'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_vacancy'] ) ) : '';
			$hds_posted['motivation'] = isset( $_POST['hds_motivation'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hds_motivation'] ) ) : '';

			if ( '' === $hds_posted['motivation'] ) {
				$hds_errors[] = __( 'Motivatie is verplicht.', 'hds' );
			}

			$cv_uploaded = isset( $_FILES['hds_cv'], $_FILES['hds_cv']['error'] ) && UPLOAD_ERR_NO_FILE !== $_FILES['hds_cv']['error'];

			if ( $cv_uploaded ) {
				$cv_error_code = (int) $_FILES['hds_cv']['error'];
				if ( UPLOAD_ERR_OK !== $cv_error_code ) {
					$hds_errors[] = __( 'Fout bij het uploaden van het bestand. Probeer het opnieuw.', 'hds' );
				} else {
					$cv_type = isset( $_FILES['hds_cv']['type'] ) ? sanitize_text_field( wp_unslash( $_FILES['hds_cv']['type'] ) ) : '';
					$cv_size = isset( $_FILES['hds_cv']['size'] ) ? (int) $_FILES['hds_cv']['size'] : 0;
					$allowed = array( 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' );

					if ( ! in_array( $cv_type, $allowed, true ) ) {
						$hds_errors[] = __( 'CV moet een PDF- of Word-document (.doc, .docx) zijn.', 'hds' );
					}
					if ( $cv_size > 5 * MB_IN_BYTES ) {
						$hds_errors[] = __( 'CV mag maximaal 5 MB zijn.', 'hds' );
					}
				}
			}
		} else {
			$hds_posted['subject'] = isset( $_POST['hds_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_subject'] ) ) : '';
			$hds_posted['message'] = isset( $_POST['hds_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hds_message'] ) ) : '';

			if ( '' === $hds_posted['message'] ) {
				$hds_errors[] = __( 'Bericht is verplicht.', 'hds' );
			}
		}

		if ( array() === $hds_errors ) {
			$to      = hds_get_email();
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );

			if ( 'sollicitatie' === $hds_form_mode ) {
				/* translators: %s: vacancy title */
				$subject_line = sprintf( __( 'Nieuwe sollicitatie: %s', 'hds' ), $hds_posted['vacancy'] );
				$body  = '<h2>' . esc_html__( 'Sollicitatie', 'hds' ) . '</h2>';
				$body .= '<p><strong>' . esc_html__( 'Naam', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['name'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Bedrijfsnaam', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['company'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'E-mailadres', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['email'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Telefoonnummer', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['phone'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Vacature', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['vacancy'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Motivatie', 'hds' ) . ':</strong><br>' . nl2br( esc_html( $hds_posted['motivation'] ) ) . '</p>';

				$attachments = array();
				$cv_attachment = isset( $_FILES['hds_cv'], $_FILES['hds_cv']['error'], $_FILES['hds_cv']['tmp_name'] ) && UPLOAD_ERR_OK === $_FILES['hds_cv']['error'];
				if ( $cv_attachment ) {
					$attachments[] = sanitize_text_field( wp_unslash( $_FILES['hds_cv']['tmp_name'] ) );
				}

				wp_mail( $to, $subject_line, $body, $headers, $attachments );
			} else {
				/* translators: %s: sender name */
				$subject_line = sprintf( __( 'Nieuw bericht van %s', 'hds' ), $hds_posted['name'] );
				$body  = '<h2>' . esc_html__( 'Contactbericht', 'hds' ) . '</h2>';
				$body .= '<p><strong>' . esc_html__( 'Naam', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['name'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Bedrijfsnaam', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['company'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'E-mailadres', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['email'] ) . '</p>';
				$body .= '<p><strong>' . esc_html__( 'Telefoonnummer', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['phone'] ) . '</p>';
				if ( '' !== $hds_posted['subject'] ) {
					$body .= '<p><strong>' . esc_html__( 'Onderwerp', 'hds' ) . ':</strong> ' . esc_html( $hds_posted['subject'] ) . '</p>';
				}
				$body .= '<p><strong>' . esc_html__( 'Bericht', 'hds' ) . ':</strong><br>' . nl2br( esc_html( $hds_posted['message'] ) ) . '</p>';

				wp_mail( $to, $subject_line, $body, $headers );
			}

			$hds_redirect_url = home_url( '/contact/bedankt/' );
		}
	}
}

if ( '' !== $hds_redirect_url ) {
	wp_safe_redirect( $hds_redirect_url );
	exit;
}

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
	$hero_title     = get_the_title();
	$hero_subtitle   = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image_id   = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url  = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	$hero_cta_text   = __( 'Vrijblijvende offerte', 'hds' );
	$hero_cta_url    = home_url( '/offerte-aanvragen/' );
	get_template_part( 'parts/hero' );
	?>

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
						<h2 id="contact-form-heading">
							<?php if ( 'sollicitatie' === $hds_form_mode ) : ?>
								<?php esc_html_e( 'Solliciteren', 'hds' ); ?>
							<?php else : ?>
								<?php esc_html_e( 'Stuur ons een bericht', 'hds' ); ?>
							<?php endif; ?>
						</h2>
					</header>

					<?php if ( array() !== $hds_errors ) : ?>
					<div class="hds-notification hds-notification--error" role="alert">
						<div class="hds-notification__message">
							<?php foreach ( $hds_errors as $err ) : ?>
								<p><?php echo esc_html( $err ); ?></p>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<form id="contactformulier" class="hds-quote-form" method="post" action="#contactformulier" enctype="multipart/form-data" novalidate>
						<?php wp_nonce_field( 'hds_contact_submit', 'hds_contact_nonce' ); ?>
						<input type="hidden" name="hds_contact_submit" value="1">

						<div class="hds-quote-form__row">
							<div class="hds-quote-form__field">
								<label class="hds-quote-form__label" for="hds-name">
									<?php esc_html_e( 'Naam', 'hds' ); ?>
									<span class="hds-quote-form__required" aria-hidden="true">*</span>
								</label>
								<input class="hds-quote-form__input" type="text" id="hds-name" name="hds_name" required
									value="<?php echo esc_attr( $hds_posted['name'] ?? '' ); ?>">
							</div>

							<div class="hds-quote-form__field">
								<label class="hds-quote-form__label" for="hds-company">
									<?php esc_html_e( 'Bedrijfsnaam', 'hds' ); ?>
								</label>
								<input class="hds-quote-form__input" type="text" id="hds-company" name="hds_company"
									value="<?php echo esc_attr( $hds_posted['company'] ?? '' ); ?>">
							</div>
						</div>

						<div class="hds-quote-form__row">
							<div class="hds-quote-form__field">
								<label class="hds-quote-form__label" for="hds-email">
									<?php esc_html_e( 'E-mailadres', 'hds' ); ?>
									<span class="hds-quote-form__required" aria-hidden="true">*</span>
								</label>
								<input class="hds-quote-form__input" type="email" id="hds-email" name="hds_email" required
									value="<?php echo esc_attr( $hds_posted['email'] ?? '' ); ?>">
							</div>

							<div class="hds-quote-form__field">
								<label class="hds-quote-form__label" for="hds-phone">
									<?php esc_html_e( 'Telefoonnummer', 'hds' ); ?>
								</label>
								<input class="hds-quote-form__input" type="tel" id="hds-phone" name="hds_phone"
									value="<?php echo esc_attr( $hds_posted['phone'] ?? '' ); ?>">
							</div>
						</div>

						<?php if ( 'sollicitatie' === $hds_form_mode ) : ?>

							<div class="hds-quote-form__field hds-quote-form__field--full">
								<label class="hds-quote-form__label" for="hds-vacancy">
									<?php esc_html_e( 'Vacature', 'hds' ); ?>
								</label>
								<input class="hds-quote-form__input" type="text" id="hds-vacancy" name="hds_vacancy" readonly
									value="<?php echo esc_attr( $hds_posted['vacancy'] ?? $hds_vacancy ); ?>">
							</div>

							<div class="hds-quote-form__field hds-quote-form__field--full">
								<label class="hds-quote-form__label" for="hds-motivation">
									<?php esc_html_e( 'Motivatie', 'hds' ); ?>
									<span class="hds-quote-form__required" aria-hidden="true">*</span>
								</label>
								<textarea class="hds-quote-form__textarea" id="hds-motivation" name="hds_motivation" required rows="6"><?php echo esc_textarea( $hds_posted['motivation'] ?? '' ); ?></textarea>
							</div>

							<div class="hds-quote-form__field hds-quote-form__field--full">
								<label class="hds-quote-form__label" for="hds-cv">
									<?php esc_html_e( 'CV (PDF of Word, max 5 MB)', 'hds' ); ?>
								</label>
								<input class="hds-quote-form__file" type="file" id="hds-cv" name="hds_cv"
									accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
							</div>

						<?php else : ?>

							<div class="hds-quote-form__field hds-quote-form__field--full">
								<label class="hds-quote-form__label" for="hds-subject">
									<?php esc_html_e( 'Onderwerp', 'hds' ); ?>
								</label>
								<input class="hds-quote-form__input" type="text" id="hds-subject" name="hds_subject"
									value="<?php echo esc_attr( $hds_posted['subject'] ?? '' ); ?>">
							</div>

							<div class="hds-quote-form__field hds-quote-form__field--full">
								<label class="hds-quote-form__label" for="hds-message">
									<?php esc_html_e( 'Uw bericht', 'hds' ); ?>
									<span class="hds-quote-form__required" aria-hidden="true">*</span>
								</label>
								<textarea class="hds-quote-form__textarea" id="hds-message" name="hds_message" required rows="6"><?php echo esc_textarea( $hds_posted['message'] ?? '' ); ?></textarea>
							</div>

						<?php endif; ?>

						<div class="hds-quote-form__field hds-quote-form__field--full">
							<label class="hds-quote-form__checkbox-label hds-quote-form__checkbox-label--block">
								<input class="hds-quote-form__checkbox" type="checkbox" name="hds_consent" value="1" required>
								<?php esc_html_e( 'Ik ga akkoord met het privacybeleid en de verwerking van mijn gegevens.', 'hds' ); ?>
								<span class="hds-quote-form__required" aria-hidden="true">*</span>
							</label>
						</div>

						<div class="hds-quote-form__actions">
							<button type="submit" class="btn hds-quote-form__submit btn--primary">
								<?php if ( 'sollicitatie' === $hds_form_mode ) : ?>
									<?php esc_html_e( 'Sollicitatie versturen', 'hds' ); ?>
								<?php else : ?>
									<?php esc_html_e( 'Bericht versturen', 'hds' ); ?>
								<?php endif; ?>
							</button>
						</div>
					</form>

				</div>

				<aside class="contact-info-column" role="complementary">
					<div class="contact-info-block">
						<h3><?php esc_html_e( 'Direct contact', 'hds' ); ?></h3>
						<p><?php esc_html_e( 'Heeft u een vraag of wilt u direct een afspraak maken?', 'hds' ); ?></p>
						<div class="contact-info-item">
							<p><?php echo hds_get_phone_link( '', array( 'class' => 'btn btn--primary' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
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
