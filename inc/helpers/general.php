<?php
/**
 * Small, dependency-free helper functions used across templates.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a single key from the busly_theme_options array option (see
 * inc/theme-options.php for the Settings API registration + defaults).
 *
 * @param string $key     Option key.
 * @param mixed  $default Fallback if not set.
 * @return mixed
 */
function busly_get_option( $key, $default = '' ) {
	static $options = null;

	if ( null === $options ) {
		$options = get_option( 'busly_theme_options', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
	}

	if ( array_key_exists( $key, $options ) ) {
		return $options[ $key ];
	}

	return function_exists( 'busly_theme_option_defaults' ) && array_key_exists( $key, busly_theme_option_defaults() )
		? busly_theme_option_defaults()[ $key ]
		: $default;
}

/**
 * Whether the current page should render a transparent-over-photo header
 * (only meaningful when a hero widget is the very first content block).
 *
 * @return bool
 */
function busly_use_transparent_header() {
	if ( ! get_theme_mod( 'busly_header_transparent', true ) ) {
		return false;
	}
	return is_front_page();
}

/**
 * Numbered pagination markup (wraps paginate_links()).
 */
function busly_pagination() {
	$links = paginate_links(
		array(
			'prev_text' => busly_get_icon_svg( 'chevron-right' ) === '' ? __( 'Prev', 'busly' ) : '<span aria-hidden="true">&larr;</span> ' . __( 'Prev', 'busly' ),
			'next_text' => __( 'Next', 'busly' ) . ' <span aria-hidden="true">&rarr;</span>',
			'type'      => 'list',
		)
	);

	if ( $links ) {
		echo '<nav class="busly-pagination" aria-label="' . esc_attr__( 'Posts navigation', 'busly' ) . '">' . wp_kses_post( $links ) . '</nav>';
	}
}

/**
 * Reading time estimate for a post, ~200 wpm.
 *
 * @param int $post_id Post ID.
 * @return int Minutes, minimum 1.
 */
function busly_reading_time( $post_id ) {
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) ceil( $word_count / 200 ) );
}

/**
 * Custom excerpt length driven by Busly Theme Settings (Blog tab).
 *
 * @param int $length Default WordPress excerpt length.
 * @return int
 */
function busly_filter_excerpt_length( $length ) {
	return (int) busly_get_option( 'blog_excerpt_length', 22 );
}
add_filter( 'excerpt_length', 'busly_filter_excerpt_length', 999 );

/**
 * Excerpt "more" marker matching the theme's "Read more" links (rendered
 * separately in template-parts, so the inline marker itself stays empty).
 */
function busly_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'busly_excerpt_more' );

/**
 * Initials avatar fallback ("Rafi Ahmed" -> "RA") for demo testimonials and
 * comment authors without a Gravatar, matching the .av class in the design.
 *
 * @param string $name Full name.
 * @return string
 */
function busly_initials( $name ) {
	$name  = trim( (string) $name );
	$parts = preg_split( '/\s+/', $name );
	$parts = array_filter( $parts );

	if ( empty( $parts ) ) {
		return '?';
	}

	$initials = '';
	foreach ( array_slice( $parts, 0, 2 ) as $part ) {
		$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
	}

	return $initials;
}

/**
 * Star rating markup (★★★★☆) for a 1–5 integer rating.
 *
 * @param int $rating 1-5.
 */
function busly_star_rating( $rating ) {
	$rating = max( 0, min( 5, (int) $rating ) );
	echo '<div class="stars" aria-label="' . esc_attr(
		sprintf(
			/* translators: %d: rating out of 5 */
			__( '%d out of 5 stars', 'busly' ),
			$rating
		)
	) . '">' . esc_html( str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ) ) . '</div>';
}
