<?php
/**
 * Nav menu locations.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme's nav menu locations.
 */
function busly_register_menus() {
	register_nav_menus(
		array(
			'primary'   => __( 'Primary Menu', 'busly' ),
			'mobile'    => __( 'Mobile Menu (falls back to Primary)', 'busly' ),
			'footer-1'  => __( 'Footer Column — Company', 'busly' ),
			'footer-2'  => __( 'Footer Column — Travel', 'busly' ),
			'footer-3'  => __( 'Footer Column — Support', 'busly' ),
		)
	);
}
add_action( 'init', 'busly_register_menus' );

/**
 * Fallback used by wp_nav_menu() when the "primary" location has no menu
 * assigned yet — avoids a blank header before Setup Wizard runs.
 */
function busly_primary_menu_fallback() {
	echo '<ul id="busly-primary-menu-fallback" class="nav">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul>';
}
