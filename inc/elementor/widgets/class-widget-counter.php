<?php
/**
 * Busly Counter Widget — travel-statistics row (e.g. "500+ Routes").
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Counter
 */
class Busly_Widget_Counter extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-counter';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Counter', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Statistics', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'number', array( 'label' => __( 'Number', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => '500+' ) );
		$repeater->add_control( 'label', array( 'label' => __( 'Label', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Routes Covered', 'busly' ), 'label_block' => true ) );

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ number }}} — {{{ label }}}',
				'default'     => array(
					array( 'number' => '500+', 'label' => __( 'Routes Covered', 'busly' ) ),
					array( 'number' => '50+', 'label' => __( 'Verified Operators', 'busly' ) ),
					array( 'number' => '100K+', 'label' => __( 'Happy Travelers', 'busly' ) ),
					array( 'number' => '4.8/5', 'label' => __( 'Average Rating', 'busly' ) ),
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
		<div class="busly-counter wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="counter-grid">
				<?php foreach ( $settings['stats'] as $stat ) : ?>
					<div class="counter-c">
						<div class="counter-n"><?php echo esc_html( $stat['number'] ); ?></div>
						<div class="counter-l"><?php echo esc_html( $stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
