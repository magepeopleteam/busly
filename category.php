<?php
/**
 * Category archive template — same loop as archive.php, plus the category
 * description card (kept separate from archive.php per the brief's
 * suggested file list, and because a category archive benefits from its
 * own description block that a generic archive shouldn't always show).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$busly_sidebar_pos = busly_get_option( 'blog_sidebar_position', 'right-sidebar' );
$busly_description = category_description();
?>

<main id="primary" class="busly-main">
	<div class="wrap busly-layout-<?php echo esc_attr( $busly_sidebar_pos ); ?>">
	<div class="busly-content-grid">
		<div class="busly-content-col">
			<?php get_template_part( 'template-parts/content/page-header' ); ?>

			<?php if ( $busly_description ) : ?>
				<div class="busly-notice busly-notice--info"><?php echo wp_kses_post( $busly_description ); ?></div>
			<?php endif; ?>

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
