<?php

class Contact_Form_Service {
	private $db;

	public function __construct( Contact_Form_DB $db ) {
		$this->db = $db;
	}

	public function save_form_submission_in_db( $data ) {
		return $this->db->insert_new_confirmation_in_db( $data );
	}
}