<?php
/**
 * Default page template.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	if ( ! is_front_page() ) {
		hds_breadcrumbs();
	}
	?>

	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
