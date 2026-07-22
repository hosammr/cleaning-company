<?php
/**
 * Template Name: Offerte Aanvragen
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="quote-page">
		<div class="container">
			<h1><?php the_title(); ?></h1>

			<div class="quote-intro">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
