<?php
/**
 * Template Name: Legal
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<article class="legal-page">
			<h1><?php the_title(); ?></h1>
			<div class="legal-content">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
			<p class="legal-updated">
				<?php
				printf(
					esc_html__( 'Laatst bijgewerkt: %s', 'hds' ),
					get_the_modified_date()
				);
				?>
			</p>
		</article>
	</div>
</main>

<?php
get_footer();
