<?php
/**
 * Blog posts page template.
 *
 * Displays the blog/posts index when a static front page is set.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<h1 class="page-title"><?php esc_html_e( 'Kennisbank', 'hds' ); ?></h1>

		<?php if ( have_posts() ) : ?>
			<div class="archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'archive-item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="archive-item-image" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
							</a>
						<?php endif; ?>
						<h2 class="archive-item-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="archive-item-meta">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>
						</div>
						<div class="archive-item-excerpt">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="archive-item-link">
							<?php esc_html_e( 'Lees meer', 'hds' ); ?>
							<span class="screen-reader-text">: <?php the_title(); ?></span>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php the_posts_pagination( [ 'class' => 'hds-pagination' ] ); ?>

		<?php else : ?>
			<p><?php esc_html_e( 'Er zijn nog geen berichten.', 'hds' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
