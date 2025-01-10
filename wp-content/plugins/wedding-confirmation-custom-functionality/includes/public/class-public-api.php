<?php

class WCCF_Public_API {
	private $plugin_instance;

	/**
	 * Constructor for the public API.
	 *
	 * @param Wedding_Confirmation_Custom_Functionality $plugin_instance
	 */
	public function __construct( Wedding_Confirmation_Custom_Functionality $plugin_instance ) {
		$this->plugin_instance = $plugin_instance;
	}

	public function get_countdown_date() {
		return '2025-01-24 17:30:00';
	}

	// Add more methods here to expose additional functionality if needed.
}