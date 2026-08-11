<?php
/**
 * The front page template.
 *
 * Per the theme brief: the homepage is a REAL WordPress Page, 100%
 * Elementor-editable section by section — never markup hard-coded here.
 * This file only decides which of three states applies and stays out of
 * Elementor's way in the (normal, post-Setup-Wizard) first case:
 *
 *   1. A static front page is set AND it's built with Elementor  → just
 *      call the_content(); Elementor's own `the_content` filter renders
 *      every section (Hero, Bus Search, Featured Routes, …).
 *   2. A static front page is set but has plain/no content yet (before
 *      Setup Wizard's demo import has run) → fall back to normal page
 *      output so the site never shows a blank screen.
 *   3. Settings → Reading is "Your latest posts" → show the blog loop.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$busly_show_posts_on_front = ( 'posts' === get_option( 'show_on_front' ) );
?>

<main id="primary" class="busly-main<?php echo ( ! $busly_show_posts_on_front && busly_is_elementor_built() ) ? ' busly-elementor-canvas' : ''; ?>">

	<?php if ( $busly_show_posts_on_front ) : ?>

		<div class="wrap busly-layout-<?php echo esc_attr( busly_get_option( 'blog_sidebar_position', 'right-sidebar' ) ); ?>">
		<div class="busly-content-grid">
			<div class="busly-content-col">
				<?php if ( have_posts() ) : ?>
					<?php get_template_part( 'template-parts/content/blog-loop' ); ?>
				<?php else : ?>
					<?php get_template_part( 'template-parts/content/content-none' ); ?>
				<?php endif; ?>
			</div>
			<?php if ( 'no-sidebar' !== busly_get_option( 'blog_sidebar_position', 'right-sidebar' ) && is_active_sidebar( 'sidebar-blog' ) ) : ?>
				<aside class="busly-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'busly' ); ?>">
					<?php dynamic_sidebar( 'sidebar-blog' ); ?>
				</aside>
			<?php endif; ?>
		</div>
		</div>

	<?php elseif ( busly_is_elementor_built() ) : ?>

		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>

	<?php else : ?>

		<div class="wrap busly-no-sidebar">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<div class="busly-page-header">
						<h1 class="busly-page-title"><?php the_title(); ?></h1>
					</div>
					<div class="busly-entry-content"><?php the_content(); ?></div>
					<?php
				endwhile;
				?>
			<?php elseif ( current_user_can( 'edit_theme_options' ) ) : ?>
				<div class="busly-notice busly-notice--info">
					<p>
						<?php esc_html_e( 'Your homepage is empty. Run the Setup Wizard to import the demo homepage, or start building it in Elementor.', 'busly' ); ?>
						<a href="<?php echo esc_url( admin_url( 'themes.php?page=busly-setup-wizard' ) ); ?>"><?php esc_html_e( 'Open Setup Wizard →', 'busly' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
		</div>

	<?php endif; ?>

</main>

<?php
get_footer();
