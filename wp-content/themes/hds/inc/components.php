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
 * @param string $heading     CTA heading text.
 * @param string $description CTA description (optional).
 * @param string $button_text Button label.
 * @param string $button_url  Button link URL.
 * @param string $style       'primary' (default) or 'secondary'.
 */
function hds_cta_section( string $heading, string $description = '', string $button_text = '', string $button_url = '', string $style = 'primary' ): string {
	if ( ! $button_text ) {
		$button_text = __( 'Offerte aanvragen', 'hds' );
	}
	if ( ! $button_url ) {
		$button_url = home_url( '/offerte-aanvragen/' );
	}

	$style_class = 'secondary' === $style ? 'cta-banner--secondary' : '';

	ob_start();
	?>
	<section class="cta-banner <?php echo esc_attr( $style_class ); ?>">
		<div class="container">
			<h2 class="cta-banner__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $description ) : ?>
				<p class="cta-banner__description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $button_url ); ?>" class="btn btn-cta">
				<?php echo esc_html( $button_text ); ?>
			</a>
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
	</header>
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
 * Inject Phosphor SVG icons into USP grid cards on the front page.
 *
 * All cards use the same code path — a single mapping array iterated
 * with str_replace. No per-card branching, no CSS pseudo-elements,
 * no browser-specific mask-image rendering.
 *
 * @param string $content Post content.
 * @return string Content with icons injected.
 */
function hds_inject_usp_icons( string $content ): string {
	if ( false === strpos( $content, 'usp-grid' ) ) {
		return $content;
	}

	$icons = [
		'Vast opgeleid personeel'           => '<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M84 80c0-24.3 19.7-44 44-44s44 19.7 44 44" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><path d="M40 208c0-48.6 39.4-88 88-88s88 39.4 88 88" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'Veiligheid &amp; Certificering'    => '<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M216 112c0 50.2-41.8 92-88 104-46.2-12-88-53.8-88-104V56l88-32 88 32v56Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><polyline points="88 136 112 160 168 104" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'Een aanspreekpunt'                 => '<svg class="is-style-card__icon" width="36" height="36" viewBox="0 0 256 256" fill="none" aria-hidden="true"><path d="M128 232a104 104 0 1 1 0-208c57.4 0 104 46.6 104 104 0 47.8-38 88.3-86 100.3L128 232Z" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/><line x1="96" y1="112" x2="160" y2="112" stroke="currentColor" stroke-width="12" stroke-linecap="round"/><line x1="96" y1="144" x2="128" y2="144" stroke="currentColor" stroke-width="12" stroke-linecap="round"/></svg>',
	];

	foreach ( $icons as $heading => $icon ) {
		$content = str_replace(
			'<h3 class="wp-block-heading">' . $heading . '</h3>',
			$icon . '<h3 class="wp-block-heading">' . $heading . '</h3>',
			$content
		);
	}

	return $content;
}
add_filter( 'the_content', 'hds_inject_usp_icons', 20 );

/**
 * Inject a supporting subtitle into the USP grid section.
 *
 * Adds a centered paragraph below the "Waarom HDS?" heading
 * using the existing section-header__subtitle typography token.
 *
 * @param string $content Post content.
 * @return string Content with subtitle injected.
 */
function hds_inject_usp_subtitle( string $content ): string {
	if ( false === strpos( $content, 'usp-grid' ) ) {
		return $content;
	}

	$subtitle = '<p class="section-header__subtitle has-text-align-center" style="margin-bottom:0">'
		. esc_html__( 'Daarom kiezen bedrijven in West-Brabant en Zeeland voor HDS als vaste schoonmaakpartner.', 'hds' )
		. '</p>';

	$needle  = '<h2 class="wp-block-heading has-text-align-center">Waarom HDS?</h2>';
	$content = str_replace( $needle, $needle . $subtitle, $content );

	return $content;
}
add_filter( 'the_content', 'hds_inject_usp_subtitle', 21 );
