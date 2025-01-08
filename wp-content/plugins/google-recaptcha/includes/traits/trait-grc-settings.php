<?php

trait GRC_Settings_Trait {
	public const PLUGIN_SLUG = 'wp-recaptcha-v3';
	public const ENABLE_RECAPTCHA_OPTION = 'wp_recaptcha_v3_enabled';
	public const SITE_KEY_OPTION = 'wp_recaptcha_v3_site_key';
	public const SECRET_KEY_OPTION = 'wp_recaptcha_v3_secret_key';
	public const SCORE_OPTION = 'wp_recaptcha_v3_score';

	public function _is_recaptcha_enabled() {
		return (bool) get_option( self::ENABLE_RECAPTCHA_OPTION, 0 );
	}

	public function _get_recaptcha_site_key() {
		return get_option( self::SITE_KEY_OPTION, '' );
	}

	public function _get_recaptcha_required_score() {
		return get_option( self::SCORE_OPTION, 0.5 );
	}

	public function _get_recaptcha_secret_key() {
		return get_option( self::SECRET_KEY_OPTION, '' );
	}
}