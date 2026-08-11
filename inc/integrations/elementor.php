<?php
/**
 * Elementor integration bootstrap: category registration, widget loading,
 * editor assets, and a friendly notice when Elementor is missing.
 *
 * Elementor is a REQUIRED companion plugin for Busly (the homepage and all
 * template-library layouts are 100% Elementor-editable) — but the theme
 * still activates and serves plain WordPress pages without it.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is Elementor active and loaded?
 *
 * @return bool
 */
function busly_is_elementor_active() {
	return did_action( 'elementor/loaded' );
}

if ( ! busly_is_elementor_active() ) {
	/**
	 * Notice on Busly admin screens (Setup Wizard covers the rest).
	 */
	add_action(
		'admin_notices',
		function () {
			$screen = get_current_screen();
			if ( ! $screen || 'dashboard' !== $screen->id || ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
			echo '<div class="notice notice-info"><p>' .
				wp_kses_post( __( '<strong>Busly:</strong> Install and activate Elementor to visually edit the homepage and every Busly template — see Busly → Setup Wizard.', 'busly' ) ) .
				'</p></div>';
		}
	);
	return;
}

/**
 * Register the "Busly" widget category in Elementor's panel.
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
 */
function busly_register_elementor_category( $elements_manager ) {
	$elements_manager->add_category(
		'busly',
		array(
			'title' => __( 'Busly', 'busly' ),
			'icon'  => 'eicon-bus-icon',
		)
	);
}
add_action( 'elementor/elements/categories_registered', 'busly_register_elementor_category' );

/**
 * Register all Busly custom widgets.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function busly_register_elementor_widgets( $widgets_manager ) {
	require_once BUSLY_DIR . '/inc/elementor/class-widget-base.php';

	$widgets = array(
		'hero'            => 'Busly_Widget_Hero',
		'bus-search'      => 'Busly_Widget_Bus_Search',
		'bus-listing'     => 'Busly_Widget_Bus_Listing',
		'featured-routes' => 'Busly_Widget_Featured_Routes',
		'destinations'    => 'Busly_Widget_Destinations',
		'features'        => 'Busly_Widget_Features',
		'counter'         => 'Busly_Widget_Counter',
		'steps'           => 'Busly_Widget_Steps',
		'offers'          => 'Busly_Widget_Offers',
		'testimonial'     => 'Busly_Widget_Testimonial',
		'pricing'         => 'Busly_Widget_Pricing',
		'faq'             => 'Busly_Widget_Faq',
		'cta'             => 'Busly_Widget_Cta',
		'contact'         => 'Busly_Widget_Contact',
	);

	foreach ( $widgets as $file => $class ) {
		$path = BUSLY_DIR . '/inc/elementor/widgets/class-widget-' . $file . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}
}
add_action( 'elementor/widgets/register', 'busly_register_elementor_widgets' );

/**
 * Editor-only CSS so widget previews look right inside the Elementor panel
 * even before the frontend stylesheet loads.
 */
function busly_elementor_editor_assets() {
	wp_enqueue_style( 'busly-elementor-editor', BUSLY_URI . '/assets/css/elementor-editor.css', array(), BUSLY_VERSION );
}
add_action( 'elementor/editor/after_enqueue_styles', 'busly_elementor_editor_assets' );

/**
 * Elementor's editor preview iframe renders the real page through header.php
 * / footer.php like any normal request, so Busly's own frontend stylesheets
 * (enqueued from inc/setup/enqueue.php) already load there automatically —
 * no extra wiring needed.
 */
