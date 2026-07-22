<?php
/**
 * 404 template.
 *
 * @package HDS
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">
		<div class="error-404">
			<h1><?php esc_html_e( 'Pagina niet gevonden', 'hds' ); ?></h1>
			<p><?php esc_html_e( 'De pagina die u zoekt bestaat niet of is verplaatst.', 'hds' ); ?></p>
			<div class="error-404-search">
				<?php get_search_form(); ?>
			</div>
			<div class="error-404-links">
				<h2><?php esc_html_e( 'Mogelijk bent u op zoek naar:', 'hds' ); ?></h2>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'hds' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/schoonmaakdiensten/' ) ); ?>"><?php esc_html_e( 'Schoonmaakdiensten', 'hds' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'hds' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/offerte-aanvragen/' ) ); ?>"><?php esc_html_e( 'Offerte Aanvragen', 'hds' ); ?></a></li>
				</ul>
			</div>
			<div class="error-404-contact">
				<p>
					<?php esc_html_e( 'Of neem direct contact op:', 'hds' ); ?><br>
					<a href="tel:0164-652846">0164-652846</a><br>
					<a href="mailto:info@helderduidelijkschoon.nl">info@helderduidelijkschoon.nl</a>
				</p>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
