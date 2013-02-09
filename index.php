<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
		
		<?php do_action( 'post_header' ) ?>
		
		<?php if ( get_the_title() ) : ?>
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_title(); ?>
			</a>
		</h2>
		<?php endif; ?>

		<div class="entry-content">
			 <?php get_template_part( 'content', get_post_type() );  ?>
		</div>
		
		<?php do_action( 'post_footer' ) ?>
	
	</article>
<?php endwhile; endif; ?>

<?php get_footer(); ?>
