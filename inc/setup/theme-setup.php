<?php
/**
 * Core theme supports, text domain, content width.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme support, load translations, set content width.
 */
function busly_setup() {

	load_theme_textdomain( 'busly', BUSLY_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'f4f6fb',
		)
	);

	// WooCommerce declarations — safe no-ops if WooCommerce is inactive.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	add_editor_style( 'assets/css/editor-style.css' );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1180; // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	}

	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'busly_setup' );

/**
 * Register the block-editor / theme.json powered pattern categories used by
 * the Busly starter content (kept minimal — the homepage is Elementor-built).
 */
function busly_block_pattern_categories() {
	register_block_pattern_category(
		'busly',
		array( 'label' => __( 'Busly', 'busly' ) )
	);
}
add_action( 'init', 'busly_block_pattern_categories' );

/**
 * Declare the plugins this theme is built around so WordPress.org / the
 * dashboard can surface a friendly recommendation list. This does NOT
 * force-install anything by itself — see inc/admin/class-requirements.php.
 *
 * @return array{required: array<string,array>, recommended: array<string,array>}
 */
function busly_get_companion_plugins() {
	return array(
		'required'    => array(
			'elementor' => array(
				'name' => 'Elementor',
				'slug' => 'elementor',
				'file' => 'elementor/elementor.php',
				'wp'   => true, // available on wordpress.org, installable via Plugin_Upgrader.
			),
			'wbtm'      => array(
				'name' => 'Bus Ticket Booking with Seat Reservation',
				'slug' => 'bus-ticket-booking-with-seat-reservation',
				'file' => 'bus-ticket-booking-with-seat-reservation/woocommerce-bus.php',
				'wp'   => false, // distributed outside wordpress.org — detect only, no auto-install.
			),
		),
		'recommended' => array(
			'woocommerce' => array(
				'name' => 'WooCommerce',
				'slug' => 'woocommerce',
				'file' => 'woocommerce/woocommerce.php',
				'wp'   => true,
			),
		),
	);
}
