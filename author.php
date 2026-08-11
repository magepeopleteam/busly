<?php
/**
 * Author archive template — same loop as archive.php, plus an author bio
 * card up top (kept separate from archive.php per the brief's suggested
 * file list).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$busly_sidebar_pos = busly_get_option( 'blog_sidebar_position', 'right-sidebar' );
$busly_author_id   = (int) get_query_var( 'author' );
?>

<main id="primary" class="busly-main">
	<div class="wrap busly-layout-<?php echo esc_attr( $busly_sidebar_pos ); ?>">
	<div class="busly-content-grid">
		<div class="busly-content-col">
			<?php busly_breadcrumbs(); ?>

			<?php if ( $busly_author_id ) : ?>
				<div class="busly-author-box">
					<?php echo get_avatar( $busly_author_id, 64 ); ?>
					<div>
						<h1 class="busly-author-name" style="font-size:18px;"><?php echo esc_html( get_the_author_meta( 'display_name', $busly_author_id ) ); ?></h1>
						<div class="busly-author-bio"><?php echo esc_html( get_the_author_meta( 'description', $busly_author_id ) ); ?></div>
					</div>
				</div>
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
