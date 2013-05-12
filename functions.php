<?php


add_theme_support( 'post-thumbnails' );

add_post_type_support( 'page', 'excerpt' );

add_image_size( 'featured-header', 800, 400, true );

register_nav_menu( 'main_menu', 'Main Menu' );
register_nav_menu( 'footer_menu', 'Footer Menu' );

register_sidebar( array(
	'id' => 'footer-area',
	'name' => __( 'Footer' ),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h1 class="widget-title">',
	'after_title' => '</h1>',
) );

add_action( 'header_after', 'add_main_menu' );

function add_main_menu() {
	wp_nav_menu( array( 
			'theme_location' => 'main_menu',
			'container_id' => 'nav-main',
			'fallback_cb' => false
		) 
	);
}


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


// add_action( 'content_before', 'tinker_section_header', 30 );

function tinker_section_header() {
	if ( ! is_archive() )
		return;

	printf( 
		'<h1 class="section-header">%s</h1>',
		single_cat_title( false, false ) 
	);
}


add_action( 'admin_print_footer_scripts', 'move_excerpt_editor' );

function move_excerpt_editor( $hook ) {
	?>
	<script type="text/javascript">
		jQuery(window).ready(function($) {
			$('#postexcerpt, #postimagediv').removeClass('closed').insertBefore('#postdivrich');
		});
	</script>
	<?php
}


add_filter( 'post_class', 'has_featured_image_class' );

function has_featured_image_class( $classes ) {
	if ( has_post_thumbnail() )
		$classes[] = 'has-featured-image';

	return $classes;
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


add_filter( 'get_the_excerpt', 'excerpt_add_readmore' );

function excerpt_add_readmore( $content ) {
	return sprintf( 
			'%s <a href="%s" class="read-more excerpt-read-more">%s</a>', 
			$content, 
			get_permalink(), 
			str_replace( ' ', '&nbsp;', __( 'Read more &rarr;', 'tinker' ) )
		);
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
			sprintf( __( 'Page %d of %d' ), $paged, $wp_query->max_num_pages )
		);
}


add_action( 'after_footer', 'tinker_credits_footer' );

function tinker_credits_footer() {
	printf( 
		'<div class="footer-menu-wrap">
			<strong class="home"><a href="%s">%s</a></strong>
			%s
		</div>',
		home_url(),
		esc_html( get_bloginfo('name') ),
		wp_nav_menu( array( 
			'theme_location' => 'footer_menu',
			'container_id' => 'nav-footer',
			'fallback_cb' => false,
			'echo' => false,
			'depth' => 1
		) )
	);
}




