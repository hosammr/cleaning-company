<?php
/**
 * Breadcrumbs template part.
 *
 * @package HDS
 */

if ( is_front_page() ) {
	return;
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Kruimelpad', 'hds' ); ?>">
	<ol itemscope itemtype="https://schema.org/BreadcrumbList">
		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="item">
				<span itemprop="name"><?php esc_html_e( 'Home', 'hds' ); ?></span>
			</a>
			<meta itemprop="position" content="1">
		</li>
		<?php if ( is_page() ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<span itemprop="name" aria-current="page"><?php the_title(); ?></span>
				<meta itemprop="position" content="2">
			</li>
		<?php elseif ( is_single() ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<span itemprop="name" aria-current="page"><?php the_title(); ?></span>
				<meta itemprop="position" content="2">
			</li>
		<?php elseif ( is_search() ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<span itemprop="name" aria-current="page"><?php esc_html_e( 'Zoekresultaten', 'hds' ); ?></span>
				<meta itemprop="position" content="2">
			</li>
		<?php elseif ( is_404() ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<span itemprop="name" aria-current="page"><?php esc_html_e( 'Pagina niet gevonden', 'hds' ); ?></span>
				<meta itemprop="position" content="2">
			</li>
		<?php endif; ?>
	</ol>
</nav>
