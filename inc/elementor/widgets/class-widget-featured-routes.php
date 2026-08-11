<?php
/**
 * Busly Featured Routes Widget — the "Popular Routes" grid in homepage.html.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Featured_Routes
 */
class Busly_Widget_Featured_Routes extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-featured-routes';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Featured Routes', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-route';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_routes',
			array(
				'label' => __( 'Routes', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'code_from', array( 'label' => __( 'From Code', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => 'DAC' ) );
		$repeater->add_control( 'code_to', array( 'label' => __( 'To Code', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => 'CGP' ) );
		$repeater->add_control( 'route_name', array( 'label' => __( 'Route Name', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Dhaka → Chittagong', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'duration', array( 'label' => __( 'Duration', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => '5h 30m' ) );
		$repeater->add_control( 'price', array( 'label' => __( 'Price Text', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'From ৳650', 'busly' ) ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'busly' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->add_control(
			'routes',
			array(
				'label'       => __( 'Routes', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ route_name }}}',
				'default'     => array(
					array( 'code_from' => 'DAC', 'code_to' => 'CGP', 'route_name' => __( 'Dhaka → Chittagong', 'busly' ), 'duration' => '5h 30m', 'price' => __( 'From ৳650', 'busly' ) ),
					array( 'code_from' => 'DAC', 'code_to' => 'CXB', 'route_name' => __( "Dhaka → Cox's Bazar", 'busly' ), 'duration' => '8h 00m', 'price' => __( 'From ৳900', 'busly' ) ),
					array( 'code_from' => 'DAC', 'code_to' => 'ZYL', 'route_name' => __( 'Dhaka → Sylhet', 'busly' ), 'duration' => '4h 45m', 'price' => __( 'From ৳550', 'busly' ) ),
					array( 'code_from' => 'DAC', 'code_to' => 'RJH', 'route_name' => __( 'Dhaka → Rajshahi', 'busly' ), 'duration' => '5h 00m', 'price' => __( 'From ৳600', 'busly' ) ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'busly' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array( '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array(
					'{{WRAPPER}} .grid-4' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
		?>
		<div class="busly-featured-routes">
			<?php $this->render_section_header( $settings ); ?>

			<div class="grid-4">
				<?php foreach ( $settings['routes'] as $route ) : ?>
					<?php
					$has_link = ! empty( $route['link']['url'] );
					$tag      = $has_link ? 'a' : 'div';
					?>
					<<?php echo esc_attr( $tag ); ?> class="route-c busly-card" <?php echo $has_link ? 'href="' . esc_url( $route['link']['url'] ) . '"' : ''; ?>>
						<?php do_action( 'busly_before_bus_card' ); ?>
						<div class="route-top">
							<div class="code-pair">
								<span class="code"><?php echo esc_html( $route['code_from'] ); ?></span>
								<?php busly_icon( 'arrow-right' ); ?>
								<span class="code"><?php echo esc_html( $route['code_to'] ); ?></span>
							</div>
							<div class="go-ic"><?php busly_icon( 'chevron-right' ); ?></div>
						</div>
						<div class="route-name"><?php echo esc_html( $route['route_name'] ); ?></div>
						<div class="route-meta">
							<span class="route-dur"><?php echo esc_html( $route['duration'] ); ?></span>
							<span class="route-price"><?php echo esc_html( $route['price'] ); ?></span>
						</div>
						<?php do_action( 'busly_after_bus_card' ); ?>
					</<?php echo esc_attr( $tag ); ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
