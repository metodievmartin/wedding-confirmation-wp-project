<?php

class Contact_Form_Main {

	// ========== Constants ==========

	// Custom Post Type
	const SUBMISSION_CPT_SLUG = 'form-submission';

	// Rest
	const NAMESPACE = 'confirmation/v1';

	private static $instance = null;

	private $submission_cpt;
	private $rest_endpoints;
	private $submission_admin;

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
		//	Init Custom Post Types
		wccf_include( 'includes/contact-form/class-contact-form-cpt.php' );

		//	Init Custom Rest Endpoints
		wccf_include( 'includes/contact-form/class-contact-form-rest.php' );
		$rest_endpoints = Contact_Form_Rest::init( self::NAMESPACE, self::SUBMISSION_CPT_SLUG );
	}
}