<?php
/**
 * The template for displaying all static pages.
 *
 * Pages built with Elementor render 100% of their own layout (Elementor
 * hooks into the_content() itself) — this template just needs to call
 * the_content() inside the Loop and stay out of the way; see
 * busly_is_elementor_built() usage in page-header.php.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$busly_elementor_built = busly_is_elementor_built();
?>

<main id="primary" class="busly-main<?php echo $busly_elementor_built ? ' busly-elementor-canvas' : ''; ?>">
	<?php if ( $busly_elementor_built ) : ?>

		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>

	<?php else : ?>

		<div class="wrap busly-no-sidebar">
		<div class="busly-content-grid">
			<div class="busly-content-col">
				<?php get_template_part( 'template-parts/content/page-header' ); ?>

				<?php
				while ( have_posts() ) :
					the_post();
					?>
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
					if ( comments_open() || get_comments_number() ) :
						?>
						<div class="busly-comments">
							<?php comments_template(); ?>
						</div>
						<?php
					endif;
				endwhile;
				?>
			</div>
		</div>
		</div>

	<?php endif; ?>
</main>

<?php
get_footer();
