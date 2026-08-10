<?php
/**
 * Template Name: About
 *
 * Used for Over HDS (P11) and Kwaliteit & Veiligheid (P12).
 *
 * @package HDS
 */

/**
 * Return section data for the About template, keyed by page slug.
 *
 * Each section contains:
 *   key          — unique identifier used for the aria-labelledby target
 *   heading      — section heading (H2)
 *   content_type — 'paragraphs' or 'cards'
 *   items        — string[] for paragraphs, array[] of {title, description} for cards
 *
 * @param string $slug Page slug ('over-hds' or 'kwaliteit-en-veiligheid').
 * @return array Section configuration.
 */
function hds_get_about_sections( string $slug ): array {
	$sections = [
		'over-hds'                => [
			[
				'key'          => 'mission',
				'heading'      => __( 'Onze missie', 'hds' ),
				'content_type' => 'paragraphs',
				'items'        => [
					__( 'Wij geloven dat een schone werkomgeving de basis is voor productiviteit, gezondheid en een goede eerste indruk. Daarom levert HDS al meer dan 20 jaar hoogwaardige schoonmaakdiensten aan bedrijven in West-Brabant en Zeeland.', 'hds' ),
				],
			],
			[
				'key'          => 'values',
				'heading'      => __( 'Onze kernwaarden', 'hds' ),
				'content_type' => 'cards',
				'items'        => [
					[ 'title' => __( 'Betrouwbaarheid', 'hds' ), 'description' => __( 'Afspraak is afspraak. U kunt op ons rekenen, elke dag weer.', 'hds' ) ],
					[ 'title' => __( 'Kwaliteit', 'hds' ), 'description' => __( 'Wij leveren consequent hoge kwaliteit met oog voor detail.', 'hds' ) ],
					[ 'title' => __( 'Flexibiliteit', 'hds' ), 'description' => __( 'Wij stemmen onze werkzaamheden af op uw planning en bedrijfsprocessen.', 'hds' ) ],
					[ 'title' => __( 'Duurzaamheid', 'hds' ), 'description' => __( 'Milieubewust werken met professionele producten en methoden.', 'hds' ) ],
				],
			],
			[
				'key'          => 'why',
				'heading'      => __( 'Waarom bedrijven kiezen voor HDS', 'hds' ),
				'content_type' => 'cards',
				'items'        => [
					[ 'title' => __( '20+ jaar ervaring', 'hds' ), 'description' => __( 'Al meer dan twee decennia een vertrouwde partner in schoonmaak.', 'hds' ) ],
					[ 'title' => __( 'Regionale partner', 'hds' ), 'description' => __( 'Wij kennen de regio West-Brabant en Zeeland als geen ander.', 'hds' ) ],
					[ 'title' => __( 'Persoonlijk contact', 'hds' ), 'description' => __( 'Geen callcenter, maar een vaste contactpersoon die u kent.', 'hds' ) ],
					[ 'title' => __( 'Volledig verzekerd', 'hds' ), 'description' => __( 'U zit nooit met risico\'s. Onze diensten zijn volledig verzekerd.', 'hds' ) ],
				],
			],
		],
		'kwaliteit-en-veiligheid' => [
			[
				'key'          => 'mission',
				'heading'      => __( 'Kwaliteit en veiligheid', 'hds' ),
				'content_type' => 'paragraphs',
				'items'        => [
					__( 'Kwaliteit en veiligheid staan centraal in alles wat wij doen. Wij werken volgens de hoogste normen, met opgeleide medewerkers, gecertificeerde apparatuur en doordachte werkprotocollen. Zo garanderen wij niet alleen een schoon resultaat, maar ook een veilige werkomgeving voor iedereen.', 'hds' ),
				],
			],
			[
				'key'          => 'why',
				'heading'      => __( 'Waarom kwaliteit en veiligheid belangrijk zijn', 'hds' ),
				'content_type' => 'cards',
				'items'        => [
					[ 'title' => __( 'OSB-gecertificeerd', 'hds' ), 'description' => __( 'Wij zijn gecertificeerd door de Ondernemersorganisatie Schoonmaak- en Bedrijfsdiensten.', 'hds' ) ],
					[ 'title' => __( 'Opgeleid personeel', 'hds' ), 'description' => __( 'Onze medewerkers zijn getraind in de juiste schoonmaaktechnieken en veiligheidsprotocollen.', 'hds' ) ],
					[ 'title' => __( 'Professionele middelen', 'hds' ), 'description' => __( 'Wij gebruiken uitsluitend professionele schoonmaakmiddelen en gekeurde apparatuur.', 'hds' ) ],
					[ 'title' => __( 'VOG-gecertificeerd personeel', 'hds' ), 'description' => __( 'Al onze schoonmaakmedewerkers zijn in vaste dienst, volledig opgeleid en beschikken over een VOG-verklaring (Verklaring Omtrent Gedrag). Uw veiligheid en vertrouwen staan voorop.', 'hds' ) ],
				],
			],
		],
	];

	return $sections[ $slug ] ?? $sections['over-hds'];
}

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
	$hero_title     = get_the_title();
	$hero_subtitle  = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image_id  = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$hero_image_url = $hero_image_id ? wp_get_attachment_image_url( $hero_image_id, 'hds-hero' ) : '';
	$hero_cta_text  = __( 'Vrijblijvende offerte', 'hds' );
	$hero_cta_url   = home_url( '/offerte-aanvragen/' );

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );

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

	<?php
	$slug     = get_post_field( 'post_name' );
	$sections = hds_get_about_sections( $slug );

	foreach ( $sections as $section ) :
		$section_id = 'about-' . $section['key'];
		?>
		<section class="hds-usp-section" aria-labelledby="<?php echo esc_attr( $section_id ); ?>">
			<div class="container">
				<header class="hds-usp-header">
					<h2 id="<?php echo esc_attr( $section_id ); ?>"><?php echo esc_html( $section['heading'] ); ?></h2>
				</header>
				<?php if ( 'paragraphs' === $section['content_type'] ) : ?>
					<?php foreach ( $section['items'] as $paragraph ) : ?>
						<p class="about-mission-text"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				<?php elseif ( 'cards' === $section['content_type'] ) : ?>
					<div class="hds-usp-grid">
						<?php foreach ( $section['items'] as $card ) : ?>
							<?php echo hds_usp_card( $card['title'], $card['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endforeach; ?>

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
