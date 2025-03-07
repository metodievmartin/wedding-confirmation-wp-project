<?php

/**
 * Adds Open Graph (OG) meta tags to the page header.
 *
 * This function generates and outputs OG meta tags for the current WordPress post or page.
 * It helps social media platforms display the correct title, description, and image when
 * a link to the website is shared.
 *
 * Meta tags included:
 * - og:title: The title of the page.
 * - og:description: The excerpt of the page.
 * - og:image: The featured image of the page (or a fallback default image if none is set).
 * - og:url: The canonical URL of the page.
 * - og:type: The type of content (defaults to "website").
 *
 * Notes:
 * - If the page does not have a featured image, a default image is used.
 * - Uses WordPress functions `get_the_title()`, `get_the_excerpt()`, `get_the_post_thumbnail_url()`, and `get_permalink()`.
 * - The function is hooked into `wp_head`, ensuring it runs in the correct context.
 *
 * @return void Outputs the OG meta tags directly in the page head.
 */
function add_og_meta_tags() {
	global $post;

	if ( empty( $post ) || empty( $post->ID ) ) {
		return;
	}

	$og_image = get_the_post_thumbnail_url( $post->ID, 'full' );

	if ( ! $og_image ) {
		$og_image = get_asset_image_url( 'default-featured-image.jpg' );
	}

	echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( get_the_excerpt() ) . '" />' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
	echo '<meta property="og:type" content="website" />' . "\n";
}

add_action( 'wp_head', 'add_og_meta_tags' );
