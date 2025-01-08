<?php

/**
 * Returns the plugin path to a specified file.
 *
 * @param string $filename The specified file.
 *
 * @return  string
 */
function grc_get_path( $filename = '' ) {
	return GRC_DIR_PATH . ltrim( $filename, '/' );
}

/**
 * Includes a file within the GRC plugin.
 *
 * @param string $filename The specified file.
 *
 * @return  void
 */
function grc_include( $filename = '' ) {
	$file_path = grc_get_path( $filename );
	if ( file_exists( $file_path ) ) {
		include_once $file_path;
	}
}