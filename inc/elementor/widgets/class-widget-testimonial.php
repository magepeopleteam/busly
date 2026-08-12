<?php
/**
 * Busly Testimonial Widget — "What Travelers Say" review grid.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Testimonial
 */
class Busly_Widget_Testimonial extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-testimonial';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Testimonials', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Reviews', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'rating', array( 'label' => __( 'Rating (1-5)', 'busly' ), 'type' => Controls_Manager::NUMBER, 'default' => 5, 'min' => 1, 'max' => 5 ) );
		$repeater->add_control( 'review', array( 'label' => __( 'Review', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Booking was incredibly smooth. The seat map made it easy to pick exactly where I wanted to sit.', 'busly' ) ) );
		$repeater->add_control( 'avatar', array( 'label' => __( 'Avatar (optional)', 'busly' ), 'type' => Controls_Manager::MEDIA ) );
		$repeater->add_control( 'name', array( 'label' => __( 'Name', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Rafi Ahmed', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'route', array( 'label' => __( 'Route / Designation', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Dhaka → Chittagong', 'busly' ) ) );

		$this->add_control(
			'reviews',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array( 'rating' => 5, 'review' => __( 'Booking was incredibly smooth. The seat map made it easy to pick exactly where I wanted to sit. Will use Busly every time.', 'busly' ), 'name' => __( 'Rafi Ahmed', 'busly' ), 'route' => __( 'Dhaka → Chittagong', 'busly' ) ),
					array( 'rating' => 5, 'review' => __( 'The digital ticket is a game-changer. No printing needed. The bus was on time and the journey was very comfortable.', 'busly' ), 'name' => __( 'Nadia Islam', 'busly' ), 'route' => __( 'Dhaka → Sylhet', 'busly' ) ),
					array( 'rating' => 4, 'review' => __( "Best bus booking experience I've had. Transparent pricing, great filters, and the 24/7 support team is very responsive.", 'busly' ), 'name' => __( 'Karim Hassan', 'busly' ), 'route' => __( "Dhaka → Cox's Bazar", 'busly' ) ),
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
		<div class="busly-testimonials wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="rev-grid">
				<?php foreach ( $settings['reviews'] as $review ) : ?>
					<div class="rev-c">
						<?php busly_star_rating( (int) $review['rating'] ); ?>
						<p class="rev-txt">&ldquo;<?php echo esc_html( $review['review'] ); ?>&rdquo;</p>
						<div class="rev-auth">
							<div class="av">
								<?php if ( ! empty( $review['avatar']['url'] ) ) : ?>
									<img src="<?php echo esc_url( $review['avatar']['url'] ); ?>" alt="<?php echo esc_attr( $review['name'] ); ?>" loading="lazy" />
								<?php else : ?>
									<?php echo esc_html( busly_initials( $review['name'] ) ); ?>
								<?php endif; ?>
							</div>
							<div>
								<div class="av-name"><?php echo esc_html( $review['name'] ); ?></div>
								<div class="av-route"><?php echo esc_html( $review['route'] ); ?></div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
