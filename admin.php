<?php

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