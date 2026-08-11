<?php
/**
 * The template for displaying single blog posts.
 *
 * (Single `wbtm_bus` pages never reach this file — the plugin's own
 * `single_template` filter routes them to templates/single_page/single-bus.php,
 * see PHASE 1 audit / templates/README.md.)
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
			<?php busly_breadcrumbs(); ?>

			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content-single' );
			endwhile;
			?>

			<?php get_template_part( 'template-parts/content/related-posts' ); ?>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<div class="busly-comments">
					<?php comments_template(); ?>
				</div>
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
