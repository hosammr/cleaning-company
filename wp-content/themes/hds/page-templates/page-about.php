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
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Betrouwbaarheid', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Afspraak is afspraak. U kunt op ons rekenen, elke dag weer.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Kwaliteit', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Wij leveren consequent hoge kwaliteit met oog voor detail.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Flexibiliteit', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Wij stemmen onze werkzaamheden af op uw planning en bedrijfsprocessen.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Duurzaamheid', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Milieubewust werken met professionele producten en methoden.', 'hds' ); ?></p>
				</article>
			</div>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="about-why">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="about-why"><?php esc_html_e( 'Waarom bedrijven kiezen voor HDS', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( '20+ jaar ervaring', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Al meer dan twee decennia een vertrouwde partner in schoonmaak.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Regionale partner', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Wij kennen de regio West-Brabant en Zeeland als geen ander.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Persoonlijk contact', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'Geen callcenter, maar een vaste contactpersoon die u kent.', 'hds' ); ?></p>
				</article>
				<article class="hds-card hds-usp-card">
					<h3 class="hds-usp-card__title"><?php esc_html_e( 'Volledig verzekerd', 'hds' ); ?></h3>
					<p class="hds-usp-card__desc"><?php esc_html_e( 'U zit nooit met risico\'s. Onze diensten zijn volledig verzekerd.', 'hds' ); ?></p>
				</article>
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
