<?php
/**
 * Busly Bus Listing Widget — renders the Bus Ticket Booking plugin's own
 * [wbtm-bus-list] shortcode (real, live buses) restyled with booking.css.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

/**
 * Class Busly_Widget_Bus_Listing
 */
class Busly_Widget_Bus_Listing extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-bus-listing';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Bus Listing', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-post-list';
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

		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Bus List', 'busly' ),
			)
		);

		if ( ! busly_is_bus_booking_active() ) {
			$this->add_control(
				'engine_notice',
				array(
					'type'             => Controls_Manager::RAW_HTML,
					'raw'              => __( 'Install & activate the Bus Ticket Booking with Seat Reservation plugin to make this widget functional.', 'busly' ),
					'content_classes'  => 'elementor-panel-alert elementor-panel-alert-warning',
				)
			);
		}

		$this->add_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'busly' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
			)
		);

		$this->add_control(
			'show',
			array(
				'label'   => __( 'Number of Buses', 'busly' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 9,
				'min'     => 1,
				'max'     => 48,
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => __( 'Bus Category (optional)', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => __( 'Bus category slug. Leave blank for all.', 'busly' ),
			)
		);

		$this->add_control(
			'sort',
			array(
				'label'   => __( 'Sort Order', 'busly' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => array(
					'ASC'  => __( 'Ascending', 'busly' ),
					'DESC' => __( 'Descending', 'busly' ),
				),
			)
		);

		$this->add_control(
			'pagination',
			array(
				'label'        => __( 'Pagination', 'busly' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'busly' ),
				'label_off'    => __( 'No', 'busly' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="busly-bus-listing-widget wrap">
			<?php $this->render_section_header( $settings ); ?>

			<?php
			echo busly_render_wbtm_shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plugin's own escaped markup.
				'wbtm-bus-list',
				array(
					'style'      => 'grid',
					'show'       => (int) $settings['show'],
					'column'     => (int) $settings['columns'],
					'cat'        => $settings['category'] ? sanitize_title( $settings['category'] ) : '',
					'sort'       => $settings['sort'],
					'pagination' => $settings['pagination'] ? 'yes' : 'no',
				)
			);
			?>
		</div>
		<?php
	}
}
