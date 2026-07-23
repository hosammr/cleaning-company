<?php
/**
 * Breadcrumbs template part.
 *
 * Renders BreadcrumbList with Schema.org microdata.
 * Supports: Pages, Services (hierarchical), Blog posts, Archive, Search, 404,
 *           WooCommerce (shop, product, category), CPT (vacancy single).
 *
 * @package HDS
 */

if ( is_front_page() || is_404() ) {
	return;
}

$items = [];

// Home
$items[] = [
	'name' => __( 'Home', 'hds' ),
	'url'  => home_url( '/' ),
];

// Blog archive
$blog_page_id = get_option( 'page_for_posts' );
$show_on_front = get_option( 'show_on_front' );

if ( is_home() ) {
	$items[] = [
		'name' => __( 'Kennisbank', 'hds' ),
		'url'  => '',
	];
} elseif ( is_single() && get_post_type() === 'post' ) {
	if ( 'page' === $show_on_front && $blog_page_id ) {
		$items[] = [
			'name' => get_the_title( $blog_page_id ),
			'url'  => get_permalink( $blog_page_id ),
		];
	} else {
		$items[] = [
			'name' => __( 'Kennisbank', 'hds' ),
			'url'  => home_url( '/kennisbank/' ),
		];
	}
	$items[] = [
		'name' => get_the_title(),
		'url'  => '',
	];
} elseif ( is_single() && get_post_type() === 'hds_vacancy' ) {
	$items[] = [
		'name' => __( 'Vacatures', 'hds' ),
		'url'  => home_url( '/vacatures/' ),
	];
	$items[] = [
		'name' => get_the_title(),
		'url'  => '',
	];
} elseif ( is_category() || is_tag() || is_tax() ) {
	$term = get_queried_object();
	if ( is_category() && $blog_page_id ) {
		$items[] = [
			'name' => get_the_title( $blog_page_id ),
			'url'  => get_permalink( $blog_page_id ),
		];
	}
	if ( $term instanceof \WP_Term && $term->parent ) {
		$ancestors = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
		foreach ( array_reverse( $ancestors ) as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $term->taxonomy );
			$items[] = [
				'name' => $ancestor->name,
				'url'  => get_term_link( $ancestor ),
			];
		}
	}
	$items[] = [
		'name' => single_term_title( '', false ),
		'url'  => '',
	];
} elseif ( is_post_type_archive() ) {
	$items[] = [
		'name' => post_type_archive_title( '', false ),
		'url'  => '',
	];
} elseif ( is_page() ) {
	global $post;
	$ancestors = $post->ancestors ?? [];
	if ( $ancestors ) {
		foreach ( $ancestors as $ancestor_id ) {
			$items[] = [
				'name' => get_the_title( $ancestor_id ),
				'url'  => get_permalink( $ancestor_id ),
			];
		}
	}
	$breadcrumb_title = (int) $blog_page_id === (int) get_the_ID() ? __( 'Kennisbank', 'hds' ) : get_the_title();
	$items[] = [
		'name' => $breadcrumb_title,
		'url'  => '',
	];
} elseif ( is_archive() ) {
	$items[] = [
		'name' => get_the_archive_title(),
		'url'  => '',
	];
} elseif ( is_search() ) {
	$items[] = [
		'name' => __( 'Zoekresultaten', 'hds' ),
		'url'  => '',
	];
} elseif ( is_404() ) {
	$items[] = [
		'name' => __( 'Pagina niet gevonden', 'hds' ),
		'url'  => '',
	];
}

// WooCommerce support
if ( function_exists( 'is_woocommerce' ) ) {
	if ( is_shop() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$items = [
			[ 'name' => __( 'Home', 'hds' ), 'url' => home_url( '/' ) ],
			[ 'name' => get_the_title( $shop_page_id ), 'url' => '' ],
		];
	} elseif ( is_product_category() || is_product_tag() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$items = [
			[ 'name' => __( 'Home', 'hds' ), 'url' => home_url( '/' ) ],
			[ 'name' => get_the_title( $shop_page_id ), 'url' => get_permalink( $shop_page_id ) ],
		];
		$term = get_queried_object();
		if ( $term instanceof \WP_Term ) {
			$items[] = [ 'name' => $term->name, 'url' => '' ];
		}
	} elseif ( is_product() ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		$items = [
			[ 'name' => __( 'Home', 'hds' ), 'url' => home_url( '/' ) ],
			[ 'name' => get_the_title( $shop_page_id ), 'url' => get_permalink( $shop_page_id ) ],
		];
		$terms = get_the_terms( get_the_ID(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$items[] = [ 'name' => $terms[0]->name, 'url' => get_term_link( $terms[0] ) ];
		}
		$items[] = [ 'name' => get_the_title(), 'url' => '' ];
	}
}

if ( count( $items ) <= 1 ) {
	return;
}
?>

<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Kruimelpad', 'hds' ); ?>">
	<ol itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php foreach ( $items as $index => $item ) : ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<?php if ( $item['url'] ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>" itemprop="item">
						<span itemprop="name"><?php echo esc_html( $item['name'] ); ?></span>
					</a>
				<?php else : ?>
					<span itemprop="name" aria-current="page"><?php echo esc_html( $item['name'] ); ?></span>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo esc_attr( (string) ( $index + 1 ) ); ?>">
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
