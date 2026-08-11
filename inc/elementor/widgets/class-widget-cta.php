<?php
/**
 * Busly CTA Widget — the full-bleed gradient call-to-action band.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

/**
 * Class Busly_Widget_Cta
 */
class Busly_Widget_Cta extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-cta';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly CTA', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'busly' ),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Heading', 'busly' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Ready for your next journey?', 'busly' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'busly' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Join 100,000+ travelers who book smarter with Busly.', 'busly' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'busly' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Book Your Trip', 'busly' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => __( 'Button Link', 'busly' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '#' ),
				'placeholder' => __( 'https://your-link.com', 'busly' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'button', 'class', 'cta-btn' );
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['button_link'] );
		}
		?>
		<div class="cta-sec">
			<div class="wrap">
				<?php if ( ! empty( $settings['heading'] ) ) : ?>
					<h2 class="cta-h"><?php echo esc_html( $settings['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<p class="cta-p"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $settings['button_text'] ) ) : ?>
					<a <?php $this->print_render_attribute_string( 'button' ); ?>>
						<?php echo esc_html( $settings['button_text'] ); ?>
						<?php busly_icon( 'arrow-right' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
