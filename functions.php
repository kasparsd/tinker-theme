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
	wp_enqueue_style( 'base-css', get_stylesheet_directory_uri() . '/style.css' );
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

function base_time_diff( $from, $to = '', $limit = 1 ) {
	
	// Since all months/years aren't the same, these values are what Google's calculator says
	$units = apply_filters( 'time_units', array(
			31556926 => array( __('%s year'),  __('%s years') ),
			2629744  => array( __('%s month'), __('%s months') ),
			604800   => array( __('%s week'),  __('%s weeks') ),
			86400    => array( __('%s day'),   __('%s days') ),
			3600     => array( __('%s hour'),  __('%s hours') ),
			60       => array( __('%s min'),   __('%s minutes') ),
	) );

	if ( empty($to) )
		$to = time();

	$from = (int) $from;
	$to   = (int) $to;
	$diff = (int) abs( $to - $from );

	$items = 0;
	$output = array();

	foreach ( $units as $unitsec => $unitnames ) {
			if ( $items >= $limit )
					break;

			if ( $diff < $unitsec )
					continue;

			$numthisunits = floor( $diff / $unitsec );
			$diff = $diff - ( $numthisunits * $unitsec );
			$items++;

			if ( $numthisunits > 0 )
					$output[] = sprintf( _n( $unitnames[0], $unitnames[1], $numthisunits ), $numthisunits );
	}

	if ( !empty($output) )
		return implode( _x( ', ', 'human_time_diff' ), $output );
	else
		return sprintf( current( array_pop( $units ) ), 1 );
}


