<?php
/**
 * Archive template.
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
			<div class="archive-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'archive-item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="archive-item-image">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						<?php endif; ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
		<?php else : ?>
			<p><?php esc_html_e( 'Geen berichten gevonden.', 'hds' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
