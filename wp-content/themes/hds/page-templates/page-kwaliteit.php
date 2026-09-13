<?php
/**
 * Template Name: Kwaliteit & Veiligheid
 *
 * Dedicated template for the Kwaliteit & Veiligheid page (P57).
 *
 * Approved section order:
 *   hero → intro → four quality principles → veilig werken →
 *   visual break → kwaliteitsaanpak → duurzaamheid → verwachtingen →
 *   FAQ → final CTA
 *
 * The Visual Break and Veilig werken images are prepared as post-meta
 * hooks (hds_visual_break_image / hds_safety_image) so dedicated images
 * can be assigned later without touching this template.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">

	<?php
	$hero_title     = get_the_title();
	$hero_subtitle  = __( 'Zorgvuldig werken, duidelijke afspraken en aandacht voor veiligheid.', 'hds' );
	$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	$hero_image_alt = __( 'Zorgvuldig en veilig schoonmaken door Hamdoun Schoonmaak', 'hds' );
	$hero_cta_text  = __( 'Vrijblijvende offerte', 'hds' );
	$hero_cta_url   = home_url( '/offerte-aanvragen/' );

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_image_id', $hero_image_id );
	set_query_var( 'hero_image_alt', $hero_image_alt );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );

	get_template_part( 'parts/hero' );
	?>

	<?php hds_breadcrumbs(); ?>

	<section class="hds-usp-section" aria-labelledby="kwaliteit-intro-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="kwaliteit-intro-heading"><?php esc_html_e( 'Kwaliteit begint met duidelijke afspraken', 'hds' ); ?></h2>
			</header>
			<p class="about-mission-text">
				<?php esc_html_e( 'Kwaliteit begint met goed luisteren. Wij nemen de tijd om uw wensen te begrijpen, maken duidelijke afspraken en voeren het werk vervolgens zorgvuldig uit. Zo weet u wat u van ons kunt verwachten.', 'hds' ); ?>
				<a href="<?php echo esc_url( home_url( '/over-hds/' ) ); ?>"><?php esc_html_e( 'Lees meer over Hamdoun Schoonmaak.', 'hds' ); ?></a>
			</p>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="kwaliteit-principes-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="kwaliteit-principes-heading"><?php esc_html_e( 'Onze kwaliteitsprincipes', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				$principes = array(
					array(
						'title' => __( 'Zorgvuldigheid', 'hds' ),
						'desc'  => __( 'Wij werken nauwkeurig en met oog voor detail, zodat het resultaat er verzorgd uitziet.', 'hds' ),
						'icon'  => 'award',
					),
					array(
						'title' => __( 'Duidelijke afspraken', 'hds' ),
						'desc'  => __( 'Vooraf spreken wij helder af wat wij doen, wanneer en hoe. Geen verrassingen achteraf.', 'hds' ),
						'icon'  => 'check',
					),
					array(
						'title' => __( 'Vaste werkwijze', 'hds' ),
						'desc'  => __( 'Wij werken volgens een vaste, betrouwbare aanpak die wij per locatie afstemmen.', 'hds' ),
						'icon'  => 'refresh',
					),
					array(
						'title' => __( 'Aandacht voor veiligheid', 'hds' ),
						'desc'  => __( 'Wij houden rekening met uw werkomgeving, de mensen om ons heen en onze eigen medewerkers.', 'hds' ),
						'icon'  => 'shield',
					),
				);

				foreach ( $principes as $principe ) :
					echo hds_usp_card( $principe['title'], $principe['desc'], hds_svg_icon( $principe['icon'], 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endforeach;
				?>
			</div>
		</div>
	</section>

	<section class="kwaliteit-veilig" aria-labelledby="kwaliteit-veilig-heading">
		<div class="container">
			<header class="kwaliteit-veilig__header">
				<h2 id="kwaliteit-veilig-heading"><?php esc_html_e( 'Veilig werken', 'hds' ); ?></h2>
			</header>
			<div class="kwaliteit-veilig__grid">
				<div class="kwaliteit-veilig__content">
					<p>
						<?php esc_html_e( 'Veiligheid is onderdeel van onze werkwijze. Wij letten op uw werkomgeving, de materialen die wij gebruiken en de mensen om ons heen. Onze medewerkers zijn in vaste dienst en kennen de locatie. Tijdens de werkzaamheden houden wij rekening met uw bedrijfsprocessen en openingstijden.', 'hds' ); ?>
					</p>
					<ul class="kwaliteit-veilig__list">
						<li><?php esc_html_e( 'Vast opgeleid personeel', 'hds' ); ?></li>
						<li><?php esc_html_e( 'Eén vast aanspreekpunt', 'hds' ); ?></li>
						<li><?php esc_html_e( 'Werkzaamheden buiten kantooruren mogelijk', 'hds' ); ?></li>
					</ul>
					<p>
						<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn--primary">
							<?php esc_html_e( 'Bespreek uw wensen', 'hds' ); ?>
						</a>
					</p>
				</div>
				<div class="kwaliteit-veilig__media">
					<?php
					$safety_image = (int) get_post_meta( get_the_ID(), 'hds_safety_image', true );
					if ( ! $safety_image ) {
						$safety_image = hds_get_attachment_id_by_filename( 'hamdoun-veilig-werken-1.png' );
					}
					if ( $safety_image && wp_get_attachment_image_url( $safety_image, 'large' ) ) {
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$safety_image,
							'large',
							false,
							array(
								'alt'     => __( 'Schoonmaakmedewerker van Hamdoun werkt veilig met beschermende handschoenen en veiligheidsbril in een moderne werkomgeving.', 'hds' ),
								'loading' => 'lazy',
							)
						);
					} else {
						echo '<div class="kwaliteit-veilig__placeholder" aria-hidden="true"></div>';
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<?php
	$visual_break_id = (int) get_post_meta( get_the_ID(), 'hds_visual_break_image', true );
	if ( ! $visual_break_id ) {
		$visual_break_id = hds_get_attachment_id_by_filename( 'hamdoun-kwaliteit-veiligheid-visual-break3.webp' );
	}
	echo hds_render_service_visual_break( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$visual_break_id,
		__( 'Schoonmaakmedewerker van Hamdoun reinigt een moderne werkomgeving met aandacht voor kwaliteit en veiligheid.', 'hds' )
	);
	?>

	<?php
	echo hds_render_process_timeline( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		__( 'Onze kwaliteitsaanpak', 'hds' ),
		array(
			array(
				'title'       => __( 'Bespreken', 'hds' ),
				'description' => __( 'Wij bespreken uw wensen en de werkzaamheden.', 'hds' ),
			),
			array(
				'title'       => __( 'Afspraken maken', 'hds' ),
				'description' => __( 'Wij maken duidelijke afspraken over de uitvoering.', 'hds' ),
			),
			array(
				'title'       => __( 'Zorgvuldig uitvoeren', 'hds' ),
				'description' => __( 'Wij voeren de werkzaamheden zorgvuldig uit volgens afspraak.', 'hds' ),
			),
			array(
				'title'       => __( 'Evalueren', 'hds' ),
				'description' => __( 'Wij blijven openstaan voor feedback en eventuele verbeteringen.', 'hds' ),
			),
		)
	);
	?>

	<section class="hds-usp-section" aria-labelledby="kwaliteit-duurzaamheid-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="kwaliteit-duurzaamheid-heading"><?php esc_html_e( 'Duurzaamheid', 'hds' ); ?></h2>
			</header>
			<p class="about-mission-text">
				<?php esc_html_e( 'Milieubewust werken met professionele producten en methoden. Waar mogelijk werken wij bewust met professionele en milieubewuste schoonmaakmiddelen.', 'hds' ); ?>
			</p>
		</div>
	</section>

	<section class="hds-usp-section" aria-labelledby="kwaliteit-verwachtingen-heading">
		<div class="container">
			<header class="hds-usp-header">
				<h2 id="kwaliteit-verwachtingen-heading"><?php esc_html_e( 'Wat mag u van ons verwachten?', 'hds' ); ?></h2>
			</header>
			<div class="hds-usp-grid">
				<?php
				$verwachtingen = array(
					array(
						'title' => __( 'Duidelijke communicatie', 'hds' ),
						'desc'  => __( 'U heeft één vast aanspreekpunt dat helder communiceert en met u meedenkt.', 'hds' ),
						'icon'  => 'phone',
					),
					array(
						'title' => __( 'Zorgvuldige uitvoering', 'hds' ),
						'desc'  => __( 'Wij voeren de afgesproken werkzaamheden zorgvuldig en consequent uit.', 'hds' ),
						'icon'  => 'check',
					),
					array(
						'title' => __( 'Betrouwbare samenwerking', 'hds' ),
						'desc'  => __( 'Wij komen onze afspraken na en blijven openstaan voor uw feedback.', 'hds' ),
						'icon'  => 'shield',
					),
				);

				foreach ( $verwachtingen as $verwachting ) :
					echo hds_usp_card( $verwachting['title'], $verwachting['desc'], hds_svg_icon( $verwachting['icon'], 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				endforeach;
				?>
			</div>
<p class="kwaliteit-verwachtingen__link">
			<a href="<?php echo esc_url( home_url( '/referenties/' ) ); ?>"><?php esc_html_e( 'Bekijk wat onze klanten over ons zeggen', 'hds' ); ?></a>
		</p>
		</div>
	</section>

	<section class="hds-faq-section" aria-labelledby="kwaliteit-faq-heading">
		<div class="container">
			<header class="hds-faq-header">
				<h2 id="kwaliteit-faq-heading"><?php esc_html_e( 'Veelgestelde vragen', 'hds' ); ?></h2>
				<p class="hds-faq-header__intro"><?php esc_html_e( 'Antwoorden op veelgestelde vragen over onze werkwijze en afspraken.', 'hds' ); ?></p>
			</header>
			<div class="hds-faq-list">
				<?php
				$faq_items = array(
					array(
						'q' => __( 'Hoe gaat Hamdoun Schoonmaak te werk?', 'hds' ),
						'a' => __( 'Wij bespreken eerst uw wensen en de werkzaamheden. Daarna maken wij duidelijke afspraken over de uitvoering en stemmen wij de werkwijze af op uw locatie.', 'hds' ),
					),
					array(
						'q' => __( 'Kunnen wij vooraf onze wensen bespreken?', 'hds' ),
						'a' => __( 'Ja. U heeft één vast aanspreekpunt dat de tijd neemt om uw wensen en de mogelijkheden te bespreken.', 'hds' ),
					),
					array(
						'q' => __( 'Hoe houden jullie rekening met veiligheid?', 'hds' ),
						'a' => __( 'Wij houden rekening met uw werkomgeving, de gebruikte materialen en de mensen om ons heen. Onze medewerkers zijn in vaste dienst en kennen de locatie.', 'hds' ),
					),
					array(
						'q' => __( 'Hoe gaan jullie om met feedback op het werk?', 'hds' ),
						'a' => __( 'Wij blijven openstaan voor feedback en gebruiken uw opmerkingen om de werkzaamheden waar mogelijk te verbeteren.', 'hds' ),
					),
					array(
						'q' => __( 'Kunnen jullie buiten kantooruren werken?', 'hds' ),
						'a' => __( 'Ja. Wij kunnen werkzaamheden uitvoeren buiten uw openingstijden, zodat u er geen hinder van ondervindt.', 'hds' ),
					),
					array(
						'q' => __( 'Kan ik vrijblijvend een offerte aanvragen?', 'hds' ),
						'a' => __( 'Ja. Wij maken graag een offerte op maat, zonder verplichtingen. Bespreek uw wensen met ons, dan denken wij graag mee.', 'hds' ),
					),
				);

				foreach ( $faq_items as $faq_item ) :
					?>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question"><?php echo esc_html( $faq_item['q'] ); ?></summary>
						<div class="hds-faq-item__answer">
							<p><?php echo esc_html( $faq_item['a'] ); ?></p>
						</div>
					</details>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</section>

	<?php
	echo hds_cta_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		__( 'Samen werken aan een schoon en verzorgd resultaat', 'hds' ),
		__( 'Vraag vrijblijvend een offerte aan en bespreek uw wensen met een vast aanspreekpunt.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();