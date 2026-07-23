( function ( wp ) {
	'use strict';

	const registerBlockType = wp.blocks.registerBlockType;
	const el = wp.element.createElement;
	const ServerSideRender = wp.serverSideRender;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const ToggleControl = wp.components.ToggleControl;
	const RangeControl = wp.components.RangeControl;

	registerBlockType( 'hds/job-listing', {
		title: 'HDS Vacatures',
		description: 'Toon vacatures met details en sollicitatieknop.',
		icon: 'businessperson',
		category: 'hds-patterns',
		attributes: {
			count: { type: 'integer', default: 5 },
			showAll: { type: 'boolean', default: true },
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
					el( PanelBody, { title: 'Vacature Instellingen', initialOpen: true },
						el( RangeControl, {
							label: 'Aantal vacatures',
							value: attributes.count,
							onChange: function ( val ) { setAttributes( { count: val } ); },
							min: 1,
							max: 50,
						} ),
						el( ToggleControl, {
							label: 'Toon lege melding als er geen vacatures zijn',
							checked: attributes.showAll,
							onChange: function ( val ) { setAttributes( { showAll: val } ); },
						} )
					)
				),
				el( ServerSideRender, {
					block: 'hds/job-listing',
					attributes: attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );

}( window.wp ));
