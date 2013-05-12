<?php

if ( is_archive() || is_home() )
	the_excerpt();
else
	the_content();
