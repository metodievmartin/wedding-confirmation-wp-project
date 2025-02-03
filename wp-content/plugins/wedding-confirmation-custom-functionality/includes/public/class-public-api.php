<?php

class WCCF_Public_API {
	private array $services;
	private array $instances;

	/**
	 * Constructor for the public API.
	 *
	 * @param $main_instance
	 */
	public function __construct( $main_instance ) {
		if ( ! empty( $main_instance->services ) ) {
			$this->services = $main_instance->services;
		}

		if ( ! empty( $main_instance->instances ) ) {
			$this->instances = $main_instance->instances;
		}
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

	public function get_selected_colour() {
		if ( empty( $this->services['settings'] ) ) {
			return array( 'hex' => '', 'name' => '' );
		}

		return $this->services['settings']->get_selected_colour();
	}

	public function get_colour_data_attribute(): string {
		if ( empty( $this->services['settings'] ) ) {
			return '';
		}

		return $this->services['settings']->get_colour_data_attribute();
	}

	public function get_info_cards( $query_args = array() ) {
		if ( empty( $this->instances['info-card'] ) ) {
			// Return an empty WP_Query instance
			return new WP_Query( array( 'post__in' => array( 0 ) ) );
		}

		return $this->instances['info-card']->get_info_cards_query( $query_args );
	}
}