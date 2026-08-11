<?php
/**
 * Busly override of the plugin's single `wbtm_bus` page chrome.
 *
 * Loaded via WBTM_Functions::template_path( 'single_page/single-bus.php' )
 * (see templates/README.md). Only the wrapper markup/classes differ from the
 * plugin's own copy — every plugin hook and both `require`d sub-templates
 * (which still resolve through the plugin's own template_path() lookup) are
 * preserved unchanged so PRO add-ons and future plugin updates keep working.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
the_post();

$busly_post_id = get_the_ID();

/** This action is documented in the Bus Ticket Booking plugin. */
do_action( 'wbtm_before_single_bus_search_page' );
/** This action is documented in WooCommerce; the plugin re-uses it on purpose. */
do_action( 'woocommerce_before_single_product' );
?>

<main id="primary" class="busly-main busly-single-bus">
	<div class="wrap">
		<?php busly_breadcrumbs(); ?>

		<?php do_action( 'busly_before_booking_form' ); ?>

		<div class="wbtm_style wbtm_container wbtm_single_modern">
			<?php require WBTM_Functions::template_path( 'layout/single_bus_details.php' ); ?>
			<?php require WBTM_Functions::template_path( 'layout/search_form.php' ); ?>
		</div>

		<?php do_action( 'busly_after_booking_form' ); ?>
	</div>
</main>

<?php
do_action( 'wbtm_after_single_bus_search_page' );

get_footer();
