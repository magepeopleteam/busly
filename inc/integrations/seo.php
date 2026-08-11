<?php
/**
 * SEO-plugin compatibility. Busly never prints its own meta description,
 * canonical, Open Graph or JSON-LD tags — it defers entirely to whatever
 * SEO plugin (Yoast, Rank Math, All in One SEO…) is active, per PHASE 33.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is a recognised SEO plugin active? Used only to decide whether Busly's
 * own (very small) breadcrumb fallback should render.
 *
 * @return bool
 */
function busly_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' )                 // Yoast SEO.
		|| class_exists( 'RankMath' )                  // Rank Math.
		|| defined( 'AIOSEO_VERSION' )                 // All in One SEO.
		|| class_exists( 'SEOPress' );                  // SEOPress.
}

/**
 * Render a breadcrumb trail. Prefers the active SEO plugin's own breadcrumb
 * function (so its settings/schema stay authoritative); falls back to a
 * minimal Busly breadcrumb only when no SEO plugin provides one.
 */
function busly_breadcrumbs() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="busly-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'busly' ) . '">', '</nav>' );
		return;
	}

	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
		return;
	}

	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="busly-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'busly' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'busly' ) . '</a>';

	if ( is_singular( 'wbtm_bus' ) ) {
		echo ' <span aria-hidden="true">/</span> ' . esc_html( busly_bus_label() );
	} elseif ( is_singular( 'post' ) ) {
		echo ' <span aria-hidden="true">/</span> ' . esc_html__( 'Blog', 'busly' );
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			echo ' <span aria-hidden="true">/</span> <a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
		}
	} elseif ( is_search() ) {
		echo ' <span aria-hidden="true">/</span> ' . esc_html__( 'Search Results', 'busly' );
	} elseif ( is_404() ) {
		echo ' <span aria-hidden="true">/</span> ' . esc_html__( 'Page Not Found', 'busly' );
	}

	echo ' <span aria-hidden="true">/</span> <span aria-current="page">' . esc_html( wp_strip_all_tags( get_the_title() ) ) . '</span>';
	echo '</nav>';
}
