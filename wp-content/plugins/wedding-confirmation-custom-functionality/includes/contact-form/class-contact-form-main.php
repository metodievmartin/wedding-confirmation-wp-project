<?php

class Contact_Form_Main {

	// ========== Constants ==========

	// Rest
	const NAMESPACE = 'confirmation/v1';

	// Custom DB
	const DB_TABLE_NAME = 'confirmations';

	// ========== Properties ==========

	private $custom_db;
	private $rest_api;
	public $service;

	// ========== Constructor ==========

	public function __construct() {
		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		// Init Custom Database
		wccf_include( 'includes/contact-form/class-contact-form-db.php' );
		$this->custom_db = new Contact_Form_DB( self::DB_TABLE_NAME );

		// Init Service layer
		wccf_include( 'includes/contact-form/class-contact-form-service.php' );
		$this->service = new Contact_Form_Service( $this->custom_db );

		// Init Custom Rest Endpoints
		wccf_include( 'includes/contact-form/class-contact-form-rest.php' );
		$this->rest_api = new Contact_Form_Rest( self::NAMESPACE, $this->service );
	}

	public function on_activation_hook() {
		$this->custom_db->on_activation_hook();
	}
}