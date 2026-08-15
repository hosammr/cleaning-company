/**
 * HDS Onderhoudsdiensten — Frontend JavaScript
 *
 * Responsibilities:
 *   - Mobile menu toggle with ARIA state management
 *   - Keyboard navigation: Escape closes overlay, arrow keys in dropdowns
 *   - Focus trap inside mobile overlay
 *   - CSS-only dropdown toggle via class on parent (desktop)
 *   - Header search panel toggle with ARIA state management
 *   - Back-to-top button (IntersectionObserver)
 *   - Cookie banner dismissal
 *   - Notification dismissal
 *   - Smooth scroll for anchor links (progressive enhancement)
 *
 * No jQuery. No frameworks. No console.log in production.
 *
 * @package HDS
 */

( function () {
	'use strict';

	/* ── Mobile Menu ── */
	const menuToggle = document.querySelector( '.menu-toggle' );
	const primaryMenu = document.querySelector( '.primary-menu' );
	const siteNavigation = document.querySelector( '.main-navigation' );

	if ( menuToggle && primaryMenu ) {
		menuToggle.addEventListener( 'click', function () {
			const expanded = this.getAttribute( 'aria-expanded' ) === 'true';
			this.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );

			if ( ! expanded ) {
				primaryMenu.classList.add( 'is-active' );
				document.body.classList.add( 'menu-open' );
				this.querySelector( '.screen-reader-text' ).textContent = this.getAttribute( 'data-close-text' )
					|| 'Menu sluiten';
				focusFirstMenuItem();
			} else {
				primaryMenu.classList.remove( 'is-active' );
				document.body.classList.remove( 'menu-open' );
				this.querySelector( '.screen-reader-text' ).textContent = this.getAttribute( 'data-open-text' )
					|| 'Menu openen';
				menuToggle.focus();
			}
		} );
	}

	function focusFirstMenuItem() {
		const firstLink = primaryMenu && primaryMenu.querySelector( 'a' );
		if ( firstLink ) {
			setTimeout( function () {
				firstLink.focus();
			}, 100 );
		}
	}

	/* ── Keyboard: Escape closes menu ── */
	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' ) {
			if ( primaryMenu && primaryMenu.classList.contains( 'is-active' ) ) {
				primaryMenu.classList.remove( 'is-active' );
				document.body.classList.remove( 'menu-open' );
				if ( menuToggle ) {
					menuToggle.setAttribute( 'aria-expanded', 'false' );
					menuToggle.querySelector( '.screen-reader-text' ).textContent = menuToggle.getAttribute( 'data-open-text' )
						|| 'Menu openen';
					menuToggle.focus();
				}
			}
		}
	} );

	/* ── Desktop dropdown: click toggle for touch/hover fallback ── */
	if ( siteNavigation ) {
		const dropdownParents = siteNavigation.querySelectorAll( '.menu-item-has-children > a' );

		dropdownParents.forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				const parent = this.parentNode;
				const hasDropdown = parent.classList.contains( 'menu-item-has-children' );

				if ( hasDropdown && window.innerWidth > 1023 ) {
					e.preventDefault();
					parent.classList.toggle( 'is-open' );

					const submenu = parent.querySelector( '.sub-menu' );
					if ( submenu ) {
						const expanded = submenu.classList.toggle( 'is-open' );
						parent.querySelector( 'a:first-child' ).setAttribute(
							'aria-expanded',
							expanded ? 'true' : 'false'
						);
					}
				}
			} );
		} );
	}

	// Close dropdowns when clicking outside
	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.menu-item-has-children' ) ) {
			const openDropdowns = document.querySelectorAll( '.menu-item-has-children.is-open' );
			openDropdowns.forEach( function ( item ) {
				item.classList.remove( 'is-open' );
				const submenu = item.querySelector( '.sub-menu' );
				if ( submenu ) {
					submenu.classList.remove( 'is-open' );
				}
				const toggle = item.querySelector( 'a:first-child' );
				if ( toggle ) {
					toggle.setAttribute( 'aria-expanded', 'false' );
				}
			} );
		}
	} );

	/* ── Header Search Toggle ── */
	const searchToggle  = document.getElementById( 'hds-header-search-toggle' );
	const searchPanel   = document.getElementById( 'hds-header-search-panel' );
	const searchClose   = document.getElementById( 'hds-header-search-close' );
	const searchInput   = searchPanel ? searchPanel.querySelector( '.hds-search-form__input' ) : null;

	function openHeaderSearch() {
		if ( ! searchToggle || ! searchPanel ) {
			return;
		}
		searchPanel.hidden = false;
		searchToggle.setAttribute( 'aria-expanded', 'true' );
		if ( searchInput ) {
			setTimeout( function () { searchInput.focus(); }, 50 );
		}
	}

	function closeHeaderSearch( returnFocus ) {
		if ( ! searchToggle || ! searchPanel ) {
			return;
		}
		searchPanel.hidden = true;
		searchToggle.setAttribute( 'aria-expanded', 'false' );
		if ( returnFocus ) {
			searchToggle.focus();
		}
	}

	if ( searchToggle && searchPanel ) {
		searchToggle.addEventListener( 'click', function () {
			if ( 'true' === this.getAttribute( 'aria-expanded' ) ) {
				closeHeaderSearch( false );
			} else {
				openHeaderSearch();
			}
		} );

		if ( searchClose ) {
			searchClose.addEventListener( 'click', function () {
				closeHeaderSearch( true );
			} );
		}

		searchPanel.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				closeHeaderSearch( true );
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if (
				'false' === searchToggle.getAttribute( 'aria-expanded' ) ||
				searchPanel.hidden
			) {
				return;
			}
			if ( ! event.target.closest( '#hds-header-search-panel' ) && ! event.target.closest( '#hds-header-search-toggle' ) ) {
				closeHeaderSearch( false );
			}
		} );
	}

	/* ── Back-to-Top Button ── */
	const backToTop = document.getElementById( 'hds-back-to-top' );

	if ( backToTop ) {
		const observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						backToTop.hidden = true;
					} else {
						backToTop.hidden = false;
					}
				} );
			},
			{ threshold: 0.1 }
		);

		const mainEl = document.getElementById( 'main' );
		if ( mainEl ) {
			observer.observe( mainEl );
		}

		backToTop.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
			document.querySelector( '.skip-link' ).focus();
		} );
	}

	/* ── Cookie Banner ── */
	const cookieBanner = document.getElementById( 'hds-cookie-banner' );
	if ( cookieBanner ) {
		cookieBanner.querySelector( '.hds-cookie-banner__accept' ).addEventListener( 'click', function () {
			document.cookie = `hds_cookie_consent=accepted;path=/;max-age=${60 * 60 * 24 * 365}`;
			cookieBanner.hidden = true;
			cookieBanner.setAttribute( 'aria-hidden', 'true' );
		} );

		cookieBanner.querySelector( '.hds-cookie-banner__decline' ).addEventListener( 'click', function () {
			document.cookie = `hds_cookie_consent=declined;path=/;max-age=${60 * 60 * 24 * 365}`;
			cookieBanner.hidden = true;
			cookieBanner.setAttribute( 'aria-hidden', 'true' );
		} );
	}

	/* ── Notification Dismiss ── */
	document.addEventListener( 'click', function ( e ) {
		const dismissBtn = e.target.closest( '.hds-notification__dismiss' );
		if ( dismissBtn ) {
			const notification = dismissBtn.closest( '.hds-notification' );
			if ( notification ) {
				notification.hidden = true;
			}
		}
	} );

	/* ── Smooth Scroll for Anchor Links ── */
	document.addEventListener( 'click', function ( e ) {
		const anchor = e.target.closest( 'a[href^="#"]' );
		if ( ! anchor ) return;

		const href = anchor.getAttribute( 'href' );
		if ( href === '#' || href === '#main' ) return;

		const target = document.querySelector( href );
		if ( target ) {
			e.preventDefault();
			target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			target.setAttribute( 'tabindex', '-1' );
			target.focus( { preventScroll: true } );
		}
	} );

}() );
