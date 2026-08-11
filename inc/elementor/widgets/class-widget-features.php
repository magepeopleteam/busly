<?php
/**
 * Busly Features Widget — "Why Travelers Choose Us" icon-box grid.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Features
 */
class Busly_Widget_Features extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-features';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Features', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Features', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'busly' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-ticket-alt', 'library' => 'fa-solid' ),
			)
		);
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Easy Booking', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Book your seat in under 2 minutes with our streamlined checkout.', 'busly' ) ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link (optional)', 'busly' ), 'type' => Controls_Manager::URL ) );

		$this->add_control(
			'features',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'Easy Booking', 'busly' ), 'description' => __( 'Book your seat in under 2 minutes with our streamlined checkout.', 'busly' ), 'icon' => array( 'value' => 'fas fa-ticket-alt', 'library' => 'fa-solid' ) ),
					array( 'title' => __( 'Secure Payments', 'busly' ), 'description' => __( '256-bit SSL encryption on every transaction. Always safe.', 'busly' ), 'icon' => array( 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ) ),
					array( 'title' => __( 'Comfortable Travel', 'busly' ), 'description' => __( 'Curated operators with verified comfort standards and clean buses.', 'busly' ), 'icon' => array( 'value' => 'fas fa-smile', 'library' => 'fa-solid' ) ),
					array( 'title' => __( '24/7 Support', 'busly' ), 'description' => __( 'Round-the-clock assistance wherever you are on your journey.', 'busly' ), 'icon' => array( 'value' => 'fas fa-headset', 'library' => 'fa-solid' ) ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => __( 'Columns', 'busly' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => array( '2' => '2', '3' => '3', '4' => '4' ),
				'selectors' => array( '{{WRAPPER}} .grid-4' => 'grid-template-columns: repeat({{VALUE}}, 1fr);' ),
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
		<div class="busly-features">
			<?php $this->render_section_header( $settings ); ?>

			<div class="grid-4">
				<?php foreach ( $settings['features'] as $feature ) : ?>
					<?php $has_link = ! empty( $feature['link']['url'] ); ?>
					<<?php echo $has_link ? 'a' : 'div'; ?> class="feat-c" <?php echo $has_link ? 'href="' . esc_url( $feature['link']['url'] ) . '"' : ''; ?>>
						<div class="feat-ic"><?php $this->print_icon( $feature['icon'] ); ?></div>
						<div class="feat-t"><?php echo esc_html( $feature['title'] ); ?></div>
						<p class="feat-d"><?php echo esc_html( $feature['description'] ); ?></p>
					</<?php echo $has_link ? 'a' : 'div'; ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
