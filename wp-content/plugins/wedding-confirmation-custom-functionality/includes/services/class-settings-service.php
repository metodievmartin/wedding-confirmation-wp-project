<?php

class WCCF_Settings_Service {
	public const CONFIRMATIONS_END_DATE = 'wccf_rsvp_end_date_utc';
	public const CONFIRMATIONS_END_DATE_TIMEZONE = 'wccf_rsvp_end_date_timezone';

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

	public function save_confirmations_end_date( string $date ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE, $date );
	}

	public function save_confirmations_end_date_timezone( string $timezone ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE_TIMEZONE, $timezone );
	}

}