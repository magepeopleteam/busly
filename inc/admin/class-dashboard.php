<?php
/**
 * Busly Dashboard — top-level admin menu, requirements summary, quick links.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Dashboard
 */
class Busly_Dashboard {

	/**
	 * Bootstrap hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
	}

	/**
	 * Register the top-level "Busly" admin menu + its Dashboard page.
	 * (Theme Settings, Setup Wizard add their own submenus elsewhere.)
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'Busly', 'busly' ),
			__( 'Busly', 'busly' ),
			'edit_theme_options',
			'busly',
			array( __CLASS__, 'render' ),
			'dashicons-palmtree',
			61
		);

		add_submenu_page(
			'busly',
			__( 'Dashboard', 'busly' ),
			__( 'Dashboard', 'busly' ),
			'edit_theme_options',
			'busly',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Render the Dashboard screen.
	 */
	public static function render() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		?>
		<div class="wrap busly-admin">
			<div class="busly-admin-header">
				<h1><span class="busly-admin-logo"><?php busly_icon( 'bus' ); ?></span> <?php esc_html_e( 'Busly Dashboard', 'busly' ); ?></h1>
				<span class="busly-admin-version"><?php echo esc_html( 'v' . BUSLY_VERSION ); ?></span>
			</div>

			<div class="busly-admin-grid">
				<div>
					<div class="busly-admin-card">
						<h2><?php esc_html_e( 'Required & Recommended Plugins', 'busly' ); ?></h2>
						<?php Busly_Requirements::render_table(); ?>
					</div>

					<div class="busly-admin-card">
						<h2><?php esc_html_e( 'System Status', 'busly' ); ?></h2>
						<div class="busly-req-row">
							<div class="busly-req-name"><?php esc_html_e( 'PHP Version', 'busly' ); ?></div>
							<span class="busly-req-status <?php echo version_compare( PHP_VERSION, BUSLY_MIN_PHP, '>=' ) ? 'is-active' : 'is-missing'; ?>"><?php echo esc_html( PHP_VERSION ); ?></span>
						</div>
						<div class="busly-req-row">
							<div class="busly-req-name"><?php esc_html_e( 'WordPress Version', 'busly' ); ?></div>
							<span class="busly-req-status is-active"><?php echo esc_html( get_bloginfo( 'version' ) ); ?></span>
						</div>
						<div class="busly-req-row">
							<div class="busly-req-name"><?php esc_html_e( 'Active Theme', 'busly' ); ?></div>
							<span class="busly-req-status is-active"><?php echo esc_html( wp_get_theme()->get( 'Name' ) . ' ' . BUSLY_VERSION ); ?></span>
						</div>
						<div class="busly-req-row">
							<div class="busly-req-name"><?php esc_html_e( 'Homepage Set', 'busly' ); ?></div>
							<?php $busly_front_ok = ( 'page' === get_option( 'show_on_front' ) && get_option( 'page_on_front' ) ); ?>
							<span class="busly-req-status <?php echo $busly_front_ok ? 'is-active' : 'is-inactive'; ?>"><?php echo $busly_front_ok ? esc_html__( 'Yes', 'busly' ) : esc_html__( 'Not yet', 'busly' ); ?></span>
						</div>
					</div>
				</div>

				<div>
					<div class="busly-admin-card">
						<h2><?php esc_html_e( 'Quick Links', 'busly' ); ?></h2>
						<div class="busly-quicklinks">
							<a href="<?php echo esc_url( admin_url( 'themes.php?page=busly-setup-wizard' ) ); ?>"><?php esc_html_e( 'Setup Wizard', 'busly' ); ?> →</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=busly-theme-settings' ) ); ?>"><?php esc_html_e( 'Theme Settings', 'busly' ); ?> →</a>
							<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Website', 'busly' ); ?> →</a>
							<?php if ( post_type_exists( 'wbtm_bus' ) ) : ?>
								<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wbtm_bus' ) ); ?>"><?php esc_html_e( 'Manage Buses', 'busly' ); ?> →</a>
							<?php endif; ?>
							<?php if ( did_action( 'elementor/loaded' ) && get_option( 'page_on_front' ) ) : ?>
								<a href="<?php echo esc_url( admin_url( 'post.php?post=' . (int) get_option( 'page_on_front' ) . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit Homepage', 'busly' ); ?> →</a>
							<?php endif; ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Website', 'busly' ); ?> ↗</a>
						</div>
					</div>

					<div class="busly-admin-card">
						<h2><?php esc_html_e( 'Documentation & Support', 'busly' ); ?></h2>
						<div class="busly-quicklinks">
							<a href="<?php echo esc_url( BUSLY_URI . '/documentation/index.html' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'busly' ); ?> ↗</a>
							<a href="https://magepeople.com/support" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'busly' ); ?> ↗</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

Busly_Dashboard::init();
