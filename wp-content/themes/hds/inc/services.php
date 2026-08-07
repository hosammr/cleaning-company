<?php
/**
 * Service data — single source of truth for all cleaning service pages.
 *
 * Each entry contains structured data consumed by the unified service
 * template (page-templates/page-service.php). Adding a new service
 * requires only an entry in hds_get_services().
 *
 * @package HDS
 */

/**
 * Return all cleaning service definitions, keyed by page slug.
 *
 * Each service shape:
 *   title           — H1 and meta title
 *   subtitle        — hero subtitle paragraph
 *   hero_image      — media attachment ID (0 = none)
 *   eyebrow         — optional hero eyebrow label
 *   intro           — array { title, paragraphs[], benefits[] }
 *   checklist       — array of { text: string } for the "What's included" section
 *   industries      — string[] of industry slugs (see hds_get_industry_data)
 *   faq             — array of { q: string, a: string }
 *   seo_title       — <title> override
 *   seo_description — meta description override
 *
 * @return array<string, array>
 */
function hds_get_services(): array {
	return [
		'kantoor-schoonmaak' => [
			'title'           => __( 'Kantoor schoonmaak', 'hds' ),
			'subtitle'        => __( 'Professionele kantoorreiniging op maat voor een schone, gezonde en representatieve werkomgeving.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Kantoorreiniging', 'hds' ),
			'intro'           => [
				'title'       => __( 'Kantoor schoonmaak op maat', 'hds' ),
				'paragraphs'  => [
					__( 'Een schoon kantoor draagt bij aan de productiviteit, gezondheid en uitstraling van uw organisatie. Met onze professionele kantoorreiniging creëren wij een frisse werkomgeving waarin medewerkers prettig werken en klanten een positieve indruk krijgen.', 'hds' ),
					__( 'Wij stemmen de schoonmaak volledig af op uw wensen, werktijden en de aard van uw bedrijf. Van dagelijkse reiniging tot periodiek onderhoud — u bepaalt de frequentie en wij leveren de kwaliteit.', 'hds' ),
				],
				'benefits'    => hds_get_default_intro_benefits(),
			],
			'checklist'       => [
				[ 'text' => __( 'Dagelijkse schoonmaak van alle kantoorruimtes, vergaderzalen en werkplekken', 'hds' ) ],
				[ 'text' => __( 'Sanitaire voorzieningen reinigen, desinfecteren en aanvullen van verbruiksartikelen', 'hds' ) ],
				[ 'text' => __( 'Vloeronderhoud: stofzuigen, dweilen en periodieke dieptereiniging van alle vloertypes', 'hds' ) ],
				[ 'text' => __( 'Afvalbeheer en recycling volgens de geldende scheidingsrichtlijnen', 'hds' ) ],
				[ 'text' => __( 'Keuken- en pantryreiniging inclusief apparatuur en sanitair', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing binnenzijde van ramen, deuren en glazen tussenwanden', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'zorginstellingen', 'scholen', 'retail', 'overheid', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Hoe vaak wordt mijn kantoor schoongemaakt?', 'hds' ),
					'a' => __( 'De frequentie stemmen wij volledig af op uw wensen en de aard van uw bedrijf. Dit kan variëren van dagelijks tot wekelijks. Tijdens de kennismaking adviseren wij u over de optimale planning.', 'hds' ),
				],
				[
					'q' => __( 'Werken jullie ook buiten kantoortijden?', 'hds' ),
					'a' => __( 'Ja, wij kunnen alle werkzaamheden uitvoeren buiten uw openingstijden — vroeg in de ochtend, ’s avonds of in het weekend. Zo ondervindt u geen hinder tijdens uw bedrijfsvoering.', 'hds' ),
				],
				[
					'q' => __( 'Welke schoonmaakmiddelen gebruiken jullie?', 'hds' ),
					'a' => __( 'Wij werken uitsluitend met professionele schoonmaakmiddelen van A-merken. Waar mogelijk kiezen wij voor milieuvriendelijke en biologisch afbreekbare producten zonder in te leveren op resultaat.', 'hds' ),
				],
				[
					'q' => __( 'Zijn jullie medewerkers gescreend?', 'hds' ),
					'a' => __( 'Ja, al onze schoonmaakmedewerkers zijn in vaste dienst, volledig opgeleid en beschikken over een VOG-verklaring (Verklaring Omtrent Gedrag). Uw veiligheid en vertrouwen staan voorop.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een proefperiode afspreken?', 'hds' ),
					'a' => __( 'Zeker. Wij starten graag met een proefperiode waarin u onze kwaliteit zelf ervaart. Daarna evalueren we samen en sturen we bij waar nodig. U zit nergens aan vast.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Kantoor schoonmaak | Professionele kantoorschoonmaak op maat | HDS', 'hds' ),
			'seo_description' => __( 'Professionele kantoorreiniging door HDS. Vaste schoonmaakmedewerkers, flexibele planning buiten kantoortijden en een schone werkomgeving. Vraag een vrijblijvende offerte aan.', 'hds' ),
		],

		'glasbewassing' => [
			'title'           => __( 'Glasbewassing', 'hds' ),
			'subtitle'        => __( 'Professionele glasbewassing voor bedrijfspanden, kantoren en commercieel vastgoed. Strak resultaat, veilig uitgevoerd.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Glasbewassing', 'hds' ),
			'intro'           => [
				'title'       => __( 'Professionele glasbewassing voor bedrijven', 'hds' ),
				'paragraphs'  => [
					__( 'Schone ramen en glazen puien bepalen de eerste indruk van uw pand. Met onze professionele glasbewassing zorgen wij voor streeploos schone ramen, binnen en buiten, tot elke hoogte.', 'hds' ),
					__( 'Wij werken met moderne osmosewater-technologie en gecertificeerde hoogwerkers. Of het nu gaat om periodieke glasbewassing of een eenmalige reiniging — wij leveren altijd een strak en veilig resultaat.', 'hds' ),
				],
				'benefits'    => [
					__( 'Binnen- en buitenglasbewassing', 'hds' ),
					__( 'Flexibele planning', 'hds' ),
					__( 'Gecertificeerde hoogwerkers', 'hds' ),
					__( 'Osmosewater-technologie', 'hds' ),
					__( 'Veilig gecertificeerd personeel', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Binnen- en buitenglasbewassing van ramen, puien en glazen gevels tot elke hoogte', 'hds' ) ],
				[ 'text' => __( 'Reinigen van zonwering, lamellen en screens met behoud van materiaalkwaliteit', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing van lichtstraten, koepels en dakramen voor optimale lichtinval', 'hds' ) ],
				[ 'text' => __( 'Onderhoud van kozijnen, vensterbanken en hang- en sluitwerk tijdens de glasronde', 'hds' ) ],
				[ 'text' => __( 'Gecertificeerde hoogwerkers en klimmaterieel voor veilige reiniging op hoogte', 'hds' ) ],
				[ 'text' => __( 'Periodieke glasbewassing volgens een vast schema dat aansluit op uw planning', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'retail', 'zorginstellingen', 'overheid', 'scholen', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Hoe vaak moet glasbewassing plaatsvinden?', 'hds' ),
					'a' => __( 'Voor de meeste bedrijfspanden adviseren wij een interval van 4 tot 8 weken. De ideale frequentie is afhankelijk van de ligging, omgevingsfactoren en de gewenste uitstraling van uw pand. Wij stellen graag een schema op maat voor.', 'hds' ),
				],
				[
					'q' => __( 'Werken jullie ook op hoogte?', 'hds' ),
					'a' => __( 'Ja, wij beschikken over gecertificeerde hoogwerkers, telescopische waterpole-systemen en geschoold personeel met de vereiste veiligheidscertificeringen. Ook gevels tot meerdere verdiepingen reinigen wij volledig veilig en efficiënt.', 'hds' ),
				],
				[
					'q' => __( 'Welke methode gebruiken jullie voor glasbewassing?', 'hds' ),
					'a' => __( 'Wij combineren traditionele glasbewassing met osmosewater-technologie voor een streeploos resultaat. Deze methode is milieuvriendelijk omdat er geen reinigingsmiddelen aan het water worden toegevoegd.', 'hds' ),
				],
				[
					'q' => __( 'Zijn jullie verzekerd bij schade tijdens werkzaamheden?', 'hds' ),
					'a' => __( 'Ja, HDS is volledig verzekerd. Onze bedrijfsaansprakelijkheidsverzekering dekt eventuele schade die tijdens de werkzaamheden ontstaat. U loopt als opdrachtgever geen enkel risico.', 'hds' ),
				],
				[
					'q' => __( 'Kunnen jullie ook incidenteel glasbewassing uitvoeren?', 'hds' ),
					'a' => __( 'Absoluut. Naast periodiek onderhoud bieden wij ook eenmalige glasbewassing aan — bijvoorbeeld na een verbouwing, oplevering of voor een speciale gelegenheid. Neem contact op voor de mogelijkheden.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Glasbewassing | Professionele glazenwasser voor bedrijven | HDS', 'hds' ),
			'seo_description' => __( 'Professionele glasbewassing voor bedrijfspanden door HDS. Binnen- en buitenglas, hoogbouw, osmosewater reiniging en periodiek onderhoud. Vrijblijvende offerte.', 'hds' ),
		],

		'gevelreiniging' => [
			'title'    => __( 'Gevelreiniging', 'hds' ),
			'subtitle' => __( 'Professionele gevelreiniging voor alle typen gevels en materialen.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],

		'reguliere-schoonmaak' => [
			'title'    => __( 'Reguliere schoonmaak', 'hds' ),
			'subtitle' => __( 'Betrouwbare reguliere schoonmaak op maat voor uw bedrijf.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],

		'vloeronderhoud' => [
			'title'    => __( 'Vloeronderhoud', 'hds' ),
			'subtitle' => __( 'Vakkundig vloeronderhoud voor een duurzame en representatieve uitstraling.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],

		'vve-service' => [
			'title'    => __( 'VvE service', 'hds' ),
			'subtitle' => __( 'Complete schoonmaakdiensten voor Verenigingen van Eigenaren.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],

		'oplevering-schoonmaak' => [
			'title'    => __( 'Oplevering schoonmaak', 'hds' ),
			'subtitle' => __( 'Grondige opleverschoonmaak voor een vlekkeloze eindoplevering.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],

		'industriele-schoonmaak' => [
			'title'    => __( 'Industriële schoonmaak', 'hds' ),
			'subtitle' => __( 'Gespecialiseerde industriële reiniging voor productieomgevingen.', 'hds' ),
			'hero_image'      => 0,
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [],
			'seo_title'       => '',
			'seo_description' => '',
		],
	];
}

/**
 * Default intro benefits used when a service does not define its own.
 *
 * @return string[]
 */
function hds_get_default_intro_benefits(): array {
	return [
		__( 'Dagelijkse of periodieke schoonmaak', 'hds' ),
		__( 'Flexibele werktijden', 'hds' ),
		__( 'Vaste schoonmaakteams', 'hds' ),
		__( 'Milieuvriendelijke producten', 'hds' ),
		__( 'Kwaliteitscontrole', 'hds' ),
		__( 'Vrijblijvende offerte', 'hds' ),
	];
}

/**
 * Look up a single service by its page slug.
 *
 * @param string $slug The page slug (e.g. 'glasbewassing').
 * @return array|null  The service data array, or null if not found.
 */
function hds_get_service( string $slug ): ?array {
	$services = hds_get_services();
	return $services[ $slug ] ?? null;
}
