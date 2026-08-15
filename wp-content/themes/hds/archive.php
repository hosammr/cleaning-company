<?php
/**
 * Archive template.
 *
 * Used for blog index (P29) and vacancy archive.
 * Vacancies render using the job-listing card layout.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<h1><?php the_archive_title(); ?></h1>

		<?php if ( have_posts() ) : ?>

			<?php if ( is_post_type_archive( 'hds_vacancy' ) ) : ?>

				<div class="vacancy-archive">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="hds-vacancy-card" itemscope itemtype="https://schema.org/JobPosting">
							<h2 class="hds-vacancy-card__title" itemprop="title">
								<?php the_title(); ?>
							</h2>

							<dl class="hds-vacancy-card__meta">
								<?php
								$location = get_post_meta( get_the_ID(), 'hds_location', true );
								$hours    = get_post_meta( get_the_ID(), 'hds_hours_per_week', true );
								$deadline = get_post_meta( get_the_ID(), 'hds_deadline', true );

								if ( $hours ) : ?>
									<div class="hds-vacancy-card__meta-item">
										<dt><?php esc_html_e( 'Uren', 'hds' ); ?></dt>
										<dd><?php echo esc_html( $hours ); ?> <?php esc_html_e( 'uur per week', 'hds' ); ?></dd>
									</div>
								<?php endif; ?>
								<?php if ( $location ) : ?>
									<div class="hds-vacancy-card__meta-item">
										<dt><?php esc_html_e( 'Locatie', 'hds' ); ?></dt>
										<dd><?php echo esc_html( $location ); ?></dd>
									</div>
								<?php endif; ?>
								<?php if ( $deadline ) : ?>
									<div class="hds-vacancy-card__meta-item">
										<dt><?php esc_html_e( 'Sluitingsdatum', 'hds' ); ?></dt>
										<dd><?php echo esc_html( $deadline ); ?></dd>
									</div>
								<?php endif; ?>
							</dl>

							<div class="hds-vacancy-card__content">
								<?php the_content(); ?>
							</div>

							<?php
							$apply_url = add_query_arg(
								[
									'type'     => 'sollicitatie',
									'vacature' => get_the_title(),
								],
								home_url( '/contact/' )
							);
							?>
							<a href="<?php echo esc_url( $apply_url ); ?>" class="btn btn--primary hds-vacancy-card__apply">
								<?php esc_html_e( 'Solliciteer nu', 'hds' ); ?>
							</a>
						</article>
						<?php
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<div class="archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article <?php post_class( 'archive-item' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="archive-item-image" tabindex="-1" aria-hidden="true">
									<?php the_post_thumbnail( 'medium' ); ?>
								</a>
							<?php endif; ?>
							<h2 class="archive-item-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<p class="archive-item-meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</p>
							<div class="archive-item-excerpt">
								<?php the_excerpt(); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="archive-item-link">
								<?php esc_html_e( 'Lees meer', 'hds' ); ?>
							</a>
						</article>
						<?php
					endwhile;
					?>
				</div>
				<?php the_posts_pagination(); ?>

			<?php endif; ?>

		<?php else : ?>
			<p><?php esc_html_e( 'Geen berichten gevonden.', 'hds' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
