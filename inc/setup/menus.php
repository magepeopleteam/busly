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
 * Fallback used by wp_nav_menu() when the requested location has no menu
 * assigned yet — avoids a blank header before Setup Wizard runs. Shared by
 * both the desktop nav (needs .nav > ul.nav-menu) and the mobile panel nav
 * (needs .busly-mobile-nav), so it rebuilds the same container/menu classes
 * the caller asked for instead of hard-coding one.
 *
 * @param array $args The wp_nav_menu() args from the calling template part.
 */
function busly_primary_menu_fallback( $args = array() ) {
	$args             = (array) $args;
	$container_class  = ! empty( $args['container_class'] ) ? $args['container_class'] : 'nav';
	$menu_class       = ! empty( $args['menu_class'] ) ? $args['menu_class'] : '';

	printf( '<nav class="%s" aria-label="%s">', esc_attr( $container_class ), esc_attr__( 'Primary navigation', 'busly' ) );
	printf( '<ul class="%s">', esc_attr( $menu_class ) );
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
		)
	);
	echo '</ul></nav>';
}
