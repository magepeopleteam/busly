<?php
/**
 * Busly FAQ Widget — accessible accordion (works with JS disabled: all
 * answers are present in the DOM, only the max-height clamp needs JS —
 * see assets/js/navigation.js:initFaqAccordion()).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Faq
 */
class Busly_Widget_Faq extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-faq';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly FAQ', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-help-o';
	}

	/**
	 * @inheritDoc
	 */
	public function get_script_depends() {
		return array( 'busly-navigation' );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Questions', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'question', array( 'label' => __( 'Question', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'How do I cancel my booking?', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'answer', array( 'label' => __( 'Answer', 'busly' ), 'type' => Controls_Manager::WYSIWYG, 'default' => __( 'You can cancel from My Bookings up to 24 hours before departure for a full refund.', 'busly' ) ) );

		$this->add_control(
			'faqs',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ question }}}',
				'default'     => array(
					array( 'question' => __( 'How do I book a bus ticket?', 'busly' ), 'answer' => __( 'Search your route and date, choose a bus, pick your seat on the interactive seat map, and complete checkout — your digital ticket arrives instantly.', 'busly' ) ),
					array( 'question' => __( 'Can I cancel or change my booking?', 'busly' ), 'answer' => __( 'Yes — visit My Bookings in your account to cancel or request a change, subject to the operator\'s cancellation policy.', 'busly' ) ),
					array( 'question' => __( 'What payment methods are accepted?', 'busly' ), 'answer' => __( 'We accept major cards and popular digital wallets, all processed securely at checkout.', 'busly' ) ),
					array( 'question' => __( 'Do I need to print my ticket?', 'busly' ), 'answer' => __( 'No — your digital ticket works directly from your phone. A printed copy is optional.', 'busly' ) ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_side_card',
			array(
				'label' => __( 'Side Card (optional)', 'busly' ),
			)
		);

		$this->add_control(
			'side_heading',
			array(
				'label'       => __( 'Heading', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Leave blank to keep the question list centered with no side card (the default, single-column layout).', 'busly' ),
			)
		);

		$this->add_control(
			'side_description',
			array(
				'label'   => __( 'Description', 'busly' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			)
		);

		$this->add_control(
			'side_button_text',
			array(
				'label'   => __( 'Button Text', 'busly' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Contact Support', 'busly' ),
			)
		);

		$this->add_control(
			'side_button_link',
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

		$has_side = ! empty( $settings['side_heading'] );

		$this->add_render_attribute( 'side_button', 'class', 'busly-btn busly-btn--primary' );
		if ( ! empty( $settings['side_button_link']['url'] ) ) {
			$this->add_link_attributes( 'side_button', $settings['side_button_link'] );
		}
		?>
		<div class="busly-faq-widget wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="busly-faq-row<?php echo $has_side ? ' has-side' : ''; ?>">
				<div class="busly-faq">
					<?php foreach ( $settings['faqs'] as $index => $faq ) : ?>
						<?php $panel_id = $this->get_id() . '-faq-' . $index; ?>
						<div class="busly-faq-item">
							<button type="button" class="busly-faq-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
								<?php echo esc_html( $faq['question'] ); ?>
								<?php busly_icon( 'chevron-down' ); ?>
							</button>
							<div class="busly-faq-a" id="<?php echo esc_attr( $panel_id ); ?>">
								<div class="busly-faq-a-inner"><?php echo wp_kses_post( $faq['answer'] ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $has_side ) : ?>
					<div class="busly-faq-side">
						<div class="busly-faq-side-ic"><?php busly_icon( 'headset' ); ?></div>
						<div class="busly-faq-side-h"><?php echo esc_html( $settings['side_heading'] ); ?></div>
						<?php if ( ! empty( $settings['side_description'] ) ) : ?>
							<p class="busly-faq-side-d"><?php echo esc_html( $settings['side_description'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $settings['side_button_text'] ) ) : ?>
							<a <?php $this->print_render_attribute_string( 'side_button' ); ?>>
								<?php echo esc_html( $settings['side_button_text'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
