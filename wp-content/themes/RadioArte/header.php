<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<style type="text/css" media="screen">

	<?php
	// Checks to see whether it needs a sidebar or not
	if ( empty($withcomments) && !is_single() ) {
	?>
		#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbg-<?php bloginfo('text_direction'); ?>.jpg") repeat-y top; border: none; }
	<?php } else { // No sidebar ?>
		#page { background: url("<?php bloginfo('stylesheet_directory'); ?>/images/kubrickbgwide.jpg") repeat-y top; border: none; }
	<?php } ?>

	</style>

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/application.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function(){
		
			jQuery('#map area').hide().mouseover(function(e){
				jQuery('#' + e.target.id.match(/[a-z]*_([a-z_-]*)/)[1]).show();
				jQuery('#' + e.target.id.match(/[a-z]*_([a-z_-]*)/)[1]).css('visibility', 'visible');
			}).mouseout(function(e){
				jQuery('#' + e.target.id.match(/[a-z]*_([a-z_-]*)/)[1]).hide();
			}).click(function(e){
				window.location = e.target.href;
			});

		
			});



</script>

</head>
<body>
	
<div id="container">

<div id="header">

	<h1><a href="/">Radio Arte</a></h1>
	<div id="map">
		<img id="transparent_map"  usemap="#nav_map" alt="" src="<?php bloginfo('template_url'); ?>/images/transp.gif"/>
			<img src="<?php bloginfo('template_url'); ?>/images/nav.gif" height="143" border="0" alt="">
	<map name="nav_map" id="nav_map">
	<area id="nav_home" shape="poly" coords="4,141,87,144,226,2,147,1,4,142" href="/" />
	<area id="nav_programming" shape="poly" coords="99,143,179,143,317,2,231,2,98,142,188,142,266,143" href="/programming" />
	<area id="nav_about" shape="poly" coords="187,140,267,143,403,5,323,2,185,140" href="/about" />
	<area id="nav_get-involved" shape="poly" coords="275,144,357,142,492,3,415,3,275,142,366,142" href="/get-involved" />
	<area id="nav_support" shape="poly" coords="367,140,445,143,584,3,506,3,366,140" href="/support" />
	<area id="nav_contact" shape="poly" coords="459,141,538,141,676,5,596,3,458,142" href="/contact" />
	</map>
	
	<ul id="nav">
		<li id="home" class="<?php if(is_home()) echo 'active'; ?>"><a href="/">Home</a></li>
		<li id="about" class="<?php if(is_page('About')) echo 'active'; ?>"><a href="/about">About</a></li>
		<li id="programming" class="<?php if(is_page('Programming')) echo 'active'; ?>"><a href="/programming">Programming</a></li>
		<li id="get-involved" class="<?php if(is_page('Get Involved')) echo 'active'; ?>"><a href="/get-involved">Get Involved</a></li>
		<li id="support" class="<?php if(is_page('Support')) echo 'active'; ?>"><a href="/support">Support</a></li>
		<li id="contact" class="<?php if(is_page('Contact')) echo 'active'; ?>"><a href="/contact">Contact</a></li>
	</ul>
	</div>
	<div id="station">90/5fm</div>
</div>