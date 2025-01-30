<?php

function get_excerpt_or_first_n_words( $number_of_words = 10 ) {
	if ( has_excerpt() ) {
		return get_the_excerpt();
	}

	return wp_trim_words( get_the_content(), $number_of_words );
}

/**
 * Get the full URL of a theme asset.
 *
 * This function returns the full URL for an asset located in the theme's `assets` folder.
 * It ensures that the provided path is cleaned up and properly formatted.
 *
 * @param string $path The relative path to the asset within the `assets` folder.
 *                     Example: 'images/example.jpg' or '/images/example.jpg'.
 *
 * @return string The full URL of the requested asset.
 */
function get_theme_asset_url( $path ) {
	// Ensure the path doesn't start with a slash
	$path = ltrim( $path, '/' );

	return get_template_directory_uri() . '/assets/' . $path;
}

function get_asset_image_url( $image_name ) {
	return get_theme_asset_url( 'img/' . $image_name );
}

/**
 * Sanitises content by allowing only specific HTML tags and attributes.
 *
 * @param string $content The content to be sanitised.
 *
 * @return string Sanitised content with only allowed HTML.
 */
function sanitize_allowed_html( $content ) {
	// Define the allowed HTML tags and attributes.
	$allowed_tags = array(
		'p'      => array(),
		'br'     => array(),
		'strong' => array(),
		'em'     => array(),
		'ul'     => array(),
		'ol'     => array(),
		'li'     => array(),
		'span'   => array(
			'style' => array(), // inline styles for spans.
		),
	);

	// Use wp_kses to sanitise the content.
	return wp_kses( $content, $allowed_tags );
}

/**
 * Format a date or time string.
 *
 * This function formats a raw date or time string according to the specified format
 * and the site's locale. It works for both dates and times.
 *
 * @param string $raw_value The raw date or time string. Use 'Y-m-d' format.
 * @param string $format Optional. The format for display. Default is 'F j, Y' for dates.
 *
 * @return string The formatted date, or an empty string if the input date is invalid.
 */
function custom_format_datetime( $raw_value, $format = 'F j, Y' ) {
	// Validate and convert the raw date into a timestamp
	$timestamp = strtotime( $raw_value );

	if ( ! $timestamp ) {
		return '';
	}

	// Format the date according to the site's locale
	return date_i18n( $format, $timestamp );
}