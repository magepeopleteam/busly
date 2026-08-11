<?php
/**
 * The main template file — fallback for any request type not matched by a
 * more specific template in the hierarchy.
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
		<?php if ( have_posts() ) : ?>
			<?php get_template_part( 'template-parts/content/blog-loop' ); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/content-none' ); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
