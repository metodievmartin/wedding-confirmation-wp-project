<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GRC_Public_API {
	use GRC_Settings_Trait;

	private static $instance = null;

	// ========== Static Methods ==========

	/**
	 * Initialises the functionality and makes sure it's done only once.
	 *
	 * @return GRC_Public_API
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the singleton instance of the plugin.
	 *
	 * @return GRC_Public_API|null
	 */
	public static function get_instance() {
		return self::$instance;
	}

	/**
	 * Main constructor.
	 */
	private function __construct() {
		$this->initialise();
	}

	private function initialise() {

	}

	public function is_enabled() {
		return $this->_is_recaptcha_enabled();
	}

	public function get_site_key() {
		return $this->_get_recaptcha_site_key();
	}

	public function get_required_score() {
		return $this->_get_recaptcha_required_score();
	}

	public function get_secret_key() {
		return $this->_get_recaptcha_secret_key();
	}

	/**
	 * Validate the reCAPTCHA response
	 */
	public function validate_recaptcha( $token ) {
		$secret_key     = $this->get_secret_key();
		$required_score = $this->get_required_score();

		if ( empty( $token ) || empty( $secret_key ) ) {
			return false;
		}

		$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
			'body' => array(
				'secret'   => $secret_key,
				'response' => $token,
			),
		) );

		$response_body      = wp_remote_retrieve_body( $response );
		$recaptcha_response = json_decode( $response_body, true );

		if ( empty( $recaptcha_response['success'] ) || $recaptcha_response['score'] < $required_score ) {
			return false;
		}

		return true;
	}
}