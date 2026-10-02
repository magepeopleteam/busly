<?php
/**
 * Web font loading (Plus Jakarta Sans), with a filter to disable Google Fonts
 * entirely for GDPR / self-hosting setups — see documentation/translation.md.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Busly should pull Plus Jakarta Sans from Google Fonts.
 * Return false via this filter (e.g. in a child theme) to fall back to the
 * system font stack only — no external request is made.
 *
 * @param bool $enabled Default true.
 */
function busly_google_fonts_enabled() {
	$enabled = 'yes' !== busly_get_option( 'perf_disable_google_fonts', '' );
	return (bool) apply_filters( 'busly_enable_google_fonts', $enabled );
}

/**
 * Enqueue Plus Jakarta Sans with preconnect resource hints, matching the
 * design reference exactly (weights 400–900).
 */
function busly_register_fonts() {
	if ( ! busly_google_fonts_enabled() ) {
		return;
	}

	wp_enqueue_style(
		'busly-google-fonts',
		// Manrope rides along with the site's default Plus Jakarta Sans in this
		// one request (same pattern as the modern-skin bundle below): the bus
		// search results card (search_result.php) uses it for body-weight text
		// (times, labels, amenities) while Plus Jakarta Sans stays the heading/
		// price/button face, matching the card's design reference exactly.
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Manrope:wght@400;500;600;700&display=swap',
		array(),
		null // phpcs:ignore -- version intentionally omitted for a third-party URL.
	);

	// Alternate "Homepage 2" skin uses its own type pairing (Space Grotesk
	// headings, IBM Plex Sans body) — loaded alongside, never instead of,
	// the site default above, and only on pages flagged for it.
	if ( busly_is_modern_skin_page() ) {
		wp_enqueue_style(
			'busly-google-fonts-modern',
			'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap',
			array(),
			null // phpcs:ignore -- version intentionally omitted for a third-party URL.
		);
	}
}
add_action( 'wp_enqueue_scripts', 'busly_register_fonts' );

/**
 * Resource hints (preconnect) for the Google Fonts host, only when enabled.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array
 */
function busly_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && busly_google_fonts_enabled() ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
		$urls[] = 'https://fonts.googleapis.com';
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'busly_resource_hints', 10, 2 );
