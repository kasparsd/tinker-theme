<?php

add_theme_support( 'post-thumbnails' );

add_theme_support( 
	'custom-header', 
	array(
		'header-text' => false
	)
);

add_post_type_support( 'page', 'excerpt' );

register_sidebar( array(
	'id' => 'sidebar',
	'name' => __( 'Sidebar' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h1 class="widget-title">',
	'after_title' => '</h1>',
) );

register_sidebar( array(
	'id' => 'footer-area',
	'name' => __( 'Footer' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h1 class="widget-title">',
	'after_title' => '</h1>',
) );

add_action( 'wp_head', 'add_head_feed_link', 8 );

function add_head_feed_link() {
	printf(
		'<link rel="alternate" type="application/rss+xml" title="%s" href="%s" />',
		esc_attr( sprintf( '%s RSS feed', get_bloginfo('name') ) ),
		get_bloginfo('rss2_url')
	);
}

add_action( 'wp_head', 'add_head_favicon', 8 );

function add_head_favicon() {
	printf(
		'<link rel="icon" type="image/png" href="%s/images/favicon.png" />',
		get_stylesheet_directory_uri()
	);
}

add_action( 'wp_head', 'make_search_notfound_noindex' );

function make_search_notfound_noindex() {
	if ( is_search() || is_404() )
		echo '<meta name="robots" content="noindex" />';
}

add_action( 'wp_enqueue_scripts', 'add_base_child_css' );

function add_base_child_css() {
	wp_enqueue_style( 'base-child', get_stylesheet_directory_uri() . '/style.css' );
}


add_filter( 'wp_title', 'base_page_title', 10, 2 );

function base_page_title( $title, $sep ) {
	$sep = apply_filters( 'base_title_sep', $sep );

	if ( is_front_page() )
		return sprintf( '%s %s %s', get_bloginfo( 'name' ), $sep, get_bloginfo( 'description' ) );

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



