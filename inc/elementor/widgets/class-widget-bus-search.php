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
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

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

		$this->add_control(
			'card_wrapper',
			array(
				'label'       => __( 'Floating Card Wrapper', 'busly' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''    => __( 'Auto (match page design)', 'busly' ),
					'yes' => __( 'Always show', 'busly' ),
					'no'  => __( 'Never show', 'busly' ),
				),
				'description' => __( 'Wraps the search bar in its own padded, shadowed white card. "Auto" follows whether this page is set to the Modern homepage design.', 'busly' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_style_card',
			array(
				'label' => __( 'Floating Card', 'busly' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'card_background',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( '{{WRAPPER}} .busly-search-card' => 'background: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => __( 'Border Radius', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( '{{WRAPPER}} .busly-search-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'Padding', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( '{{WRAPPER}} .busly-search-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .busly-search-card',
			)
		);

		$this->add_control(
			'heading_style_pill',
			array(
				'label'     => __( 'Search Bar', 'busly' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'pill_background',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_input_fields_holder' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pill_border',
				'selector' => 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_input_fields_holder',
			)
		);

		$this->add_responsive_control(
			'pill_radius',
			array(
				'label'      => __( 'Border Radius', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_input_fields_holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'pill_shadow',
				'selector' => 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_input_fields_holder',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_fields',
			array(
				'label' => __( 'Fields', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Field Padding', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wtbm_inputList' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'field_divider_color',
			array(
				'label'     => __( 'Divider Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wtbm_inputList' => 'border-right-color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'field_hover_background',
			array(
				'label'     => __( 'Field Hover Background', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wtbm_inputList:hover' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Field Icon Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .marker > i' => 'color: {{VALUE}} !important;',
					'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .calendar > i' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'heading_style_label',
			array(
				'label'     => __( 'Field Label', 'busly' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign label.wtbm_fdColumn' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign label.wtbm_fdColumn',
			)
		);

		$this->add_control(
			'heading_style_value',
			array(
				'label'     => __( 'Field Value', 'busly' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .formControl' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .formControl',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_swap',
			array(
				'label' => __( 'Swap Button', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'swap_background',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_location_toggle' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'swap_border_color',
			array(
				'label'     => __( 'Border Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_location_toggle' => 'border-color: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'swap_icon_color',
			array(
				'label'     => __( 'Icon Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_location_toggle i' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => __( 'Search Button', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab_normal',
			array( 'label' => __( 'Normal', 'busly' ) )
		);

		$this->add_control(
			'btn_background',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'btn_text_color',
			array(
				'label'     => __( 'Text Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			array( 'label' => __( 'Hover', 'busly' ) )
		);

		$this->add_control(
			'btn_background_hover',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button:hover' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'btn_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button:hover' => 'color: {{VALUE}} !important;' ),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typography',
				'selector' => 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button',
			)
		);

		$this->add_responsive_control(
			'btn_radius',
			array(
				'label'      => __( 'Border Radius', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ),
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => __( 'Padding', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign .wbtm_search_action_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_dropdown',
			array(
				'label' => __( 'Location Dropdown', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'dropdown_background',
			array(
				'label'     => __( 'Background Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign ul.wbtm_input_select_list' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_responsive_control(
			'dropdown_radius',
			array(
				'label'      => __( 'Border Radius', 'busly' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign ul.wbtm_input_select_list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;' ),
			)
		);

		$this->add_control(
			'dropdown_item_hover_background',
			array(
				'label'     => __( 'Item Hover Background', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array( 'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign ul.wbtm_input_select_list li:hover' => 'background: {{VALUE}} !important;' ),
			)
		);

		$this->add_control(
			'dropdown_selected_color',
			array(
				'label'     => __( 'Selected Item Accent Color', 'busly' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body {{WRAPPER}} #wbtm_area .wbtm-bar-redesign ul.wbtm_input_select_list li.wbtm_city_selected::before' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
				),
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
		<?php
		$card_wrapper_setting = $settings['card_wrapper'] ?? '';
		$show_card            = 'yes' === $card_wrapper_setting ? true : ( 'no' === $card_wrapper_setting ? false : busly_is_modern_skin_page() );
		?>
		<div class="busly-search-widget" id="busly-search">
			<div class="wrap">
				<?php if ( $show_card ) : ?><div class="busly-search-card"><?php endif; ?>
				<?php do_action( 'busly_before_booking_form' ); ?>
				<?php echo busly_render_wbtm_shortcode( 'wbtm-bus-search-form', $atts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode output is the plugin's own escaped markup. ?>
				<?php do_action( 'busly_after_booking_form' ); ?>
				<?php if ( $show_card ) : ?></div><?php endif; ?>
			</div>
		</div>
		<?php
	}
}
