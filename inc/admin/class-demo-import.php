<?php
/**
 * Busly Demo Import — AJAX-driven, idempotent, non-destructive.
 *
 * Every step checks for existing content (by a `_busly_demo` post meta flag,
 * or by option) before creating anything, so re-running the wizard never
 * duplicates pages/menus and never overwrites content a merchant has since
 * edited. Nothing here ever deletes existing user content.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Demo_Import
 */
class Busly_Demo_Import {

	/**
	 * Bootstrap AJAX handler.
	 */
	public static function init() {
		add_action( 'wp_ajax_busly_demo_import_step', array( __CLASS__, 'ajax_step' ) );
	}

	/**
	 * AJAX dispatcher — one request per wizard step (see setup-wizard.js).
	 */
	public static function ajax_step() {
		check_ajax_referer( 'busly_demo_import', 'nonce' );

		if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
			wp_send_json_error( array( 'message' => __( 'You are not allowed to import demo content.', 'busly' ) ) );
		}

		$step = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';

		$method = 'step_' . $step;
		if ( ! method_exists( __CLASS__, $method ) ) {
			wp_send_json_error( array( 'message' => __( 'Unknown import step.', 'busly' ) ) );
		}

		try {
			$message = self::$method();
			wp_send_json_success( array( 'message' => $message ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Find an existing demo page by its stable demo key, regardless of
	 * whether the merchant has since renamed/moved it.
	 *
	 * @param string $key Stable key, e.g. 'home', 'about'.
	 * @return int Post ID, or 0 if not found.
	 */
	private static function find_demo_page( $key ) {
		$posts = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'meta_key'       => '_busly_demo_key', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		return ! empty( $posts ) ? (int) $posts[0] : 0;
	}

	/**
	 * Create a demo page if it doesn't already exist for this key.
	 *
	 * @param string $key     Stable demo key.
	 * @param string $title   Page title.
	 * @param string $content Page content (post_content).
	 * @return int Post ID (existing or newly created).
	 */
	private static function ensure_page( $key, $title, $content = '' ) {
		$existing = self::find_demo_page( $key );
		if ( $existing ) {
			return $existing;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			throw new Exception( esc_html( $post_id->get_error_message() ) );
		}

		update_post_meta( $post_id, '_busly_demo_key', $key );

		return $post_id;
	}

	/**
	 * Step: create demo pages.
	 *
	 * @return string Status message.
	 */
	public static function step_pages() {
		self::ensure_page( 'home', __( 'Home', 'busly' ), '' );

		self::ensure_page(
			'about',
			__( 'About Us', 'busly' ),
			"<!-- wp:heading --><h2>" . esc_html__( 'Moving people, comfortably.', 'busly' ) . "</h2><!-- /wp:heading -->\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'Busly connects travelers with verified bus operators across the country. Since day one, our mission has been simple: make booking a bus ticket as easy as booking a flight — transparent pricing, real seat maps, and a digital ticket that just works.', 'busly' ) . '</p><!-- /wp:paragraph -->' . "\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'We partner only with operators who meet our safety and comfort standards, and every route on Busly is backed by 24/7 customer support.', 'busly' ) . '</p><!-- /wp:paragraph -->'
		);

		self::ensure_page(
			'contact',
			__( 'Contact', 'busly' ),
			'<!-- wp:heading --><h2>' . esc_html__( "We're here to help", 'busly' ) . '</h2><!-- /wp:heading -->' . "\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'Email us at support@busly.example or call +880 1234-567890, available 24/7.', 'busly' ) . '</p><!-- /wp:paragraph -->' . "\n" .
			'<!-- wp:paragraph --><p><em>' . esc_html__( 'Add a Contact Form 7 or WPForms block/shortcode here for a working contact form — Busly automatically matches its styling.', 'busly' ) . '</em></p><!-- /wp:paragraph -->'
		);

		self::ensure_page(
			'faq',
			__( 'FAQ', 'busly' ),
			'<!-- wp:heading --><h2>' . esc_html__( 'How do I book a ticket?', 'busly' ) . '</h2><!-- /wp:heading -->' . "\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'Search your route, pick a bus and seat, then check out securely — your digital ticket arrives instantly.', 'busly' ) . '</p><!-- /wp:paragraph -->' . "\n" .
			'<!-- wp:heading --><h2>' . esc_html__( 'Can I cancel my booking?', 'busly' ) . '</h2><!-- /wp:heading -->' . "\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'Yes, from My Bookings in your account, subject to the operator\'s cancellation window.', 'busly' ) . '</p><!-- /wp:paragraph -->' . "\n" .
			'<!-- wp:paragraph --><p><em>' . esc_html__( 'Tip: replace this page\'s content with the Busly FAQ Elementor widget for an accordion layout.', 'busly' ) . '</em></p><!-- /wp:paragraph -->'
		);

		self::ensure_page(
			'terms',
			__( 'Terms & Conditions', 'busly' ),
			'<!-- wp:paragraph --><p><em>' . esc_html__( 'Sample placeholder — replace with your own Terms & Conditions before launch.', 'busly' ) . '</em></p><!-- /wp:paragraph -->' . "\n" .
			'<!-- wp:paragraph --><p>' . esc_html__( 'By booking through Busly you agree to the fare rules, baggage policy, and cancellation terms of the operating bus company. Busly acts as a booking platform on behalf of verified operators.', 'busly' ) . '</p><!-- /wp:paragraph -->'
		);

		$blog_id = self::ensure_page( 'blog', __( 'Blog', 'busly' ), '' );

		// Privacy Policy: reuse WordPress's own default draft if one exists,
		// otherwise create ours — never orphan the site's privacy setting.
		$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
		if ( ! $privacy_id || 'trash' === get_post_status( $privacy_id ) ) {
			$privacy_id = self::ensure_page(
				'privacy',
				__( 'Privacy Policy', 'busly' ),
				'<!-- wp:paragraph --><p><em>' . esc_html__( 'Sample placeholder — replace with your own Privacy Policy before launch.', 'busly' ) . '</em></p><!-- /wp:paragraph -->'
			);
			update_option( 'wp_page_for_privacy_policy', $privacy_id );
		}

		update_option( 'page_for_posts', $blog_id );

		return __( 'Demo pages created.', 'busly' );
	}

	/**
	 * Step: build navigation menus (primary + 3 footer columns).
	 *
	 * @return string Status message.
	 */
	public static function step_menus() {
		$locations = get_theme_mod( 'nav_menu_locations', array() );

		// Matches the reference design's header nav exactly: Home, Routes,
		// Destinations, Offers, About, Contact.
		$primary_items = array(
			array( 'title' => __( 'Home', 'busly' ), 'page_key' => 'home' ),
			array( 'title' => __( 'Routes', 'busly' ), 'url' => self::search_page_url() ),
			array( 'title' => __( 'Destinations', 'busly' ), 'url' => home_url( '/#busly-destinations' ) ),
			array( 'title' => __( 'Offers', 'busly' ), 'url' => home_url( '/#busly-offers' ) ),
			array( 'title' => __( 'About', 'busly' ), 'page_key' => 'about' ),
			array( 'title' => __( 'Contact', 'busly' ), 'page_key' => 'contact' ),
		);

		$primary_menu_id = self::ensure_menu( __( 'Primary Menu', 'busly' ), $primary_items );
		$locations['primary'] = $primary_menu_id;

		// Footer columns mirror the reference design's Company / Travel /
		// Support link sets, substituting a real page or homepage anchor for
		// every reference link that has one, and dropping the couple of
		// reference-only labels ("Careers", "Press", "Operators", "Help
		// Center", "Cancellation Policy") that have no page to point to yet.
		$footer_1 = self::ensure_menu(
			__( 'Footer — Company', 'busly' ),
			array(
				array( 'title' => __( 'About Us', 'busly' ), 'page_key' => 'about' ),
				array( 'title' => __( 'Blog', 'busly' ), 'page_key' => 'blog' ),
				array( 'title' => __( 'Contact', 'busly' ), 'page_key' => 'contact' ),
			)
		);
		$locations['footer-1'] = $footer_1;

		$footer_2 = self::ensure_menu(
			__( 'Footer — Travel', 'busly' ),
			array(
				array( 'title' => __( 'Routes', 'busly' ), 'url' => self::search_page_url() ),
				array( 'title' => __( 'Destinations', 'busly' ), 'url' => home_url( '/#busly-destinations' ) ),
				array( 'title' => __( 'Special Offers', 'busly' ), 'url' => home_url( '/#busly-offers' ) ),
				array( 'title' => __( 'How It Works', 'busly' ), 'url' => home_url( '/#busly-how-it-works' ) ),
			)
		);
		$locations['footer-2'] = $footer_2;

		$footer_3 = self::ensure_menu(
			__( 'Footer — Support', 'busly' ),
			array(
				array( 'title' => __( 'FAQ', 'busly' ), 'page_key' => 'faq' ),
				array( 'title' => __( 'Terms of Service', 'busly' ), 'page_key' => 'terms' ),
				array( 'title' => __( 'Privacy Policy', 'busly' ), 'url' => get_privacy_policy_url() ),
			)
		);
		$locations['footer-3'] = $footer_3;

		set_theme_mod( 'nav_menu_locations', $locations );

		return __( 'Navigation menus created and assigned.', 'busly' );
	}

	/**
	 * Create a menu (if a menu of that name doesn't already exist) with the
	 * given items, resolving `page_key` entries to their actual page IDs.
	 *
	 * @param string $name  Menu name.
	 * @param array  $items List of array{title, page_key?, url?}.
	 * @return int Menu (term) ID.
	 */
	private static function ensure_menu( $name, $items ) {
		$existing = wp_get_nav_menu_object( $name );
		if ( $existing ) {
			return $existing->term_id;
		}

		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			throw new Exception( esc_html( $menu_id->get_error_message() ) );
		}

		foreach ( $items as $position => $item ) {
			$url = '#';
			if ( ! empty( $item['page_key'] ) ) {
				$page_id = self::find_demo_page( $item['page_key'] );
				$url     = $page_id ? get_permalink( $page_id ) : home_url( '/' );
			} elseif ( ! empty( $item['url'] ) ) {
				$url = $item['url'];
			}

			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'    => $item['title'],
					'menu-item-url'      => $url,
					'menu-item-status'   => 'publish',
					'menu-item-position' => $position + 1,
				)
			);
		}

		return $menu_id;
	}

	/**
	 * URL of the plugin's own auto-created bus search page, if the plugin
	 * created one; otherwise the site home URL as a safe fallback.
	 *
	 * @return string
	 */
	private static function search_page_url() {
		foreach ( array( 'bus-global-search', 'search-result' ) as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page ) {
				return get_permalink( $page );
			}
		}
		return home_url( '/' );
	}

	/**
	 * Step: assemble the Elementor homepage and set it as the static front page.
	 *
	 * @return string Status message.
	 */
	public static function step_homepage() {
		$home_id = self::find_demo_page( 'home' );
		if ( ! $home_id ) {
			$home_id = self::ensure_page( 'home', __( 'Home', 'busly' ), '' );
		}

		if ( did_action( 'elementor/loaded' ) ) {
			require_once BUSLY_DIR . '/inc/admin/class-elementor-homepage.php';
			Busly_Elementor_Homepage::build( $home_id );
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );

		return __( 'Homepage assembled and set as your static front page.', 'busly' );
	}

	/**
	 * Step: apply sensible Theme Settings defaults tying it all together.
	 *
	 * @return string Status message.
	 */
	public static function step_options() {
		$options = get_option( 'busly_theme_options', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$search_url = self::search_page_url();
		$search_id  = url_to_postid( $search_url );
		if ( $search_id ) {
			$options['booking_search_page'] = $search_id;
		}

		$options = wp_parse_args( $options, busly_theme_option_defaults() );
		update_option( 'busly_theme_options', $options );

		return __( 'Theme settings applied.', 'busly' );
	}

	/**
	 * Step: mark import complete.
	 *
	 * @return string Status message.
	 */
	public static function step_finish() {
		update_option( 'busly_demo_imported', time() );

		return __( 'All done!', 'busly' );
	}
}

Busly_Demo_Import::init();
