<?php
/**
 * Optional WooCommerce support. Every function here is a no-op when
 * WooCommerce is inactive — the theme never assumes WooCommerce exists.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! busly_is_wc_active() ) {
	return;
}

/**
 * Sensible defaults matching the Busly grid (3-up on the demo shop).
 */
function busly_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 3,
				'min_columns'     => 1,
				'max_columns'     => 4,
			),
		)
	);
}
add_action( 'after_setup_theme', 'busly_woocommerce_setup' );

/**
 * Busly renders its own wrapper markup (matching header.php/footer.php)
 * instead of WooCommerce's default theme wrapper hooks.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Open the Busly content wrapper before WooCommerce content.
 */
function busly_wc_wrapper_start() {
	$layout = busly_get_option( 'woo_layout', 'right-sidebar' );
	echo '<main id="primary" class="busly-main busly-woo-main busly-layout-' . esc_attr( $layout ) . '"><div class="wrap"><div class="busly-content-grid">';
	echo '<div class="busly-content-col">';
}
add_action( 'woocommerce_before_main_content', 'busly_wc_wrapper_start', 10 );

/**
 * Close the Busly content wrapper, optionally rendering the shop sidebar.
 */
function busly_wc_wrapper_end() {
	echo '</div>'; // .busly-content-col

	$layout = busly_get_option( 'woo_layout', 'right-sidebar' );
	if ( 'no-sidebar' !== $layout && is_active_sidebar( 'sidebar-shop' ) ) {
		echo '<aside class="busly-sidebar busly-woo-sidebar" aria-label="' . esc_attr__( 'Shop sidebar', 'busly' ) . '">';
		dynamic_sidebar( 'sidebar-shop' );
		echo '</aside>';
	}

	echo '</div></div></main>';
}
add_action( 'woocommerce_after_main_content', 'busly_wc_wrapper_end', 10 );

/**
 * Match the Busly button styling on all WooCommerce buttons.
 *
 * @param array $classes Existing classes.
 * @return array
 */
function busly_wc_button_classes( $classes ) {
	$classes[] = 'busly-btn';
	$classes[] = 'busly-btn--primary';
	return $classes;
}
add_filter( 'woocommerce_button_class', 'busly_wc_button_classes' );

/**
 * Products per row / per page from Busly Theme Settings.
 *
 * @return int
 */
function busly_wc_loop_columns() {
	return (int) busly_get_option( 'woo_columns', 3 );
}
add_filter( 'loop_shop_columns', 'busly_wc_loop_columns' );

/**
 * Reduce WooCommerce's default 15px gallery gap to match the Busly card grid.
 */
function busly_wc_gallery_thumb_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'busly_wc_gallery_thumb_columns' );

/**
 * Mini-cart / notice styling hook — adds a Busly class to the cart fragment
 * wrapper without touching WooCommerce core templates.
 *
 * @param array $classes Body classes.
 * @return array
 */
function busly_wc_body_class( $classes ) {
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'busly-woocommerce-page';
	}
	return $classes;
}
add_filter( 'body_class', 'busly_wc_body_class' );

/**
 * Number of related products, tuned to the Busly 4-col grid.
 *
 * @param array $args WooCommerce related-products query args.
 * @return array
 */
function busly_wc_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'busly_wc_related_products_args' );
