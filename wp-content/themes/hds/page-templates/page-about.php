<?php
/**
 * Template Name: About
 *
 * Used for Over HDS (P11) and Kwaliteit & Veiligheid (P12).
 * Layout: Hero → Content (the_content) → CTA Banner.
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

	<?php
	$cta_text = sprintf(
		__( 'Wilt u meer weten over %s?', 'hds' ),
		mb_strtolower( get_the_title() )
	);
	echo hds_cta_section(
		$cta_text,
		__( 'Neem vrijblijvend contact met ons op voor een persoonlijk gesprek.', 'hds' ),
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();
