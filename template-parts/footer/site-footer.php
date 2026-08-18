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

$busly_socials = array();
foreach ( busly_get_option( 'social_links', array() ) as $busly_social_item ) {
	if ( ! empty( $busly_social_item['icon'] ) && ! empty( $busly_social_item['url'] ) ) {
		$busly_socials[] = $busly_social_item;
	}
}

$busly_copyright = busly_get_option( 'footer_copyright', '' );
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
	// Strip any leftover curly braces and collapse multiple spaces.
	$busly_copyright = preg_replace( '/\{[^}]+\}/', ' ', $busly_copyright );
	$busly_copyright = preg_replace( '/\s+/', ' ', $busly_copyright );
	$busly_copyright = trim( $busly_copyright );
}

$busly_payment_badges = busly_get_option( 'footer_payment_badges', array() );

// Read show-payment-badges directly from DB — busly_get_option() treats
// empty-string (unchecked checkbox) as "not set" and returns the default.
$busly_options        = get_option( 'busly_theme_options', array() );
$busly_show_badges    = isset( $busly_options['footer_show_payment_badges'] )
	? $busly_options['footer_show_payment_badges']
	: 'yes';
?>
<footer class="ftr">
	<div class="wrap">
		<div class="ftr-grid">

			<div class="ftr-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
					<?php busly_the_logo(); ?>
				</a>
				<?php
				// busly_get_option()'s 2nd arg only wins when the key isn't a
				// registered Theme Settings field; 'footer_description' IS one
				// (with a blank schema default), so resolve the real fallback
				// chain explicitly here instead.
				$busly_footer_desc = busly_get_option( 'footer_description', '' );
				if ( ! $busly_footer_desc ) {
					$busly_footer_desc = get_bloginfo( 'description' )
						? get_bloginfo( 'description' )
						: __( 'The modern way to book bus travel. Comfortable journeys, simple booking, transparent pricing.', 'busly' );
				}
				?>
				<p><?php echo esc_html( $busly_footer_desc ); ?></p>
				<?php if ( ! empty( $busly_socials ) ) : ?>
					<div class="ftr-socials">
						<?php foreach ( $busly_socials as $busly_social_item ) : ?>
							<a href="<?php echo esc_url( $busly_social_item['url'] ); ?>" class="soc" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( str_replace( '-', ' ', $busly_social_item['icon'] ) ) ); ?>">
								<?php busly_icon( $busly_social_item['icon'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		<?php for ( $busly_i = 1; $busly_i <= 3; $busly_i++ ) : ?>
			<div class="ftr-col">
				<?php if ( is_active_sidebar( 'sidebar-footer-' . $busly_i ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-footer-' . $busly_i ); ?>
				<?php endif; ?>
			</div>
		<?php endfor; ?>

		</div>

		<div class="ftr-bot">
			<span class="ftr-copy"><?php echo wp_kses_post( $busly_copyright ); ?></span>

			<?php if ( 'yes' === $busly_show_badges && ! empty( $busly_payment_badges ) ) : ?>
				<div class="pay-badges">
					<?php foreach ( $busly_payment_badges as $busly_badge ) : ?>
						<?php if ( ! empty( $busly_badge['label'] ) ) : ?>
							<span class="pay"><?php echo esc_html( $busly_badge['label'] ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>
