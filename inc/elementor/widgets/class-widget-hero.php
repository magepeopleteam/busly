<?php
/**
 * Busly Hero Widget — the cinematic photo hero from homepage.html.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

/**
 * Class Busly_Widget_Hero
 */
class Busly_Widget_Hero extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-hero';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Hero', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	/**
	 * @inheritDoc
	 */
	public function get_style_depends() {
		return array_merge( parent::get_style_depends(), array( 'busly-hero' ) );
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
			'badge_text',
			array(
				'label'       => __( 'Eyebrow Badge Text', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Live seat availability · Instant booking', 'busly' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'busly' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Your journey starts with the right bus.', 'busly' ),
				'description' => __( 'Wrap any word(s) in *asterisks* to color them (the accent color).', 'busly' ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => __( 'Subtitle', 'busly' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( '500+ routes · 50+ verified operators · Real-time seat selection.', 'busly' ),
			)
		);

		$this->add_control(
			'cta_text',
			array(
				'label'   => __( 'Button Text', 'busly' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Search Buses', 'busly' ),
			)
		);

		$this->add_control(
			'cta_link',
			array(
				'label'       => __( 'Button Link', 'busly' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '#busly-search' ),
				'placeholder' => __( 'https://your-link.com', 'busly' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_trust',
			array(
				'label' => __( 'Trust Row', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'busly' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ),
			)
		);
		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Secure payments', 'busly' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'trust_items',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'text' => __( 'Secure payments', 'busly' ), 'icon' => array( 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ) ),
					array( 'text' => __( '24/7 support', 'busly' ), 'icon' => array( 'value' => 'fas fa-clock', 'library' => 'fa-solid' ) ),
					array( 'text' => __( '100K+ happy travelers', 'busly' ), 'icon' => array( 'value' => 'fas fa-users', 'library' => 'fa-solid' ) ),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_background',
			array(
				'label' => __( 'Background', 'busly' ),
			)
		);

		$this->add_control(
			'background_image',
			array(
				'label'   => __( 'Background Photo', 'busly' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'background_image',
				'default' => 'full',
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'busly' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array( 'min' => 300, 'max' => 900 ),
					'vh' => array( 'min' => 30, 'max' => 100 ),
				),
				'default'    => array( 'unit' => 'vh', 'size' => 62 ),
				'selectors'  => array(
					'{{WRAPPER}} .busly-hero' => 'height: clamp(400px, {{SIZE}}{{UNIT}}, 680px);',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => __( 'Text Style', 'busly' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_typography_group( 'heading_typography', __( 'Heading', 'busly' ), '.hero-h1' );
		$this->add_typography_group( 'subtitle_typography', __( 'Subtitle', 'busly' ), '.hero-sub' );

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$bg_url = ! empty( $settings['background_image']['url'] ) ? $settings['background_image']['url'] : '';

		$heading_html = wp_kses( preg_replace( '/\*(.+?)\*/', '<span class="accent">$1</span>', $settings['heading'] ), array( 'span' => array( 'class' => true ) ) );

		$this->add_render_attribute( 'cta_link', 'class', 'hero-cta' );
		if ( ! empty( $settings['cta_link']['url'] ) ) {
			$this->add_link_attributes( 'cta_link', $settings['cta_link'] );
		}
		?>
		<div class="busly-hero-wrap">
			<div class="busly-hero">
				<div class="busly-hero-photo" style="<?php echo $bg_url ? 'background-image:url(' . esc_url( $bg_url ) . ')' : ''; ?>"></div>
				<div class="busly-hero-overlay"></div>
				<div class="busly-hero-top-fade"></div>

				<div class="busly-hero-body">
					<div class="wrap">
						<?php if ( ! empty( $settings['badge_text'] ) ) : ?>
							<div class="hero-badge">
								<span class="hero-badge-dot"></span>
								<?php echo esc_html( $settings['badge_text'] ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $settings['heading'] ) ) : ?>
							<h1 class="hero-h1"><?php echo wp_kses_post( $heading_html ); ?></h1>
						<?php endif; ?>

						<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
							<p class="hero-sub"><?php echo esc_html( $settings['subtitle'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $settings['cta_text'] ) ) : ?>
							<a <?php $this->print_render_attribute_string( 'cta_link' ); ?>>
								<?php busly_icon( 'search' ); ?>
								<?php echo esc_html( $settings['cta_text'] ); ?>
							</a>
						<?php endif; ?>

						<?php if ( ! empty( $settings['trust_items'] ) ) : ?>
							<div class="hero-trust">
								<?php foreach ( $settings['trust_items'] as $index => $item ) : ?>
									<?php if ( 0 !== $index ) : ?>
										<div class="trust-sep"></div>
									<?php endif; ?>
									<div class="trust-item">
										<?php $this->print_icon( $item['icon'] ); ?>
										<?php echo esc_html( $item['text'] ); ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
