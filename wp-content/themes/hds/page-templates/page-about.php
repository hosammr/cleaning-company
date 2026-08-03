<?php
/**
 * Template Name: About
 *
 * Used for Over HDS (P11) and Kwaliteit & Veiligheid (P12).
 *
 * @package HDS
 */

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

	<section class="hds-usp-section" aria-labelledby="about-mission">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="about-mission"><?php esc_html_e( 'Onze missie', 'hds' ); ?></h2>
			</header>
			<p class="about-mission-text"><?php esc_html_e( 'Wij geloven dat een schone werkomgeving de basis is voor productiviteit, gezondheid en een goede eerste indruk. Daarom levert HDS al meer dan 20 jaar hoogwaardige schoonmaakdiensten aan bedrijven in West-Brabant en Zeeland.', 'hds' ); ?></p>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="about-values">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="about-values"><?php esc_html_e( 'Onze kernwaarden', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				echo hds_usp_card( __( 'Betrouwbaarheid', 'hds' ), __( 'Afspraak is afspraak. U kunt op ons rekenen, elke dag weer.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Kwaliteit', 'hds' ), __( 'Wij leveren consequent hoge kwaliteit met oog voor detail.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Flexibiliteit', 'hds' ), __( 'Wij stemmen onze werkzaamheden af op uw planning en bedrijfsprocessen.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Duurzaamheid', 'hds' ), __( 'Milieubewust werken met professionele producten en methoden.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="about-why">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="about-why"><?php esc_html_e( 'Waarom bedrijven kiezen voor HDS', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				echo hds_usp_card( __( '20+ jaar ervaring', 'hds' ), __( 'Al meer dan twee decennia een vertrouwde partner in schoonmaak.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Regionale partner', 'hds' ), __( 'Wij kennen de regio West-Brabant en Zeeland als geen ander.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Persoonlijk contact', 'hds' ), __( 'Geen callcenter, maar een vaste contactpersoon die u kent.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo hds_usp_card( __( 'Volledig verzekerd', 'hds' ), __( 'U zit nooit met risico\'s. Onze diensten zijn volledig verzekerd.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</div>
		</div>
	</section>

	<?php
	echo hds_cta_section(
		__( 'Klaar voor een schonere werkomgeving?', 'hds' ),
		__( 'Neem vrijblijvend contact op voor een kennismaking.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();
