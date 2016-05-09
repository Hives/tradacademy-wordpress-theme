<!DOCTYPE html>
<?php $environment = WP_DEBUG == true ? "local" : "production"; ?>
<!--[if lt IE 7]>      <html class="no-js <?php echo $environment; ?> lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js <?php echo $environment; ?> lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js <?php echo $environment; ?> lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js <?php echo $environment; ?>"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		 <title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<meta name="viewport" content="width=device-width">
		<!-- Place favicon.ico and apple-touch-icon.png in img/icons -->
		<!-- <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" type="image/vnd.microsoft.icon"> -->
		<!-- <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" /> -->
		<?php wp_head(); ?>
	</head>
	<body class="clearfix">
		<!--[if lt IE 7]>
			<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
		<![endif]-->

		<?php // https://developers.facebook.com/docs/reference/plugins/like/ ?>
		<div id="fb-root"></div>
		<script>
		  window.fbAsyncInit = function() {
		    // init the FB JS SDK
		    FB.init({
		      // appId      : 'YOUR_APP_ID',                        					// App ID from the app dashboard
		      channelUrl : '<?php echo get_template_directory_uri(); ?>/fb/channel.php', // Channel file for x-domain comms
		      status     : true,                   						              // Check Facebook Login status
		      xfbml      : true                   						              // Look for social plugins on the page
		    });

		    // Additional initialization code such as adding Event Listeners goes here
		  };

		  // Load the SDK asynchronously
		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>

		<header id="masthead" role="banner">
			<div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<h1 class="ir" id="site-title"><?php bloginfo( 'name' ); ?></h1>
				</a>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>
			</div>
			<img id="mascot" src="<?php echo get_template_directory_uri(); ?>/img/cat.png" alt="A cat playing a violin like a cello"/>
		</header><!-- #masthead -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h2>Navigation</h2>
			<?php // wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			<?php print_menu( ); ?>
		</nav>
