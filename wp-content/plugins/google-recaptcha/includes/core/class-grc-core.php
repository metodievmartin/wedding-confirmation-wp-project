<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GRC_Core {
	use GRC_Settings_Trait;

	private static $instance = null;

	// ========== Static Methods ==========

	/**
	 * Initialises the functionality and makes sure it's done only once.
	 *
	 * @return GRC_Core
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the singleton instance of the plugin.
	 *
	 * @return GRC_Core|null
	 */
	public static function get_instance() {
		return self::$instance;
	}

	/**
	 * Main constructor.
	 */
	private function __construct() {
		$this->initialise();
	}

	private function initialise() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_recaptcha_script' ] );
		add_action( 'wp_footer', [ $this, 'inject_recaptcha_options' ] );
	}

	/**
	 * Add admin menu for plugin settings
	 */
	public function add_admin_menu() {
		add_options_page(
			'WP reCAPTCHA v3 Settings',
			'reCAPTCHA v3',
			'manage_options',
			self::PLUGIN_SLUG,
			[ $this, 'create_settings_page' ]
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( self::PLUGIN_SLUG, self::ENABLE_RECAPTCHA_OPTION );
		register_setting( self::PLUGIN_SLUG, self::SITE_KEY_OPTION );
		register_setting( self::PLUGIN_SLUG, self::SECRET_KEY_OPTION );
		register_setting( self::PLUGIN_SLUG, self::SCORE_OPTION );
	}

	/**
	 * Enqueue the reCAPTCHA v3 script
	 */
	public function enqueue_recaptcha_script() {
		if ( $this->_is_recaptcha_enabled() ) {
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $this->_get_recaptcha_site_key() ),
				[],
				null,
				true
			);
		}
	}

	public function inject_recaptcha_options() {
		$recaptcha_settings = array(
			'isEnabled' => $this->_is_recaptcha_enabled(),
			'siteKey'   => '',
		);

		if ( $this->_is_recaptcha_enabled() ) {
			$recaptcha_settings['siteKey'] = $this->_get_recaptcha_site_key();
		}

		echo '<script type="text/javascript">window.recaptchaGlobalOptions = ' . json_encode( $recaptcha_settings ) . ';</script>';
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
            <h1>Google reCAPTCHA v3 Settings</h1>
            <form method="post" action="options.php">
				<?php settings_fields( self::PLUGIN_SLUG ); ?>
				<?php do_settings_sections( self::PLUGIN_SLUG ); ?>

                <!-- Enable/Disable reCAPTCHA -->
                <div style="margin: 20px 0;">
                    <label for="<?php echo self::ENABLE_RECAPTCHA_OPTION; ?>"
                           style="display: block; font-weight: bold;">
                        <input
                                type="checkbox"
                                name="<?php echo self::ENABLE_RECAPTCHA_OPTION; ?>"
                                id="<?php echo self::ENABLE_RECAPTCHA_OPTION; ?>"
                                value="1"
							<?php checked( $this->_is_recaptcha_enabled() ); ?>>

						<?php esc_html_e( 'Enable reCAPTCHA', 'grc-v3-domain' ); ?>

                    </label>
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo self::SITE_KEY_OPTION; ?>" style="display: block; font-weight: bold;">
						<?php esc_html_e( 'Site Key', 'grc-v3-domain' ); ?>
                    </label>
                    <input
                            type="text"
                            name="<?php echo self::SITE_KEY_OPTION; ?>"
                            id="<?php echo self::SITE_KEY_OPTION; ?>"
                            value="<?php echo esc_attr( $this->_get_recaptcha_site_key() ); ?>"
                            class="regular-text"
                            style="width: 100%; max-width: 600px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo self::SECRET_KEY_OPTION; ?>" style="display: block; font-weight: bold;">
						<?php esc_html_e( 'Secret Key', 'grc-v3-domain' ); ?>
                    </label>
                    <input
                            type="text"
                            name="<?php echo self::SECRET_KEY_OPTION; ?>"
                            id="<?php echo self::SECRET_KEY_OPTION; ?>"
                            value="<?php echo esc_attr( $this->_get_recaptcha_secret_key() ); ?>"
                            class="regular-text"
                            style="width: 100%; max-width: 600px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label for="<?php echo self::SCORE_OPTION; ?>" style="display: block; font-weight: bold;">
						<?php esc_html_e( 'Minimum Score', 'grc-v3-domain' ); ?>
                    </label>
                    <input
                            type="number"
                            name="<?php echo self::SCORE_OPTION; ?>"
                            id="<?php echo self::SCORE_OPTION; ?>"
                            value="<?php echo esc_attr( $this->_get_recaptcha_required_score() ); ?>"
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
}