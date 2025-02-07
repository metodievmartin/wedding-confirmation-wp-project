<?php

// Exit if this file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Confirmations_Custom_DB {

	private $charset;
	public $full_table_name;

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

	public function fetch_confirmations( $args = [] ) {
		global $wpdb;

		$query = "SELECT * FROM {$this->full_table_name}";
		$where = [];

		if ( empty( $args ) ) {
			return $wpdb->get_results( $query, ARRAY_A );
		}

		// Add search condition
		if ( ! empty( $args['search_term'] ) ) {
			$where[] = $wpdb->prepare(
				"(first_name LIKE %s OR last_name LIKE %s OR email LIKE %s)",
				"%{$args['search_term']}%",
				"%{$args['search_term']}%",
				"%{$args['search_term']}%"
			);
		}

		// Add WHERE clause
		if ( ! empty( $where ) ) {
			$query .= " WHERE " . implode( ' AND ', $where );
		}

		// Add ORDER BY clause
		if ( ! empty( $args['order_by'] ) && ! empty( $args['order'] ) ) {
			// Validate order_by and order
			$valid_columns = [ 'first_name', 'last_name', 'num_guests', 'attendance_status' ];
			$valid_orders  = [ 'ASC', 'DESC' ];

			$order_by = in_array( $args['order_by'], $valid_columns, true ) ? $args['order_by'] : 'last_name';
			$order    = in_array( strtoupper( $args['order'] ), $valid_orders, true ) ? strtoupper( $args['order'] ) : 'ASC';

			// Add ORDER BY clause (validated inputs)
			$query .= " ORDER BY {$order_by} {$order}";
		}

		// Add LIMIT and OFFSET
		if ( ! empty( $args['limit'] ) ) {
			$query .= $wpdb->prepare( " LIMIT %d OFFSET %d", $args['limit'], $args['offset'] );
		}

		return $wpdb->get_results( $query, ARRAY_A );
	}

	public function get_count( $search_term = '' ) {
		global $wpdb;

		$total_rows_query = "SELECT COUNT(*) FROM {$this->full_table_name}";

		// Add search condition
		if ( ! empty( $search_term ) ) {
			$where[] = $wpdb->prepare(
				"(first_name LIKE %s OR last_name LIKE %s OR email LIKE %s)",
				"%{$search_term}%",
				"%{$search_term}%",
				"%{$search_term}%"
			);
		}

		// Add WHERE clause
		if ( ! empty( $where ) ) {
			$total_rows_query .= " WHERE " . implode( ' AND ', $where );
		}

		return $wpdb->get_var( $total_rows_query );
	}


	/**
	 * @throws Exception
	 */
	public function insert_new_confirmation_in_db( $data ) {
		global $wpdb;

		// Sanitise and validate data
		$sanitised_data = [
			'first_name'        => sanitize_text_field( $data['first_name'] ?? '' ),
			'last_name'         => sanitize_text_field( $data['last_name'] ?? '' ),
			'email'             => sanitize_email( $data['email'] ?? '' ),
			'num_guests'        => absint( $data['num_guests'] ?? 1 ),
			'additional_info'   => sanitize_textarea_field( $data['additional_info'] ?? '' ),
			'rsvp_confirmation' => filter_var( $data['rsvp_confirmation'], FILTER_VALIDATE_BOOLEAN ),
		];

		// Validate required fields
		if ( empty( $sanitised_data['first_name'] ) || empty( $sanitised_data['last_name'] ) || empty( $sanitised_data['email'] ) ) {
			throw new Exception( 'Name and email are required.' );
		}

		// Prepare the SQL query using placeholders
		$sql = $wpdb->prepare(
			"INSERT INTO {$this->full_table_name} (first_name, last_name, email, num_guests, attendance_status, additional_info) VALUES (%s, %s, %s, %d, %s, %s)",
			$sanitised_data['first_name'],
			$sanitised_data['last_name'],
			$sanitised_data['email'],
			$sanitised_data['num_guests'],
			$sanitised_data['rsvp_confirmation'] ? 'confirmed' : 'declined',
			$sanitised_data['additional_info']
		);

		$result = $wpdb->query( $sql );

		if ( $result === false ) {
			throw new Exception( 'Database error: ' . $wpdb->last_error );
		}

		return $wpdb->insert_id; // Return the ID of the inserted row
	}
}