( function ( wp ) {
	'use strict';

	const registerBlockType = wp.blocks.registerBlockType;
	const el = wp.element.createElement;
	const ServerSideRender = wp.serverSideRender;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const ToggleControl = wp.components.ToggleControl;
	const RangeControl = wp.components.RangeControl;

	registerBlockType( 'hds/testimonial', {
		title: 'HDS Referenties',
		description: 'Toon referenties / testimonials van klanten.',
		icon: 'format-quote',
		category: 'hds-patterns',
		attributes: {
			count: { type: 'integer', default: 3 },
			showRating: { type: 'boolean', default: true },
			selectedItems: { type: 'array', default: [] },
		},
		supports: {
			align: [ 'wide', 'full' ],
			html: false,
		},
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;

			return el( 'div', {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Referentie Instellingen', initialOpen: true },
						el( RangeControl, {
							label: 'Aantal referenties',
							value: attributes.count,
							onChange: function ( val ) { setAttributes( { count: val } ); },
							min: 1,
							max: 20,
						} ),
						el( ToggleControl, {
							label: 'Toon sterren',
							checked: attributes.showRating,
							onChange: function ( val ) { setAttributes( { showRating: val } ); },
						} )
					)
				),
				el( ServerSideRender, {
					block: 'hds/testimonial',
					attributes: attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );

}( window.wp ));
