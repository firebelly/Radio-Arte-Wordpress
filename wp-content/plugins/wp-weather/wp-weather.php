<?php
/*
Plugin Name: wp-weather
Plugin URI: http://devblog.x-sphere.com/index.php/projects/wordpress/wp-weather
Description: Weather.com widget - shows forecast information for a city.  Displays image related to current conditions.  Weather can also be inserted into a post or page via the shortcode [weather_display].  Go <a href="plugins.php?page=wp-weather-options-config">here</a> to configure.  
Version: 0.2.9.2
Author: Matt Brotherson
Author URI: http://devblox.x-sphere.com/



Changelog

2007-07-31		0.2			Added PHP4 support.
2007-08-01		0.2.1		Fixed $is_php_5 (was static coded.)
2007-08-08		0.2.2		Added metric unit support.
2007-10-24		0.2.3		Added ability to select whether to use theme/custom css or stock css.
2008-02-08		0.2.4		Fixed widget formatting.
2008-04-26		0.2.5		Added option to show tonight's conditions.
							Changed the way config page is registered.
				0.2.6		Added nonce field.
2008-05-06		0.2.7		Added new required URL parameter.  (link=xoap)
2008-08-15		0.2.8		Changed request to weather.com to use wp's snoopy class.
2008-08-25		0.2.9		Added shortcode [weather_display] for use in post/page.
2008-08-26		0.2.9.1		Fixed bug in shortcode that made content display immediately.
2009-06-09		0.2.9.2		Added check for empty xml_data.
*/

add_action('activate_wp-weather/wp-weather.php','weather_install');
add_action('init', 'weather_init');


if ( !function_exists('wp_nonce_field') ) {
	function weather_nonce_field($action = -1) { return; }
	$weather_nonce = -1;
} else {
	function weather_nonce_field($action = -1) { return wp_nonce_field($action); }
	$weather_nonce = 'weather-update-key';
}

function weather_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('plugins.php', __('wp-weather'), __('wp-weather'), 'manage_options', 'wp-weather-options-config', 'weather_manager');
}

function weather_install()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "weatherxml";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name){

		$sql = "CREATE TABLE ".$table_name." (
		  xml_url varchar(150) NOT NULL default '',
		  xml_data text NOT NULL,
		  last_updated datetime NOT NULL default '0000-00-00 00:00:00',
		  KEY xml_url (xml_url)
		) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		dbDelta($sql);
	}

}

function weather_manager() {
	global $partner_id, $license_key, $location_id, $forecast_length, $image_size, $units, $use_own_css, $weather_show_tonight;

	add_action( 'in_admin_footer', 'weather_admin_footer' );

 	echo '<div class="wrap">';
	echo "<h2>WP-Weather Manager</h2>\n";

	if (isset($_POST['submit']))
	{
		$partner_id					= $_POST['weather_partner_id'];
		$license_key				= $_POST['weather_license_key'];
		$location_id				= $_POST['weather_location_id'];
		$forecast_length			= $_POST['weather_forecast_length'];
		$image_size					= $_POST['weather_image_size'];
		$units						= $_POST['weather_units'];
		$use_own_css				= $_POST['weather_own_css'] == 'on' ? 'true' : 'false';
		$weather_show_tonight		= $_POST['weather_show_tonight'] == 'on' ? 'true' : 'false';

		//echo $_POST['weather_show_tonight'];

		check_admin_referer( $weather_nonce );
		weather_update_options();

		echo '<div id="message" class="updated fade"><p><strong>Options saved.</strong></p></div>';
	}

	echo '<form name="wp_weather_manager" action="" method="post" style="margin: auto; width: 650px; ">';	
	weather_nonce_field($weather_nonce);
	echo '<p>This plugin gathers weather information from weather.com and displays it via a widget.  You must obtain a partner id and license key from weather.com to use this widget.  Sign up for Weather.com\'s free XML service at <a href="http://www.weather.com/services/xmloap.html">Weather.com XML service</a>.  The data from weather.com is cached in the database for one half hour to avoid unnecessary calls to the web service.</p>
	<ol>
		<li>Weather.com partner id: <input type="text" name="weather_partner_id" value="'
		. htmlentities($partner_id) . '" /></li>
		<li>Weather.com license key:<input type="text" name="weather_license_key" value="'
		. htmlentities($license_key) .'" /></li>
		<li>Location id (zip code or city code):<input type="text" name="weather_location_id" value="'
		. htmlentities($location_id) .'" /></li>
		<li>Forecast length (in days):<input type="text" name="weather_forecast_length" value="'
		. htmlentities($forecast_length) .'" /></li>
		<li>Forecast unit type:<input type="radio" value="s" name="weather_units" ';
			if ($units == "s")
				echo 'checked';
		echo ' />Standard&nbsp;<input type="radio" value="m" name="weather_units" ';
			if ($units == "m")
				echo 'checked';
		echo ' />Metric
		<li>Image size: 
			<select name="weather_image_size">
				<option ';
					if ($image_size == "1")
						echo 'selected';			
				echo ' value="1">32x32</option>
				<option ';
					if ($image_size == "2")
						echo 'selected';
				echo ' value="2">64x64</option>
				<option ';
					if ($image_size == "3")
						echo 'selected';			
				echo ' value="3">128x128</option>
			</select>
		</li>
		
		<li>Use your own css (theme or custom): 
			<input type="checkbox" name="weather_own_css"
				 ';
					if ($use_own_css  == "true" )
						echo ' "checked"';			
				
				echo ' />
			</select>
		</li>
		<li>Show tonight\'s conditions: 
			<input type="checkbox" name="weather_show_tonight"
				 ';
					if ($weather_show_tonight  == "true" )
						echo ' "checked"';			
				
				echo ' />
			</select>
		</li>
		
	</ol>
	<p class="submit"><input type="submit" name="submit" value="Update options &raquo;" /></p>
		</form></div>';

}

function weather_init()
{
	global $partner_id, $license_key, $location_id, $forecast_length, $units, $image_size, $forecast_url, $is_php_5, $use_own_css, $weather_show_tonight;

	$partner_id             = get_option('weather_partner_id');
	$license_key			= get_option('weather_license_key');
	$location_id            = get_option('weather_location_id');
	$forecast_length        = get_option('weather_forecast_length');
	$image_size				= get_option('weather_image_size');
	$units					= get_option('weather_units');
	$use_own_css			= get_option('weather_own_css') == 'true' ? true : false;
	$weather_show_tonight	= get_option('weather_show_tonight') == 'true' ? true : false; 

	$is_php_5				= phpversion() > 5 ? true: false;
	
	$forecast_url			= 'http://xoap.weather.com/weather/local/' . $location_id . '?cc=*&dayf=' .$forecast_length .'&prod=xoap&par=' . $partner_id . '&key=' . $license_key . '&link=xoap&unit=' . $units;

	//echo $forecast_url;

	// Add the menu.
	add_action('admin_menu', 'weather_config_page');

}

function weather_update_options()
{
	global $partner_id, $license_key, $location_id, $forecast_length, $image_size, $units, $use_own_css, $weather_show_tonight;

	update_option('weather_partner_id', $partner_id);
	update_option('weather_license_key', $license_key);
	update_option('weather_location_id', $location_id);
	update_option('weather_forecast_length', $forecast_length);
	update_option('weather_image_size', $image_size);
	update_option('weather_units', $units);
	update_option('weather_own_css', $use_own_css);
	update_option('weather_show_tonight', $weather_show_tonight);

	// Clean out the storage table if the options have changed.
	weather_empty_table();

	// Clean out the cache if wp-cache in use.
	weather_clean_cache();	
}

function weather_clean_cache()
{
	global $cache_enabled, $file_prefix;

	if (!$cache_enabled) 
		return;

	wp_cache_phase2_clean_cache($file_prefix);
}

function weather_empty_table()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "weatherxml";
	$query = "TRUNCATE TABLE $table_name";
	$result = $wpdb->query($query);
}


function weather_display($returnHtml = false)
{
	global $wpdb, $partner_id, $license_key, $location_id, $forecast_length, $image_size, $forecast_url, $is_php_5, $use_own_css;


	$table_name = $wpdb->prefix . "weatherxml";
	$datetime = date("Y-m-d h:i:s");
	$xml_url = md5($forecast_url);
	$interval = .5;	// Hours to keep data in db before being considered old
	$expires = $interval*60*60;
	$expiredatetime = date("Y-m-d H:i:s", time() - $expires);

	$query = "SELECT xml_url, xml_data, last_updated FROM $table_name WHERE xml_url = '$xml_url'"; 
	$result = $wpdb->get_row($query);
	$time_diff = strtotime($datetime) - strtotime($result->last_updated);

	if ($time_diff > $expires || $time_diff < 0 || empty($result->xml_data))
	{
		weather_empty_table();
	}

	if (!isset($result->last_updated) || empty($result->xml_data) )
	{

		// Get XML Query Results from Weather.com
		//$fp = fopen($forecast_url,"r");
		//while (!feof ($fp))
		//	$xml .= fgets($fp, 4096);
		//fclose ($fp);

		require_once( ABSPATH . WPINC . '/class-snoopy.php' );
		$weather_snoopy = new Snoopy();
		$weather_snoopy->agent = 'WordPress/' . $wp_version;
		$weather_snoopy->read_timeout = 2;

		if( !$weather_snoopy->fetch( $forecast_url )) {
			die( "alert('Could not connect to lookup host.')" );
		}

		$xml = $weather_snoopy->results;

		// Fire up the built-in XML parser
		$parser = xml_parser_create(  ); 
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);

		// Set tag names and values
		xml_parse_into_struct($parser,$xml,$values,$index); 

		// Close down XML parser
		xml_parser_free($parser);

		if ($location_id) // Only inserts forecast feed, not search results feed, into db
		{
			$query = "INSERT INTO $table_name VALUES ('$xml_url', '$xml', '$datetime')";
			$result = $wpdb->query($query) or die('Invalid query: ' . mysql_error());
		}

		//$xml = new SimpleXMLElement($xml);
	}
	else // Data in table, and it is within expiration period - do not load from weather.com and use cached copy instead.
	{
		$xml = $result->xml_data;
	}

	$htmlstring = parseXml($xml, $is_php_5);

	if (!$returnHtml)
		echo $htmlstring;

	return $htmlstring;
}

function parseXml($xml, $is_php_5)
{
	$htmlstring = '';
	if ($is_php_5)
		$htmlstring = parseSimpleXml($xml);
	else
		$htmlstring = parseIsterSimpleXml($xml);

	return $htmlstring;
}

function parseSimpleXml($xml)
{
	global $wpdb, $partner_id, $license_key, $location_id, $forecast_length, $image_size, $forecast_url, $is_php_5, $weather_show_tonight;

	$xml = new SimpleXmlElement($xml);

	switch ($image_size)
	{
		case "1":
			$image_dimensions = "32x32";
			break;
		case "2":
			$image_dimensions = "64x64";
			break;
		case "3":
			$image_dimensions = "128x128";
			break;
	}

	$day_forecast = $xml->dayf;

	$htmlstring = '';

	$htmlstring .= '<div class="weather_info">';
	$htmlstring .= '<p class="temp">';
	$htmlstring .= $xml->cc->tmp.'&#730; </p>';
	$htmlstring .= '<p class="feels_like">Feels Like: '.$xml->cc->flik.'&#730; '.$xml->head->ut.'</p>';
	$htmlstring .= '<div class="weather_image"><img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$xml->cc->icon.'.png" alt="'.$xml->cc->t.'" /></div>';

	if ($weather_show_tonight)
	{
		$htmlstring .= '<p>Tonight: '.$day_forecast->day[0]->low.'&#730;<br/>';
		$htmlstring .= 'Sunset: '. $day_forecast->day[0]->suns.'<br/>';
		$htmlstring .= 'Moon Phase: '. $xml->cc->moon->t.'<br/>';
		$htmlstring .= '<img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$day_forecast->day[0]->part[1]->icon.'.png" alt="'.$day_forecast->day[0]->part[1]->t.'" /><br/>';
		$htmlstring .= '</p>';
	}
	
	if (sizeof($day_forecast->day) > 1)
	{

		foreach($day_forecast->day as $day)
		{	
			if ($day[d] == "0")
				continue;
			$htmlstring .= '<p>';
			$htmlstring .= '<i>'.$day[t] . ', ' . $day[dt].'</i><br/>';
			$htmlstring .= '<strong>Hi: ' . $day->hi . '&#730;, ';
			$htmlstring .= 'Lo: ' . $day->low . '&#730;</strong><br/>';
			$htmlstring .= '<img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$day->part[0]->icon.'.png" alt="'.$day->part[0]->t.'" />';
			$htmlstring .= '</p>';


		}
	}
	

	//$htmlstring .= '<p class="weather_info">weather feed courtesy of <a href="http://www.weather.com/?prod=xoap&amp;par=' . $partner_id . '" title="weather.com">weather.com</a> - thanks!</p>';
	$htmlstring .= '</div>';

	return $htmlstring;
}

function parseIsterSimpleXml($xml)
{
	global $wpdb, $partner_id, $license_key, $location_id, $forecast_length, $image_size, $forecast_url, $is_php_5, $weather_show_tonight;

	require_once(dirname(__FILE__).'/simplexml44/IsterXmlSimpleXMLImpl.php');
  
	// read and write a document
	$impl = new IsterXmlSimpleXMLImpl;
	$xml = $impl->load_string($xml);

	switch ($image_size)
	{
		case "1":
			$image_dimensions = "32x32";
			break;
		case "2":
			$image_dimensions = "64x64";
			break;
		case "3":
			$image_dimensions = "128x128";
			break;
	}

	$day_forecast = $xml->weather->dayf;
	$days = $day_forecast->children();

	$attributes = $xml->weather->loc->attributes();
	$location_id = $attributes['id'];
	$day_attributes = $days[1]->attributes();

	$day = $day_attributes['t'];
	$date = $day_attributes['dt'];

	$current_temp = $xml->weather->cc->tmp->CDATA();
	$feels_like = $xml->weather->cc->flik->CDATA();
	$current_conditions = $xml->weather->cc->t->CDATA();
	$temp_unit = $xml->weather->head->ut->CDATA();
	$image_icon = $xml->weather->cc->icon->CDATA();
	
	
	$high_temp = $days[1]->hi->CDATA();
	$low_temp = $days[1]->low->CDATA();

	
	$htmlstring .= '<div class="weather_info">';
	$htmlstring .= '<p><a href="http://www.weather.com/weather/local/' . $location_id .'" title="Forecast for '.$day . ', ' . $date.'">';
	$htmlstring .= $day . ", " . $date;
	$htmlstring .= '</a>';
	$htmlstring .= '<br />'.$current_conditions.'<br/>';
	$htmlstring .= 'Currently: <strong>'.$current_temp.'&#730; </strong><br/>';
	$htmlstring .= 'Feels Like: '.$feels_like.'&#730; '.$temp_unit.'<br/>';
	$htmlstring .= '<strong>Hi: ' . $high_temp . '&#730;, ';
	$htmlstring .= 'Lo: ' . $low_temp . '&#730;</strong><br/>';
	$htmlstring .= '<img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$image_icon.'.png" alt="'.$current_conditions.'" /></p>';

	if ($weather_show_tonight)
	{
		$htmlstring .= '<p>Tonight: '.$days[1]->low->CDATA().'&#730;<br/>';
		$htmlstring .= 'Sunset: '. $days[1]->suns->CDATA().'<br/>';
		$htmlstring .= 'Moon Phase: '. $xml->weather->cc->moon->t->CDATA().'<br/>';
		$htmlstring .= '<img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$days[1]->part[1]->icon->CDATA().'.png" alt="'.$days[1]->part[1]->t->CDATA().'" /><br/>';
		$htmlstring .= '</p>';
	} 

	if (sizeof($days) > 2)
	{

		for($i = 1; $i < sizeof($days); $i++)
		{	

			$attributes = $days[$i]->attributes();

			if ($attributes['d'] == "0")
				continue;

			$day = $attributes['t'];
			$date = $attributes['dt'];

			$high_temp = $days[$i]->hi->CDATA();
			$low_temp = $days[$i]->low->CDATA();
			$image_icon = $days[$i]->part[0]->icon->CDATA();
			$conditions = $days[$i]->part[0]->t->CDATA();

			$htmlstring .= '<p>';
			$htmlstring .= '<i>'.$day . ', ' . $date.'</i><br/>';
			$htmlstring .= '<strong>Hi: ' . $high_temp . '&#730;, ';
			$htmlstring .= 'Lo: ' . $low_temp . '&#730;</strong><br/>';
			$htmlstring .= '<img border="0" src="'. get_bloginfo(wpurl).'/wp-content/plugins/wp-weather/images/'.$image_dimensions.'/'.$image_icon.'.png" alt="'.$conditions.'" />';
			$htmlstring .= '</p>';
		}
	}
	
	$htmlstring .= '<p class="weather_info">weather feed courtesy of <a href="http://www.weather.com/?prod=xoap&amp;par=' . $partner_id . '" title="weather.com">weather.com</a> - thanks!</p>';
	$htmlstring .= '</div>';

	return $htmlstring;
}


// Widget stuff
function widget_weather_register() {
	if ( function_exists('register_sidebar_widget') ) :
	function widget_weather($args) {
		extract($args);
		$options = get_option('widget_weather');		
		?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $options['title'] . $after_title; ?>
			<?php echo '<ul><li>'; ?>
				<?php weather_display(); ?>
			<?php echo '</li></ul>'.$after_widget; ?>			
	<?php
	}
	
	function widget_weather_style() {
		
		?>
		<!--	wp-weather widget css http://devblog.x-sphere.com	-->
			<style type="text/css">
				.weather_info
				{
					font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
					font-size: 11px;
				}	
			</style>
		<?php
	}

	function widget_weather_control() {
		$options = $newoptions = get_option('widget_weather');
		if ( $_POST["weather-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["weather-title"]));
			if ( empty($newoptions['title']) ) $newoptions['title'] = 'Local Weather';
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_weather', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
	?>
				<p><label for="weather-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="weather-title" name="weather-title" type="text" value="<?php echo $title; ?>" /></label></p>
				<input type="hidden" id="weather-submit" name="weather-submit" value="1" />
	<?php
	}

	global $use_own_css;

	register_sidebar_widget('Weather', 'widget_weather', null, 'weather');
	register_widget_control('Weather', 'widget_weather_control', 300, 75, 'weather');

	if ( is_active_widget('widget_weather') && !$use_own_css)
		add_action('wp_head', 'widget_weather_style');
	endif;
}

function weather_shortcode_handler($atts, $content=null)
{
	return 	weather_display(true);
}

add_shortcode('weather_display', 'weather_shortcode_handler');

function weather_admin_footer() {
	$plugin_data = get_plugin_data( __FILE__ );
	printf('%1$s plugin | Version %2$s<br />', $plugin_data['Title'], $plugin_data['Version']);
}


add_action('init', 'widget_weather_register');
?>