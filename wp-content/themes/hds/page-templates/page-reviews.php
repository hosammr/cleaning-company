<?php
/**
 * Template Name: Reviews
 *
 * Referenties page: a short introduction followed by the confirmed
 * client (opdrachtgevers) grid. The grid is rendered via the
 * hds/client-logos block, which is shared with the front page.
 *
 * Layout: Hero → Intro → Client grid → CTA.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
		// 1. Hero — reuse parts/hero
		$hero_title    = get_the_title();
		$hero_eyebrow  = get_post_meta( get_the_ID(), 'hds_eyebrow', true );
		$hero_subtitle = get_post_meta( get_the_ID(), 'hds_subtitle', true );
		if ( '' === trim( (string) $hero_subtitle ) ) {
			$hero_subtitle = __( 'Een selectie van bedrijven en organisaties waarvoor Hamdoun Schoonmaak werkzaamheden uitvoert of heeft uitgevoerd.', 'hds' );
		}
		$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
		$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
		$hero_cta_text  = __( 'Vrijblijvende offerte', 'hds' );
		$hero_cta_url   = home_url( '/offerte-aanvragen/' );

		set_query_var( 'hero_title', $hero_title );
		set_query_var( 'hero_eyebrow', $hero_eyebrow );
		set_query_var( 'hero_subtitle', $hero_subtitle );
		set_query_var( 'hero_image_url', $hero_image_url );
		set_query_var( 'hero_cta_text', $hero_cta_text );
		set_query_var( 'hero_cta_url', $hero_cta_url );

		get_template_part( 'parts/hero' );
	?>

	<div class="container">
		<div class="references-page">
			<div class="references-intro">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( ! empty( get_the_content() ) ) :
						the_content();
					endif;
				endwhile;
				?>
			</div>
		</div>
	</div>

	<?php
	// 2. Final CTA — reuse hds_cta_section
	echo hds_cta_section(
		__( 'Klaar om ook klant te worden?', 'hds' ),
		__( 'Vraag vandaag nog een vrijblijvende offerte aan.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</main>

<?php
get_footer();
