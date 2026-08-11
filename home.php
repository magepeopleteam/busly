<?php
/**
 * The blog posts index (used when a static front page is set AND a
 * separate "Posts page" is assigned in Settings → Reading).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="busly-main">
	<div class="wrap busly-layout-<?php echo esc_attr( busly_get_option( 'blog_sidebar_position', 'right-sidebar' ) ); ?>">
	<div class="busly-content-grid">
		<div class="busly-content-col">
			<?php get_template_part( 'template-parts/content/page-header' ); ?>

			<?php if ( have_posts() ) : ?>
				<?php get_template_part( 'template-parts/content/blog-loop' ); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content/content-none' ); ?>
			<?php endif; ?>
		</div>

		<?php if ( 'no-sidebar' !== busly_get_option( 'blog_sidebar_position', 'right-sidebar' ) && is_active_sidebar( 'sidebar-blog' ) ) : ?>
			<aside class="busly-sidebar" aria-label="<?php esc_attr_e( 'Blog sidebar', 'busly' ); ?>">
				<?php dynamic_sidebar( 'sidebar-blog' ); ?>
			</aside>
		<?php endif; ?>
	</div>
	</div>
</main>

<?php
get_footer();
