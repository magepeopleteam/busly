<?php
/**
 * Builds the demo homepage's Elementor `_elementor_data` JSON — one section
 * per Busly widget, in the exact order of homepage.html. Every widget is
 * used with its own built-in defaults (see inc/elementor/widgets/*.php), so
 * this file only has to describe *layout*, not content.
 *
 * Only ever runs from Busly_Demo_Import::step_homepage() — never touches an
 * existing page's Elementor data if one is already saved (see build()).
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
			self::section( array( self::column( array( self::widget( 'busly-cta' ) ) ) ) ),
		);

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_version', self::elementor_version() );

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
