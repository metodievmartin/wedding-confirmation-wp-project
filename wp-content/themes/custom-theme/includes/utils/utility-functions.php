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