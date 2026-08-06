<?php
/**
 * Template Name: Service
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	$slug    = get_post_field( 'post_name' );
	$service = hds_get_service( $slug );

	if ( $service ) {
		$hero_title    = $service['title'];
		$hero_subtitle = $service['subtitle'];
		$hero_image_id = $service['hero_image'] ?: get_post_thumbnail_id( get_queried_object_id() );
		$hero_cta_text = __( 'Vraag vrijblijvend een offerte aan', 'hds' );
		$hero_cta_url  = home_url( '/offerte-aanvragen/' );
	} else {
		$hero_title    = get_the_title();
		$hero_subtitle = get_post_meta( get_the_ID(), 'hds_subtitle', true );
		$hero_image_id = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
		$cta_override  = get_post_meta( get_the_ID(), 'hds_cta_override', true );
		$hero_cta_text = $cta_override ?: __( 'Vrijblijvende offerte', 'hds' );
		$hero_cta_url  = home_url( '/offerte-aanvragen/' );
	}

	$hero_image_url = '';
	if ( $hero_image_id ) {
		$hero_image_url = wp_get_attachment_image_url( $hero_image_id, 'hds-hero' );
	}
	if ( ! $hero_image_url ) {
		$hero_image_url = HDS_URI . '/screenshot.png';
	}
	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );
	get_template_part( 'parts/hero' );
	?>

	<?php hds_breadcrumbs(); ?>

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
					<?php
					echo hds_usp_card( __( 'Betrouwbare service', 'hds' ), __( 'Afspraak is afspraak. Wij leveren constante kwaliteit volgens een duidelijke planning.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo hds_usp_card( __( 'Ervaren medewerkers', 'hds' ), __( 'Professionele schoonmakers met ervaring in uiteenlopende sectoren.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo hds_usp_card( __( 'Flexibele planning', 'hds' ), __( 'Werkzaamheden afgestemd op uw openingstijden en bedrijfsprocessen.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo hds_usp_card( __( 'Duurzame werkwijze', 'hds' ), __( 'Wij werken met professionele producten en milieubewuste schoonmaakmethoden.', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
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

		<?php
		$related_testimonial_ids = get_posts( [
			'post_type'      => 'hds_testimonial',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_key'       => 'hds_related_service', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => get_the_ID(), // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		] );
		if ( ! empty( $related_testimonial_ids ) ) :
			?>
			<section class="home-testimonials" aria-labelledby="service-testimonials-heading">
				<div class="container">
					<?php
					echo hds_section_header(
						__( 'Wat onze klanten zeggen', 'hds' ),
						'',
						'center'
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo do_blocks( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<!-- wp:hds/testimonial {"count":3,"showRating":true,"selectedItems":' . wp_json_encode( $related_testimonial_ids ) . '} /-->'
					);
					?>
				</div>
			</section>
		<?php endif; ?>

		<section class="hds-faq-section" aria-labelledby="hds-faq-heading">
			<div class="container">
				<header class="hds-faq-header">
					<h2 id="hds-faq-heading"><?php esc_html_e( 'Veelgestelde vragen', 'hds' ); ?></h2>
				</header>
				<div class="hds-faq-list">
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php esc_html_e( 'Hoe vaak adviseren jullie schoonmaak?', 'hds' ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php esc_html_e( 'Dit is afhankelijk van uw bedrijf, bezoekersaantallen en wensen. Wij adviseren u graag.', 'hds' ); ?></p>
						</div>
					</details>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php esc_html_e( 'Werken jullie buiten kantooruren?', 'hds' ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php esc_html_e( 'Ja. Wij kunnen werkzaamheden uitvoeren buiten uw openingstijden.', 'hds' ); ?></p>
						</div>
					</details>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php esc_html_e( 'Gebruiken jullie milieuvriendelijke producten?', 'hds' ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php esc_html_e( 'Ja. Waar mogelijk gebruiken wij professionele en milieubewuste schoonmaakmiddelen.', 'hds' ); ?></p>
						</div>
					</details>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php esc_html_e( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php esc_html_e( 'Ja. Wij maken graag een offerte op maat zonder verplichtingen.', 'hds' ); ?></p>
						</div>
					</details>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php esc_html_e( 'Zijn jullie diensten beschikbaar voor zowel kleine als grote bedrijven?', 'hds' ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php esc_html_e( 'Ja. Wij werken voor organisaties van iedere omvang.', 'hds' ); ?></p>
						</div>
					</details>
				</div>
			</div>
		</section>

	<?php
	echo hds_cta_section(
		__( 'Vrijblijvende offerte aanvragen', 'hds' ),
		__( 'Wij denken graag met u mee over de beste oplossing.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();
