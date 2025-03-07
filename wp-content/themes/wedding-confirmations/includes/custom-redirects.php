<?php

/**
 * Disable archive pages and single posts by redirecting to the homepage.
 *
 * This function checks if the current page is an archive (category, tag, author, date, etc.)
 * or a single post. If it matches, it performs a 301 permanent redirect to the homepage.
 *
 * @return void
 */
function disable_archives_and_posts_redirect() {
	if ( is_archive() || is_single() ) {
		wp_redirect( home_url(), 301 ); // 301 = Permanent Redirect
		exit;
	}
}

add_action( 'template_redirect', 'disable_archives_and_posts_redirect' );
