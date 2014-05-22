<?php


add_filter( 'wp_title', 'tinker_head_title', 10, 2 );

function tinker_head_title( $title, $sep ) {

	if ( is_feed() )
		return $title;

	if ( is_front_page() )
		return sprintf( '%s %s %s', get_bloginfo( 'name' ), $sep, get_bloginfo( 'description' ) );

	$title = sprintf( '%s %s', $title, get_bloginfo( 'name' ) );

	if ( get_query_var('paged') > 1 )
		$title .= sprintf( ' (%s)', sprintf( __( 'Page %d', 'tinker' ), get_query_var('paged') ) );

	return $title;

}


add_action( 'wp_head', 'tinker_favicon' );

function tinker_favicon() {
	$email_hash = md5( strtolower( trim( get_option( 'admin_email' ) ) ) );

	if ( is_ssl() )
		$host = 'https://secure.gravatar.com';
	else
		$host = 'http://gravatar.com';

	printf(
		'<link rel="icon" type="image/png" href="%s" />',
		sprintf( '%s/avatar/%s?s=64', $host, $email_hash )
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


