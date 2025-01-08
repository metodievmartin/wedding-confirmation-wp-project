<?php
/**
 * Plugin Name: Custom Google reCAPTCHA v3
 * Description: A class-based plugin to handle Google reCAPTCHA v3 with customisable score and API keys.
 * Version: 1.0
 * Author: Martin Metodiev
 */

// Exit if this file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Google_Recaptcha_Main {
	private static $instance = null;

	private $grc_core = null;
	public $grc_public_api = null;

	// ========== Static Methods ==========

	/**
	 * Initialises the functionality and makes sure it's done only once.
	 *
	 * @return Google_Recaptcha_Main
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
	 * @return Google_Recaptcha_Main|null
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

	/**
	 * Initialise hooks and load dependencies.
	 */
	private function initialise() {
		// Define constants.
		$this->define( 'GRC_PLUGIN', true );
		$this->define( 'GRC_DIR_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'GRC_DIR_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'GRC_BASENAME', plugin_basename( __FILE__ ) );

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'plugin_activated' ) );

		// Include utility functions.
		include_once GRC_DIR_PATH . 'includes/utils/grc-utility-functions.php';

		// Include classes.
		grc_include( 'includes/traits/trait-grc-settings.php' );
		grc_include( 'includes/core/class-grc-core.php' );
		grc_include( 'includes/public/class-grc-public-api.php' );

		$this->grc_core       = GRC_CORE::init();
		$this->grc_public_api = GRC_PUBLIC_API::init();
	}

	/**
	 * Activation hook.
	 */
	public function plugin_activated() {
		flush_rewrite_rules();
	}

	/**
	 * Deactivation hook.
	 */
	public function plugin_deactivated() {
		flush_rewrite_rules();
	}

	/**
	 * Defines a constant if doesn't already exist.
	 *
	 * @param string $name The constant name.
	 * @param mixed $value The constant value.
	 *
	 * @return void
	 */
	public function define( $name, $value = true ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}

/**
 * The main function responsible for initialising and returning the single BrandIt_Custom_Functionality Instance to functions everywhere.
 *
 * @return  Google_Recaptcha_Main
 */
function recaptcha_public_api() {
	$recaptcha_main = Google_Recaptcha_Main::get_instance();

	// Instantiate only once.
	if ( ! $recaptcha_main ) {
		$recaptcha_main = Google_Recaptcha_Main::init();
	}

	return $recaptcha_main->grc_public_api;
}

// Instantiate.
recaptcha_public_api();