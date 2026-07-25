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
							$start    = get_post_meta( get_the_ID(), 'hds_start_date', true );

							if ( $hours ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Uren per week', 'hds' ); ?></dt>
									<dd itemprop="workHours"><?php echo esc_html( $hours ); ?></dd>
								</div>
							<?php endif; ?>
							<?php if ( $location ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Locatie', 'hds' ); ?></dt>
									<dd itemprop="jobLocation" itemscope itemtype="https://schema.org/Place">
										<span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
											<span itemprop="addressLocality"><?php echo esc_html( $location ); ?></span>
											<meta itemprop="addressCountry" content="NL">
										</span>
									</dd>
								</div>
							<?php endif; ?>
							<?php if ( $deadline ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Reageren voor', 'hds' ); ?></dt>
									<dd><meta itemprop="validThrough" content="<?php echo esc_attr( $deadline ); ?>"><?php echo esc_html( $deadline ); ?></dd>
								</div>
							<?php endif; ?>
							<?php if ( $start ) : ?>
								<div class="single-vacancy-meta__item">
									<dt><?php esc_html_e( 'Startdatum', 'hds' ); ?></dt>
									<dd><?php echo esc_html( $start ); ?></dd>
								</div>
							<?php endif; ?>
						</dl>
						<meta itemprop="datePosted" content="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<meta itemprop="employmentType" content="PART_TIME">
						<div itemprop="hiringOrganization" itemscope itemtype="https://schema.org/Organization">
							<meta itemprop="name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							<meta itemprop="url" content="<?php echo esc_url( home_url() ); ?>">
						</div>
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
