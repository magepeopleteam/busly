<?php
/**
 * Required/recommended plugin detection + safe install/activate actions.
 *
 * Only ever installs from wordpress.org via WordPress's own Plugin_Upgrader
 * (never an arbitrary URL) and only for plugins explicitly flagged `wp` =>
 * true in busly_get_companion_plugins(). Non-WordPress.org plugins are
 * detected only — never auto-installed.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Busly_Requirements
 */
class Busly_Requirements {

	/**
	 * Bootstrap AJAX handlers.
	 */
	public static function init() {
		add_action( 'wp_ajax_busly_install_plugin', array( __CLASS__, 'ajax_install_plugin' ) );
		add_action( 'wp_ajax_busly_activate_plugin', array( __CLASS__, 'ajax_activate_plugin' ) );
	}

	/**
	 * Flat list of every required + recommended plugin with live status.
	 *
	 * @return array<int,array{key:string,name:string,slug:string,file:string,wp:bool,required:bool,status:string}>
	 */
	public static function get_plugins() {
		$groups = busly_get_companion_plugins();
		$list   = array();

		foreach ( array( 'required', 'recommended' ) as $group ) {
			foreach ( $groups[ $group ] as $key => $plugin ) {
				$list[] = array_merge(
					$plugin,
					array(
						'key'      => $key,
						'required' => 'required' === $group,
						'status'   => self::status( $plugin['file'] ),
					)
				);
			}
		}

		return $list;
	}

	/**
	 * Determine a plugin's status by its main file (relative to wp-content/plugins).
	 *
	 * @param string $plugin_file e.g. 'elementor/elementor.php'.
	 * @return string One of 'active', 'inactive', 'not-installed'.
	 */
	public static function status( $plugin_file ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( $plugin_file ) ) {
			return 'active';
		}

		$installed = get_plugins();

		if ( isset( $installed[ $plugin_file ] ) ) {
			return 'inactive';
		}

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			return 'inactive';
		}

		return 'not-installed';
	}

	/**
	 * Render one requirement row (used by both the Dashboard and Setup Wizard).
	 *
	 * @param array $plugin Single plugin entry from get_plugins().
	 */
	public static function render_row( $plugin ) {
		$status_labels = array(
			'active'        => array( __( 'Active', 'busly' ), 'is-active' ),
			'inactive'      => array( __( 'Installed, Inactive', 'busly' ), 'is-inactive' ),
			'not-installed' => array( __( 'Not Installed', 'busly' ), 'is-missing' ),
		);
		list( $label, $class ) = $status_labels[ $plugin['status'] ];
		?>
		<div class="busly-req-row">
			<div>
				<div class="busly-req-name"><?php echo esc_html( $plugin['name'] ); ?> <?php echo $plugin['required'] ? '' : '<span class="busly-req-desc">(' . esc_html__( 'optional', 'busly' ) . ')</span>'; ?></div>
				<div class="busly-req-desc"></div>
			</div>
			<div style="display:flex;align-items:center;gap:10px;">
				<span class="busly-req-status <?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></span>
				<?php if ( 'active' !== $plugin['status'] && current_user_can( 'activate_plugins' ) ) : ?>
					<?php if ( 'not-installed' === $plugin['status'] && ! empty( $plugin['install_url'] ) ) : ?>
						<a href="<?php echo esc_url( $plugin['install_url'] ); ?>" target="_blank" class="busly-btn-admin busly-req-action busly-req-external-install" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>">
							<?php esc_html_e( 'Install', 'busly' ); ?>
						</a>
					<?php elseif ( 'not-installed' === $plugin['status'] && $plugin['wp'] ) : ?>
						<button type="button" class="busly-btn-admin busly-req-action" data-action="install" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-file="<?php echo esc_attr( $plugin['file'] ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'busly_admin_actions' ) ); ?>">
							<?php esc_html_e( 'Install', 'busly' ); ?>
						</button>
					<?php elseif ( 'inactive' === $plugin['status'] ) : ?>
						<button type="button" class="busly-btn-admin busly-req-action" data-action="activate" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-file="<?php echo esc_attr( $plugin['file'] ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'busly_admin_actions' ) ); ?>">
							<?php esc_html_e( 'Activate', 'busly' ); ?>
						</button>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the full requirements table.
	 */
	public static function render_table() {
		foreach ( self::get_plugins() as $plugin ) {
			self::render_row( $plugin );
		}
	}

	/**
	 * Are all *required* plugins active?
	 *
	 * @return bool
	 */
	public static function all_required_active() {
		foreach ( self::get_plugins() as $plugin ) {
			if ( $plugin['required'] && 'active' !== $plugin['status'] ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Look up a declared companion plugin by slug, to validate AJAX input
	 * against a known allow-list rather than trusting the posted slug/file.
	 *
	 * @param string $slug Plugin slug.
	 * @return array|null
	 */
	private static function find_by_slug( $slug ) {
		foreach ( self::get_plugins() as $plugin ) {
			if ( $plugin['slug'] === $slug ) {
				return $plugin;
			}
		}
		return null;
	}

	/**
	 * Safely activate a plugin during an AJAX request, suppressing any
	 * "headers already sent" warnings from plugins that try to redirect
	 * on activation (the redirect is not needed in AJAX context).
	 *
	 * @param string $plugin_file Plugin file path relative to wp-content/plugins.
	 * @return true|WP_Error True on success.
	 */
	private static function safe_activate( $plugin_file ) {
		ob_start();
		$result = activate_plugin( $plugin_file );
		ob_end_clean();

		return $result;
	}

	/**
	 * AJAX: install a wordpress.org plugin by slug.
	 */
	public static function ajax_install_plugin() {
		check_ajax_referer( 'busly_admin_actions', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'You are not allowed to install plugins.', 'busly' ) ) );
		}

		$slug   = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
		$plugin = self::find_by_slug( $slug );

		if ( ! $plugin ) {
			wp_send_json_error( array( 'message' => __( 'Unknown plugin.', 'busly' ) ) );
		}

		if ( ! $plugin['wp'] ) {
			wp_send_json_error( array( 'message' => __( 'This plugin cannot be auto-installed. Please install it manually.', 'busly' ) ) );
		}

		$current_status = self::status( $plugin['file'] );

		if ( 'active' === $current_status ) {
			wp_send_json_success( array( 'message' => __( 'Plugin is already active.', 'busly' ) ) );
		}

		if ( 'inactive' === $current_status ) {
			$activate = self::safe_activate( $plugin['file'] );

			if ( is_wp_error( $activate ) ) {
				wp_send_json_error( array( 'message' => $activate->get_error_message() ) );
			}
			wp_send_json_success( array( 'message' => __( 'Plugin activated.', 'busly' ) ) );
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( ! WP_Filesystem() ) {
			wp_send_json_error( array( 'message' => __( 'Could not access filesystem. Please check server permissions.', 'busly' ) ) );
		}

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array( 'sections' => false ),
			)
		);

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( array( 'message' => $api->get_error_message() ) );
		}

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		ob_start();
		$result = $upgrader->install( $api->download_link );
		ob_end_clean();

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		if ( ! $result ) {
			$errors = $skin->get_errors();
			if ( is_wp_error( $errors ) ) {
				$msg = $errors->get_error_message();
			} else {
				$msg = '';
				if ( is_array( $skin->get_error_codes() ) ) {
					foreach ( $skin->get_error_codes() as $code ) {
						$raw = $skin->get_error_data( $code );
						if ( is_string( $raw ) ) {
							$msg .= $raw . ' ';
						}
					}
				}
				$msg = trim( $msg ) ?: __( 'Installation failed. Please install the plugin manually.', 'busly' );
			}
			wp_send_json_error( array( 'message' => $msg ) );
		}

		$activate = self::safe_activate( $plugin['file'] );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_success( array( 'message' => __( 'Plugin installed. Please activate it from the requirements list.', 'busly' ) ) );
		}

		wp_send_json_success( array( 'message' => __( 'Plugin installed and activated.', 'busly' ) ) );
	}

	/**
	 * AJAX: activate an already-installed plugin.
	 */
	public static function ajax_activate_plugin() {
		check_ajax_referer( 'busly_admin_actions', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'You are not allowed to activate plugins.', 'busly' ) ) );
		}

		$slug   = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
		$plugin = self::find_by_slug( $slug );

		if ( ! $plugin ) {
			wp_send_json_error( array( 'message' => __( 'Unknown plugin.', 'busly' ) ) );
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$result = self::safe_activate( $plugin['file'] );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Plugin activated.', 'busly' ) ) );
	}
}

Busly_Requirements::init();
