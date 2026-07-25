<?php
/**
 * Template Name: Luchtreiniging Landing
 *
 * Dedicated landing page for the Airfixr product line (P23).
 * Layout: Hero → Intro content → Featured products → CTA (shop link).
 *
 * MPS-001 G2.5: Introduces Airfixr, explains connection to cleaning services,
 * highlights key products, links to /winkel/.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<section class="luchtreiniging-hero">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<div class="container">
		<div class="luchtreiniging-intro">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>

		<?php if ( HDS_Config::is_enabled( 'woocommerce_integration' ) ) : ?>
			<section class="luchtreiniging-featured">
				<h2><?php esc_html_e( 'Uitgelichte producten', 'hds' ); ?></h2>
				<?php
				echo do_shortcode( '[products limit="4" columns="4" orderby="date" order="DESC"]' );
				?>
			</section>
		<?php endif; ?>
	</div>

	<?php
	$shop_url = function_exists( 'wc_get_page_permalink' )
		? wc_get_page_permalink( 'shop' )
		: home_url( '/winkel/' );
	echo hds_cta_section(
		__( 'Bekijk alle Airfixr producten', 'hds' ),
		__( 'Ontdek ons volledige assortiment luchtreinigingsproducten in de webshop.', 'hds' ),
		__( 'Naar de winkel', 'hds' ),
		$shop_url
	);
	?>
</main>

<?php
get_footer();
