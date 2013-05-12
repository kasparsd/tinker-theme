<?php 

get_header();

?>
<article <?php post_class(); ?>>
	<h1 class="entry-title" role="heading">
		<?php _e( 'Search Results', 'tinker' ) ?>		
	</h1>

	<div class="entry-content">
	<?php 
		get_search_form();

		if ( have_posts() ) : 
			$results = array();

			while ( have_posts() ) : 
				the_post(); 
				$results[] = sprintf( '<li><h1><a href="%s">%s</a></h1></li>', get_permalink(), esc_html( get_the_title() ) );
			endwhile;

			printf( '<ul class="search-results">%s</ul>', implode( '', $results ) );
		else:
			printf( '<p>%s</p>', __( 'Sorry, but nothing matched your search criteria.', 'tinker' ) );
		endif;
	?>
	</div>

</article>
<?php 

get_footer();
