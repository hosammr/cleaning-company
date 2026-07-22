<?php
/**
 * Schema — LocalBusiness JSON-LD template part.
 *
 * Included via get_template_part() in header.php.
 * Outputs LocalBusiness (HomeAndConstructionBusiness) structured data.
 *
 * @package HDS
 */

$address = hds_get_address();
$postal  = hds_get_postal_city();
$phone   = hds_get_phone();
$email   = hds_get_email();
$kvk     = get_theme_mod( 'hds_kvk' );
$facebook  = get_theme_mod( 'hds_facebook_url' );
$instagram = get_theme_mod( 'hds_instagram_url' );
$gbp       = get_theme_mod( 'hds_gbp_url' );

$same_as = array_filter( [ $facebook, $instagram, $gbp ] );

$data = [
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
	$data['address'] = [
		'@type'           => 'PostalAddress',
		'streetAddress'   => $address,
		'postalCode'      => $parts[0] ?? '',
		'addressLocality' => $parts[1] ?? '',
		'addressCountry'  => 'NL',
	];
}

if ( $same_as ) {
	$data['sameAs'] = array_values( $same_as );
}

$hours = get_theme_mod( 'hds_opening_hours' );
if ( $hours ) {
	$data['openingHours'] = array_filter( array_map( 'trim', explode( "\n", $hours ) ) );
}

echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
