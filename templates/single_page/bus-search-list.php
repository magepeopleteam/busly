<?php
/**
 * Busly override of the plugin's dedicated search-results page chrome
 * (rendered when `get_query_var( 'bussearchlist' )` is true — the
 * `search-result` page created on plugin activation).
 *
 * Loaded via WBTM_Functions::template_path( 'single_page/bus-search-list.php' ).
 * The plugin's own hooks and the `[wbtm-bus-search]` shortcode call are kept
 * verbatim; only the surrounding markup/classes are Busly's.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
the_post();

/** This action is documented in the Bus Ticket Booking plugin. */
do_action( 'wbtm_before_search_listing_page' );
?>

<main id="primary" class="busly-main busly-search-results">
	<div class="wrap">
		<?php busly_breadcrumbs(); ?>

		<div class="sec-hd">
			<h1 class="sec-h"><?php the_title(); ?></h1>
			<?php
			$busly_page_copy = trim( str_replace( '[wbtm-bus-search]', '', get_the_content() ) );
			if ( $busly_page_copy ) {
				echo '<div class="sec-p">' . wp_kses_post( $busly_page_copy ) . '</div>';
			}
			?>
		</div>

		<?php do_action( 'busly_before_booking_form' ); ?>

		<div class="wbtm_style wbtm_container">
			<?php echo do_shortcode( '[wbtm-bus-search]' ); ?>
		</div>

		<?php do_action( 'busly_after_booking_form' ); ?>
	</div>
</main>

<?php
/**
 * This action is documented in the Bus Ticket Booking plugin.
 */
do_action( 'wbtm_after_search_listing_page' );

get_footer();
