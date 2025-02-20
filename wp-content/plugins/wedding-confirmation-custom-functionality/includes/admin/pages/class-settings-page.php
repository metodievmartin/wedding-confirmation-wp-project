<?php

class WCCF_Settings_Page {
	private const SAVE_SETTINGS_ACTION = 'wccf_save_settings';

	private WCCF_Settings_Service $settings_service;
	private string $menu_page_slug = 'wedding-confirmation-settings';

	public function __construct( WCCF_Settings_Service $settings_service, $menu_page_slug = '' ) {
		$this->settings_service = $settings_service;

		if ( $menu_page_slug ) {
			$this->menu_page_slug = $menu_page_slug;
		}
	}

	public function get_page_slug() {
		return $this->menu_page_slug;
	}

	public function initialise() {
		add_action( 'admin_post_' . self::SAVE_SETTINGS_ACTION, array( $this, 'handle_save_settings' ) );
	}

	public function add_page_as_menu_page( array $args = [] ): string {
		$defaults = [
			'page_title' => __( 'Wedding Confirmations Settings', 'wccf-domain' ),
			'menu_title' => __( 'Settings', 'wccf-domain' ),
			'capability' => 'edit_pages',
			'icon_url'   => '',
			'position'   => 25,
		];

		$args = wp_parse_args( $args, $defaults );

		return add_menu_page(
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$this->get_page_slug(),
			array( $this, 'render_admin_page' ),
			$args['icon_url'],
			$args['position']
		);
	}

	public function add_page_as_submenu_page( string $parent_slug, array $args = [] ): string|false {
		// Define the default arguments
		$defaults = [
			'page_title' => __( 'Wedding Confirmations Settings', 'wccf-domain' ),
			'menu_title' => __( 'Settings', 'wccf-domain' ),
			'capability' => 'edit_pages',
		];

		// Merge the provided arguments with defaults
		$args = wp_parse_args( $args, $defaults );

		// Use the arguments to create the submenu page
		return add_submenu_page(
			$parent_slug,
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$this->get_page_slug(),
			array( $this, 'render_settings_page' )
		);
	}

	public function load_page_assets() {
		error_log( plugin_dir_url( __FILE__ ) . 'assets/js/settings.js' );
		wp_enqueue_script(
			'wccf-settings-page-js',
			plugin_dir_url( __FILE__ ) . 'assets/js/settings.js'
		);
	}

	public function handle_save_settings() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( esc_html__( 'Unauthorised user', 'wccf-domain' ) );
		}

		// Check for nonce validity; this returns a value, which can be checked.
		$nonce_check = check_admin_referer( self::SAVE_SETTINGS_ACTION, 'wccf_settings_nonce' );

		if ( ! $nonce_check ) {
			wp_die( esc_html__( 'Invalid request.', 'wccf-domain' ) );
		}

		$end_date_option_id = $this->settings_service::CONFIRMATIONS_END_DATE;
		$timezone_option_id = $this->settings_service::CONFIRMATIONS_END_DATE_TIMEZONE;
		$colour_picker_id   = $this->settings_service::COLOUR_PICKER_OPTION;

		$date_time_utc = isset( $_POST[ $end_date_option_id ] ) ? sanitize_text_field( $_POST[ $end_date_option_id ] ) : '';
		$timezone      = isset( $_POST[ $timezone_option_id ] ) ? sanitize_text_field( $_POST[ $timezone_option_id ] ) : '';
		$colour_picker = isset( $_POST[ $colour_picker_id ] ) ? sanitize_text_field( $_POST[ $colour_picker_id ] ) : '';

		if ( $date_time_utc && $timezone && $colour_picker ) {
			// Save both UTC datetime and timezone
			$this->settings_service->save_confirmations_end_date( $date_time_utc );
			$this->settings_service->save_confirmations_end_date_timezone( $timezone );

			$this->settings_service->save_selected_colour( $colour_picker );

			wp_redirect( admin_url( 'admin.php?page=' . $this->get_page_slug() . '&message=success' ) );
			exit;
		}

		wp_redirect( admin_url( 'admin.php?page=' . $this->get_page_slug() . '&message=error' ) );
		exit;
	}

	public function render_settings_page() {
		$conf_end_date_option_name          = $this->settings_service::CONFIRMATIONS_END_DATE;
		$conf_end_date_timezone_option_name = $this->settings_service::CONFIRMATIONS_END_DATE_TIMEZONE;
		$conf_end_date_picker_id            = 'wccf_date_time_picker';
		$end_date                           = $this->settings_service->get_confirmations_end_date();

		$colour_picker_option_name     = $this->settings_service::COLOUR_PICKER_OPTION;
		$colour_picker_selected_colour = $this->settings_service->get_selected_colour();
		$colors                        = $this->settings_service->get_all_colours();
		?>

        <div class="wrap">
            <h1><?php esc_html_e( 'Settings', 'wccf-domain' ); ?></h1>

            <div id="settings-notices">
				<?php $this->render_wp_notice(); ?>
            </div>

            <form id="save-settings-form"
                  method="post"
                  action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">

				<?php wp_nonce_field( self::SAVE_SETTINGS_ACTION, 'wccf_settings_nonce' ); ?>
                <input type="hidden" name="action" value="<?php echo self::SAVE_SETTINGS_ACTION; ?>">

                <!-- Hidden fields for UTC time and timezone -->
                <input type="hidden"
                       id="wccf_date_time_utc"
                       name="<?php echo $conf_end_date_option_name ?>"
                       value="">
                <input type="hidden"
                       id="wccf_date_time_timezone"
                       name="<?php echo $conf_end_date_timezone_option_name; ?>"
                       value="">

                <table class="form-table">
                    <tr class="end-date-info-message-row">
                        <td colspan="2">
                            <div class="info-message">
								<?php echo $this->get_end_date_info_message( $end_date ); ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="<?php echo $conf_end_date_picker_id ?>">
								<?php esc_html_e( 'Accept confirmations until:', 'wccf-domain' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="datetime-local"
                                   id="<?php echo $conf_end_date_picker_id ?>"
                                   class="datepicker"
                                   value="<?php echo esc_attr( $this->get_formatted_date( $end_date ) ); ?>"
                                   required>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="color-picker">Select a Colour:</label>
                        </th>
                        <td>
                            <div class="custom-dropdown">
                                <button type="button" id="dropdown-button" class="dropdown-button">
                                    <span class="color-box"
                                          style="background-color: <?php echo esc_attr( $colour_picker_selected_colour['hex'] ); ?>;"></span>
									<?php echo esc_html( $colour_picker_selected_colour['name'] ); ?>
                                </button>

                                <ul id="color-options" class="dropdown-menu">

									<?php foreach ( $colors as $colour ): ?>
                                        <li class="dropdown-item"
                                            data-colour-id="<?php echo esc_attr( $colour['id'] ); ?>"
                                            data-colour-hex="<?php echo esc_attr( $colour['hex'] ); ?>"
                                            data-colour-name="<?php echo esc_attr( $colour['name'] ); ?>">
                                            <span class="color-box"
                                                  style="background-color: <?php echo esc_attr( $colour['hex'] ); ?>;"></span>
											<?php echo esc_html( $colour['name'] ); ?>
                                        </li>
									<?php endforeach; ?>

                                </ul>

                                <input type="hidden" id="selected-color-id"
                                       name="<?php echo esc_attr( $colour_picker_option_name ); ?>"
                                       value="<?php echo esc_attr( $colour_picker_selected_colour['id'] ); ?>">
                            </div>
                        </td>
                    </tr>
                </table>

				<?php submit_button( 'Save Settings' ); ?>
            </form>
        </div>
		<?php
	}

	public function get_formatted_date( $date, $format = 'Y-m-d\TH:i' ) {
		if ( ! $date ) {
			return '';
		}

		return $date->format( $format );
	}


	public function get_end_date_info_message( $date ) {
		if ( ! $date ) {
			return 'Please, set a closing date and time.';
		}

		$formatted_date     = $date->format( 'd.m.Y' );
		$formatted_time     = $date->format( 'H:i' );
		$formatted_timezone = $date->getTimezone()->getName();

		return sprintf(
			'&#9432; Accepting new confirmations will be closed on <strong>%s</strong> at <strong>%s</strong> (%s time).',
			$formatted_date,
			$formatted_time,
			$formatted_timezone
		);
	}

	// TODO: move to a helper
	public function render_wp_notice() {
		if ( isset( $_GET['message'] ) ) {
			$message = sanitize_text_field( $_GET['message'] );

			if ( $message === 'success' ) {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved successfully.', 'wccf-domain' ) . '</p></div>';
			} elseif ( $message === 'error' ) {
				echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Failed to save settings. Please try again.', 'wccf-domain' ) . '</p></div>';
			}
		}
	}
}