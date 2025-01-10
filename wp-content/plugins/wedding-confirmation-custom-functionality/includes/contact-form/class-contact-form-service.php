<?php

class Contact_Form_Service {
	private $db;

	public function __construct( Contact_Form_DB $db ) {
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