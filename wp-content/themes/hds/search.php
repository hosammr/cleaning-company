<?php
/**
 * Search results template.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">
		<h1>
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Zoekresultaten voor: %s', 'hds' ),
				'<span>' . get_search_query() . '</span>'
			);
			?>
		</h1>

		<?php if ( have_posts() ) : ?>
			<div class="search-results">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'search-result-item' ); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="search-result-excerpt">
							<?php the_excerpt(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="search-result-link">
							<?php esc_html_e( 'Lees meer', 'hds' ); ?>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="no-results"><?php esc_html_e( 'Geen resultaten gevonden. Probeer een andere zoekterm.', 'hds' ); ?></p>
			<div class="search-again">
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
