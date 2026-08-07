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
 * @param string   $style         'primary' (default) or 'secondary'.
 * @param string[] $trust_bullets Optional list of trust bullet texts below the button.
 */
function hds_cta_section( string $heading, string $description = '', string $button_text = '', string $button_url = '', string $style = 'primary', array $trust_bullets = [] ): string {
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
 * Render the homepage USP section.
 *
 * @return string Complete USP section HTML.
 */
function hds_render_usp_section(): string {
	$usp_items = [
		[
			'title'       => __( 'Vast opgeleid personeel', 'hds' ),
			'description' => __( 'Onze medewerkers zijn in vaste dienst en volledig opgeleid.', 'hds' ),
		],
		[
			'title'       => __( 'Veiligheid & Certificering', 'hds' ),
			'description' => __( 'OSB-gecertificeerd. Wij werken volgens de hoogste veiligheidsnormen.', 'hds' ),
		],
		[
			'title'       => __( 'Een aanspreekpunt', 'hds' ),
			'description' => __( 'U heeft altijd één vast aanspreekpunt voor al uw vragen.', 'hds' ),
		],
	];

	return hds_render_usp_grid(
		$usp_items,
		__( 'Waarom HDS?', 'hds' ),
		__( 'Daarom kiezen bedrijven in West-Brabant en Zeeland voor HDS als vaste schoonmaakpartner.', 'hds' )
	);
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
					</section>
	<?php
	return ob_get_clean();
}

/**
 * Render the Service Introduction section.
 *
 * Two-column layout: heading + paragraphs on the left, benefit checklist on the right.
 * Reads content from the service's `intro` data in services.php.
 *
 * @param array $intro Intro data with 'title', 'paragraphs', and 'benefits' keys.
 * @return string Service introduction section HTML.
 */
function hds_render_service_intro( array $intro ): string {
	ob_start();
	?>
	<section class="service-intro">
		<div class="container">
			<div class="service-intro__grid">
				<div class="service-intro__content">
					<h2 class="service-intro__title"><?php echo esc_html( $intro['title'] ); ?></h2>
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
