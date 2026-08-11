<?php
/**
 * The template for displaying comments.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<?php if ( have_comments() ) : ?>
	<h2 class="busly-comments-title">
		<?php
		$busly_count = get_comments_number();
		printf(
			/* translators: %s: number of comments */
			esc_html( _n( '%s Comment', '%s Comments', $busly_count, 'busly' ) ),
			esc_html( number_format_i18n( $busly_count ) )
		);
		?>
	</h2>

	<ol class="comment-list">
		<?php
		wp_list_comments(
			array(
				'style'      => 'ol',
				'short_ping' => true,
				'callback'   => 'busly_comment_template',
			)
		);
		?>
	</ol>

	<?php the_comments_pagination( array( 'prev_text' => __( '&larr; Older', 'busly' ), 'next_text' => __( 'Newer &rarr;', 'busly' ) ) ); ?>

<?php endif; ?>

<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="busly-notice busly-notice--info"><?php esc_html_e( 'Comments are closed.', 'busly' ); ?></p>
<?php endif; ?>

<?php
comment_form(
	array(
		'class_submit' => 'busly-btn busly-btn--primary',
		'title_reply'  => __( 'Leave a Comment', 'busly' ),
	)
);
