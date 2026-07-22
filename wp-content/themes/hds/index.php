<?php
/**
 * Main template fallback.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'entry' ); ?>>
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;
		else :
			?>
			<p><?php esc_html_e( 'Geen inhoud gevonden.', 'hds' ); ?></p>
			<?php
		endif;
		?>
	</div>
</main>

<?php
get_footer();
