<?php

function get_excerpt_or_first_n_words( $number_of_words = 10 ) {
	if ( has_excerpt() ) {
		return get_the_excerpt();
	}

	return wp_trim_words( get_the_content(), $number_of_words );
}

function display_service_category_buttons( $active_term_slug, $show_all_services = true ) {
	// Fetch terms from the custom taxonomy 'service-category'
	$terms = get_terms( array(
		'taxonomy'   => get_service_category_slug(),
		'hide_empty' => false, // Do not include categories with no posts
	) );

	// Check if terms are available
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		echo '<div class="d-flex flex-wrap justify-content-center gap-3">';

		if ( $show_all_services ) {
			// Print the button to all services
			render_category_button(
				__( 'All Services', 'brandit' ),
				'all-services',
				site_url( '/services' ),
				$active_term_slug
			);
		}

		// Loop through terms to create buttons
		foreach ( $terms as $term ) {
			// Pass individual values to the rendering function
			render_category_button(
				$term->name,
				$term->slug,
				get_term_link( $term ),
				$active_term_slug
			);
		}

		echo '</div>';
	}
}

function render_category_button( $name, $slug, $link, $current_term_slug ) {
	// Check if the term is currently active
	$is_term_active = $current_term_slug === $slug;

	// Build classes array
	$classes = array(
		'btn',
		'btn-outline-primary',
		'service-category-btn',
		'text-dark'
	);

	// Add 'active' class if this term is currently active
	if ( $is_term_active ) {
		$classes[] = 'active';
	}

	// Convert the classes array to a space-separated string
	$class_string = implode( ' ', array_map( 'esc_attr', $classes ) );

	// Print the button
	echo '<a href="' . esc_url( $link ) . '" class="' . $class_string . '">';
	echo esc_html( $name );
	echo '</a>';
}

/**
 * Replace a placeholder in the title with a predefined value.
 *
 * @param string $title The title string containing a placeholder.
 *
 * @return string The title with the placeholder replaced, or the original title if no placeholder is found.
 */
function replace_title_placeholder( $title ) {
	$sanitized_title = esc_html( $title );
	$variation_1     = 'Brand<span class="text-primary">It</span>';
	$variation_2     = '<span class="text-primary">Brand</span>It';

	if ( str_contains( $sanitized_title, '{{TITLE_VAR_1}}' ) ) {
		return str_replace( '{{TITLE_VAR_1}}', $variation_1, $sanitized_title );
	}

	if ( str_contains( $sanitized_title, '{{TITLE_VAR_2}}' ) ) {
		return str_replace( '{{TITLE_VAR_2}}', $variation_2, $sanitized_title );
	}

	// Return the original title if no placeholder is found
	return $sanitized_title;
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