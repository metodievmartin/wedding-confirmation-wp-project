<?php

class Confirmations_DB_Service {
	private $db;

	public function __construct( Confirmations_Custom_DB $db ) {
		$this->db = $db;
	}

	/**
	 * @throws Exception
	 */
	public function save_confirmation_in_db( $confirmation_details ) {
		if ( empty( $this->db ) || ! method_exists( $this->db, 'insert_new_confirmation_in_db' ) || empty( $confirmation_details ) ) {
			throw new Exception( 'Missing configuration parameters or data!' );
		}

		return $this->db->insert_new_confirmation_in_db( $confirmation_details );
	}
}