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
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Kantoor schoonmaak op maat', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe professionele kantoor schoonmaak bijdraagt aan een representatieve, gezonde en productieve werkomgeving.', 'hds' ),
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
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele glasbewassing voor bedrijven', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe professionele glasbewassing bijdraagt aan een representatieve uitstraling van uw bedrijfspand.', 'hds' ),
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
			'title'           => __( 'Gevelreiniging', 'hds' ),
			'subtitle'        => __( 'Professionele gevelreiniging voor alle typen gevels en materialen.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Gevelreiniging', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele gevelreiniging voor bedrijfspanden', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe professionele gevelreiniging bijdraagt aan een verzorgde uitstraling en het behoud van uw vastgoed.', 'hds' ),
				'paragraphs'  => [
					__( 'Een schone gevel bepaalt het visitekaartje van uw bedrijfspand. Met onze professionele gevelreiniging verwijderen wij vervuiling, aanslag, graffiti en weersinvloeden van iedere gevel — ongeacht het materiaal.', 'hds' ),
					__( 'Wij werken met gespecialiseerde reinigingstechnieken per geveltype, van chemische reiniging tot stoom- en hogedrukreiniging. Ons gecertificeerde team voert iedere opdracht veilig en efficiënt uit, van laagbouw tot hoogbouw.', 'hds' ),
				],
				'benefits'    => [
					__( 'Reiniging van alle geveltypes', 'hds' ),
					__( 'Flexibele planning', 'hds' ),
					__( 'Gecertificeerde hoogwerkers', 'hds' ),
					__( 'Milieuvriendelijke reinigingsmethoden', 'hds' ),
					__( 'Veilig gecertificeerd personeel', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [
				[
					'q' => __( 'Welke soorten gevels en materialen kunnen jullie reinigen?', 'hds' ),
					'a' => __( 'Wij reinigen alle typen gevels en materialen — van baksteen, beton en natuursteen tot metaal, kunststof en hout. Iedere gevel vraagt om een eigen aanpak en wij stemmen de reinigingstechniek daar volledig op af.', 'hds' ),
				],
				[
					'q' => __( 'Welke reinigingstechniek gebruiken jullie?', 'hds' ),
					'a' => __( 'Dit is afhankelijk van het geveltype en de aard van de vervuiling. Wij werken met gespecialiseerde technieken per geveltype, van chemische reiniging tot stoom- en hogedrukreiniging.', 'hds' ),
				],
				[
					'q' => __( 'Hoe bepalen jullie de juiste reinigingsmethode voor mijn gevel?', 'hds' ),
					'a' => __( 'Wij inspecteren vooraf het geveltype, de mate van vervuiling en eventuele beschadigingen. Op basis daarvan kiezen wij de meest geschikte en veilige reinigingsmethode, zodat het materiaal niet wordt aangetast.', 'hds' ),
				],
				[
					'q' => __( 'Hoe werken jullie veilig bij gevels op hoogte?', 'hds' ),
					'a' => __( 'Veiligheid staat bij ons voorop. Wij beschikken over gecertificeerde hoogwerkers en ons personeel is volledig opgeleid en gecertificeerd. Van laagbouw tot hoogbouw voeren wij iedere opdracht veilig en efficiënt uit.', 'hds' ),
				],
				[
					'q' => __( 'Hoe vaak is gevelreiniging nodig?', 'hds' ),
					'a' => __( 'Dit is afhankelijk van de ligging van uw pand, omgevingsfactoren zoals verkeer of industrie, en de gewenste uitstraling. Tijdens de kennismaking adviseren wij u over de optimale frequentie voor uw situatie.', 'hds' ),
				],
			],
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
			'title'           => __( 'Vloeronderhoud', 'hds' ),
			'subtitle'        => __( 'Vakkundig vloeronderhoud voor een duurzame en representatieve uitstraling.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Vloeronderhoud', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professioneel vloeronderhoud voor bedrijven', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe professioneel vloeronderhoud bijdraagt aan een duurzame en representatieve uitstraling van uw bedrijfspand.', 'hds' ),
				'paragraphs'  => [
					__( 'Een goed onderhouden vloer verlengt de levensduur, voorkomt slijtage en draagt bij aan een verzorgde uitstraling. Met ons professionele vloeronderhoud houden wij iedere vloer in topconditie — van dagelijks stofzuigen en dweilen tot periodieke dieptereiniging.', 'hds' ),
					__( 'Wij werken met gespecialiseerde reinigingsmethoden per vloertype, van tapijt en PVC tot natuursteen en parket. Ons ervaren team stemt het onderhoud volledig af op het materiaal, de gebruiksintensiteit en uw wensen.', 'hds' ),
				],
				'benefits'    => [
					__( 'Onderhoud van alle vloertypes', 'hds' ),
					__( 'Dagelijks en periodiek onderhoud', 'hds' ),
					__( 'Verlengde levensduur van uw vloer', 'hds' ),
					__( 'Gespecialiseerde reinigingsmethoden', 'hds' ),
					__( 'Flexibele planning', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [
				[
					'q' => __( 'Welke soorten vloeren kunnen jullie onderhouden?', 'hds' ),
					'a' => __( 'Wij onderhouden alle vloertypes — van tapijt, PVC, linoleum en laminaat tot natuursteen, parket en beton. Ieder vloertype vraagt om een eigen aanpak en wij stemmen de reinigingsmethode daar volledig op af.', 'hds' ),
				],
				[
					'q' => __( 'Welke onderhoudsmethoden gebruiken jullie?', 'hds' ),
					'a' => __( 'Dit is afhankelijk van het vloertype en de gebruiksintensiteit. Onze werkzaamheden variëren van dagelijks stofzuigen en dweilen tot periodieke dieptereiniging met gespecialiseerde machines en reinigingsmiddelen per materiaalsoort.', 'hds' ),
				],
				[
					'q' => __( 'Hoe vaak is vloeronderhoud nodig?', 'hds' ),
					'a' => __( 'De frequentie hangt af van het vloertype, de loopintensiteit en de gewenste uitstraling. Wij adviseren een combinatie van dagelijks of wekelijks onderhoud met periodieke dieptereiniging. Tijdens de kennismaking stellen wij een plan op maat voor.', 'hds' ),
				],
				[
					'q' => __( 'Hoe beschermen jullie vloeren tijdens het gebruik?', 'hds' ),
					'a' => __( 'Naast reiniging bieden wij beschermende behandelingen aan zoals impregneren, coaten en het aanbrengen van een beschermlaag. Deze behandelingen verlengen de levensduur van uw vloer aanzienlijk en maken dagelijks onderhoud eenvoudiger.', 'hds' ),
				],
				[
					'q' => __( 'Is vloeronderhoud geschikt voor een bedrijfsomgeving?', 'hds' ),
					'a' => __( 'Ja, wij zijn gespecialiseerd in vloeronderhoud voor bedrijfspanden. Onze werkzaamheden worden afgestemd op de openingstijden van uw organisatie, zodat u geen hinder ondervindt van het onderhoud.', 'hds' ),
				],
			],
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
			'title'           => __( 'Oplevering schoonmaak', 'hds' ),
			'subtitle'        => __( 'Grondige opleverschoonmaak voor een vlekkeloze eindoplevering.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Opleverschoonmaak', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele opleverschoonmaak voor een vlekkeloze oplevering', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe onze grondige opleverschoonmaak zorgt voor een vlekkeloze eindoplevering van uw project.', 'hds' ),
				'paragraphs'  => [
					__( 'Na een bouwproject, verbouwing of renovatie blijft er vaak veel stof, vuil en bouwresidu achter. Onze opleverschoonmaak verwijdert al deze resten grondig, zodat uw pand er piekfijn uitziet bij de eindoplevering. Van plafond tot plint — wij laten geen enkel detail ongemoeid.', 'hds' ),
					__( 'Wij stemmen de opleverschoonmaak volledig af op uw projectplanning en de aard van het pand. Of het nu gaat om een kantoorpand, winkelruimte, zorginstelling of school — ons ervaren team zorgt voor een strak en schoon resultaat, precies op het juiste moment vóór de sleuteloverdracht.', 'hds' ),
				],
				'benefits'    => [
					__( 'Reiniging na bouw, verbouwing en renovatie', 'hds' ),
					__( 'Flexibele planning', 'hds' ),
					__( 'Volledige opleveringsreiniging', 'hds' ),
					__( 'Aandacht voor ieder detail', 'hds' ),
					__( 'Afgestemd op uw opleverschema', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Verwijderen van bouwstof, cementresten, kit- en verfspatten van alle oppervlakken', 'hds' ) ],
				[ 'text' => __( 'Reinigen en stofvrij maken van vloeren, plinten, kozijnen, deuren en vensterbanken', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing van alle ramen, zowel binnen- als buitenzijde', 'hds' ) ],
				[ 'text' => __( 'Reinigen van keukenblokken, sanitair, tegels en voegen in natte ruimtes', 'hds' ) ],
				[ 'text' => __( 'Schoonmaken van radiatoren, ventilatieroosters, stopcontacten en lichtschakelaars', 'hds' ) ],
				[ 'text' => __( 'Nazorg en eventuele correctierondes voor een perfecte eindoplevering', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'zorginstellingen', 'scholen', 'retail', 'overheid', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Wanneer wordt een opleverschoonmaak uitgevoerd?', 'hds' ),
					'a' => __( 'Een opleverschoonmaak wordt uitgevoerd na afronding van bouw-, verbouwings- of renovatiewerkzaamheden, vlak voor de sleuteloverdracht. Dit kan ook na een verhuizing of bij de eindoplevering van een nieuw pand. Wij plannen de schoonmaak zo in dat het pand perfect schoon is op het moment van overdracht aan de eigenaar of huurder.', 'hds' ),
				],
				[
					'q' => __( 'Welke soorten panden kunnen jullie opleverschoon maken?', 'hds' ),
					'a' => __( 'Wij verzorgen opleverschoonmaak voor alle typen bedrijfspanden, waaronder kantoren, winkelruimtes, zorginstellingen, scholen, overheidsgebouwen en bedrijfsverzamelgebouwen. Ook nieuwbouw- en renovatieprojecten van iedere omvang behoren tot onze expertise.', 'hds' ),
				],
				[
					'q' => __( 'Wat houdt de opleverschoonmaak precies in?', 'hds' ),
					'a' => __( 'Onze opleverschoonmaak omvat het verwijderen van bouwstof, cementresten, kit- en verfspatten, het reinigen van vloeren en oppervlakken, glasbewassing binnen en buiten, schoonmaken van sanitair en keukenblokken, en het stofvrij maken van radiatoren, ventilatieroosters en elektrapunten. Wij leveren een volledig schoon en gebruiksklaar pand op.', 'hds' ),
				],
				[
					'q' => __( 'Hoe plannen jullie de opleverschoonmaak voor de sleuteloverdracht?', 'hds' ),
					'a' => __( 'Wij stemmen de planning volledig af op uw project en de datum van sleuteloverdracht. Idealiter plannen wij de schoonmaak één tot twee dagen vóór de eindinspectie, zodat eventuele correcties nog kunnen worden uitgevoerd. Onze flexibele planning zorgt ervoor dat u nooit voor verrassingen komt te staan.', 'hds' ),
				],
				[
					'q' => __( 'Hoe verloopt de afstemming met de aannemer of projectleider?', 'hds' ),
					'a' => __( 'Wij plannen de werkzaamheden in nauw overleg met de aannemer, projectleider of opdrachtgever. Wij houden rekening met de bouwplanning, de datum van oplevering en eventuele laatste werkzaamheden op de bouwplaats. Indien gewenst kunnen wij ook buiten reguliere werktijden schoonmaken om de planning niet te verstoren.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Oplevering schoonmaak | Professionele opleverschoonmaak na bouw en renovatie | HDS', 'hds' ),
			'seo_description' => __( 'Professionele opleverschoonmaak door HDS. Grondige reiniging na bouw, verbouwing en renovatie voor een vlekkeloze eindoplevering. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'industriele-schoonmaak' => [
			'title'           => __( 'Industriële schoonmaak', 'hds' ),
			'subtitle'        => __( 'Gespecialiseerde industriële reiniging voor productieomgevingen.', 'hds' ),
			'hero_image'      => 0,
			'eyebrow'         => __( 'Industriële reiniging', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele industriële reiniging voor productieomgevingen', 'hds' ),
				'intro_text'  => __( 'Ontdek hoe gespecialiseerde industriële reiniging bijdraagt aan een veilige, schone en efficiënte productieomgeving.', 'hds' ),
				'paragraphs'  => [
					__( 'In een industriële omgeving is reiniging meer dan alleen schoonmaken — het draagt bij aan veiligheid, productkwaliteit en de levensduur van machines en installaties. Met onze industriële reiniging pakken wij ieder type vervuiling aan, van stof en olie tot productieresten.', 'hds' ),
					__( 'Wij stemmen de reinigingsmethode volledig af op uw productieomgeving, de aard van de vervuiling en de geldende veiligheidsvoorschriften. Ons ervaren team werkt efficiënt en veilig, met minimale verstoring van uw bedrijfsprocessen.', 'hds' ),
				],
				'benefits'    => [
					__( 'Reiniging van productieruimtes en installaties', 'hds' ),
					__( 'Flexibele planning', 'hds' ),
					__( 'Veilig werken volgens voorschriften', 'hds' ),
					__( 'Gespecialiseerde reinigingsmethoden', 'hds' ),
					__( 'Minimale verstoring van productie', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [],
			'industries'      => [],
			'faq'             => [
				[
					'q' => __( 'Welke industriële ruimtes en installaties kunnen jullie reinigen?', 'hds' ),
					'a' => __( 'Wij reinigen productieruimtes, magazijnen, werkplaatsen, machinekamers en technische installaties. Iedere ruimte en installatie vraagt om een eigen aanpak en wij stemmen de reinigingsmethode daar volledig op af.', 'hds' ),
				],
				[
					'q' => __( 'Hoe pakken jullie industriële vervuiling aan?', 'hds' ),
					'a' => __( 'Dit is afhankelijk van de aard van de vervuiling — van stof en olie tot vet en productieresten. Wij werken met gespecialiseerde reinigingsmethoden die zijn afgestemd op het type vervuiling en de te reinigen ondergrond.', 'hds' ),
				],
				[
					'q' => __( 'Hoe vaak is industriële reiniging nodig?', 'hds' ),
					'a' => __( 'De frequentie hangt af van het type productieomgeving, de aard en mate van vervuiling en de geldende voorschriften. Wij adviseren een planning op maat — van dagelijkse reiniging tot periodiek onderhoud. Tijdens de kennismaking stellen wij een schema voor.', 'hds' ),
				],
				[
					'q' => __( 'Hoe zorgen jullie voor veiligheid tijdens de werkzaamheden?', 'hds' ),
					'a' => __( 'Veiligheid staat bij ons voorop. Ons personeel is volledig opgeleid en werkt volgens de geldende veiligheidsvoorschriften van uw bedrijf. Wij stemmen de werkwijze vooraf af op de risico\'s van uw productieomgeving.', 'hds' ),
				],
				[
					'q' => __( 'Kunnen de werkzaamheden worden uitgevoerd zonder de productie te verstoren?', 'hds' ),
					'a' => __( 'Ja, wij plannen de werkzaamheden in overleg met u, bij voorkeur buiten productietijden — zoals in de avond, nacht of het weekend. Zo ondervindt uw productieproces minimale hinder van onze werkzaamheden.', 'hds' ),
				],
			],
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
