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
			</div>

		</div>
	</div>
</header>
