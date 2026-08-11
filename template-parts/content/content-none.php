<?php
/**
 * Shown when a loop returns no results (search/archive).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="busly-notice busly-notice--info">
	<?php if ( is_search() ) : ?>
		<p>
			<?php
			printf(
				/* translators: %s: the search query */
				esc_html__( 'No results found for "%s". Try a different search term.', 'busly' ),
				esc_html( get_search_query() )
			);
			?>
		</p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing to show here yet. Please check back soon.', 'busly' ); ?></p>
	<?php endif; ?>
</div>
