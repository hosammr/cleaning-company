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
 * Render a small inline SVG icon.
 *
 * Feather-style stroke icons, consistent with the existing header/footer SVGs.
 *
 * @param string $name Icon name: phone, smartphone, mail, clock, check, shield, award, refresh, leaf.
 * @param int    $size Icon size in pixels.
 * @return string Inline SVG markup.
 */
function hds_svg_icon( string $name, int $size = 20 ): string {
	$icons = [
		'phone'      => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'smartphone' => '<rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
		'mail'       => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
		'clock'      => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'check'      => '<polyline points="20 6 9 17 4 12"/>',
		'shield'     => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
		'award'      => '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
		'refresh'    => '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>',
		'leaf'       => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
	];

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="hds-icon hds-icon--' . esc_attr( $name ) . '" aria-hidden="true" width="' . esc_attr( (string) $size ) . '" height="' . esc_attr( (string) $size ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" focusable="false">' . $icons[ $name ] . '</svg>';
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
				<a href="<?php echo esc_url( home_url( '/cookiebeleid/' ) ); ?>" class="hds-cookie-banner__link">
					<?php esc_html_e( 'Cookiebeleid', 'hds' ); ?>
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
		__( 'Vakbekwaam en ervaren team', 'hds' ),
		__( 'Persoonlijk contact en korte lijnen', 'hds' ),
		__( 'Flexibele inzet voor uw situatie', 'hds' ),
		__( 'Professionele en verzorgde uitvoering', 'hds' ),
		__( 'Duurzame dienstverlening', 'hds' ),
	);

	$image_url = home_url( '/wp-content/uploads/2026/08/Bedrijf.png' );
	$bedrijf_image_id = attachment_url_to_postid( $image_url );

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
					<?php if ( $bedrijf_image_id ) : ?>
						<?php
						$webp_source = hds_below_fold_webp_source( (int) $bedrijf_image_id, 'hds-gallery' );
						if ( $webp_source ) {
							echo '<picture>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $webp_source; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapes internally.
							(int) $bedrijf_image_id,
							'hds-gallery',
							false,
							[
								'alt'     => __( 'Hamdoun Schoonmaak bedrijfsvoertuigen en team', 'hds' ),
								'loading' => 'lazy',
							]
						);
						if ( $webp_source ) {
							echo '</picture>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					<?php else : ?>
						<img
							src="<?php echo esc_url( $image_url ); ?>"
							alt="<?php esc_attr_e( 'Hamdoun Schoonmaak bedrijfsvoertuigen en team', 'hds' ); ?>"
							loading="lazy"
							width="1254"
							height="1254"
						>
					<?php endif; ?>
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
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><circle cx="128" cy="128" r="96" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="88 128 116 156 168 104" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Kwaliteit en controle', 'hds' ),
			'desc'  => __( 'Wij controleren onze werkzaamheden en leveren werk van een constant niveau.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M216 112c0 48-32 88-88 104-56-16-88-56-88-104V64l88-32 88 32Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Veilig werken', 'hds' ),
			'desc'  => __( 'Wij werken veilig en volgens de geldende veiligheidsnormen.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M128 128a40 40 0 1 0 0-80 40 40 0 0 0 0 80ZM60 216c8-32 36-52 68-52s60 20 68 52" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
			'title' => __( 'Vakbekwaam en opgeleid', 'hds' ),
			'desc'  => __( 'Ons team wordt opgeleid en bijgeschoold om vakbekwaam te blijven.', 'hds' ),
		),
		array(
			'icon'  => '<svg width="32" height="32" viewBox="0 0 256 256" fill="none" aria-hidden="true"><rect x="40" y="48" width="176" height="160" rx="16" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="72 104 88 120 120 88" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><line x1="144" y1="104" x2="192" y2="104" stroke="currentColor" stroke-width="12" stroke-linecap="round"/><line x1="144" y1="136" x2="192" y2="136" stroke="currentColor" stroke-width="12" stroke-linecap="round"/><line x1="144" y1="168" x2="192" y2="168" stroke="currentColor" stroke-width="12" stroke-linecap="round"/></svg>',
			'title' => __( 'Duidelijke werkwijze', 'hds' ),
			'desc'  => __( 'U weet precies wat u van ons kunt verwachten; wij leggen afspraken helder vast.', 'hds' ),
		),
	);

	ob_start();
	?>
	<section class="hds-quality-section">
		<div class="container">
			<div class="section-header section-header--center hds-quality-header">
				<h2 class="section-header__heading"><?php esc_html_e( 'Kwaliteit die u kunt vertrouwen', 'hds' ); ?></h2>
				<p class="section-header__subtitle"><?php esc_html_e( 'Kwaliteit en veiligheid staan centraal in onze werkwijze. Ons team is vakbekwaam en opgeleid en werkt volgens een duidelijke aanpak.', 'hds' ); ?></p>
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
			<p class="hds-quality-cta">
				<a class="btn btn--white" href="<?php echo esc_url( home_url( '/kwaliteit-en-veiligheid/' ) ); ?>">
					<?php esc_html_e( 'Meer over kwaliteit & veiligheid', 'hds' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</p>
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

	$slider_alts = array(
		'kantoor-schoonmaak'                => __( 'Kantoorreiniging door Hamdoun Schoonmaak', 'hds' ),
		'glasbewassing'                     => __( 'Glasbewassing door Hamdoun Schoonmaak', 'hds' ),
		'scholen-en-kinderopvang-reiniging' => __( 'Schoonmaak van scholen en kinderopvang door Hamdoun Schoonmaak', 'hds' ),
		'gevelreiniging'                    => __( 'Gevelreiniging door Hamdoun Schoonmaak', 'hds' ),
		'vloeronderhoud'                    => __( 'Vloeronderhoud door Hamdoun Schoonmaak', 'hds' ),
		'oplevering-schoonmaak'             => __( 'Oplevering schoonmaak door Hamdoun Schoonmaak', 'hds' ),
		'reguliere-schoonmaak'              => __( 'Reguliere schoonmaak door Hamdoun Schoonmaak', 'hds' ),
		'vve-service'                       => __( 'VvE service door Hamdoun Schoonmaak', 'hds' ),
	);

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
									$image_id = (int) get_post_meta( $service->ID, 'hds_slider_image', true );
									if ( ! $image_id ) {
										$image_id = get_post_thumbnail_id( $service );
									}
									if ( ! $image_id ) {
										$image_id = get_post_meta( $service->ID, 'hds_hero_image', true );
									}
									if ( $image_id ) {
										$alt         = isset( $slider_alts[ $service->post_name ] ) ? $slider_alts[ $service->post_name ] : get_the_title( $service );
										$webp_source = hds_below_fold_webp_source( (int) $image_id, 'medium_large' );
										if ( $webp_source ) {
											echo '<picture>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											echo $webp_source; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										echo wp_get_attachment_image( (int) $image_id, 'medium_large', false, array( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image escapes internally.
											'alt'     => $alt,
											'loading' => 'lazy',
										) );
										if ( $webp_source ) {
											echo '</picture>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
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
 * Left: paragraphs, Right: benefit checklist or optional supporting image.
 * Reads content from the service's `intro` data in services.php.
 *
 * @param array $intro Intro data with 'eyebrow', 'title', 'intro_text', 'paragraphs', and 'benefits' keys.
 * @param array $image Optional supporting image data with 'id' and 'alt' keys. Skipped when empty.
 * @return string Service introduction section HTML.
 */
function hds_render_service_intro( array $intro, array $image = array() ): string {
	$has_image = ! empty( $image['id'] );
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
			<div class="service-intro__grid<?php echo $has_image ? ' service-intro__grid--has-image' : ''; ?>">
				<div class="service-intro__content">
					<?php foreach ( $intro['paragraphs'] as $paragraph ) : ?>
						<p class="service-intro__text"><?php echo esc_html( $paragraph ); ?></p>
					<?php endforeach; ?>
				</div>
				<?php if ( $has_image ) : ?>
					<div class="service-intro__media">
						<?php
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							(int) $image['id'],
							'hds-content',
							false,
							[
								'alt'     => $image['alt'] ?? '',
								'loading' => 'lazy',
							]
						);
						?>
					</div>
				<?php elseif ( ! empty( $intro['benefits'] ) ) : ?>
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
 * Render the "Wat valt onder deze dienst?" checklist section.
 *
 * Renders the service-specific checklist as a two-column card list.
 * The section is only rendered when valid checklist data is present;
 * empty checklists are skipped entirely.
 *
 * @param array  $checklist Array of { text: string } items.
 * @param string $eyebrow   Optional eyebrow label.
 * @return string Section HTML, or an empty string when no items exist.
 */
function hds_render_service_checklist( array $checklist, string $eyebrow = '' ): string {
	$items = array_values( array_filter( $checklist, fn( $item ) => ! empty( $item['text'] ) ) );
	if ( empty( $items ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="service-checklist" aria-labelledby="service-checklist-heading">
		<div class="container">
			<header class="service-checklist__header">
				<?php if ( $eyebrow ) : ?>
					<p class="service-checklist__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="service-checklist-heading" class="service-checklist__title"><?php esc_html_e( 'Wat valt onder deze dienst?', 'hds' ); ?></h2>
			</header>
			<ul class="service-checklist__list">
				<?php foreach ( $items as $item ) : ?>
					<li class="service-checklist__item">
						<span class="service-checklist__icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
						<span class="service-checklist__text"><?php echo esc_html( $item['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the "Voor wie is deze dienst?" audience section.
 *
 * Maps the service `industries` slugs to their Dutch labels via
 * hds_get_industry_data(). Unknown slugs are skipped. The section is
 * only rendered when at least one supported industry is present.
 *
 * @param array $industries Array of industry slugs.
 * @return string Section HTML, or an empty string when no supported industries exist.
 */
function hds_render_service_audience( array $industries ): string {
	$labels = [];
	foreach ( $industries as $slug ) {
		$label = hds_get_industry_label( $slug );
		if ( null !== $label ) {
			$labels[] = $label;
		}
	}
	$labels = array_values( array_unique( $labels ) );

	if ( empty( $labels ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="service-audience" aria-labelledby="service-audience-heading">
		<div class="container">
			<header class="service-audience__header">
				<p class="service-audience__eyebrow"><?php esc_html_e( 'Onze dienst', 'hds' ); ?></p>
				<h2 id="service-audience-heading" class="service-audience__title"><?php esc_html_e( 'Voor wie is deze dienst?', 'hds' ); ?></h2>
				<p class="service-audience__intro"><?php esc_html_e( 'Deze dienst is geschikt voor de volgende klanten en locaties.', 'hds' ); ?></p>
			</header>
			<ul class="service-audience__list">
				<?php foreach ( $labels as $label ) : ?>
					<li class="service-audience__item"><?php echo esc_html( $label ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the "Waarom Hamdoun?" strengths section for service pages.
 *
 * Renders the five canonical company strengths from
 * hds_get_why_hamdoun_strengths() as a clean card grid. This is a
 * service-page-only component and does not touch the homepage
 * hds_render_why_section() output.
 *
 * @param string $eyebrow Optional eyebrow label.
 * @return string Section HTML.
 */
function hds_render_why_hamdoun_section( string $eyebrow = '' ): string {
	$strengths = hds_get_why_hamdoun_strengths();
	if ( empty( $strengths ) ) {
		return '';
	}

	ob_start();
	?>
	<section class="service-why" aria-labelledby="service-why-heading">
		<div class="container">
			<header class="service-why__header">
				<?php if ( $eyebrow ) : ?>
					<p class="service-why__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="service-why-heading" class="service-why__title"><?php esc_html_e( 'Waarom Hamdoun?', 'hds' ); ?></h2>
			</header>
			<ul class="service-why__list">
				<?php foreach ( $strengths as $strength ) : ?>
					<li class="service-why__item">
						<span class="service-why__icon" aria-hidden="true">
							<svg width="22" height="22" viewBox="0 0 256 256" fill="none"><path d="M216 72l-104 104-72-72" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
						<span class="service-why__label"><?php echo esc_html( $strength ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the Visual Break section (large service image).
 *
 * Only rendered when an approved image attachment ID is supplied. When
 * no image exists the section is skipped entirely — no placeholder is
 * shown. Dedicated Visual Break images are expected to be added later.
 *
 * @param int    $image_id Attachment ID of the Visual Break image.
 * @param string $alt      Accessible alt text.
 * @return string Section HTML, or an empty string when no image is supplied.
 */
function hds_render_service_visual_break( int $image_id = 0, string $alt = '' ): string {
	if ( ! $image_id || ! wp_get_attachment_image_url( $image_id, 'full' ) ) {
		return '';
	}

	if ( '' === $alt ) {
		$alt = wp_get_attachment_caption( $image_id ) ?: '';
	}

	ob_start();
	?>
	<section class="service-visual-break" aria-hidden="false">
		<div class="service-visual-break__media">
			<?php
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				(int) $image_id,
				'full',
				false,
				[
					'alt'     => $alt,
					'loading' => 'lazy',
				]
			);
			?>
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
						loading="lazy"
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
