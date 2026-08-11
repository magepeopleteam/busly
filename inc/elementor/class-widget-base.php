<?php
/**
 * Shared base class for every Busly Elementor widget.
 *
 * Centralises: default category/keywords, the reusable "eyebrow + heading +
 * description" section-header control group (matches .sec-eye/.sec-h/.sec-p
 * in the design reference) and its render helper, plus small utilities used
 * across widgets (icon rendering, safe repeater access).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

/**
 * Class Busly_Widget_Base
 */
abstract class Busly_Widget_Base extends Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return array( 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return array( 'busly', 'bus', 'booking', 'travel' );
	}

	/**
	 * Style dependency shared by every Busly widget (design tokens, cards,
	 * buttons). Individual widgets may add a second, section-specific handle
	 * by overriding get_style_depends() and merging with parent::.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'busly-base', 'busly-layout', 'busly-components', 'busly-sections' );
	}

	/**
	 * Adds the reusable "Section Header" control group (eyebrow / heading /
	 * description) used by most content widgets. Call from register_controls().
	 */
	protected function register_section_header_controls() {
		$this->start_controls_section(
			'section_header',
			array(
				'label' => __( 'Section Header', 'busly' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'       => __( 'Eyebrow', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Busly', 'busly' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => __( 'Heading', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Section Heading', 'busly' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'Description', 'busly' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			)
		);

		$this->add_control(
			'header_align',
			array(
				'label'   => __( 'Alignment', 'busly' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array(
						'title' => __( 'Left', 'busly' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'busly' ),
						'icon'  => 'eicon-text-align-center',
					),
				),
				'default' => 'left',
				'toggle'  => false,
			)
		);

		$this->add_control(
			'show_link',
			array(
				'label'        => __( 'Show "View All" Link', 'busly' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Show', 'busly' ),
				'label_off'    => __( 'Hide', 'busly' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'busly' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'busly' ),
				'default'     => array( 'url' => '#' ),
				'condition'   => array( 'show_link' => 'yes' ),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'     => __( 'Link Text', 'busly' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'View all', 'busly' ),
				'condition' => array( 'show_link' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the section header block matching sec-top/sec-hd markup.
	 *
	 * @param array $settings Widget settings (from $this->get_settings_for_display()).
	 */
	protected function render_section_header( $settings ) {
		if ( empty( $settings['heading'] ) && empty( $settings['eyebrow'] ) && empty( $settings['description'] ) ) {
			return;
		}

		$centered = 'center' === ( $settings['header_align'] ?? 'left' );
		$has_link = ( ( $settings['show_link'] ?? '' ) === 'yes' );
		$wrap_cls = $has_link ? 'sec-top' : 'sec-hd';
		?>
		<div class="<?php echo esc_attr( $wrap_cls ); ?><?php echo $centered ? ' sec-centered' : ''; ?>">
			<div>
				<?php if ( ! empty( $settings['eyebrow'] ) ) : ?>
					<p class="sec-eye"><?php echo esc_html( $settings['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $settings['heading'] ) ) : ?>
					<h2 class="sec-h"><?php echo esc_html( $settings['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $settings['description'] ) ) : ?>
					<p class="sec-p"><?php echo esc_html( $settings['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $has_link && ! empty( $settings['link']['url'] ) ) : ?>
				<a
					class="see-all"
					href="<?php echo esc_url( $settings['link']['url'] ); ?>"
					<?php echo ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : ''; ?>
					<?php echo ! empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : ''; ?>
				>
					<?php echo esc_html( $settings['link_text'] ?? __( 'View all', 'busly' ) ); ?>
					<?php busly_icon( 'chevron-right' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Print an Elementor icon control value as inline SVG/markup.
	 *
	 * @param array $icon Icon control value (['value' => ..., 'library' => ...]).
	 */
	protected function print_icon( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return;
		}
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
	}

	/**
	 * Typography + color group controls, scoped to a CSS selector, reusing
	 * Elementor's own responsive/typography system instead of hand-rolled
	 * font-size controls (keeps every widget Desktop/Tablet/Mobile aware).
	 *
	 * @param string $name     Control base name.
	 * @param string $label    Field label.
	 * @param string $selector CSS selector to scope the typography group to.
	 */
	protected function add_typography_group( $name, $label, $selector ) {
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $name,
				'label'    => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}
}
