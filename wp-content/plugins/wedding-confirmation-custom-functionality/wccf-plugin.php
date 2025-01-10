<?php

/*
 * Plugin Name: Wedding Confirmation Custom Functionality Plugin
 * Description: Provides the all functionality to manage wedding confirmations with a plugin that integrates a custom contact form, REST API endpoints, and database handling for efficient guest response tracking.
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
	public $instances = array();

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
		$this->define( 'WCCF_PLUGIN_FILE', __FILE__ );
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
		wccf_include( 'includes/public/class-public-api.php' );

		// Initialise each instance
		$this->instances['contact_form'] = new Contact_Form_Main();
	}

	/**
	 * Handles the plugin activation process.
	 *
	 * This method is triggered when the plugin is activated. It iterates through
	 * all registered instances stored in `$this->instances` and checks if they
	 * have an `on_activation_hook` method. If the method exists, it is called,
	 * allowing individual components to perform their activation-specific logic
	 * (e.g. setting up database tables, default options, etc.).
	 *
	 * Finally, `flush_rewrite_rules()` is called to ensure that WordPress's
	 * rewrite rules are updated, accounting for any custom post types, taxonomies,
	 * or routes that may have been registered during activation.
	 */
	public function wccf_plugin_activated() {
		foreach ( $this->instances as $instance ) {
			if ( method_exists( $instance, 'on_activation_hook' ) ) {
				$instance->on_activation_hook();
			}
		}

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
 * The main function responsible for initialising and returning the single WCCF_Public_API Instance to functions everywhere.
 *
 * @return  WCCF_Public_API
 */
function wccf(): WCCF_Public_API {
	global $wccf, $wccf_api;

	if ( ! isset( $wccf ) ) {
		$wccf = new Wedding_Confirmation_Custom_Functionality();
		$wccf->initialise();
	}

	if ( ! isset( $wccf_api ) ) {
		$wccf_api = new WCCF_Public_API( $wccf );
	}

	return $wccf_api;
}

// Instantiate.
wccf();