( function ( wp ) {
	'use strict';

	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var ServerSideRender = wp.serverSideRender;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var ToggleControl = wp.components.ToggleControl;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var useSelect = wp.data.useSelect;

	registerBlockType( 'hds/service-card', {
		title: 'HDS Service Card',
		description: 'Toon een enkele service kaart met icoon, titel, excerpt en link.',
		icon: 'admin-page',
		category: 'hds-patterns',
		attributes: {
			pageId: { type: 'integer', default: 0 },
			showImage: { type: 'boolean', default: false },
		},
		supports: {
			align: [ 'wide', 'full' ],
			html: false,
		},
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var pages = useSelect( function ( select ) {
				var query = { per_page: 100, orderby: 'title', order: 'asc' };
				var items = select( 'core' ).getEntityRecords( 'postType', 'page', query );
				if ( ! items ) return [];
				return items.map( function ( page ) {
					return { value: page.id, label: page.title.rendered || '(geen titel)' };
				} );
			}, [] );

			return el( 'div', {},
				el( InspectorControls, {},
					el( PanelBody, { title: 'Service Instellingen', initialOpen: true },
						el( SelectControl, {
							label: 'Service Pagina',
							value: attributes.pageId,
							options: [ { value: 0, label: '— Selecteer —' } ].concat( pages ),
							onChange: function ( val ) { setAttributes( { pageId: parseInt( val, 10 ) } ); },
						} ),
						el( ToggleControl, {
							label: 'Toon afbeelding',
							checked: attributes.showImage,
							onChange: function ( val ) { setAttributes( { showImage: val } ); },
						} )
					)
				),
				attributes.pageId ? el( ServerSideRender, {
					block: 'hds/service-card',
					attributes: attributes,
				} ) : el( 'p', { className: 'components-placeholder' }, 'Selecteer een service pagina in de block instellingen.' )
			);
		},
		save: function () {
			return null;
		},
	} );

}( window.wp ));
