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
 *   title              — H1 and meta title
 *   subtitle           — hero subtitle paragraph
 *   hero_image         — media attachment ID (0 = none)
 *   visual_break_image — media attachment ID for the Visual Break section (0 = none)
 *   eyebrow            — optional hero eyebrow label
 *   intro              — array { title, paragraphs[], benefits[] }
 *   checklist          — array of { text: string } for the "What's included" section
 *   industries         — string[] of industry slugs (see hds_get_industry_data)
 *   faq                — array of { q: string, a: string }
 *   seo_title          — <title> override
 *   seo_description    — meta description override
 *
 * Workflow and audience are resolved by the template: workflow falls back
 * to hds_get_default_workflow(), audience is derived from `industries`
 * via hds_get_industry_data().
 *
 * @return array<string, array>
 */
function hds_get_services(): array {
	return [
		'kantoor-schoonmaak' => [
			'title'           => __( 'Kantoorreiniging', 'hds' ),
			'subtitle'        => __( 'Professionele kantoorreiniging op maat voor een schone en representatieve werkomgeving.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 361,
			'eyebrow'         => __( 'Kantoorreiniging', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Kantoorreiniging op maat', 'hds' ),
				'intro_text'  => __( 'Een schone kantooromgeving draagt bij aan een prettige en verzorgde werkomgeving.', 'hds' ),
				'paragraphs'  => [
					__( 'Wij verzorgen de schoonmaak van kantoren en kantoorruimtes, zoals werkplekken, vergaderzalen en overige ruimtes binnen uw kantoor.', 'hds' ),
					__( 'De werkzaamheden stemmen wij af op uw wensen en het gebruik van de ruimtes. Zo blijft uw kantoor schoon en verzorgd voor medewerkers en bezoekers.', 'hds' ),
				],
				'benefits'    => [
					__( 'Schoonmaak van kantoorruimtes en werkplekken', 'hds' ),
					__( 'Schoonmaak van sanitaire ruimtes', 'hds' ),
					__( 'Reiniging van keukens en pantry\'s', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Schoonmaak van kantoorruimtes, vergaderzalen en werkplekken', 'hds' ) ],
				[ 'text' => __( 'Reiniging van sanitaire voorzieningen en het aanvullen van verbruiksartikelen', 'hds' ) ],
				[ 'text' => __( 'Stofzuigen en dweilen van vloeren', 'hds' ) ],
				[ 'text' => __( 'Afvalbeheer en het legen van prullenbakken', 'hds' ) ],
				[ 'text' => __( 'Reiniging van keukens en pantry\'s', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing van de binnenzijde van ramen, deuren en glazen tussenwanden', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'zorginstellingen', 'scholen', 'retail', 'overheid', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Welke ruimtes worden bij kantoorreiniging schoongemaakt?', 'hds' ),
					'a' => __( 'Wij reinigen kantoorruimtes, vergaderzalen, werkplekken, sanitaire voorzieningen en keukens of pantry\'s. De werkzaamheden stemmen wij af op uw wensen.', 'hds' ),
				],
				[
					'q' => __( 'Hoe bepalen jullie de planning?', 'hds' ),
					'a' => __( 'De planning stemmen wij af op uw wensen en het gebruik van de ruimtes. De werkzaamheden voeren wij uit volgens de afspraken die met u zijn gemaakt.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bespreken wij uw wensen en stellen wij een passend voorstel op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Kantoorreiniging | Schoonmaak van kantoren en werkplekken | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Kantoorreiniging door Hamdoun Schoonmaak. Wij reinigen kantoorruimtes, werkplekken, sanitaire ruimtes en keukens. Werkzaamheden afgestemd op uw wensen. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'glasbewassing' => [
			'title'           => __( 'Glasbewassing', 'hds' ),
			'subtitle'        => __( 'Professionele glasbewassing voor bedrijfspanden, kantoren en commercieel vastgoed. Strak resultaat, veilig uitgevoerd.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 362,
			'eyebrow'         => __( 'Glasbewassing', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Glasbewassing voor bedrijven en instellingen', 'hds' ),
				'intro_text'  => __( 'Schone ramen en glazen puien dragen bij aan een verzorgde uitstraling van uw pand.', 'hds' ),
				'paragraphs'  => [
					__( 'Wij verzorgen de glasbewassing van ramen, puien en andere glaspartijen, zowel de binnenzijde als de buitenzijde van uw pand.', 'hds' ),
					__( 'De werkzaamheden stemmen wij af op uw wensen en de mogelijkheden van de locatie. Wij maken graag duidelijke afspraken over de uitvoering.', 'hds' ),
				],
				'benefits'    => [
					__( 'Binnen- en buitenglasbewassing', 'hds' ),
					__( 'Reiniging van ramen, puien en glaspartijen', 'hds' ),
					__( 'Afgestemd op uw wensen', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Binnen- en buitenglasbewassing van ramen, puien en glaspartijen', 'hds' ) ],
				[ 'text' => __( 'Reinigen van zonwering, lamellen en screens', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing van lichtstraten, koepels en dakramen', 'hds' ) ],
				[ 'text' => __( 'Reinigen van kozijnen en vensterbanken tijdens de glasronde', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing volgens de afspraken die met u zijn gemaakt', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'retail', 'zorginstellingen', 'overheid', 'scholen', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Welke glaspartijen worden gereinigd?', 'hds' ),
					'a' => __( 'Wij reinigen ramen, puien en andere glaspartijen, zowel de binnenzijde als de buitenzijde van het pand.', 'hds' ),
				],
				[
					'q' => __( 'Hoe bepalen jullie de planning?', 'hds' ),
					'a' => __( 'De planning stemmen wij af op uw wensen en de mogelijkheden van de locatie. De werkzaamheden voeren wij uit volgens de afspraken die met u zijn gemaakt.', 'hds' ),
				],
				[
					'q' => __( 'Kunnen jullie ook eenmalig glasbewassing uitvoeren?', 'hds' ),
					'a' => __( 'Ja. Naast glasbewassing volgens een afgesproken schema kunnen wij ook eenmalig glasbewassing uitvoeren, bijvoorbeeld na een verbouwing of oplevering.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Glasbewassing voor bedrijven | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Glasbewassing door Hamdoun Schoonmaak. Wij reinigen ramen, puien en glaspartijen, binnen en buiten. Werkzaamheden afgestemd op uw wensen. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'gevelreiniging' => [
			'title'           => __( 'Gevelreiniging', 'hds' ),
			'subtitle'        => __( 'Gevelreiniging voor woningen en winkelpanden, gericht op laagbouw en goed bereikbare gevels.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 364,
			'eyebrow'         => __( 'Gevelreiniging', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Gevelreiniging voor woningen en winkelpanden', 'hds' ),
				'intro_text'  => __( 'Een schone gevel draagt bij aan een verzorgde uitstraling van uw woning of winkelpand.', 'hds' ),
				'paragraphs'  => [
					__( 'Wij reinigen gevels van woningen en winkelpanden, met de nadruk op laagbouw en goed bereikbare gevels. Zo verwijderen wij algemene vervuiling, groene aanslag, algen en stof- en vuilaanslag die aan de gevel hecht.', 'hds' ),
					__( 'Voor grote of hooggelegen gevels beschikken wij momenteel niet over de benodigde apparatuur. Onze gevelreiniging richt zich daarom op panden waarvan de gevel met de beschikbare middelen goed bereikbaar is.', 'hds' ),
				],
				'benefits'    => [
					__( 'Reiniging van woningen en winkelpanden', 'hds' ),
					__( 'Gericht op laagbouw en goed bereikbare gevels', 'hds' ),
					__( 'Verwijderen van groene aanslag en algen', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Verwijderen van algemene vervuiling die aan de gevel hecht', 'hds' ) ],
				[ 'text' => __( 'Reiniging van groene aanslag op de gevel', 'hds' ) ],
				[ 'text' => __( 'Verwijderen van algen van de gevel', 'hds' ) ],
				[ 'text' => __( 'Reiniging van stof- en vuilaanslag op de gevel', 'hds' ) ],
			],
			'industries'      => [ 'woningen', 'winkelpanden' ],
			'faq'             => [
				[
					'q' => __( 'Voor welke soorten panden is gevelreiniging beschikbaar?', 'hds' ),
					'a' => __( 'Wij reinigen gevels van woningen en winkelpanden, met de nadruk op laagbouw en goed bereikbare gevels.', 'hds' ),
				],
				[
					'q' => __( 'Welke soorten vervuiling kunnen jullie verwijderen?', 'hds' ),
					'a' => __( 'Wij verwijderen algemene vervuiling, groene aanslag, algen en stof- en vuilaanslag die aan de gevel hecht.', 'hds' ),
				],
				[
					'q' => __( 'Reinigen jullie ook hoge gevels?', 'hds' ),
					'a' => __( 'Nee. Onze gevelreiniging is gericht op laagbouw en goed bereikbare gevels. Voor grote of hooggelegen gevels beschikken wij momenteel niet over de benodigde apparatuur.', 'hds' ),
				],
				[
					'q' => __( 'Welke gevels zijn geschikt voor deze dienst?', 'hds' ),
					'a' => __( 'Gevels van woningen en winkelpanden die laaggelegen en goed bereikbaar zijn. Twijfelt u of uw gevel in aanmerking komt? Dan beoordelen wij dit graag samen met u.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bekijken wij de gevel en bespreken wij uw wensen.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Gevelreiniging voor woningen en winkelpanden | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Gevelreiniging voor woningen en winkelpanden door Hamdoun Schoonmaak. Wij verwijderen groene aanslag, algen en vuil van laagbouw en goed bereikbare gevels. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'reguliere-schoonmaak' => [
			'title'           => __( 'Reguliere Schoonmaak', 'hds' ),
			'subtitle'        => __( 'Betrouwbare reguliere schoonmaak op maat voor uw bedrijf.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 370,
			'eyebrow'         => __( 'Reguliere Schoonmaak', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele reguliere schoonmaak voor bedrijven', 'hds' ),
				'intro_text'  => __( 'Een schone werkomgeving draagt bij aan een prettige, verzorgde en representatieve bedrijfsomgeving.', 'hds' ),
				'paragraphs'  => [
					__( 'Met reguliere schoonmaak zorgen wij ervoor dat kantoren en bedrijfsruimtes schoon en verzorgd blijven. De werkzaamheden stemmen wij af op uw locatie, het gebruik van de ruimtes en uw wensen.', 'hds' ),
					__( 'Van algemene schoonmaak tot het reinigen van veelgebruikte ruimtes en oppervlakken: de werkzaamheden voeren wij uit volgens de afspraken die met u zijn gemaakt.', 'hds' ),
				],
				'benefits'    => [
					__( 'Schoonmaak van kantoren en bedrijfsruimtes', 'hds' ),
					__( 'Schoonmaak van sanitaire ruimtes', 'hds' ),
					__( 'Afgestemd op uw wensen', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Algemene schoonmaak van kantoorruimtes, vergaderruimtes en gemeenschappelijke ruimtes', 'hds' ) ],
				[ 'text' => __( 'Reinigen van sanitaire voorzieningen en het aanvullen van verbruiksartikelen', 'hds' ) ],
				[ 'text' => __( 'Stofzuigen en dweilen van vloeren, inclusief het stofvrij maken van plinten en hoeken', 'hds' ) ],
				[ 'text' => __( 'Afnemen van werkplekken, bureaus, tafels en overige meubeloppervlakken', 'hds' ) ],
				[ 'text' => __( 'Leegmaken van prullenbakken en afvalbeheer', 'hds' ) ],
				[ 'text' => __( 'Reinigen van pantry\'s, keukenblokken en koffiecorners inclusief apparatuur', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'zorginstellingen', 'scholen', 'retail', 'overheid', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Hoe bepalen jullie de planning?', 'hds' ),
					'a' => __( 'De planning en de frequentie stemmen wij af op uw wensen en het gebruik van de ruimtes. De werkzaamheden voeren wij uit volgens de afspraken die met u zijn gemaakt.', 'hds' ),
				],
				[
					'q' => __( 'Wat valt er onder reguliere schoonmaak?', 'hds' ),
					'a' => __( 'Onze reguliere schoonmaak omvat het reinigen van kantoorruimtes en bedrijfsruimtes: stofzuigen en dweilen van vloeren, reinigen van werkplekken en oppervlakken, schoonmaken van sanitair, legen van prullenbakken en het reinigen van pantry\'s en keukenblokken. De exacte werkzaamheden stemmen wij af op uw wensen.', 'hds' ),
				],
				[
					'q' => __( 'Kan het schoonmaakschema worden aangepast als de behoefte verandert?', 'hds' ),
					'a' => __( 'Zeker. De werkzaamheden en de planning passen wij in overleg eenvoudig aan wanneer uw situatie verandert. U zit nergens aan vast.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bespreken wij uw wensen en stellen wij een passend voorstel op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Reguliere Schoonmaak | Schoonmaak van kantoren en bedrijfsruimtes | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Reguliere schoonmaak door Hamdoun Schoonmaak. Schoonmaak van kantoren en bedrijfsruimtes, afgestemd op uw wensen. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'vloeronderhoud' => [
			'title'           => __( 'Vloeronderhoud', 'hds' ),
			'subtitle'        => __( 'Vakkundig vloeronderhoud voor een verzorgde en representatieve uitstraling.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 366,
			'eyebrow'         => __( 'Vloeronderhoud', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Vloeronderhoud voor verschillende soorten vloeren', 'hds' ),
				'intro_text'  => __( 'Wij verzorgen vloeronderhoud voor de meeste soorten vloeren, waaronder graniet, natuursteen, tegels en houten vloeren.', 'hds' ),
				'paragraphs'  => [
					__( 'Het onderhoud bestaat uit reinigen, polijsten, boenen, behandelen en beschermen. De werkzaamheden stemmen wij af op de vloer en de wensen van de locatie.', 'hds' ),
					__( 'Wij verzorgen vloeronderhoud voor kantoren, winkels, scholen, kinderopvang, woningen en VvE\'s.', 'hds' ),
				],
				'benefits'    => [
					__( 'Verschillende soorten vloeren', 'hds' ),
					__( 'Reinigen en onderhouden', 'hds' ),
					__( 'Polijsten en boenen', 'hds' ),
					__( 'Behandelen en beschermen', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Reinigen van de vloer', 'hds' ) ],
				[ 'text' => __( 'Polijsten van de vloer', 'hds' ) ],
				[ 'text' => __( 'Boenen van de vloer', 'hds' ) ],
				[ 'text' => __( 'Behandelen van de vloer', 'hds' ) ],
				[ 'text' => __( 'Beschermen van de vloer', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'winkels', 'scholen', 'kinderopvang', 'woningen', 'vve' ],
			'faq'             => [
				[
					'q' => __( 'Voor welke soorten vloeren verzorgen jullie vloeronderhoud?', 'hds' ),
					'a' => __( 'Wij onderhouden de meeste soorten vloeren, waaronder graniet, natuursteen, tegels en houten vloeren.', 'hds' ),
				],
				[
					'q' => __( 'Welke werkzaamheden voeren jullie uit?', 'hds' ),
					'a' => __( 'Wij reinigen, polijsten, boenen, behandelen en beschermen vloeren. De werkzaamheden stemmen wij af op de vloer en de locatie.', 'hds' ),
				],
				[
					'q' => __( 'Voor welke soorten locaties is vloeronderhoud beschikbaar?', 'hds' ),
					'a' => __( 'Wij verzorgen vloeronderhoud voor kantoren, winkels, scholen, kinderopvang, woningen en VvE\'s.', 'hds' ),
				],
				[
					'q' => __( 'Kunnen jullie verschillende soorten vloeren onderhouden?', 'hds' ),
					'a' => __( 'Ja. Wij onderhouden de meeste soorten vloeren, waaronder graniet, natuursteen, tegels en houten vloeren. Tijdens de kennismaking bespreken wij welke werkzaamheden passend zijn voor uw vloer.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bespreken wij uw wensen en stellen wij een passend voorstel op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Vloeronderhoud voor verschillende soorten vloeren | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Vloeronderhoud door Hamdoun Schoonmaak. Wij reinigen, polijsten, boenen, behandelen en beschermen vloeren van graniet, natuursteen, tegels en hout. Voor kantoren, winkels, scholen, kinderopvang, woningen en VvE\'s. Vrijblijvende offerte.', 'hds' ),
		],

		'vve-service' => [
			'title'           => __( 'VvE Service', 'hds' ),
			'subtitle'        => __( 'Complete schoonmaakdiensten voor Verenigingen van Eigenaren.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 371,
			'eyebrow'         => __( 'VvE Service', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Professionele schoonmaak voor VvE\'s', 'hds' ),
				'intro_text'  => __( 'Een schone en verzorgde woonomgeving draagt bij aan het comfort en de uitstraling van een appartementencomplex.', 'hds' ),
				'paragraphs'  => [
					__( 'Met onze VvE-service verzorgen wij de schoonmaak van gemeenschappelijke ruimtes binnen appartementencomplexen en woongebouwen. Denk aan entrees, trappenhuizen, gangen en andere gedeelde ruimtes die dagelijks door bewoners worden gebruikt.', 'hds' ),
					__( 'Wij stemmen de werkzaamheden af op het gebouw en de wensen van de VvE. Zo blijft het complex verzorgd en weten bewoners en bestuur waar zij aan toe zijn.', 'hds' ),
				],
				'benefits'    => [
					__( 'Schoonmaak van gemeenschappelijke ruimtes', 'hds' ),
					__( 'Afgestemd op de wensen van de VvE', 'hds' ),
					__( 'Geschikt voor appartementencomplexen en woongebouwen', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Schoonmaak van entrees, centrale hallen en algemene toegangsruimtes', 'hds' ) ],
				[ 'text' => __( 'Reinigen van trappenhuizen, gangen, galerijen en portieken', 'hds' ) ],
				[ 'text' => __( 'Stofzuigen en dweilen van vloeren in de gemeenschappelijke ruimtes', 'hds' ) ],
				[ 'text' => __( 'Reinigen van trapleuningen, deurklinken en andere veelgebruikte onderdelen', 'hds' ) ],
				[ 'text' => __( 'Schoonmaken van ramen en glaspartijen in gemeenschappelijke ruimtes', 'hds' ) ],
				[ 'text' => __( 'Aanvullende schoonmaak van bergingen, fietsenstallingen en technische ruimtes', 'hds' ) ],
			],
			'industries'      => [ 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Welke ruimtes worden binnen een VvE-complex schoongemaakt?', 'hds' ),
					'a' => __( 'Wij reinigen de gemeenschappelijke ruimtes binnen het complex: entrees, centrale hallen, trappenhuizen, gangen, galerijen en portieken. Optioneel kunnen ook bergingen, fietsenstallingen en technische ruimtes worden meegenomen in het schoonmaakplan.', 'hds' ),
				],
				[
					'q' => __( 'Hoe bepalen jullie de planning?', 'hds' ),
					'a' => __( 'De planning stemmen wij af op het complex en de wensen van de VvE. De werkzaamheden voeren wij uit volgens de afspraken die met de VvE zijn gemaakt.', 'hds' ),
				],
				[
					'q' => __( 'Kan de planning worden aangepast als de behoefte verandert?', 'hds' ),
					'a' => __( 'Ja. De werkzaamheden en de planning passen wij in overleg met het VvE-bestuur eenvoudig aan wanneer de behoefte verandert. U zit nergens aan vast.', 'hds' ),
				],
				[
					'q' => __( 'Hoe stemmen jullie de werkzaamheden af met het VvE-bestuur?', 'hds' ),
					'a' => __( 'Wij maken vooraf duidelijke afspraken over de uit te voeren werkzaamheden en de planning. Het VvE-bestuur heeft één vast aanspreekpunt bij Hamdoun Schoonmaak.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen voor onze VvE?', 'hds' ),
					'a' => __( 'Ja, wij maken graag een vrijblijvende offerte op maat voor uw VvE. Tijdens een kennismaking bekijken wij het complex, bespreken we de wensen en stellen we een passend schoonmaakplan op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'VvE Service | Professionele schoonmaak voor VvE\'s | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Professionele VvE-schoonmaak door Hamdoun Schoonmaak. Schoonmaak van gemeenschappelijke ruimtes in appartementencomplexen en woongebouwen, afgestemd op uw VvE. Vrijblijvende offerte.', 'hds' ),
		],

		'oplevering-schoonmaak' => [
			'title'           => __( 'Oplevering Schoonmaak', 'hds' ),
			'subtitle'        => __( 'Grondige opleverschoonmaak na bouw, verbouwing en renovatie.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 367,
			'eyebrow'         => __( 'Oplevering Schoonmaak', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Opleverschoonmaak na bouw en verbouwing', 'hds' ),
				'intro_text'  => __( 'Een opleverschoonmaak zorgt ervoor dat uw pand schoon wordt opgeleverd na bouw- of verbouwingswerkzaamheden.', 'hds' ),
				'paragraphs'  => [
					__( 'Na een bouwproject, verbouwing of renovatie blijft er vaak bouwstof en vuil achter. Onze opleverschoonmaak verwijdert deze resten, zodat uw pand schoon wordt opgeleverd.', 'hds' ),
					__( 'Wij stemmen de opleverschoonmaak af op uw projectplanning en de aard van het pand. Zo is het pand schoon op het moment van oplevering.', 'hds' ),
				],
				'benefits'    => [
					__( 'Reiniging na bouw, verbouwing en renovatie', 'hds' ),
					__( 'Verwijderen van bouwstof en bouwresten', 'hds' ),
					__( 'Afgestemd op uw opleverschema', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Verwijderen van bouwstof, cementresten, kit- en verfspatten', 'hds' ) ],
				[ 'text' => __( 'Reinigen en stofvrij maken van vloeren, plinten, kozijnen, deuren en vensterbanken', 'hds' ) ],
				[ 'text' => __( 'Glasbewassing van ramen, zowel binnen als buiten', 'hds' ) ],
				[ 'text' => __( 'Reinigen van keukenblokken, sanitair, tegels en voegen in natte ruimtes', 'hds' ) ],
				[ 'text' => __( 'Schoonmaken van radiatoren, ventilatieroosters, stopcontacten en lichtschakelaars', 'hds' ) ],
				[ 'text' => __( 'Nazorg en eventuele correctierondes', 'hds' ) ],
			],
			'industries'      => [ 'kantoren', 'zorginstellingen', 'scholen', 'retail', 'overheid', 'bedrijfsverzamelgebouwen' ],
			'faq'             => [
				[
					'q' => __( 'Wanneer wordt een opleverschoonmaak uitgevoerd?', 'hds' ),
					'a' => __( 'Een opleverschoonmaak wordt uitgevoerd na afronding van bouw-, verbouwings- of renovatiewerkzaamheden, vlak voor de sleuteloverdracht. Dit kan ook na een verhuizing of bij de oplevering van een nieuw pand.', 'hds' ),
				],
				[
					'q' => __( 'Wat houdt een opleverschoonmaak in?', 'hds' ),
					'a' => __( 'Onze opleverschoonmaak omvat het verwijderen van bouwstof, cementresten, kit- en verfspatten, het reinigen van vloeren en oppervlakken, glasbewassing binnen en buiten, en het schoonmaken van sanitair en keukenblokken. De exacte werkzaamheden stemmen wij af op uw wensen.', 'hds' ),
				],
				[
					'q' => __( 'Hoe wordt de opleverschoonmaak gepland?', 'hds' ),
					'a' => __( 'Wij stemmen de planning af op uw project en de datum van oplevering. De werkzaamheden plannen wij in overleg met u, zodat het pand op het juiste moment schoon wordt opgeleverd.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bespreken wij uw project en stellen wij een passend voorstel op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Oplevering Schoonmaak | Opleverschoonmaak na bouw en verbouwing | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Opleverschoonmaak door Hamdoun Schoonmaak. Wij verwijderen bouwstof en bouwresten en reinigen vloeren, oppervlakken, ramen en sanitair. Afgestemd op uw opleverschema. Vrijblijvende offerte aanvragen.', 'hds' ),
		],

		'scholen-en-kinderopvang-reiniging' => [
			'title'           => __( 'Scholen en Kinderopvang reiniging', 'hds' ),
			'subtitle'        => __( 'Schoonmaak voor scholen en kinderopvanglocaties, afgestemd op het gebruik van de ruimtes.', 'hds' ),
			'hero_image'      => 0,
			'visual_break_image' => 363,
			'eyebrow'         => __( 'Scholen en kinderopvang', 'hds' ),
			'intro'           => [
				'eyebrow'     => __( 'Onze dienst', 'hds' ),
				'title'       => __( 'Schoonmaak voor scholen en kinderopvang', 'hds' ),
				'intro_text'  => __( 'Een schone omgeving draagt bij aan een prettige leer- en speelomgeving.', 'hds' ),
				'paragraphs'  => [
					__( 'Wij hebben praktische ervaring met schoonmaakwerk in scholen en kinderopvanglocaties. Deze ervaring hebben wij opgedaan in het basisonderwijs, het voortgezet onderwijs en in de kinderopvang.', 'hds' ),
					__( 'Onze medewerkers voeren de werkzaamheden zorgvuldig uit volgens de afspraken die wij met de locatie hebben gemaakt. Zo blijft de omgeving schoon en verzorgd voor kinderen, medewerkers en bezoekers.', 'hds' ),
				],
				'benefits'    => [
					__( 'Ervaring met basisscholen en middelbare scholen', 'hds' ),
					__( 'Ervaring met kinderopvang', 'hds' ),
					__( 'Schoonmaak volgens de gemaakte afspraken', 'hds' ),
					__( 'Vrijblijvende offerte', 'hds' ),
				],
			],
			'checklist'       => [
				[ 'text' => __( 'Reiniging van de klaslokalen, waar kinderen en docenten gebruik van maken', 'hds' ) ],
				[ 'text' => __( 'Schoonmaak van de sanitaire ruimtes, zoals toiletten en wastafels', 'hds' ) ],
				[ 'text' => __( 'Reiniging van de speelruimtes, waar kinderen spelen en verblijven', 'hds' ) ],
				[ 'text' => __( 'Schoonmaak van de gangen binnen de locatie', 'hds' ) ],
			],
			'industries'      => [ 'basisscholen', 'middelbare-scholen', 'kinderopvang' ],
			'faq'             => [
				[
					'q' => __( 'Voor welke locaties is deze schoonmaak bedoeld?', 'hds' ),
					'a' => __( 'Voor basisscholen, middelbare scholen en kinderopvanglocaties. Wij hebben in deze omgevingen praktische schoonmaakervaring opgedaan.', 'hds' ),
				],
				[
					'q' => __( 'Welke ruimtes maken jullie schoon?', 'hds' ),
					'a' => __( 'Wij reinigen klaslokalen, sanitaire ruimtes, speelruimtes en gangen. De werkzaamheden stemmen wij af op de wensen van de locatie.', 'hds' ),
				],
				[
					'q' => __( 'Hoe bepalen jullie de planning?', 'hds' ),
					'a' => __( 'Samen met de locatie bepalen wij de werkzaamheden en het moment van schoonmaken. Wij voeren de werkzaamheden uit volgens de afspraken die met u zijn gemaakt.', 'hds' ),
				],
				[
					'q' => __( 'Kan ik een vrijblijvende offerte aanvragen?', 'hds' ),
					'a' => __( 'Ja. Wij maken graag een vrijblijvende offerte op maat. Tijdens de kennismaking bespreken wij uw wensen en stellen wij een passend voorstel op.', 'hds' ),
				],
			],
			'seo_title'       => __( 'Schoonmaak van scholen en kinderopvang | Hamdoun Schoonmaak', 'hds' ),
			'seo_description' => __( 'Schoonmaak van scholen en kinderopvanglocaties door Hamdoun Schoonmaak. Ervaring met basisscholen, middelbare scholen en kinderopvang. Wij reinigen klaslokalen, sanitair, speelruimtes en gangen. Vrijblijvende offerte.', 'hds' ),
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
