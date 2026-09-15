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
	$tiktok    = get_theme_mod( 'hds_tiktok_url' );
	$gbp       = get_theme_mod( 'hds_gbp_url' );

	if ( $facebook ) {
		$same_as[] = esc_url( $facebook );
	}
	if ( $instagram ) {
		$same_as[] = esc_url( $instagram );
	}
	if ( $tiktok ) {
		$same_as[] = esc_url( $tiktok );
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

	$same_as = array_map(
		'esc_url',
		array_values( array_unique( array_filter( [
			get_theme_mod( 'hds_facebook_url' ),
			get_theme_mod( 'hds_instagram_url' ),
			get_theme_mod( 'hds_tiktok_url' ),
			get_theme_mod( 'hds_gbp_url' ),
		] ) ) )
	);

	$schema = [
		'@context'        => 'https://schema.org',
		'@type'           => 'HomeAndConstructionBusiness',
		'@id'             => home_url( '/#localbusiness' ),
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'url'             => home_url(),
		'telephone'       => hds_get_phone_intl( $phone ),
		'email'           => $email,
		'priceRange'      => '€€',
		'image'           => has_custom_logo() ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
		'openingHours'    => [
			'Mo-Fr 08:00-17:00',
			'Sa 12:00-17:00',
		],
		'areaServed'      => [
			'@type' => 'State',
			'name'  => 'Groningen',
		],
	] + ( $same_as ? [ 'sameAs' => $same_as ] : [] );

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

	$description = wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ?: $post->post_content ), 30, '...' );

	// Service pages render entirely from the approved service data in
	// inc/services.php and carry no excerpt/body content. Fall back to the
	// approved service description so the Service schema is never empty.
	if ( '' === $description && 'page-templates/page-service.php' === get_page_template_slug( $post ) ) {
		$service = hds_get_service( $post->post_name );
		if ( $service && ! empty( $service['seo_description'] ) ) {
			$description = $service['seo_description'];
		}
	}

	return [
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'name'        => get_the_title( $post ),
		'description' => $description,
		'provider'    => [
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		],
		'url'         => get_permalink( $post ),
		'areaServed'  => [
			'@type' => 'City',
			'name'  => hds_get_postal_city() ?: __( 'Provincie Groningen', 'hds' ),
		],
		'serviceType' => get_the_title( $post ),
	];
}

/**
 * Build FAQPage schema for service template pages.
 *
 * Derives from the same service-specific FAQ data as the visible FAQ
 * renderer in page-templates/page-service.php (hds_get_service()).
 * Returns an empty array when the service has no FAQ items, so no
 * FAQPage schema is emitted without matching visible FAQ content.
 */
function hds_get_service_faq_schema(): array {
	$slug      = get_post_field( 'post_name', get_the_ID() );
	$service   = hds_get_service( $slug );
	$faq_items = $service['faq'] ?? [];

	if ( empty( $faq_items ) ) {
		return [];
	}

	$questions = [];
	foreach ( $faq_items as $item ) {
		if ( empty( $item['q'] ) || empty( $item['a'] ) ) {
			continue;
		}
		$questions[] = [
			'@type'          => 'Question',
			'name'           => $item['q'],
			'acceptedAnswer' => [
				'@type' => 'Answer',
				'text'  => $item['a'],
			],
		];
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
 * Build FAQPage schema from FAQ content blocks.
 */
function hds_get_faqpage_schema( int $post_id ): array {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return [];
	}

	$blocks = parse_blocks( $post->post_content );
	$questions = [];
	$seen      = [];

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
						$key = mb_strtolower( trim( wp_strip_all_tags( $question_html ) ) );
						if ( isset( $seen[ $key ] ) ) {
							continue;
						}
						$seen[ $key ] = true;
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

	// Parse native core/details blocks: summary = question, inner content = answer.
	foreach ( $blocks as $block ) {
		if ( $block['blockName'] !== 'core/details' || empty( $block['innerBlocks'] ) ) {
			continue;
		}

		$question_html = trim( wp_strip_all_tags( $block['attrs']['summary'] ?? '' ) );
		if ( '' === $question_html ) {
			if ( preg_match( '~<summary[^>]*>(.*?)</summary>~is', render_block( $block ), $matches ) ) {
				$question_html = trim( wp_strip_all_tags( $matches[1] ) );
			}
		}

		$answer_html = '';
		foreach ( $block['innerBlocks'] as $inner ) {
			$answer_html .= render_block( $inner );
		}

		if ( $question_html && trim( wp_strip_all_tags( $answer_html ) ) ) {
			$key = mb_strtolower( $question_html );
			if ( isset( $seen[ $key ] ) ) {
				continue;
			}
			$seen[ $key ] = true;
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
