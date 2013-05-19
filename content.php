<?php

if ( ! is_singular() && has_excerpt() )
	the_excerpt();
else
	the_content( __( 'Read more &rarr;', 'tinker' ) );
