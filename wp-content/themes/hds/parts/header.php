<?php
/**
 * Header template part.
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

<a class="skip-link screen-reader-text" href="#main">
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
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Hoofdmenu', 'hds' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle-icon"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'hds' ); ?></span>
				</button>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => false,
					]
				);
				?>
			</nav>

			<div class="site-header-contact">
				<a href="tel:0164-652846" class="header-phone" aria-label="<?php esc_attr_e( 'Bel ons', 'hds' ); ?>">
					<span class="header-phone-icon" aria-hidden="true"></span>
					<span class="header-phone-number">0164-652846</span>
				</a>
			</div>
		</div>
	</div>
</header>
