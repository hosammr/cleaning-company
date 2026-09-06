<?php
/**
 * Reusable global component library.
 *
 * Functions that output standardized, accessible UI components:
 * notifications, cookie banner, CTA sections, search form, back-to-top,
 * page headers, section headers, shared button/card helpers.
 *
 * All components follow the HDS Design System (DS-001) and use theme.json
 * design tokens exclusively.
 *
 * @package HDS
 */

/**
 * Render a reusable CTA section.
 *
 * @param string   $heading       CTA heading text.
 * @param string   $description   CTA description (optional).
 * @param string   $button_text   Button label.
 * @param string   $button_url    Button link URL.
 * @param string   $style         'primary' (default), 'secondary', or 'light'.
 * @param string[] $trust_bullets Optional list of trust bullet texts below the button.
 * @param string   $eyebrow       Optional small eyebrow label above the heading.
 * @param array    $secondary     Optional secondary CTA with 'text' and 'url' keys.
 */
function hds_cta_section( string $heading, string $description = '', string $button_text = '', string $button_url = '', string $style = 'primary', array $trust_bullets = array(), string $eyebrow = '', array $secondary = array() ): string {
	if ( ! $button_text ) {
		$button_text = __( 'Offerte aanvragen', 'hds' );
	}
	if ( ! $button_url ) {
		$button_url = home_url( '/offerte-aanvragen/' );
	}

	$style_class = 'secondary' === $style ? 'cta-banner--secondary' : ( 'light' === $style ? 'cta-banner--light' : '' );

	ob_start();
	?>
	<section class="cta-banner <?php echo esc_attr( $style_class ); ?>">
		<div class="container">
			<?php if ( $eyebrow ) : ?>
				<p class="cta-banner__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="cta-banner__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $description ) : ?>
				<p class="cta-banner__description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<div class="cta-banner__actions">
				<a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-cta">
					<?php echo esc_html( $button_text ); ?>
				</a>
				<?php if ( ! empty( $secondary['text'] ) && ! empty( $secondary['url'] ) ) : ?>
					<a href="<?php echo esc_url( $secondary['url'] ); ?>" class="btn cta-banner__secondary">
						<?php echo esc_html( $secondary['text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
			<?php if ( $trust_bullets ) : ?>
				<ul class="cta-banner__trust">
					<?php foreach ( $trust_bullets as $bullet ) : ?>
						<li><?php echo esc_html( $bullet ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render a page header section.
 *
 * @param string $title    Page title (H1).
 * @param string $subtitle Optional subtitle.
 * @param int    $bg_image Optional hero background image attachment ID.
 */
function hds_page_header( string $title, string $subtitle = '', int $bg_image = 0 ): string {
	$bg_style = '';
	if ( $bg_image ) {
		$bg_url = wp_get_attachment_image_url( $bg_image, 'hds-hero' );
		if ( $bg_url ) {
			$bg_style = ' style="background-image:url(' . esc_url( $bg_url ) . ')"';
		}
	}

	ob_start();
	?>
	<header class="page-header"<?php echo $bg_style; // phpcs:ignore ?>>
		<div class="container">
			<h1 class="page-header__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="page-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render a section header.
 *
 * @param string $heading   Section heading (H2).
 * @param string $subtitle  Optional subtitle.
 * @param string $alignment 'left' (default), 'center'.
 */
function hds_section_header( string $heading, string $subtitle = '', string $alignment = 'left' ): string {
	$align_class = 'center' === $alignment ? ' section-header--center' : '';

	ob_start();
	?>
	<div class="section-header<?php echo esc_attr( $align_class ); ?>">
		<h2 class="section-header__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php if ( $subtitle ) : ?>
			<p class="section-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render a button component.
 *
 * @param string $text  Button label.
 * @param string $url   Button URL.
 * @param string $style 'primary', 'secondary', 'cta', 'outline'.
 * @param array  $attrs Additional HTML attributes [key => value].
 */
function hds_button( string $text, string $url = '#', string $style = 'primary', array $attrs = [] ): string {
	$classes = [ 'btn', 'btn--' . $style ];

	$attr_string = '';
	foreach ( $attrs as $key => $val ) {
		$attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $val ) . '"';
	}

	return sprintf(
		'<a href="%s" class="%s"%s>%s</a>',
		esc_url( $url ),
		esc_attr( implode( ' ', $classes ) ),
		$attr_string,
		esc_html( $text )
	);
}

/**
/**
 * Render a card wrapper.
 *
 * @param string $content Pre-escaped content HTML.
 * @param string $class   Additional CSS classes.
 * @param bool   $link    If true, card is clickable (wraps entire card).
 */
function hds_card( string $content, string $class = '', bool $clickable = false ): string {
	$classes = 'hds-card';
	if ( $class ) {
		$classes .= ' ' . $class;
	}
	if ( $clickable ) {
		$classes .= ' hds-card--clickable';
	}

	return sprintf( '<div class="%s">%s</div>', esc_attr( $classes ), $content );
}

/**
 * Render a USP card.
 *
 * @param string $title       Card title (pre-translated).
 * @param string $description Card description (pre-translated).
 * @param string $icon        Optional SVG icon markup.
 */
function hds_usp_card( string $title, string $description, string $icon = '' ): string {
	ob_start();
	?>
	<article class="hds-card hds-usp-card">
		<?php if ( $icon ) : ?>
			<span class="hds-usp-card__icon" aria-hidden="true"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
		<h3 class="hds-usp-card__title"><?php echo esc_html( $title ); ?></h3>
		<p class="hds-usp-card__desc"><?php echo esc_html( $description ); ?></p>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render a grid wrapper.
 *
 * @param string $content Pre-escaped content HTML.
 * @param int    $columns Number of columns (default auto-fit).
 * @param string $class   Additional CSS classes.
 */
function hds_grid( string $content, int $columns = 3, string $class = '' ): string {
	$classes = 'hds-grid';
	if ( $class ) {
		$classes .= ' ' . $class;
	}

	$style = '--hds-grid-columns:' . $columns;

	return sprintf( '<div class="%s" style="%s">%s</div>', esc_attr( $classes ), esc_attr( $style ), $content );
}

/**
 * Render a cookie consent banner placeholder.
 *
 * In production, Complianz Premium replaces this with its own banner.
 * This serves as a fallback and structural placeholder during development.
 */
function hds_cookie_banner(): string {
	if ( function_exists( 'cmplz_cookiebanner' ) ) {
		return '';
	}

	if ( isset( $_COOKIE['hds_cookie_consent'] ) ) {
		return '';
	}

	ob_start();
	?>
	<div id="hds-cookie-banner" class="hds-cookie-banner" role="dialog" aria-labelledby="hds-cookie-title" aria-describedby="hds-cookie-desc">
		<div class="hds-cookie-banner__inner">
			<div class="hds-cookie-banner__content">
				<h2 id="hds-cookie-title" class="hds-cookie-banner__title">
					<?php esc_html_e( 'Deze website gebruikt cookies', 'hds' ); ?>
				</h2>
				<p id="hds-cookie-desc" class="hds-cookie-banner__description">
					<?php esc_html_e( 'Wij gebruiken cookies om de website goed te laten werken en te analyseren. Door op "Accepteren" te klikken stemt u in met het gebruik van alle cookies.', 'hds' ); ?>
				</p>
			</div>
			<div class="hds-cookie-banner__actions">
				<button type="button" class="btn btn--primary hds-cookie-banner__accept" data-action="accept">
					<?php esc_html_e( 'Accepteren', 'hds' ); ?>
				</button>
				<button type="button" class="btn btn--outline hds-cookie-banner__decline" data-action="decline">
					<?php esc_html_e( 'Alleen functioneel', 'hds' ); ?>
				</button>
				<a href="<?php echo esc_url( home_url( '/privacyverklaring/' ) ); ?>" class="hds-cookie-banner__link">
					<?php esc_html_e( 'Privacyverklaring', 'hds' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render a custom search form.
 */
function hds_search_form(): string {
	$unique_id = 'hds-search-' . wp_unique_id( 's-' );

	ob_start();
	?>
	<form role="search" method="get" class="hds-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="hds-search-form__label" for="<?php echo esc_attr( $unique_id ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Zoeken naar:', 'hds' ); ?></span>
		</label>
		<div class="hds-search-form__wrapper">
			<input
				type="search"
				id="<?php echo esc_attr( $unique_id ); ?>"
				class="hds-search-form__input"
				name="s"
				value="<?php echo get_search_query(); ?>"
				placeholder="<?php esc_attr_e( 'Zoeken...', 'hds' ); ?>"
				required
				aria-label="<?php esc_attr_e( 'Zoeken op de website', 'hds' ); ?>"
			>
			<button type="submit" class="hds-search-form__submit" aria-label="<?php esc_attr_e( 'Zoek', 'hds' ); ?>">
				<span class="hds-search-form__submit-icon" aria-hidden="true">&#128269;</span>
			</button>
		</div>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Replace the default WordPress search form with the HDS version.
 */
function hds_replace_search_form( string $form ): string {
	return hds_search_form();
}
add_filter( 'get_search_form', 'hds_replace_search_form' );

/**
 * Render a back-to-top button.
 */
function hds_back_to_top(): string {
	ob_start();
	?>
	<button type="button" id="hds-back-to-top" class="hds-back-to-top" aria-label="<?php esc_attr_e( 'Terug naar boven', 'hds' ); ?>" hidden>
		<span aria-hidden="true">&#8593;</span>
	</button>
	<?php
	return ob_get_clean();
}

/**
 * Add back-to-top button to footer.
 */
function hds_add_back_to_top_to_footer(): void {
	if ( wp_is_mobile() ) {
		return;
	}
	echo hds_back_to_top(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_footer', 'hds_add_back_to_top_to_footer', 99 );

/**
 * Output cookie consent banner fallback.
 *
 * Only renders when Complianz Premium is not active.
 * When Complianz is installed, cmplz_cookiebanner() handles rendering
 * and this function returns empty.
 */
function hds_output_cookie_banner(): void {
	echo hds_cookie_banner(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_footer', 'hds_output_cookie_banner', 10 );

/**
 * Render a single USP card.
 *
 * @param array $item Card data with 'title' and 'description' keys.
 * @return string HTML for one USP card.
 */
function hds_render_usp_card( array $item ): string {
	ob_start();
	?>
	<div class="usp-card">
		<div class="usp-card__accent" aria-hidden="true"></div>
		<h3 class="usp-card__title"><?php echo esc_html( $item['title'] ); ?></h3>
		<div class="usp-card__divider" aria-hidden="true"></div>
		<p class="usp-card__description"><?php echo esc_html( $item['description'] ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render a USP card grid with heading, subtitle, and card items.
 *
 * Shared component used by homepage, service pages, and any page
 * that needs USP cards. Cards are rendered via hds_render_usp_card().
 *
 * @param array  $items   Array of cards, each with 'title' and 'description'.
 * @param string $heading Section heading (H2).
 * @param string $subtitle Optional subtitle paragraph.
 * @return string Complete USP grid section HTML.
 */
function hds_render_usp_grid( array $items, string $heading, string $subtitle = '' ): string {
	if ( empty( $items ) ) {
		return '';
	}

	$column_count = count( $items );
	$grid_class   = 'usp-grid__cards';

	ob_start();
	?>
	<section class="usp-grid">
		<div class="container">
			<div class="section-header section-header--center">
				<h2 class="section-header__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php if ( $subtitle ) : ?>
					<p class="section-header__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="<?php echo esc_attr( $grid_class ); ?>" style="--usp-columns:<?php echo (int) $column_count; ?>">
				<?php foreach ( $items as $item ) : ?>
					<?php echo hds_render_usp_card( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the homepage trust strip.
 *
 * Compact benefit strip shown directly below the hero. All claims come
 * from verified project content (USPs and the service area statement).
 *
 * @return string Trust strip section HTML.
 */
function hds_render_trust_strip(): string {
	$items = array(
		__( 'Vast opgeleid personeel', 'hds' ),
		__( 'Betrouwbaar en flexibel', 'hds' ),
		__( 'Eén vast aanspreekpunt', 'hds' ),
		__( 'Actief in de provincie Groningen', 'hds' ),
	);

	ob_start();
	?>
	<section class="hds-trust-strip" aria-label="<?php esc_attr_e( 'Waar u op kunt rekenen', 'hds' ); ?>">
		<div class="container">
			<ul class="hds-trust-strip__list">
				<?php foreach ( $items as $item ) : ?>
					<li class="hds-trust-strip__item">
						<span class="hds-trust-strip__icon" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
						<span class="hds-trust-strip__label"><?php echo esc_html( $item ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the homepage "Why Hamdoun Schoonmaak" section.
 *
 * Two-column layout: copy with verified benefits on the left,
 * large image on the right. All claims come from verified project
 * content (USPs, service pages, and the About page values).
 *
 * @return string Why section HTML.
 */
function hds_render_why_section(): string {
	$benefits = array(
		__( 'Vast opgeleid personeel', 'hds' ),
		__( 'Eén vast aanspreekpunt', 'hds' ),
		__( 'Flexibele dienstverlening', 'hds' ),
		__( 'Professionele werkwijze', 'hds' ),
		__( 'Duurzame dienstverlening', 'hds' ),
	);

	$image_url = home_url( '/wp-content/uploads/2026/08/Bedrijf.png' );

	ob_start();
	?>
	<section class="hds-why-section">
		<div class="container">
			<div class="hds-why-grid">
				<div class="hds-why-content">
					<p class="hds-why-eyebrow"><?php esc_html_e( 'Waarom Hamdoun Schoonmaak', 'hds' ); ?></p>
					<h2 class="hds-why-heading"><?php esc_html_e( 'Uw betrouwbare schoonmaakpartner in Groningen', 'hds' ); ?></h2>
					<p class="hds-why-text"><?php esc_html_e( 'Daarom kiezen bedrijven in de provincie Groningen voor Hamdoun Schoonmaak als vaste schoonmaakpartner.', 'hds' ); ?></p>
					<ul class="hds-why-benefits">
						<?php foreach ( $benefits as $benefit ) : ?>
							<li class="hds-why-benefits__item">
								<span class="hds-why-benefits__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
								<span><?php echo esc_html( $benefit ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="hds-why-cta">
						<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/over-hds/' ) ); ?>">
							<?php esc_html_e( 'Meer over Hamdoun', 'hds' ); ?>
							<span aria-hidden="true">&rarr;</span>
						</a>
					</p>
				</div>
				<div class="hds-why-media">
					<img
						src="<?php echo esc_url( $image_url ); ?>"
						alt="<?php esc_attr_e( 'Hamdoun Schoonmaak bedrijfsvoertuigen en team', 'hds' ); ?>"
						loading="lazy"
						width="1254"
						height="1254"
					>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the homepage quality / trust section.
 *
 * Dark-blue section with four benefit blocks. No statistics or
 * unverified claims — only verified project content is used.
 *
 * @return string Quality section HTML.
 */
function hds_render_quality_section(): string {
	$blocks = array(
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M216 112c0 48-32 88-88 104-56-16-88-56-88-104V64l88-32 88 32Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M160 112l-32 32-24-24" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Professionele aanpak', 'hds' ),
			'desc'  => __( 'Professionele schoonmaakdiensten voor bedrijven en organisaties.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M128 128a40 40 0 1 0 0-80 40 40 0 0 0 0 80ZM60 216c8-32 36-52 68-52s60 20 68 52" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Eén vast aanspreekpunt', 'hds' ),
			'desc'  => __( 'U heeft altijd één vast aanspreekpunt voor al uw vragen.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><rect x="40" y="56" width="176" height="160" rx="16" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M40 104h176M96 48v32M160 48v32" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Flexibele dienstverlening', 'hds' ),
			'desc'  => __( 'Werkzaamheden afgestemd op uw openingstijden en bedrijfsprocessen.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M128 48a48 48 0 0 0-48 48c0 36 48 96 48 96s48-60 48-96a48 48 0 0 0-48-48Zm0 72a24 24 0 1 0 0-48 24 24 0 0 0 0 48Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Actief in de provincie Groningen', 'hds' ),
			'desc'  => __( 'Bedrijven in de provincie Groningen kiezen ons als vaste schoonmaakpartner.', 'hds' ),
		),
	);

	ob_start();
	?>
	<section class="hds-quality-section">
		<div class="container">
			<div class="section-header section-header--center hds-quality-header">
				<h2 class="section-header__heading"><?php esc_html_e( 'Kwaliteit die u kunt vertrouwen', 'hds' ); ?></h2>
				<p class="section-header__subtitle"><?php esc_html_e( 'Wij werken volgens de hoogste veiligheidsnormen, met vast opgeleid personeel.', 'hds' ); ?></p>
			</div>
			<div class="hds-quality-grid">
				<?php foreach ( $blocks as $block ) : ?>
					<article class="hds-quality-card">
						<span class="hds-quality-card__icon" aria-hidden="true"><?php echo $block['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3 class="hds-quality-card__title"><?php echo esc_html( $block['title'] ); ?></h3>
						<p class="hds-quality-card__desc"><?php echo esc_html( $block['desc'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the homepage service gallery slider.
 *
 * Visual-first horizontal slider showing the homepage service images.
 * Lightweight: CSS scroll-snap for touch swiping plus minimal vanilla JS
 * for prev/next, pagination and keyboard support (assets/js/main.js).
 *
 * @return string Gallery section HTML.
 */
function hds_render_service_gallery(): string {
	$services = hds_get_visible_service_pages();

	if ( empty( $services ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="hds-slider-section" aria-labelledby="hds-slider-heading">
		<div class="container">
			<div class="section-header section-header--center">
				<p class="section-header__eyebrow"><?php esc_html_e( 'Onze diensten', 'hds' ); ?></p>
				<h2 class="section-header__heading" id="hds-slider-heading"><?php esc_html_e( 'Onze schoonmaakdiensten in beeld', 'hds' ); ?></h2>
			</div>
			<div class="hds-slider">
				<div class="hds-slider__viewport" tabindex="0" role="region" aria-roledescription="<?php esc_attr_e( 'carrousel', 'hds' ); ?>" aria-label="<?php esc_attr_e( 'Onze schoonmaakdiensten in beeld', 'hds' ); ?>">
					<ul class="hds-slider__track">
						<?php foreach ( $services as $service ) : ?>
							<li class="hds-slider__slide">
								<figure class="hds-slider__figure">
									<?php
									$image_id = get_post_thumbnail_id( $service );
									if ( ! $image_id ) {
										$image_id = get_post_meta( $service->ID, 'hds_hero_image', true );
									}
									if ( $image_id ) {
										echo wp_get_attachment_image( (int) $image_id, 'hds-card', false, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapes internally.
											'alt'     => get_the_title( $service ),
											'loading' => 'lazy',
										) );
									} else {
										echo '<span class="hds-slider__placeholder" aria-hidden="true"></span>';
									}
									?>
									<figcaption class="hds-slider__caption">
										<a href="<?php echo esc_url( get_permalink( $service ) ); ?>">
											<?php echo esc_html( get_the_title( $service ) ); ?>
										</a>
									</figcaption>
								</figure>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<div class="hds-slider__controls">
					<button type="button" class="hds-slider__arrow hds-slider__arrow--prev" aria-label="<?php esc_attr_e( 'Vorige dienst', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M160 48l-80 80 80 80" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<div class="hds-slider__dots" role="group" aria-label="<?php esc_attr_e( 'Kies een dienst', 'hds' ); ?>"></div>
					<button type="button" class="hds-slider__arrow hds-slider__arrow--next" aria-label="<?php esc_attr_e( 'Volgende dienst', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M96 48l80 80-80 80" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render a process timeline section.
 *
 * Shared component used by service pages and the quote page.
 * Renders a heading followed by an ordered list of process steps
 * with numbered circles connected by a timeline.
 *
 * @param string $heading Section heading.
 * @param array  $steps   Array of steps, each with 'title' and 'description'.
 * @return string Complete process timeline section HTML.
 */
function hds_render_process_timeline( string $heading, array $steps ): string {
	if ( empty( $steps ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="hds-process-section" aria-labelledby="hds-process-heading">
		<div class="container">
			<header class="hds-process-header">
				<h2 id="hds-process-heading"><?php echo esc_html( $heading ); ?></h2>
			</header>
			<ol class="hds-process-steps">
				<?php foreach ( $steps as $index => $step ) : ?>
					<li class="hds-process-step">
						<span class="hds-process-step__number" aria-hidden="true"><?php echo (int) ( $index + 1 ); ?></span>
						<h3 class="hds-process-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="hds-process-step__desc"><?php echo esc_html( $step['description'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the Service Introduction section.
 *
 * Centered eyebrow + heading + intro sentence above a two-column layout.
 * Left: paragraphs, Right: benefit checklist.
 * Reads content from the service's `intro` data in services.php.
 *
 * @param array $intro Intro data with 'eyebrow', 'title', 'intro_text', 'paragraphs', and 'benefits' keys.
 * @return string Service introduction section HTML.
 */
function hds_render_service_intro( array $intro ): string {
	ob_start();
	?>
	<section class="service-intro">
		<div class="container">
			<?php if ( ! empty( $intro['eyebrow'] ) ) : ?>
				<p class="service-intro__eyebrow"><?php echo esc_html( $intro['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h2 class="service-intro__title"><?php echo esc_html( $intro['title'] ); ?></h2>
			<?php if ( ! empty( $intro['intro_text'] ) ) : ?>
				<p class="service-intro__intro-text"><?php echo esc_html( $intro['intro_text'] ); ?></p>
			<?php endif; ?>
			<div class="service-intro__grid">
				<div class="service-intro__content">
					<?php foreach ( $intro['paragraphs'] as $paragraph ) : ?>
						<p class="service-intro__text"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $intro['benefits'] ) ) : ?>
					<div class="service-intro__benefits">
						<ul class="service-intro__benefit-list">
							<?php foreach ( $intro['benefits'] as $benefit ) : ?>
								<li class="service-intro__benefit-item"><?php echo esc_html( $benefit ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the homepage HAMDOUN branding video section.
 *
 * Two-column layout: copy on the left, the approved HAMDOUN branding V4
 * video on the right. The video is the visual centerpiece; the copy
 * reinforces the brand after the services grid.
 *
 * Reduced motion: autoplay is suppressed from assets/js/main.js so the
 * poster is shown as the visual fallback.
 *
 * @return string Branding video section HTML.
 */
function hds_render_branding_video_section(): string {
	$video_dir = get_template_directory_uri() . '/assets/videos';

	ob_start();
	?>
	<section class="hds-branding-video" aria-labelledby="hds-branding-video-heading">
		<div class="container">
			<div class="hds-branding-video__grid">
				<div class="hds-branding-video__content">
					<p class="hds-branding-video__eyebrow"><?php esc_html_e( 'Onze identiteit', 'hds' ); ?></p>
					<h2 id="hds-branding-video-heading" class="hds-branding-video__heading"><?php esc_html_e( 'Vakmanschap begint met aandacht voor detail', 'hds' ); ?></h2>
					<p class="hds-branding-video__text"><?php esc_html_e( 'Bij Hamdoun staat kwaliteit centraal. Van het kleinste detail tot onze professionele uitstraling.', 'hds' ); ?></p>
				</div>
				<div class="hds-branding-video__media">
					<video
						class="hds-branding-video__player"
						autoplay
						muted
						loop
						playsinline
						preload="metadata"
						poster="<?php echo esc_url( $video_dir . '/hamdoun-branding-v4-poster.jpg' ); ?>"
						aria-label="<?php esc_attr_e( 'HAMDOUN branding video', 'hds' ); ?>"
					>
						<source src="<?php echo esc_url( $video_dir . '/hamdoun-branding-v4.mp4' ); ?>" type="video/mp4">
					</video>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
