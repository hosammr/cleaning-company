/**
 * HDS Meta Panels — Block Editor sidebar panels.
 *
 * Registers PluginDocumentSettingPanel components for post meta fields.
 * Replaces what would be ACF metaboxes in a traditional setup.
 *
 * @package HDS
 */

( function ( wp ) {
	'use strict';

	const { registerPlugin } = wp.plugins;
	const { PluginDocumentSettingPanel } = wp.editPost;
	const { createElement: el } = wp.element;
	const { TextControl, ToggleControl } = wp.components;
	const { useSelect, useDispatch } = wp.data;
	const { __ } = wp.i18n;

	const data = window.hdsMetaPanelsData || {};

	/**
	 * Generic meta field component that reads/writes post meta via the REST API.
	 */
	function MetaTextControl( { metaKey, label, help, postType: _postType } ) {
		const metaValue = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' )?.[ metaKey ] ?? '';
		}, [ metaKey ] );

		const { editPost } = useDispatch( 'core/editor' );

		return el( TextControl, {
			label: label,
			help: help,
			value: metaValue || '',
			onChange: function ( value ) {
				editPost( { meta: { [ metaKey ]: value } } );
			},
		} );
	}

	function MetaToggleControl( { metaKey, label, help } ) {
		const metaValue = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' )?.[ metaKey ] ?? false;
		}, [ metaKey ] );

		const { editPost } = useDispatch( 'core/editor' );

		return el( ToggleControl, {
			label: label,
			help: help,
			checked: !! metaValue,
			onChange: function ( value ) {
				editPost( { meta: { [ metaKey ]: value } } );
			},
		} );
	}

	/**
	 * Service page meta panel.
	 */
	function ServiceMetaPanel() {
		if ( data.postType !== 'page' ) return null;

		const fields = data.serviceFields || {};
		const fieldKeys = Object.keys( fields );

		return el( PluginDocumentSettingPanel, {
			name: 'hds-service-meta',
			title: __( 'Service instellingen', 'hds' ),
			className: 'hds-meta-panel',
		},
			fieldKeys.map( function ( key ) {
				const field = fields[ key ];

				if ( field.type === 'toggle' ) {
					return el( MetaToggleControl, {
						key: key,
						metaKey: key,
						label: field.label,
						help: field.description,
					} );
				}

				return el( MetaTextControl, {
					key: key,
					metaKey: key,
					label: field.label,
					help: field.description,
					postType: data.postType,
				} );
			} )
		);
	}

	/**
	 * Testimonial meta panel.
	 */
	function TestimonialMetaPanel() {
		if ( data.postType !== 'hds_testimonial' ) return null;

		const fields = data.testimonialFields || {};
		const fieldKeys = Object.keys( fields );

		return el( PluginDocumentSettingPanel, {
			name: 'hds-testimonial-meta',
			title: __( 'Referentie details', 'hds' ),
			className: 'hds-meta-panel',
		},
			fieldKeys.map( function ( key ) {
				const field = fields[ key ];

				if ( field.type === 'number' ) {
					return el( MetaTextControl, {
						key: key,
						metaKey: key,
						label: field.label,
						help: field.description,
						postType: data.postType,
					} );
				}

				return el( MetaTextControl, {
					key: key,
					metaKey: key,
					label: field.label,
					help: field.description,
					postType: data.postType,
				} );
			} )
		);
	}

	/**
	 * Vacancy meta panel.
	 */
	function VacancyMetaPanel() {
		if ( data.postType !== 'hds_vacancy' ) return null;

		const fields = data.vacancyFields || {};
		const fieldKeys = Object.keys( fields );

		return el( PluginDocumentSettingPanel, {
			name: 'hds-vacancy-meta',
			title: __( 'Vacature details', 'hds' ),
			className: 'hds-meta-panel',
		},
			fieldKeys.map( function ( key ) {
				const field = fields[ key ];

				if ( field.type === 'toggle' ) {
					return el( MetaToggleControl, {
						key: key,
						metaKey: key,
						label: field.label,
						help: field.description,
					} );
				}

				return el( MetaTextControl, {
					key: key,
					metaKey: key,
					label: field.label,
					help: field.description,
					postType: data.postType,
				} );
			} )
		);
	}

	registerPlugin( 'hds-service-meta-panel', { render: ServiceMetaPanel } );
	registerPlugin( 'hds-testimonial-meta-panel', { render: TestimonialMetaPanel } );
	registerPlugin( 'hds-vacancy-meta-panel', { render: VacancyMetaPanel } );

}( window.wp ) );
