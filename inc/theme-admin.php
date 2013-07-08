<?php


/**
 * Add a submenu item under Appearance as WordPress 3.5 doesn't add this by default.
 */
add_action( 'admin_menu', 'maybe_add_theme_customizer_menu' );

function maybe_add_theme_customizer_menu() {
	global $wp_version;
	
	if ( version_compare( '3.6', $wp_version ) )
		add_theme_page( __( 'Customize', 'tinker' ), __( 'Customize', 'tinker' ), 'edit_theme_options', 'customize.php' );
}


/**
 * Filters we allow the user to disable/enable
 * @var array
 */
$tinker_filters = array(
		'maybe_add_tinker_blog_avatar' => array( 
			'hook' => 'logo_image', 
			'label' => __( 'Show Gravatar image in the header', 'tinker' ),
			'default' => true
		),
		'tinklog_breadcrumb' => array( 
			'hook' => 'content_before', 
			'label' => __( 'Enable breadcrumbs', 'tinker' ),
			'default' => true
		),
		'tinker_featured_image_header_single' => array(
			'hook' => 'after_post_title',
			'label' => __( 'Place featured images after title', 'tinker' ),
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
		)	
	);


/**
 * Tinker theme colors
 * @var array
 */
$tinker_colors = array(
		'background_color' => array(
			'label' => __( 'Background', 'tinker' ),
			'default' => 'dddddd',
			'css' => array( 
				'#nav-main .sub-menu a, #nav-main-toggle:before' => 'color'
			)
		),
		'link-color' => array( 
			'label' => __( 'Links', 'tinker' ),
			'default' => '1e73be',
			'css' => array( 
				'a' => 'color'
			)
		),
		'header-color' => array( 
			'label' => __( 'Header text', 'tinker' ),
			'default' => '222222',
			'css' => array( 
				'#header, #nav-main .sub-menu a, .breadcrumbs, .pagination, .wrap-footer, .archive-header, .archive-heading, .archive-header a' => 'color',
				'#nav-main-toggle' => 'background-color'
			)
		),
		'text-color' => array( 
			'label' => __( 'Text', 'tinker' ),
			'default' => '333333',
			'css' => array( 
				'body' => 'color' 
			)
		),
		'headline-color' => array( 
			'label' => __( 'Headlines', 'tinker' ),
			'default' => '333333',
			'css' => array( 
				'article .entry-title' => 'color'
			)
		)
	);


/**
 * Tinker fonts frot Google Webfonts
 * @var array
 */
$tinker_google_fonts = array(
		'Noto+Sans:400,700,400italic,700italic',
		'Playfair+Display:400,700,400italic,700italic',
		'PT+Sans:400,700,400italic,700italic',
		'PT+Sans+Caption:400,700',
		'PT+Mono',
		'Noto+Serif:400,700,400italic,700italic',
		'Roboto+Slab:400,700',
		'Abril+Fatface',
		'Open+Sans:400italic,700italic,400,700',
		'Roboto:400,400italic,700,700italic',
		'Roboto+Condensed:400italic,700italic,400,700',
		'Source+Sans+Pro:400,700,400italic,700italic',
		'Oxygen:400,700',
		'Titillium+Web:400,400italic,700,700italic',
		'Bree+Serif',
		'Domine:400,700',
		'Arimo:400,700,400italic,700italic',
		'Libre+Baskerville:400,700,400italic',
		'Abril+Fatface',
		'Coustard',
		'Merriweather:400,400italic,700,700italic'
	);

sort( $tinker_google_fonts );

$tinker_font_choices = array( 
		'' => __( 'Default', 'tinker' )
	);

foreach ( $tinker_google_fonts as $font_uri )
	$tinker_font_choices[ $font_uri ] = str_replace( '+', ' ', array_shift( explode( ':', $font_uri ) ) );

$tinker_fonts = array(
		'heading-font' => array( 
			'label' => __( 'Headings', 'tinker' ),
			'choices' => $tinker_font_choices,
			'css' => array( 
				'.wf-active #header, .wf-active .footer-menu-wrap, .wf-active h1, .wf-active h2, .wf-active h3, .wf-active h4, .wf-active h5, .wf-active h6' => 'font-family'
			)
		),
		'body-font' => array( 
			'label' => __( 'Body text', 'tinker' ),
			'choices' => $tinker_font_choices,
			'css' => array( 
				'.wf-active body' => 'font-family'
			)
		)
	);


add_action( 'customize_register', 'tinker_customizer' );

function tinker_customizer( $wp_customize ) {
	global $tinker_filters, $tinker_colors, $tinker_fonts;

	/**
	 * Theme color options
	 */

	foreach ( $tinker_colors as $color_name => $color_options ) {
		$wp_customize->add_setting( 
			$color_name, 
			array_merge(
				array(
					'sanitize_js_callback' => 'maybe_hash_hex_color',
					'sanitize_callback' => 'sanitize_hex_color_no_hash',
				),
				$color_options 
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color_name,
				array(
					'label' => $color_options['label'],
					'section' => 'colors',
					'settings' => $color_name
				)
			)
		);
	}


	/**
	 * Tinker fonts
	 */

	$wp_customize->add_section(
		'tinker-fonts',
		array(
			'title' => __( 'Fonts', 'tinker' ),
			'description' => __( 'Choose fonts for headings and body text.', 'tinker' ),
			'priority' => 45
		)
	);

	foreach ( $tinker_fonts as $font_setting => $font_options ) {
		$wp_customize->add_setting( $font_setting, array( 'default' => '' ) );

		$wp_customize->add_control(
			$font_setting,
			array(
				'type' => 'select',
				'section' => 'tinker-fonts',
				'label' => $font_options['label'],
				'choices' => $font_options['choices']
 			)
		);
	}


	/**
	 * Turn regular WordPress actions into theme options
	 */
	
	$wp_customize->add_section(
		'tinker-elements',
		array(
			'title' => __( 'Tinker Features', 'tinker' ),
			'description' => __( 'Enable or disable certain theme elements.', 'tinker' ),
			'priority' => 50
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


// We hook into wp_head just like wp-head-callback does
add_action( 'wp_head', 'tinker_custom_styles' );

function tinker_custom_styles() {
	global $tinker_colors, $tinker_fonts;

	$styles = array();

	// Custom colors
	foreach ( $tinker_colors as $color => $settings ) {
		$mod_value = get_theme_mod( $color, $settings['default'] );

		// Ensure that we don't have the hex in front
		$mod_value = ltrim( $mod_value, '#' );

		if ( strcasecmp( $mod_value, $settings['default'] ) )
			foreach ( $settings['css'] as $selector => $property )
				$styles[] = sprintf( '%s { %s: #%s; }', $selector, $property, $mod_value );
	}

	// Custom fonts
	foreach ( $tinker_fonts as $font => $font_settings ) {
		$mod_value = get_theme_mod( $font, null );
		
		if ( ! empty( $mod_value ) )
			foreach ( $font_settings['css'] as $selector => $property )
				$styles[] = sprintf( 
						'%s { %s: "%s", sans-serif; }', 
						$selector, 
						$property, 
						str_replace( '+', ' ', array_shift( explode( ':', $mod_value ) ) ) 
					);
	}

	if ( ! empty( $styles ) )
		printf( 
			'<style type="text/css">%s</style>',
			implode( ' ', $styles )
		);
}


add_action( 'wp_footer', 'tinker_custom_fonts', 30 );

function tinker_custom_fonts() {
	global $tinker_fonts;

	$queue = array();

	$tinker_fonts = array_unique( $tinker_fonts );

	// CSS styles for google fonts
	foreach ( $tinker_fonts as $font => $font_settings ) {
		$mod_value = get_theme_mod( $font, null );
		
		if ( ! empty( $mod_value ) )
			$queue[] = sprintf( '"%s"', esc_attr( $mod_value ) );
	}

	if ( ! empty( $queue ) )
		printf( 
			'<script type="text/javascript">
				WebFontConfig = { google: { families: [ %s ] } };
				(function() {
					var wf = document.createElement("script");
					wf.src = ("https:" == document.location.protocol ? "https" : "http") +
					"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
					wf.type = "text/javascript";
					wf.async = "true";
					var s = document.getElementsByTagName("script")[0];
					s.parentNode.insertBefore(wf, s);
				})();
			</script>', 
			implode( ', ', $queue )
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


add_action( 'login_enqueue_scripts', 'tinker_custom_login_logo' );

function tinker_custom_login_logo() {
	$email_hash = md5( strtolower( trim( get_option( 'admin_email' ) ) ) );

	if ( is_ssl() )
		$host = 'https://secure.gravatar.com';
	else
		$host = 'http://gravatar.com';

	printf( 
		'<style type="text/css">
			.login h1 a { 
				background:url("%s/avatar/%s?s=64") no-repeat center center;
				background-size:auto; 
				height:80px; 
			}
		</style>',
		$host, 
		$email_hash 
	);
}

