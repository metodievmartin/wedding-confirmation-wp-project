<?php

class WCCF_Public_API {
	private array $services;

	/**
	 * Constructor for the public API.
	 *
	 * @param array $services
	 */
	public function __construct( $services ) {
		$this->services = $services;
	}

	public function get_confirmations_end_date( bool $convert_to_local_time = true ): ?DateTime {
		if ( empty( $this->services['settings'] ) ) {
			return null;
		}

		return $this->services['settings']->get_confirmations_end_date( $convert_to_local_time );
	}

	public function get_confirmations_end_date_formatted( string $format, bool $convert_to_local_time = true ): string {
		if ( empty( $this->services['settings'] ) ) {
			return '';
		}

		return $this->services['settings']->get_confirmations_end_date_formatted( $format, $convert_to_local_time );
	}

	public function has_end_date_passed(): bool {
		if ( empty( $this->services['settings'] ) ) {
			return false;
		}

		return $this->services['settings']->has_confirmations_end_date_passed();
	}

	// Add more methods here to expose additional functionality if needed.
}