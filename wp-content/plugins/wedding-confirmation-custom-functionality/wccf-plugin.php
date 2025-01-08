<?php

/*
 * Plugin Name: Wedding Confirmation Custom Functionality Plugin
 * Description: This plugin adds additional functionality and Custom Post Types like Services, etc.
 * Version: 1.0
 * Author: Martin Metodiev
 * Author URI: https://github.com/metodievmartin
 * Text Domain: wccf_domain
 * Domain Path: /languages
 */

// Exit if this file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wedding_Confirmation_Custom_Functionality {
	public $contact_form_main = null;

	/**
	 * Main constructor.
	 */
	public function __construct() {
	}

	/**
	 * Initialise hooks and load dependencies.
	 */
	public function initialise() {
		// Define constants.
		$this->define( 'WCCF', true );
		$this->define( 'WCCF_DIR_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'WCCF_DIR_URL', plugin_dir_url( __FILE__ ) );
		$this->define( 'WCCF_BASENAME', plugin_basename( __FILE__ ) );

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'wccf_plugin_activated' ) );

		// Include main utility functions
		// It's important to come BEFORE anything else
		include_once WCCF_DIR_PATH . 'includes/wccf-utility-functions.php';

		// Include helpers
		wccf_include( 'includes/recaptcha/helper-functions.php' );

		// Include classes
		wccf_include( 'includes/contact-form/class-contact-form-main.php' );

		$this->contact_form_main = Contact_Form_Main::init();
	}

	/**
	 * Activation hook.
	 */
	public function wccf_plugin_activated() {
		flush_rewrite_rules();
	}

	/**
	 * Deactivation hook.
	 */
	public function wccf_plugin_deactivated() {
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
 * @return  Wedding_Confirmation_Custom_Functionality
 */
function wccf() {
	global $wccf;

	// Instantiate only once.
	if ( ! isset( $wccf ) ) {
		$wccf = new Wedding_Confirmation_Custom_Functionality();
		$wccf->initialise();
	}

	return $wccf;
}

// Instantiate.
wccf();