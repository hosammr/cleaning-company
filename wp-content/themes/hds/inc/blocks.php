<?php
/**
 * Custom block registration.
 *
 * Four custom blocks that query dynamic data via server-side render:
 *   hds/service-card   — renders a single service card from a Page
 *   hds/testimonial    — renders testimonial cards from hds_testimonial CPT
 *   hds/job-listing    — renders job vacancy cards from hds_vacancy CPT
 *   hds/contact-info   — renders company info from Theme Customizer
 *
 * All blocks use render_callback (dynamic). No save() function.
 * Block editor scripts in assets/js/blocks/.
 * Block metadata (title, icon, description, attributes) is
 * defined in register_block_type() for core compatibility.
 *
 * @package HDS
 */

/**
 * Register all custom HDS blocks.
 */
function hds_register_custom_blocks(): void {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	$blocks_dir = HDS_DIR . '/assets/js/blocks';
	$blocks_uri = HDS_URI . '/assets/js/blocks';

	$blocks = [
		'service-card' => [
			'render'    => 'hds_render_service_card',
			'title'     => __( 'HDS Service Card', 'hds' ),
			'desc'      => __( 'Toon een service kaart met icoon, titel, excerpt en link.', 'hds' ),
			'icon'      => 'admin-page',
			'category'  => 'hds-service',
		],
		'testimonial'  => [
			'render'    => 'hds_render_testimonial',
			'title'     => __( 'HDS Referenties', 'hds' ),
			'desc'      => __( 'Toon referenties van klanten uit het hds_testimonial CPT.', 'hds' ),
			'icon'      => 'format-quote',
			'category'  => 'hds-service',
		],
		'job-listing'  => [
			'render'    => 'hds_render_job_listing',
			'title'     => __( 'HDS Vacatures', 'hds' ),
			'desc'      => __( 'Toon openstaande vacatures met details en solliciteerknop.', 'hds' ),
			'icon'      => 'businessperson',
			'category'  => 'hds-service',
		],
		'contact-info' => [
			'render'    => 'hds_render_contact_info',
			'title'     => __( 'HDS Contactgegevens', 'hds' ),
			'desc'      => __( 'Toon bedrijfsgegevens uit de Customizer (telefoon, e-mail, adres, KVK, BTW).', 'hds' ),
			'icon'      => 'phone',
			'category'  => 'hds-content',
		],
	];

	foreach ( $blocks as $name => $config ) {
		$handle = 'hds-' . $name;

		wp_register_script(
			$handle,
			$blocks_uri . '/' . $name . '.js',
			[ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-server-side-render' ],
			HDS_VERSION,
			true
		);

		register_block_type( 'hds/' . $name, [
			'editor_script'   => $handle,
			'render_callback' => $config['render'],
			'attributes'      => hds_get_block_attributes( $name ),
		] );
	}
}
add_action( 'init', 'hds_register_custom_blocks' );

/**
 * Get block attribute definitions.
 */
function hds_get_block_attributes( string $block ): array {
	$attributes = [
		'service-card' => [
			'pageId'    => [ 'type' => 'integer', 'default' => 0 ],
			'showImage' => [ 'type' => 'boolean', 'default' => false ],
		],
		'testimonial' => [
			'count'         => [ 'type' => 'integer', 'default' => 3 ],
			'showRating'    => [ 'type' => 'boolean', 'default' => true ],
			'selectedItems' => [ 'type' => 'array',   'default' => [] ],
		],
		'job-listing' => [
			'count'   => [ 'type' => 'integer', 'default' => 5 ],
			'showAll' => [ 'type' => 'boolean', 'default' => true ],
		],
		'contact-info' => [
			'showPhone'   => [ 'type' => 'boolean', 'default' => true ],
			'showEmail'   => [ 'type' => 'boolean', 'default' => true ],
			'showAddress' => [ 'type' => 'boolean', 'default' => true ],
			'showKVK'     => [ 'type' => 'boolean', 'default' => true ],
			'showHours'   => [ 'type' => 'boolean', 'default' => false ],
			'showSocial'  => [ 'type' => 'boolean', 'default' => false ],
		],
	];

	return $attributes[ $block ] ?? [];
}

/* ── Render callbacks ── */

/**
 * Render hds/service-card block.
 */
function hds_render_service_card( array $attributes, string $content, \WP_Block $block ): string {
	$page_id = $attributes['pageId'] ?? 0;
	if ( ! $page_id ) {
		return '<p class="hds-block-empty">' . esc_html__( 'Selecteer een service pagina in de blokinstellingen.', 'hds' ) . '</p>';
	}

	$post = get_post( $page_id );
	if ( ! $post || $post->post_type !== 'page' ) {
		return '';
	}

	$show_image = (bool) ( $attributes['showImage'] ?? false );

	return hds_render_service_card_core( $post, $show_image, true );
}

/**
 * Render hds/testimonial block.
 */
function hds_render_testimonial( array $attributes, string $content, \WP_Block $block ): string {
	$count = $attributes['count'] ?? 3;

	$args = [
		'post_type'      => 'hds_testimonial',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	$selected = $attributes['selectedItems'] ?? [];
	if ( ! empty( $selected ) ) {
		$args['post__in']       = array_map( 'intval', $selected );
		$args['orderby']        = 'post__in';
		$args['posts_per_page'] = count( $selected );
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return '<div class="hds-testimonial-empty">'
			. esc_html__( 'Wij horen graag uw ervaring! Deel uw review.', 'hds' )
			. '</div>';
	}

	ob_start();
	?>
	<div class="hds-testimonial-grid">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<blockquote class="hds-testimonial-card">
				<p class="hds-testimonial-card__quote">
					&ldquo;<?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?>&rdquo;
				</p>
				<footer class="hds-testimonial-card__footer">
					<cite class="hds-testimonial-card__author">
						<?php echo esc_html( get_post_meta( get_the_ID(), 'hds_author_name', true ) ?: get_the_title() ); ?>
					</cite>
					<?php
					$company = get_post_meta( get_the_ID(), 'hds_company_name', true );
					if ( $company ) : ?>
						<span class="hds-testimonial-card__company"><?php echo esc_html( $company ); ?></span>
					<?php endif; ?>
					<?php if ( $attributes['showRating'] ?? true ) : ?>
						<div class="hds-testimonial-card__rating" aria-label="<?php printf( esc_attr__( '%d van 5 sterren', 'hds' ), (int) get_post_meta( get_the_ID(), 'hds_star_rating', true ) ); ?>">
							<?php
							$rating = (int) get_post_meta( get_the_ID(), 'hds_star_rating', true );
							for ( $i = 1; $i <= 5; $i++ ) {
								echo $i <= $rating ? '&#9733;' : '&#9734;';
							}
							?>
						</div>
					<?php endif; ?>
				</footer>
			</blockquote>
		<?php endwhile; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}

/**
 * Render hds/job-listing block.
 */
function hds_render_job_listing( array $attributes, string $content, \WP_Block $block ): string {
	$count = $attributes['count'] ?? 5;

	$args = [
		'post_type'      => 'hds_vacancy',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => [
			[
				'key'     => 'hds_is_active',
				'value'   => '1',
				'compare' => '=',
			],
		],
	];

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		if ( $attributes['showAll'] ?? true ) {
			return '<p>' . esc_html__( 'Er zijn momenteel geen openstaande vacatures.', 'hds' ) . '</p>';
		}
		return '';
	}

	ob_start();
	?>
	<div class="hds-vacancy-list">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<article class="hds-vacancy-card" itemscope itemtype="https://schema.org/JobPosting">
				<h3 class="hds-vacancy-card__title" itemprop="title">
					<?php the_title(); ?>
				</h3>

				<dl class="hds-vacancy-card__meta">
					<?php
					$location = get_post_meta( get_the_ID(), 'hds_location', true );
					$hours    = get_post_meta( get_the_ID(), 'hds_hours_per_week', true );
					$deadline = get_post_meta( get_the_ID(), 'hds_deadline', true );

					if ( $hours ) : ?>
						<div class="hds-vacancy-card__meta-item">
							<dt><?php esc_html_e( 'Uren', 'hds' ); ?></dt>
							<dd><?php echo esc_html( $hours ); ?> <?php esc_html_e( 'uur per week', 'hds' ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<div class="hds-vacancy-card__meta-item">
							<dt><?php esc_html_e( 'Locatie', 'hds' ); ?></dt>
							<dd><?php echo esc_html( $location ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $deadline ) : ?>
						<div class="hds-vacancy-card__meta-item">
							<dt><?php esc_html_e( 'Sluitingsdatum', 'hds' ); ?></dt>
							<dd><?php echo esc_html( $deadline ); ?></dd>
						</div>
					<?php endif; ?>
				</dl>

				<?php
				$content      = get_the_content();
				$intro        = wp_trim_words( wp_strip_all_tags( $content ), 35, '...' );
				?>
				<p class="hds-vacancy-card__intro"><?php echo esc_html( $intro ); ?></p>
				<details class="hds-vacancy-card__details">
					<summary class="hds-vacancy-card__toggle">
						<span class="hds-vacancy-card__toggle-more"><?php esc_html_e( 'Lees meer', 'hds' ); ?></span>
					</summary>
					<div class="hds-vacancy-card__content">
						<?php echo apply_filters( 'the_content', $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<button type="button" class="hds-vacancy-card__toggle-less" onclick="this.closest('details').removeAttribute('open')"><?php esc_html_e( 'Lees minder', 'hds' ); ?></button>
				</details>

				<?php
				$email = get_post_meta( get_the_ID(), 'hds_application_email', true ) ?: hds_get_email();
				?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>?subject=<?php echo esc_attr( sprintf( __( 'Sollicitatie: %s', 'hds' ), get_the_title() ) ); ?>" class="btn hds-vacancy-card__apply">
					<?php esc_html_e( 'Solliciteer nu', 'hds' ); ?>
				</a>
			</article>
		<?php endwhile; ?>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}

/**
 * Render hds/contact-info block.
 */
function hds_render_contact_info( array $attributes, string $content, \WP_Block $block ): string {
	$show_phone   = $attributes['showPhone'] ?? true;
	$show_email   = $attributes['showEmail'] ?? true;
	$show_address = $attributes['showAddress'] ?? true;
	$show_kvk     = $attributes['showKVK'] ?? true;
	$show_hours   = $attributes['showHours'] ?? false;
	$show_social  = $attributes['showSocial'] ?? false;

	$phone     = hds_get_phone();
	$email     = hds_get_email();
	$address   = hds_get_address();
	$postal    = hds_get_postal_city();
	$kvk       = get_theme_mod( 'hds_kvk' );
	$btw       = get_theme_mod( 'hds_btw' );
	$hours     = get_theme_mod( 'hds_opening_hours' );
	$facebook  = get_theme_mod( 'hds_facebook_url' );
	$instagram = get_theme_mod( 'hds_instagram_url' );

	ob_start();
	?>
	<div class="hds-contact-info">
		<?php if ( $show_phone ) : ?>
			<div class="hds-contact-info__item">
				<span class="hds-contact-info__label"><?php esc_html_e( 'Telefoon', 'hds' ); ?></span>
				<a href="tel:<?php echo esc_attr( str_replace( '-', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
			</div>
		<?php endif; ?>

		<?php if ( $show_email ) : ?>
			<div class="hds-contact-info__item">
				<span class="hds-contact-info__label"><?php esc_html_e( 'E-mail', 'hds' ); ?></span>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</div>
		<?php endif; ?>

		<?php if ( $show_address && ( $address || $postal ) ) : ?>
			<div class="hds-contact-info__item">
				<span class="hds-contact-info__label"><?php esc_html_e( 'Adres', 'hds' ); ?></span>
				<p><?php echo $address ? esc_html( $address ) . '<br>' : ''; ?><?php echo esc_html( $postal ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( $show_kvk && ( $kvk || $btw ) ) : ?>
			<div class="hds-contact-info__item hds-contact-info__legal">
				<?php if ( $kvk ) : ?>
					<p><?php echo esc_html__( 'KVK:', 'hds' ) . ' ' . esc_html( $kvk ); ?></p>
				<?php endif; ?>
				<?php if ( $btw ) : ?>
					<p><?php echo esc_html__( 'BTW:', 'hds' ) . ' ' . esc_html( $btw ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_hours && $hours ) : ?>
			<div class="hds-contact-info__item">
				<span class="hds-contact-info__label"><?php esc_html_e( 'Openingstijden', 'hds' ); ?></span>
				<p><?php echo nl2br( esc_html( $hours ) ); ?></p>
			</div>
		<?php endif; ?>

		<?php if ( $show_social && ( $facebook || $instagram ) ) : ?>
			<div class="hds-contact-info__social">
				<?php if ( $facebook ) : ?>
					<a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">Facebook</a>
				<?php endif; ?>
				<?php if ( $instagram ) : ?>
					<a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
