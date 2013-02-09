<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		
		<?php do_action( 'post_header' ) ?>
		
		<?php if ( get_the_title() ) : ?>
			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>
		<?php endif; ?>

		<?php do_action( 'after_post_title' ) ?>

		<div class="entry-content">
			<?php get_template_part( 'content', get_post_type() );  ?>
		</div>
		
		<?php do_action( 'post_footer' ) ?>
	
	</article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
