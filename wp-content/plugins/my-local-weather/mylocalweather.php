<?php

/*

Plugin Name: My Local Weather

Plugin URI: http://blog.collinsinternet.com/30/my-local-weather-wordpress-plugin/

Description: This plugin will display the local weather conditions from Yahoo! Weather.

Author: Allan Collins

Version: 1.1.2

Author URI: http://www.allancollins.net

*/

/*

Copyright (C) 2009 Collins Internet / Allan Collins



This program is free software; you can redistribute it and/or modify

it under the terms of the GNU General Public License as published by

the Free Software Foundation; either version 3 of the License, or

(at your option) any later version.



This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY; without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

GNU General Public License for more details.



You should have received a copy of the GNU General Public License

along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/



function my_local_weather() {



	

	$zipcode=get_option('mlw_zip');

	if ($zipcode != '') {

		include_once(ABSPATH . WPINC . '/rss.php');

		$weather_url="http://weather.yahooapis.com/forecastrss?p=" . $zipcode;

		$xml=fetch_rss($weather_url);

		if ($xml) {


			$weather=$xml->items[0];
			$weather=$weather['summary'];
			print_r($weather);
				preg_match('/(.*?)F<B/msi',$weather,$match);
				$curW=str_replace("F<B","&deg;F",$match[0]);
				$curW=str_replace("<b>Current Conditions:</b><br />","",$curW);
				echo $curW;
			
			if (get_option('mlw_logo') == 'yes') {

				echo "<br/><br/><small>My Local Weather by <a href=\"http://www.allancollins.net/\">Allan Collins</a></small>";

			}

		}

	

	}





}



function mylocalweather_options() {

	echo "<div style=\"margin-left:200px; width:250px;\" class=\"wrap\">";

	echo "<h2>My Local Weather</h2>";

	echo "Enter your zip code below to initiate the local weather functionality.<br/><br/>";

	echo '<form method="post" action="options.php">';

	wp_nonce_field('update-options');

	echo 'Zip Code: <input type="text" name="mlw_zip" value="' .get_option('mlw_zip') . '" /><br/><br/>';

	if (get_option('mlw_logo') == 'yes') {

		$yes_select="selected";

	}else{

		$no_select="selected";

	}

	echo 'Show Link: <select name="mlw_logo"> <option value="yes" ' . $yes_select . '>Yes</option><option value="no" ' . $no_select . '>No</option></select><br/>';

	echo '<input type="hidden" name="action" value="update" />';

	echo '<input type="hidden" name="page_options" value="mlw_zip,mlw_logo" />';

	echo '<p class="submit">

	<input type="submit" name="Submit" value="Save Changes" />

	</p>';

	echo "</div>";

}



/* WIDGET */

function mlw_register() {



	function mlw_widget($args) {

	

		extract($args);

	   echo $before_widget; 

	   echo $before_title . $after_title; 

	   my_local_weather();			

	   echo $fssText;

	   echo $after_widget; 

	   

      }

	  

	register_sidebar_widget('My Local Weather','mlw_widget');

	



	

	

}











// Hook for adding admin menus

add_action('admin_menu', 'mlw_add_pages');

add_action('init', 'mlw_register');





// action function for above hook

function mlw_add_pages() {

	add_options_page('My Local Weather', 'Local Weather', 8, 'mylocalweather', 'mylocalweather_options');

}

function mlw_dashFeed() {
?>
<h2 style="text-decoration:underline;padding-top:0;margin-top:0;"><?php _e('Latest Entries'); ?></h2> <br />

<?php // Get RSS Feed(s)
include_once(ABSPATH . WPINC . '/rss.php');
$rss = fetch_rss('http://feeds2.feedburner.com/allancollins');
$maxitems = 3;
$items = array_slice($rss->items, 0, $maxitems);
?>

<ul>
<?php if (empty($items)) echo '<li>No items</li>';
else
foreach ( $items as $item ) : ?>
<li><a href='<?php echo $item['link']; ?>' 
title='<?php echo $item['title']; ?>'>
<?php echo $item['title']; ?>
</a></li>
<?php endforeach; ?>
</ul> <br />

<a href="http://feeds2.feedburner.com/allancollins" style="float:left;"><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/my-local-weather/rss.gif" border="0" /></a><a href="http://feeds2.feedburner.com/allancollins" style="float:left;padding-top:5px;"><strong>Subscribe to Feed</strong></a>
<br style="clear:both;" /> <br />
<p align="right">
<a href="http://www.allancollins.net/"><small>Plugin Support</small></a></p>
<?php 
}
add_action('wp_dashboard_setup', 'mlw_dashboard' );
function mlw_dashboard() {

if (function_exists(wp_add_dashboard_widget)) {
	if (!function_exists(fss_dashFeed)) {
		wp_add_dashboard_widget('ac_blog', 'Allan Collins Blog', 'mlw_dashFeed');	
	}
}
}
?>