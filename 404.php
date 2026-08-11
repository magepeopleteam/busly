<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="busly-main">
	<div class="wrap">
		<div class="busly-404">
			<div class="busly-404-code">404</div>
			<h1 class="busly-404-title"><?php esc_html_e( 'This route doesn\'t exist', 'busly' ); ?></h1>
			<p class="busly-404-text"><?php esc_html_e( 'The page you\'re looking for may have been moved or no longer exists. Let\'s get you back on track.', 'busly' ); ?></p>

			<div style="max-width:480px;margin:0 auto 40px">
				<?php get_search_form(); ?>
			</div>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="busly-btn busly-btn--primary">
				<?php busly_icon( 'arrow-right' ); ?>
				<?php esc_html_e( 'Back to Homepage', 'busly' ); ?>
			</a>
		</div>
	</div>
</main>

<?php
get_footer();
