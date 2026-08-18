<?php
/**
 * WordPress Customizer additions.
 *
 * Scope is intentionally small: the Customizer here only holds settings
 * that are genuinely "live preview" appearance/copy (header behaviour,
 * footer copyright). Everything else — including social links (a repeater
 * under the Social tab) — lives in the dedicated Busly → Theme Settings
 * screen (inc/theme-options.php) per PHASE 17/18, keeping the two systems
 * from ever describing the same option twice.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer panel/sections/controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 */
function busly_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'busly_options',
		array(
			'title'    => __( 'Busly Options', 'busly' ),
			'priority' => 160,
		)
	);

	/* ---------------------------------------------------------------
	 * Header behaviour
	 * ------------------------------------------------------------- */
	$wp_customize->add_section(
		'busly_header_behavior',
		array(
			'title' => __( 'Header Behavior', 'busly' ),
			'panel' => 'busly_options',
		)
	);

	$wp_customize->add_setting(
		'busly_header_sticky',
		array(
			'default'           => true,
			'sanitize_callback' => 'busly_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'busly_header_sticky',
		array(
			'label'   => __( 'Sticky header on scroll', 'busly' ),
			'section' => 'busly_header_behavior',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'busly_header_transparent',
		array(
			'default'           => true,
			'sanitize_callback' => 'busly_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'busly_header_transparent',
		array(
			'label'       => __( 'Transparent header over the homepage hero', 'busly' ),
			'description' => __( 'Only applies to the front page; every other page always uses a solid header.', 'busly' ),
			'section'     => 'busly_header_behavior',
			'type'        => 'checkbox',
		)
	);

	/* ---------------------------------------------------------------
	 * Logo display (Site Identity — alongside core's logo upload control)
	 * ------------------------------------------------------------- */
	$wp_customize->add_setting(
		'busly_logo_display',
		array(
			'default'           => 'logo',
			'sanitize_callback' => 'busly_sanitize_logo_display',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'busly_logo_display',
		array(
			'label'       => __( 'Logo display', 'busly' ),
			'description' => __( 'What to show in the header and footer logo link. "Logo image" falls back to the site title when no logo is uploaded below.', 'busly' ),
			'section'     => 'title_tagline',
			'type'        => 'select',
			'choices'     => array(
				'logo' => __( 'Logo image only', 'busly' ),
				'text' => __( 'Site title only', 'busly' ),
				'both' => __( 'Logo image and site title', 'busly' ),
			),
			'priority'    => 9,
		)
	);

	/* ---------------------------------------------------------------
	 * Footer
	 * ------------------------------------------------------------- */
	$wp_customize->add_section(
		'busly_footer',
		array(
			'title' => __( 'Footer', 'busly' ),
			'panel' => 'busly_options',
		)
	);

	$wp_customize->add_setting(
		'busly_footer_copyright',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'busly_footer_copyright',
		array(
			'label'       => __( 'Copyright text', 'busly' ),
			/* translators: {year} and {site} are literal placeholders, not translatable. */
			'description' => __( 'Use {year} and {site} as placeholders. Leave blank for the default.', 'busly' ),
			'section'     => 'busly_footer',
			'type'        => 'text',
		)
	);

}
add_action( 'customize_register', 'busly_customize_register' );

/**
 * Checkbox sanitizer for Customizer settings.
 *
 * @param mixed $checked Raw value.
 * @return bool
 */
function busly_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === $checked ) || '1' === $checked || 1 === $checked;
}

/**
 * Sanitize the logo display select control.
 *
 * @param string $value Raw value.
 * @return string
 */
function busly_sanitize_logo_display( $value ) {
	$allowed = array( 'logo', 'text', 'both' );
	return in_array( $value, $allowed, true ) ? $value : 'logo';
}

/*
 * Every Busly Customizer control above uses the 'refresh' transport, so no
 * postMessage/selective-refresh preview script is required.
 */
