<?php

// Exit if this file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Contact_Form_DB {

	private $charset;
	private $full_table_name;

	// ========== Constructor ==========

	public function __construct( $table_name ) {
		global $wpdb;
		$this->charset         = $wpdb->get_charset_collate();
		$this->full_table_name = $wpdb->prefix . $table_name;
	}

	public function on_activation_hook() {
		$this->_create_wedding_confirmations_table();
	}

	// ========== Setup Methods (Hook callbacks) ==========

	private function _create_wedding_confirmations_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // Required for dbDelta

		$sql = "CREATE TABLE {$this->full_table_name} (
	        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	        first_name VARCHAR(100) NOT NULL,
	        last_name VARCHAR(100) NOT NULL,
	        email VARCHAR(100) NOT NULL,
	        num_guests TINYINT(3) UNSIGNED NOT NULL DEFAULT 1,
	        additional_info TEXT DEFAULT NULL,
	        invitation_code VARCHAR(50) DEFAULT NULL,
	        attendance_status ENUM('confirmed', 'declined', 'pending') DEFAULT 'pending',
	        date_of_confirmation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	        PRIMARY KEY (id)
	    ) {$this->charset};";

		dbDelta( $sql ); // Execute the query
	}

	/**
	 * @throws Exception
	 */
	public function insert_new_confirmation_in_db( $data ) {
		global $wpdb;

		// Sanitise and validate data
		$sanitised_data = [
			'first_name'      => sanitize_text_field( $data['first_name'] ?? '' ),
			'last_name'       => sanitize_text_field( $data['last_name'] ?? '' ),
			'email'           => sanitize_email( $data['email'] ?? '' ),
			'num_guests'      => absint( $data['num_guests'] ?? 1 ),
			'additional_info' => sanitize_textarea_field( $data['additional_info'] ?? '' ),
		];

		// Validate required fields
		if ( empty( $sanitised_data['first_name'] ) || empty( $sanitised_data['last_name'] ) || empty( $sanitised_data['email'] ) ) {
			throw new Exception( 'Name and email are required.' );
		}

		// Prepare the SQL query using placeholders
		$sql = $wpdb->prepare(
			"INSERT INTO {$this->full_table_name} (first_name, last_name, email, num_guests, additional_info) VALUES (%s, %s, %s, %d, %s)",
			$sanitised_data['first_name'],
			$sanitised_data['last_name'],
			$sanitised_data['email'],
			$sanitised_data['num_guests'],
			$sanitised_data['additional_info']
		);

		$result = $wpdb->query( $sql );

		if ( $result === false ) {
			throw new Exception( 'Database error: ' . $wpdb->last_error );
		}

		return $wpdb->insert_id; // Return the ID of the inserted row
	}
}