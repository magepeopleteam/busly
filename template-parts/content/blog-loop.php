<?php
/**
 * Shared blog loop — used by home.php, front-page.php (posts mode),
 * archive.php and search.php. Expects the global query to already be
 * positioned (standard Loop) and to have posts (caller checks have_posts()).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$busly_layout     = busly_get_option( 'blog_layout', 'grid' );
$busly_grid_class = 'list' === $busly_layout ? 'busly-post-list' : 'busly-post-grid busly-post-grid--' . ( 'grid-3' === $busly_layout ? '3' : '2' );
?>
<div class="<?php echo esc_attr( $busly_grid_class ); ?>">
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content/entry-card' );
	endwhile;
	?>
</div>

<?php busly_pagination(); ?>
