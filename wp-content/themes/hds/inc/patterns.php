<?php
/**
 * Block pattern registration.
 *
 * @package HDS
 */

/**
 * Register all block patterns.
 */
function hds_register_block_patterns(): void {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	// CTA Banner
	register_block_pattern(
		'hds/cta-banner',
		[
			'title'       => __( 'CTA Banner', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","bottom":"var:preset|spacing|16","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}},"color":{"background":"#1a73e8"}},"className":"is-style-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-banner has-background" style="background-color:#1a73e8;padding-top:var(--wp--preset--spacing--16);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--16);padding-left:var(--wp--preset--spacing--4)">
<!-- wp:heading {"textAlign":"center","level":2,"textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Wilt u een vrijblijvende offerte? Wij denken graag met u mee.</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"textColor":"primary","backgroundColor":"white","className":"is-style-cta"} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link has-primary-color has-white-background-color has-text-color has-background wp-element-button" href="' . esc_url( home_url( '/offerte-aanvragen/' ) ) . '">Vrijblijvende offerte aanvragen</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
		]
	);

	// Hero Section
	register_block_pattern(
		'hds/hero-section',
		[
			'title'       => __( 'Hero Section', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}}},"className":"hero-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group hero-section" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--4)">
<!-- wp:heading {"anchor":"hero-heading","level":1,"textAlign":"center"} -->
<h1 class="wp-block-heading has-text-align-center" id="hero-heading">Helder en Duidelijk voor het Schoonste resultaat!</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Uw betrouwbare partner voor professionele schoonmaak- en onderhoudsdiensten in West-Brabant en Zeeland.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-cta"} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="' . esc_url( home_url( '/offerte-aanvragen/' ) ) . '">Vrijblijvende offerte</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
		]
	);

	// USP Grid
	register_block_pattern(
		'hds/usp-grid',
		[
			'title'       => __( 'USP Grid', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","bottom":"var:preset|spacing|16"}}},"className":"usp-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group usp-grid" style="padding-top:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16)">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Waarom HDS?</h2>
<!-- /wp:heading -->
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:html -->
<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M84 80c0-24.3 19.7-44 44-44s44 19.7 44 44" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M40 208c0-48.6 39.4-88 88-88s88 39.4 88 88" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Vast opgeleid personeel</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Onze medewerkers zijn in vaste dienst en volledig opgeleid.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:html -->
<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M216 112c0 50.2-41.8 92-88 104-46.2-12-88-53.8-88-104V56l88-32 88 32v56Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="88 136 112 160 168 104" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Veiligheid &amp; Certificering</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>OSB-gecertificeerd. Wij werken volgens de hoogste veiligheidsnormen.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:html -->
<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M87.8 69.6c-13.2-14.8-32.4-23.2-54.2-22.3C14.8 48.5 0 64.4 0 83.3v89.4c0 18.9 14.8 34.8 33.6 36.1 21.8 1 41-7.4 54.2-22.3L122 152h12l34.2 34.6c13.2 14.8 32.4 23.2 54.2 22.3 18.9-1.3 33.6-17.2 33.6-36.1V83.3c0-18.9-14.8-34.8-33.6-36.1-21.8-1-41 7.4-54.2 22.3L134 104h-12L87.8 69.6Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>
<!-- /wp:html -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Een aanspreekpunt</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>U heeft altijd één vast aanspreekpunt voor al uw vragen.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
		]
	);

	// Content with Image
	register_block_pattern(
		'hds/content-with-image',
		[
			'title'       => __( 'Content with Image', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12"}}}} -->
<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--12);padding-bottom:var(--wp--preset--spacing--12)">
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="" alt=""/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%">
<!-- wp:heading -->
<h2 class="wp-block-heading">Koptekst</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Inhoud tekst hier...</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->',
		]
	);

	// Cross-Sell Services
	register_block_pattern(
		'hds/cross-sell-services',
		[
			'title'       => __( 'Cross-Sell Services', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"className":"cross-sell-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|16","bottom":"var:preset|spacing|16"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group cross-sell-section" style="padding-top:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16)">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Gerelateerde diensten</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Ontdek ook onze andere diensten die voor u interessant kunnen zijn.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);

	// Contact Info Block
	register_block_pattern(
		'hds/contact-info-block',
		[
			'title'       => __( 'Contact Info Block', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"className":"contact-info-block","layout":{"type":"constrained"}} -->
<div class="wp-block-group contact-info-block">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Contactgegevens</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><strong>Telefoon:</strong> <a href="tel:0164-652846">0164-652846</a></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>E-mail:</strong> <a href="mailto:info@helderduidelijkschoon.nl">info@helderduidelijkschoon.nl</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);

	// 404 Content
	register_block_pattern(
		'hds/404-content',
		[
			'title'       => __( '404 Content', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|4","right":"var:preset|spacing|4"}}},"className":"error-404-content","layout":{"type":"constrained"}} -->
<div class="wp-block-group error-404-content" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--4);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--4)">
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Pagina niet gevonden</h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>De pagina die u zoekt bestaat niet of is verplaatst.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);

	// FAQ Starter
	register_block_pattern(
		'hds/faq-starter',
		[
			'title'       => __( 'FAQ Starter', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
<!-- wp:paragraph -->
<p>Hieronder vindt u antwoorden op veelgestelde vragen. Heeft u een vraag die er niet bij staat? Neem dan gerust <a href="/contact/">contact</a> met ons op.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);

	// Service Card Grid
	register_block_pattern(
		'hds/service-card-grid',
		[
			'title'       => __( 'Service Card Grid', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|12","bottom":"var:preset|spacing|12"}}},"className":"service-card-grid-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group service-card-grid-section" style="padding-top:var(--wp--preset--spacing--12);padding-bottom:var(--wp--preset--spacing--12)">
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Onze diensten</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Professionele schoonmaak- en onderhoudsdiensten voor uw bedrijf.</p>
<!-- /wp:paragraph -->
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="/glasbewassing/">Glasbewassing</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Professionele glasbewassing voor kantoren, winkels en bedrijfspanden.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="/gevelreiniging/">Gevelreiniging</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Reiniging en onderhoud van gevels, daken en reclameborden.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"className":"is-style-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-card" style="padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><a href="/reguliere-schoonmaak/">Reguliere Schoonmaak</a></h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Periodieke schoonmaak voor kantoren en bedrijfsruimtes.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->',
		]
	);

	// Referenties Page
	register_block_pattern(
		'hds/referenties-page',
		[
			'title'       => __( 'Referenties Pagina', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"className":"referenties-page","layout":{"type":"constrained"}} -->
<div class="wp-block-group referenties-page" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
<!-- wp:heading -->
<h2 class="wp-block-heading">Wat onze klanten zeggen</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Wij zijn trots op de samenwerking met onze klanten. Hieronder leest u wat zij over HDS Onderhoudsdiensten zeggen.</p>
<!-- /wp:paragraph -->
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"className":"client-logo-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group client-logo-grid" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Onze klanten</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Logo\'s en namen worden hier geplaatst. Gebruik de Mediagalerij om logo\'s toe te voegen.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph -->
<p>Wilt u ook uw ervaring delen? Wij horen graag van u.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);

	// Downloads Card List
	register_block_pattern(
		'hds/downloads-card-list',
		[
			'title'       => __( 'Downloads Kaartenlijst', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"className":"downloads-list","layout":{"type":"constrained"}} -->
<div class="wp-block-group downloads-list" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
<!-- wp:group {"className":"download-card","style":{"spacing":{"padding":{"top":"var:preset|spacing|6","bottom":"var:preset|spacing|6","left":"var:preset|spacing|6","right":"var:preset|spacing|6"}},"border":{"width":"1px"}},"borderColor":"light-gray","backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group download-card has-border-color has-light-gray-border-color has-background has-white-background-color" style="border-width:1px;padding-top:var(--wp--preset--spacing--6);padding-bottom:var(--wp--preset--spacing--6);padding-left:var(--wp--preset--spacing--6);padding-right:var(--wp--preset--spacing--6)">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Documentnaam</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Beschrijving van het document. Bestandstype en grootte toevoegen.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-secondary"} -->
<div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button" href="#">Download</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		]
	);

	// Vacancy Page Intro
	register_block_pattern(
		'hds/vacancy-intro',
		[
			'title'       => __( 'Vacature Pagina Intro', 'hds' ),
			'categories'  => [ 'hds-patterns' ],
			'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|8","bottom":"var:preset|spacing|8"}}},"className":"vacancy-intro","layout":{"type":"constrained"}} -->
<div class="wp-block-group vacancy-intro" style="padding-top:var(--wp--preset--spacing--8);padding-bottom:var(--wp--preset--spacing--8)">
<!-- wp:heading -->
<h2 class="wp-block-heading">Werken bij HDS</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>HDS Onderhoudsdiensten is een groeiend schoonmaakbedrijf in West-Brabant. Wij zoeken gemotiveerde collega\'s die kwaliteit, veiligheid en klantgerichtheid belangrijk vinden. Bekijk hieronder onze openstaande vacatures.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		]
	);
}
add_action( 'init', 'hds_register_block_patterns' );
