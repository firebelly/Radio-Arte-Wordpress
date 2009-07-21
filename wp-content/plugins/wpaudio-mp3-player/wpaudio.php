<?php

/*
Plugin Name: WPaudio
Plugin URI: http://wpaudio.com
Description: Play mp3s in your posts by converting audio tags and/or links to a totally customizable CSS-skinnable Javascript-controlled audio player.  (Skinning coming soon.)
Version: 1.2
Author: Todd Iceton
Author URI: http://ticeton.com

Copyright 2009 Todd Iceton (email : t@ticeton.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

player.swf is the JW Player from LongTail Video
*/

## TO DO
## - Integrate with attachment add (js and post - look at highslide)
## - Don't call scripts if no audio
## - OO for wpaFilter
## - Add option for built-in jQuery

## Pre-2.6 compatibility (from WP codex)
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

## WP handlers
# Built-in jQuery (inactive -- currently using Google CDN)
# add_action('init', 'wpaLibraries');
# Add header action to include CSS and call Google CDN JQuery/SWFObject
add_action('wp_head', 'wpaHead');
# Add shortcode for WPaudio player
add_shortcode('wpaudio', 'wpaShortcode');
# Add filter for non-shortcode substitutes (including excerpts)
if (get_option('wpa_tag_audio')) {
	add_filter('the_content', 'wpaFilter');
	add_filter('the_excerpt', 'wpaFilter');
}

## Built-in libraries
function wpaLibraries(){
	wp_enqueue_script('jquery');
}

## WPaudio style, jQuery, SWFObject
function wpaHead(){
	# Player CSS
	echo <<<WPA
<style type="text/css">
.wpa_container {position: relative; margin: 0 0 15px 0; padding: 0; font-family: Arial, Sans-serif; line-height: 1; text-align: left;}
.wpa_play {position: absolute; top: 0; left: 0; width: 16px; height: 15px; margin-top: 2px; background: #777 url(
WPA;
	echo WP_PLUGIN_URL . '/wpaudio-mp3-player/wpa_button.gif) 0px 0px; cursor: pointer; overflow: hidden;}';
	echo <<<WPA
.wpa_meta, .wpa_bar, .wpa_sub {margin-left: 20px;}
.wpa_meta {font-size: 18px; font-weight: bold; letter-spacing: -1px; line-height: 18px;}
.wpa_meta a {text-decoration: none; color: #14BAE7;}
.wpa_meta a:hover {color: #14BAE7;}
.wpa_bar {display: none; position: relative; height: 5px; margin-top: 2px; background: #eee; overflow: hidden;}
.wpa_bar_load {position: absolute; top: 0; left: 0; width: 0; height: 5px; z-index: 10; background: #ccc;}
.wpa_bar_position {position: absolute; top: 0; left: 0; width: 0; height: 5px; z-index: 11; background: #14BAE7;}
.wpa_bar_click {position: absolute; top: 0; left: 0; width: 100%; height: 5px; z-index: 12; background: transparent; cursor: pointer;}
.wpa_sub {display: none; margin-top: 2px; font-size: 11px; color: #14BAE7;}
.wpa_time {}
</style>
WPA;
	# Call Google CDN JQuery
	echo <<<WPA
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
WPA;
	# Common JS
	echo <<<WPA
<script type="text/javascript">
// Preferences and functions common to all players
var wpa_pref_bar = true;
var wpa_pref_sub = true;
$(document).ready(function(){
	// Click functions common to all players
	$('.wpa_play').click(function(){ //DONE
		// Play/pause on click of play button
		var wpa_id = $(this).attr('id').split('_',1);
		window[wpa_id + '_player'].sendEvent('PLAY');
		if (wpa_pref_bar) {
			$('#' + wpa_id + '_bar').slideDown('slow', function(){
				if (wpa_pref_sub) {
					$('#' + wpa_id + '_sub').fadeIn('slow');
				}
			});
		}
	});
	$('.wpa_bar_click').click(function(e){ //DONE
		// Allow clicks on status bar to jog track
		var wpa_id = $(this).attr('id').split('_',1);
		if (e.pageX) {
			var pos = (e.pageX - $(this).offset()['left']) / $(this).width();
			var sec = window[wpa_id + '_duration'] * pos;
			window[wpa_id + '_player'].sendEvent('SEEK', sec);
		}
	});
});
function playerReady(obj){
	// Add listeners to pull id3, load, time (position), and state (playing, paused) data
	var wpa_id = obj['id'];
	window[wpa_id + '_player'] = document.getElementById(obj['id']);
	// For some reason, the wpa0, wpa1, wpa2, ... objects aren't accessible by window[wpa_id]
	window[wpa_id + '_player'].addModelListener('LOADED', 'wpaLoadListener');
	window[wpa_id + '_player'].addModelListener('TIME', 'wpaTimeListener');
	window[wpa_id + '_player'].addModelListener('STATE', 'wpaStateListener');
	if (window[wpa_id + '_text'])
		wpaText(wpa_id, window[wpa_id + '_text']);
	else {
		window[wpa_id + '_player'].addModelListener('META', 'wpaMetaListener');
		window[wpa_id + '_player'].sendEvent('VOLUME', 0);
		window[wpa_id + '_player'].sendEvent('PLAY');
	}
}
function wpaMetaListener(obj){ //DONE
	// Pull id3 data on media load
	var wpa_id = obj['id'];
	var id3_string;
	if (obj['name']) id3_string = obj['name'];
	if (obj['artist']) id3_string = obj['artist'] + ' - ' + id3_string;
	if (id3_string) wpaText(wpa_id, id3_string);
	// File is auto-started to get ID3 data, stop it once ID3 is retrieved
	if (!window[wpa_id + '_text']) {
		window[wpa_id + '_text'] = (id3_string) ? id3_string : true;
		window[wpa_id + '_player'].sendEvent('STOP');
		window[wpa_id + '_player'].sendEvent('VOLUME', 90);
	}
}
function wpaLoadListener(obj){ //DONE
	// Display load status of media by setting % of loaded progress bar
	var wpa_id = obj['id'];
	if (!(obj['loaded'] && obj['total'])) return;
	$('#' + wpa_id + '_bar_load').width(obj['loaded']/obj['total']*100 + '%');
}
function wpaTimeListener(obj){ //DONE
	var wpa_id = obj['id'];
	// Display time data and set % of progress bar
	if (!(obj['duration'] && obj['position'])) return;
	window[wpa_id + '_duration'] = obj['duration'];
	$('#' + wpa_id + '_bar_position').width(obj['position']/obj['duration']*100 + '%');
	$('#' + wpa_id + '_position').text(wpaTimeFormat(obj['position']));
	$('#' + wpa_id + '_duration').text(wpaTimeFormat(obj['duration']));
}
function wpaTimeFormat(seconds){ //DONE
	// Given time in seconds, convert to "m:s" format
	var min = Math.floor(seconds / 60);
	var sec = Math.floor(seconds % 60);
	var time_string = min + ':';
	// Add leading 0 to seconds if necessary
	if (sec<10) time_string += '0';
	time_string += sec;
	return time_string;
}
function wpaStateListener(obj){ //DONE
	var wpa_id = obj['id'];
	// Change the play button to pause or vice versa (uses sprites), also blink time when paused
	if (obj['newstate'] == 'PLAYING' && window[wpa_id + '_text'])
		wpaButtonToggle(wpa_id, true);
	if (obj['newstate'] == 'PAUSED' || obj['newstate'] == 'COMPLETED' || obj['newstate'] == 'IDLE')
		wpaButtonToggle(wpa_id, false);
}
function wpaText(wpa_id, text){
	$('#' + wpa_id + '_meta').text(text);
	$('#' + wpa_id + '_bar').width($('#' + wpa_id + '_meta').width());
}
function wpaButtonToggle(wpa_id, playing){
	if (playing) $('#' + wpa_id + '_play').css('backgroundPosition', '0px 15px');
	else $('#' + wpa_id + '_play').css('backgroundPosition', '0px 0px');
}
</script>
WPA;
}

# Declare global wpa_id to be incremented for multiple players
$wpa_id = 0;

# Used only for wpaudio shortcode tags
function wpaShortcode($atts){
	# Convert shortcodes to WPaudio player depending on settings
	extract(shortcode_atts(Array(
		'url' => false,
		'text' => false,
		'dl' => true
	), $atts));
	# If no url, return with nothing
	if (!url)
		return;
	# ID number for players, will be incremented for each player instance
	global $wpa_id;
	# Get player HTML and JS
	$player = wpaPlayerHtml($wpa_id, $url, $text, $dl);
	$player .= wpaPlayerJs($wpa_id, $url, $text, $dl);
	$wpa_id++;
	return $player;
}

# Used for audio tags
function wpaFilter($content){
	# Convert audio tags and links to WPaudio player depending on settings
	$tag_regex = '/\[audio:(.*?)\]/';
	$link_regex = '/<a href=["\'](http:\/\/.*?\.mp3)["\']>.*?<\/a>/';
	$tag_match = preg_match_all($tag_regex, $content, $tag_matches);
	$link_match = preg_match_all($link_regex, $content, $link_matches);
	# If no matches, return content unchanged
	if (!($tag_match || $link_match))
		return $content;
	# ID number for players, will be incremented for each instance
	global $wpa_id;
	# String for unique player JS
	$player_js = '';
	# Replace audio tags with player
	if ($tag_match){
		foreach ($tag_matches[1] as $key => $value){
			$player_html = wpaPlayerHtml($wpa_id, $value);
			$content = str_replace($tag_matches[0][$key], $player_html, $content);
			$player_js .= wpaPlayerJs($wpa_id, $value);
			$wpa_id++;
		}
	}
	### FIX PATHS
	# Create document.ready with player_js
	$content .= $player_js;
	return $content;
}

# Common to all players
function wpaPlayerHtml($id, $url, $text = false, $dl = true){
	# Return the html for a player given id
	$html = <<<WPA
<!-- wpa0 html begin -->
<div class="wpa_container">
	<div id="wpa0_play" class="wpa_play"></div>
	<div><!-- req'd for correct IE6 display -->
		<div class="wpa_meta">
WPA;
	if ($dl) $html .= '<a id="wpa0_meta" href="MP3URL" target="_blank">MP3BASE</a>';
	else $html .= 'MP3BASE';
	$html .= <<<WPA
<span id="wpa0_placeholder"><br><a href="http://get.adobe.com/flashplayer/">Get Adobe Flash Player</a> to play audio from this site.</span>
		</div>
		<div id="wpa0_bar" class="wpa_bar">
			<div id="wpa0_bar_load" class="wpa_bar_load"></div>
			<div id="wpa0_bar_position" class="wpa_bar_position"></div>
			<div id="wpa0_bar_click" class="wpa_bar_click"></div>
		</div>
		<div id="wpa0_sub" class="wpa_sub">
			<div id="wpa0_time" class="wpa_time"><span id="wpa0_position">0:00</span> / <span id="wpa0_duration">0:00</span></div>
		</div>
	</div>
</div>
<!-- wpa0 html end -->
WPA;
	$html = str_replace('wpa0', "wpa$id", $html);
	if ($dl)
		$html = ($dl === true) ? str_replace('MP3URL', $url, $html) : str_replace('MP3URL', $dl, $html);
	$html = ($text) ? str_replace('MP3BASE', $text, $html) : str_replace('MP3BASE', basename($url), $html);
	return $html;
}
function wpaPlayerJs($id, $url, $text = false, $dl = true){
	# Return the js for a given id and url
 	$js = <<<WPA
<!-- wpa0 js begin -->
<script type="text/javascript">
var wpa0_player; var wpa0_duration; var wpa0_text = false;
$(document).ready(function(){
	// Initializing wpa0
	var wpa0_flashvars = {'file': 'MP3URL', 'id': 'wpa0', 'screencolor': 'FFFFFF', 'frontcolor': 'FFFFFF', 'lightcolor': 'FFFFFF', 'controlbar': 'none', 'icons': 'false'};
	var wpa0_params = {'allowscriptaccess': 'always'};
	var wpa0_attribs = {'id': 'wpa0', 'name': 'wpa0'};
WPA;
	$js .= "	swfobject.embedSWF('" . WP_PLUGIN_URL . "/wpaudio-mp3-player/player.swf', 'wpa0_placeholder', '1', '1', '9.0.124', false, wpa0_flashvars, wpa0_params, wpa0_attribs);";
	$js .= <<<WPA
});
</script>
<!-- wpa0 js end -->
WPA;
	$js = str_replace('wpa0', "wpa$id", $js);
	$js = str_replace('MP3URL', $url, $js);
	if ($text) $js = str_replace('_text = false', "_text = '" . str_replace("'", "\'", $text) . "'", $js);
	return $js;
}

## WP options
add_option('wpa_tag_audio', false);
# WP admin menu
add_action('admin_menu', 'wpa_menu');
function wpa_menu() {
	add_options_page('WPaudio Options', 'WPaudio', 10, __FILE__, 'wpa_menu_page');
}
function wpa_menu_page() {
	if (isset($_POST['wpa_tag_audio'])) {
		if ($_POST['wpa_tag_audio'])
			update_option('wpa_tag_audio', true);
		else
			update_option('wpa_tag_audio', false);
	}
	echo <<<WPA
<!-- wpa menu begin -->
<div class="wrap">
<h2>WPaudio Options</h2>
<form method="POST" action="">
WPA;
	wp_nonce_field('update-options');	
	echo <<<WPA
	<p>WPaudio tags take the format <span style="font-family: Courier, Serif">[wpaudio url="http://domain.com/song.mp3"]</span>, but you can also convert older Audio Player tags.</p>
	<p>
		<b>Convert <span style="font-family: Courier, Serif">[audio:http://domain.com/song.mp3]</span> tags?</b><br>
WPA;
	# WPA audio tag
	echo '<input name="wpa_tag_audio" id="wpa_tag_audio_yes" value="1" type="radio"';
	if (get_option('wpa_tag_audio'))
		echo ' checked="yes"';
	echo '> <label for="wpa_tag_audio_yes">Yes, convert audio tags too.</label><br>';
	echo '<input name="wpa_tag_audio" id="wpa_tag_audio_no" value="0" type="radio"';
	if (!get_option('wpa_tag_audio'))
		echo ' checked="yes"';
	echo '> <label for="wpa_tag_audio_no">No, convert only WPaudio tags. (default)</label>';
	echo <<<WPA
	</p>
	<p><input type="submit" value="Save"></p>
</form>
</div>
<!-- wpa menu end -->
WPA;
}

?>
