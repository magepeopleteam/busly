<?php
/**
 * Busly Pricing Widget — plan/fare comparison cards.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Pricing
 */
class Busly_Widget_Pricing extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-pricing';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Pricing', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Plans', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'name', array( 'label' => __( 'Plan Name', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Standard', 'busly' ) ) );
		$repeater->add_control( 'price', array( 'label' => __( 'Price', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => '৳650' ) );
		$repeater->add_control( 'price_suffix', array( 'label' => __( 'Price Suffix', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( '/ seat', 'busly' ) ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Non-AC coach seating', 'busly' ) ) );
		$repeater->add_control( 'features', array( 'label' => __( 'Features (one per line)', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( "Reclining seat\nFree cancellation (24h)\nOnline seat selection", 'busly' ) ) );
		$repeater->add_control( 'badge', array( 'label' => __( 'Badge (optional)', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$repeater->add_control( 'featured', array( 'label' => __( 'Highlight this plan', 'busly' ), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes' ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Button Link', 'busly' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$repeater->add_control( 'button_text', array( 'label' => __( 'Button Text', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Select', 'busly' ) ) );

		$this->add_control(
			'plans',
			array(
				'label'       => __( 'Plans', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array( 'name' => __( 'Economy', 'busly' ), 'price' => '৳550', 'description' => __( 'Non-AC coach seating', 'busly' ), 'features' => __( "Standard seat\nFree cancellation (24h)\nOnline seat selection", 'busly' ), 'button_text' => __( 'Select', 'busly' ) ),
					array( 'name' => __( 'Standard AC', 'busly' ), 'price' => '৳900', 'description' => __( 'Air-conditioned comfort', 'busly' ), 'features' => __( "Reclining AC seat\nFree cancellation (24h)\nOnline seat selection\nUSB charging port", 'busly' ), 'badge' => __( 'Most Popular', 'busly' ), 'featured' => 'yes', 'button_text' => __( 'Select', 'busly' ) ),
					array( 'name' => __( 'Business', 'busly' ), 'price' => '৳1500', 'description' => __( 'Premium sleeper coach', 'busly' ), 'features' => __( "Sleeper/business seat\nFree cancellation (48h)\nPriority boarding\nComplimentary snacks", 'busly' ), 'button_text' => __( 'Select', 'busly' ) ),
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
		<div class="busly-pricing wrap">
			<?php $this->render_section_header( $settings ); ?>

			<div class="busly-pricing-grid">
				<?php foreach ( $settings['plans'] as $plan ) : ?>
					<div class="busly-pricing-card<?php echo 'yes' === $plan['featured'] ? ' is-featured' : ''; ?>">
						<?php if ( ! empty( $plan['badge'] ) ) : ?>
							<span class="busly-pricing-badge"><?php echo esc_html( $plan['badge'] ); ?></span>
						<?php endif; ?>
						<div class="busly-pricing-name"><?php echo esc_html( $plan['name'] ); ?></div>
						<div class="busly-pricing-price">
							<?php echo esc_html( $plan['price'] ); ?>
							<?php if ( ! empty( $plan['price_suffix'] ) ) : ?>
								<small><?php echo esc_html( $plan['price_suffix'] ); ?></small>
							<?php endif; ?>
						</div>
						<p class="busly-pricing-desc"><?php echo esc_html( $plan['description'] ); ?></p>
						<?php
						$features = array_filter( array_map( 'trim', explode( "\n", (string) $plan['features'] ) ) );
						if ( $features ) :
							?>
							<ul class="busly-pricing-features">
								<?php foreach ( $features as $feature ) : ?>
									<li><?php busly_icon( 'check' ); ?> <?php echo esc_html( $feature ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<a href="<?php echo esc_url( $plan['link']['url'] ?? '#' ); ?>" class="busly-btn <?php echo 'yes' === $plan['featured'] ? 'busly-btn--primary' : 'busly-btn--outline'; ?>" style="width:100%;">
							<?php echo esc_html( $plan['button_text'] ); ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
