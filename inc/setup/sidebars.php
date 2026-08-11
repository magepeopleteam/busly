<?php
/**
 * Widget areas.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme's widget areas (blog sidebar + 4 footer columns).
 */
function busly_register_sidebars() {
	$card = array(
		'before_widget' => '<div id="%1$s" class="busly-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="busly-widget-title">',
		'after_title'   => '</h4>',
	);

	register_sidebar(
		array_merge(
			$card,
			array(
				'name'        => __( 'Blog Sidebar', 'busly' ),
				'id'          => 'sidebar-blog',
				'description' => __( 'Shown on posts and archives when the Blog layout uses a sidebar.', 'busly' ),
			)
		)
	);

	register_sidebar(
		array_merge(
			$card,
			array(
				'name'        => __( 'Shop Sidebar', 'busly' ),
				'id'          => 'sidebar-shop',
				'description' => __( 'Shown on the WooCommerce shop and product pages, if enabled in Busly Theme Settings.', 'busly' ),
			)
		)
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf(
					/* translators: %d: footer column number */
					__( 'Footer Column %d', 'busly' ),
					$i
				),
				'id'            => 'sidebar-footer-' . $i,
				'description'   => __( 'Optional widget-based footer column. Leave empty to use the Footer Menu instead.', 'busly' ),
				'before_widget' => '<div id="%1$s" class="ftr-col %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>',
			)
		);
	}
}
add_action( 'widgets_init', 'busly_register_sidebars' );
