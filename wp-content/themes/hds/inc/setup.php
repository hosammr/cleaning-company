<?php
/**
 * Theme setup — image sizes, theme supports, disabling unused features.
 *
 * @package HDS
 */

/**
 * Register custom image sizes.
 */
function hds_register_image_sizes(): void {
	add_image_size( 'hds-card',    400,  300, true );
	add_image_size( 'hds-content', 800,  600, false );
	add_image_size( 'hds-hero',   1600,  900, true );

	remove_image_size( '1536x1536' );
	remove_image_size( '2048x2048' );
}
add_action( 'after_setup_theme', 'hds_register_image_sizes', 20 );

/**
 * Add custom image sizes to the editor size selector.
 */
function hds_add_image_sizes_to_editor( array $sizes ): array {
	return array_merge( $sizes, [
		'hds-card'    => __( 'HDS Card', 'hds' ),
		'hds-content' => __( 'HDS Content', 'hds' ),
		'hds-hero'    => __( 'HDS Hero', 'hds' ),
	] );
}
add_filter( 'image_size_names_choose', 'hds_add_image_sizes_to_editor' );

/**
 * Disable unused WordPress features.
 */
function hds_disable_unused_features(): void {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

	add_filter( 'emoji_svg_url', '__return_false' );
	add_filter( 'wp_resource_hints', 'hds_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'hds_disable_unused_features' );

/**
 * Remove unnecessary DNS prefetch entries.
 */
function hds_remove_dns_prefetch( array $hints, string $relation_type ): array {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_filter( $hints, function ( $hint ) {
			return strpos( $hint, 'fonts.googleapis.com' ) === false
				&& strpos( $hint, 's.w.org' ) === false;
		} );
	}
	return $hints;
}

/**
 * Remove WordPress version generator.
 */
function hds_remove_version_generator(): void {
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );
}
add_action( 'init', 'hds_remove_version_generator' );

/**
 * Remove RSD, wlwmanifest, shortlink from head.
 */
function hds_remove_head_links(): void {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}
add_action( 'init', 'hds_remove_head_links' );

/**
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable Gutenberg full-screen editor by default.
 */
function hds_disable_fullscreen_editor(): void {
	$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } };";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'hds_disable_fullscreen_editor' );

/**
 * Limit post revisions to 10.
 */
function hds_limit_revisions(): void {
	if ( ! defined( 'WP_POST_REVISIONS' ) || WP_POST_REVISIONS === true || WP_POST_REVISIONS < 0 ) {
		add_filter( 'wp_revisions_to_keep', function ( $num, $post ) {
			return 10;
		}, 10, 2 );
	}
}
add_action( 'init', 'hds_limit_revisions' );

/**
 * Disable attachment year/month folder structure for cleaner URLs.
 */
function hds_attachment_folder_structure(): bool {
	return true;
}
add_filter( 'pre_option_uploads_use_yearmonth_folders', 'hds_attachment_folder_structure' );

/**
 * Allow SVG uploads only for trusted users.
 *
 * SVGs are active content: even after sanitization they are only safe
 * when uploaded by users trusted to post unfiltered HTML. Administrators
 * hold `unfiltered_html` on single-site installs, so legitimate
 * logo/icon uploads keep working; less trusted roles (authors, editors,
 * shop managers, ...) are blocked from uploading SVGs entirely.
 */
function hds_allow_svg_uploads( array $mimes ): array {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'hds_allow_svg_uploads' );

/**
 * Sanitize SVG during upload (DOM allowlist).
 *
 * Replaces the former regex-only scrubber, which did not block
 * foreignObject, javascript:/data: URIs, CDATA-based payloads or other
 * active SVG content. The sanitizer:
 *
 * - rejects DOCTYPE/ENTITY declarations (XXE / entity expansion)
 * - rejects documents whose root element is not <svg>
 * - removes active elements: script, foreignObject, iframe, embed,
 *   object, image, a, animate/animateTransform/animateMotion/set,
 *   filter and every other element outside the allowlist
 * - removes comments and processing instructions
 * - strips attributes outside the allowlist (covers all on* handlers)
 * - only allows href/xlink:href values that reference a local #fragment
 * - rejects javascript:/vbscript:/data: URIs and non-local url() refs
 * - sanitizes style attributes and <style> blocks to a CSS property
 *   allowlist with local-only url() references
 *
 * Trade-off: external references, SMIL animation and CSS outside the
 * allowlist are removed, so complex SVGs may render differently after
 * upload. Vector logos built from shapes, gradients, classes and
 * presentation attributes keep working.
 *
 * @param array $file Uploaded file info.
 * @return array Filtered file info (with 'error' set on rejection).
 */
function hds_sanitize_svg( array $file ): array {
	if ( 'image/svg+xml' !== $file['type'] ) {
		return $file;
	}

	if ( empty( $file['tmp_name'] ) || ! is_readable( $file['tmp_name'] ) ) {
		$file['error'] = __( 'SVG-bestand kon niet worden gelezen.', 'hds' );
		return $file;
	}

	$svg = file_get_contents( $file['tmp_name'] );
	if ( false === $svg ) {
		$file['error'] = __( 'SVG-bestand kon niet worden gelezen.', 'hds' );
		return $file;
	}

	$clean = hds_sanitize_svg_markup( $svg );
	if ( null === $clean ) {
		$file['error'] = __( 'Ongeldig of onveilig SVG-bestand.', 'hds' );
		return $file;
	}

	file_put_contents( $file['tmp_name'], $clean );

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'hds_sanitize_svg' );

/**
 * SVG elements allowed to survive sanitization.
 *
 * Lowercase element names (compared against strtolower(nodeName)).
 *
 * @return string[]
 */
function hds_svg_allowed_elements(): array {
	return [
		'svg',
		'g',
		'defs',
		'symbol',
		'use',
		'path',
		'circle',
		'ellipse',
		'rect',
		'line',
		'polyline',
		'polygon',
		'text',
		'tspan',
		'textpath',
		'title',
		'desc',
		'lineargradient',
		'radialgradient',
		'stop',
		'clippath',
		'mask',
		'pattern',
		'marker',
		'view',
		'style',
	];
}

/**
 * SVG attributes allowed to survive sanitization.
 *
 * Lowercase attribute names (compared against strtolower(nodeName)).
 * Any attribute not listed here — including every on* event handler —
 * is stripped.
 *
 * @return string[]
 */
function hds_svg_allowed_attributes(): array {
	return [
		'id',
		'class',
		'style',
		'xmlns',
		'xmlns:xlink',
		'xlink:href',
		'href',
		'version',
		'x',
		'y',
		'x1',
		'y1',
		'x2',
		'y2',
		'cx',
		'cy',
		'r',
		'rx',
		'ry',
		'width',
		'height',
		'viewbox',
		'preserveaspectratio',
		'd',
		'points',
		'transform',
		'offset',
		'stop-color',
		'stop-opacity',
		'fill',
		'fill-opacity',
		'fill-rule',
		'clip-rule',
		'stroke',
		'stroke-width',
		'stroke-linecap',
		'stroke-linejoin',
		'stroke-miterlimit',
		'stroke-dasharray',
		'stroke-dashoffset',
		'stroke-opacity',
		'opacity',
		'display',
		'visibility',
		'marker-start',
		'marker-mid',
		'marker-end',
		'gradientunits',
		'gradienttransform',
		'spreadmethod',
		'patternunits',
		'patterntransform',
		'patterncontentunits',
		'clippathunits',
		'maskunits',
		'markerunits',
		'markerwidth',
		'markerheight',
		'refx',
		'refy',
		'orient',
		'font-family',
		'font-size',
		'font-weight',
		'font-style',
		'text-anchor',
		'letter-spacing',
		'word-spacing',
		'dominant-baseline',
		'vector-effect',
		'pathlength',
		'color',
	];
}

/**
 * CSS properties allowed inside style attributes and <style> blocks.
 *
 * @return string[]
 */
function hds_svg_allowed_css_properties(): array {
	return [
		'fill',
		'fill-opacity',
		'fill-rule',
		'stroke',
		'stroke-width',
		'stroke-linecap',
		'stroke-linejoin',
		'stroke-miterlimit',
		'stroke-dasharray',
		'stroke-dashoffset',
		'stroke-opacity',
		'opacity',
		'clip-path',
		'clip-rule',
		'mask',
		'display',
		'visibility',
		'stop-color',
		'stop-opacity',
		'color',
		'font-family',
		'font-size',
		'font-weight',
		'font-style',
		'text-anchor',
		'letter-spacing',
		'word-spacing',
		'dominant-baseline',
		'vector-effect',
	];
}

/**
 * Verify that every url() reference in a value targets a local #fragment.
 *
 * @param string $value Attribute or CSS value.
 * @return bool True when all url() references are local fragments.
 */
function hds_svg_css_urls_are_local( string $value ): bool {
	preg_match_all( '/url\(\s*(["\']?)(.*?)\1\s*\)/i', $value, $matches );
	foreach ( $matches[2] as $url ) {
		if ( ! str_starts_with( trim( $url ), '#' ) ) {
			return false;
		}
	}
	return true;
}

/**
 * Sanitize an inline CSS declaration list to the property allowlist.
 *
 * Re-emits only whitelisted property:value pairs. @-rules, unknown
 * properties, expression()/URI vectors and non-local url() references
 * are dropped.
 *
 * @param string $css CSS declarations (with or without braces).
 * @return string Sanitized CSS declarations.
 */
function hds_sanitize_svg_css( string $css ): string {
	$allowed = hds_svg_allowed_css_properties();
	$clean   = '';

	if ( preg_match_all( '/([a-zA-Z-]+)\s*:\s*([^;]+)/', $css, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $match ) {
			$prop  = strtolower( trim( $match[1] ) );
			$value = trim( $match[2] );

			if ( ! in_array( $prop, $allowed, true ) ) {
				continue;
			}
			if ( preg_match( '/expression|javascript|vbscript|@import|data\s*:|[<>]/i', $value ) ) {
				continue;
			}
			if ( false !== stripos( $value, 'url(' ) && ! hds_svg_css_urls_are_local( $value ) ) {
				continue;
			}

			$clean .= $prop . ': ' . $value . '; ';
		}
	}

	return $clean;
}

/**
 * Sanitize an SVG document (DOM allowlist).
 *
 * @param string $svg Raw SVG markup.
 * @return string|null Sanitized SVG markup, or null when the document is
 *                     not a valid, sanitizable SVG.
 */
function hds_sanitize_svg_markup( string $svg ): ?string {
	// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOMDocument exposes camelCase properties ($nodeName, $textContent, ...) which cannot be renamed.
	// No DOCTYPE/ENTITY declarations — closes XXE and entity-expansion
	// vectors before any parsing happens.
	if ( preg_match( '/<!\s*(?:DOCTYPE|ENTITY)/i', $svg ) ) {
		return null;
	}

	$allowed_elements   = hds_svg_allowed_elements();
	$allowed_attributes = hds_svg_allowed_attributes();

	$dom = new DOMDocument();

	// Prevent external network access and entity substitution during parsing.
	$dom->resolveExternals   = false;
	$dom->substituteEntities = false;

	$previous_loader = libxml_get_external_entity_loader();
	libxml_set_external_entity_loader( static function () {
		return null;
	} );
	$previous_internal = libxml_use_internal_errors( true );

	try {
		$loaded = $dom->loadXML( $svg, LIBXML_NONET | LIBXML_NOBLANKS | LIBXML_COMPACT | LIBXML_NOCDATA );
	} finally {
		libxml_clear_errors();
		libxml_use_internal_errors( $previous_internal );
		libxml_set_external_entity_loader( $previous_loader );
	}

	if ( ! $loaded ) {
		return null;
	}

	$root = $dom->documentElement;
	if ( ! $root || 'svg' !== strtolower( $root->nodeName ) ) {
		return null;
	}

	hds_sanitize_svg_node( $root, $allowed_elements, $allowed_attributes );

	$clean = $dom->saveXML( $root );

	return false === $clean ? null : $clean;
}

/**
 * Recursively strip disallowed elements, attributes and CSS from an SVG.
 *
 * @param DOMNode $node                Element to sanitize (in place).
 * @param array   $allowed_elements    Allowed element names (lowercase).
 * @param array   $allowed_attributes  Allowed attribute names (lowercase).
 */
function hds_sanitize_svg_node( DOMNode $node, array $allowed_elements, array $allowed_attributes ): void {
	if ( XML_ELEMENT_NODE === $node->nodeType ) {
		// Sanitize this element's own attributes (covers the root too).
		for ( $i = $node->attributes->length - 1; $i >= 0; $i-- ) {
			$attr  = $node->attributes->item( $i );
			$name  = strtolower( $attr->nodeName );
			$value = $attr->nodeValue;
			$keep  = in_array( $name, $allowed_attributes, true );

			if ( ! $keep ) {
				$node->removeAttributeNode( $attr );
				continue;
			}

			if ( 'style' === $name ) {
				$value = hds_sanitize_svg_css( $value );
				if ( '' === trim( $value ) ) {
					$node->removeAttributeNode( $attr );
					continue;
				}
				$node->setAttribute( 'style', $value );
				continue;
			}

			if ( 'href' === $name || 'xlink:href' === $name ) {
				$keep = (bool) preg_match( '/^\s*#/', $value );
			}

			if ( $keep && preg_match( '/(?:javascript|vbscript|data)\s*:/i', $value ) ) {
				$keep = false;
			}

			if ( $keep && false !== stripos( $value, 'url(' ) && ! hds_svg_css_urls_are_local( $value ) ) {
				$keep = false;
			}

			if ( ! $keep ) {
				$node->removeAttributeNode( $attr );
			}
		}
	}

	$remove = [];
	foreach ( $node->childNodes as $child ) {
		if ( XML_ELEMENT_NODE === $child->nodeType ) {
			if ( ! in_array( strtolower( $child->nodeName ), $allowed_elements, true ) ) {
				$remove[] = $child;
			}
		} elseif ( XML_COMMENT_NODE === $child->nodeType || XML_PI_NODE === $child->nodeType ) {
			$remove[] = $child;
		}
	}

	foreach ( $remove as $child ) {
		$node->removeChild( $child );
	}

	foreach ( $node->childNodes as $child ) {
		if ( XML_ELEMENT_NODE !== $child->nodeType ) {
			continue;
		}

		hds_sanitize_svg_node( $child, $allowed_elements, $allowed_attributes );

		// Sanitize inline CSS inside <style> blocks, keeping selectors.
		// @-rules (@import, @charset, @media, ...) are removed first so
		// they cannot smuggle external references past the brace pass.
		if ( 'style' === strtolower( $child->nodeName ) ) {
			$css       = preg_replace( '/@[a-zA-Z-]+\s*[^;{}]*[;{}]/s', '', $child->textContent );
			$style_css = preg_replace_callback(
				'/\{[^}]*\}/s',
				static function ( array $block ): string {
					return '{' . hds_sanitize_svg_css( $block[0] ) . '}';
				},
				$css
			);
			$style_css = preg_replace( '/@[^{}]*/', '', $style_css );

			$child->textContent = '';
			$child->appendChild( $child->ownerDocument->createTextNode( $style_css ) );
		}
	}
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
}

/**
 * Extend allowed block types to include patterns.
 */
function hds_allowed_block_types( $allowed_block_types, $block_editor_context ): array {
	if ( ! is_array( $allowed_block_types ) ) {
		$allowed_block_types = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		$allowed_block_types = array_keys( $allowed_block_types );
	}

	return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'hds_allowed_block_types', 10, 2 );

/**
 * Theme activation hook — flush rewrite rules.
 */
function hds_theme_activation(): void {
	hds_register_testimonial_cpt();
	hds_register_vacancy_cpt();
}
add_action( 'after_switch_theme', 'hds_theme_activation' );
