<?php
/**
 * Template Name: Service
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
	$cta_override = get_post_meta( get_the_ID(), 'hds_cta_override', true );
	$cta_text     = $cta_override ?: __( 'Vrijblijvende offerte', 'hds' );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	?>

	<section class="service-hero"<?php echo $hero_image_url ? ' style="background-image:url(' . esc_url( $hero_image_url ) . ')"' : ''; ?>>
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="service-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php echo esc_html( $cta_text ); ?>
			</a>
		</div>
	</section>

	<div class="container">
		<div class="service-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>

		<section class="hds-usp-section" aria-labelledby="hds-usp-heading">
			<div class="container">
				<header class="hds-usp-header">
					<h2 id="hds-usp-heading"><?php esc_html_e( 'Waarom kiezen voor HDS', 'hds' ); ?></h2>
				</header>
				<div class="hds-usp-grid">
					<article class="hds-card hds-usp-card">
						<h3 class="hds-usp-card__title"><?php esc_html_e( 'Betrouwbare service', 'hds' ); ?></h3>
						<p class="hds-usp-card__desc"><?php esc_html_e( 'Afspraak is afspraak. Wij leveren constante kwaliteit volgens een duidelijke planning.', 'hds' ); ?></p>
					</article>
					<article class="hds-card hds-usp-card">
						<h3 class="hds-usp-card__title"><?php esc_html_e( 'Ervaren medewerkers', 'hds' ); ?></h3>
						<p class="hds-usp-card__desc"><?php esc_html_e( 'Professionele schoonmakers met ervaring in uiteenlopende sectoren.', 'hds' ); ?></p>
					</article>
					<article class="hds-card hds-usp-card">
						<h3 class="hds-usp-card__title"><?php esc_html_e( 'Flexibele planning', 'hds' ); ?></h3>
						<p class="hds-usp-card__desc"><?php esc_html_e( 'Werkzaamheden afgestemd op uw openingstijden en bedrijfsprocessen.', 'hds' ); ?></p>
					</article>
					<article class="hds-card hds-usp-card">
						<h3 class="hds-usp-card__title"><?php esc_html_e( 'Duurzame werkwijze', 'hds' ); ?></h3>
						<p class="hds-usp-card__desc"><?php esc_html_e( 'Wij werken met professionele producten en milieubewuste schoonmaakmethoden.', 'hds' ); ?></p>
					</article>
				</div>
			</div>
		</section>

		<section class="hds-process-section" aria-labelledby="hds-process-heading">
			<div class="container">
				<header class="hds-process-header">
					<h2 id="hds-process-heading"><?php esc_html_e( 'Onze werkwijze', 'hds' ); ?></h2>
				</header>
				<ol class="hds-process-steps">
					<li class="hds-process-step">
						<span class="hds-process-step__number" aria-hidden="true">1</span>
						<h3 class="hds-process-step__title"><?php esc_html_e( 'Aanvraag', 'hds' ); ?></h3>
						<p class="hds-process-step__desc"><?php esc_html_e( 'Neem contact met ons op en vertel ons uw wensen.', 'hds' ); ?></p>
					</li>
					<li class="hds-process-step">
						<span class="hds-process-step__number" aria-hidden="true">2</span>
						<h3 class="hds-process-step__title"><?php esc_html_e( 'Vrijblijvende offerte', 'hds' ); ?></h3>
						<p class="hds-process-step__desc"><?php esc_html_e( 'Wij analyseren uw situatie en sturen een duidelijke offerte.', 'hds' ); ?></p>
					</li>
					<li class="hds-process-step">
						<span class="hds-process-step__number" aria-hidden="true">3</span>
						<h3 class="hds-process-step__title"><?php esc_html_e( 'Planning', 'hds' ); ?></h3>
						<p class="hds-process-step__desc"><?php esc_html_e( 'Samen plannen we de werkzaamheden op een geschikt moment.', 'hds' ); ?></p>
					</li>
					<li class="hds-process-step">
						<span class="hds-process-step__number" aria-hidden="true">4</span>
						<h3 class="hds-process-step__title"><?php esc_html_e( 'Uitvoering', 'hds' ); ?></h3>
						<p class="hds-process-step__desc"><?php esc_html_e( 'Ons team voert de werkzaamheden zorgvuldig en volgens afspraak uit.', 'hds' ); ?></p>
					</li>
				</ol>
			</div>
		</section>

		<section class="service-cross-sell">
		<?php echo hds_render_cross_sell_section(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</section>

	<section class="cta-banner">
		<div class="container">
			<h2><?php esc_html_e( 'Vrijblijvende offerte aanvragen', 'hds' ); ?></h2>
			<p><?php esc_html_e( 'Wij denken graag met u mee over de beste oplossing.', 'hds' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php esc_html_e( 'Offerte aanvragen', 'hds' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
