<?php
/**
 * General third-party plugin compatibility that doesn't warrant its own file
 * (caching plugins, translation plugins, block/page-builder edge cases).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tell caching plugins (WP Rocket, W3TC, WP Super Cache, LiteSpeed Cache…)
 * not to cache pages that render the plugin's live seat map / cart state,
 * since availability changes per request.
 *
 * @return bool
 */
function busly_is_dynamic_booking_request() {
	if ( ! busly_is_bus_booking_active() ) {
		return false;
	}

	return is_singular( 'wbtm_bus' )
		|| get_query_var( 'bussearchlist' )
		|| ( busly_is_wc_active() && ( is_cart() || is_checkout() || is_account_page() ) );
}

/**
 * WP Rocket "donotcachepage" convention.
 */
function busly_donotcachepage() {
	if ( busly_is_dynamic_booking_request() && ! defined( 'DONOTCACHEPAGE' ) ) {
		define( 'DONOTCACHEPAGE', true );
	}
}
add_action( 'template_redirect', 'busly_donotcachepage' );

/**
 * Nginx-helper / LiteSpeed / generic "no-cache" response header hint for
 * booking pages, mirroring the WooCommerce cart/checkout convention.
 *
 * @param array $headers Existing headers.
 * @return array
 */
function busly_no_cache_headers( $headers ) {
	if ( busly_is_dynamic_booking_request() ) {
		$headers['Cache-Control'] = 'no-cache, no-store, must-revalidate, max-age=0';
	}
	return $headers;
}
add_filter( 'wp_headers', 'busly_no_cache_headers' );

/**
 * WPML / Polylang: register the theme's translatable Customizer/theme-mod
 * strings so translation plugins pick them up automatically. Silently skips
 * if neither plugin is present.
 */
function busly_register_translatable_strings() {
	if ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( 'Busly', 'Hero Title', get_theme_mod( 'busly_hero_title', '' ) );
	}
}
add_action( 'init', 'busly_register_translatable_strings' );

/**
 * Classic-editor / Gutenberg: keep the plugin's own
 * `disable_block_editor` setting (wbtm_global_settings) authoritative for
 * the bus CPT rather than layering a second, conflicting theme setting.
 *
 * @param bool   $use_block_editor Whether the block editor is enabled.
 * @param string $post_type        Post type being checked.
 * @return bool
 */
function busly_respect_wbtm_editor_choice( $use_block_editor, $post_type ) {
	if ( 'wbtm_bus' === $post_type && busly_is_bus_booking_active() ) {
		$disabled = busly_get_wbtm_setting( 'wbtm_global_settings', 'disable_block_editor', 'no' );
		if ( 'yes' === $disabled ) {
			return false;
		}
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'busly_respect_wbtm_editor_choice', 10, 2 );
