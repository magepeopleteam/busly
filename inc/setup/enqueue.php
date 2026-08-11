<?php
/**
 * Front-end and admin asset registration/enqueueing.
 *
 * Every stylesheet is registered (not enqueued) up front so Elementor
 * widgets can declare them as style_depends and get them loaded only when a
 * widget is actually placed on the page (see class-widget-base.php). The
 * sitewide baseline (base/layout/components/header/footer/blog) is always
 * enqueued; everything else is conditional — see PHASE 32 performance notes.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register every theme stylesheet/script handle (without enqueueing all of
 * them) so both busly_enqueue_assets() and Elementor's style_depends can
 * reference the handles safely regardless of load order.
 */
function busly_register_assets() {
	$css = BUSLY_URI . '/assets/css/';
	$js  = BUSLY_URI . '/assets/js/';
	$v   = BUSLY_VERSION;

	wp_register_style( 'busly-base', $css . 'base.css', array(), $v );
	wp_register_style( 'busly-layout', $css . 'layout.css', array( 'busly-base' ), $v );
	wp_register_style( 'busly-components', $css . 'components.css', array( 'busly-base' ), $v );
	wp_register_style( 'busly-header', $css . 'header.css', array( 'busly-base' ), $v );
	wp_register_style( 'busly-footer', $css . 'footer.css', array( 'busly-base' ), $v );
	wp_register_style( 'busly-hero', $css . 'hero.css', array( 'busly-base' ), $v );
	wp_register_style( 'busly-sections', $css . 'sections.css', array( 'busly-base', 'busly-components' ), $v );
	wp_register_style( 'busly-blog', $css . 'blog.css', array( 'busly-base', 'busly-components' ), $v );
	wp_register_style( 'busly-booking', $css . 'booking.css', array( 'busly-base', 'busly-components' ), $v );
	wp_register_style( 'busly-woocommerce', $css . 'woocommerce.css', array( 'busly-base', 'busly-components' ), $v );
	wp_register_style( 'busly-responsive', $css . 'responsive.css', array( 'busly-base' ), $v );

	if ( is_rtl() ) {
		wp_register_style( 'busly-rtl', $css . 'rtl.css', array( 'busly-responsive' ), $v );
	}

	wp_register_script( 'busly-navigation', $js . 'navigation.js', array(), $v, true );
	wp_register_script( 'busly-admin', $js . 'admin.js', array( 'jquery' ), $v, true );
	wp_register_script( 'busly-setup-wizard', $js . 'setup-wizard.js', array( 'jquery' ), $v, true );
}
add_action( 'wp_enqueue_scripts', 'busly_register_assets', 1 );
add_action( 'admin_enqueue_scripts', 'busly_register_assets', 1 );

/**
 * Enqueue the sitewide baseline + page-conditional bundles.
 */
function busly_enqueue_assets() {
	// Always-on baseline: reset, tokens, layout grid, shared components, chrome.
	wp_enqueue_style( 'busly-base' );
	wp_enqueue_style( 'busly-layout' );
	wp_enqueue_style( 'busly-components' );
	wp_enqueue_style( 'busly-header' );
	wp_enqueue_style( 'busly-footer' );
	wp_enqueue_style( 'busly-responsive' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'busly-rtl' );
	}

	// Hero + homepage sections: only where a Busly section widget is used.
	// (Elementor widgets pull these in themselves via get_style_depends();
	// this covers the no-Elementor / shortcode-in-content fallback case.)
	if ( is_front_page() || busly_page_has_busly_shortcode() ) {
		wp_enqueue_style( 'busly-hero' );
		wp_enqueue_style( 'busly-sections' );
	}

	// Blog: home, single posts, archives, search.
	if ( is_home() || is_singular( 'post' ) || is_archive() || is_search() || is_404() ) {
		wp_enqueue_style( 'busly-blog' );
	}

	// Booking UI skin: only where the plugin's own markup will actually render.
	if ( busly_should_load_booking_assets() ) {
		wp_enqueue_style( 'busly-booking' );
	}

	// WooCommerce skin: shop/product/cart/checkout/account only.
	if ( busly_is_wc_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style( 'busly-woocommerce' );
	}

	wp_enqueue_script( 'busly-navigation' );
	wp_localize_script(
		'busly-navigation',
		'busly',
		array(
			'isRTL' => is_rtl(),
		)
	);

	// Inline CSS custom properties from Busly Theme Settings — always last.
	wp_add_inline_style( 'busly-base', busly_generate_css_variables() );

	if ( 'yes' === busly_get_option( 'perf_comment_reply_defer', 'yes' ) ) {
		wp_script_add_data( 'comment-reply', 'defer', true );
	}
	wp_script_add_data( 'busly-navigation', 'defer', true );
}
add_action( 'wp_enqueue_scripts', 'busly_enqueue_assets', 20 );

/**
 * Very small, cheap heuristic: does the current singular post/page contain
 * a Busly-relevant shortcode in raw post_content? (Elementor pages are
 * covered separately through style_depends.)
 *
 * @return bool
 */
function busly_page_has_busly_shortcode() {
	if ( ! is_singular() ) {
		return false;
	}
	$post = get_queried_object();
	if ( ! ( $post instanceof WP_Post ) ) {
		return false;
	}
	foreach ( array( 'wbtm-bus-list', 'wbtm-bus-search-form', 'wbtm-bus-search' ) as $tag ) {
		if ( has_shortcode( $post->post_content, $tag ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Build the :root{ --busly-...: ...; } custom-property block from Theme
 * Settings, so every stylesheet can reference the site owner's chosen colors/
 * radius/container width without a single hard-coded value. See PHASE 31.
 *
 * @return string Raw CSS (already safe — every value is sanitized on save).
 */
function busly_generate_css_variables() {
	$vars = array(
		'--busly-navy'      => busly_get_option( 'color_navy', '#0c1a52' ),
		'--busly-primary'   => busly_get_option( 'color_primary', '#1d3d87' ),
		'--busly-primary-2' => busly_get_option( 'color_primary_light', '#2f56c7' ),
		'--busly-primary-3' => busly_get_option( 'color_primary_lighter', '#3b6ae8' ),
		'--busly-accent'    => busly_get_option( 'color_accent', '#f05a28' ),
		'--busly-accent-hover' => busly_get_option( 'color_accent_hover', '#e04a18' ),
		'--busly-heading'   => busly_get_option( 'color_heading', '#0f172a' ),
		'--busly-text'      => busly_get_option( 'color_text', '#64748b' ),
		'--busly-border'    => busly_get_option( 'color_border', '#e2e8f0' ),
		'--busly-background' => busly_get_option( 'color_background', '#f4f6fb' ),
		'--busly-radius'    => absint( busly_get_option( 'radius', 20 ) ) . 'px',
		'--busly-container' => absint( busly_get_option( 'container_width', 1180 ) ) . 'px',
		'--busly-font-primary' => busly_get_option( 'font_body', "'Plus Jakarta Sans', sans-serif" ),
	);

	$vars = apply_filters( 'busly_css_variables', $vars );

	$css = ':root{';
	foreach ( $vars as $prop => $value ) {
		$prop = preg_replace( '/[^a-z0-9\-]/i', '', (string) $prop );
		if ( ! $prop ) {
			continue;
		}
		$css .= sprintf( '%s:%s;', $prop, busly_sanitize_css_value( $value ) );
	}
	$css .= '}';

	return $css;
}

/**
 * Minimal allow-list sanitizer for a CSS custom-property value: hex colors,
 * rgb()/rgba(), numbers+units, and quoted font-family stacks only.
 *
 * @param string $value Raw value from a sanitized option.
 * @return string
 */
function busly_sanitize_css_value( $value ) {
	$value = (string) $value;

	if ( preg_match( '/^#[0-9a-f]{3,8}$/i', $value ) ) {
		return $value;
	}
	if ( preg_match( '/^rgba?\([0-9.,%\s]+\)$/i', $value ) ) {
		return $value;
	}
	if ( preg_match( '/^-?[0-9.]+(px|rem|em|%|vh|vw)?$/', $value ) ) {
		return $value;
	}
	if ( preg_match( '/^[a-z0-9 ,\'"\-]+$/i', $value ) ) {
		return $value;
	}

	return '';
}

/**
 * Admin-side assets, scoped strictly to Busly's own screens.
 *
 * @param string $hook Current admin page hook.
 */
function busly_admin_assets( $hook ) {
	if ( false === strpos( (string) $hook, 'busly' ) ) {
		return;
	}

	wp_enqueue_style( 'busly-admin', BUSLY_URI . '/assets/css/admin.css', array(), BUSLY_VERSION );
	wp_enqueue_script( 'busly-admin' );
	wp_localize_script(
		'busly-admin',
		'buslyAdmin',
		array(
			'nonce' => wp_create_nonce( 'busly_admin_actions' ),
			'i18n'  => array(
				'installing' => __( 'Installing…', 'busly' ),
				'activating' => __( 'Activating…', 'busly' ),
			),
		)
	);

	if ( false !== strpos( (string) $hook, 'setup-wizard' ) ) {
		wp_enqueue_script( 'busly-setup-wizard' );
		wp_localize_script(
			'busly-setup-wizard',
			'buslySetup',
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'busly_demo_import' ),
				'pluginNonce' => wp_create_nonce( 'busly_admin_actions' ),
				'i18n'        => array(
					'importing'      => __( 'Importing…', 'busly' ),
					'done'           => __( 'Done!', 'busly' ),
					'error'          => __( 'Something went wrong. Please try again.', 'busly' ),
					'confirmImport'  => __( 'This will create new pages/menus and may set a new homepage. Existing content is never deleted. Continue?', 'busly' ),
					'activating'     => __( 'Activating…', 'busly' ),
					'installing'     => __( 'Installing…', 'busly' ),
				),
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', 'busly_admin_assets' );
