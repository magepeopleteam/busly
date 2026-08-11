<?php
/**
 * The template for displaying the footer.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php do_action( 'busly_after_content' ); ?>

<?php do_action( 'busly_before_footer' ); ?>

<?php get_template_part( 'template-parts/footer/site-footer' ); ?>

<?php do_action( 'busly_after_footer' ); ?>

<button type="button" class="busly-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'busly' ); ?>">
	<?php busly_icon( 'arrow-right' ); ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
