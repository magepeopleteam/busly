<?php
/**
 * Post card used in the blog grid/list loop.
 * Matches .busly-post-card / .post-title / .post-meta in blog.css.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'busly-post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-thumb" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'medium_large', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</a>
	<?php endif; ?>

	<div class="post-body">
		<?php
		$busly_categories = get_the_category();
		if ( ! empty( $busly_categories ) ) :
			?>
			<a href="<?php echo esc_url( get_category_link( $busly_categories[0]->term_id ) ); ?>" class="post-cat"><?php echo esc_html( $busly_categories[0]->name ); ?></a>
		<?php endif; ?>

		<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<div class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), busly_get_option( 'blog_excerpt_length', 22 ) ) ); ?></div>

		<div class="post-meta">
			<span>
				<?php busly_icon( 'user' ); ?>
				<?php the_author(); ?>
			</span>
			<span>
				<?php busly_icon( 'calendar' ); ?>
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</span>
			<?php if ( 'yes' === busly_get_option( 'blog_show_reading_time', 'yes' ) ) : ?>
				<span>
					<?php busly_icon( 'clock' ); ?>
					<?php
					printf(
						/* translators: %d: reading time in minutes */
						esc_html__( '%d min read', 'busly' ),
						(int) busly_reading_time( get_the_ID() )
					);
					?>
				</span>
			<?php endif; ?>
		</div>

		<a href="<?php the_permalink(); ?>" class="post-readmore">
			<?php esc_html_e( 'Read more', 'busly' ); ?>
			<?php busly_icon( 'chevron-right' ); ?>
		</a>
	</div>
</article>
