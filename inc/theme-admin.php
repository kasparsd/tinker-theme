<?php

$tinker_filters = array(
		'maybe_add_blog_avatar' => array( 
			'hook' => 'logo_image', 
			'label' => __( 'Show Gravatar image in the header', 'tinker' ),
			'default' => true
		),
		'tinklog_breadcrumb' => array( 
			'hook' => 'content_before', 
			'label' => __( 'Enable breadcrumbs', 'tinker' ),
			'default' => true
		),
		'tinker_credits_footer' => array( 
			'hook' => 'after_footer', 
			'label' => __( 'Enable footer menu', 'tinker' ),
			'default' => true
		),
		'tinker_favicon' => array( 
			'hook' => 'wp_head', 
			'label' => __( 'Use Gravatar as favicon', 'tinker' ),
			'default' => true
		),
		//'' => array( 'hook' => '', 'label' => __( '', tinker ) ),
	);

$tinker_colors = array(
		'link-color' => array( 
			'label' => __( 'Links', 'tinker' ),
			'default' => '#1e73be',
			'sanitize_callback' => 'sanitize_hex_color',
			'css' => array( 
				'a' => 'color'
			)
		),
		'header-color' => array( 
			'label' => __( 'Header text', 'tinker' ),
			'default' => '#222222',
			'sanitize_callback' => 'sanitize_hex_color',
			'css' => array( 
				'#header, .breadcrumbs, .pagination, .wrap-footer' => 'color',
				'#header li .sub-menu' => 'background-color'
			)
		),
		'background-color' => array(
			'label' => __( 'Background', 'tinker' ),
			'default' => '#eeeeee',
			'sanitize_callback' => 'sanitize_hex_color',
			'css' => array( 
				'body' => 'background-color',
				'#nav-main .sub-menu a' => 'color'
			)
		),
		'text-color' => array( 
			'label' => __( 'Text', 'tinker' ),
			'default' => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'css' => array( 
				'body' => 'color' 
			)
		),
		'headline-color' => array( 
			'label' => __( 'Headlines', 'tinker' ),
			'default' => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'css' => array( 
				'article .entry-title' => 'color' 
			)
		)
	);


add_action( 'customize_register', 'tinker_customizer' );

function tinker_customizer( $wp_customize ) {
	global $tinker_filters, $tinker_colors;

	/**
	 * Theme color options
	 */
	
	$wp_customize->add_section(
		'tinker-colors',
		array(
			'title' => __( 'Colors', 'tinker' ),
			'description' => __( 'Choose background and text color.' ),
			'priority' => 40
		)
	);

	foreach ( $tinker_colors as $color_name => $color_options ) {
		$wp_customize->add_setting( $color_name, $color_options );

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color_name,
				array(
					'label' => $color_options['label'],
					'section' => 'tinker-colors',
					'settings' => $color_name,
				)
			)
		);
	}

	/**
	 * Turn regular WordPress actions into theme options
	 */
	
	$wp_customize->add_section(
		'tinker-elements',
		array(
			'title' => __( 'Header & Footer', 'tinker' ),
			'description' => __( 'Enable or disable certain theme elements.', 'tinker' ),
			'priority' => 40
		)
	);

	foreach ( $tinker_filters as $filter => $options ) {
		$wp_customize->add_setting( $filter, array( 'default' => $options['default'] ) );

		$wp_customize->add_control(
			$filter,
			array(
				'label' => $options['label'],
				'section' => 'tinker-elements',
				'type' => 'checkbox'
			)
		);
	}
}


add_action( 'wp_footer', 'tinker_custom_styles', 50 );

function tinker_custom_styles() {
	global $tinker_colors;

	$styles = array();

	foreach ( $tinker_colors as $color => $settings ) {
		$mod_value = get_theme_mod( $color, $settings['default'] );

		if ( strcasecmp( $mod_value, $settings['default'] ) )
			foreach ( $settings['css'] as $selector => $property )
			$styles[] = sprintf( '%s { %s:%s; }', $selector, $property, $mod_value );
	}

	if ( ! empty( $styles ) )
		printf( 
			'<style type="text/css">
				%s
			</style>',
			implode( "\n", $styles )
		);
}


add_action( 'wp', 'tinker_apply_customization' );

function tinker_apply_customization() {
	global $tinker_filters;

	foreach ( $tinker_filters as $filter => $options )
		if ( ! get_theme_mod( $filter, $options['default'] ) )
			remove_action( $options['hook'], $filter );
}


add_action( 'admin_print_footer_scripts', 'tinker_move_excerpt_editor' );

function tinker_move_excerpt_editor( $hook ) {
	?>
	<script type="text/javascript">
		jQuery(window).ready(function($) {
			$('#postexcerpt, #postimagediv').removeClass('closed').insertBefore('#postdivrich');
		});
	</script>
	<?php
}



