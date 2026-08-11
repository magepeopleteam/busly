<?php
/**
 * The search form (get_search_form() template).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$busly_unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="busly-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $busly_unique_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'busly' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $busly_unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search…', 'placeholder', 'busly' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
	<button type="submit" class="search-submit">
		<?php busly_icon( 'search' ); ?>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'busly' ); ?></span>
	</button>
</form>
