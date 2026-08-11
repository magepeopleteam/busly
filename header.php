<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up through the site
 * header markup (delegated to template-parts/header/site-header.php).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'busly' ); ?></a>

<?php do_action( 'busly_before_header' ); ?>

<?php get_template_part( 'template-parts/header/site-header' ); ?>

<?php do_action( 'busly_after_header' ); ?>

<?php do_action( 'busly_before_content' ); ?>
