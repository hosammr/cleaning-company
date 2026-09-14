<?php
/**
 * Footer template part.
 *
 * 5-column grid: Branding, Diensten, Over HDS, Contact, Juridisch.
 * Bottom bar: copyright and social links.
 * All content loaded dynamically from Customizer + Nav Menus.
 * Fallback menus render when no nav menu is assigned to a theme location.
 *
 * @package HDS
 */
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid">

			<div class="footer-column">
				<h3 class="footer-heading"><?php bloginfo( 'name' ); ?></h3>
				<?php if ( has_custom_logo() ) : ?>
					<div class="site-logo" style="margin-bottom:var(--wp--preset--spacing--3)">
						<?php the_custom_logo(); ?>
					</div>
				<?php endif; ?>
				<p class="footer-contact__item" style="color:var(--wp--preset--color--light-gray);font-size:var(--wp--preset--font-size--s)">
					<?php esc_html_e( 'Professionele schoonmaakdiensten voor bedrijven en organisaties.', 'hds' ); ?>
				</p>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Diensten', 'hds' ); ?></h3>
				<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Diensten', 'hds' ); ?>">
				<?php
				wp_nav_menu( [
					'theme_location' => 'footer-services',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => 'hds_footer_services_fallback',
					'depth'          => 1,
				] );
				?>
				</nav>
			</div>

			<div class="footer-column">
<h3 class="footer-heading"><?php esc_html_e( 'Over Hamdoun Schoonmaak', 'hds' ); ?></h3>
			<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Over Hamdoun Schoonmaak', 'hds' ); ?>">
				<?php
				wp_nav_menu( [
					'theme_location' => 'footer-about',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => 'hds_footer_about_fallback',
					'depth'          => 1,
				] );
				?>
				</nav>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Contact', 'hds' ); ?></h3>
				<div class="footer-contact">
					<?php
					$contact_mobile = hds_get_phone();
					$contact_fixed  = hds_get_phone_secondary();
					$contact_email  = hds_get_email();

					$contact_mobile_display = hds_format_phone( $contact_mobile );
					$contact_fixed_display  = hds_format_phone( $contact_fixed );
					?>
					<div class="footer-contact__item footer-contact__item--mobile">
						<a href="<?php echo esc_url( 'tel:' . hds_get_phone_intl( $contact_mobile ) ); ?>" class="footer-contact__link">
							<span class="footer-contact__icon" aria-hidden="true">
								<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
							</span>
							<span class="footer-contact__content">
								<span class="footer-contact__label"><?php esc_html_e( 'Mobiel', 'hds' ); ?></span>
								<span class="footer-contact__value"><?php echo esc_html( $contact_mobile_display ); ?></span>
							</span>
						</a>
					</div>
					<div class="footer-contact__item footer-contact__item--fixed">
						<a href="<?php echo esc_url( 'tel:' . hds_get_phone_intl( $contact_fixed ) ); ?>" class="footer-contact__link">
							<span class="footer-contact__icon" aria-hidden="true">
								<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
							</span>
							<span class="footer-contact__content">
								<span class="footer-contact__label"><?php esc_html_e( 'Vast', 'hds' ); ?></span>
								<span class="footer-contact__value"><?php echo esc_html( $contact_fixed_display ); ?></span>
							</span>
						</a>
					</div>
					<div class="footer-contact__item footer-contact__item--email">
						<a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>" class="footer-contact__link">
							<span class="footer-contact__icon" aria-hidden="true">
								<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
							</span>
							<span class="footer-contact__content">
								<span class="footer-contact__label"><?php esc_html_e( 'E-mailadres', 'hds' ); ?></span>
								<span class="footer-contact__value"><?php echo esc_html( $contact_email ); ?></span>
							</span>
						</a>
					</div>
				</div>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Juridisch', 'hds' ); ?></h3>
				<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Juridisch', 'hds' ); ?>">
				<?php
				wp_nav_menu( [
					'theme_location' => 'footer-legal',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'fallback_cb'    => 'hds_footer_legal_fallback',
					'depth'          => 1,
				] );
				?>
				</nav>
			</div>

		</div>

		<div class="footer-bottom">
			<div class="footer-copyright">
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Alle rechten voorbehouden.', 'hds' ); ?></p>
			</div>

			<div class="footer-social">
				<?php
				$facebook  = get_theme_mod( 'hds_facebook_url' );
				$instagram = get_theme_mod( 'hds_instagram_url' );
				$tiktok    = get_theme_mod( 'hds_tiktok_url' );
				$gbp       = get_theme_mod( 'hds_gbp_url' );

				if ( $facebook ) : ?>
					<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social__link footer-social__link--facebook" aria-label="<?php esc_attr_e( 'Volg ons op Facebook', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( $instagram ) : ?>
					<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social__link footer-social__link--instagram" aria-label="<?php esc_attr_e( 'Volg ons op Instagram', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( $tiktok ) : ?>
					<a href="<?php echo esc_url( $tiktok ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social__link footer-social__link--tiktok" aria-label="<?php esc_attr_e( 'Volg ons op TikTok', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( $gbp ) : ?>
					<a href="<?php echo esc_url( $gbp ); ?>" target="_blank" rel="noopener noreferrer" class="footer-social__link footer-social__link--google" aria-label="<?php esc_attr_e( 'Bekijk ons op Google', 'hds' ); ?>">
						<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
