<?php


/**
 * Add headings to archive pages
 */
add_action( 'content_before', 'tinker_add_archive_heading', 15 );

function tinker_add_archive_heading() {
	if ( ! is_archive() )
		return;

	$extra = array();
	$heading = null;

	if ( is_author() )
		$author = get_user_by( 'login', get_query_var('author_name') );

	if ( is_tax() || is_category() || is_tag() )
		$heading = single_term_title( null, false );
	elseif ( is_day() )
		$heading = get_the_date();
	elseif ( is_month() )
		$heading = get_the_date( _x( 'F Y', 'monthly archives date format', 'tinker' ) );
	elseif ( is_year() )
		$heading = get_the_date( _x( 'Y', 'yearly archives date format', 'tinker' ) );
	elseif ( is_author() && $author )
		$heading = sprintf( __( 'Author: %s', 'tinker' ), $author->display_name );
	else
		$heading = __( 'Archives', 'tinker' );

	if ( is_home() && $page_for_posts = get_option('page_for_posts') ) {
		$post_for_posts = get_post( $page_for_posts );

		$heading = get_the_title( $post_for_posts );
		$extra[] = $post_for_posts->post_excerpt;
	}

	if ( category_description() )
		$extra[] = sprintf(
				'<div class="index-description">%s</div>',
				category_description()
			);

	if ( is_author() && $author )
		$extra[] = sprintf(
				'<div class="index-description">%s</div>',
				wptexturize( $author->user_description )
			);

	printf(
		'<div class="index-header">
			<h1 class="index-heading">%s</h1>
			%s
		</div>',
		esc_html( $heading ),
		implode( '', $extra )
	);
}


/**
 * Add headings to blog index page
 */
add_action( 'content_before', 'tinker_add_blog_index_heading' );

function tinker_add_blog_index_heading() {
	if ( ! is_home() )
		return;

	$page_for_posts = get_option('page_for_posts');

	if ( ! $page_for_posts )
		return;

	$index_post = get_post( $page_for_posts );

	printf(
		'<div class="index-header">
			<h1 class="index-heading">%s</h1>
			<div class="index-description">%s</div>
		</div>',
		apply_filters( 'the_title', $index_post->post_title, array( 'context' => 'index' ) ),
		apply_filters( 'the_content', $index_post->post_content, array( 'context' => 'index' ) )
	);
}


/**
 * Prepend featured image to posts on archive and single pages
 */
add_action( 'after_post_title', 'tinker_featured_image_header_single' );

function tinker_featured_image_header_single() {
	if ( is_search() || is_404() || ! has_post_thumbnail() )
		return;

	if ( is_singular() )
		printf( 
			'<div class="post-featured-header">
				%s
			</div>',
			get_the_post_thumbnail( null, 'featured-header' ) 
		);
	else
		printf( 
			'<div class="post-featured-header">
				<a href="%s">%s</a>
			</div>',
			get_permalink(),
			get_the_post_thumbnail( null, 'featured-header' ) 
		);
}


/**
 * Add hfeed class to body tag on index/archive pages
 */
add_filter( 'body_class', 'tinker_body_class' );

function tinker_body_class( $classes ) {
	if ( ! is_singular() )
		$classes[] = 'hfeed';

	return $classes;
}


/**
 * Add a CSS class to post wraps if post has a featured image
 */
add_filter( 'post_class', 'tinker_has_featured_image_class' );

function tinker_has_featured_image_class( $classes ) {
	if ( has_post_thumbnail() )
		$classes[] = 'has-featured-image';

	return $classes;
}


/**
 * Add links to attachment pages
 */
add_action( 'post_header', 'tinker_add_attachemnt_parent' );

function tinker_add_attachemnt_parent() {
	if ( ! is_attachment() )
		return;

	$post = get_queried_object();
	$metadata = wp_get_attachment_metadata();

	printf( 
		'<a href="%s" class="attachment-parent">&larr; %s</a>',
		esc_url( get_permalink( $post->post_parent ) ),
		get_the_title( $post->post_parent )
	);

	echo '<p class="attachment-nav">';
		previous_image_link( false, __( '&larr; Previous', 'tinker' ) );
		next_image_link( false, __( 'Next &rarr;', 'tinker' ) );
	echo '</p>';
}


/**
 * Add post meta links
 */
add_action( 'post_footer', 'tinker_add_post_meta_header', 5 );

function tinker_add_post_meta_header() {
	if ( is_page() || is_404() )
		return;

	$elements = array(
		'published' => sprintf( 
			'<span class="meta-published time published">
				<abbr title="%s">%s</abbr>
			</span>', 
			esc_attr( get_the_time('r') ),  
			get_the_time( get_option( 'date_format' ) )
		),
		'updated' => sprintf( 
			'<span class="meta-updated time updated">
				<span class="value-title" title="%s"></span>
			</span>', 
			esc_attr( get_the_modified_date('r') )
		),
		'author' => sprintf( 
			'<span class="meta-author author vcard">
				<span class="name fn">
					<span class="value-title" title="%s"></span>
				</span>
			</span>', 
			esc_attr( get_the_author() )
		),
		'category' => sprintf( 
			'<span class="meta-category category">%s</span>', 
			get_the_category_list( ',' ) 
		),
		'tags' => get_the_tag_list( '<span class="meta-tags tags">', '', '</span>' )
	);

	$comment_count = get_comments_number();

	if ( $comment_count )
		$comment_text = sprintf( _n( 'One Comment', '%d Comments', $comment_count, 'tinker' ), number_format_i18n( get_comments_number() ) );
	else
		$comment_text = __( 'Add Comment', 'tinker' );

	if ( ! is_single() )
		$elements['comments'] = sprintf( 
				'<span class="meta-comment-count"><a href="%s#comments">%s</a></span>', 
				get_permalink(), 
				$comment_text 
			);

	$elements = apply_filters( 'tinker_post_meta', $elements );

	printf( 
		'<div class="post-meta-footer post-meta">%s</div>',
		implode( ' ', $elements )
	);
}


/**
 * Use Gravatar profile image (using the admin email) as the logo
 */
add_action( 'logo_image', 'maybe_add_tinker_blog_avatar' );

function maybe_add_tinker_blog_avatar() {
	echo get_avatar( get_option( 'admin_email' ), 64 );
}


/**
 * Add breadcrumbs
 */
add_action( 'content_before', 'tinker_breadcrumb' );

function tinker_breadcrumb() {
	$path = array();
	$page_ancestors = null;

	// Use WordPress SEO breadcrumbs if plugin installed
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<p class="breadcrumb yoast-breadcrumb" itemprop="breadcrumb">', '</p>' );
		return;
	}

	if ( is_404() || is_search() )
		return; 

	// Get blog index page, if any
	$page_for_posts = get_option( 'page_for_posts' );

	// Get page ancestors, if any
	if ( is_page() )
		$page_ancestors = get_ancestors( get_queried_object_id(), 'page' );

	// Add home link
	$path[] = sprintf( '<a href="%s">%s</a>', home_url('/'), __( 'Home', 'tinker' ) );

	// Add page ancestors
	if ( is_page() && ! empty( $page_ancestors ) )
		foreach ( array_reverse( $page_ancestors ) as $ancestor )
			$path[] = sprintf( '<a href="%s">%s</a>', get_permalink( $ancestor ), esc_html( get_the_title( $ancestor ) ) );

	// Add blog index page if on single post or archive
	if ( ( is_single() || is_archive() || get_query_var( 'paged' ) ) && $page_for_posts )
		$path[] = sprintf( '<a href="%s">%s</a>', get_permalink( $page_for_posts ), get_the_title( $page_for_posts ) );

	// Add category links, if on single post
	if ( is_single() && $category_list = get_the_category_list( ',' ) )
		$path[] = $category_list;

	// Add category links on category archive pages
	if ( is_category() && get_query_var( 'paged' ) )
		$path[] = sprintf( '<a href="%s">%s</a>', get_category_link( get_queried_object_id() ), single_cat_title( false, false ) );

	// Print breadcrumb if not on front page
	if ( ! is_front_page() )
		printf( '<p class="breadcrumb" itemprop="breadcrumb">%s &rsaquo;</p>', implode( ' &rsaquo; ', $path ) );
}


/**
 * Add pagination to all index/archive pages
 */
add_action( 'content_after', 'tinker_pagination' );

function tinker_pagination() {
	global $wp_query, $paged;

	if ( ! $wp_query->max_num_pages || is_404() )
		return;

	$nav = array();

	if ( is_search() )
		$labels = array( 'next' => __( 'Next &rarr;', 'tinker' ), 'previous' => __( '&larr; Previous', 'tinker' ) );
	else
		$labels = array( 'next' => __( 'Older &rarr;', 'tinker' ), 'previous' => __( '&larr; Newer', 'tinker' ) );

	if ( $older_link = get_previous_posts_link( $labels['previous'] ) )
		$nav[] = $older_link;

	if ( $newer_link = get_next_posts_link( $labels['next'] ) )
		$nav[] = $newer_link;

	if ( ! empty( $nav ) )
		printf( 
			'<nav class="pagination post-pagination">
				<span class="links">%s</span> 
				<span class="status">%s</span>
			</nav>', 
			implode( '', $nav ),
			sprintf( __( 'Page %d of %d', 'tinker' ), $paged, $wp_query->max_num_pages )
		);
}


/**
 * Append the Read more link to the excerpt on archive/index pages
 */
add_filter( 'get_the_excerpt', 'tinker_excerpt_add_readmore' );

function tinker_excerpt_add_readmore( $content ) {
	return sprintf( 
			'%s <a href="%s" class="read-more excerpt-read-more">%s</a>', 
			$content, 
			get_permalink(), 
			str_replace( ' ', '&nbsp;', __( 'Read more &rarr;', 'tinker' ) )
		);
}

