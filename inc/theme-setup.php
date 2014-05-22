<?php


add_action( 'after_setup_theme', 'tinker_theme_setup' );

function tinker_theme_setup() {
	
	// Enable localization
	load_theme_textdomain( 'tinker', get_template_directory() . '/languages' );

	// Required only for theme check, not used in theme
	global $content_width;
	if ( ! isset( $content_width ) ) 
		$content_width = 650;

	// Support for custom post formats
	add_theme_support( 'post-formats', array( 'link', 'quote', 'status', 'aside', 'image', 'gallery', 'video', 'audio' ) );

	// Support custom background colors and images
	add_theme_support( 'custom-background', array( 'default-color' => 'dddddd' ) );

	// Add RSS links, etc.
	add_theme_support( 'automatic-feed-links' );

	// Enable post thumbnails
	add_theme_support( 'post-thumbnails' );

	add_editor_style();

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

	if ( is_singular() && comments_open() ) 
		wp_enqueue_script( 'comment-reply' );

}


add_action( 'wp_enqueue_scripts', 'tinker_base_css' );

function tinker_base_css() {
	wp_enqueue_style( 'tinker-base-css', get_template_directory_uri() . '/style.css', null, 150 );
}
