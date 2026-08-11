<?php
/**
 * Contact Form 7 / WPForms styling compatibility. Busly does not ship its
 * own form-processing widget — it re-skins whichever plugin is active so
 * forms match the theme's inputs/buttons, per PHASE 34.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a Busly wrapper class around Contact Form 7 forms so booking.css /
 * components.css selectors can restyle inputs without touching CF7 core.
 *
 * @param string $html Rendered form HTML.
 * @return string
 */
function busly_cf7_wrapper_class( $html ) {
	return '<div class="busly-form busly-form--cf7">' . $html . '</div>';
}
add_filter( 'wpcf7_form_elements', 'busly_cf7_wrapper_class' );

/**
 * Same treatment for WPForms.
 *
 * @param string $output Rendered form HTML.
 * @param array  $form_data Form data.
 * @return string
 */
function busly_wpforms_wrapper_class( $output, $form_data ) { // phpcs:ignore
	return '<div class="busly-form busly-form--wpforms">' . $output . '</div>';
}
add_filter( 'wpforms_frontend_output', 'busly_wpforms_wrapper_class', 20, 2 );

/**
 * Whether any recognised form plugin is active — used by the Busly Contact
 * widget to show a real form instead of a "configure a form plugin" notice.
 *
 * @return bool
 */
function busly_has_form_plugin() {
	return defined( 'WPCF7_VERSION' ) || defined( 'WPFORMS_VERSION' );
}
