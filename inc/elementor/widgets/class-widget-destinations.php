<?php
/**
 * Busly Destination Widget — the "Popular Destinations" image grid.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

/**
 * Class Busly_Widget_Destinations
 */
class Busly_Widget_Destinations extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-destinations';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Destinations', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_destinations',
			array(
				'label' => __( 'Destinations', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'Image', 'busly' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);
		$repeater->add_control( 'city', array( 'label' => __( 'City', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Chittagong', 'busly' ) ) );
		$repeater->add_control( 'country', array( 'label' => __( 'Country', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Bangladesh', 'busly' ) ) );
		$repeater->add_control( 'fare', array( 'label' => __( 'Fare Text', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'From ৳650', 'busly' ) ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'busly' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );

		$this->add_control(
			'destinations',
			array(
				'label'       => __( 'Destinations', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ city }}}',
				// Same 4 demo photos as the design reference, hotlinked from
				// Unsplash under the free Unsplash License (not bundled with
				// the theme) — replace any of them from the Elementor panel.
				'default'     => array(
					array( 'city' => __( 'Chittagong', 'busly' ), 'country' => __( 'Bangladesh', 'busly' ), 'fare' => __( 'From ৳650', 'busly' ), 'image' => array( 'url' => 'https://images.unsplash.com/photo-1499669404910-ba8b35824a3c?w=500&h=700&fit=crop&auto=format' ) ),
					array( 'city' => __( "Cox's Bazar", 'busly' ), 'country' => __( 'Bangladesh', 'busly' ), 'fare' => __( 'From ৳900', 'busly' ), 'image' => array( 'url' => 'https://images.unsplash.com/photo-1465447142348-e9952c393450?w=500&h=700&fit=crop&auto=format' ) ),
					array( 'city' => __( 'Sylhet', 'busly' ), 'country' => __( 'Bangladesh', 'busly' ), 'fare' => __( 'From ৳550', 'busly' ), 'image' => array( 'url' => 'https://images.unsplash.com/photo-1526404423292-15db8c2334e5?w=500&h=700&fit=crop&auto=format' ) ),
					array( 'city' => __( 'Rajshahi', 'busly' ), 'country' => __( 'Bangladesh', 'busly' ), 'fare' => __( 'From ৳600', 'busly' ), 'image' => array( 'url' => 'https://images.unsplash.com/photo-1639037179118-d1a475030792?w=500&h=700&fit=crop&auto=format' ) ),
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
		<div class="busly-destinations wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="dest-grid">
				<?php foreach ( $settings['destinations'] as $dest ) : ?>
					<?php $has_link = ! empty( $dest['link']['url'] ); ?>
					<<?php echo $has_link ? 'a' : 'div'; ?> class="dest-c" <?php echo $has_link ? 'href="' . esc_url( $dest['link']['url'] ) . '"' : ''; ?>>
						<?php if ( ! empty( $dest['image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $dest['image']['url'] ); ?>" alt="<?php echo esc_attr( $dest['city'] ); ?>" loading="lazy" />
						<?php endif; ?>
						<div class="dest-ov"></div>
						<div class="dest-body">
							<div class="dest-city"><?php echo esc_html( $dest['city'] ); ?></div>
							<div class="dest-ctry"><?php echo esc_html( $dest['country'] ); ?></div>
							<?php if ( ! empty( $dest['fare'] ) ) : ?>
								<span class="dest-fare busly-pill"><?php echo esc_html( $dest['fare'] ); ?></span>
							<?php endif; ?>
						</div>
					</<?php echo $has_link ? 'a' : 'div'; ?>>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
