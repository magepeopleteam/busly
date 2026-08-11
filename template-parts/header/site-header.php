<?php
/**
 * Site header — logo, primary nav, header buttons, mobile menu trigger.
 * Matches .hdr / .hdr-in / .logo / .nav / .hdr-btns in homepage.html.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$busly_urls        = busly_header_urls();
$busly_show_login  = 'yes' === busly_get_option( 'header_show_login', 'yes' );
$busly_show_book   = 'yes' === busly_get_option( 'header_show_bookings', 'yes' );
$busly_show_cta    = 'yes' === busly_get_option( 'header_show_cta', 'yes' );
$busly_cta_label   = busly_get_option( 'header_cta_label', __( 'Book Now', 'busly' ) );
?>
<header class="hdr" id="busly-header">
	<div class="hdr-in">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="logo-box"><?php busly_icon( 'bus' ); ?></span>
				<span class="logo-name"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => 'nav',
				'container_class' => 'nav',
				'container_aria_label' => esc_attr__( 'Primary navigation', 'busly' ),
				'menu_class'     => '',
				'depth'          => 3,
				'fallback_cb'    => 'busly_primary_menu_fallback',
			)
		);
		?>

		<div class="hdr-btns">
			<?php if ( $busly_show_login ) : ?>
				<a href="<?php echo esc_url( $busly_urls['login'] ); ?>" class="hbtn"><?php echo esc_html( $busly_urls['login_label'] ); ?></a>
			<?php endif; ?>

			<?php if ( $busly_show_book && $busly_urls['bookings'] ) : ?>
				<a href="<?php echo esc_url( $busly_urls['bookings'] ); ?>" class="hbtn hbtn-outline"><?php esc_html_e( 'My Bookings', 'busly' ); ?></a>
			<?php endif; ?>

			<?php if ( $busly_show_cta ) : ?>
				<a href="<?php echo esc_url( $busly_urls['cta'] ); ?>" class="hbtn hbtn-fill"><?php echo esc_html( $busly_cta_label ); ?></a>
			<?php endif; ?>
		</div>

		<button type="button" class="hdr-burger" aria-expanded="false" aria-controls="busly-mobile-panel" aria-label="<?php esc_attr_e( 'Open menu', 'busly' ); ?>">
			<?php busly_icon( 'menu' ); ?>
		</button>
	</div>
</header>

<?php get_template_part( 'template-parts/header/mobile-menu' ); ?>
