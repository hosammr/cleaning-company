( function ( wp ) {
	'use strict';

	const registerBlockType = wp.blocks.registerBlockType;
	const el = wp.element.createElement;
	const ServerSideRender = wp.serverSideRender;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const ToggleControl = wp.components.ToggleControl;

	registerBlockType( 'hds/contact-info', {
		title: 'HDS Contactgegevens',
		description: 'Toon bedrijfsgegevens uit de Customizer (telefoon, e-mail, adres, KVK, BTW).',
		icon: 'phone',
		category: 'hds-patterns',
		attributes: {
			showPhone: { type: 'boolean', default: true },
			showEmail: { type: 'boolean', default: true },
			showAddress: { type: 'boolean', default: true },
			showKVK: { type: 'boolean', default: true },
			showHours: { type: 'boolean', default: false },
			showSocial: { type: 'boolean', default: false },
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
					el( PanelBody, { title: 'Zichtbare velden', initialOpen: true },
						el( ToggleControl, {
							label: 'Telefoon',
							checked: attributes.showPhone,
							onChange: function ( val ) { setAttributes( { showPhone: val } ); },
						} ),
						el( ToggleControl, {
							label: 'E-mail',
							checked: attributes.showEmail,
							onChange: function ( val ) { setAttributes( { showEmail: val } ); },
						} ),
						el( ToggleControl, {
							label: 'Adres',
							checked: attributes.showAddress,
							onChange: function ( val ) { setAttributes( { showAddress: val } ); },
						} ),
						el( ToggleControl, {
							label: 'KVK / BTW',
							checked: attributes.showKVK,
							onChange: function ( val ) { setAttributes( { showKVK: val } ); },
						} ),
						el( ToggleControl, {
							label: 'Openingstijden',
							checked: attributes.showHours,
							onChange: function ( val ) { setAttributes( { showHours: val } ); },
						} ),
						el( ToggleControl, {
							label: 'Social media links',
							checked: attributes.showSocial,
							onChange: function ( val ) { setAttributes( { showSocial: val } ); },
						} )
					)
				),
				el( ServerSideRender, {
					block: 'hds/contact-info',
					attributes: attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );

}( window.wp ));
