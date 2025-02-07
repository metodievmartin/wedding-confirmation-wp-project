<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Contact_Form_Rest {

	// ========== Constants ==========

	const ROUTE_NAME = 'submit-form';

	// ========== Properties ==========

	private $namespace;
	private $service;

	// ========== Constructor ==========

	public function __construct( $namespace, $service ) {
		$this->namespace = $namespace;
		$this->service   = $service;

		// Init functionality
		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		add_action( 'rest_api_init', array( $this, 'register_contact_form_rest_route' ) );
	}

	public function register_contact_form_rest_route() {
		$route_args = array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'handle_contact_form_submission' ),
			'permission_callback' => array( $this, 'wccf_verify_nonce' ),  // Add nonce validation
		);

		register_rest_route( $this->namespace, self::ROUTE_NAME, $route_args );
	}

	function wccf_verify_nonce( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );

		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return rest_ensure_response( new WP_Error(
				'request_failed',
				'You are not allowed to perform this action',
				array( 'status' => 403 )
			) );
		}

		return true;
	}

	function handle_contact_form_submission( $request ) {
		$params = $request->get_params();

		// Extract parameters
		$guest_first_name  = sanitize_text_field( $params['guest_first_name'] ?? '' );
		$guest_last_name   = sanitize_text_field( $params['guest_last_name'] ?? '' );
		$guest_email       = sanitize_email( $params['guest_email'] ?? '' );
		$num_guests        = absint( $params['num_guests'] ?? 1 );
		$rsvp_confirmation = sanitize_textarea_field( $params['rsvp_confirmation'] ?? 'true' );
		$additional_info   = sanitize_textarea_field( $params['additional_info'] ?? '' );
		$recaptcha_token   = sanitize_text_field( $params['recaptcha_token'] ?? '' );
		$recaptcha_action  = sanitize_text_field( $params['recaptcha_action'] ?? '' );

		// Run the reCAPTCHA validation if the service is enabled
		if ( wccf_is_recaptcha_enabled() ) {
			if ( empty( $recaptcha_action ) || $recaptcha_action != 'guest_confirmation' || ! wccf_validate_recaptcha( $recaptcha_token ) ) {
				return rest_ensure_response( new WP_Error(
					'validation_failed',
					__( 'Validation failed.', 'wccf-domain' ),
					array( 'status' => 400 )
				) );
			}
		}

		// Validate required fields
		if ( empty( $guest_first_name ) || empty( $guest_last_name ) || empty( $guest_email ) ) {
			return rest_ensure_response( new WP_Error(
				'missing_fields',
				__( 'Required fields are missing.', 'wccf-domain' ),
				array( 'status' => 400, 'params' => $params )
			) );
		}

		// Validate email format
		if ( ! is_email( $guest_email ) ) {
			return rest_ensure_response( new WP_Error(
				'invalid_email',
				'The provided email address is not valid.',
				array( 'status' => 400 )
			) );
		}

		$confirmation_details = array(
			'first_name'        => $guest_first_name,
			'last_name'         => $guest_last_name,
			'email'             => $guest_email,
			'additional_info'   => $additional_info,
			'num_guests'        => $num_guests,
			'rsvp_confirmation' => filter_var( $rsvp_confirmation, FILTER_VALIDATE_BOOLEAN ),
		);

		try {
			$this->service->save_confirmation_in_db( $confirmation_details );
		} catch ( Exception $e ) {
			return rest_ensure_response( new WP_Error(
				'internal_error',
				'Something went wrong.',
				array( 'status' => 500 )
			) );
		}


		// Successful Response
		return rest_ensure_response( array(
			'success' => true,
			'message' => __( 'Form submitted successfully.', 'wccf-domain' ),
		) );
	}

}
