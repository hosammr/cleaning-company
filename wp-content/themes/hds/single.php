<?php
/**
 * Single post template.
 *
 * Used for blog posts (P30) and single vacancy views.
 * Vacancy posts render JobPosting schema metadata + application link.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();

			if ( 'hds_vacancy' === get_post_type() ) :
				?>
				<article <?php post_class( 'single-vacancy' ); ?> itemscope itemtype="https://schema.org/JobPosting">
					<header class="entry-header">
						<h1 class="entry-title" itemprop="title"><?php the_title(); ?></h1>

						<dl class="single-vacancy-meta">
							<?php
							$location = get_post_meta( get_the_ID(), 'hds_location', true );
							$hours    = get_post_meta( get_the_ID(), 'hds_hours_per_week', true );
							$deadline = get_post_meta( get_the_ID(), 'hds_deadline', true );

							if ( $hours ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Uren per week', 'hds' ); ?></dt>
									<dd><?php echo esc_html( $hours ); ?></dd>
								</div>
							<?php endif; ?>
							<?php if ( $location ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Locatie', 'hds' ); ?></dt>
									<dd><?php echo esc_html( $location ); ?></dd>
								</div>
							<?php endif; ?>
							<?php if ( $deadline ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Reageren voor', 'hds' ); ?></dt>
									<dd><?php echo esc_html( $deadline ); ?></dd>
								</div>
							<?php endif; ?>
						</dl>
					</header>

					<div class="entry-content" itemprop="description">
						<?php the_content(); ?>
					</div>

					<div class="single-vacancy-apply">
						<h2><?php esc_html_e( 'Solliciteren', 'hds' ); ?></h2>
						<p>
							<?php esc_html_e( 'Stuur uw motivatie en CV naar', 'hds' ); ?>
							<?php
							$apply_email = get_post_meta( get_the_ID(), 'hds_application_email', true ) ?: hds_get_email();
							echo hds_get_email_link( $apply_email, sprintf( __( 'Sollicitatie: %s', 'hds' ), get_the_title() ) );
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</p>
					</div>
				</article>
				<?php
			else :
				?>
				<article <?php post_class( 'single-post' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail">
							<?php the_post_thumbnail( 'large' ); ?>
						</div>
					<?php endif; ?>

					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>
						</div>
					</header>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php
					$categories = get_the_category();
					if ( $categories ) :
						?>
						<footer class="entry-footer">
							<p class="entry-categories">
								<?php esc_html_e( 'Categorie:', 'hds' ); ?>
								<?php the_category( ', ' ); ?>
							</p>
						</footer>
					<?php endif; ?>
				</article>
				<?php
			endif;
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
