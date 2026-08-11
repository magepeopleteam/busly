<?php
/**
 * Site footer — brand column + 3 link/widget columns + bottom bar.
 * Matches .ftr / .ftr-grid / .ftr-bot in homepage.html.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$busly_socials = array(
	'facebook'  => get_theme_mod( 'busly_social_facebook', '' ),
	'x-twitter' => get_theme_mod( 'busly_social_x', '' ),
	'instagram' => get_theme_mod( 'busly_social_instagram', '' ),
	'youtube'   => get_theme_mod( 'busly_social_youtube', '' ),
	'linkedin'  => get_theme_mod( 'busly_social_linkedin', '' ),
);
$busly_socials = array_filter( $busly_socials );

$busly_copyright = get_theme_mod( 'busly_footer_copyright', '' );
if ( ! $busly_copyright ) {
	$busly_copyright = sprintf(
		/* translators: 1: current year, 2: site name */
		__( '© %1$s %2$s. All rights reserved.', 'busly' ),
		gmdate( 'Y' ),
		get_bloginfo( 'name' )
	);
} else {
	$busly_copyright = str_replace( '{year}', gmdate( 'Y' ), $busly_copyright );
	$busly_copyright = str_replace( '{site}', get_bloginfo( 'name' ), $busly_copyright );
}
?>
<footer class="ftr">
	<div class="wrap">
		<div class="ftr-grid">

			<div class="ftr-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="logo-box"><?php busly_icon( 'bus' ); ?></span>
						<span class="logo-name"><?php bloginfo( 'name' ); ?></span>
					<?php endif; ?>
				</a>
				<p><?php echo esc_html( busly_get_option( 'footer_description', get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'The modern way to book bus travel. Comfortable journeys, simple booking, transparent pricing.', 'busly' ) ) ); ?></p>
				<?php if ( ! empty( $busly_socials ) ) : ?>
					<div class="ftr-socials">
						<?php foreach ( $busly_socials as $busly_icon_name => $busly_url ) : ?>
							<a href="<?php echo esc_url( $busly_url ); ?>" class="soc" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( str_replace( '-', ' ', $busly_icon_name ) ) ); ?>">
								<?php busly_icon( $busly_icon_name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php for ( $busly_i = 1; $busly_i <= 3; $busly_i++ ) : ?>
				<div class="ftr-col">
					<?php if ( is_active_sidebar( 'sidebar-footer-' . $busly_i ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-footer-' . $busly_i ); ?>
					<?php elseif ( has_nav_menu( 'footer-' . $busly_i ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer-' . $busly_i,
								'container'      => false,
								'menu_class'     => '',
								'depth'          => 1,
								'items_wrap'     => '<h4>' . esc_html( wp_get_nav_menu_name( 'footer-' . $busly_i ) ) . '</h4><ul>%3$s</ul>',
							)
						);
						?>
					<?php endif; ?>
				</div>
			<?php endfor; ?>

		</div>

		<div class="ftr-bot">
			<span class="ftr-copy"><?php echo esc_html( $busly_copyright ); ?></span>

			<?php if ( 'yes' === busly_get_option( 'footer_show_payment_badges', 'yes' ) ) : ?>
				<div class="pay-badges">
					<span class="pay">Visa</span><span class="pay">Mastercard</span><span class="pay">PayPal</span><span class="pay">Stripe</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>
