<?php

if ( post_password_required() )
	return;

if ( comments_open() || have_comments() ) : ?>

<div class="comments-wrap">
	<div class="comments" id="comments">

		<?php if ( have_comments() ) : ?>
			<h2 class="comments-title">
				<?php
					printf(
						_n( 'One Comment', '%1$s Comments', get_comments_number(), 'tinker' ),
						number_format_i18n( get_comments_number() ) 
					);
				?>
			</h2>

			<ol class="commentlist">
				<?php 
					wp_list_comments( array(
							'avatar_size' => 64
						) ); 
				?>
			</ol>

			<?php
				if ( ! comments_open() && get_comments_number() )
					printf( '<p class="nocomments">%s</p>', _e( 'Comments are closed.' , 'tinker' ) );

				// Allow comment pagination
				paginate_comments_links();
			?>
		<?php endif; ?>

		<?php 
			comment_form( array(
					'comment_notes_before' => null,
					'comment_notes_after' => null
				) ); 
		?>

	</div><!-- #comments -->
</div>

<?php endif; // comments_open() || have_comments()
