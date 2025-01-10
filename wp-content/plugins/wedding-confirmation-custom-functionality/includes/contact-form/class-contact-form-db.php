<?php

// Exit if this file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Contact_Form_DB {

	private static $instance = null;
	private $charset;
	private $full_table_name;

	/**
	 * Initialise the Contact_Form_DB class as a singleton.
	 *
	 * This method ensures that the class is instantiated only once and provides access
	 * to the single instance. It also sets up the database table name and handles
	 * the plugin activation hook for creating the custom database table.
	 *
	 * @param string $plugin_file Full path to the main plugin file (e.g. __FILE__). Used for registering activation hooks.
	 * @param string $table_name The custom database table name (without prefix).
	 *
	 * @return Contact_Form_DB The singleton instance of the Contact_Form_DB class.
	 */
	public static function init( $plugin_file, $table_name ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $plugin_file, $table_name );
		}

		return self::$instance;
	}

	/**
	 * Get the singleton instance of the plugin.
	 *
	 * @return Contact_Form_DB|null
	 */
	public static function get_instance() {
		return self::$instance;
	}

	// ========== Constructor ==========

	private function __construct( $plugin_file, $table_name ) {
		global $wpdb;
		$this->charset         = $wpdb->get_charset_collate();
		$this->full_table_name = $wpdb->prefix . $table_name;

		// Init functionality
		$this->initialise( $plugin_file );
	}

	private function initialise( $plugin_file ) {
		//	Register Custom Post Types
		register_activation_hook( $plugin_file, array( $this, 'create_wedding_confirmations_table' ) );
	}


	// ========== Setup Methods (Hook callbacks) ==========

	function create_wedding_confirmations_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // Required for dbDelta

		$sql = "CREATE TABLE {$this->full_table_name} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        num_guests TINYINT(3) UNSIGNED NOT NULL DEFAULT 1,
        additional_info TEXT DEFAULT NULL,
        invitation_code VARCHAR(50) DEFAULT NULL,
        attendance_status ENUM('Confirmed', 'Declined', 'Pending') DEFAULT 'Pending',
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

		if ( $wpdb->last_error ) {
			throw new Exception( 'Database error: ' . $wpdb->last_error );
		}

		return $wpdb->insert_id; // Return the ID of the inserted row
	}
}