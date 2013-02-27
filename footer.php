
			<?php do_action( 'content_after' ); ?>
	
		</div><!-- content-main -->

		<?php do_action( 'before_sidebar' ); ?>

	</div><!-- wrap -->

	<?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
	<div class="wrap wrap-sidebar" role="complementary">
		<div id="sidebar">
			<?php dynamic_sidebar( 'sidebar' ); ?>
		</div>
	</div>
	<?php endif; ?>

	<?php do_action( 'before_footer' ); ?>

	<?php if ( is_active_sidebar( 'footer-area' ) ) : ?>
	<div class="wrap wrap-footer" role="contentinfo">
		<nav id="footer">
			<?php dynamic_sidebar( 'footer-area' ); ?>
		</nav>
	</div>
	<?php endif; ?>

	<?php do_action( 'after_footer' ); ?>

</div><!-- soul -->

<?php wp_footer(); ?>

<!-- <?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds on <?php echo date('r'); ?>  -->

</body>
</html>
