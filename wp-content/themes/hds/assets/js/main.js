/**
 * HDS Onderhoudsdiensten — Frontend JavaScript
 *
 * @package HDS
 */

( function () {
	'use strict';

	const menuToggle = document.querySelector( '.menu-toggle' );
	const primaryMenu = document.querySelector( '.primary-menu' );

	if ( menuToggle && primaryMenu ) {
		menuToggle.addEventListener( 'click', function () {
			const expanded = this.getAttribute( 'aria-expanded' ) === 'true';
			this.setAttribute( 'aria-expanded', ! expanded );
			primaryMenu.classList.toggle( 'is-active' );
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && primaryMenu && primaryMenu.classList.contains( 'is-active' ) ) {
			primaryMenu.classList.remove( 'is-active' );
			menuToggle.setAttribute( 'aria-expanded', 'false' );
			menuToggle.focus();
		}
	} );
}() );
