<?php
/**
 * Busly Offers Widget — "Special Offers" promo card grid.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class Busly_Widget_Offers
 */
class Busly_Widget_Offers extends Busly_Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'busly-offers';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return __( 'Busly Offers', 'busly' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-sale';
	}

	/**
	 * Available color themes for offer cards.
	 *
	 * @return array<string,array{bg:string,border:string,tag_bg:string,text:string}>
	 */
	private function color_themes() {
		return array(
			'indigo' => array( 'bg' => '#eef2ff', 'border' => '#c7d2fe', 'tag_bg' => '#e0e7ff', 'text' => '#1d3d87' ),
			'green'  => array( 'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'tag_bg' => '#dcfce7', 'text' => '#15803d' ),
			'amber'  => array( 'bg' => '#fffbeb', 'border' => '#fde68a', 'tag_bg' => '#fef3c7', 'text' => '#b45309' ),
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_section_header_controls();

		$this->start_controls_section(
			'section_items',
			array(
				'label' => __( 'Offers', 'busly' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'tag', array( 'label' => __( 'Tag', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( 'Early Bird', 'busly' ) ) );
		$repeater->add_control( 'title', array( 'label' => __( 'Title', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => __( '20% Off Morning Buses', 'busly' ), 'label_block' => true ) );
		$repeater->add_control( 'description', array( 'label' => __( 'Description', 'busly' ), 'type' => Controls_Manager::TEXTAREA, 'default' => __( 'Book 7+ days in advance and save on any AC route.', 'busly' ) ) );
		$repeater->add_control( 'code', array( 'label' => __( 'Coupon Code', 'busly' ), 'type' => Controls_Manager::TEXT, 'default' => 'EARLY20' ) );
		$repeater->add_control( 'link', array( 'label' => __( 'Link', 'busly' ), 'type' => Controls_Manager::URL, 'default' => array( 'url' => '#' ) ) );
		$repeater->add_control(
			'theme',
			array(
				'label'   => __( 'Color Theme', 'busly' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'indigo',
				'options' => array( 'indigo' => __( 'Indigo', 'busly' ), 'green' => __( 'Green', 'busly' ), 'amber' => __( 'Amber', 'busly' ) ),
			)
		);

		$this->add_control(
			'offers',
			array(
				'label'       => __( 'Items', 'busly' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'tag' => __( 'Early Bird', 'busly' ), 'title' => __( '20% Off Morning Buses', 'busly' ), 'description' => __( 'Book 7+ days in advance and save on any AC route.', 'busly' ), 'code' => 'EARLY20', 'theme' => 'indigo' ),
					array( 'tag' => __( 'Weekend Deal', 'busly' ), 'title' => __( 'Flat ৳150 Off Fridays', 'busly' ), 'description' => __( 'Every weekend, automatically applied at checkout.', 'busly' ), 'code' => 'WKND150', 'theme' => 'green' ),
					array( 'tag' => __( 'Family Offer', 'busly' ), 'title' => __( '4 Passengers, 1 Free', 'busly' ), 'description' => __( 'Travel as a group of 4 or more and one ticket is on us.', 'busly' ), 'code' => 'FAM4+1', 'theme' => 'amber' ),
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
		$themes   = $this->color_themes();
		?>
		<div class="busly-offers">
			<?php $this->render_section_header( $settings ); ?>

			<div class="off-grid">
				<?php foreach ( $settings['offers'] as $offer ) : ?>
					<?php $c = $themes[ $offer['theme'] ] ?? $themes['indigo']; ?>
					<div class="off-c" style="background:<?php echo esc_attr( $c['bg'] ); ?>;border-color:<?php echo esc_attr( $c['border'] ); ?>">
						<span class="off-tag" style="background:<?php echo esc_attr( $c['tag_bg'] ); ?>;color:<?php echo esc_attr( $c['text'] ); ?>"><?php echo esc_html( $offer['tag'] ); ?></span>
						<div class="off-t"><?php echo esc_html( $offer['title'] ); ?></div>
						<div class="off-d"><?php echo esc_html( $offer['description'] ); ?></div>
						<div class="off-foot">
							<?php if ( ! empty( $offer['code'] ) ) : ?>
								<span class="off-code" style="color:<?php echo esc_attr( $c['text'] ); ?>;border-color:<?php echo esc_attr( $c['border'] ); ?>"><?php echo esc_html( $offer['code'] ); ?></span>
							<?php endif; ?>
							<a href="<?php echo esc_url( $offer['link']['url'] ?? '#' ); ?>" class="off-cta" style="color:<?php echo esc_attr( $c['text'] ); ?>"><?php esc_html_e( 'Book now →', 'busly' ); ?></a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
