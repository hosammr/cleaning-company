<?php
/**
 * Plugin Name: HDS Mail Transport
 * Description: Routes wp_mail() to Mailpit in local development or an SMTP relay in staging/production.
 * Version:     1.0.0
 * Author:      HDS
 * License:     GPL-2.0-or-later
 *
 * Local:      Mailpit (Docker service, no external delivery).
 * Production: SMTP relay configured via SMTP_HOST / SMTP_PORT / SMTP_USER / SMTP_PASS.
 *
 * @package HDS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'phpmailer_init', 'hds_mail_configure_transport' );

/**
 * Configure the PHPMailer transport used by wp_mail().
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance.
 */
function hds_mail_configure_transport( $phpmailer ): void {
	// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PHPMailer property names are part of the library API.

	// ── Local: Mailpit (local only, never deliver externally) ──
	if ( 'local' === wp_get_environment_type() ) {
		$phpmailer->isSMTP();
		$phpmailer->Host        = 'mailpit';
		$phpmailer->Port        = 1025;
		$phpmailer->SMTPAuth    = false;
		$phpmailer->SMTPSecure  = '';
		$phpmailer->SMTPAutoTLS = false;
		return;
	}

	// ── Staging / Production: SMTP relay from environment variables ──
	$smtp_host = getenv( 'SMTP_HOST' );
	if ( ! $smtp_host ) {
		return;
	}

	$smtp_user = getenv( 'SMTP_USER' );
	$smtp_pass = getenv( 'SMTP_PASS' );
	$smtp_port = getenv( 'SMTP_PORT' );
	$smtp_port = $smtp_port ? (int) $smtp_port : 587;

	$phpmailer->isSMTP();
	$phpmailer->Host       = $smtp_host;
	$phpmailer->Port       = $smtp_port;
	$phpmailer->SMTPAuth   = (bool) $smtp_user;
	$phpmailer->Username   = $smtp_user ? $smtp_user : '';
	$phpmailer->Password   = $smtp_pass ? $smtp_pass : '';
	$phpmailer->SMTPSecure = ( 465 === $smtp_port ) ? 'ssl' : 'tls';

	// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
}
