<?php
/**
 * Structural action hooks fired by header.php / footer.php / page templates.
 *
 * These are intentionally empty by default (or attach only a minimal default
 * callback) — they exist so child themes and site-specific plugins can inject
 * markup without ever touching a Busly template file. Prefix: busly_ (PHASE 40).
 *
 * Available hooks:
 *   busly_before_header / busly_after_header
 *   busly_before_content / busly_after_content
 *   busly_before_footer / busly_after_footer
 *   busly_before_booking_form / busly_after_booking_form  (fired around every
 *     plugin shortcode/template Busly renders — see class-widget-bus-search.php
 *     and templates/single_page/*.php)
 *   busly_before_bus_card / busly_after_bus_card           (fired by the
 *     Busly Bus Listing / Featured Routes widgets around each card)
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * No default callbacks are attached here on purpose: these are pure
 * extension points. The "booking engine missing" fallback notice is printed
 * inline by busly_render_wbtm_shortcode() (bus-booking.php) at the exact
 * spot the shortcode would have rendered, so nothing else needs to react to
 * busly_before_booking_form/busly_after_booking_form by default — attaching
 * a second notice here would just print it twice.
 */
