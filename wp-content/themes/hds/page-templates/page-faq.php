<?php
/**
 * Template Name: FAQ
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<h1><?php the_title(); ?></h1>
		<div class="faq-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>
</main>

<?php
get_footer();
