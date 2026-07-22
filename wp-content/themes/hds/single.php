<?php
/**
 * Single post template.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<?php hds_breadcrumbs(); ?>

	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'single-post' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<div class="entry-meta">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</div>
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
