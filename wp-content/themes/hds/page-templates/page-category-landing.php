<?php
/**
 * Template Name: Category Landing
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="category-landing-hero">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<div class="container">
		<div class="category-landing-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>

	<section class="cta-banner">
		<div class="container">
			<h2><?php esc_html_e( 'Vrijblijvende offerte aanvragen', 'hds' ); ?></h2>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php esc_html_e( 'Offerte aanvragen', 'hds' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
