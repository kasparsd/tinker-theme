<?php


add_filter( 'wp_title', 'base_page_title', 10, 2 );

function base_page_title( $title, $sep ) {
	$sep = apply_filters( 'base_title_sep', $sep );

	if ( is_front_page() )
		return sprintf( '%s %s %s', get_bloginfo( 'name' ), $sep, get_bloginfo( 'description' ) );

	if ( ! is_feed() )
		$title .= get_bloginfo( 'name' );

	return $title;
}


add_action( 'wp_head', 'add_head_feed_link', 8 );

function add_head_feed_link() {
	printf(
		'<link rel="alternate" type="application/rss+xml" title="%s" href="%s" />',
		esc_attr( sprintf( __( '%s RSS feed', 'tinker' ), get_bloginfo('name') ) ),
		get_bloginfo('rss2_url')
	);
}


add_action( 'wp_head', 'add_head_favicon', 8 );

function add_head_favicon() {
	printf(
		'<link rel="icon" type="image/png" href="%s" />',
		get_avatar( get_option( 'admin_email' ), 64 )
	);
}


add_action( 'wp_head', 'make_search_notfound_noindex' );

function make_search_notfound_noindex() {
	if ( is_search() || is_404() )
		echo '<meta name="robots" content="noindex" />';
}


add_filter( 'previous_posts_link_attributes', 'base_pagination_rel_prev' );

function base_pagination_rel_prev() {
	return 'rel="prev"';
}


add_filter( 'next_posts_link_attributes', 'base_pagination_rel_next' );

function base_pagination_rel_next() {
	return 'rel="next"';
}


