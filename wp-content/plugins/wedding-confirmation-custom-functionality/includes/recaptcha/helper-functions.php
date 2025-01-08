<?php

/**
 * Check if Google reCAPTCHA is enabled.
 *
 * This function checks if the recaptcha_public_api function exists and calls the
 * is_recaptcha_enabled method on the public API class, if available.
 *
 * @return bool True if reCAPTCHA is enabled, false otherwise.
 */
function wccf_is_recaptcha_enabled() {
	if ( function_exists( 'recaptcha_public_api' ) ) {
		$recaptcha_api = recaptcha_public_api();

		if ( method_exists( $recaptcha_api, 'is_enabled' ) ) {
			return $recaptcha_api->is_enabled();
		} else {
			error_log( 'The is_enabled method is not available in recaptcha_public_api.' );
		}
	} else {
		error_log( 'The recaptcha_public_api function is not available.' );
	}

	return false; // Default to false if something goes wrong
}

/**
 * Validate the Google reCAPTCHA token.
 *
 * This function validates the provided reCAPTCHA token using the public API of the Google reCAPTCHA plugin.
 * It checks if the `recaptcha_public_api` function and `validate_recaptcha` method are available before proceeding.
 *
 * @param string $token The reCAPTCHA token to validate.
 *
 * @return bool True if the token is valid, false otherwise.
 */
function wccf_validate_recaptcha( $token ) {
	if ( function_exists( 'recaptcha_public_api' ) ) {
		$recaptcha_api = recaptcha_public_api();

		if ( method_exists( $recaptcha_api, 'validate_recaptcha' ) ) {
			return $recaptcha_api->validate_recaptcha( $token );
		} else {
			error_log( 'The validate_recaptcha method is not available in recaptcha_public_api.' );
		}
	} else {
		error_log( 'The recaptcha_public_api function is not available.' );
	}

	return false; // Default to invalid if something goes wrong
}