<?php

	$environment = WP_DEBUG == true ? "test" : "production";

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js <?php echo $environment; ?> lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js <?php echo $environment; ?> lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js <?php echo $environment; ?> lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js <?php echo $environment; ?>"> <!--<![endif]-->
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		 <title>!!! <?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<meta name="viewport" content="width=device-width">
		<!-- Place favicon.ico and apple-touch-icon.png in img/icons -->
		<!-- <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" type="image/vnd.microsoft.icon"> -->
		<!-- <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" /> -->
		<?php wp_head(); ?>
	</head>
	<body class="clearfix">

	<?php if ( $environment == "test" ) { ?>
		<div id="test-server-warning">
			<div class= "vertical-section">
				This is a test version of the site
			</div>
		</div>
	<?php } ?>

		<header id="masthead" class="vertical-section" role="banner">
			<div id="title-and-description">
				<a id="site-title" class="visually-hidden" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<h1><?php bloginfo( 'name' ); ?></h1>
				</a>
				<div id="site-description" class="visually-hidden"><?php bloginfo( 'description' ); ?></div>
			</div>
			<img id="mascot" src="<?php echo get_template_directory_uri(); ?>/img/cat.png" alt="A cat playing a violin like a cello"/>
		</header><!-- #masthead -->

		<?php print_menu(); ?>