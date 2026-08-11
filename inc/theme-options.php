<?php
/**
 * Busly → Theme Settings.
 *
 * A single Settings-API-registered option (`busly_theme_options`, an array)
 * drives every field below. Fields are declared once in
 * busly_theme_option_fields() and used both to render the tabbed admin UI
 * and to sanitize input on save — no value is ever trusted from $_POST
 * without passing through the matching sanitizer for its declared type.
 *
 * @package Busly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single source of truth for every Theme Settings field: tab, type,
 * label, description, default and (for select/checkbox) choices.
 *
 * @return array<string,array{label:string,fields:array<string,array>}>
 */
function busly_theme_option_fields() {
	static $tabs = null;

	if ( null !== $tabs ) {
		return $tabs;
	}

	$tabs = array(
		'general'    => array(
			'label'  => __( 'General', 'busly' ),
			'fields' => array(
				'site_layout'      => array(
					'type'    => 'select',
					'label'   => __( 'Site Layout', 'busly' ),
					'default' => 'boxed',
					'choices' => array(
						'boxed'      => __( 'Boxed', 'busly' ),
						'full-width' => __( 'Full Width', 'busly' ),
					),
				),
				'container_width'  => array(
					'type'        => 'number',
					'label'       => __( 'Container Width (px)', 'busly' ),
					'default'     => 1180,
					'description' => __( 'Max width of the content wrapper on desktop.', 'busly' ),
				),
				'logo_retina'      => array(
					'type'        => 'text',
					'label'       => __( 'Retina Logo URL', 'busly' ),
					'default'     => '',
					'description' => __( 'Optional 2x logo image URL for HiDPI screens. Set your standard logo in Customizer → Site Identity first.', 'busly' ),
				),
				'favicon_note'     => array(
					'type'        => 'note',
					'label'       => __( 'Favicon', 'busly' ),
					'description' => __( 'Set your favicon under Customizer → Site Identity → Site Icon (512×512px recommended).', 'busly' ),
				),
			),
		),
		'header'     => array(
			'label'  => __( 'Header', 'busly' ),
			'fields' => array(
				'header_show_login'    => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show "Login / My Account" button', 'busly' ),
					'default' => 'yes',
				),
				'header_show_bookings' => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show "My Bookings" button', 'busly' ),
					'default' => 'yes',
				),
				'header_show_cta'      => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show primary CTA button', 'busly' ),
					'default' => 'yes',
				),
				'header_cta_label'     => array(
					'type'    => 'text',
					'label'   => __( 'CTA Button Text', 'busly' ),
					'default' => __( 'Book Now', 'busly' ),
				),
				'header_cta_url'       => array(
					'type'        => 'text',
					'label'       => __( 'CTA Button URL (fallback)', 'busly' ),
					'default'     => '',
					'description' => __( 'Used only if no Bus Search page is selected under the Bus Booking tab.', 'busly' ),
				),
				'header_sticky_note'   => array(
					'type'        => 'note',
					'label'       => __( 'Sticky / Transparent Header', 'busly' ),
					'description' => __( 'These are live-preview settings — manage them under Customizer → Busly Options → Header Behavior.', 'busly' ),
				),
			),
		),
		'typography' => array(
			'label'  => __( 'Typography', 'busly' ),
			'fields' => array(
				'font_body'      => array(
					'type'        => 'text',
					'label'       => __( 'Body/Heading Font Stack', 'busly' ),
					'default'     => "'Plus Jakarta Sans', sans-serif",
					'description' => __( 'A CSS font-family value. To use a different Google Font, also update the Google Fonts URL via the busly_enable_google_fonts filter / a child theme.', 'busly' ),
				),
				'font_size_base' => array(
					'type'    => 'number',
					'label'   => __( 'Base Font Size (px)', 'busly' ),
					'default' => 15,
				),
				'heading_weight' => array(
					'type'    => 'select',
					'label'   => __( 'Heading Font Weight', 'busly' ),
					'default' => '900',
					'choices' => array(
						'700' => __( '700 — Bold', 'busly' ),
						'800' => __( '800 — Extra Bold', 'busly' ),
						'900' => __( '900 — Black', 'busly' ),
					),
				),
			),
		),
		'colors'     => array(
			'label'  => __( 'Colors', 'busly' ),
			'fields' => array(
				'color_navy'           => array(
					'type'    => 'color',
					'label'   => __( 'Navy (hero overlay / CTA gradient)', 'busly' ),
					'default' => '#0c1a52',
				),
				'color_primary'        => array(
					'type'    => 'color',
					'label'   => __( 'Primary', 'busly' ),
					'default' => '#1d3d87',
				),
				'color_primary_light'  => array(
					'type'    => 'color',
					'label'   => __( 'Primary — Light', 'busly' ),
					'default' => '#2f56c7',
				),
				'color_primary_lighter' => array(
					'type'    => 'color',
					'label'   => __( 'Primary — Lighter', 'busly' ),
					'default' => '#3b6ae8',
				),
				'color_accent'         => array(
					'type'    => 'color',
					'label'   => __( 'Accent (search / CTA buttons)', 'busly' ),
					'default' => '#f05a28',
				),
				'color_accent_hover'   => array(
					'type'    => 'color',
					'label'   => __( 'Accent — Hover', 'busly' ),
					'default' => '#e04a18',
				),
				'color_heading'        => array(
					'type'    => 'color',
					'label'   => __( 'Heading Text', 'busly' ),
					'default' => '#0f172a',
				),
				'color_text'           => array(
					'type'    => 'color',
					'label'   => __( 'Body Text', 'busly' ),
					'default' => '#64748b',
				),
				'color_border'         => array(
					'type'    => 'color',
					'label'   => __( 'Borders', 'busly' ),
					'default' => '#e2e8f0',
				),
				'color_background'     => array(
					'type'    => 'color',
					'label'   => __( 'Page Background', 'busly' ),
					'default' => '#f4f6fb',
				),
				'radius'               => array(
					'type'    => 'number',
					'label'   => __( 'Card Corner Radius (px)', 'busly' ),
					'default' => 20,
				),
			),
		),
		'footer'     => array(
			'label'  => __( 'Footer', 'busly' ),
			'fields' => array(
				'footer_description'         => array(
					'type'    => 'textarea',
					'label'   => __( 'Footer Brand Description', 'busly' ),
					'default' => '',
				),
				'footer_show_payment_badges' => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show payment badges (Visa/Mastercard/PayPal/Stripe)', 'busly' ),
					'default' => 'yes',
				),
			),
		),
		'blog'       => array(
			'label'  => __( 'Blog', 'busly' ),
			'fields' => array(
				'blog_layout'            => array(
					'type'    => 'select',
					'label'   => __( 'Layout', 'busly' ),
					'default' => 'grid',
					'choices' => array(
						'grid'   => __( 'Grid (2 columns)', 'busly' ),
						'grid-3' => __( 'Grid (3 columns)', 'busly' ),
						'list'   => __( 'List', 'busly' ),
					),
				),
				'blog_sidebar_position'  => array(
					'type'    => 'select',
					'label'   => __( 'Sidebar', 'busly' ),
					'default' => 'right-sidebar',
					'choices' => array(
						'right-sidebar' => __( 'Right Sidebar', 'busly' ),
						'left-sidebar'  => __( 'Left Sidebar', 'busly' ),
						'no-sidebar'    => __( 'No Sidebar', 'busly' ),
					),
				),
				'blog_excerpt_length'    => array(
					'type'    => 'number',
					'label'   => __( 'Excerpt Length (words)', 'busly' ),
					'default' => 22,
				),
				'blog_show_reading_time' => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show estimated reading time', 'busly' ),
					'default' => 'yes',
				),
				'blog_show_comment_count' => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show comment count', 'busly' ),
					'default' => 'yes',
				),
				'blog_show_author_box'   => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show author box on single posts', 'busly' ),
					'default' => 'yes',
				),
				'blog_show_related'      => array(
					'type'    => 'checkbox',
					'label'   => __( 'Show related posts', 'busly' ),
					'default' => 'yes',
				),
			),
		),
		'booking'    => array(
			'label'  => __( 'Bus Booking', 'busly' ),
			'fields' => array(
				'booking_search_page'  => array(
					'type'        => 'page',
					'label'       => __( 'Bus Search Page', 'busly' ),
					'default'     => 0,
					'description' => __( 'Where the header "Book Now" button and Busly widgets link by default. Set automatically by the Setup Wizard demo import.', 'busly' ),
				),
				'booking_default_style' => array(
					'type'    => 'select',
					'label'   => __( 'Default Results Card Style', 'busly' ),
					'default' => 'grid',
					'choices' => array(
						'grid' => __( 'Modern cards', 'busly' ),
						'flix' => __( 'Compact (flix) rows', 'busly' ),
					),
				),
			),
		),
		'woocommerce' => array(
			'label'  => __( 'WooCommerce', 'busly' ),
			'fields' => array(
				'woo_layout'  => array(
					'type'    => 'select',
					'label'   => __( 'Shop Layout', 'busly' ),
					'default' => 'right-sidebar',
					'choices' => array(
						'right-sidebar' => __( 'Right Sidebar', 'busly' ),
						'left-sidebar'  => __( 'Left Sidebar', 'busly' ),
						'no-sidebar'    => __( 'No Sidebar', 'busly' ),
					),
				),
				'woo_columns' => array(
					'type'    => 'number',
					'label'   => __( 'Product Columns', 'busly' ),
					'default' => 3,
				),
			),
		),
		'performance' => array(
			'label'  => __( 'Performance', 'busly' ),
			'fields' => array(
				'perf_disable_google_fonts'  => array(
					'type'        => 'checkbox',
					'label'       => __( 'Disable Google Fonts (use system font fallback)', 'busly' ),
					'default'     => '',
					'description' => __( 'Improves privacy/performance; falls back to your OS font stack.', 'busly' ),
				),
				'perf_lazy_load_images'      => array(
					'type'    => 'checkbox',
					'label'   => __( 'Native lazy-loading for images', 'busly' ),
					'default' => 'yes',
				),
				'perf_comment_reply_defer'   => array(
					'type'    => 'checkbox',
					'label'   => __( 'Defer the comment-reply script', 'busly' ),
					'default' => 'yes',
				),
			),
		),
		'social'     => array(
			'label'  => __( 'Social', 'busly' ),
			'fields' => array(
				'social_note' => array(
					'type'        => 'note',
					'label'       => __( 'Social Links', 'busly' ),
					'description' => __( 'Social links are live-preview settings — manage them under Customizer → Busly Options → Social Links.', 'busly' ),
				),
			),
		),
	);

	return $tabs;
}

/**
 * Flat key => default map, built from busly_theme_option_fields().
 * Used by busly_get_option() as the final fallback layer.
 *
 * @return array<string,mixed>
 */
function busly_theme_option_defaults() {
	static $defaults = null;

	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array();
	foreach ( busly_theme_option_fields() as $tab ) {
		foreach ( $tab['fields'] as $key => $field ) {
			if ( 'note' === $field['type'] ) {
				continue;
			}
			$defaults[ $key ] = $field['default'];
		}
	}

	return $defaults;
}

/**
 * Register the setting (sanitize callback only — fields are rendered
 * manually by busly_render_theme_settings_page() for the tabbed layout).
 */
function busly_register_theme_options() {
	register_setting(
		'busly_theme_options_group',
		'busly_theme_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'busly_sanitize_theme_options',
			'default'           => busly_theme_option_defaults(),
		)
	);
}
add_action( 'admin_init', 'busly_register_theme_options' );

/**
 * Sanitize every submitted field according to its declared type. Unknown
 * keys are dropped; missing checkboxes are stored as '' (unchecked).
 *
 * @param array $input Raw $_POST['busly_theme_options'] (already unslashed
 *                      by the Settings API before this callback runs).
 * @return array
 */
function busly_sanitize_theme_options( $input ) {
	$input   = is_array( $input ) ? $input : array();
	$clean   = array();

	foreach ( busly_theme_option_fields() as $tab ) {
		foreach ( $tab['fields'] as $key => $field ) {
			if ( 'note' === $field['type'] ) {
				continue;
			}

			$raw = isset( $input[ $key ] ) ? $input[ $key ] : '';

			switch ( $field['type'] ) {
				case 'checkbox':
					$clean[ $key ] = ( 'yes' === $raw ) ? 'yes' : '';
					break;
				case 'number':
					$clean[ $key ] = absint( $raw );
					break;
				case 'color':
					$clean[ $key ] = sanitize_hex_color( $raw );
					if ( ! $clean[ $key ] ) {
						$clean[ $key ] = $field['default'];
					}
					break;
				case 'select':
					$choices       = array_keys( $field['choices'] );
					$clean[ $key ] = in_array( $raw, $choices, true ) ? $raw : $field['default'];
					break;
				case 'page':
					$clean[ $key ] = absint( $raw );
					break;
				case 'textarea':
					$clean[ $key ] = sanitize_textarea_field( $raw );
					break;
				case 'text':
				default:
					$clean[ $key ] = sanitize_text_field( $raw );
					break;
			}
		}
	}

	add_settings_error( 'busly_theme_options', 'busly_saved', __( 'Theme settings saved.', 'busly' ), 'success' );

	return $clean;
}

/**
 * Add the "Theme Settings" submenu under the top-level Busly menu
 * (top-level menu itself is registered in inc/admin/class-dashboard.php).
 */
function busly_add_theme_options_page() {
	add_submenu_page(
		'busly',
		__( 'Busly Theme Settings', 'busly' ),
		__( 'Theme Settings', 'busly' ),
		'edit_theme_options',
		'busly-theme-settings',
		'busly_render_theme_settings_page'
	);
}
add_action( 'admin_menu', 'busly_add_theme_options_page' );

/**
 * Render the tabbed Theme Settings screen.
 */
function busly_render_theme_settings_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$tabs    = busly_theme_option_fields();
	$options = get_option( 'busly_theme_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}
	$defaults        = busly_theme_option_defaults();
	$busly_first_tab = array_key_first( $tabs );
	?>
	<div class="wrap busly-admin">
		<div class="busly-admin-header">
			<h1><span class="busly-admin-logo"><?php busly_icon( 'bus' ); ?></span> <?php esc_html_e( 'Busly Theme Settings', 'busly' ); ?></h1>
			<span class="busly-admin-version"><?php echo esc_html( 'v' . BUSLY_VERSION ); ?></span>
		</div>

		<?php settings_errors( 'busly_theme_options' ); ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'busly_theme_options_group' ); ?>

			<div class="busly-settings-wrap">
				<nav class="busly-settings-nav">
					<?php foreach ( $tabs as $tab_key => $tab ) : ?>
						<a href="#<?php echo esc_attr( $tab_key ); ?>" data-tab="<?php echo esc_attr( $tab_key ); ?>" class="<?php echo $tab_key === $busly_first_tab ? 'is-active' : ''; ?>"><?php echo esc_html( $tab['label'] ); ?></a>
					<?php endforeach; ?>
				</nav>

				<div class="busly-settings-panels" style="flex:1;">
					<?php foreach ( $tabs as $tab_key => $tab ) : ?>
						<div class="busly-settings-panel" data-tab="<?php echo esc_attr( $tab_key ); ?>" <?php echo $tab_key === $busly_first_tab ? '' : 'style="display:none;"'; ?>>
							<h2><?php echo esc_html( $tab['label'] ); ?></h2>

							<?php foreach ( $tab['fields'] as $field_key => $field ) : ?>
								<div class="busly-field-row">
									<label for="busly-field-<?php echo esc_attr( $field_key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
									<div>
										<?php
										$value = array_key_exists( $field_key, $options ) ? $options[ $field_key ] : ( $defaults[ $field_key ] ?? '' );
										busly_render_settings_field( $field_key, $field, $value );
										?>
										<?php if ( ! empty( $field['description'] ) ) : ?>
											<p class="busly-field-desc"><?php echo wp_kses_post( $field['description'] ); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>

					<?php submit_button( __( 'Save Settings', 'busly' ) ); ?>
				</div>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Render a single settings field input.
 *
 * @param string $key   Field key.
 * @param array  $field Field config.
 * @param mixed  $value Current value.
 */
function busly_render_settings_field( $key, $field, $value ) {
	$name = 'busly_theme_options[' . $key . ']';
	$id   = 'busly-field-' . $key;

	switch ( $field['type'] ) {
		case 'note':
			echo '<p class="busly-field-desc" style="margin-top:8px;">' . wp_kses_post( $field['description'] ) . '</p>';
			return;

		case 'checkbox':
			printf(
				'<label><input type="checkbox" id="%1$s" name="%2$s" value="yes" %3$s /> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $name ),
				checked( $value, 'yes', false ),
				esc_html__( 'Enabled', 'busly' )
			);
			return;

		case 'select':
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $field['choices'] as $choice_value => $choice_label ) {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $choice_value ),
					selected( $value, $choice_value, false ),
					esc_html( $choice_label )
				);
			}
			echo '</select>';
			return;

		case 'color':
			printf(
				'<input type="color" id="%1$s" name="%2$s" value="%3$s" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value ? $value : $field['default'] )
			);
			return;

		case 'number':
			printf(
				'<input type="number" id="%1$s" name="%2$s" value="%3$s" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value )
			);
			return;

		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="3">%3$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_textarea( $value )
			);
			return;

		case 'page':
			wp_dropdown_pages(
				array(
					'id'                => $id,
					'name'              => $name,
					'selected'          => $value,
					'show_option_none'  => __( '— Select a page —', 'busly' ),
					'option_none_value' => 0,
				)
			);
			return;

		case 'text':
		default:
			printf(
				'<input type="text" id="%1$s" name="%2$s" value="%3$s" />',
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value )
			);
			return;
	}
}
