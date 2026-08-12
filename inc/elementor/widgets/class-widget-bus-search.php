<?php
/**
 * Busly Bus Search Widget — renders the Bus Ticket Booking plugin's own
 * [wbtm-bus-search-form] shortcode (never re-implemented), restyled by
 * assets/css/booking.css to match the homepage.html pill search bar.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

/**
 * Class Busly_Widget_Bus_Search
 */
class Busly_Widget_Bus_Search extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-bus-search';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Bus Search', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return array_merge( parent::get_keywords(), array( 'wbtm', 'search form', 'route' ) );
	}

	/**
	 * @inheritDoc
	 */
	public function get_style_depends() {
		return array_merge( parent::get_style_depends(), array( 'busly-booking' ) );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Search Form', 'busly' ),
			)
		);

		if ( ! busly_is_booking_engine_ready() ) {
			$this->add_control(
				'engine_notice',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => busly_is_bus_booking_active()
						? __( 'WooCommerce must be active for search results/checkout to work. This widget will show a notice on the front end until then.', 'busly' )
						: __( 'Install & activate the Bus Ticket Booking with Seat Reservation plugin to make this widget functional.', 'busly' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		}

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Card Style', 'busly' ),
				'type'    => Controls_Manager::SELECT,
				'default' => busly_get_option( 'booking_default_style', 'grid' ),
				'options' => array(
					''     => __( 'Default', 'busly' ),
					'flix' => __( 'Compact (flix)', 'busly' ),
				),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => __( 'Restrict to Bus Category (optional)', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Bus category slug, from the Bus Category taxonomy. Leave blank for all.', 'busly' ),
			)
		);

		$this->add_control(
			'search_page',
			array(
				'label'       => __( 'Redirect Results To (optional)', 'busly' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Leave empty to show results inline via AJAX', 'busly' ),
			)
		);

		$this->add_control(
			'left_filter',
			array(
				'label'        => __( 'Show Left Filter Sidebar', 'busly' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'busly' ),
				'label_off'    => __( 'Hide', 'busly' ),
				'return_value' => 'on',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$atts = array(
			'style'       => $settings['style'],
			'cat'         => $settings['category'] ? sanitize_title( $settings['category'] ) : '',
			'search-page' => ! empty( $settings['search_page']['url'] ) ? url_to_postid( $settings['search_page']['url'] ) : '',
			'left_filter' => 'on' === $settings['left_filter'] ? 'on' : 'off',
		);
		?>
		<div class="busly-search-widget" id="busly-search">
			<div class="wrap">
				<?php do_action( 'busly_before_booking_form' ); ?>
				<?php echo busly_render_wbtm_shortcode( 'wbtm-bus-search-form', $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output is the plugin's own escaped markup. ?>
				<?php do_action( 'busly_after_booking_form' ); ?>
			</div>
		</div>
		<?php
	}
}
