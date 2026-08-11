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
	 * Bootstrap hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_page' ) );
	}

	/**
	 * Register under Appearance (matches the Dashboard's quick-link URL).
	 */
	public static function register_page() {
		add_theme_page(
			__( 'Busly Setup Wizard', 'busly' ),
			__( 'Busly Setup', 'busly' ),
			'edit_theme_options',
			'busly-setup-wizard',
			array( __CLASS__, 'render' )
		);
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
			admin_url( 'themes.php' )
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
		?>
		<h1><?php esc_html_e( 'Required Plugins', 'busly' ); ?></h1>
		<p class="busly-wizard-lead"><?php esc_html_e( 'Busly needs these plugins for booking and page-building to work.', 'busly' ); ?></p>

		<div class="busly-admin-card" style="box-shadow:none;">
			<?php Busly_Requirements::render_table(); ?>
		</div>

		<?php if ( ! Busly_Requirements::all_required_active() ) : ?>
			<p class="busly-field-desc"><?php esc_html_e( 'You can continue without them, but demo import and booking widgets will show a notice until they\'re active.', 'busly' ); ?></p>
		<?php endif; ?>

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
			<div class="busly-notice busly-notice--info"><?php esc_html_e( 'Demo content was already imported. Running it again will only add anything missing — it will not duplicate pages.', 'busly' ); ?></div>
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
