
			<?php do_action( 'content_after' ); ?>
	
		</div><!-- content-main -->
	</div><!-- wrap -->

	<div class="wrap wrap-footer" role="contentinfo">
		<?php do_action( 'before_footer' ); ?>

		<?php if ( is_active_sidebar( 'footer-area' ) ) : ?>
			<nav id="footer">
				<?php dynamic_sidebar( 'footer-area' ); ?>
			</nav>
		<?php endif; ?>

		<?php do_action( 'after_footer' ); ?>
	</div>
	
</div><!-- soul -->

<?php wp_footer(); ?>

<!-- <?php printf( '%s queries in %s seconds on %s', get_num_queries(), timer_stop(1), date('r') ); ?>  -->

</body>
</html>
