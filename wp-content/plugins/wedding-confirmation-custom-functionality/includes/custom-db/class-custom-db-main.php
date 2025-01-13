<?php

class Custom_DB_Main {

	// ========== Constants ==========

	// Custom DB
	const DB_TABLE_NAME = 'confirmations';

	// ========== Properties ==========

	private $confirmations_custom_db;

	// ========== Constructor ==========

	public function __construct() {
		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		// Init Custom Database
		wccf_include( 'includes/custom-db/class-confirmations-custom-db.php' );
		$this->confirmations_custom_db = new Confirmations_Custom_DB( self::DB_TABLE_NAME );
	}

	public function get_confirmations_db_instance() {
		return $this->confirmations_custom_db;
	}

	public function on_activation_hook() {
		$this->confirmations_custom_db->on_activation_hook();
	}
}