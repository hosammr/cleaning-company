<?php
/**
 * JSON-LD structured data generation.
 *
 * Outputs all schema types via wp_head. Schema is built from Customizer
 * company info and per-page metadata.
 *
 * @package HDS
 */

/**
 * Output all JSON-LD schema in <head>.
 */
function hds_output_schema(): void {
	$schemas = [];

	$schemas[] = hds_get_organization_schema();

	if ( is_front_page() || is_page( [ 'contact', 'over-hds' ] ) ) {
		$schemas[] = hds_get_localbusiness_schema();
	}

	if ( is_page_template( 'page-templates/page-service.php' ) ) {
		$schemas[] = hds_get_service_schema( get_the_ID() );
		$schemas[] = hds_get_service_faq_schema();
	}

	if ( is_page( 'veelgestelde-vragen' ) || is_page_template( 'page-templates/page-faq.php' ) ) {
		$schemas[] = hds_get_faqpage_schema( get_the_ID() );
	}

	foreach ( $schemas as $schema ) {
		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_head', 'hds_output_schema', 5 );

/**
 * Build Organization schema.
 */
function hds_get_organization_schema(): array {
	$same_as = [];
	$facebook  = get_theme_mod( 'hds_facebook_url' );
	$instagram = get_theme_mod( 'hds_instagram_url' );
	$gbp       = get_theme_mod( 'hds_gbp_url' );

	if ( $facebook ) {
		$same_as[] = esc_url( $facebook );
	}
	if ( $instagram ) {
		$same_as[] = esc_url( $instagram );
	}
	if ( $gbp ) {
		$same_as[] = esc_url( $gbp );
	}

	return [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => get_bloginfo( 'name' ),
		'url'      => home_url(),
		'email'    => hds_get_email(),
		'telephone'=> hds_get_phone(),
	] + ( $same_as ? [ 'sameAs' => $same_as ] : [] );
}

/**
 * Build LocalBusiness (HomeAndConstructionBusiness) schema.
 */
function hds_get_localbusiness_schema(): array {
	$address   = hds_get_address();
	$postal    = hds_get_postal_city();
	$phone     = hds_get_phone();
	$email     = hds_get_email();

	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'HomeAndConstructionBusiness',
		'@id'             => home_url( '/#localbusiness' ),
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'url'             => home_url(),
		'telephone'       => $phone,
		'email'           => $email,
		'priceRange'      => '€€',
		'image'           => has_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
	];

	if ( $address && $postal ) {
		$parts = explode( ' ', $postal, 2 );
		$schema['address'] = [
			'@type'           => 'PostalAddress',
			'streetAddress'   => $address,
			'postalCode'      => $parts[0] ?? '',
			'addressLocality' => $parts[1] ?? '',
			'addressCountry'  => 'NL',
		];
	}

	$hours = get_theme_mod( 'hds_opening_hours' );
	if ( $hours ) {
		$schema['openingHours'] = array_filter( array_map( 'trim', explode( "\n", $hours ) ) );
	}

	return $schema;
}

/**
 * Build Service schema for a single service page.
 */
function hds_get_service_schema( int $post_id ): array {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return [];
	}

	return [
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'name'        => get_the_title( $post ),
		'description' => wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ?: $post->post_content ), 30, '...' ),
		'provider'    => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		],
		'url'         => get_permalink( $post ),
		'areaServed'  => [
			'@type' => 'City',
			'name'  => hds_get_postal_city() ?: __( 'West-Brabant en Zeeland', 'hds' ),
		],
		'serviceType' => get_the_title( $post ),
	];
}

/**
 * Build FAQPage schema for service template pages.
 *
 * Matches the hardcoded FAQ items in page-templates/page-service.php.
 */
function hds_get_service_faq_schema(): array {
	$faq_items = [
		[
			'question' => __( 'Hoe vaak adviseren jullie schoonmaak?', 'hds' ),
			'answer'   => __( 'Dit is afhankelijk van uw bedrijf, bezoekersaantallen en wensen. Wij adviseren u graag.', 'hds' ),
		],
		[
			'question' => __( 'Werken jullie buiten kantooruren?', 'hds' ),
			'answer'   => __( 'Ja. Wij kunnen werkzaamheden uitvoeren buiten uw openingstijden.', 'hds' ),
		],
		[
			'question' => __( 'Gebruiken jullie milieuvriendelijke producten?', 'hds' ),
			'answer'   => __( 'Ja. Waar mogelijk gebruiken wij professionele en milieubewuste schoonmaakmiddelen.', 'hds' ),
		],
		[
			'question' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
			'answer'   => __( 'Ja. Wij maken graag een offerte op maat zonder verplichtingen.', 'hds' ),
		],
		[
			'question' => __( 'Zijn jullie diensten beschikbaar voor zowel kleine als grote bedrijven?', 'hds' ),
			'answer'   => __( 'Ja. Wij werken voor organisaties van iedere omvang.', 'hds' ),
		],
	];

	$questions = [];
	foreach ( $faq_items as $item ) {
		$questions[] = [
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $item['answer'],
			],
		];
	}

	return [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $questions,
	];
}

/**
 * Build FAQPage schema from FAQ content blocks.
 */
function hds_get_faqpage_schema( int $post_id ): array {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return [];
	}

	$blocks = parse_blocks( $post->post_content );
	$questions = [];

	foreach ( $blocks as $block ) {
		if ( $block['blockName'] === 'yoast/faq-block' && ! empty( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $inner ) {
				if ( $inner['blockName'] === 'yoast/faq-question' ) {
					$question_html = '';
					$answer_html   = '';

					foreach ( $inner['innerBlocks'] ?? [] as $child ) {
						if ( $child['blockName'] === 'core/heading' ) {
							$question_html .= wp_strip_all_tags( render_block( $child ) );
						} else {
							$answer_html .= render_block( $child );
						}
					}

					if ( $question_html && $answer_html ) {
						$questions[] = [
							'@type'          => 'Question',
							'name'           => $question_html,
							'acceptedAnswer' => [
								'@type' => 'Answer',
								'text'  => $answer_html,
							],
						];
					}
				}
			}
		}
	}

	if ( empty( $questions ) ) {
		return [];
	}

	return [
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $questions,
	];
}

/**
 * Get JobPosting schema for a vacancy.
 */
function hds_get_jobposting_schema( int $vacancy_id ): array {
	$post = get_post( $vacancy_id );
	if ( ! $post || $post->post_type !== 'hds_vacancy' ) {
		return [];
	}

	$location   = get_post_meta( $vacancy_id, 'hds_location', true );
	$hours      = get_post_meta( $vacancy_id, 'hds_hours_per_week', true );
	$deadline   = get_post_meta( $vacancy_id, 'hds_deadline', true );

	return [
		'@context'        => 'https://schema.org',
		'@type'           => 'JobPosting',
		'title'           => get_the_title( $post ),
		'description'     => wp_trim_words( wp_strip_all_tags( $post->post_content ), 50, '...' ),
		'datePosted'      => get_the_date( 'c', $post ),
		'hiringOrganization' => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'sameAs'=> home_url(),
		],
	] + ( $location ? [ 'jobLocation' => [ '@type' => 'Place', 'address' => [ '@type' => 'PostalAddress', 'addressLocality' => $location, 'addressCountry' => 'NL' ] ] ] : [] )
	  + ( $hours    ? [ 'employmentType' => 'PART_TIME', 'workHours' => $hours . ' ' . __( 'uur per week', 'hds' ) ] : [] )
	  + ( $deadline ? [ 'validThrough' => $deadline ] : [] );
}
