<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_add_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	// Custom Image Sizes
	add_image_size( 'hero-banner', 1920, 900, true );
	add_image_size( 'hero-banner-portrait', 900, 1120, true );
	add_image_size( 'page-banner', 1500, 350, true );
	add_image_size( 'about-section-portrait', 500, 700, true );

	// Navigation Menus
	register_nav_menus( array(
		'header-menu'            => __( 'Header Menu', '' ),
		'footer-page-links-menu' => __( 'Footer Page Links Menu', '' )
	) );
}

add_action( 'after_setup_theme', 'theme_add_support' );