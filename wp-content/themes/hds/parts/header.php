<?php
/**
 * Header template part.
 *
 * DOCTYPE → <head> → skip-link → <header> with logo, primary nav, CTA.
 * Mobile navigation handled via JS (asset/js/main.js).
 *
 * @package HDS
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">
	<?php esc_html_e( 'Direct naar inhoud', 'hds' ); ?>
</a>

<header id="masthead" class="site-header" role="banner">
	<div class="container">
		<div class="site-header-inner">

			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<p class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — <?php esc_attr_e( 'Home', 'hds' ); ?>">
							<?php bloginfo( 'name' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Hoofdmenu', 'hds' ); ?>">

				<button
					class="menu-toggle"
					aria-controls="primary-menu"
					aria-expanded="false"
					aria-haspopup="true"
					data-open-text="<?php esc_attr_e( 'Menu openen', 'hds' ); ?>"
					data-close-text="<?php esc_attr_e( 'Menu sluiten', 'hds' ); ?>"
				>
					<span class="menu-toggle__icon" aria-hidden="true">
						<span class="menu-toggle__bar"></span>
						<span class="menu-toggle__bar"></span>
						<span class="menu-toggle__bar"></span>
					</span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu openen', 'hds' ); ?></span>
				</button>

				<?php
				wp_nav_menu( [
					'theme_location'  => 'primary',
					'menu_id'         => 'primary-menu',
					'menu_class'      => 'primary-menu',
					'container'       => false,
					'fallback_cb'     => false,
					'depth'           => 3,
					'walker'          => new HDS_Walker_Nav_Menu(),
				] );
				?>
			</nav>

			<div class="site-header-actions">
				<a href="tel:<?php echo esc_attr( hds_esc_tel( hds_get_phone() ) ); ?>" class="header-phone" aria-label="<?php echo esc_attr( sprintf( __( 'Bel ons op %s', 'hds' ), hds_get_phone() ) ); ?>">
					<span class="header-phone__icon" aria-hidden="true"></span>
					<span class="header-phone__number"><?php echo esc_html( hds_get_phone() ); ?></span>
				</a>

				<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn--cta header-cta">
					<?php esc_html_e( 'Offerte', 'hds' ); ?>
				</a>

				<button
					type="button"
					class="header-search-toggle"
					id="hds-header-search-toggle"
					aria-expanded="false"
					aria-controls="hds-header-search-panel"
					aria-label="<?php esc_attr_e( 'Zoeken', 'hds' ); ?>"
				>
					<svg class="header-search-toggle__icon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
				</button>
			</div>

		</div>

		<div class="header-search-panel" id="hds-header-search-panel" hidden>
			<div class="container">
				<form role="search" method="get" class="hds-search-form hds-search-form--header" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label class="hds-search-form__label" for="hds-header-search-input">
						<span class="screen-reader-text"><?php esc_html_e( 'Zoeken naar:', 'hds' ); ?></span>
					</label>
					<div class="hds-search-form__wrapper">
						<input
							type="search"
							id="hds-header-search-input"
							class="hds-search-form__input"
							name="s"
							value="<?php echo get_search_query(); ?>"
							placeholder="<?php esc_attr_e( 'Zoeken...', 'hds' ); ?>"
							required
							aria-label="<?php esc_attr_e( 'Zoeken op de website', 'hds' ); ?>"
						>
						<button type="submit" class="hds-search-form__submit" aria-label="<?php esc_attr_e( 'Zoek', 'hds' ); ?>">
							<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
						</button>
					</div>
					<button type="button" class="header-search-panel__close" id="hds-header-search-close" aria-label="<?php esc_attr_e( 'Zoeken sluiten', 'hds' ); ?>" aria-controls="hds-header-search-panel">
						<?php esc_html_e( 'Sluiten', 'hds' ); ?>
					</button>
				</form>
			</div>
		</div>

	</div>
</header>
