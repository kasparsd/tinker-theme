
<form method="get" id="searchform" action="<?php echo home_url(); ?>" role="search">
	<label for="s" class="screen-reader-text"><?php _e( 'Search', 'tinker' ); ?></label>
	<input type="text" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'tinker' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
	<input type="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'tinker' ); ?>" />
</form>