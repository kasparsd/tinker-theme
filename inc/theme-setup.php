<?php


// Enable post thumbnails
add_theme_support( 'post-thumbnails' );

// Add an image size for featured images
add_image_size( 'featured-header', 800, 400, true );


// Register menus
register_nav_menu( 'main_menu', 'Main Menu' );
register_nav_menu( 'footer_menu', 'Footer Menu' );


// Register the sidebar
register_sidebar( array(
	'id' => 'footer-area',
	'name' => __( 'Footer Widget Area', 'tinker' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h1 class="widget-title">',
	'after_title' => '</h1>',
) );


add_action( 'wp_enqueue_scripts', 'tinker_child_css' );

function tinker_child_css() {
	wp_enqueue_style( 'tinker-child', get_stylesheet_directory_uri() . '/style.css' );
}
