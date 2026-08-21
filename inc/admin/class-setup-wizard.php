<?php
/**
 * Busly → Setup Wizard: Welcome → Required Plugins → Demo Import →
 * Homepage → Finish. Steps are plain GET navigation (works without JS);
 * only the "Import Demo Content" button needs AJAX (assets/js/setup-wizard.js).
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Setup_Wizard
 */
class Busly_Setup_Wizard {

	/**
	 * Ordered step keys.
	 *
	 * @var string[]
	 */
	private static $steps = array( 'welcome', 'requirements', 'import', 'homepage', 'finish' );

	/**
	 * Option name for the one-shot "has the post-activation redirect to
	 * the wizard already fired" flag. See maybe_redirect_after_activation().
	 *
	 * @var string
	 */
	const REDIRECT_FLAG = 'busly_setup_wizard_redirected';

	/**
	 * Bootstrap hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_page' ) );
		add_action( 'after_switch_theme', array( __CLASS__, 'clear_redirect_flag' ) );
		// Priority 1: run before any later-priority admin_init callback
		// from another plugin/theme has a chance to echo anything first —
		// see the headers_sent() fallback in maybe_redirect_after_activation()
		// for what happens on hosts where something still beats this to it.
		add_action( 'admin_init', array( __CLASS__, 'maybe_redirect_after_activation' ), 1 );
	}

	/**
	 * Register the wizard page under BOTH the top-level "Busly" menu
	 * (alongside Dashboard and Theme Settings, where the rest of this
	 * theme's own admin lives) and under Appearance via add_theme_page().
	 * Both point at the exact same `page=busly-setup-wizard` slug/callback
	 * — add_theme_page() is a thin wrapper around
	 * add_submenu_page( 'themes.php', ... ), so this doesn't change the
	 * URL anything elsewhere in the theme already links to.
	 *
	 * The Appearance registration isn't just an extra place to find it —
	 * it's what makes the "add a Setup Wizard button to the theme details
	 * page" half of this work at all. wp-admin/themes.php builds the
	 * button row on a theme's Details overlay ($current_theme_actions,
	 * around line 358) by walking $submenu['themes.php'] for the CURRENTLY
	 * ACTIVE theme and turning every page registered there into a button
	 * automatically — the overlay's own JS template (#tmpl-theme-single)
	 * has no filter or dynamic hook of its own for adding arbitrary extra
	 * buttons; this Appearance-menu walk is the actual, only supported
	 * mechanism core provides for it.
	 */
	public static function register_page() {
		add_submenu_page(
			'busly',
			__( 'Busly Setup Wizard', 'busly' ),
			__( 'Setup Wizard', 'busly' ),
			'edit_theme_options',
			'busly-setup-wizard',
			array( __CLASS__, 'render' )
		);

		add_theme_page(
			__( 'Busly Setup Wizard', 'busly' ),
			__( 'Setup Wizard', 'busly' ),
			'edit_theme_options',
			'busly-setup-wizard',
			array( __CLASS__, 'render' )
		);
	}

	/**
	 * Registered from Busly's OWN functions.php, so — importantly — this
	 * can only actually run for a switch that happens while Busly is
	 * ALREADY the theme WordPress loaded for this request (e.g. running
	 * `wp theme activate busly` again while it's already active, or any
	 * other no-op re-switch; WordPress fires this action even then).
	 * Switching INTO Busly FROM a different theme fires this exact same
	 * action one request too early for Busly's own code to be listening
	 * yet — wp-settings.php loads a theme's functions.php once, near the
	 * very start of the request, based on whichever theme was active
	 * when that request BEGAN; switch_theme() (called later, from
	 * themes.php's own activation handler) updates the theme options
	 * but can't retroactively load Busly's code into a request that
	 * already started as the OLD theme. That first-activation case is
	 * instead caught by maybe_redirect_after_activation() below, on the
	 * very next admin page load — which, since the DB options are already
	 * updated by then, is the first request that actually boots as Busly.
	 * This hook exists only for the other, secondary case: if Busly is
	 * switched away from and later reactivated, the flag that method sets
	 * would otherwise still say "already redirected" from the ORIGINAL
	 * activation, silently skipping the wizard on this later one.
	 */
	public static function clear_redirect_flag() {
		delete_option( self::REDIRECT_FLAG );
	}

	/**
	 * Runs on every single admin_init, but only ever acts once per
	 * activation: the option it checks is absent only (a) right after
	 * Busly is activated for the first time on a given site — nothing
	 * has set it yet — or (b) after clear_redirect_flag() above resets it
	 * for a later reactivation. Every other admin_init call, on any other
	 * page, on any other day, finds the option already set and returns
	 * immediately on the very first line; this is not a per-page-load
	 * redirect, it fires exactly once per activation.
	 */
	public static function maybe_redirect_after_activation() {
		if ( get_option( self::REDIRECT_FLAG ) ) {
			return;
		}

		// Never for anyone who couldn't reach the wizard anyway, and never
		// during AJAX/cron/network-admin contexts, where a redirect would
		// either error out or silently do nothing useful.
		if ( wp_doing_ajax() || wp_doing_cron() || is_network_admin() || ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		// The Customizer's own "Live Preview" of a theme temporarily makes
		// that theme's code run for the preview request WITHOUT actually
		// switching to it (no switch_theme() call, no persisted option
		// change, until "Activate & Publish" is clicked) — so this flag
		// being unset here does NOT mean Busly was just activated. Without
		// this check, merely previewing Busly would have yanked the admin
		// straight out of the Customizer and into the wizard.
		if ( isset( $GLOBALS['pagenow'] ) && 'customize.php' === $GLOBALS['pagenow'] ) {
			return;
		}

		// Already there (or navigating within it) — nothing to redirect
		// to, and redirecting FROM here would just loop. Still consumes
		// the flag, so this doesn't keep re-checking on every step change.
		if ( isset( $_GET['page'] ) && 'busly-setup-wizard' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only, no state change.
			update_option( self::REDIRECT_FLAG, 1 );
			return;
		}

		$url = admin_url( 'admin.php?page=busly-setup-wizard' );
		update_option( self::REDIRECT_FLAG, 1 );

		// Priority 1 (see init()) makes this the first admin_init callback
		// to run on a normal WordPress setup, but it can't force that on
		// every host — some hosts' MU-plugins/object-cache drop-ins/output
		// buffering handlers hook in even earlier than admin_init (plugins_loaded,
		// or a wrapper around the whole request) and print something of
		// their own first. When that's already happened, wp_safe_redirect()'s
		// header() call cannot work any more — PHP only warns rather than
		// throwing, so without this check the page would carry on rendering
		// underneath a visible "Cannot modify header information" notice
		// and never actually redirect at all, silently failing at the one
		// thing this method exists to do. A tiny inline script tag still
		// works even once headers are committed, since it's body content,
		// not a header, so use that as a same-effect fallback instead of
		// letting wp_safe_redirect() fail loudly for nothing.
		if ( headers_sent() ) {
			printf( '<script>window.location.replace(%s);</script>', wp_json_encode( $url ) );
			return;
		}

		wp_safe_redirect( $url );
		exit;
	}

	/**
	 * Current step key, defaulting to the first.
	 *
	 * @return string
	 */
	private static function current_step() {
		$step = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : 'welcome'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only navigation, no state change.
		return in_array( $step, self::$steps, true ) ? $step : 'welcome';
	}

	/**
	 * Build the URL for a given step.
	 *
	 * @param string $step Step key.
	 * @return string
	 */
	private static function step_url( $step ) {
		return add_query_arg(
			array(
				'page' => 'busly-setup-wizard',
				'step' => $step,
			),
			admin_url( 'admin.php' )
		);
	}

	/**
	 * Render the wizard shell + current step.
	 */
	public static function render() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$current = self::current_step();
		$index   = array_search( $current, self::$steps, true );
		?>
		<div class="wrap busly-admin">
			<div class="busly-wizard">
				<div class="busly-wizard-steps">
					<?php foreach ( self::$steps as $i => $step ) : ?>
						<span class="busly-wizard-step-dot <?php echo $i <= $index ? 'is-done' : ''; ?> <?php echo $i === $index ? 'is-active' : ''; ?>"></span>
					<?php endforeach; ?>
				</div>

				<?php
				$method = 'render_step_' . str_replace( '-', '_', $current );
				if ( method_exists( __CLASS__, $method ) ) {
					self::$method();
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Step 1 — Welcome.
	 */
	private static function render_step_welcome() {
		?>
		<h1><?php esc_html_e( 'Welcome to Busly', 'busly' ); ?></h1>
		<p class="busly-wizard-lead">
			<?php esc_html_e( 'Busly is a premium bus ticket booking & transportation theme. This quick wizard checks your plugins, imports demo content, and sets up your homepage in a few minutes.', 'busly' ); ?>
		</p>
		<div class="busly-wizard-actions" style="justify-content:center;">
			<a href="<?php echo esc_url( self::step_url( 'requirements' ) ); ?>" class="busly-btn-admin"><?php esc_html_e( 'Get Started →', 'busly' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Step 2 — Required plugins.
	 */
	private static function render_step_requirements() {
		if ( Busly_Requirements::all_required_active() ) {
			wp_safe_redirect( self::step_url( 'import' ) );
			exit;
		}
		?>
		<h1><?php esc_html_e( 'Required Plugins', 'busly' ); ?></h1>
		<p class="busly-wizard-lead"><?php esc_html_e( 'Busly needs these plugins for booking and page-building to work.', 'busly' ); ?></p>

		<div class="busly-admin-card" style="box-shadow:none;">
			<?php Busly_Requirements::render_table(); ?>
		</div>

		<p class="busly-field-desc"><?php esc_html_e( 'You can continue without them, but demo import and booking widgets will show a notice until they\'re active.', 'busly' ); ?></p>

		<div class="busly-wizard-actions">
			<a href="<?php echo esc_url( self::step_url( 'welcome' ) ); ?>" class="busly-btn-admin secondary"><?php esc_html_e( '← Back', 'busly' ); ?></a>
			<a href="<?php echo esc_url( self::step_url( 'import' ) ); ?>" class="busly-btn-admin"><?php esc_html_e( 'Continue →', 'busly' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Step 3 — Demo import.
	 */
	private static function render_step_import() {
		$already_imported = get_option( 'busly_demo_imported' );
		?>
		<h1><?php esc_html_e( 'Demo Import', 'busly' ); ?></h1>
		<p class="busly-wizard-lead"><?php esc_html_e( 'Creates sample pages, a navigation menu, and a fully Elementor-editable homepage. Existing content is never deleted or overwritten.', 'busly' ); ?></p>

		<?php if ( $already_imported ) : ?>
			<div class="busly-notice busly-notice--info"><?php esc_html_e( 'Theme demo content was already imported. Running it again will only add anything missing — it will not duplicate pages.', 'busly' ); ?></div>
		<?php else : ?>
			<div class="busly-notice busly-notice--info"><?php esc_html_e( 'This will create theme pages (Home, About, Contact, FAQ, etc.), navigation menus, and set up the Elementor homepage. Bus plugin demo data is managed separately.', 'busly' ); ?></div>
		<?php endif; ?>

		<div style="text-align:center;">
			<button type="button" id="busly-run-import" class="busly-btn-admin" data-confirm-needed="1">
				<?php echo $already_imported ? esc_html__( 'Re-run Demo Import', 'busly' ) : esc_html__( 'Import Demo Content', 'busly' ); ?>
			</button>
		</div>

		<div class="busly-wizard-progress"><div class="busly-wizard-progress-bar"></div></div>
		<div class="busly-wizard-log"></div>

		<div class="busly-wizard-finish-links" style="display:none;">
			<a href="<?php echo esc_url( self::step_url( 'homepage' ) ); ?>" class="busly-btn-admin" style="text-align:center;"><?php esc_html_e( 'Continue →', 'busly' ); ?></a>
		</div>

		<div class="busly-wizard-actions">
			<a href="<?php echo esc_url( self::step_url( 'requirements' ) ); ?>" class="busly-btn-admin secondary"><?php esc_html_e( '← Back', 'busly' ); ?></a>
			<a href="<?php echo esc_url( self::step_url( 'homepage' ) ); ?>" class="busly-btn-admin secondary"><?php esc_html_e( 'Skip →', 'busly' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Step 4 — Homepage confirmation.
	 */
	private static function render_step_homepage() {
		$front_id = (int) get_option( 'page_on_front' );
		$is_set   = ( 'page' === get_option( 'show_on_front' ) && $front_id );
		?>
		<h1><?php esc_html_e( 'Homepage', 'busly' ); ?></h1>

		<?php if ( $is_set ) : ?>
			<div class="busly-notice busly-notice--info">
				<?php
				printf(
					/* translators: %s: page title */
					esc_html__( '"%s" is set as your static homepage.', 'busly' ),
					esc_html( get_the_title( $front_id ) )
				);
				?>
			</div>
			<div class="busly-wizard-finish-links">
				<a href="<?php echo esc_url( get_permalink( $front_id ) ); ?>" target="_blank" rel="noopener noreferrer" class="busly-btn-admin secondary" style="text-align:center;"><?php esc_html_e( 'Preview', 'busly' ); ?></a>
				<?php if ( did_action( 'elementor/loaded' ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $front_id . '&action=elementor' ) ); ?>" class="busly-btn-admin" style="text-align:center;"><?php esc_html_e( 'Edit in Elementor', 'busly' ); ?></a>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p class="busly-wizard-lead"><?php esc_html_e( 'No static homepage is set yet. Run Demo Import on the previous step, or set one manually under Settings → Reading.', 'busly' ); ?></p>
		<?php endif; ?>

		<div class="busly-wizard-actions">
			<a href="<?php echo esc_url( self::step_url( 'import' ) ); ?>" class="busly-btn-admin secondary"><?php esc_html_e( '← Back', 'busly' ); ?></a>
			<a href="<?php echo esc_url( self::step_url( 'finish' ) ); ?>" class="busly-btn-admin"><?php esc_html_e( 'Continue →', 'busly' ); ?></a>
		</div>
		<?php
	}

	/**
	 * Step 5 — Finish.
	 */
	private static function render_step_finish() {
		$front_id = (int) get_option( 'page_on_front' );
		?>
		<h1><?php esc_html_e( "You're all set!", 'busly' ); ?></h1>
		<p class="busly-wizard-lead"><?php esc_html_e( 'Busly is ready. Here\'s where to go next.', 'busly' ); ?></p>

		<div class="busly-quicklinks">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Website', 'busly' ); ?> ↗</a>
			<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Website', 'busly' ); ?> →</a>
			<?php if ( $front_id && did_action( 'elementor/loaded' ) ) : ?>
				<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $front_id . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit Homepage', 'busly' ); ?> →</a>
			<?php endif; ?>
			<?php if ( post_type_exists( 'wbtm_bus' ) ) : ?>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wbtm_bus' ) ); ?>"><?php esc_html_e( 'Manage Bus Bookings', 'busly' ); ?> →</a>
			<?php endif; ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=busly-theme-settings' ) ); ?>"><?php esc_html_e( 'Theme Settings', 'busly' ); ?> →</a>
		</div>
		<?php
	}
}

Busly_Setup_Wizard::init();
