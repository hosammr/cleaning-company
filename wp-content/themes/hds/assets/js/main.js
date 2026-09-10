/**
 * HDS Onderhoudsdiensten — Frontend JavaScript
 *
 * Responsibilities:
 *   - Mobile menu toggle with ARIA state management
 *   - Keyboard navigation: Escape closes menu and dropdowns
 *   - Focus trap inside mobile overlay
 *   - CSS-only dropdown toggle via class on parent (desktop)
 *   - Header search panel toggle with ARIA state management
 *   - Sticky header compact state on scroll
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
				lockBodyScroll();
				primaryMenu.classList.add( 'is-active' );
				document.body.classList.add( 'menu-open' );
				this.querySelector( '.screen-reader-text' ).textContent = this.getAttribute( 'data-close-text' )
					|| 'Menu sluiten';
				focusFirstMenuItem();
			} else {
				primaryMenu.classList.remove( 'is-active' );
				document.body.classList.remove( 'menu-open' );
				unlockBodyScroll();
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

	/* ── Focus trap: keep Tab inside the open mobile navigation ── */
	function getMenuFocusables() {
		if ( ! primaryMenu ) {
			return [];
		}
		const selector = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';
		const candidates = primaryMenu.querySelectorAll( selector );
		return Array.prototype.filter.call( candidates, function ( el ) {
			return el.getClientRects().length > 0 && ! el.closest( '[aria-hidden="true"]' );
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Tab' ) {
			return;
		}
		if ( window.innerWidth > 1023 || ! document.body.classList.contains( 'menu-open' ) || ! primaryMenu ) {
			return;
		}

		const focusables = getMenuFocusables();
		if ( ! focusables.length ) {
			return;
		}

		const activeIndex = focusables.indexOf( document.activeElement );

		if ( activeIndex === -1 ) {
			event.preventDefault();
			const target = event.shiftKey ? focusables[ focusables.length - 1 ] : focusables[ 0 ];
			target.focus();
			return;
		}

		if ( event.shiftKey && activeIndex === 0 ) {
			event.preventDefault();
			focusables[ focusables.length - 1 ].focus();
		} else if ( ! event.shiftKey && activeIndex === focusables.length - 1 ) {
			event.preventDefault();
			focusables[ 0 ].focus();
		}
	} );

	let scrollLockY = 0;

	function lockBodyScroll() {
		scrollLockY = window.scrollY;
		document.body.style.top = `-${scrollLockY}px`;
	}

	function unlockBodyScroll() {
		document.body.style.top = '';
		window.scrollTo( 0, scrollLockY );
		scrollLockY = 0;
	}

	window.addEventListener( 'resize', function () {
		if ( window.innerWidth <= 1023 ) {
			return;
		}

		const menuOpen = document.body.classList.contains( 'menu-open' );
		const menuActive = primaryMenu && primaryMenu.classList.contains( 'is-active' );
		const ariaExpanded = menuToggle && menuToggle.getAttribute( 'aria-expanded' ) === 'true';

		if ( ! menuOpen && ! menuActive && ! ariaExpanded ) {
			return;
		}

		if ( menuOpen ) {
			unlockBodyScroll();
			document.body.classList.remove( 'menu-open' );
		}

		if ( menuActive ) {
			primaryMenu.classList.remove( 'is-active' );
		}

		if ( menuToggle ) {
			menuToggle.setAttribute( 'aria-expanded', 'false' );
			const srText = menuToggle.querySelector( '.screen-reader-text' );
			if ( srText ) {
				srText.textContent = menuToggle.getAttribute( 'data-open-text' ) || 'Menu openen';
			}
		}
	} );

	function openDropdown( item ) {
		item.classList.add( 'is-open' );
		const sub = item.querySelector( '.sub-menu' );
		if ( sub ) {
			sub.classList.add( 'is-open' );
		}
		const toggle = item.querySelector( 'a:first-child' );
		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', 'true' );
		}
	}

	function closeDropdown( item ) {
		item.classList.remove( 'is-open' );
		const sub = item.querySelector( '.sub-menu' );
		if ( sub ) {
			sub.classList.remove( 'is-open' );
		}
		const toggle = item.querySelector( 'a:first-child' );
		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', 'false' );
		}
		const hoverIndex = hoverOpenedItems.indexOf( item );
		if ( hoverIndex !== -1 ) {
			hoverOpenedItems.splice( hoverIndex, 1 );
		}
	}

	function closeSiblingDropdowns( currentParent ) {
		if ( ! siteNavigation ) {
			return;
		}
		const openItems = siteNavigation.querySelectorAll( '.menu-item-has-children.is-open' );
		openItems.forEach( function ( item ) {
			if ( item === currentParent ) {
				return;
			}
			closeDropdown( item );
		} );
	}

	/* ── Keyboard: Escape closes menu ── */
	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' ) {
			if ( primaryMenu && primaryMenu.classList.contains( 'is-active' ) ) {
				primaryMenu.classList.remove( 'is-active' );
				document.body.classList.remove( 'menu-open' );
				unlockBodyScroll();
				if ( menuToggle ) {
					menuToggle.setAttribute( 'aria-expanded', 'false' );
					menuToggle.querySelector( '.screen-reader-text' ).textContent = menuToggle.getAttribute( 'data-open-text' )
						|| 'Menu openen';
					menuToggle.focus();
				}
			} else if ( siteNavigation ) {
				const openItems = siteNavigation.querySelectorAll( '.menu-item-has-children.is-open' );
				if ( openItems.length ) {
					const focusTarget = openItems[ 0 ] ? openItems[ 0 ].querySelector( 'a:first-child' ) : null;
					closeSiblingDropdowns( null );
					if ( focusTarget ) {
						focusTarget.focus();
					}
				}
			}
		}
	} );

	/* ── Dropdown: click/tap toggle for touch/hover fallback (all widths) ── */
	let pointerInteraction = false;
	const hoverOpenedItems = [];

	document.addEventListener( 'pointerdown', function () {
		pointerInteraction = true;
	} );

	document.addEventListener( 'keydown', function () {
		pointerInteraction = false;
	} );

	if ( siteNavigation ) {
		const dropdownParents = siteNavigation.querySelectorAll( '.menu-item-has-children > a' );

		dropdownParents.forEach( function ( link ) {
			link.addEventListener( 'keydown', function ( e ) {
				if (
					window.innerWidth > 1023 &&
					( e.key === 'Enter' || e.key === ' ' ) &&
					this.parentNode.classList.contains( 'menu-item-has-children' )
				) {
					e.preventDefault();
					const parent = this.parentNode;
					if ( parent.classList.contains( 'is-open' ) ) {
						closeDropdown( parent );
					} else {
						closeSiblingDropdowns( parent );
						openDropdown( parent );
					}
				}
			} );

			link.addEventListener( 'click', function ( e ) {
				const parent = this.parentNode;
				const hasDropdown = parent.classList.contains( 'menu-item-has-children' );

				if ( hasDropdown ) {
					e.preventDefault();

					if ( window.innerWidth > 1023 && e.detail === 0 ) {
						return;
					}

					if ( window.innerWidth > 1023 && pointerInteraction ) {
						const openedByHover = hoverOpenedItems.indexOf( parent ) !== -1;

						if ( openedByHover || ! parent.classList.contains( 'is-open' ) ) {
							closeSiblingDropdowns( parent );
							openDropdown( parent );
						} else {
							closeDropdown( parent );
						}

						const hoverIndex = hoverOpenedItems.indexOf( parent );
						if ( hoverIndex !== -1 ) {
							hoverOpenedItems.splice( hoverIndex, 1 );
						}
						return;
					}

					if ( parent.classList.contains( 'is-open' ) ) {
						closeDropdown( parent );
					} else {
						closeSiblingDropdowns( parent );
						openDropdown( parent );
					}
				}
			} );
		} );

		const topLevelParents = siteNavigation.querySelectorAll( '.primary-menu > .menu-item-has-children' );
		topLevelParents.forEach( function ( item ) {
			item.addEventListener( 'mouseenter', function () {
				if ( window.innerWidth > 1023 ) {
					if ( ! item.classList.contains( 'is-open' ) && hoverOpenedItems.indexOf( item ) === -1 ) {
						hoverOpenedItems.push( item );
					}
					closeSiblingDropdowns( item );
					openDropdown( item );
				}
			} );
			item.addEventListener( 'mouseleave', function () {
				if ( window.innerWidth > 1023 ) {
					closeDropdown( item );
				}
			} );
		} );

		siteNavigation.addEventListener( 'focusin', function ( e ) {
			if ( window.innerWidth <= 1023 ) {
				return;
			}
			if ( pointerInteraction ) {
				return;
			}
			const targetItem = e.target.closest( '.primary-menu > .menu-item-has-children' );
			closeSiblingDropdowns( targetItem );
			if ( targetItem && ! targetItem.classList.contains( 'is-open' ) ) {
				openDropdown( targetItem );
			}
		} );

		siteNavigation.addEventListener( 'focusout', function () {
			if ( window.innerWidth <= 1023 ) {
				return;
			}
			setTimeout( function () {
				if ( ! siteNavigation.contains( document.activeElement ) ) {
					closeSiblingDropdowns( null );
				}
			}, 0 );
		} );
	}

	// Close dropdowns when clicking outside
	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.menu-item-has-children' ) ) {
			closeSiblingDropdowns( null );
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

	/* ── Sticky Header Compact State ── */
	const siteHeader = document.querySelector( '.site-header' );

	if ( siteHeader ) {
		let ticking = false;
		const SCROLL_THRESHOLD = 24;

		function updateHeaderState() {
			if ( window.scrollY > SCROLL_THRESHOLD ) {
				siteHeader.classList.add( 'is-scrolled' );
			} else {
				siteHeader.classList.remove( 'is-scrolled' );
			}
			ticking = false;
		}

		updateHeaderState();

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( updateHeaderState );
				ticking = true;
			}
		}, { passive: true } );
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
	function hdsUpdateConsent( grants ) {
		window.dataLayer = window.dataLayer || [];
		if ( typeof window.gtag === 'function' ) {
			window.gtag( 'consent', 'update', grants );
		} else {
			window.dataLayer.push( [ 'consent', 'update', grants ] );
		}
	}

	const cookieBanner = document.getElementById( 'hds-cookie-banner' );
	if ( cookieBanner ) {
		cookieBanner.querySelector( '.hds-cookie-banner__accept' ).addEventListener( 'click', function () {
			document.cookie = `hds_cookie_consent=accepted;path=/;max-age=${60 * 60 * 24 * 365}`;
			hdsUpdateConsent( {
				ad_storage: 'granted',
				ad_user_data: 'granted',
				ad_personalization: 'granted',
				analytics_storage: 'granted',
				functionality_storage: 'granted',
				personalization_storage: 'granted',
				security_storage: 'granted'
			} );
			cookieBanner.hidden = true;
			cookieBanner.setAttribute( 'aria-hidden', 'true' );
		} );

		cookieBanner.querySelector( '.hds-cookie-banner__decline' ).addEventListener( 'click', function () {
			document.cookie = `hds_cookie_consent=declined;path=/;max-age=${60 * 60 * 24 * 365}`;
			hdsUpdateConsent( {
				ad_storage: 'denied',
				ad_user_data: 'denied',
				ad_personalization: 'denied',
				analytics_storage: 'denied',
				functionality_storage: 'granted',
				personalization_storage: 'denied',
				security_storage: 'granted'
			} );
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

	/* ── Service Gallery Slider ── */
	function initServiceSliders() {
		document.querySelectorAll( '.hds-slider' ).forEach( function ( slider ) {
			const viewport = slider.querySelector( '.hds-slider__viewport' );
			const prevBtn = slider.querySelector( '.hds-slider__arrow--prev' );
			const nextBtn = slider.querySelector( '.hds-slider__arrow--next' );
			const dotsWrap = slider.querySelector( '.hds-slider__dots' );
			const slides = Array.prototype.slice.call( slider.querySelectorAll( '.hds-slider__slide' ) );

			if ( ! viewport || ! prevBtn || ! nextBtn || ! dotsWrap || ! slides.length ) {
				return;
			}

			const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );
			const dots = [];
			let currentIndex = 0;
			let scrollTicking = false;
			let resizeTicking = false;

			slides.forEach( function ( slide, index ) {
				const dot = document.createElement( 'button' );
				dot.type = 'button';
				dot.className = 'hds-slider__dot';
				dot.setAttribute( 'aria-label', `Dienst ${ index + 1 }` );
				dot.addEventListener( 'click', function () {
					goTo( index, true );
				} );
				dotsWrap.appendChild( dot );
				dots.push( dot );
			} );

			function slideStep() {
				if ( slides.length < 2 ) {
					return viewport.clientWidth;
				}
				return slides[ 1 ].getBoundingClientRect().left - slides[ 0 ].getBoundingClientRect().left;
			}

			function goTo( index, smooth ) {
				currentIndex = Math.max( 0, Math.min( index, slides.length - 1 ) );
				const target = slides[ currentIndex ].getBoundingClientRect().left - viewport.getBoundingClientRect().left + viewport.scrollLeft;
				const behavior = smooth && ! reduceMotion.matches ? 'smooth' : 'auto';
				viewport.scrollTo( {
					left: target,
					behavior: behavior,
				} );
			}

			function updateState() {
				const step = slideStep();
				const rawIndex = step > 0 ? Math.round( viewport.scrollLeft / step ) : 0;
				currentIndex = Math.max( 0, Math.min( rawIndex, slides.length - 1 ) );

				prevBtn.disabled = viewport.scrollLeft <= 1;
				nextBtn.disabled = viewport.scrollLeft >= viewport.scrollWidth - viewport.clientWidth - 1;

				dots.forEach( function ( dot, index ) {
					dot.classList.toggle( 'is-active', index === currentIndex );
					if ( index === currentIndex ) {
						dot.setAttribute( 'aria-current', 'true' );
					} else {
						dot.removeAttribute( 'aria-current' );
					}
				} );
			}

			viewport.addEventListener( 'scroll', function () {
				if ( scrollTicking ) {
					return;
				}
				scrollTicking = true;
				window.requestAnimationFrame( function () {
					scrollTicking = false;
					updateState();
				} );
			}, { passive: true } );

			viewport.addEventListener( 'keydown', function ( event ) {
				if ( event.key === 'ArrowRight' ) {
					event.preventDefault();
					goTo( currentIndex + 1, true );
				} else if ( event.key === 'ArrowLeft' ) {
					event.preventDefault();
					goTo( currentIndex - 1, true );
				}
			} );

			prevBtn.addEventListener( 'click', function () {
				goTo( currentIndex - 1, true );
			} );

			nextBtn.addEventListener( 'click', function () {
				goTo( currentIndex + 1, true );
			} );

			window.addEventListener( 'resize', function () {
				if ( resizeTicking ) {
					return;
				}
				resizeTicking = true;
				window.requestAnimationFrame( function () {
					resizeTicking = false;
					goTo( currentIndex, false );
					updateState();
				} );
			} );

			updateState();
		} );
	}

	initServiceSliders();

/* ── Branding Video: autoplay + reduced motion ── */
	document.querySelectorAll( '.hds-branding-video__player' ).forEach( function ( video ) {
		if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			video.removeAttribute( 'autoplay' );
			video.pause();
		} else if ( video.hasAttribute( 'autoplay' ) ) {
			video.play().catch( function () {} );
		}
	} );

}() );
