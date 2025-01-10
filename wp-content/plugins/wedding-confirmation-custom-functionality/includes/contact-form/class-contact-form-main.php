<?php

class Contact_Form_Main {

	// ========== Constants ==========

	// Custom Post Type
	const SUBMISSION_CPT_SLUG = 'form-submission';

	// Rest
	const NAMESPACE = 'confirmation/v1';

	// Custom DB
	const DB_TABLE_NAME = 'confirmations';

	private static $instance = null;

	// ========== Static Methods ==========

	/**
	 * Initialises the functionality and makes sure it's done only once.
	 *
	 * @return Contact_Form_Main
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
	 * @return Contact_Form_Main|null
	 */
	public static function get_instance() {
		return self::$instance;
	}

	// ========== Constructor ==========

	private function __construct() {
		$this->initialise();
	}

	// ========== Init ==========

	private function initialise() {
		//	Init Custom Database
		wccf_include( 'includes/contact-form/class-contact-form-db.php' );
		Contact_Form_DB::init( WCCF_PLUGIN_FILE, self::DB_TABLE_NAME );

		//	Init Custom Rest Endpoints
		wccf_include( 'includes/contact-form/class-contact-form-rest.php' );
		Contact_Form_Rest::init( self::NAMESPACE, self::SUBMISSION_CPT_SLUG );
	}
}