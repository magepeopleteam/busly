<?php
/**
 * Busly Breadcrumbs Widget — drops the theme's own breadcrumb trail
 * (busly_breadcrumbs(), same output/markup as non-Elementor pages) anywhere
 * inside an Elementor layout.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Widget_Breadcrumbs
 */
class Busly_Widget_Breadcrumbs extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-breadcrumbs';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Breadcrumbs', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-navigator';
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
			'note',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => __( 'Shows this page\'s breadcrumb trail (Home / … / Current Page) — the same one non-Elementor pages print automatically. Nothing to configure here; it always reflects the current page.', 'busly' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		?>
		<div class="busly-page-header">
			<?php busly_breadcrumbs(); ?>
		</div>
		<?php
	}
}
