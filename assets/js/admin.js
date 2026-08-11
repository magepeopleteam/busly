/**
 * Busly — admin.js
 * Theme Settings tab switching + plugin activate/install buttons on the
 * Busly Dashboard. Setup Wizard has its own script (setup-wizard.js).
 *
 * @package Busly
 */
( function ( $ ) {
	'use strict';

	$( function () {

		// Settings tab switching (no page reload, hash-based deep link).
		var $nav   = $( '.busly-settings-nav' );
		var $panels = $( '.busly-settings-panel[data-tab]' );

		function activateTab( tab ) {
			if ( ! tab ) {
				return;
			}
			$nav.find( 'a' ).removeClass( 'is-active' );
			$nav.find( 'a[data-tab="' + tab + '"]' ).addClass( 'is-active' );
			$panels.hide().filter( '[data-tab="' + tab + '"]' ).show();
		}

		if ( $nav.length ) {
			$nav.on( 'click', 'a[data-tab]', function ( e ) {
				e.preventDefault();
				var tab = $( this ).data( 'tab' );
				window.location.hash = tab;
				activateTab( tab );
			} );

			var initialTab = window.location.hash.replace( '#', '' ) || $nav.find( 'a' ).first().data( 'tab' );
			activateTab( initialTab );
		}

		// Requirement row: install/activate a wordpress.org plugin via AJAX.
		$( '.busly-req-action' ).on( 'click', function ( e ) {
			e.preventDefault();
			var $btn    = $( this );
			var action  = $btn.data( 'action' ); // 'install' | 'activate'
			var slug    = $btn.data( 'slug' );
			var file    = $btn.data( 'file' );
			var original = $btn.text();

			$btn.prop( 'disabled', true ).text( 'install' === action ? busluAdminL10n().installing : busluAdminL10n().activating );

			$.post( ajaxurl, {
				action: 'busly_' + action + '_plugin',
				slug: slug,
				file: file,
				nonce: window.buslyAdmin ? window.buslyAdmin.nonce : ''
			} ).done( function ( response ) {
				if ( response && response.success ) {
					window.location.reload();
				} else {
					$btn.prop( 'disabled', false ).text( original );
					window.alert( ( response && response.data && response.data.message ) || 'Something went wrong.' );
				}
			} ).fail( function () {
				$btn.prop( 'disabled', false ).text( original );
				window.alert( 'Request failed. Please try again.' );
			} );
		} );

		function busluAdminL10n() {
			return window.buslyAdmin && window.buslyAdmin.i18n ? window.buslyAdmin.i18n : { installing: 'Installing…', activating: 'Activating…' };
		}

		// Color inputs: keep a visible hex readout next to the <input type=color>.
		$( '.busly-field-row input[type="color"]' ).each( function () {
			var $input = $( this );
			var $text  = $( '<input type="text" class="busly-color-hex" style="width:90px;margin-left:8px;" />' ).val( $input.val() );
			$input.after( $text );
			$input.on( 'input', function () { $text.val( $input.val() ); } );
			$text.on( 'change', function () {
				if ( /^#[0-9a-f]{6}$/i.test( $text.val() ) ) {
					$input.val( $text.val() );
				}
			} );
		} );
	} );

} )( jQuery );
