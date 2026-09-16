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
$hds_success      = isset( $_GET['hds_success'] ) && '1' === $_GET['hds_success'];
$hds_errors       = array();
$hds_posted       = array();
$hds_redirect_url = '';

if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['hds_contact_submit'] ) ) {

	if ( ! isset( $_POST['hds_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hds_contact_nonce'] ) ), 'hds_contact_submit' ) ) {
		$hds_errors['security'] = __( 'Beveiligingscontrole mislukt. Vernieuw de pagina en probeer het opnieuw.', 'hds' );
	} elseif ( ! empty( $_POST['hds_website'] ) || empty( $_POST['hds_form_ts'] ) || ( time() - (int) $_POST['hds_form_ts'] ) < (int) HDS_Config::get( 'features.form_min_submit_seconds', 3 ) ) {
		$hds_errors['security'] = __( 'Uw bericht kon niet worden verzonden. Probeer het opnieuw of neem telefonisch contact met ons op.', 'hds' );
	} else {

		$hds_posted['name']    = isset( $_POST['hds_name'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_name'] ) ) : '';
		$hds_posted['company'] = isset( $_POST['hds_company'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_company'] ) ) : '';
		$hds_posted['email']   = isset( $_POST['hds_email'] ) ? sanitize_email( wp_unslash( $_POST['hds_email'] ) ) : '';
		$hds_posted['phone']   = isset( $_POST['hds_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_phone'] ) ) : '';
		$hds_consent           = ! empty( $_POST['hds_consent'] );

		if ( '' === $hds_posted['name'] ) {
			$hds_errors['name'] = __( 'Naam is verplicht.', 'hds' );
		}

		if ( '' === $hds_posted['email'] || ! is_email( $hds_posted['email'] ) ) {
			$hds_errors['email'] = __( 'Een geldig e-mailadres is verplicht.', 'hds' );
		}

		if ( ! $hds_consent ) {
			$hds_errors['consent'] = __( 'U moet akkoord gaan met de privacyvoorwaarden.', 'hds' );
		}

		if ( 'sollicitatie' === $hds_form_mode ) {
			$hds_posted['vacancy']    = isset( $_POST['hds_apply_vacancy'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_apply_vacancy'] ) ) : '';
			$hds_posted['motivation'] = isset( $_POST['hds_motivation'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hds_motivation'] ) ) : '';

			if ( '' === $hds_posted['motivation'] ) {
				$hds_errors['motivation'] = __( 'Motivatie is verplicht.', 'hds' );
			}

			$cv_uploaded = isset( $_FILES['hds_cv'], $_FILES['hds_cv']['error'] ) && UPLOAD_ERR_NO_FILE !== $_FILES['hds_cv']['error'];

			if ( $cv_uploaded ) {
				$cv_error_code = (int) $_FILES['hds_cv']['error'];
				if ( UPLOAD_ERR_OK !== $cv_error_code ) {
					$hds_errors['cv'] = __( 'Fout bij het uploaden van het bestand. Probeer het opnieuw.', 'hds' );
				} else {
					$cv_name = isset( $_FILES['hds_cv']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['hds_cv']['name'] ) ) : '';
					$cv_tmp  = isset( $_FILES['hds_cv']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['hds_cv']['tmp_name'] ) ) : '';
					$cv_size = isset( $_FILES['hds_cv']['size'] ) ? (int) $_FILES['hds_cv']['size'] : 0;

					$allowed_mimes = array(
						'pdf'  => 'application/pdf',
						'doc'  => 'application/msword',
						'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
					);

					$ext       = strtolower( pathinfo( $cv_name, PATHINFO_EXTENSION ) );
					$real_mime = '';
					if ( is_readable( $cv_tmp ) && function_exists( 'finfo_open' ) ) {
						$finfo     = finfo_open( FILEINFO_MIME_TYPE );
						$real_mime = $finfo ? (string) finfo_file( $finfo, $cv_tmp ) : '';
						if ( $finfo ) {
							finfo_close( $finfo );
						}
					}

					if ( ! array_key_exists( $ext, $allowed_mimes ) || ! in_array( $real_mime, $allowed_mimes, true ) ) {
						$hds_errors['cv'] = __( 'CV moet een PDF- of Word-document (.doc, .docx) zijn.', 'hds' );
					}
					if ( $cv_size > 5 * MB_IN_BYTES ) {
						$hds_errors['cv'] = __( 'CV mag maximaal 5 MB zijn.', 'hds' );
					}
				}
			}
		} else {
			$hds_posted['subject'] = isset( $_POST['hds_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['hds_subject'] ) ) : '';
			$hds_posted['message'] = isset( $_POST['hds_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hds_message'] ) ) : '';

			if ( '' === $hds_posted['message'] ) {
				$hds_errors['message'] = __( 'Bericht is verplicht.', 'hds' );
			}
		}

		if ( array() === $hds_errors ) {
			$to = hds_get_email();

			if ( 'sollicitatie' === $hds_form_mode && '' !== $hds_posted['vacancy'] ) {
				$hds_vacancy_query = new \WP_Query( [
					'post_type'      => 'hds_vacancy',
					'title'          => $hds_posted['vacancy'],
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'no_found_rows'  => true,
				] );

				if ( $hds_vacancy_query->have_posts() ) {
					$hds_vacancy_mail = sanitize_email( get_post_meta( $hds_vacancy_query->posts[0]->ID, 'hds_application_email', true ) );
					if ( is_email( $hds_vacancy_mail ) ) {
						$to = $hds_vacancy_mail;
					}
				}
			}

			$headers = [
				sprintf( 'From: %s <%s>', get_bloginfo( 'name' ), hds_get_email() ),
				sprintf( 'Reply-To: %s', $hds_posted['email'] ),
				'Content-Type: text/html; charset=UTF-8',
			];

			$hds_dup_key = 'hds_form_dup_' . md5(
				$hds_form_mode . '|' . $hds_posted['name'] . '|' . $hds_posted['email'] . '|' .
				( 'sollicitatie' === $hds_form_mode ? $hds_posted['motivation'] : $hds_posted['message'] )
			);

			// Tracks whether the mail was actually accepted by the transport.
			// null = duplicate submission (already handled by the prior request).
			$hds_mail_sent = null;

			if ( ! get_transient( $hds_dup_key ) ) {
				set_transient( $hds_dup_key, 1, 60 );

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
					$cv_cleanup   = '';
					$cv_attachment = isset( $_FILES['hds_cv'], $_FILES['hds_cv']['error'], $_FILES['hds_cv']['tmp_name'], $_FILES['hds_cv']['name'] ) && UPLOAD_ERR_OK === $_FILES['hds_cv']['error'];
					if ( $cv_attachment ) {
						$cv_tmp  = sanitize_text_field( wp_unslash( $_FILES['hds_cv']['tmp_name'] ) );
						$cv_name = sanitize_text_field( wp_unslash( $_FILES['hds_cv']['name'] ) );
						$cv_ext  = strtolower( pathinfo( $cv_name, PATHINFO_EXTENSION ) );

						if ( is_readable( $cv_tmp ) && in_array( $cv_ext, array( 'pdf', 'doc', 'docx' ), true ) ) {
							$cv_safe = sanitize_file_name( $cv_name );
							$cv_base = pathinfo( $cv_safe, PATHINFO_FILENAME );
							$cv_dest = sys_get_temp_dir() . '/' . wp_unique_filename( sys_get_temp_dir(), $cv_base . '.' . $cv_ext );

							if ( copy( $cv_tmp, $cv_dest ) ) {
								$attachments[] = $cv_dest;
								$cv_cleanup    = $cv_dest;
							}
						}
					}

					$hds_mail_sent = (bool) wp_mail( $to, $subject_line, $body, $headers, $attachments );

					if ( '' !== $cv_cleanup && file_exists( $cv_cleanup ) ) {
						unlink( $cv_cleanup );
					}
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

					$hds_mail_sent = (bool) wp_mail( $to, $subject_line, $body, $headers );
				}
			}

			if ( null === $hds_mail_sent || $hds_mail_sent ) {
				$hds_redirect_url = add_query_arg(
					array( 'hds_success' => '1' ),
					get_permalink()
				) . '#contactformulier';
			} else {
				delete_transient( $hds_dup_key );
				$hds_errors['mail'] = __( 'Uw bericht kon niet worden verzonden. Probeer het opnieuw of neem telefonisch contact met ons op.', 'hds' );
			}
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

	if ( 'sollicitatie' === $hds_form_mode ) {
		$hero_title    = __( 'Solliciteren', 'hds' );
		$hero_subtitle = __( 'Vul het formulier in en stuur uw motivatie en CV mee.', 'hds' );
		$hero_cta_text = __( 'Direct solliciteren', 'hds' );
		$hero_cta_url  = '#contactformulier';
	} else {
		if ( '' === $hero_subtitle ) {
			$hero_subtitle = __( 'Stel uw vraag, maak een afspraak of vraag een vrijblijvende offerte aan.', 'hds' );
		}
		$hero_cta_text = __( 'Offerte aanvragen', 'hds' );
		$hero_cta_url  = home_url( '/offerte-aanvragen/' );
	}

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );

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
					<h3 class="hds-usp-card__title">
						<?php echo hds_svg_icon( 'phone', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Telefoon', 'hds' ); ?>
					</h3>
					<ul class="hds-contact-phone">
						<li class="hds-contact-phone__item">
							<span class="hds-contact-phone__icon" aria-hidden="true"><?php echo hds_svg_icon( 'smartphone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="hds-contact-phone__meta">
								<span class="hds-contact-phone__label"><?php esc_html_e( 'Mobiel', 'hds' ); ?></span>
								<?php echo hds_get_phone_link( '', array( 'class' => 'hds-contact-phone__number' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</li>
						<li class="hds-contact-phone__item">
							<span class="hds-contact-phone__icon" aria-hidden="true"><?php echo hds_svg_icon( 'phone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="hds-contact-phone__meta">
								<span class="hds-contact-phone__label"><?php esc_html_e( 'Vaste lijn', 'hds' ); ?></span>
								<?php echo hds_get_phone_link( hds_get_phone_secondary(), array( 'class' => 'hds-contact-phone__number' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</li>
					</ul>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title">
						<?php echo hds_svg_icon( 'mail', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'E-mailadres', 'hds' ); ?>
					</h3>
					<?php echo hds_get_email_link( '', '', array( 'class' => 'hds-contact-email' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

					<?php
					$contact_socials = array_filter( [
						'facebook'  => get_theme_mod( 'hds_facebook_url' ),
						'instagram' => get_theme_mod( 'hds_instagram_url' ),
						'tiktok'    => get_theme_mod( 'hds_tiktok_url' ),
					] );

					if ( $contact_socials ) :
						$contact_social_labels = [
							'facebook'  => __( 'Volg Hamdoun Schoonmaak op Facebook', 'hds' ),
							'instagram' => __( 'Volg Hamdoun Schoonmaak op Instagram', 'hds' ),
							'tiktok'    => __( 'Volg Hamdoun Schoonmaak op TikTok', 'hds' ),
						];
						$contact_social_icons = [
							'facebook'  => '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
							'instagram' => '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
							'tiktok'    => '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
						];
						?>
					<p class="hds-contact-social__title"><?php esc_html_e( 'Volg Hamdoun Schoonmaak', 'hds' ); ?></p>
					<ul class="hds-contact-social">
						<?php foreach ( $contact_socials as $network => $social_url ) : ?>
							<li class="hds-contact-social__item">
								<a class="hds-contact-social__link hds-contact-social__link--<?php echo esc_attr( $network ); ?>" href="<?php echo esc_url( $social_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $contact_social_labels[ $network ] ); ?>">
									<?php echo $contact_social_icons[ $network ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</article>
				<?php if ( hds_get_address() || hds_get_postal_city() ) : ?>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Adres', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc">
						<?php if ( hds_get_address() ) : ?>
							<?php echo esc_html( hds_get_address() ); ?><br>
						<?php endif; ?>
						<?php echo esc_html( hds_get_postal_city() ); ?>
					</p>
				</article>
				<?php endif; ?>
			</div>

			<div class="hds-contact-hours-panel">
				<h3 class="hds-contact-hours-panel__title">
					<?php echo hds_svg_icon( 'clock', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Telefonische bereikbaarheid', 'hds' ); ?>
				</h3>
				<?php
				$hours_rows = [];
				$hours_text = get_theme_mod( 'hds_opening_hours' );
				if ( $hours_text ) {
					foreach ( preg_split( '/\r?\n/', $hours_text ) as $hours_line ) {
						$hours_line = trim( $hours_line );
						if ( '' === $hours_line ) {
							continue;
						}
						$hours_parts   = array_map( 'trim', explode( ':', $hours_line, 2 ) );
						$hours_rows[]  = [
							'day'   => $hours_parts[0],
							'hours' => isset( $hours_parts[1] ) ? $hours_parts[1] : '',
						];
					}
				}
				if ( empty( $hours_rows ) ) {
					$hours_rows = [
						[ 'day' => __( 'Maandag t/m vrijdag', 'hds' ), 'hours' => __( '08:00–17:00', 'hds' ) ],
						[ 'day' => __( 'Zaterdag', 'hds' ), 'hours' => __( '12:00–17:00', 'hds' ) ],
						[ 'day' => __( 'Zondag', 'hds' ), 'hours' => __( 'gesloten', 'hds' ) ],
					];
				}
				?>
				<dl class="hds-contact-hours">
					<?php foreach ( $hours_rows as $hours_row ) : ?>
						<?php $hours_closed = false !== stripos( $hours_row['hours'], 'gesloten' ); ?>
						<div class="hds-contact-hours__row<?php echo $hours_closed ? ' hds-contact-hours__row--closed' : ''; ?>">
							<dt><?php echo esc_html( $hours_row['day'] ); ?></dt>
							<dd><?php echo esc_html( $hours_row['hours'] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
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

					<?php if ( 'sollicitatie' !== $hds_form_mode ) : ?>
						<p class="hds-contact-form-intro">
							<?php esc_html_e( 'Vul het formulier in en wij nemen zo snel mogelijk contact met u op. Velden met een * zijn verplicht.', 'hds' ); ?>
						</p>
					<?php endif; ?>

					<?php if ( $hds_success ) : ?>

						<div class="thank-you-page">
							<header class="thank-you-header">
								<h1 tabindex="-1"><?php esc_html_e( 'Bedankt voor uw bericht', 'hds' ); ?></h1>
								<p class="thank-you-message">
									<?php esc_html_e( 'Uw bericht is goed ontvangen. We nemen zo snel mogelijk contact met u op.', 'hds' ); ?>
								</p>
							</header>
							<script>
							( function () {
								function hdsFocusThankYou() {
									var h = document.querySelector( '.thank-you-header h1' );
									if ( h ) {
										h.focus();
									}
								}
								if ( document.readyState === 'complete' ) {
									hdsFocusThankYou();
								} else {
									window.addEventListener( 'load', hdsFocusThankYou );
								}
							}() );
							</script>

							<div class="thank-you-fallback">
								<h2><?php esc_html_e( 'Direct contact nodig?', 'hds' ); ?></h2>
								<p>
									<?php esc_html_e( 'Bel ons op', 'hds' ); ?>
									<?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</p>
							</div>

							<div class="thank-you-links">
								<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn--primary">
									<?php esc_html_e( 'Terug naar contact', 'hds' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline">
									<?php esc_html_e( 'Terug naar home', 'hds' ); ?>
								</a>
							</div>
						</div>

					<?php else : ?>

						<?php if ( array() !== $hds_errors ) : ?>
					<div class="hds-notification hds-notification--error" role="alert" tabindex="-1" id="hds-contact-form-errors">
						<span class="hds-notification__icon" aria-hidden="true">&#10007;</span>
						<div class="hds-notification__message">
							<p class="hds-notification__title"><strong><?php esc_html_e( 'Niet alle velden zijn correct ingevuld.', 'hds' ); ?></strong></p>
							<ul>
								<?php foreach ( $hds_errors as $hds_error_key => $hds_error_msg ) : ?>
									<li id="hds-error-<?php echo esc_attr( $hds_error_key ); ?>"><?php echo esc_html( $hds_error_msg ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<script>
					( function () {
						function hdsFocusContactErrors() {
							var el = document.getElementById( 'hds-contact-form-errors' );
							if ( el ) {
								el.focus();
							}
						}
						if ( document.readyState === 'complete' ) {
							hdsFocusContactErrors();
						} else {
							window.addEventListener( 'load', hdsFocusContactErrors );
						}
					}() );
					</script>
					<?php endif; ?>

						<?php
						$hds_inline_error = static function ( string $key ) use ( $hds_errors ): string {
							if ( ! isset( $hds_errors[ $key ] ) ) {
								return '';
							}
							return '<p class="hds-quote-form__error" id="hds-inline-error-' . esc_attr( $key ) . '">' . esc_html( $hds_errors[ $key ] ) . '</p>';
						};
?>

					<form id="contactformulier" class="hds-quote-form" method="post" action="#contactformulier" enctype="multipart/form-data" novalidate>
						<?php wp_nonce_field( 'hds_contact_submit', 'hds_contact_nonce' ); ?>
						<input type="hidden" name="hds_contact_submit" value="1">
						<div class="hds-honeypot" aria-hidden="true">
							<label for="hds-website"><?php esc_html_e( 'Website', 'hds' ); ?></label>
							<input type="text" id="hds-website" name="hds_website" tabindex="-1" autocomplete="off" value="">
						</div>
						<input type="hidden" name="hds_form_ts" value="<?php echo (int) time(); ?>">

						<fieldset class="hds-quote-form__fieldset">
							<legend class="hds-quote-form__legend"><?php esc_html_e( 'Uw gegevens', 'hds' ); ?></legend>

							<div class="hds-quote-form__row">
								<div class="hds-quote-form__field">
									<label class="hds-quote-form__label" for="hds-name">
										<?php esc_html_e( 'Naam', 'hds' ); ?>
										<span class="hds-quote-form__required" aria-hidden="true">*</span>
									</label>
									<input class="hds-quote-form__input" type="text" id="hds-name" name="hds_name" required aria-required="true"
										value="<?php echo esc_attr( $hds_posted['name'] ?? '' ); ?>"<?php echo isset( $hds_errors['name'] ) ? ' aria-invalid="true" aria-describedby="hds-inline-error-name hds-error-name"' : ''; ?>>
									<?php echo $hds_inline_error( 'name' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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
									<input class="hds-quote-form__input" type="email" id="hds-email" name="hds_email" required aria-required="true"
										value="<?php echo esc_attr( $hds_posted['email'] ?? '' ); ?>"<?php echo isset( $hds_errors['email'] ) ? ' aria-invalid="true" aria-describedby="hds-inline-error-email hds-error-email"' : ''; ?>>
									<?php echo $hds_inline_error( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>

								<div class="hds-quote-form__field">
									<label class="hds-quote-form__label" for="hds-phone">
										<?php esc_html_e( 'Telefoonnummer', 'hds' ); ?>
									</label>
									<input class="hds-quote-form__input" type="tel" id="hds-phone" name="hds_phone"
										value="<?php echo esc_attr( $hds_posted['phone'] ?? '' ); ?>">
								</div>
							</div>
						</fieldset>

						<?php if ( 'sollicitatie' === $hds_form_mode ) : ?>

							<fieldset class="hds-quote-form__fieldset">
								<legend class="hds-quote-form__legend"><?php esc_html_e( 'Sollicitatie', 'hds' ); ?></legend>

								<div class="hds-quote-form__field hds-quote-form__field--full">
									<?php $hds_vacancy_value = $hds_posted['vacancy'] ?? $hds_vacancy; ?>
									<label class="hds-quote-form__label" for="hds-vacancy">
										<?php esc_html_e( 'Vacature', 'hds' ); ?>
									</label>
									<input class="hds-quote-form__input" type="text" id="hds-vacancy" name="hds_apply_vacancy" readonly
										value="<?php echo esc_attr( $hds_vacancy_value ); ?>">
									<?php if ( '' === $hds_vacancy_value ) : ?>
										<p class="hds-quote-form__hint"><?php esc_html_e( 'Open sollicitatie', 'hds' ); ?></p>
									<?php endif; ?>
								</div>
							</fieldset>

							<fieldset class="hds-quote-form__fieldset">
								<legend class="hds-quote-form__legend"><?php esc_html_e( 'Motivatie & CV', 'hds' ); ?></legend>

								<div class="hds-quote-form__field hds-quote-form__field--full">
									<label class="hds-quote-form__label" for="hds-motivation">
										<?php esc_html_e( 'Motivatie', 'hds' ); ?>
										<span class="hds-quote-form__required" aria-hidden="true">*</span>
									</label>
									<textarea class="hds-quote-form__textarea" id="hds-motivation" name="hds_motivation" required aria-required="true" rows="6"<?php echo isset( $hds_errors['motivation'] ) ? ' aria-invalid="true" aria-describedby="hds-inline-error-motivation hds-error-motivation"' : ''; ?>><?php echo esc_textarea( $hds_posted['motivation'] ?? '' ); ?></textarea>
									<?php echo $hds_inline_error( 'motivation' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>

								<div class="hds-quote-form__field hds-quote-form__field--full">
									<label class="hds-quote-form__label" for="hds-cv">
										<?php esc_html_e( 'CV', 'hds' ); ?>
									</label>
									<div class="hds-quote-form__file-control">
										<input class="hds-quote-form__file" type="file" id="hds-cv" name="hds_cv"
											accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
											aria-describedby="hds-cv-hint<?php echo isset( $hds_errors['cv'] ) ? ' hds-inline-error-cv hds-error-cv' : ''; ?>"<?php echo isset( $hds_errors['cv'] ) ? ' aria-invalid="true"' : ''; ?>>
										<label class="hds-quote-form__file-button" for="hds-cv">
											<span class="hds-quote-form__file-button-text"><?php esc_html_e( 'Kies een bestand', 'hds' ); ?></span>
											<span class="hds-quote-form__file-name" data-hds-file-name><?php esc_html_e( 'Geen bestand gekozen', 'hds' ); ?></span>
										</label>
										<p class="hds-quote-form__hint" id="hds-cv-hint"><?php esc_html_e( 'Toegestaan: PDF, DOC of DOCX (max. 5 MB).', 'hds' ); ?></p>
										<?php echo $hds_inline_error( 'cv' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</div>
							</fieldset>

						<?php else : ?>

							<fieldset class="hds-quote-form__fieldset">
								<legend class="hds-quote-form__legend"><?php esc_html_e( 'Uw bericht', 'hds' ); ?></legend>

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
									<textarea class="hds-quote-form__textarea" id="hds-message" name="hds_message" required aria-required="true" rows="6"<?php echo isset( $hds_errors['message'] ) ? ' aria-invalid="true" aria-describedby="hds-inline-error-message hds-error-message"' : ''; ?>><?php echo esc_textarea( $hds_posted['message'] ?? '' ); ?></textarea>
									<?php echo $hds_inline_error( 'message' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</fieldset>

						<?php endif; ?>

						<div class="hds-quote-form__field hds-quote-form__field--full">
							<label class="hds-quote-form__checkbox-label hds-quote-form__checkbox-label--block">
								<input class="hds-quote-form__checkbox" type="checkbox" name="hds_consent" value="1" required aria-required="true"<?php echo isset( $hds_errors['consent'] ) ? ' aria-invalid="true" aria-describedby="hds-inline-error-consent hds-error-consent"' : ''; ?>>
								<?php esc_html_e( 'Ik ga akkoord met het privacybeleid en de verwerking van mijn gegevens.', 'hds' ); ?>
								<span class="hds-quote-form__required" aria-hidden="true">*</span>
							</label>
							<?php echo $hds_inline_error( 'consent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<div class="hds-quote-form__actions">
							<button type="submit" class="btn hds-quote-form__submit btn-cta">
								<?php if ( 'sollicitatie' === $hds_form_mode ) : ?>
									<?php esc_html_e( 'Sollicitatie versturen', 'hds' ); ?>
								<?php else : ?>
									<?php esc_html_e( 'Bericht versturen', 'hds' ); ?>
								<?php endif; ?>
							</button>
							<p class="hds-quote-form__submit-note">
								<?php esc_html_e( 'Vrijblijvend — u zit nergens aan vast.', 'hds' ); ?>
							</p>
						</div>
					</form>

					<?php endif; ?>

				</div>

				<aside class="contact-info-column" role="complementary">
					<div class="contact-info-block">
						<h3><?php esc_html_e( 'Direct contact', 'hds' ); ?></h3>
						<p><?php esc_html_e( 'Heeft u een vraag of wilt u direct een afspraak maken?', 'hds' ); ?></p>
						<ul class="contact-info-actions">
							<li class="contact-info-actions__item">
								<a href="<?php echo esc_url( 'tel:' . hds_get_phone_intl( hds_get_phone() ) ); ?>" class="contact-info-actions__link contact-info-actions__link--phone btn btn--primary" aria-label="<?php echo esc_attr( sprintf( __( 'Bel %s', 'hds' ), hds_format_phone( hds_get_phone() ) ) ); ?>">
									<?php echo hds_svg_icon( 'phone', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span><?php echo esc_html( hds_format_phone( hds_get_phone() ) ); ?></span>
								</a>
							</li>
							<li class="contact-info-actions__item">
								<a href="<?php echo esc_url( 'mailto:' . hds_get_email() ); ?>" class="contact-info-actions__link contact-info-actions__link--email" aria-label="<?php echo esc_attr( sprintf( __( 'E-mail %s', 'hds' ), hds_get_email() ) ); ?>">
									<?php echo hds_svg_icon( 'mail', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span><?php echo esc_html( hds_get_email() ); ?></span>
								</a>
							</li>
						</ul>
					</div>

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
				<?php esc_html_e( 'Wij zijn werkzaam in de hele provincie Groningen. Ons team staat voor u klaar. Ook voor spoedklussen of grote projecten kunt u contact met ons opnemen.', 'hds' ); ?>
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
