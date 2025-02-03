<?php

class WCCF_Settings_Service {
	public const CONFIRMATIONS_END_DATE = 'wccf_rsvp_end_date_utc';
	public const CONFIRMATIONS_END_DATE_TIMEZONE = 'wccf_rsvp_end_date_timezone';
	public const COLOUR_PICKER_OPTION = 'wccf_colour_picker_option';

	public function get_confirmations_end_date( $convert_to_local_time = true ): ?DateTime {
		$date_time_utc = get_option( self::CONFIRMATIONS_END_DATE, '' ); // UTC datetime from DB
		$timezone      = get_option( self::CONFIRMATIONS_END_DATE_TIMEZONE, 'UTC' ); // Timezone from DB

		if ( $date_time_utc ) {
			try {
				// Create DateTime object from UTC datetime
				$date = new DateTime( $date_time_utc, new DateTimeZone( 'UTC' ) );

				if ( $convert_to_local_time ) {
					// Convert to the stored timezone
					$date->setTimezone( new DateTimeZone( $timezone ) );
				}

				return $date;
			} catch ( Exception $e ) {
				error_log( $e->getMessage() );

				return null;
			}
		}

		return null;
	}

	public function get_confirmations_end_date_formatted( string $format, bool $convert_to_local_time = true ): string {
		$date = $this->get_confirmations_end_date( $convert_to_local_time );

		if ( empty( $date ) || empty( $format ) ) {
			return '';
		}

		return $date->format( $format );
	}


	/**
	 * Check if the end date has already passed.
	 *
	 * @return bool True if the end date has passed, false otherwise.
	 */
	public function has_confirmations_end_date_passed(): bool {
		$end_date = get_option( self::CONFIRMATIONS_END_DATE );
		$timezone = get_option( self::CONFIRMATIONS_END_DATE_TIMEZONE );

		if ( empty( $end_date ) || empty( $timezone ) ) {
			// If the end date or timezone is not set, assume it hasn't passed
			return false;
		}

		try {
			$tz_object         = new DateTimeZone( $timezone );
			$end_date_time     = new DateTime( $end_date, $tz_object );
			$current_date_time = new DateTime( 'now', $tz_object );

			// Compare the current time with the end date
			return $current_date_time > $end_date_time;
		} catch ( Exception $e ) {
			error_log( 'Error checking end date: ' . $e->getMessage() );

			return false;
		}
	}

	public function get_selected_colour() {
		$saved_colour = get_option( self::COLOUR_PICKER_OPTION, '' );

		return $this->get_colour_object( $saved_colour );
	}


	public function get_colour_data_attribute() {
		$saved_colour = get_option( self::COLOUR_PICKER_OPTION, '' );
		$colour_data  = $this->get_colour_object( $saved_colour );

		// Replace spaces with dashes and convert to lowercase for consistency
		$formatted_name = strtolower( str_replace( ' ', '-', $colour_data['name'] ) );

		return sprintf( 'data-colour="%s"', esc_attr( $formatted_name ) );
	}

	public function save_confirmations_end_date( string $date ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE, $date );
	}

	public function save_confirmations_end_date_timezone( string $timezone ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE_TIMEZONE, $timezone );
	}

	public function save_selected_colour( string $colour ): bool {
		return update_option( self::COLOUR_PICKER_OPTION, $colour );
	}

	public function get_all_colours() {
		return array(
			'wccf-red'         => array(
				'name' => __( 'Red', 'wccf-domain' ),
				'id'   => 'wccf-red',
				'hex'  => '#ff0000'
			),
			'wccf-pink'        => array(
				'name' => __( 'Pink', 'wccf-domain' ),
				'id'   => 'wccf-pink',
				'hex'  => '#E3959E'
			),
			'wccf-deep-blue'   => array(
				'name' => __( 'Deep Blue', 'wccf-domain' ),
				'id'   => 'wccf-deep-blue',
				'hex'  => '#07295E'
			),
			'wccf-wine'        => array(
				'name' => __( 'Wine', 'wccf-domain' ),
				'id'   => 'wccf-wine',
				'hex'  => '#792225'
			),
			'wccf-peach'       => array(
				'name' => __( 'Peach', 'wccf-domain' ),
				'id'   => 'wccf-peach',
				'hex'  => '#E56667'
			),
			'wccf-burgundy'    => array(
				'name' => __( 'Burgundy', 'wccf-domain' ),
				'id'   => 'wccf-burgundy',
				'hex'  => '#50112E'
			),
			'wccf-dark-green'  => array(
				'name' => __( 'Dark Green', 'wccf-domain' ),
				'id'   => 'wccf-dark-green',
				'hex'  => '#0C3B3C'
			),
			'wccf-taupe'       => array(
				'name' => __( 'Taupe', 'wccf-domain' ),
				'id'   => 'wccf-taupe',
				'hex'  => '#987462'
			),
			'wccf-deep-purple' => array(
				'name' => __( 'Deep Purple', 'wccf-domain' ),
				'id'   => 'wccf-deep-purple',
				'hex'  => '#310057'
			),
		);
	}

	public function get_default_colour_object() {
		return array(
			'name' => __( 'Red', 'wccf-domain' ),
			'id'   => 'wccf-red',
			'hex'  => '#ff0000'
		);
	}

	public function get_colour_object( $colour_id ) {
		$colours = $this->get_all_colours();

		if ( ! $colour_id || ! isset( $colours[ $colour_id ] ) ) {
			return $this->get_default_colour_object();
		}

		return $colours[ $colour_id ];
	}
}