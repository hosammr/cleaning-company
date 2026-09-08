<?php
/**
 * Template Name: Service
 *
 * Shared service detail page template. All eight HDS service pages use
 * this single template. Every section is rendered from the service data
 * in inc/services.php (via hds_get_service()), so there is no duplicated
 * markup between the service pages.
 *
 * Rendering order:
 *   hero → breadcrumbs → intro → checklist → workflow → why hamdoun
 *   → visual break (if image) → audience (if data) → related services
 *   → testimonials (if available) → FAQ → final CTA
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
		$hero_image_id =
			$service['hero_image']
			?: (int) get_post_meta( get_queried_object_id(), 'hds_hero_image', true )
			?: get_post_thumbnail_id( get_queried_object_id() );
		$hero_cta_text = __( 'Vraag vrijblijvend een offerte aan', 'hds' );
		$hero_cta_url  = home_url( '/offerte-aanvragen/' );
		$hero_eyebrow  = $service['eyebrow'] ?? __( 'Onze diensten', 'hds' );
	} else {
		$hero_title    = get_the_title();
		$hero_subtitle = get_post_meta( get_the_ID(), 'hds_subtitle', true );
		$hero_image_id = (int) get_post_meta( get_the_ID(), 'hds_hero_image', true );
		$cta_override  = get_post_meta( get_the_ID(), 'hds_cta_override', true );
		$hero_cta_text = $cta_override ?: __( 'Vrijblijvende offerte', 'hds' );
		$hero_cta_url  = home_url( '/offerte-aanvragen/' );
		$hero_eyebrow  = get_post_meta( get_the_ID(), 'hds_eyebrow', true ) ?: __( 'Onze diensten', 'hds' );
	}

	$hero_image_url = '';
	if ( $hero_image_id ) {
		$hero_image_url = wp_get_attachment_image_url( $hero_image_id, 'hds-hero' );
	}
	if ( ! $hero_image_url ) {
		$hero_image_id = 0;
		$hero_image_url = HDS_URI . '/screenshot.png';
	}
	$hero_image_alt = $service ? sprintf(
		/* translators: %s: service name. */
		__( '%s door Hamdoun Schoonmaak', 'hds' ),
		$hero_title
	) : '';

	set_query_var( 'hero_title', $hero_title );
	set_query_var( 'hero_eyebrow', $hero_eyebrow );
	set_query_var( 'hero_subtitle', $hero_subtitle );
	set_query_var( 'hero_image_url', $hero_image_url );
	set_query_var( 'hero_image_id', $hero_image_id );
	set_query_var( 'hero_image_alt', $hero_image_alt );
	set_query_var( 'hero_cta_text', $hero_cta_text );
	set_query_var( 'hero_cta_url', $hero_cta_url );
	get_template_part( 'parts/hero' );
	?>

	<?php hds_breadcrumbs(); ?>

	<?php
	$service_intro = $service['intro'] ?? null;
	if ( $service_intro && ! empty( $service_intro['paragraphs'] ) ) {
		echo hds_render_service_intro( $service_intro ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>

	<?php
	$checklist = $service['checklist'] ?? [];
	echo hds_render_service_checklist( $checklist, __( 'Onze dienst', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php
	$workflow = ! empty( $service['workflow'] ) ? $service['workflow'] : hds_get_default_workflow();
	echo hds_render_process_timeline( __( 'Onze werkwijze', 'hds' ), $workflow ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php
	echo hds_render_why_hamdoun_section( __( 'Waarom Hamdoun', 'hds' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php
	$visual_break_id = (int) ( $service['visual_break_image'] ?? 0 );
	echo hds_render_service_visual_break( $visual_break_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

	<?php
	$industries = $service['industries'] ?? [];
	echo hds_render_service_audience( $industries ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>

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

	<?php
	$service_faq = $service['faq'] ?? [];
	$faq_items   = ! empty( $service_faq ) ? $service_faq : [
		[ 'q' => __( 'Hoe vaak adviseren jullie schoonmaak?', 'hds' ), 'a' => __( 'Dit is afhankelijk van uw bedrijf, bezoekersaantallen en wensen. Wij adviseren u graag.', 'hds' ) ],
		[ 'q' => __( 'Werken jullie buiten kantooruren?', 'hds' ), 'a' => __( 'Ja. Wij kunnen werkzaamheden uitvoeren buiten uw openingstijden.', 'hds' ) ],
		[ 'q' => __( 'Gebruiken jullie milieuvriendelijke producten?', 'hds' ), 'a' => __( 'Ja. Waar mogelijk gebruiken wij professionele en milieubewuste schoonmaakmiddelen.', 'hds' ) ],
		[ 'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ), 'a' => __( 'Ja. Wij maken graag een offerte op maat zonder verplichtingen.', 'hds' ) ],
		[ 'q' => __( 'Zijn jullie diensten beschikbaar voor zowel kleine als grote bedrijven?', 'hds' ), 'a' => __( 'Ja. Wij werken voor organisaties van iedere omvang.', 'hds' ) ],
	];
	?>
	<section class="hds-faq-section" aria-labelledby="hds-faq-heading">
		<div class="container">
			<header class="hds-faq-header">
				<h2 id="hds-faq-heading"><?php esc_html_e( 'Veelgestelde vragen', 'hds' ); ?></h2>
				<p class="hds-faq-header__intro"><?php esc_html_e( 'Hier vindt u antwoorden op de meest gestelde vragen over onze schoonmaakdiensten.', 'hds' ); ?></p>
			</header>
			<div class="hds-faq-list">
				<?php foreach ( $faq_items as $faq_item ) : ?>
					<details class="hds-faq-item">
						<summary class="hds-faq-item__question">
							<?php echo esc_html( $faq_item['q'] ); ?>
						</summary>
						<div class="hds-faq-item__answer">
							<p><?php echo esc_html( $faq_item['a'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php
	echo hds_cta_section(
		__( 'Vrijblijvende offerte aanvragen', 'hds' ),
		__( 'Wij denken graag met u mee over de beste oplossing.', 'hds' ),
		__( 'Vraag vrijblijvend een offerte aan', 'hds' ),
		home_url( '/offerte-aanvragen/' ),
		'primary',
		[
			__( 'Reactie binnen 24 uur', 'hds' ),
			__( 'Vrijblijvende offerte', 'hds' ),
			__( 'Geen verplichtingen', 'hds' ),
		]
	);
	?>
</main>

<?php
get_footer();
