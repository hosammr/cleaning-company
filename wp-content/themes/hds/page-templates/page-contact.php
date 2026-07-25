<?php
/**
 * Template Name: Contact
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="contact-page">
		<div class="container">
			<h1><?php the_title(); ?></h1>

			<div class="contact-layout">
				<div class="contact-form-column">
					<?php
					while ( have_posts() ) :
						the_post();
						the_content();
					endwhile;
					?>
				</div>

				<aside class="contact-info-column" role="complementary">
					<div class="contact-info-block">
						<h2><?php esc_html_e( 'Contactgegevens', 'hds' ); ?></h2>

						<div class="contact-info-item">
							<h3><?php esc_html_e( 'Telefoon', 'hds' ); ?></h3>
							<p>
								<?php echo hds_get_phone_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</p>
						</div>

						<div class="contact-info-item">
							<h3><?php esc_html_e( 'E-mail', 'hds' ); ?></h3>
							<p>
								<?php echo hds_get_email_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</p>
						</div>

						<?php if ( hds_get_address() && hds_get_postal_city() ) : ?>
							<div class="contact-info-item">
								<h3><?php esc_html_e( 'Adres', 'hds' ); ?></h3>
								<address>
									<?php echo esc_html( hds_get_address() ); ?><br>
									<?php echo esc_html( hds_get_postal_city() ); ?>
								</address>
							</div>
						<?php endif; ?>

						<?php
						$kvk = get_theme_mod( 'hds_kvk' );
						$btw = get_theme_mod( 'hds_btw' );
						if ( $kvk || $btw ) :
							?>
							<div class="contact-info-item">
								<?php if ( $kvk ) : ?>
									<p><strong><?php esc_html_e( 'KVK', 'hds' ); ?>:</strong> <?php echo esc_html( $kvk ); ?></p>
								<?php endif; ?>
								<?php if ( $btw ) : ?>
									<p><strong><?php esc_html_e( 'BTW', 'hds' ); ?>:</strong> <?php echo esc_html( $btw ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php
						$hours = get_theme_mod( 'hds_opening_hours' );
						$gbp   = get_theme_mod( 'hds_gbp_url' );
						if ( $hours ) :
							?>
							<div class="contact-info-item">
								<h3><?php esc_html_e( 'Openingstijden', 'hds' ); ?></h3>
								<p><?php echo nl2br( esc_html( $hours ) ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</aside>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
