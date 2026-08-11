<?php
/**
 * Template Name: Full Width (No Sidebar)
 *
 * A plain full-width page template for content that isn't built with
 * Elementor but still shouldn't show the blog sidebar (e.g. a landing page
 * assembled from shortcodes/blocks). Elementor-built pages don't need this
 * template — page.php already detects and full-widths them automatically.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="busly-main">
	<div class="wrap busly-no-sidebar">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php get_template_part( 'template-parts/content/page-header' ); ?>
			<div class="busly-entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="busly-page-links">' . esc_html__( 'Pages:', 'busly' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
