<?php
/**
 * Functions which enhance the theme by hooking into WordPress core.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function busly_body_classes( $classes ) {
	if ( ! is_active_sidebar( 'sidebar-blog' ) || is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'busly-no-sidebar';
	}

	if ( busly_use_transparent_header() ) {
		$classes[] = 'busly-header-transparent';
	}

	if ( get_theme_mod( 'busly_header_sticky', true ) ) {
		$classes[] = 'busly-header-sticky';
	}

	if ( busly_is_elementor_built() ) {
		$classes[] = 'busly-elementor-page';
	}

	$classes[] = 'busly-layout-' . sanitize_html_class( busly_get_option( 'site_layout', 'boxed' ) );

	return $classes;
}
add_filter( 'body_class', 'busly_body_classes' );

/**
 * Native lazy-loading toggle, driven by Busly Theme Settings → Performance.
 *
 * @param bool $default_value Core's default decision.
 * @return bool
 */
function busly_lazy_loading_enabled( $default_value ) {
	if ( 'yes' !== busly_get_option( 'perf_lazy_load_images', 'yes' ) ) {
		return false;
	}
	return $default_value;
}
add_filter( 'wp_lazy_loading_enabled', 'busly_lazy_loading_enabled' );

/**
 * Add a pixel width to the embed defaults, matching the theme's content width.
 *
 * @param array $dimensions Embed dimensions.
 * @return array
 */
function busly_embed_dimensions( $dimensions ) {
	$dimensions['width'] = 1180;
	return $dimensions;
}
add_filter( 'embed_defaults', 'busly_embed_dimensions' );

/**
 * Whether the current request is rendering a page built entirely with
 * Elementor (front-page.php/page.php use this to skip the default title/
 * content wrapper and let Elementor own 100% of the layout).
 *
 * @param int $post_id Optional. Defaults to the current post.
 * @return bool
 */
function busly_is_elementor_built( $post_id = 0 ) {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return false;
	}

	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return false;
	}

	$document = \Elementor\Plugin::$instance->documents->get( $post_id );
	return $document && $document->is_built_with_elementor();
}

/**
 * wp_list_comments() callback — renders one comment matching .busly-comment
 * in components.css. Handles the open <li> only; wp_list_comments() closes it.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    wp_list_comments() args.
 * @param int        $depth   Nesting depth.
 */
function busly_comment_template( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	?>
	<li <?php comment_class( 'busly-comment' ); ?> id="comment-<?php comment_ID(); ?>">
		<div class="busly-comment-avatar">
			<?php echo get_avatar( $comment, 44 ); ?>
		</div>
		<div class="busly-comment-body">
			<div class="busly-comment-meta">
				<span class="busly-comment-author"><?php comment_author(); ?></span>
				<span class="busly-comment-date">
					<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
						<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
					</a>
				</span>
				<?php if ( '0' === $comment->comment_approved ) : ?>
					<em class="busly-comment-date"><?php esc_html_e( 'Awaiting moderation', 'busly' ); ?></em>
				<?php endif; ?>
			</div>
			<div class="busly-comment-content">
				<?php comment_text(); ?>
			</div>
			<div class="busly-comment-reply">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						)
					)
				);
				?>
			</div>
		</div>
	<?php
}

/**
 * Compute the header's Login / My Bookings / Book Now button targets,
 * adapting to whichever plugins are actually active so the header never
 * links to a WooCommerce or booking page that doesn't exist.
 *
 * @return array{login: string, login_label: string, bookings: string, cta: string}
 */
function busly_header_urls() {
	$urls = array(
		'login'       => wp_login_url(),
		'login_label' => __( 'Login', 'busly' ),
		'bookings'    => '',
		'cta'         => busly_get_option( 'header_cta_url', home_url( '/' ) ),
	);

	if ( busly_is_wc_active() ) {
		$account_url    = wc_get_page_permalink( 'myaccount' );
		$urls['login']  = $account_url ? $account_url : $urls['login'];

		if ( is_user_logged_in() ) {
			$urls['login_label'] = __( 'My Account', 'busly' );
		}

		if ( busly_is_booking_engine_ready() ) {
			$urls['bookings'] = wc_get_account_endpoint_url( 'bus-booking-dashboard' );
		}
	}

	$search_page = busly_get_option( 'booking_search_page', 0 );
	if ( $search_page && get_post( $search_page ) ) {
		$urls['cta'] = get_permalink( $search_page );
	}

	return $urls;
}
