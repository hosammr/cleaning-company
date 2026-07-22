<?php
/**
 * Footer template part.
 *
 * @package HDS
 */
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-grid">
			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Diensten', 'hds' ); ?></h3>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer-services',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					]
				);
				?>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Over HDS', 'hds' ); ?></h3>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer-about',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					]
				);
				?>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Contact', 'hds' ); ?></h3>
				<div class="footer-contact">
					<p>
						<a href="tel:0164-652846">0164-652846</a>
					</p>
					<p>
						<a href="mailto:info@helderduidelijkschoon.nl">info@helderduidelijkschoon.nl</a>
					</p>
					<?php
					$address = get_theme_mod( 'hds_address' );
					$postal_city = get_theme_mod( 'hds_postal_city' );
					if ( $address && $postal_city ) :
						?>
						<p><?php echo esc_html( $address ); ?><br><?php echo esc_html( $postal_city ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Luchtreiniging', 'hds' ); ?></h3>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer-airfixr',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					]
				);
				?>
			</div>

			<div class="footer-column">
				<h3 class="footer-heading"><?php esc_html_e( 'Juridisch', 'hds' ); ?></h3>
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer-legal',
						'menu_class'     => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					]
				);
				?>
			</div>
		</div>

		<div class="footer-bottom">
			<div class="footer-legal-info">
				<?php
				$kvk = get_theme_mod( 'hds_kvk' );
				$btw = get_theme_mod( 'hds_btw' );
				if ( $kvk ) {
					echo '<p>' . esc_html__( 'KVK:', 'hds' ) . ' ' . esc_html( $kvk ) . '</p>';
				}
				if ( $btw ) {
					echo '<p>' . esc_html__( 'BTW:', 'hds' ) . ' ' . esc_html( $btw ) . '</p>';
				}
				?>
			</div>

			<div class="footer-copyright">
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			</div>

			<div class="footer-social">
				<?php
				$facebook = get_theme_mod( 'hds_facebook_url' );
				$instagram = get_theme_mod( 'hds_instagram_url' );
				if ( $facebook ) :
					?>
					<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
						<span class="social-icon facebook-icon" aria-hidden="true"></span>
					</a>
				<?php endif; ?>
				<?php if ( $instagram ) : ?>
					<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
						<span class="social-icon instagram-icon" aria-hidden="true"></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
