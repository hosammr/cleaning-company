<?php
/**
 * Template Name: About
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="about-hero">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<div class="container">
		<div class="about-content">
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
			<h2><?php esc_html_e( 'Meer weten?', 'hds' ); ?></h2>
			<p><?php esc_html_e( 'Neem vrijblijvend contact met ons op.', 'hds' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-cta">
				<?php esc_html_e( 'Neem contact op', 'hds' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
