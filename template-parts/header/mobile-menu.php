<?php
/**
 * Slide-in mobile navigation panel (behaviour in assets/js/navigation.js).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$busly_urls = busly_header_urls();
?>
<div class="busly-mobile-panel" id="busly-mobile-panel">
	<div class="busly-mobile-panel-head">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="logo-box" style="background:var(--busly-primary)"><?php busly_icon( 'bus' ); ?></span>
				<span class="logo-name" style="color:var(--busly-s900)"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>
		<button type="button" class="busly-mobile-panel-close" aria-label="<?php esc_attr_e( 'Close menu', 'busly' ); ?>">
			<?php busly_icon( 'close' ); ?>
		</button>
	</div>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => has_nav_menu( 'mobile' ) ? 'mobile' : 'primary',
			'container'      => 'nav',
			'container_class' => 'busly-mobile-nav',
			'menu_class'     => '',
			'depth'          => 3,
			'fallback_cb'    => 'busly_primary_menu_fallback',
		)
	);
	?>

	<div class="busly-mobile-btns">
		<a href="<?php echo esc_url( $busly_urls['login'] ); ?>" class="busly-btn busly-btn--outline"><?php echo esc_html( $busly_urls['login_label'] ); ?></a>
		<?php if ( $busly_urls['bookings'] ) : ?>
			<a href="<?php echo esc_url( $busly_urls['bookings'] ); ?>" class="busly-btn busly-btn--outline"><?php esc_html_e( 'My Bookings', 'busly' ); ?></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( $busly_urls['cta'] ); ?>" class="busly-btn busly-btn--primary"><?php echo esc_html( busly_get_option( 'header_cta_label', __( 'Book Now', 'busly' ) ) ); ?></a>
	</div>
</div>
