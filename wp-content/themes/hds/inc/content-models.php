<?php
/**
 * Content models — structured content definitions via core WordPress primitives.
 *
 * This file is the core equivalent of ACF Option Pages, Flexible Content layouts,
 * and Repeaters. It defines:
 *
 *  1. Template lock strategies (which blocks are locked where)
 *  2. Page-specific starter content via block templates
 *  3. CPT metadata structures
 *  4. Global content "option" areas (Customizer-backed via inc/customizer.php)
 *  5. SEO field group (Rank Math Pro provides this — no custom implementation needed)
 *
 * Each content model maps to a DHG content type.
 *
 * @package HDS
 */

/**
 * Service Content Model.
 *
 * Layout: hero section → USP grid → body content → cross-sell → CTA banner
 * Managed via: Block Editor on pages with Service template.
 * Custom fields: hds_subtitle, hds_hero_image, hds_service_icon, hds_cta_override
 */
function hds_init_service_content_model(): void {
	$template = [
		[ 'hds/hero-section',    [] ],
		[ 'hds/usp-grid',        [] ],
		[ 'core/paragraph',      [ 'placeholder' => __( 'Uitgebreide beschrijving van de dienst...', 'hds' ) ] ],
		[ 'hds/cross-sell-services', [] ],
		[ 'hds/cta-banner',      [] ],
	];
}

/**
 * FAQ Content Model.
 *
 * Layout: intro paragraph → FAQ blocks (Rank Math/Yoast FAQ Block)
 * Managed via: Block Editor on FAQ page template.
 * No custom post type needed per ADR D-012.
 */
function hds_init_faq_content_model(): void {
	$template = [
		[ 'core/paragraph', [ 'placeholder' => __( 'Hieronder vindt u antwoorden op veelgestelde vragen...', 'hds' ) ] ],
	];
}

/**
 * Testimonial Content Model.
 *
 * CPT: hds_testimonial (non-public, block-queried only)
 * Fields: Title → quote, Content → full testimonial, Meta: author, company, rating, related service
 * Display via: hds/testimonial custom block (server-side rendered)
 */
function hds_get_testimonial_model_schema(): array {
	return [
		'title'    => __( 'Citaat (korte versie)', 'hds' ),
		'content'  => __( 'Volledige referentie tekst', 'hds' ),
		'meta'     => [
			'hds_author_name'     => __( 'Naam', 'hds' ),
			'hds_company_name'    => __( 'Bedrijf', 'hds' ),
			'hds_star_rating'     => __( 'Beoordeling', 'hds' ),
			'hds_related_service' => __( 'Gerelateerde dienst', 'hds' ),
		],
	];
}

/**
 * Download Content Model.
 *
 * Page: /downloads/ (standard Page with Default template)
 * Content: list of download links or embedded PDFs
 * No custom post type needed.
 */
function hds_init_download_content_model(): array {
	return [
		[ 'core/heading', [ 'level' => 2, 'content' => __( 'Downloads', 'hds' ) ] ],
		[ 'core/list',    [] ],
	];
}

/**
 * Hero Section Content Model.
 *
 * Managed via: Block Pattern "hds/hero-section" inserted on any page.
 * Alternatively: Service page template renders hero via PHP (page-templates/page-service.php).
 */
function hds_get_hero_model_variants(): array {
	return [
		'default'   => 'hds/hero-section',
		'service'   => 'page-templates/page-service.php',
		'contact'   => 'page-templates/page-contact.php',
		'about'     => 'page-templates/page-about.php',
	];
}

/**
 * CTA Section Content Model.
 *
 * Managed via: Block Pattern "hds/cta-banner" or PHP-rendered in page templates.
 */
function hds_get_cta_model_variants(): array {
	return [
		'pattern'     => 'hds/cta-banner',
		'php-service' => 'page-templates/page-service.php',
		'php-about'   => 'page-templates/page-about.php',
	];
}

/**
 * Global Options Content Model.
 *
 * Company information (NAP — Name, Address, Phone) stored in Theme Customizer.
 * This is the core equivalent of an ACF Options Page.
 *
 * Fields: address, postal_city, phone, email, KVK, BTW, facebook, instagram, GBP, opening_hours
 * Managed via: Customizer → Bedrijfsgegevens panel (inc/customizer.php)
 * Access via: get_theme_mod('hds_*') or helper functions hds_get_phone(), etc.
 */
function hds_get_global_options_schema(): array {
	return [
		'section'     => 'hds_company_info',
		'title'       => __( 'Bedrijfsgegevens', 'hds' ),
		'description' => __( 'Globale bedrijfsinformatie — wordt gebruikt in footer, contactpagina, gestructureerde data en Google Business Profile.', 'hds' ),
		'fields'      => [
			'hds_address'       => [ 'type' => 'text',     'label' => __( 'Adres (straat + huisnummer)', 'hds' ) ],
			'hds_postal_city'   => [ 'type' => 'text',     'label' => __( 'Postcode en plaats', 'hds' ) ],
			'hds_phone'         => [ 'type' => 'text',     'label' => __( 'Telefoonnummer', 'hds' ), 'default' => '0164-652846' ],
			'hds_email'         => [ 'type' => 'text',     'label' => __( 'E-mailadres', 'hds' ), 'default' => 'info@helderduidelijkschoon.nl' ],
			'hds_kvk'           => [ 'type' => 'text',     'label' => __( 'KVK-nummer', 'hds' ) ],
			'hds_btw'           => [ 'type' => 'text',     'label' => __( 'BTW-nummer', 'hds' ) ],
			'hds_facebook_url'  => [ 'type' => 'url',      'label' => __( 'Facebook URL', 'hds' ) ],
			'hds_instagram_url' => [ 'type' => 'url',      'label' => __( 'Instagram URL', 'hds' ) ],
			'hds_gbp_url'       => [ 'type' => 'url',      'label' => __( 'Google Business Profile URL', 'hds' ) ],
			'hds_opening_hours' => [ 'type' => 'textarea', 'label' => __( 'Openingstijden', 'hds' ) ],
		],
	];
}

/**
 * SEO Fields Content Model.
 *
 * Managed exclusively by Rank Math Pro (ADR D-003).
 * No custom fields needed — Rank Math adds its own meta boxes.
 *
 * Per-page SEO fields:
 *   - Focus keyphrase
 *   - SEO title template
 *   - Meta description
 *   - Open Graph title/description/image
 *   - Twitter card data
 *   - Schema markup type
 *   - Redirect URL (301/302/410)
 *   - Canonical URL override
 *   - noindex/nofollow toggles
 */
function hds_get_seo_model_note(): string {
	return __( 'SEO velden worden beheerd door Rank Math Pro. Ga naar Rank Math → Titles & Meta voor globale instellingen of gebruik het Rank Math SEO metabox per pagina.', 'hds' );
}

/**
 * Team Members Content Model.
 *
 * Not implemented as a CPT. The DHG does not specify a team members post type.
 * If needed post-launch, this model would use a CPT with block template + post meta
 * (name, role, photo, bio, social links).
 */
function hds_get_team_model_placeholder(): array {
	return [
		'status'  => 'deferred',
		'reason'  => __( 'Geen team/medewerkers sectie gespecificeerd in de huidige scope.', 'hds' ),
		'cpt'     => 'hds_team',
		'fields'  => [ 'role', 'photo', 'bio', 'email', 'phone', 'linkedin' ],
	];
}
