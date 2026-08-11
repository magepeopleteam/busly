/**
 * Busly — navigation.js
 * Sticky header on scroll, mobile menu panel, back-to-top button and the
 * Busly FAQ widget's accordion. Namespaced under `Busly` — no global leaks.
 * Progressive enhancement only: every element here still has a working,
 * markup-only fallback (menu links work with JS disabled, FAQ answers are
 * simply always visible, etc.) per PHASE 47.
 *
 * @package Busly
 */
( function () {
	'use strict';

	var Busly = window.Busly || {};

	/**
	 * Header goes solid once the page scrolls past the hero.
	 */
	function initStickyHeader() {
		var header = document.getElementById( 'busly-header' );
		if ( ! header ) {
			return;
		}

		function onScroll() {
			var solid = window.scrollY > 40 || ! document.body.classList.contains( 'busly-header-transparent' );
			header.classList.toggle( 'solid', solid );
		}

		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/**
	 * Slide-in mobile navigation panel.
	 */
	function initMobileMenu() {
		var toggle = document.querySelector( '.hdr-burger' );
		var panel  = document.querySelector( '.busly-mobile-panel' );
		var close  = document.querySelector( '.busly-mobile-panel-close' );

		if ( ! toggle || ! panel ) {
			return;
		}

		function open() {
			panel.classList.add( 'is-open' );
			document.body.classList.add( 'busly-mobile-open' );
			toggle.setAttribute( 'aria-expanded', 'true' );
		}

		function closePanel() {
			panel.classList.remove( 'is-open' );
			document.body.classList.remove( 'busly-mobile-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
		}

		toggle.addEventListener( 'click', open );
		if ( close ) {
			close.addEventListener( 'click', closePanel );
		}

		panel.querySelectorAll( '.menu-item-has-children > a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var parentLi = link.parentElement;
				var submenu  = parentLi.querySelector( ':scope > .sub-menu' );
				if ( submenu ) {
					e.preventDefault();
					parentLi.classList.toggle( 'is-expanded' );
				}
			} );
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) {
				closePanel();
			}
		} );
	}

	/**
	 * "Back to top" floating button.
	 */
	function initBackToTop() {
		var btn = document.querySelector( '.busly-back-to-top' );
		if ( ! btn ) {
			return;
		}

		window.addEventListener(
			'scroll',
			function () {
				btn.classList.toggle( 'is-visible', window.scrollY > 600 );
			},
			{ passive: true }
		);

		btn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	/**
	 * FAQ accordion (Busly FAQ Elementor widget) — keyboard accessible,
	 * degrades to "always open" if this script never runs (no JS class
	 * added, .busly-faq-a has no max-height clamp without .is-open logic
	 * because the CSS only clamps height inside .busly-faq-item, and the
	 * clamp is applied via a class this script toggles).
	 */
	function initFaqAccordion() {
		document.querySelectorAll( '.busly-faq-item' ).forEach( function ( item ) {
			var question = item.querySelector( '.busly-faq-q' );
			if ( ! question ) {
				return;
			}
			question.addEventListener( 'click', function () {
				var wasOpen = item.classList.contains( 'is-open' );
				item.closest( '.busly-faq' ).querySelectorAll( '.busly-faq-item.is-open' ).forEach( function ( openItem ) {
					if ( openItem !== item ) {
						openItem.classList.remove( 'is-open' );
						openItem.querySelector( '.busly-faq-q' ).setAttribute( 'aria-expanded', 'false' );
					}
				} );
				item.classList.toggle( 'is-open', ! wasOpen );
				question.setAttribute( 'aria-expanded', String( ! wasOpen ) );
			} );
		} );
	}

	/**
	 * Elementor-visibility-safe init: on the front end, and re-run inside
	 * the Elementor editor whenever a Busly widget is rendered/updated.
	 */
	function initAll() {
		initStickyHeader();
		initMobileMenu();
		initBackToTop();
		initFaqAccordion();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}

	if ( window.elementorFrontend ) {
		window.jQuery && window.jQuery( window ).on( 'elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/global', initFaqAccordion );
		} );
	}

	window.Busly = Busly;
} )();
