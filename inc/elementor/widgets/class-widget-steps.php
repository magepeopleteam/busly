<?php
/**
 * Busly Steps Widget — "How It Works" numbered process row.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Steps
 */
class Busly_Widget_Steps extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-steps';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly How It Works', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-number-field';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Steps', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Search', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Enter your route and date to see all available buses instantly.', 'busly' ) ) );

		$this->add_control(
			'steps',
			array(
				'label'       => __( 'Steps', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'Search', 'busly' ), 'description' => __( 'Enter your route and date to see all available buses instantly.', 'busly' ) ),
					array( 'title' => __( 'Choose Your Bus', 'busly' ), 'description' => __( 'Compare operators, prices, amenities and departure times.', 'busly' ) ),
					array( 'title' => __( 'Select Your Seat', 'busly' ), 'description' => __( 'Pick exactly where you sit on an interactive real-time seat map.', 'busly' ) ),
					array( 'title' => __( 'Enjoy the Journey', 'busly' ), 'description' => __( 'Receive your digital ticket instantly and travel comfortably.', 'busly' ) ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$step_count = count( $settings['steps'] );
		$size_class = 5 === $step_count ? 'busly-steps-5' : ( 3 === $step_count ? 'busly-steps-3' : '' );
		?>
		<div class="busly-steps-widget wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="steps <?php echo esc_attr( $size_class ); ?>">
				<?php foreach ( $settings['steps'] as $index => $step ) : ?>
					<div class="step">
						<div class="step-n"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></div>
						<div class="step-t"><?php echo esc_html( $step['title'] ); ?></div>
						<p class="step-d"><?php echo esc_html( $step['description'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
