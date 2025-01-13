<?php

class Contact_Form_Main {

	// ========== Constants ==========

	// Rest
	const NAMESPACE = 'confirmation/v1';

	// ========== Properties ==========

	private $db_service;
	private $rest_api;

	// ========== Constructor ==========

	public function __construct( $db_service ) {
		$this->db_service = $db_service;
		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		// Init Custom Rest Endpoints
		wccf_include( 'includes/contact-form/class-contact-form-rest.php' );
		$this->rest_api = new Contact_Form_Rest( self::NAMESPACE, $this->db_service );
	}
}