<?php
/**
 * Quote request form.
 *
 * Renders a 13-field quotation form per MPS-001 G1.2.
 * Handles server-side validation, file upload, and email notification.
 * Serves as the fallback when no form plugin (Gravity Forms) is active.
 *
 * @package HDS
 */

/**
 * Render the quote request form.
 *
 * @return string Form HTML or success message.
 */
function hds_render_quote_form(): string {
	$errors   = [];
	$success  = false;
	$submitted = isset( $_POST['hds_quote_submit'] ) && wp_verify_nonce( $_POST['hds_quote_nonce'] ?? '', 'hds_quote_form' );

	if ( $submitted ) {
		$data   = hds_quote_sanitize_submission( $_POST );
		$errors = hds_quote_validate_submission( $data, $_FILES );

		if ( empty( $errors ) ) {
			$attachment = hds_quote_handle_upload( $_FILES );
			$sent       = hds_quote_send_notification( $data, $attachment );
			$success    = true;
		}
	}

	ob_start();

	if ( $success ) :
		?>
		<div class="hds-notification hds-notification--success" role="status">
			<span class="hds-notification__icon" aria-hidden="true">&#10003;</span>
			<div class="hds-notification__message">
				<p><strong><?php esc_html_e( 'Uw offerteaanvraag is verstuurd!', 'hds' ); ?></strong></p>
				<p><?php esc_html_e( 'Wij nemen binnen één werkdag contact met u op. Heeft u dringend een offerte nodig? Bel ons dan op', 'hds' ); ?> <?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	endif;

	if ( ! empty( $errors ) ) :
		?>
		<div class="hds-notification hds-notification--error" role="alert">
			<span class="hds-notification__icon" aria-hidden="true">&#10007;</span>
			<div class="hds-notification__message">
				<p><strong><?php esc_html_e( 'Niet alle velden zijn correct ingevuld.', 'hds' ); ?></strong></p>
				<ul>
					<?php foreach ( $errors as $error ) : ?>
						<li><?php echo esc_html( $error ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	endif;

	$values = $submitted ? $data : [];
	?>
	<div class="hds-quote-trust">
		<span class="hds-quote-trust__item">&#10003; <?php esc_html_e( 'Vrijblijvend', 'hds' ); ?></span>
		<span class="hds-quote-trust__item">&#10003; <?php esc_html_e( 'Reactie binnen 1 werkdag', 'hds' ); ?></span>
		<span class="hds-quote-trust__item">&#10003; <?php esc_html_e( 'Geen verplichtingen', 'hds' ); ?></span>
	</div>
	<form method="post" action="#offerte-formulier" class="hds-quote-form" enctype="multipart/form-data" novalidate>
		<?php wp_nonce_field( 'hds_quote_form', 'hds_quote_nonce' ); ?>

		<fieldset class="hds-quote-form__fieldset">
			<legend class="hds-quote-form__legend"><?php esc_html_e( 'Uw gegevens', 'hds' ); ?></legend>

			<div class="hds-quote-form__row">
				<div class="hds-quote-form__field">
					<label for="hds-qf-bedrijf" class="hds-quote-form__label">
						<?php esc_html_e( 'Bedrijfsnaam', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<input type="text" id="hds-qf-bedrijf" name="hds_qf_bedrijf" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_bedrijf'] ?? '' ); ?>" required aria-required="true">
				</div>

				<div class="hds-quote-form__field">
					<label for="hds-qf-contact" class="hds-quote-form__label">
						<?php esc_html_e( 'Contactpersoon', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<input type="text" id="hds-qf-contact" name="hds_qf_contact" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_contact'] ?? '' ); ?>" required aria-required="true">
				</div>
			</div>

			<div class="hds-quote-form__row">
				<div class="hds-quote-form__field">
					<label for="hds-qf-email" class="hds-quote-form__label">
						<?php esc_html_e( 'E-mailadres', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<input type="email" id="hds-qf-email" name="hds_qf_email" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_email'] ?? '' ); ?>" required aria-required="true">
				</div>

				<div class="hds-quote-form__field">
					<label for="hds-qf-phone" class="hds-quote-form__label">
						<?php esc_html_e( 'Telefoonnummer', 'hds' ); ?>
					</label>
					<input type="tel" id="hds-qf-phone" name="hds_qf_phone" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_phone'] ?? '' ); ?>">
					<p class="hds-quote-form__hint"><?php esc_html_e( 'Optioneel. Handig voor een snellere afhandeling van uw aanvraag.', 'hds' ); ?></p>
				</div>
			</div>

			<div class="hds-quote-form__row">
				<div class="hds-quote-form__field">
					<label for="hds-qf-postcode" class="hds-quote-form__label">
						<?php esc_html_e( 'Postcode', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<input type="text" id="hds-qf-postcode" name="hds_qf_postcode" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_postcode'] ?? '' ); ?>" maxlength="7" placeholder="1234 AB" required aria-required="true">
				</div>

				<div class="hds-quote-form__field">
					<label for="hds-qf-type" class="hds-quote-form__label">
						<?php esc_html_e( 'Type bedrijfspand', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<select id="hds-qf-type" name="hds_qf_type" class="hds-quote-form__select" required aria-required="true">
						<option value=""><?php esc_html_e( '— Maak een keuze —', 'hds' ); ?></option>
						<?php foreach ( hds_quote_building_types() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $values['hds_qf_type'] ?? '', $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</fieldset>

		<fieldset class="hds-quote-form__fieldset">
			<legend class="hds-quote-form__legend"><?php esc_html_e( 'Uw wensen', 'hds' ); ?></legend>

			<div class="hds-quote-form__field">
				<span class="hds-quote-form__label">
					<?php esc_html_e( 'Gewenste diensten', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
				</span>
				<div class="hds-quote-form__checklist">
					<?php
					$selected_services = $values['hds_qf_services'] ?? [];
					foreach ( hds_quote_services() as $value => $label ) :
						$id = 'hds-qf-svc-' . sanitize_title( $value );
						?>
						<label for="<?php echo esc_attr( $id ); ?>" class="hds-quote-form__checkbox-label">
							<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="hds_qf_services[]" value="<?php echo esc_attr( $value ); ?>" class="hds-quote-form__checkbox" <?php checked( in_array( $value, $selected_services, true ) ); ?>>
							<?php echo esc_html( $label ); ?>
						</label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="hds-quote-form__row">
				<div class="hds-quote-form__field">
					<label for="hds-qf-surface" class="hds-quote-form__label">
						<?php esc_html_e( 'Oppervlakte (m²)', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<input type="number" id="hds-qf-surface" name="hds_qf_surface" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_surface'] ?? '' ); ?>" min="1" step="1" required aria-required="true">
				</div>

				<div class="hds-quote-form__field">
					<label for="hds-qf-frequency" class="hds-quote-form__label">
						<?php esc_html_e( 'Frequentie', 'hds' ); ?> <span class="hds-quote-form__required" aria-hidden="true">*</span>
					</label>
					<select id="hds-qf-frequency" name="hds_qf_frequency" class="hds-quote-form__select" required aria-required="true">
						<option value=""><?php esc_html_e( '— Maak een keuze —', 'hds' ); ?></option>
						<?php foreach ( hds_quote_frequencies() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $values['hds_qf_frequency'] ?? '', $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="hds-quote-form__row">
				<div class="hds-quote-form__field">
					<label for="hds-qf-start" class="hds-quote-form__label">
						<?php esc_html_e( 'Gewenste startdatum', 'hds' ); ?>
					</label>
					<input type="date" id="hds-qf-start" name="hds_qf_start" class="hds-quote-form__input" value="<?php echo esc_attr( $values['hds_qf_start'] ?? '' ); ?>">
				</div>

				<div class="hds-quote-form__field">
					<label for="hds-qf-file" class="hds-quote-form__label">
						<?php esc_html_e( 'Bestand bijvoegen', 'hds' ); ?>
					</label>
					<input type="file" id="hds-qf-file" name="hds_qf_file" class="hds-quote-form__file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
					<p class="hds-quote-form__hint"><?php esc_html_e( 'Optioneel. Voeg een plattegrond, foto of situatieschets toe voor een nauwkeurigere offerte. Toegestaan: PDF, JPG, PNG, DOC (max. 5 MB).', 'hds' ); ?></p>
				</div>
			</div>

			<div class="hds-quote-form__field hds-quote-form__field--full">
				<label for="hds-qf-message" class="hds-quote-form__label">
					<?php esc_html_e( 'Extra wensen of opmerkingen', 'hds' ); ?>
				</label>
				<textarea id="hds-qf-message" name="hds_qf_message" class="hds-quote-form__textarea" rows="4"><?php echo esc_textarea( $values['hds_qf_message'] ?? '' ); ?></textarea>
			</div>
		</fieldset>

		<fieldset class="hds-quote-form__fieldset">
			<div class="hds-quote-form__field">
				<label for="hds-qf-privacy" class="hds-quote-form__checkbox-label hds-quote-form__checkbox-label--block">
					<input type="checkbox" id="hds-qf-privacy" name="hds_qf_privacy" value="1" class="hds-quote-form__checkbox" required aria-required="true">
					<?php
					printf(
						/* translators: %s: URL to privacy policy page */
						esc_html__( 'Ik ga akkoord met de %s.', 'hds' ),
						'<a href="' . esc_url( home_url( '/privacyverklaring/' ) ) . '" target="_blank" rel="noopener">' . esc_html__( 'privacyverklaring', 'hds' ) . '</a>'
					);
					?>
					<span class="hds-quote-form__required" aria-hidden="true">*</span>
				</label>
			</div>
		</fieldset>

		<div class="hds-quote-form__actions">
			<button type="submit" name="hds_quote_submit" class="hds-quote-form__submit btn btn--cta">
				<?php esc_html_e( 'Offerte aanvragen', 'hds' ); ?>
				<span class="hds-quote-form__submit-arrow" aria-hidden="true">&rarr;</span>
			</button>
			<p class="hds-quote-form__submit-note">
				<?php esc_html_e( 'Vrijblijvend — u zit nergens aan vast.', 'hds' ); ?>
			</p>
		</div>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Sanitize form submission data.
 *
 * @param array $post Raw $_POST data.
 * @return array Sanitized data.
 */
function hds_quote_sanitize_submission( array $post ): array {
	return [
		'hds_qf_bedrijf'   => sanitize_text_field( wp_unslash( $post['hds_qf_bedrijf'] ?? '' ) ),
		'hds_qf_contact'   => sanitize_text_field( wp_unslash( $post['hds_qf_contact'] ?? '' ) ),
		'hds_qf_email'     => sanitize_email( wp_unslash( $post['hds_qf_email'] ?? '' ) ),
		'hds_qf_phone'     => sanitize_text_field( wp_unslash( $post['hds_qf_phone'] ?? '' ) ),
		'hds_qf_postcode'  => sanitize_text_field( wp_unslash( $post['hds_qf_postcode'] ?? '' ) ),
		'hds_qf_type'      => sanitize_text_field( wp_unslash( $post['hds_qf_type'] ?? '' ) ),
		'hds_qf_services'  => array_map( 'sanitize_text_field', wp_unslash( $post['hds_qf_services'] ?? [] ) ),
		'hds_qf_surface'   => absint( wp_unslash( $post['hds_qf_surface'] ?? 0 ) ),
		'hds_qf_frequency' => sanitize_text_field( wp_unslash( $post['hds_qf_frequency'] ?? '' ) ),
		'hds_qf_start'     => sanitize_text_field( wp_unslash( $post['hds_qf_start'] ?? '' ) ),
		'hds_qf_message'   => sanitize_textarea_field( wp_unslash( $post['hds_qf_message'] ?? '' ) ),
		'hds_qf_privacy'   => (int) ( $post['hds_qf_privacy'] ?? 0 ),
	];
}

/**
 * Validate form submission.
 *
 * @param array $data  Sanitized POST data.
 * @param array $files $_FILES array.
 * @return array Error messages (empty if valid).
 */
function hds_quote_validate_submission( array $data, array $files ): array {
	$errors = [];

	$required = [
		'hds_qf_bedrijf'   => __( 'Bedrijfsnaam is verplicht.', 'hds' ),
		'hds_qf_contact'   => __( 'Contactpersoon is verplicht.', 'hds' ),
		'hds_qf_email'     => __( 'E-mailadres is verplicht.', 'hds' ),
		'hds_qf_postcode'  => __( 'Postcode is verplicht.', 'hds' ),
		'hds_qf_type'      => __( 'Type bedrijfspand is verplicht.', 'hds' ),
		'hds_qf_surface'   => __( 'Oppervlakte is verplicht.', 'hds' ),
		'hds_qf_frequency' => __( 'Frequentie is verplicht.', 'hds' ),
	];

	foreach ( $required as $key => $message ) {
		if ( empty( $data[ $key ] ) && $data[ $key ] !== 0 ) {
			$errors[] = $message;
		}
	}

	if ( empty( $data['hds_qf_services'] ) ) {
		$errors[] = __( 'Selecteer minimaal één gewenste dienst.', 'hds' );
	}

	if ( ! empty( $data['hds_qf_email'] ) && ! is_email( $data['hds_qf_email'] ) ) {
		$errors[] = __( 'Vul een geldig e-mailadres in.', 'hds' );
	}

	if ( ! empty( $data['hds_qf_postcode'] ) && ! hds_quote_validate_postcode( $data['hds_qf_postcode'] ) ) {
		$errors[] = __( 'Vul een geldige Nederlandse postcode in (bijv. 1234 AB).', 'hds' );
	}

	if ( ! empty( $data['hds_qf_type'] ) && ! array_key_exists( $data['hds_qf_type'], hds_quote_building_types() ) ) {
		$errors[] = __( 'Selecteer een geldig type bedrijfspand.', 'hds' );
	}

	if ( ! empty( $data['hds_qf_frequency'] ) && ! array_key_exists( $data['hds_qf_frequency'], hds_quote_frequencies() ) ) {
		$errors[] = __( 'Selecteer een geldige frequentie.', 'hds' );
	}

	if ( empty( $data['hds_qf_privacy'] ) ) {
		$errors[] = __( 'U moet akkoord gaan met de privacyverklaring.', 'hds' );
	}

	if ( ! empty( $files['hds_qf_file']['name'] ) ) {
		$max_size = 5 * 1024 * 1024; // 5 MB
		if ( $files['hds_qf_file']['size'] > $max_size ) {
			$errors[] = __( 'Het bestand is te groot. Maximum is 5 MB.', 'hds' );
		}
		$allowed = [ 'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx' ];
		$ext     = strtolower( pathinfo( $files['hds_qf_file']['name'], PATHINFO_EXTENSION ) );
		if ( ! in_array( $ext, $allowed, true ) ) {
			$errors[] = __( 'Ongeldig bestandsformaat. Toegestaan: PDF, JPG, PNG, DOC.', 'hds' );
		}
	}

	return $errors;
}

/**
 * Validate Dutch postcode format (1234 AB).
 *
 * @param string $postcode The postcode to validate.
 * @return bool True if valid Dutch postcode.
 */
function hds_quote_validate_postcode( string $postcode ): bool {
	return (bool) preg_match( '/^[1-9][0-9]{3}\s?[A-Z]{2}$/i', trim( $postcode ) );
}

/**
 * Handle file upload for quote form.
 *
 * @param array $files $_FILES array.
 * @return string Empty string or path to uploaded file.
 */
function hds_quote_handle_upload( array $files ): string {
	if ( empty( $files['hds_qf_file']['tmp_name'] ) ) {
		return '';
	}

	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}

	$upload = wp_handle_upload(
		$files['hds_qf_file'],
		[ 'test_form' => false ]
	);

	if ( isset( $upload['error'] ) ) {
		return '';
	}

	return $upload['file'] ?? '';
}

/**
 * Send email notification for a quote request.
 *
 * @param array  $data       Sanitized form data.
 * @param string $attachment Optional path to uploaded file.
 * @return bool True if email was sent.
 */
function hds_quote_send_notification( array $data, string $attachment = '' ): bool {
	$to      = hds_get_email();
	$subject = sprintf(
		/* translators: %s: company name from form */
		__( 'Nieuwe offerteaanvraag van %s', 'hds' ),
		$data['hds_qf_bedrijf']
	);

	$building_types = hds_quote_building_types();
	$frequencies    = hds_quote_frequencies();
	$services_list  = hds_quote_services();
	$chosen         = array_map(
		fn( $s ) => $services_list[ $s ] ?? $s,
		$data['hds_qf_services']
	);

	$body = sprintf(
		"%s\n\n" .
		"Bedrijfsnaam: %s\n" .
		"Contactpersoon: %s\n" .
		"E-mail: %s\n" .
		"Telefoon: %s\n" .
		"Postcode: %s\n" .
		"Type pand: %s\n" .
		"Diensten: %s\n" .
		"Oppervlakte: %s m²\n" .
		"Frequentie: %s\n" .
		"Startdatum: %s\n\n" .
		"Opmerkingen:\n%s\n",
		__( 'Er is een nieuwe offerteaanvraag ingediend via de website.', 'hds' ),
		$data['hds_qf_bedrijf'],
		$data['hds_qf_contact'],
		$data['hds_qf_email'],
		$data['hds_qf_phone'],
		$data['hds_qf_postcode'],
		$building_types[ $data['hds_qf_type'] ] ?? $data['hds_qf_type'],
		implode( ', ', $chosen ),
		$data['hds_qf_surface'],
		$frequencies[ $data['hds_qf_frequency'] ] ?? $data['hds_qf_frequency'],
		$data['hds_qf_start'] ?: __( 'Niet opgegeven', 'hds' ),
		$data['hds_qf_message'] ?: __( 'Geen opmerkingen.', 'hds' )
	);

	$headers = [
		sprintf( 'From: %s <%s>', get_bloginfo( 'name' ), hds_get_email() ),
		sprintf( 'Reply-To: %s', $data['hds_qf_email'] ),
		'Content-Type: text/plain; charset=UTF-8',
	];

	$attachments = $attachment ? [ $attachment ] : [];

	return wp_mail( $to, $subject, $body, $headers, $attachments );
}

/**
 * Get building type options.
 *
 * @return array Value => label pairs.
 */
function hds_quote_building_types(): array {
	return [
		'kantoor'        => __( 'Kantoor', 'hds' ),
		'school'         => __( 'School / Onderwijs', 'hds' ),
		'zorg'           => __( 'Zorginstelling', 'hds' ),
		'winkel'         => __( 'Winkel / Retail', 'hds' ),
		'horeca'         => __( 'Horeca', 'hds' ),
		'industrieel'    => __( 'Industrieel / Bedrijfshal', 'hds' ),
		'vve'            => __( 'VvE / Appartementencomplex', 'hds' ),
		'anders'         => __( 'Anders', 'hds' ),
	];
}

/**
 * Get service options.
 *
 * @return array Value => label pairs.
 */
function hds_quote_services(): array {
	return [
		'glasbewassing'          => __( 'Glasbewassing', 'hds' ),
		'gevelreiniging'         => __( 'Gevelreiniging', 'hds' ),
		'reguliere-schoonmaak'   => __( 'Reguliere schoonmaak', 'hds' ),
		'vloeronderhoud'         => __( 'Vloeronderhoud', 'hds' ),
		'vve-service'            => __( 'VvE service', 'hds' ),
		'oplevering-schoonmaak'  => __( 'Oplevering schoonmaak', 'hds' ),
		'industriele-schoonmaak' => __( 'Industriële schoonmaak', 'hds' ),
		'specialistische-reiniging' => __( 'Specialistische reiniging', 'hds' ),
	];
}

/**
 * Get cleaning frequency options.
 *
 * @return array Value => label pairs.
 */
function hds_quote_frequencies(): array {
	return [
		'dagelijks'      => __( 'Dagelijks', 'hds' ),
		'wekelijks'      => __( 'Wekelijks', 'hds' ),
		'tweewekelijks'  => __( 'Tweewekelijks', 'hds' ),
		'maandelijks'    => __( 'Maandelijks', 'hds' ),
		'eenmalig'       => __( 'Eenmalig / Project', 'hds' ),
	];
}

/**
 * Check if a form plugin shortcode or block is present in content.
 *
 * @param string $content Post content.
 * @return bool True if a known form is detected.
 */
function hds_has_plugin_form( string $content ): bool {
	$patterns = [
		'\[gravityform',       // Gravity Forms shortcode
		'wp:gravityforms/',    // Gravity Forms block
		'\[contact-form-7',    // Contact Form 7 shortcode
		'wp:contact-form-7/',  // Contact Form 7 block
		'\[wpforms',           // WPForms shortcode
		'\[formidable',        // Formidable shortcode
	];
	foreach ( $patterns as $pattern ) {
		if ( false !== stripos( $content, $pattern ) ) {
			return true;
		}
	}
	return false;
}
