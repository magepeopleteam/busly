<?php
/**
 * Integration layer for the "Bus Ticket Booking with Seat Reservation" plugin
 * (prefix WBTM_, by MagePeople). This file NEVER modifies plugin behaviour —
 * it only detects the plugin, reads its public helpers/options, and decides
 * when the theme should load its own presentation-layer CSS.
 *
 * The plugin's booking engine is itself hard-wired to WooCommerce (search,
 * seat maps and cart work without WooCommerce; add-to-cart/checkout do not),
 * so busly_is_wc_active() is used alongside busly_is_bus_booking_active().
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is the Bus Ticket Booking with Seat Reservation plugin active and loaded?
 *
 * Mirrors the class-exists guards the plugin itself uses internally, so this
 * check is safe to call from anywhere (including before `init`).
 *
 * @return bool
 */
function busly_is_bus_booking_active() {
	return class_exists( 'WBTM_Functions' ) || class_exists( 'WBTM_Global_Function' ) || post_type_exists( 'wbtm_bus' );
}

/**
 * Is WooCommerce active? The bus plugin's cart/checkout/account features are
 * only registered when this is true — see PHASE 1 audit notes in the docs.
 *
 * @return bool
 */
function busly_is_wc_active() {
	return function_exists( 'WC' ) && class_exists( 'WooCommerce' );
}

/**
 * Is the bus plugin's own booking engine (search + cart) actually wired up?
 * This is stricter than busly_is_bus_booking_active() alone, because the
 * plugin registers its CPT/shortcodes/AJAX only inside a WooCommerce-active
 * branch of its bootstrap file.
 *
 * @return bool
 */
function busly_is_booking_engine_ready() {
	return busly_is_bus_booking_active() && busly_is_wc_active();
}

/**
 * Friendly admin notice when a page/section needs the bus plugin but it is
 * missing or WooCommerce isn't active underneath it — never a fatal error.
 *
 * @param string $context Optional short context label for the message.
 * @return string Escaped HTML notice, safe to echo.
 */
function busly_booking_missing_notice( $context = '' ) {
	if ( ! busly_is_bus_booking_active() ) {
		$message = current_user_can( 'activate_plugins' )
			? __( 'This section needs the "Bus Ticket Booking with Seat Reservation" plugin to be installed and activated.', 'busly' )
			: __( 'Bus search is temporarily unavailable.', 'busly' );
	} elseif ( ! busly_is_wc_active() ) {
		$message = current_user_can( 'activate_plugins' )
			? __( 'The Bus Ticket Booking plugin needs WooCommerce active to handle search results, seats and checkout.', 'busly' )
			: __( 'Bus search is temporarily unavailable.', 'busly' );
	} else {
		return '';
	}

	return sprintf(
		'<div class="busly-notice busly-notice--warning">%s%s</div>',
		esc_html( $message ),
		$context ? '<br><small>' . esc_html( $context ) . '</small>' : ''
	);
}

/**
 * Read a Busly-relevant option from the bus plugin's own settings API,
 * falling back to a default when the plugin (or the option) is unavailable.
 *
 * @param string $section Settings array/option name, e.g. 'wbtm_style_settings'.
 * @param string $key     Key inside that settings array.
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function busly_get_wbtm_setting( $section, $key, $default = '' ) {
	if ( class_exists( 'WBTM_Global_Function' ) && method_exists( 'WBTM_Global_Function', 'get_settings' ) ) {
		return WBTM_Global_Function::get_settings( $section, $key, $default );
	}

	$stored = get_option( $section );

	return ( is_array( $stored ) && isset( $stored[ $key ] ) && '' !== $stored[ $key ] ) ? $stored[ $key ] : $default;
}

/**
 * The bus CPT's public label, e.g. "Bus" — configurable in the plugin.
 *
 * @return string
 */
function busly_bus_label() {
	if ( class_exists( 'WBTM_Functions' ) && method_exists( 'WBTM_Functions', 'get_name' ) ) {
		return WBTM_Functions::get_name();
	}

	return __( 'Bus', 'busly' );
}

/**
 * Render one of the plugin's shortcodes safely, with a graceful fallback
 * notice instead of blank output/fatal error when the engine isn't ready.
 *
 * @param string $tag  Shortcode tag, e.g. 'wbtm-bus-search-form' or 'wbtm-bus-list'.
 * @param array  $atts Shortcode attributes.
 * @return string HTML.
 */
function busly_render_wbtm_shortcode( $tag, $atts = array() ) {
	if ( ! busly_is_booking_engine_ready() ) {
		return busly_booking_missing_notice( '[' . $tag . ']' );
	}

	$atts_string = '';
	foreach ( $atts as $key => $value ) {
		if ( '' === $value || null === $value ) {
			continue;
		}
		$atts_string .= sprintf( ' %s="%s"', sanitize_key( $key ), esc_attr( $value ) );
	}

	/**
	 * A page-builder-embedded shortcode isn't literal text inside
	 * post_content, so the plugin's own asset gate (which scans
	 * post_content for the shortcode tag) will not fire. Force it on.
	 */
	add_filter( 'wbtm_load_frontend_assets', '__return_true' );

	return do_shortcode( '[' . $tag . $atts_string . ']' );
}

/**
 * Whether the current request should load Busly's booking.css skin
 * (search form, results cards, seat map, single-bus, my-account dashboard).
 *
 * Mirrors the plugin's own should_load_frontend_assets() heuristics so the
 * theme's presentation layer appears exactly when the plugin's markup does.
 *
 * @return bool
 */
function busly_should_load_booking_assets() {
	if ( ! busly_is_bus_booking_active() ) {
		return false;
	}

	if ( is_singular( array( 'wbtm_bus', 'wbtm_bus_booking' ) ) ) {
		return true;
	}

	if ( get_query_var( 'bussearchlist' ) ) {
		return true;
	}

	if ( busly_is_wc_active() && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return true;
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			foreach ( array( 'wbtm-bus-list', 'wbtm-bus-search-form', 'wbtm-bus-search' ) as $tag ) {
				if ( has_shortcode( $post->post_content, $tag ) ) {
					return true;
				}
			}

			// Elementor-built pages store widgets in post meta, not post_content.
			if ( did_action( 'elementor/loaded' ) && get_post_meta( $post->ID, '_elementor_edit_mode', true ) ) {
				$data = get_post_meta( $post->ID, '_elementor_data', true );
				if ( is_string( $data ) && false !== strpos( $data, 'busly-bus-' ) ) {
					return true;
				}
			}
		}
	}

	return (bool) apply_filters( 'busly_force_load_booking_assets', false );
}

/**
 * Add a body class whenever booking assets/markup are present, so CSS/JS can
 * scope cleanly without any PHP conditional checks on the front end.
 *
 * @param array $classes Existing body classes.
 * @return array
 */
function busly_booking_body_class( $classes ) {
	if ( busly_should_load_booking_assets() ) {
		$classes[] = 'busly-has-booking-ui';
	}

	if ( busly_is_bus_booking_active() && ! busly_is_wc_active() ) {
		$classes[] = 'busly-booking-needs-woocommerce';
	}

	return $classes;
}
add_filter( 'body_class', 'busly_booking_body_class' );

/**
 * Sync the plugin's own "Style Settings" colours (Busly → theme-agnostic
 * defaults set by the site owner in WBTM's settings) onto Busly's CSS custom
 * properties, so a merchant who only touches the plugin's color picker still
 * gets a consistent look. Busly Theme Settings colors always take priority
 * when explicitly set — see inc/theme-options.php.
 *
 * @param array $vars Existing CSS variable map (property => value).
 * @return array
 */
function busly_merge_wbtm_style_vars( $vars ) {
	if ( ! busly_is_bus_booking_active() ) {
		return $vars;
	}

	$theme_color = busly_get_wbtm_setting( 'wbtm_style_settings', 'theme_color', '' );

	if ( $theme_color && empty( $vars['--wbtm-color-theme-override'] ) ) {
		$vars['--wbtm-color-theme-override'] = $theme_color;
	}

	return $vars;
}
add_filter( 'busly_css_variables', 'busly_merge_wbtm_style_vars' );

/**
 * Admin notice on Appearance screens when the bus plugin (required) or
 * WooCommerce (required by the plugin's own booking engine) is missing.
 * Kept to a single, dismissible notice — see PHASE 43 error-handling rules.
 */
function busly_booking_admin_notice() {
	$screen        = get_current_screen();
	$screen_id     = $screen ? (string) $screen->id : '';
	$is_busly_page = false !== strpos( $screen_id, 'busly' );

	if ( 'dashboard' !== $screen_id && ! $is_busly_page ) {
		return;
	}

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( busly_is_booking_engine_ready() ) {
		return;
	}

	echo '<div class="notice notice-warning is-dismissible"><p>' .
		wp_kses_post(
			busly_is_bus_booking_active()
				? __( '<strong>Busly:</strong> WooCommerce is required by the Bus Ticket Booking plugin for search results, seat selection and checkout to work. Please install and activate WooCommerce.', 'busly' )
				: __( '<strong>Busly:</strong> Install and activate the "Bus Ticket Booking with Seat Reservation" plugin to enable bus search, seat maps and booking. Visit Busly → Setup Wizard.', 'busly' )
		) .
		'</p></div>';
}
add_action( 'admin_notices', 'busly_booking_admin_notice' );
