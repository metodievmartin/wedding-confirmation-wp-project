<?php
/**
 * Plugin Name: WP reCAPTCHA v3 Handler
 * Description: A class-based plugin to handle Google reCAPTCHA v3 with customisable score and API keys.
 * Version: 1.0
 * Author: Martin Metodiev
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WP_Recaptcha_V3_Handler {

	// Define plugin constants
	private $plugin_slug = 'wp-recaptcha-v3';
	private $enable_recaptcha_option = 'wp_recaptcha_v3_enabled';
	private $site_key_option = 'wp_recaptcha_v3_site_key';
	private $secret_key_option = 'wp_recaptcha_v3_secret_key';
	private $score_option = 'wp_recaptcha_v3_score';

	public function __construct() {
		// Add actions
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_recaptcha_script' ] );
	}

	/**
	 * Add admin menu for plugin settings
	 */
	public function add_admin_menu() {
		add_options_page(
			'WP reCAPTCHA v3 Settings',
			'reCAPTCHA v3',
			'manage_options',
			$this->plugin_slug,
			[ $this, 'create_settings_page' ]
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( $this->plugin_slug, $this->enable_recaptcha_option );
		register_setting( $this->plugin_slug, $this->site_key_option );
		register_setting( $this->plugin_slug, $this->secret_key_option );
		register_setting( $this->plugin_slug, $this->score_option );
	}

	/**
	 * Enqueue the reCAPTCHA v3 script
	 */
	public function enqueue_recaptcha_script() {
		$site_key = get_option( $this->site_key_option );
		if ( $site_key ) {
			wp_enqueue_script(
				'google-recaptcha-v3',
				'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $site_key ),
				[],
				null,
				true
			);
		}
	}

	/**
	 * Display the settings page in the admin panel.
	 */
	public function create_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>
        <div class="wrap">
            <h1>WP reCAPTCHA v3 Settings</h1>
            <form method="post" action="options.php">
				<?php settings_fields( $this->plugin_slug ); ?>
				<?php do_settings_sections( $this->plugin_slug ); ?>

                <!-- Enable/Disable reCAPTCHA -->
                <div style="margin: 20px 0;">
                    <label for="<?php echo $this->enable_recaptcha_option ?>"
                           style="display: block; font-weight: bold;">
                        <input
                                type="checkbox"
                                name="<?php echo $this->enable_recaptcha_option ?>"
                                id="<?php echo $this->enable_recaptcha_option ?>"
                                value="1"
							<?php checked( get_option( $this->enable_recaptcha_option, 0 ) ); ?>>
                        Enable reCAPTCHA
                    </label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo $this->site_key_option; ?>" style="display: block; font-weight: bold;">Site
                        Key</label>
                    <input
                            type="text"
                            name="<?php echo $this->site_key_option; ?>"
                            id="<?php echo $this->site_key_option; ?>"
                            value="<?php echo esc_attr( get_option( $this->site_key_option, '' ) ); ?>"
                            class="regular-text"
                            style="width: 100%; max-width: 600px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo $this->secret_key_option; ?>" style="display: block; font-weight: bold;">Secret
                        Key</label>
                    <input
                            type="text"
                            name="<?php echo $this->secret_key_option; ?>"
                            id="<?php echo $this->secret_key_option; ?>"
                            value="<?php echo esc_attr( get_option( $this->secret_key_option, '' ) ); ?>"
                            class="regular-text"
                            style="width: 100%; max-width: 600px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo $this->score_option; ?>" style="display: block; font-weight: bold;">Minimum
                        Score</label>
                    <input
                            type="number"
                            name="<?php echo $this->score_option; ?>"
                            id="<?php echo $this->score_option; ?>"
                            value="<?php echo esc_attr( get_option( $this->score_option, 0.5 ) ); ?>"
                            step="0.1"
                            min="0"
                            max="1"
                            style="width: 100px;">
                </div>

				<?php submit_button(); ?>
            </form>
        </div>
		<?php
	}

	/**
	 * Verify the reCAPTCHA response
	 */
	public function verify_recaptcha( $token ) {
		$secret_key = get_option( $this->secret_key_option );

		$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
			'body' => [
				'secret'   => $secret_key,
				'response' => $token,
			],
		] );

		$response_body = wp_remote_retrieve_body( $response );

		return json_decode( $response_body, true );
	}

	/**
	 * Handle form submission
	 */
	public function handle_form_submission() {
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['g-recaptcha-response'] ) ) {
			$recaptcha_response = $this->verify_recaptcha( $_POST['g-recaptcha-response'] );
			$required_score     = get_option( $this->score_option, 0.5 );

			if ( empty( $recaptcha_response['success'] ) || $recaptcha_response['score'] < $required_score ) {
				wp_die( 'reCAPTCHA verification failed. Please try again.' );
			}

			// Continue processing form submission
			echo 'Form submitted successfully!';
		}
	}
}

// Initialise the plugin
new WP_Recaptcha_V3_Handler();