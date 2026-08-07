<?php
/**
 * Hero template part.
 *
 * Expected variables set by the calling template:
 *   string $hero_title     — The page title (H1).
 *   string $hero_eyebrow   — Optional eyebrow label above the title.
 *   string $hero_subtitle  — Optional subtitle below the title.
 *   string $hero_image_url — Full URL of the hero background image, or empty string.
 *   string $hero_cta_text  — CTA button label.
 *   string $hero_cta_url   — CTA button destination URL.
 *
 * @package HDS
 */

if ( empty( $hero_title ) ) {
	return;
}
?>

<section class="service-hero"<?php echo $hero_image_url ? ' style="background-image:url(' . esc_url( $hero_image_url ) . ')"' : ''; ?>>
	<div class="container">
		<?php if ( ! empty( $hero_eyebrow ) ) : ?>
			<p class="service-hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
		<?php endif; ?>
		<h1><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( $hero_subtitle ) : ?>
			<p class="service-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
		<?php endif; ?>
		<a href="<?php echo esc_url( $hero_cta_url ); ?>" class="btn btn-cta">
			<?php echo esc_html( $hero_cta_text ); ?>
		</a>
	</div>
</section>
