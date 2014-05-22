<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php wp_title( '&mdash;', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script><![endif]-->
</head>
<body <?php body_class(); ?>>
<div id="soul">

<div class="wrap wrap-header">
	<nav id="header" role="navigation">
		<?php do_action( 'header_before' ); ?>

		<p id="logo" role="banner">
			<a href="<?php echo home_url() ?>">
				<?php do_action('logo_image'); ?>
				<strong><?php bloginfo('name'); ?></strong>
			</a>
			<em><?php bloginfo('description'); ?></em>
		</p>
		
		<?php do_action( 'header_after' ); ?>
	</nav>
</div>

<div class="wrap wrap-content-main">

	<?php do_action( 'content_before' ); ?>

	<div id="content-main" class="hfeed" role="main">

