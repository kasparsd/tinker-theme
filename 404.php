<?php get_header(); ?>

	<article id="post-404">
		
		<?php do_action( 'post_header' ) ?>
		
		<h1 class="entry-title">
			Page Not Found
		</h1>

		<?php do_action( 'after_post_title' ) ?>

		<div class="entry-content">
			<?php get_template_part( 'content', '404' );  ?>
		</div>
		
		<?php do_action( 'post_footer' ) ?>
	
	</article>

<?php get_footer(); ?>
