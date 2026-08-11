<?php
/**
 * The sidebar containing the blog widget area.
 * (Kept for template-hierarchy completeness / get_sidebar() callers;
 * single.php/archive.php/search.php/home.php already inline this markup
 * themselves so the sidebar sits inside the same .busly-content-grid.)
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_active_sidebar( 'sidebar-blog' ) ) {
	return;
}
?>
<aside class="busly-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'busly' ); ?>">
	<?php dynamic_sidebar( 'sidebar-blog' ); ?>
</aside>
