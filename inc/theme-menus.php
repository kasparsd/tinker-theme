<?php


add_action( 'header_after', 'add_main_menu' );

function add_main_menu() {
	$menu = wp_nav_menu( array( 
				'theme_location' => 'main_menu',
				'container_id' => 'nav-main',
				'fallback_cb' => false,
				'depth' => 2,
				'echo' => false
			) 
		);

	if ( ! empty( $menu ) )
		printf(
			'<a id="nav-main-toggle" href="#nav-main" title="%s"></a>
			%s',
			__( 'Menu', 'tinker' ),
			$menu
		);
}


add_action( 'after_footer', 'tinker_credits_footer' );

function tinker_credits_footer() {
	$footer_menu = wp_nav_menu( array( 
			'theme_location' => 'footer_menu',
			'container_id' => 'nav-footer',
			'fallback_cb' => false,
			'echo' => false,
			'depth' => 1
		) );
	
	printf( 
		'<div class="footer-menu-wrap">
			<strong class="home"><a href="%s">%s</a></strong>
			%s
		</div>',
		home_url(),
		esc_html( get_bloginfo('name') ),
		$footer_menu
	);
}

