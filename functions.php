<?php

add_theme_support( 'custom-header', array(
		'header-text' => false
	) );

register_sidebar( array(
	'id' => 'sidebar',
	'name' => __( 'Sidebar' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h4 class="widget-title">',
	'after_title' => '</h4>',
) );

register_sidebar( array(
	'id' => 'footer-area',
	'name' => __( 'Footer' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h4 class="widget-title">',
	'after_title' => '</h4>',
) );


add_action( 'wp_enqueue_scripts', 'add_base_child_css' );

function add_base_child_css() {
	wp_enqueue_style( 'base-child', get_stylesheet_directory_uri() . '/style.css' );
}


add_filter( 'wp_title', 'base_page_title', 10, 2 );

function base_page_title( $title, $sep ) {
	$sep = apply_filters( 'base_title_sep', $sep );

	if ( is_front_page() )
		$title = sprintf( '%s %s %s', get_bloginfo( 'name' ), $sep, get_bloginfo( 'description' ) );

	if ( ! is_feed() )
		$title .= get_bloginfo( 'name' );

	return $title;
}


add_filter( 'previous_posts_link_attributes', 'base_pagination_rel_prev' );

function base_pagination_rel_prev() {
	return 'rel="prev"';
}


add_filter( 'next_posts_link_attributes', 'base_pagination_rel_next' );

function base_pagination_rel_next() {
	return 'rel="next"';
}

add_action( 'logo_image', 'add_header_image_as_logo' );

function add_header_image_as_logo() {
	if ( $header_image = get_header_image() )
		printf( 
				'<img src="%s" width="%d" height="%d" alt="%s" />',
				$header_image,
				get_custom_header()->width,
				get_custom_header()->height,
				esc_attr( get_bloginfo('name') )
			);
}



