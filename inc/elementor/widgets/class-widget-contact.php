<?php
/**
 * Busly Contact Widget — contact info + optional map + form-plugin slot.
 *
 * This widget deliberately does NOT process form submissions itself — per
 * the brief's plugin-compatibility rules it defers to Contact Form 7 /
 * WPForms (styled to match via inc/integrations/forms.php). Drop a CF7/
 * WPForms widget next to this one in Elementor for a working form.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Contact
 */
class Busly_Widget_Contact extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-contact';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Contact Info', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-contact-icon';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Contact Details', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'Icon', 'busly' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array( 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ),
			)
		);
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Office', 'busly' ) ) );
		$repeater->add_control( 'detail', array( 'label' => __( 'Detail', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( '123 Gulshan Avenue, Dhaka 1212, Bangladesh', 'busly' ) ) );

		$this->add_control(
			'items',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'Office', 'busly' ), 'detail' => __( '123 Gulshan Avenue, Dhaka 1212, Bangladesh', 'busly' ), 'icon' => array( 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ) ),
					array( 'title' => __( 'Phone', 'busly' ), 'detail' => '+880 1234-567890', 'icon' => array( 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ) ),
					array( 'title' => __( 'Email', 'busly' ), 'detail' => 'support@busly.example', 'icon' => array( 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ) ),
					array( 'title' => __( 'Support Hours', 'busly' ), 'detail' => __( '24/7, every day', 'busly' ), 'icon' => array( 'value' => 'fas fa-headset', 'library' => 'fa-solid' ) ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_map',
			array(
				'label' => __( 'Map', 'busly' ),
			)
		);

		$this->add_control(
			'show_map',
			array(
				'label'        => __( 'Show Map Embed', 'busly' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'map_embed_url',
			array(
				'label'       => __( 'Map Embed URL', 'busly' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Paste a Google Maps "Embed a map" iframe src URL.', 'busly' ),
				'condition'   => array( 'show_map' => 'yes' ),
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
		<div class="busly-contact-widget wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div>
				<?php foreach ( $settings['items'] as $item ) : ?>
					<div class="busly-contact-info-item">
						<div class="busly-contact-info-ic"><?php $this->print_icon( $item['icon'] ); ?></div>
						<div>
							<div class="busly-contact-info-t"><?php echo esc_html( $item['title'] ); ?></div>
							<div class="busly-contact-info-d"><?php echo esc_html( $item['detail'] ); ?></div>
						</div>
					</div>
				<?php endforeach; ?>

				<?php if ( 'yes' === $settings['show_map'] && ! empty( $settings['map_embed_url'] ) ) : ?>
					<div class="busly-contact-map">
						<iframe src="<?php echo esc_url( $settings['map_embed_url'] ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'Map', 'busly' ); ?>"></iframe>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
