<?php
/**
 * Related posts (same primary category), shown at the end of single.php
 * when Busly Theme Settings → Blog → "Show related posts" is enabled.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'yes' !== busly_get_option( 'blog_show_related', 'yes' ) ) {
	return;
}

$busly_cats = get_the_category();
if ( empty( $busly_cats ) ) {
	return;
}

$busly_related = new WP_Query(
	array(
		'category__in'        => wp_list_pluck( $busly_cats, 'term_id' ),
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $busly_related->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<div class="busly-related-posts">
	<h3 class="post-title" style="margin-bottom:20px;"><?php esc_html_e( 'You might also like', 'busly' ); ?></h3>
	<div class="busly-post-grid busly-post-grid--3">
		<?php
		while ( $busly_related->have_posts() ) :
			$busly_related->the_post();
			get_template_part( 'template-parts/content/entry-card' );
		endwhile;
		?>
	</div>
</div>
<?php
wp_reset_postdata();
