<?php
/**
 * Builds the Elementor `_elementor_data` JSON for the demo homepage (build())
 * and for the alternate "Homepage 2" / Modern design (build_modern()) — one
 * section per Busly widget, in the exact order of homepage.html. Every
 * widget is used with its own built-in defaults (see
 * inc/elementor/widgets/*.php), so this file only has to describe *layout*
 * (plus, for build_modern(), the handful of illustration images that design
 * uses), not the bulk of the copy.
 *
 * Only ever runs from Busly_Demo_Import::step_homepage() / step_homepage2()
 * — never touches an existing page's Elementor data if one is already saved.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Elementor_Homepage
 */
class Busly_Elementor_Homepage {

	/**
	 * Write the demo homepage layout to $post_id, unless it already has
	 * Elementor data (never clobber a merchant's own edits on re-import).
	 *
	 * @param int $post_id Target page ID.
	 */
	public static function build( $post_id ) {
		$existing = get_post_meta( $post_id, '_elementor_data', true );
		if ( ! empty( $existing ) && '[]' !== $existing ) {
			return;
		}

		$data = array(
			self::section( array( self::column( array( self::widget( 'busly-hero' ) ) ) ), array( 'custom_margin' => '' ) ),
			self::section( array( self::column( array( self::widget( 'busly-bus-search' ) ) ) ) ),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-featured-routes',
								array(
									'eyebrow' => __( 'Popular Routes', 'busly' ),
									'heading' => __( 'Most Traveled Corridors', 'busly' ),
									'show_link' => 'yes',
									'link_text' => __( 'View all routes', 'busly' ),
								)
							),
						)
					),
				)
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-features',
								array(
									'eyebrow'      => __( 'Why Busly', 'busly' ),
									'heading'      => __( 'Why Travelers Choose Us', 'busly' ),
									'description'  => __( 'Everything you need for a stress-free, comfortable bus journey.', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#f8fafc' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-destinations',
								array(
									'eyebrow'   => __( 'Destinations', 'busly' ),
									'heading'   => __( 'Popular Destinations', 'busly' ),
									'show_link' => 'yes',
									'link_text' => __( 'Explore all', 'busly' ),
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-destinations' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-offers',
								array(
									'eyebrow'      => __( 'Promotions', 'busly' ),
									'heading'      => __( 'Special Offers', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#f8fafc', '_element_id' => 'busly-offers' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-steps',
								array(
									'eyebrow'      => __( 'Process', 'busly' ),
									'heading'      => __( 'How It Works', 'busly' ),
									'description'  => __( 'Book your bus seat in 4 effortless steps.', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-how-it-works' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-testimonial',
								array(
									'eyebrow'      => __( 'Testimonials', 'busly' ),
									'heading'      => __( 'What Travelers Say', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#f8fafc' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-faq',
								array(
									'eyebrow'           => __( 'FAQ', 'busly' ),
									'heading'           => __( 'Frequently Asked Questions', 'busly' ),
									'description'       => __( 'Everything you need to know before you book.', 'busly' ),
									'header_align'      => 'center',
									'side_heading'      => __( 'Still have questions?', 'busly' ),
									'side_description'  => __( 'Our support team is available 24/7 to help with bookings, cancellations and more.', 'busly' ),
									'side_button_text'  => __( 'Contact Support', 'busly' ),
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-faq' )
			),
			self::section( array( self::column( array( self::widget( 'busly-cta' ) ) ) ) ),
		);

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_version', self::elementor_version() );

		self::regenerate_css( $post_id );
	}

	/**
	 * Write the "Homepage 2" (Modern design) layout to $post_id, unless it
	 * already has Elementor data. Same widgets, same order and copy as
	 * build() — the navy/terracotta look comes entirely from
	 * assets/css/homepage-modern.css (scoped to the `_busly_page_skin =
	 * modern` post meta the caller sets), not from anything here. The only
	 * content differences are the illustrations this design uses (hero
	 * corner graphic, destination photos, offer banners, CTA phone
	 * mockup), all shipped with the theme under assets/images/homepage2/
	 * so they survive a theme re-install.
	 *
	 * @param int $post_id Target page ID.
	 */
	public static function build_modern( $post_id ) {
		$existing = get_post_meta( $post_id, '_elementor_data', true );
		if ( ! empty( $existing ) && '[]' !== $existing ) {
			return;
		}

		$img = BUSLY_URI . '/assets/images/homepage2/';

		$data = array(
			self::section( array( self::column( array( self::widget(
				'busly-hero',
				array(
					'background_image' => array( 'url' => $img . 'hero-illustration.svg', 'id' => '' ),
				)
			) ) ) ), array( 'custom_margin' => '' ) ),
			self::section( array( self::column( array( self::widget( 'busly-bus-search' ) ) ) ) ),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-featured-routes',
								array(
									'eyebrow' => __( 'Popular Routes', 'busly' ),
									'heading' => __( 'Most Traveled Corridors', 'busly' ),
									'show_link' => 'yes',
									'link_text' => __( 'View all routes', 'busly' ),
								)
							),
						)
					),
				)
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-features',
								array(
									'eyebrow'      => __( 'Why Busly', 'busly' ),
									'heading'      => __( 'Why Travelers Choose Us', 'busly' ),
									'description'  => __( 'Everything you need for a stress-free, comfortable bus journey.', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#F3F0E9' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-destinations',
								array(
									'eyebrow'   => __( 'Destinations', 'busly' ),
									'heading'   => __( 'Popular Destinations', 'busly' ),
									'show_link' => 'yes',
									'link_text' => __( 'Explore all', 'busly' ),
									// No 'destinations' override: same photos as the main
									// homepage, via the widget's own built-in defaults
									// (see class-widget-destinations.php).
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-destinations' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-offers',
								array(
									'eyebrow'      => __( 'Promotions', 'busly' ),
									'heading'      => __( 'Special Offers', 'busly' ),
									'header_align' => 'center',
									'offers' => array(
										array( 'banner_image' => array( 'url' => $img . 'offer-earlybird.svg', 'id' => '' ), 'tag' => __( 'Early Bird', 'busly' ), 'title' => __( '20% Off Morning Buses', 'busly' ), 'description' => __( 'Book 7+ days in advance and save on any AC route.', 'busly' ), 'code' => 'EARLY20', 'link' => array( 'url' => '#' ), 'theme' => 'indigo' ),
										array( 'banner_image' => array( 'url' => $img . 'offer-weekend.svg', 'id' => '' ), 'tag' => __( 'Weekend Deal', 'busly' ), 'title' => __( 'Flat ৳150 Off Fridays', 'busly' ), 'description' => __( 'Every weekend, automatically applied at checkout.', 'busly' ), 'code' => 'WKND150', 'link' => array( 'url' => '#' ), 'theme' => 'green' ),
										array( 'banner_image' => array( 'url' => $img . 'offer-family.svg', 'id' => '' ), 'tag' => __( 'Family Offer', 'busly' ), 'title' => __( '4 Passengers, 1 Free', 'busly' ), 'description' => __( 'Travel as a group of 4 or more and one ticket is on us.', 'busly' ), 'code' => 'FAM4+1', 'link' => array( 'url' => '#' ), 'theme' => 'amber' ),
									),
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#F3F0E9', '_element_id' => 'busly-offers' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-steps',
								array(
									'eyebrow'      => __( 'Process', 'busly' ),
									'heading'      => __( 'How It Works', 'busly' ),
									'description'  => __( 'Book your bus seat in 4 effortless steps.', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-how-it-works' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-testimonial',
								array(
									'eyebrow'      => __( 'Testimonials', 'busly' ),
									'heading'      => __( 'What Travelers Say', 'busly' ),
									'header_align' => 'center',
								)
							),
						)
					),
				),
				array( 'background_background' => 'classic', 'background_color' => '#F3F0E9' )
			),
			self::section(
				array(
					self::column(
						array(
							self::widget(
								'busly-faq',
								array(
									'eyebrow'           => __( 'FAQ', 'busly' ),
									'heading'           => __( 'Frequently Asked Questions', 'busly' ),
									'description'       => __( 'Everything you need to know before you book.', 'busly' ),
									'header_align'      => 'center',
									'side_heading'      => __( 'Still have questions?', 'busly' ),
									'side_description'  => __( 'Our support team is available 24/7 to help with bookings, cancellations and more.', 'busly' ),
									'side_button_text'  => __( 'Contact Support', 'busly' ),
								)
							),
						)
					),
				),
				array( '_element_id' => 'busly-faq' )
			),
			self::section( array( self::column( array( self::widget(
				'busly-cta',
				array(
					'eyebrow'     => __( 'Paperless travel', 'busly' ),
					'description' => __( 'Join 100,000+ travelers who book smarter with Busly. Your ticket lives on your phone — just show it at boarding.', 'busly' ),
					'side_image'  => array( 'url' => $img . 'cta-phone.svg', 'id' => '' ),
				)
			) ) ) ) ),
		);

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_version', self::elementor_version() );
		update_post_meta( $post_id, '_busly_page_skin', 'modern' );

		self::regenerate_css( $post_id );
	}

	/**
	 * Current Elementor version, or a safe fallback string.
	 *
	 * @return string
	 */
	private static function elementor_version() {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			return ELEMENTOR_VERSION;
		}
		return '3.20.0';
	}

	/**
	 * Force Elementor to regenerate this page's CSS immediately, so it
	 * renders correctly on first visit without an editor round-trip.
	 *
	 * @param int $post_id Page ID.
	 */
	private static function regenerate_css( $post_id ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		try {
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
				$css_file->update();
			} elseif ( isset( \Elementor\Plugin::$instance->files_manager ) ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}
		} catch ( Exception $e ) {
			// Non-fatal: Elementor will regenerate CSS on first editor save
			// or first front-end request either way.
			unset( $e );
		}
	}

	/**
	 * Build a full-width section element.
	 *
	 * @param array $columns  Child column elements.
	 * @param array $settings Extra Elementor section settings to merge in.
	 * @return array
	 */
	private static function section( $columns, $settings = array() ) {
		return array(
			'id'       => self::uid(),
			'elType'   => 'section',
			'settings' => array_merge( array( 'layout' => 'full_width' ), $settings ),
			'elements' => $columns,
			'isInner'  => false,
		);
	}

	/**
	 * Build a single full-width (100%) column element.
	 *
	 * @param array $widgets Child widget elements.
	 * @return array
	 */
	private static function column( $widgets ) {
		return array(
			'id'       => self::uid(),
			'elType'   => 'column',
			'settings' => array( '_column_size' => 100 ),
			'elements' => $widgets,
			'isInner'  => false,
		);
	}

	/**
	 * Build a widget element.
	 *
	 * @param string $type     Registered widget name, e.g. 'busly-hero'.
	 * @param array  $settings Widget settings overrides (merged over its defaults).
	 * @return array
	 */
	private static function widget( $type, $settings = array() ) {
		return array(
			'id'         => self::uid(),
			'elType'     => 'widget',
			'settings'   => $settings,
			'elements'   => array(),
			'widgetType' => $type,
		);
	}

	/**
	 * Elementor-style 7-character hex element id.
	 *
	 * @return string
	 */
	private static function uid() {
		return substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 7 );
	}
}
