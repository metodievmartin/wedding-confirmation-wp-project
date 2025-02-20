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

	public function get_all_confirmations() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			return array();
		}

		return $this->db->fetch_confirmations();
	}

	public function get_confirmations( $query_args = array() ) {
		if ( ! current_user_can( 'edit_pages' ) ) {
			return array();
		}

		$defaults = array(
			'search_term' => '',
			'order_by'    => 'last_name',
			'order'       => 'ASC',
			'limit'       => 10,
			'offset'      => 0,
		);

		$query_args = wp_parse_args( $query_args, $defaults );

		return $this->db->fetch_confirmations( $query_args );
	}

	public function get_total_confirmations_count( $search_term = '' ) {
		return $this->db->get_count( $search_term );
	}
}