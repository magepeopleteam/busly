<?php
/**
 * Single post content (title, meta, thumbnail, body, tags, author box, nav).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="busly-single-header">
		<?php
		$busly_categories = get_the_category();
		if ( ! empty( $busly_categories ) ) :
			?>
			<a href="<?php echo esc_url( get_category_link( $busly_categories[0]->term_id ) ); ?>" class="post-cat"><?php echo esc_html( $busly_categories[0]->name ); ?></a>
		<?php endif; ?>

		<h1 class="busly-page-title"><?php the_title(); ?></h1>

		<div class="post-meta">
			<span><?php busly_icon( 'user' ); ?> <?php the_author(); ?></span>
			<span><?php busly_icon( 'calendar' ); ?> <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
			<?php if ( 'yes' === busly_get_option( 'blog_show_reading_time', 'yes' ) ) : ?>
				<span><?php busly_icon( 'clock' ); ?> <?php printf( esc_html__( '%d min read', 'busly' ), (int) busly_reading_time( get_the_ID() ) ); ?></span>
			<?php endif; ?>
			<?php if ( 'yes' === busly_get_option( 'blog_show_comment_count', 'yes' ) && ( comments_open() || get_comments_number() ) ) : ?>
				<span>💬 <?php comments_number( esc_html__( '0 Comments', 'busly' ), esc_html__( '1 Comment', 'busly' ), esc_html__( '% Comments', 'busly' ) ); ?></span>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="busly-single-thumb">
			<?php the_post_thumbnail( 'large', array( 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
		</div>
	<?php endif; ?>

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
	$busly_tags = get_the_tags();
	if ( $busly_tags ) :
		?>
		<div class="busly-entry-tags">
			<?php foreach ( $busly_tags as $busly_tag ) : ?>
				<a href="<?php echo esc_url( get_tag_link( $busly_tag->term_id ) ); ?>">#<?php echo esc_html( $busly_tag->name ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( 'yes' === busly_get_option( 'blog_show_author_box', 'yes' ) ) : ?>
		<div class="busly-author-box">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 56 ); ?>
			<div>
				<div class="busly-author-name"><?php the_author(); ?></div>
				<div class="busly-author-bio"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></div>
			</div>
		</div>
	<?php endif; ?>

	<nav class="busly-post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'busly' ); ?>">
		<?php
		$busly_prev = get_previous_post_link( '%link', '&larr; %title' );
		$busly_next = get_next_post_link( '%link', '%title &rarr;' );
		?>
		<div class="nav-previous"><?php echo $busly_prev ? wp_kses_post( $busly_prev ) : ''; ?></div>
		<div class="nav-next"><?php echo $busly_next ? wp_kses_post( $busly_next ) : ''; ?></div>
	</nav>

</article>
