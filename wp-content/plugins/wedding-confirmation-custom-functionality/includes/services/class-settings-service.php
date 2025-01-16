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

	public function save_confirmations_end_date( string $date ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE, $date );
	}

	public function save_confirmations_end_date_timezone( string $timezone ): bool {
		return update_option( self::CONFIRMATIONS_END_DATE_TIMEZONE, $timezone );
	}

}