<?php

/**
 * Returns the plugin path to a specified file.
 *
 * @param string $filename The specified file.
 *
 * @return  string
 */
function wccf_get_path( $filename = '' ) {
	return WCCF_DIR_PATH . ltrim( $filename, '/' );
}

/**
 * Includes a file within the WCCF plugin.
 *
 * @param string $filename The specified file.
 *
 * @return  void
 */
function wccf_include( $filename = '' ) {
	$file_path = wccf_get_path( $filename );
	if ( file_exists( $file_path ) ) {
		include_once $file_path;
	}
}