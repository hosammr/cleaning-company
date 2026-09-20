<?php
/**
 * Hero template part.
 *
 * Expected variables set by the calling template:
 *   string $hero_title       — The page title (H1).
 *   string $hero_eyebrow     — Optional eyebrow label above the title.
 *   string $hero_subtitle    — Optional subtitle below the title.
 *   string $hero_image_url   — Full URL of the hero background image, or empty string.
 *   string $hero_cta_text    — CTA button label.
 *   string $hero_cta_url     — CTA button destination URL.
 *
 * Service pages additionally set:
 *   int    $hero_image_id    — Attachment ID. When set, the hero renders a real
 *                              <img> (with alt + intrinsic dimensions) instead of
 *                              a CSS background image. Other templates do not set
 *                              this, so their output stays unchanged.
 *   string $hero_image_alt   — Accessible alt text for the <img>.
 *
 * Background-hero pages may additionally set:
 *   array  $hero_image_srcset — Width => URL map of generated candidates for the
 *                                responsive background. When set, the background
 *                                hero switches candidates via scoped max-width
 *                                media queries; the plain $hero_image_url remains
 *                                the fallback and the default above all breakpoints.
 *   string $hero_image_media_max — Optional single max-width (e.g. "679px"). When
 *                                set, the smallest candidate is served below this
 *                                width and the fallback image at and above it.
 *
 *  Background-hero delivery prefers a sibling .webp file per candidate via
 *  CSS image-set() while keeping the PNG candidate as the universal fallback.
 *  If the .webp file is absent the PNG is served as before.
 *
 * @package HDS
 */

if ( empty( $hero_title ) ) {
	return;
}

if ( ! function_exists( 'hds_hero_webp_candidate' ) ) {
	/**
	 * Resolve the sibling WebP URL for a PNG hero candidate.
	 *
	 * Returns the WebP URL only when the corresponding .webp file already exists
	 * in the uploads directory; otherwise returns '' so delivery degrades
	 * gracefully to the PNG fallback (e.g. when the WebP file is absent).
	 *
	 * @param string $png_url Full URL of the PNG candidate.
	 * @return string WebP URL, or '' when unavailable.
	 */
	function hds_hero_webp_candidate( string $png_url ): string {
		if ( '' === $png_url ) {
			return '';
		}
		$uploads = wp_get_upload_dir();
		$baseurl = trailingslashit( $uploads['baseurl'] );
		$basedir = trailingslashit( $uploads['basedir'] );
		if ( 0 !== strpos( $png_url, $baseurl ) ) {
			return '';
		}
		$relative = substr( $png_url, strlen( $baseurl ) );
		$webp     = preg_replace( '/\.png$/i', '.webp', $relative );
		if ( $webp === $relative || ! file_exists( $basedir . $webp ) ) {
			return '';
		}
		return $baseurl . $webp;
	}
}

/**
 * Build the background-image declarations for a hero candidate.
 *
 * The plain PNG declaration is emitted first as the universal fallback,
 * followed by an image-set() declaration that prefers the sibling WebP file.
 * Browsers without image-set() support keep the PNG declaration; browsers
 * that support image-set() but cannot decode image/webp select the PNG
 * candidate via its type() hint. When $important is true each declaration
 * receives !important so the scoped media-gate rules can override the
 * inline fallback style.
 *
 * @param string $png_url   Full URL of the PNG candidate.
 * @param bool   $important Whether to add !important to each declaration.
 * @return string Semicolon-joined background-image declarations.
 */
function hds_hero_background_declarations( string $png_url, bool $important = false ): string {
	$suffix = $important ? ' !important' : '';
	$png    = esc_url( $png_url );
	$webp   = hds_hero_webp_candidate( $png_url );

	$declarations = array( "background-image:url('" . $png . "')" . $suffix );
	if ( '' !== $webp ) {
		$declarations[] = "background-image:image-set(url('" . esc_url( $webp ) . "') type('image/webp') 1x,url('" . $png . "') type('image/png') 1x)" . $suffix;
	}
	return implode( ';', $declarations );
}

$hero_image_id        = isset( $hero_image_id ) ? (int) $hero_image_id : 0;
$has_img              = $hero_image_id && wp_get_attachment_image_url( $hero_image_id, 'hds-hero' );
$hero_image_srcset    = isset( $hero_image_srcset ) ? array_filter( (array) $hero_image_srcset ) : array();
$hero_image_media_max = isset( $hero_image_media_max ) ? $hero_image_media_max : '';
?>

<?php if ( $has_img ) : ?>
	<section class="service-hero service-hero--image">
		<div class="service-hero__media" aria-hidden="true">
			<?php
			echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$hero_image_id,
				'hds-hero',
				false,
				[
					'alt' => $hero_image_alt ?? '',
					'fetchpriority' => 'high',
				]
			);
			?>
		</div>
		<div class="container">
			<?php if ( ! empty( $hero_eyebrow ) ) : ?>
				<p class="service-hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<?php endif; ?>
			<h1><?php echo esc_html( $hero_title ); ?></h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="service-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $hero_cta_url ); ?>" class="btn btn-cta">
				<?php echo esc_html( $hero_cta_text ); ?>
			</a>
		</div>
	</section>
<?php else : ?>
	<?php
	$hero_class = 'service-hero';
	$hero_style = $hero_image_url ? ' style="' . hds_hero_background_declarations( $hero_image_url ) . '"' : '';
	$hero_gate  = '';

	if ( $hero_image_url && $hero_image_srcset ) {
		$hero_class = 'service-hero service-hero--responsive';

		$candidates = array();
		foreach ( $hero_image_srcset as $width => $src_url ) {
			$candidates[ (int) $width ] = (string) $src_url;
		}
		ksort( $candidates );

		$media_max = trim( (string) $hero_image_media_max );

		if ( '' !== $media_max && preg_match( '/^\d+(?:\.\d+)?px$/i', $media_max ) ) {
			// Single mandatory breakpoint: serve the smallest candidate below the
			// gate width, keep the fallback (hero_image_url) at and above it.
			$smallest  = $candidates[ min( array_keys( $candidates ) ) ];
			$hero_gate = '<style>@media (max-width:' . $media_max . '){.service-hero--responsive{' . hds_hero_background_declarations( $smallest, true ) . '}}</style>';
		} elseif ( count( $candidates ) > 1 ) {
			// Width-based candidates: largest stays the default (inline fallback),
			// each smaller candidate is served below its own max-width breakpoint.
			// Rules are emitted largest-first so the narrowest query wins.
			$widths = array_keys( $candidates );
			rsort( $widths, SORT_NUMERIC );
			$largest = $widths[0];
			$rules   = array();
			foreach ( $widths as $width ) {
				if ( $width === $largest ) {
					continue;
				}
				$rules[] = '@media (max-width:' . $width . 'px){.service-hero--responsive{' . hds_hero_background_declarations( $candidates[ $width ], true ) . '}}';
			}
			if ( $rules ) {
				$hero_gate = '<style>' . implode( '', $rules ) . '</style>';
			}
		}
	}
	?>
	<?php echo $hero_gate; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_url() applied to every URL in $hero_gate above. ?>
	<section class="<?php echo esc_attr( $hero_class ); ?>"<?php echo $hero_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_url() applied to every URL in $hero_style above. ?>>
		<div class="container">
			<?php if ( ! empty( $hero_eyebrow ) ) : ?>
				<p class="service-hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<?php endif; ?>
			<h1><?php echo esc_html( $hero_title ); ?></h1>
			<?php if ( $hero_subtitle ) : ?>
				<p class="service-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( $hero_cta_url ); ?>" class="btn btn-cta">
				<?php echo esc_html( $hero_cta_text ); ?>
			</a>
		</div>
	</section>
<?php endif; ?>
