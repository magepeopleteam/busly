<?php
/**
 * Busly theme bootstrap.
 *
 * This file only defines constants and requires the files under inc/.
 * All real logic lives in inc/ so the theme stays maintainable and
 * child-theme friendly (nothing below should ever need overriding).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/** Theme version — bump on every release, used for cache-busting enqueues. */
define( 'BUSLY_VERSION', '1.0.15' );

/** Absolute filesystem path to the theme root, no trailing slash. */
define( 'BUSLY_DIR', get_template_directory() );

/** Absolute URL to the theme root, no trailing slash. */
define( 'BUSLY_URI', get_template_directory_uri() );

/** Minimum PHP version the theme actively supports. */
define( 'BUSLY_MIN_PHP', '8.0' );

/**
 * Require a theme file relative to BUSLY_DIR and warn (once) if it is missing,
 * instead of a fatal error. Keeps activation safe even if a file is removed.
 *
 * @param string $relative_path Path relative to the theme root, e.g. 'inc/setup/theme-setup.php'.
 */
function busly_require( $relative_path ) {
	$path = BUSLY_DIR . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $path ) ) {
		require_once $path;
		return;
	}

	add_action(
		'admin_notices',
		function () use ( $relative_path ) {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html(
					sprintf(
						/* translators: %s: relative file path */
						__( 'Busly theme file missing: %s. Please reinstall the theme.', 'busly' ),
						$relative_path
					)
				)
			);
		}
	);
}

/*
 * ---------------------------------------------------------------------
 * Core setup (theme supports, menus, sidebars, assets, template hooks).
 * ---------------------------------------------------------------------
 */
busly_require( 'inc/setup/theme-setup.php' );
busly_require( 'inc/setup/menus.php' );
busly_require( 'inc/setup/sidebars.php' );
busly_require( 'inc/setup/enqueue.php' );
busly_require( 'inc/setup/fonts.php' );

/*
 * ---------------------------------------------------------------------
 * Reusable helpers, template functions/hooks, and CSS-variable output.
 * ---------------------------------------------------------------------
 */
busly_require( 'inc/helpers/general.php' );
busly_require( 'inc/helpers/svg-icons.php' );
busly_require( 'inc/template-functions.php' );
busly_require( 'inc/template-hooks.php' );
busly_require( 'inc/compatibility.php' );

/*
 * ---------------------------------------------------------------------
 * Admin: Theme Settings screen, dashboard, requirements, setup wizard.
 * ---------------------------------------------------------------------
 */
if ( is_admin() ) {
	busly_require( 'inc/admin/class-requirements.php' );
	busly_require( 'inc/admin/class-dashboard.php' );
	busly_require( 'inc/admin/class-setup-wizard.php' );
	busly_require( 'inc/admin/class-demo-import.php' );
}
busly_require( 'inc/theme-options.php' );
busly_require( 'inc/customizer/customizer.php' );

/*
 * ---------------------------------------------------------------------
 * Third-party integrations. Every file below must degrade gracefully
 * when the corresponding plugin is not installed/active.
 * ---------------------------------------------------------------------
 */
busly_require( 'inc/integrations/bus-booking.php' );
busly_require( 'inc/integrations/woocommerce.php' );
busly_require( 'inc/integrations/elementor.php' );
busly_require( 'inc/integrations/seo.php' );
busly_require( 'inc/integrations/forms.php' );
