<?php
/**
 * Reusable "not the homepage" page header: breadcrumbs + title.
 * Skipped entirely on Elementor-built pages (they own their own hero).
 *
 * @param bool $busly_show_title Whether to print the <h1>. Default true.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( busly_is_elementor_built() ) {
	return;
}

$busly_show_title = isset( $busly_show_title ) ? (bool) $busly_show_title : true;
?>
<div class="busly-page-header">
	<div class="wrap">
		<?php busly_breadcrumbs(); ?>
		<?php if ( $busly_show_title ) : ?>
			<h1 class="busly-page-title">
				<?php
				if ( is_search() ) {
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search results for: %s', 'busly' ),
						'<span>' . esc_html( get_search_query() ) . '</span>'
					);
				} elseif ( is_home() && ! is_front_page() ) {
					single_post_title();
				} elseif ( is_archive() ) {
					the_archive_title();
				} else {
					the_title();
				}
				?>
			</h1>
		<?php endif; ?>
	</div>
</div>
