<?php
/**
 * Archive template — category, tag, author, date and custom taxonomy
 * archives (including the plugin's wbtm_bus_cat/wbtm_bus_stops taxonomies,
 * which fall back to this same layout since the plugin registers no
 * archive template of its own for wbtm_bus, per PHASE 1 audit).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$busly_sidebar_pos = busly_get_option( 'blog_sidebar_position', 'right-sidebar' );
?>

<main id="primary" class="busly-main">
	<div class="wrap busly-layout-<?php echo esc_attr( $busly_sidebar_pos ); ?>">
	<div class="busly-content-grid">
		<div class="busly-content-col">
			<?php get_template_part( 'template-parts/content/page-header' ); ?>

			<?php if ( have_posts() ) : ?>
				<?php get_template_part( 'template-parts/content/blog-loop' ); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content-none' ); ?>
			<?php endif; ?>
		</div>

		<?php if ( 'no-sidebar' !== $busly_sidebar_pos && is_active_sidebar( 'sidebar-blog' ) ) : ?>
			<aside class="busly-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'busly' ); ?>">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</aside>
		<?php endif; ?>
	</div>
	</div>
</main>

<?php
get_footer();
