<?php


add_action( 'after_post_title', 'featured_image_header_single', 20 );

function featured_image_header_single() {
	if ( ! has_post_thumbnail() )
		return;

	printf( 
		'<div class="post-featured-header">
			<a href="%s">%s</a>
		</div>',
		get_permalink(),
		get_the_post_thumbnail( null, 'featured-header' ) 
	);
}


add_filter( 'post_class', 'has_featured_image_class' );

function has_featured_image_class( $classes ) {
	if ( has_post_thumbnail() )
		$classes[] = 'has-featured-image';

	return $classes;
}


add_action( 'post_header', 'add_tinker_attachemnt_parent' );

function add_tinker_attachemnt_parent() {
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


add_action( 'post_footer', 'add_post_meta_header', 5 );

function add_post_meta_header() {
	if ( is_page() || is_404() )
		return;

	$elements = array();

	$elements['time'] = sprintf( 
			'<span class="meta-time time"><abbr title="%s">%s</abbr></span>', 
			get_the_time('r'), 
			get_the_time( get_option( 'date_format' ) )
		);

	$elements['category'] = sprintf( 
			'<span class="meta-cat category">%s</span>', 
			get_the_category_list( ',' ) 
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

	printf( 
		'<div class="post-meta-footer post-meta">%s</div>',
		implode( ' ', $elements )
	);
}


add_action( 'logo_image', 'maybe_add_blog_avatar' );

function maybe_add_blog_avatar() {
	$admin_email = get_option( 'admin_email' );

	echo get_avatar( $admin_email, 64 );
}


// Use WordPress SEO breadcrumbs if plugin installed
if ( function_exists( 'yoast_breadcrumb' ) )
	add_action( 'content_before', 'tinklog_yoast_breadcrumb' );
else
	add_action( 'content_before', 'tinklog_breadcrumb' );

function tinklog_yoast_breadcrumb() {
	yoast_breadcrumb( '<p class="breadcrumbs yoast-breadcrumbs">', '</p>' );
}

function tinklog_breadcrumb() {
	$path = array();

	$page_on_front = get_option( 'page_on_front' );
	$page_for_posts = get_option( 'page_for_posts' );

	if ( $page_on_front )
		$path[] = sprintf( '<a href="%s">%s</a>', get_permalink( $page_on_front ), __('Home') );

	if ( ( is_single() || is_archive() || get_query_var( 'paged' ) ) && $page_for_posts )
		$path[] = sprintf( '<a href="%s">%s</a>', get_permalink( $page_for_posts ), get_the_title( $page_for_posts ) );

	if ( is_single() && $category_list = get_the_category_list( ',' ) )
		$path[] = $category_list;

	if ( is_category() )
		$path[] = sprintf( '<a href="%s">%s</a>', get_category_link( get_queried_object_id() ), single_cat_title( false, false ) );

	if ( count( $path ) > 1 )
		printf( '<p class="breadcrumbs">%s &rsaquo;</p>', implode( ' &rsaquo; ', $path ) );
}


add_action( 'content_after', 'tinklog_pagination' );

function tinklog_pagination() {
	global $wp_query, $paged;

	if ( ! $wp_query->max_num_pages )
		return;

	$nav = array();

	if ( $older_link = get_previous_posts_link( __( '&larr; Newer', 'tinker' ) ) )
		$nav[] = $older_link;

	if ( $newer_link = get_next_posts_link( __( 'Older &rarr;', 'tinker' ) ) )
		$nav[] = $newer_link;

	if ( ! empty( $nav ) )
		printf( 
			'<nav class="pagination">
				<span class="links">%s</span> 
				<span class="status">%s</span>
			</nav>', 
			implode( '', $nav ),
			sprintf( __( 'Page %d of %d', 'tinker' ), $paged, $wp_query->max_num_pages )
		);
}


add_filter( 'get_the_excerpt', 'excerpt_add_readmore' );

function excerpt_add_readmore( $content ) {
	return sprintf( 
			'%s <a href="%s" class="read-more excerpt-read-more">%s</a>', 
			$content, 
			get_permalink(), 
			str_replace( ' ', '&nbsp;', __( 'Read more &rarr;', 'tinker' ) )
		);
}

