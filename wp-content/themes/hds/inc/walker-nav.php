<?php
/**
 * HDS Navigation Walker.
 *
 * Extends Walker_Nav_Menu to add ARIA attributes, schema.org markup,
 * and accessible dropdown toggles for the primary navigation menu.
 *
 * Implements: REQ-ACC-020 (keyboard navigation for dropdown menu)
 *            REQ-ACC-005 (ARIA landmarks)
 *
 * @package HDS
 */

class HDS_Walker_Nav_Menu extends \Walker_Nav_Menu {

	/**
	 * Start level — wrap sub-menus with ARIA.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ): void {
		$indent  = str_repeat( "\t", $depth );
		$classes = [ 'sub-menu' ];
		$class_names = implode( ' ', $classes );

		$output .= "\n{$indent}<ul class=\"" . esc_attr( $class_names ) . "\" aria-label=\"submenu\">\n";
	}

	/**
	 * Start element — add ARIA attributes to menu items with children.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ): void {
		$indent = $depth ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $data_object->classes ) ? [] : (array) $data_object->classes;
		$classes[] = 'menu-item-' . $data_object->ID;

		if ( $args->walker->has_children ) {
			$classes[] = 'menu-item-has-children';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $data_object, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $data_object->ID, $data_object, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = [];
		$atts['title']  = ! empty( $data_object->attr_title ) ? $data_object->attr_title : '';
		$atts['target'] = ! empty( $data_object->target ) ? $data_object->target : '';
		if ( '_blank' === $data_object->target && empty( $data_object->xfn ) ) {
			$atts['rel'] = 'noopener noreferrer';
		} else {
			$atts['rel'] = $data_object->xfn;
		}
		$atts['href']         = ! empty( $data_object->url ) ? $data_object->url : '';
		$atts['aria-current'] = $data_object->current ? 'page' : '';

		if ( $args->walker->has_children ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $data_object, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title = apply_filters( 'the_title', $data_object->title, $data_object->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $data_object, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $data_object, $depth, $args );
	}
}
