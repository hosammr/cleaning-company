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

	<?php
	$current_slug = get_post_field( 'post_name', get_the_ID() );
	$groups       = hds_get_service_page_groups();
	$group_posts  = $groups[ $current_slug ] ?? array();

	if ( ! empty( $group_posts ) ) {
		echo hds_render_service_card_grid( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$group_posts,
			__( 'Onze diensten', 'hds' ),
			'',
			3
		);
	}
	?>

	<?php
	echo hds_cta_section( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		__( 'Vrijblijvende offerte aanvragen', 'hds' ),
		'',
		__( 'Offerte aanvragen', 'hds' ),
		home_url( '/offerte-aanvragen/' )
	);
	?>
</main>

<?php
get_footer();
