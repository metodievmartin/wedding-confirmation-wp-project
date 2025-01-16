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
			'page_title' => __( 'Wedding Confirmations Settings', 'wccf_domain' ),
			'menu_title' => __( 'Settings', 'wccf_domain' ),
			'capability' => 'manage_options',
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
			'page_title' => __( 'Wedding Confirmations Settings', 'wccf_domain' ),
			'menu_title' => __( 'Settings', 'wccf_domain' ),
			'capability' => 'manage_options',
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
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorised user', 'wccf_domain' ) );
		}

		// Check for nonce validity; this returns a value, which can be checked.
		$nonce_check = check_admin_referer( self::SAVE_SETTINGS_ACTION, 'wccf_settings_nonce' );

		if ( ! $nonce_check ) {
			wp_die( esc_html__( 'Invalid request.', 'wccf_domain' ) );
		}

		$end_date_option_id = $this->settings_service::CONFIRMATIONS_END_DATE;
		$timezone_option_id = $this->settings_service::CONFIRMATIONS_END_DATE_TIMEZONE;

		$date_time_utc = isset( $_POST[ $end_date_option_id ] ) ? sanitize_text_field( $_POST[ $end_date_option_id ] ) : '';
		$timezone      = isset( $_POST[ $timezone_option_id ] ) ? sanitize_text_field( $_POST[ $timezone_option_id ] ) : '';

		error_log( '$date_time_utc: ' . $date_time_utc );
		error_log( '$timezone: ' . $timezone );

		if ( $date_time_utc && $timezone ) {
			// Save both UTC datetime and timezone
			$this->settings_service->save_confirmations_end_date( $date_time_utc );
			$this->settings_service->save_confirmations_end_date_timezone( $timezone );

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
                    <tr>
                        <th scope="row">
                            <label for="<?php echo $conf_end_date_picker_id ?>">
								<?php esc_html_e( 'Accept confirmations until:', 'wccf-domain' ); ?>
                            </label>
                        </th>
                        <td>
                            <input type="datetime-local" id="<?php echo $conf_end_date_picker_id ?>"
                                   value="<?php echo esc_attr( $this->get_formatted_date( $end_date ) ); ?>"
                                   required>
                        </td>
                    </tr>
                    <tr class="end-date-info-message-row">
                        <td colspan="2">
                            <div class="info-message">
								<?php echo $this->get_end_date_info_message( $end_date ); ?>
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
//		$date = $this->settings_service->get_confirmations_end_date();

		if ( ! $date ) {
			return '';
		}

		return $date->format( $format );
	}


	public function get_end_date_info_message( $date ) {
//		$date = $this->settings_service->get_confirmations_end_date();

		if ( ! $date ) {
			return 'Please, set a closing date and time.';
		}

		$formatted_date     = $date->format( 'd.m.Y' );
		$formatted_time     = $date->format( 'H:i' );
		$formatted_timezone = $date->getTimezone()->getName();

		return sprintf(
			'Accepting new confirmations will be closed on <strong>%s</strong> at <strong>%s</strong> (%s time).',
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