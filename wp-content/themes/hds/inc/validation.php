<?php
/**
 * Field validation rules for editor-facing inputs.
 *
 * These function as the core equivalent of ACF validation rules —
 * applied via sanitize_callback in register_post_meta() and via
 * PHP-side checks on save_post.
 *
 * @package HDS
 */

/**
 * Validate and sanitize a star rating (integer 1-5).
 */
function hds_validate_star_rating( $value ): int {
	$int = (int) $value;
	if ( $int < 1 ) {
		$int = 1;
	}
	if ( $int > 5 ) {
		$int = 5;
	}
	return $int;
}

/**
 * Validate that a value is a valid email address.
 */
function hds_validate_email( string $value ): string {
	if ( $value === '' ) {
		return '';
	}
	$sanitized = sanitize_email( $value );
	return $sanitized ?: '';
}

/**
 * Validate a subtitle — max 120 characters, strip tags.
 */
function hds_validate_subtitle( string $value ): string {
	$value = wp_strip_all_tags( $value, true );
	return mb_substr( $value, 0, 120 );
}

/**
 * Validate a hero image — must be a valid attachment ID or 0.
 */
function hds_validate_attachment_id( $value ): int {
	$id = (int) $value;
	if ( $id > 0 && get_post_type( $id ) !== 'attachment' ) {
		return get_post_thumbnail_id( get_the_ID() ) ?: 0;
	}
	return $id;
}

/**
 * Validate hours_per_week — typical format "32-40" or "40".
 */
function hds_validate_hours( string $value ): string {
	return wp_strip_all_tags( trim( $value ), true );
}

/**
 * Validate is_active — boolean.
 */
function hds_validate_is_active( $value ): bool {
	return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
}

/**
 * Validate date string in DD-MM-YYYY or YYYY-MM-DD format.
 */
function hds_validate_date( string $value ): string {
	$value = trim( $value );
	if ( $value === '' ) {
		return '';
	}

	$formats = [ 'Y-m-d', 'd-m-Y', 'Ymd', 'Y/m/d', 'd/m/Y' ];
	foreach ( $formats as $format ) {
		$d = \DateTime::createFromFormat( $format, $value );
		if ( $d && $d->format( $format ) === $value ) {
			return $d->format( 'Y-m-d' );
		}
	}

	return '';
}

/**
 * Validate service icon — alphanumeric + hyphens only, max 50 chars.
 */
function hds_validate_icon_slug( string $value ): string {
	$value = preg_replace( '/[^a-zA-Z0-9\-]/', '', trim( $value ) );
	return mb_substr( $value, 0, 50 );
}

/**
 * Validate a location string — strip tags, 200 char max.
 */
function hds_validate_location( string $value ): string {
	$value = wp_strip_all_tags( trim( $value ), true );
	return mb_substr( $value, 0, 200 );
}

/**
 * Validate CTA button text — strip tags, 80 char max.
 */
function hds_validate_cta_text( string $value ): string {
	$value = wp_strip_all_tags( trim( $value ), true );
	return mb_substr( $value, 0, 80 );
}

/**
 * Validate related service — must be a published page ID or 0.
 */
function hds_validate_related_service( $value ): int {
	$id = (int) $value;
	if ( $id > 0 ) {
		$post = get_post( $id );
		if ( ! $post || $post->post_type !== 'page' || $post->post_status !== 'publish' ) {
			return 0;
		}
	}
	return $id;
}

/**
 * Validate author or company name — strip tags, 100 char max.
 */
function hds_validate_person_name( string $value ): string {
	$value = wp_strip_all_tags( trim( $value ), true );
	return mb_substr( $value, 0, 100 );
}

/**
 * Run all post-meta validation on save_post.
 *
 * This hook mimics ACF's field-level validation at the WordPress save
 * lifecycle. Each post meta field is re-validated before being saved.
 */
function hds_validate_post_meta_on_save( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post_type = get_post_type( $post_id );

	if ( $post_type === 'page' && isset( $_POST['hds_subtitle'] ) ) {
		update_post_meta( $post_id, 'hds_subtitle', hds_validate_subtitle( $_POST['hds_subtitle'] ) );
	}

	if ( $post_type === 'page' && isset( $_POST['hds_cta_override'] ) ) {
		update_post_meta( $post_id, 'hds_cta_override', hds_validate_cta_text( $_POST['hds_cta_override'] ) );
	}

	if ( $post_type === 'page' && isset( $_POST['hds_service_icon'] ) ) {
		update_post_meta( $post_id, 'hds_service_icon', hds_validate_icon_slug( $_POST['hds_service_icon'] ) );
	}

	if ( $post_type === 'hds_testimonial' && isset( $_POST['hds_star_rating'] ) ) {
		update_post_meta( $post_id, 'hds_star_rating', hds_validate_star_rating( $_POST['hds_star_rating'] ) );
	}

	if ( $post_type === 'hds_testimonial' && isset( $_POST['hds_author_name'] ) ) {
		update_post_meta( $post_id, 'hds_author_name', hds_validate_person_name( $_POST['hds_author_name'] ) );
	}

	if ( $post_type === 'hds_vacancy' && isset( $_POST['hds_application_email'] ) ) {
		update_post_meta( $post_id, 'hds_application_email', hds_validate_email( $_POST['hds_application_email'] ) );
	}

	if ( $post_type === 'hds_vacancy' && isset( $_POST['hds_deadline'] ) ) {
		update_post_meta( $post_id, 'hds_deadline', hds_validate_date( $_POST['hds_deadline'] ) );
	}

	if ( $post_type === 'hds_vacancy' && isset( $_POST['hds_is_active'] ) ) {
		update_post_meta( $post_id, 'hds_is_active', hds_validate_is_active( $_POST['hds_is_active'] ) );
	}
}
add_action( 'save_post', 'hds_validate_post_meta_on_save', 10, 1 );
