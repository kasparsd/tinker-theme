<?php

/**
 * Include all files inside the inc folder
 */
foreach ( glob( dirname( __FILE__ ) . '/inc/*.php' ) as $include_file )
	include $include_file;

