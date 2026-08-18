<?php
/**
 * Web font loading — a curated Google Fonts library (Typography tab), with
 * a filter to disable Google Fonts entirely for GDPR / self-hosting setups.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Busly should pull the selected font from Google Fonts.
 * Return false via this filter (e.g. in a child theme) to fall back to the
 * system font stack only — no external request is made.
 *
 * @return bool
 */
function busly_google_fonts_enabled() {
	$enabled = 'yes' !== busly_get_option( 'perf_disable_google_fonts', '' );
	return (bool) apply_filters( 'busly_enable_google_fonts', $enabled );
}

/**
 * Curated Google Fonts library offered by the Typography tab's font picker.
 * Single source of truth for both the admin <select> choices and the
 * enqueued stylesheet — `custom` (empty key) means "not a library font",
 * in which case only the raw Custom Font Stack option is used and no
 * Google Fonts request is made.
 *
 * @return array<string,array{label:string,family:string,stack:string}>
 */
function busly_google_font_library() {
	static $fonts = null;

	if ( null !== $fonts ) {
		return $fonts;
	}

	$fonts = array(
		'plus_jakarta_sans' => array(
			'label'  => __( 'Plus Jakarta Sans (default)', 'busly' ),
			'family' => 'Plus Jakarta Sans',
			'stack'  => "'Plus Jakarta Sans', sans-serif",
		),
		'inter'             => array(
			'label'  => __( 'Inter', 'busly' ),
			'family' => 'Inter',
			'stack'  => "'Inter', sans-serif",
		),
		'poppins'           => array(
			'label'  => __( 'Poppins', 'busly' ),
			'family' => 'Poppins',
			'stack'  => "'Poppins', sans-serif",
		),
		'manrope'           => array(
			'label'  => __( 'Manrope', 'busly' ),
			'family' => 'Manrope',
			'stack'  => "'Manrope', sans-serif",
		),
		'outfit'            => array(
			'label'  => __( 'Outfit', 'busly' ),
			'family' => 'Outfit',
			'stack'  => "'Outfit', sans-serif",
		),
		'sora'              => array(
			'label'  => __( 'Sora', 'busly' ),
			'family' => 'Sora',
			'stack'  => "'Sora', sans-serif",
		),
		'urbanist'          => array(
			'label'  => __( 'Urbanist', 'busly' ),
			'family' => 'Urbanist',
			'stack'  => "'Urbanist', sans-serif",
		),
		'work_sans'         => array(
			'label'  => __( 'Work Sans', 'busly' ),
			'family' => 'Work Sans',
			'stack'  => "'Work Sans', sans-serif",
		),
		'dm_sans'           => array(
			'label'  => __( 'DM Sans', 'busly' ),
			'family' => 'DM Sans',
			'stack'  => "'DM Sans', sans-serif",
		),
		'space_grotesk'     => array(
			'label'  => __( 'Space Grotesk', 'busly' ),
			'family' => 'Space Grotesk',
			'stack'  => "'Space Grotesk', sans-serif",
		),
		'nunito'            => array(
			'label'  => __( 'Nunito', 'busly' ),
			'family' => 'Nunito',
			'stack'  => "'Nunito', sans-serif",
		),
		'roboto'            => array(
			'label'  => __( 'Roboto', 'busly' ),
			'family' => 'Roboto',
			'stack'  => "'Roboto', sans-serif",
		),
		'open_sans'         => array(
			'label'  => __( 'Open Sans', 'busly' ),
			'family' => 'Open Sans',
			'stack'  => "'Open Sans', sans-serif",
		),
		'lato'              => array(
			'label'  => __( 'Lato', 'busly' ),
			'family' => 'Lato',
			'stack'  => "'Lato', sans-serif",
		),
		'montserrat'        => array(
			'label'  => __( 'Montserrat', 'busly' ),
			'family' => 'Montserrat',
			'stack'  => "'Montserrat', sans-serif",
		),
		'raleway'           => array(
			'label'  => __( 'Raleway', 'busly' ),
			'family' => 'Raleway',
			'stack'  => "'Raleway', sans-serif",
		),
		'rubik'             => array(
			'label'  => __( 'Rubik', 'busly' ),
			'family' => 'Rubik',
			'stack'  => "'Rubik', sans-serif",
		),
		'figtree'           => array(
			'label'  => __( 'Figtree', 'busly' ),
			'family' => 'Figtree',
			'stack'  => "'Figtree', sans-serif",
		),
		'lexend'            => array(
			'label'  => __( 'Lexend', 'busly' ),
			'family' => 'Lexend',
			'stack'  => "'Lexend', sans-serif",
		),
		'ibm_plex_sans'     => array(
			'label'  => __( 'IBM Plex Sans', 'busly' ),
			'family' => 'IBM Plex Sans',
			'stack'  => "'IBM Plex Sans', sans-serif",
		),
		'barlow'            => array(
			'label'  => __( 'Barlow', 'busly' ),
			'family' => 'Barlow',
			'stack'  => "'Barlow', sans-serif",
		),
		'playfair_display'  => array(
			'label'  => __( 'Playfair Display (serif)', 'busly' ),
			'family' => 'Playfair Display',
			'stack'  => "'Playfair Display', serif",
		),
		'merriweather'      => array(
			'label'  => __( 'Merriweather (serif)', 'busly' ),
			'family' => 'Merriweather',
			'stack'  => "'Merriweather', serif",
		),
		'source_serif_4'    => array(
			'label'  => __( 'Source Serif 4 (serif)', 'busly' ),
			'family' => 'Source Serif 4',
			'stack'  => "'Source Serif 4', serif",
		),
	);

	return $fonts;
}

/**
 * Admin <select> choices for the Google Font Family field: the library
 * above plus a leading "Custom" sentinel (empty string key).
 *
 * @return array<string,string>
 */
function busly_google_font_choices() {
	$choices = array( '' => __( 'Custom (use the font stack below)', 'busly' ) );
	foreach ( busly_google_font_library() as $key => $font ) {
		$choices[ $key ] = $font['label'];
	}
	return $choices;
}

/**
 * The currently selected library font, or null when "Custom" is selected
 * (or the saved key no longer exists in the library).
 *
 * @return array{family:string,stack:string,label:string}|null
 */
function busly_get_active_google_font() {
	$key     = busly_get_option( 'font_google_family', 'plus_jakarta_sans' );
	$library = busly_google_font_library();
	return isset( $library[ $key ] ) ? $library[ $key ] : null;
}

/**
 * Enqueue the selected Google Font (weights 400–900; Google Fonts silently
 * omits any weight a family doesn't actually have — no error on request).
 */
function busly_register_fonts() {
	if ( ! busly_google_fonts_enabled() ) {
		return;
	}

	$font = busly_get_active_google_font();
	if ( ! $font ) {
		return; // "Custom" selected — nothing to auto-load.
	}

	$family_param = str_replace( ' ', '+', $font['family'] );

	wp_enqueue_style(
		'busly-google-fonts',
		"https://fonts.googleapis.com/css2?family={$family_param}:wght@400;500;600;700;800;900&display=swap",
		array(),
		null // phpcs:ignore -- version intentionally omitted for a third-party URL.
	);
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
