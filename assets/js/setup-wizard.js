/**
 * Busly — setup-wizard.js
 * Drives the Requirements-check + Demo Import steps of Busly → Setup Wizard.
 * Every request is nonce-verified server-side (see class-setup-wizard.php /
 * class-demo-import.php) — this file only orchestrates the UI.
 *
 * @package Busly
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var $wizard = $( '.busly-wizard' );
		if ( ! $wizard.length || 'undefined' === typeof buslySetup ) {
			return;
		}

		var $importBtn = $( '#busly-run-import' );
		var $progress  = $( '.busly-wizard-progress-bar' );
		var $log       = $( '.busly-wizard-log' );

		var STEPS = [
			{ key: 'pages', label: 'Creating demo pages' },
			{ key: 'menus', label: 'Building navigation menus' },
			{ key: 'homepage', label: 'Assembling the Elementor homepage' },
			{ key: 'options', label: 'Applying Theme Settings' },
			{ key: 'finish', label: 'Finishing up' }
		];

		function log( message, isError ) {
			var $line = $( '<div>' ).addClass( isError ? 'err' : 'ok' ).text( ( isError ? '✕ ' : '✓ ' ) + message );
			$log.append( $line );
			$log.scrollTop( $log[ 0 ].scrollHeight );
		}

		function setProgress( percent ) {
			$progress.css( 'width', percent + '%' );
		}

		function runStep( index ) {
			if ( index >= STEPS.length ) {
				setProgress( 100 );
				log( buslySetup.i18n.done );
				$importBtn.prop( 'disabled', false ).text( buslySetup.i18n.done );
				$( '.busly-wizard-finish-links' ).slideDown();
				return;
			}

			var step = STEPS[ index ];

			$.post( buslySetup.ajaxUrl, {
				action: 'busly_demo_import_step',
				nonce: buslySetup.nonce,
				step: step.key
			} ).done( function ( response ) {
				if ( response && response.success ) {
					log( ( response.data && response.data.message ) || step.label );
					setProgress( Math.round( ( ( index + 1 ) / STEPS.length ) * 100 ) );
					runStep( index + 1 );
				} else {
					log( ( response && response.data && response.data.message ) || step.label, true );
					$importBtn.prop( 'disabled', false ).text( buslySetup.i18n.error );
				}
			} ).fail( function () {
				log( step.label, true );
				$importBtn.prop( 'disabled', false ).text( buslySetup.i18n.error );
			} );
		}

		$importBtn.on( 'click', function ( e ) {
			e.preventDefault();

			if ( $importBtn.data( 'confirm-needed' ) && ! window.confirm( buslySetup.i18n.confirmImport ) ) {
				return;
			}

			$importBtn.prop( 'disabled', true ).text( buslySetup.i18n.importing );
			$log.empty();
			setProgress( 0 );
			runStep( 0 );
		} );

		// Requirements step: reuse the same install/activate handler as the
		// main dashboard (admin.js), scoped here only if admin.js isn't
		// also loaded on this screen.
		$( '.busly-req-action' ).on( 'click', function ( e ) {
			if ( $( this ).hasClass( 'busly-req-external-install' ) ) {
				return; // external install links open in a new tab — don't interfere.
			}
			if ( $._data && $._data( this, 'events' ) ) {
				return; // admin.js already bound a handler.
			}
			e.preventDefault();
			var $btn   = $( this );
			var action = $btn.data( 'action' );
			var slug   = $btn.data( 'slug' );
			var file   = $btn.data( 'file' );
			var nonce  = $btn.data( 'nonce' ) || ( typeof buslySetup !== 'undefined' ? buslySetup.pluginNonce : '' );

			$btn.prop( 'disabled', true ).text( 'install' === action ? buslySetup.i18n.installing : buslySetup.i18n.activating );

			$.post( ajaxurl, {
				action: 'busly_' + action + '_plugin',
				slug: slug,
				file: file,
				nonce: nonce
			} ).done( function ( response ) {
				if ( response && response.success ) {
					window.location.reload();
				} else {
					var msg = ( response && response.data && response.data.message ) || buslySetup.i18n.error;
					window.alert( msg );
					window.location.reload();
				}
			} ).fail( function ( jqXHR, textStatus, errorThrown ) {
				var msg = buslySetup.i18n.error;
				if ( jqXHR.responseJSON && jqXHR.responseJSON.data && jqXHR.responseJSON.data.message ) {
					msg = jqXHR.responseJSON.data.message;
				}
				window.alert( msg );
				window.location.reload();
			} );
		} );
	} );

} )( jQuery );
