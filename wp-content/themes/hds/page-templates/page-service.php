<?php
/**
 * Template Name: Service
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<?php
	$subtitle = get_post_meta( get_the_ID(), 'hds_subtitle', true );
	$hero_image = get_post_meta( get_the_ID(), 'hds_hero_image', true );
	$cta_override = get_post_meta( get_the_ID(), 'hds_cta_override', true );
	$cta_text = $cta_override ?: __( 'Vrijblijvende offerte', 'hds' );
	?>

	<section class="service-hero"<?php echo $hero_image ? ' style="background-image:url(' . esc_url( $hero_image ) . ')"' : ''; ?>>
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<?php if ( $subtitle ) : ?>
				<p class="service-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php echo esc_html( $cta_text ); ?>
			</a>
		</div>
	</section>

	<div class="container">
		<div class="service-content">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>

	<section class="service-cross-sell">
		<?php echo hds_render_cross_sell_section(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</section>

	<section class="cta-banner">
		<div class="container">
			<h2><?php esc_html_e( 'Vrijblijvende offerte aanvragen', 'hds' ); ?></h2>
			<p><?php esc_html_e( 'Wij denken graag met u mee over de beste oplossing.', 'hds' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>" class="btn btn-cta">
				<?php esc_html_e( 'Offerte aanvragen', 'hds' ); ?>
			</a>
		</div>
	</section>
</main>

<?php
get_footer();
