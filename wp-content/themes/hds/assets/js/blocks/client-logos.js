( function ( wp ) {
	'use strict';

	const registerBlockType = wp.blocks.registerBlockType;
	const el = wp.element.createElement;
	const ServerSideRender = wp.serverSideRender;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const SelectControl = wp.components.SelectControl;

	registerBlockType( 'hds/client-logos', {
		title: 'HDS Opdrachtgevers',
		description: 'Toon de bevestigde opdrachtgevers met hun logo en externe website.',
		icon: 'businessperson',
		category: 'hds-patterns',
		attributes: {
			variant: { type: 'string', default: 'grid' },
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
					el( PanelBody, { title: 'Opdrachtgevers Instellingen', initialOpen: true },
						el( SelectControl, {
							label: 'Weergave',
							value: attributes.variant,
							options: [
								{ label: 'Kaarten (referenties pagina)', value: 'grid' },
								{ label: 'Logo strip (homepage)', value: 'strip' },
							],
							onChange: function ( val ) { setAttributes( { variant: val } ); },
						} )
					)
				),
				el( ServerSideRender, {
					block: 'hds/client-logos',
					attributes: attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );

}( window.wp ));