<?php
// This small code snipped makes sure that
// the include is called within the plugin
if(!defined("load_protection"))
	{
	header("Content-type: text/html; charset=utf8", true);
	header("HTTP/1.0 404 Not Found");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
 <head>
  <title>404 - Not Found</title>
 </head>
 <body>
  <h1>404 - Not Found</h1>
 </body>
</html>
';
	die();
	}

function swc_return_language()
	{
	return array(
		"language"				=>	"Language",
		"update_button"			=>	"Update Options",
		"show"					=>	"Show",
		"hide"					=>	"Hide",
		"nice"					=>	"nice-video-services-options",
		"nice_text"				=>	"The nicely supported video services are the video services which can be embedded by generating a flash object using only the information from the input URL. Since v0.3 you can override the unifined sizes for each of these services by using a syntax like [tag w=320 h=240]url-to-embed[/tag] which creates an object that has a width of 320 pixels, and a height of 240 pixels.",
		"ugly"					=>	"ugly-video-services-options",
		"size"					=>	"flash-sizes-options",
		"size_text"				=>	"This section allows you to customize the unified sizes for the flash objects.",
		"options"				=>	"misc-options",
		"options_text"			=>	"This section is dedicated to the miscellaneos options.",
		"collapsed"				=>	"Collapsed as default",
		"use_text"				=>	"Use -generic- -service- support",
		"generic"				=>	"generic",
		"usage"					=>	"Usage",
		"usage_swf"				=>	"[swf]path-to-swf-file[/swf]",
		"usage_swf_ex"			=>	"Notice: the path can be either relative, or absolute. The absolute path is recommended if you use the pretty permalink structure when loading files which are hosted by you. By using absolute paths, you can avoid certain malfunctions. Of course, an absolute path is required if you use a SWF object hosted onto another domain or subdomain.",
		"usage_flv"				=>	"[flv]path-to-flv-file[/flv]",
		"usage_flv_ex"			=>	"Notice: the path can be either relative, or absolute. The absolute path is recommended if you use the pretty permalink structure when loading files which are hosted by you. By using absolute paths, you can avoid certain malfunctions. Of course, an absolute path is required if you use a FLV file hosted onto another domain or subdomain.",
		"usage_youtube"			=>	"[youtube]http://www.youtube.com/watch?v=5LtYk7wsFnk[/youtube]",
		"usage_youtube_ex"		=>	"Notice: The extra parameters are ignored if you use a permalink like this<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&feature=dir</em>. Since the v0.3 has been introduced the support for the high definition content, thus you may use permalinks which have the fmt parameter with the supported values, 6 and 18:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=6</em>;<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=18</em>.",
		"usage_google-video"	=>	"[google-video]http://video.google.com/videoplay?docid=-1770384172897733802[/google-video]",
		"usage_google-video_ex"	=>	"Notice: The extra parameters are ignored if you use a permalink like<br />http://video.google.com/videoplay?docid=-1770384172897733802<em>&hl=en</em>",
		"usage_metacafe"		=>	"[metacafe]http://www.metacafe.com/watch/822298/too_much_time_to_waste/[/metacafe]",
		"usage_trilu-video"		=>	"[trilu-video]http://www.trilulilu.ro/SaltwaterC/54fcf02ed330bd[/trilu-video]",
		"usage_trilu-audio"		=>	"[trilu-audio]http://www.trilulilu.ro/ambro/26129477a2da0c[/trilu-audio]",
		"usage_trilu-image"		=>	"[trilu-image]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-image]",
		"usage_trilu-image_ex"	=>	"Alternative usage:<br /><strong>[trilu-imagine]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-imagine]</strong>. The [trilu-image] tag was introduced in v0.2.3 in order to match the native language (English) support of this plug-in. The [trilu-imagine] was introduced since the beginning of the development in order to preserve the full compatibility with Trilulilu's own WordPress plug-in. \"Imagine\" is the Romanian word for \"Image\" and it was used because Trilulilu is a Romanian video/audio/image service. Unlike the previous versions, since v0.3 the standard Trilulilu Image tag is [trilu-image]. It's recommended to use the tag [trilu-image] since the alternative is only implemented for compatibility reasons.",
		"usage_dailymotion"		=>	"[dailymotion]http://www.dailymotion.com/us/cluster/auto/featured/video/x4g5zc[/dailymotion]",
		"usage_dailymotion_ex"	=>	"Notice: the extra parameters wrapped around the video ID are ignored. As long as the /video/{video_id} - from the URL is intact, the plug-in works. Use URLs from your browser's address bar.",
		"usage_myspace"			=>	"[myspace]http://vids.myspace.com/index.cfm?fuseaction=vids.individual&VideoID=28776245[/myspace]",
		"usage_myspace_ex"		=>	"Notice: MySpace TV malfunctions if you have an old Flash Player version such as v7 - it won't show any image. You can only hear the sound.",
		"usage_revver"			=>	"[revver]http://revver.com/video/268495/hows-my-driving/[/revver]",
		"usage_spike"			=>	"[spike]http://www.spike.com/video/2881456[/spike]",
		"usage_spike_ex"		=>	"Notice: when using permalinks like<br /><em>http://www.spike.com/video/2881456?cmpnid=800&lkdes=VID_2881456</em>,<br />the parameters after the video ID are ignored.",
		"usage_vimeo"			=>	"[vimeo]http://www.vimeo.com/212286[/vimeo]",
		"usage_jumpcut"			=>	"[jumpcut]http://www.jumpcut.com/view?id=A08576661C0211DDABFF000423CF4092[/jumpcut]",
		"usage_mogulus"			=>	"[mogulus]http://www.mogulus.com/looneytunesnetwork[/mogulus]",
		"usage_capped"			=>	"[capped]http://capped.tv/playeralt.php?vid=fairlight-panic_room[/capped]",
		"usage_gametrailers"	=>	"[gametrailers]http://www.gametrailers.com/player/39088.html[/gametrailers]",
		"usage_veevo"			=>	"[veevo]http://www.veevo.ro/mojoplayer.swf?id=148be5b09a2fc2[/veevo]",
		"usage_veevo_ex"		=>	"Notice: for the moment, the URL needs to be copied from the embed code and not from your Web browser's address bar. The size of the video must be specified for a proper aspect ratio. Otherwise it uses the default unified size.",
		"usage_collegehumor"	=>	"[collegehumor]http://www.collegehumor.com/video:1795924[/collegehumor]",
		"usage_myvideo"			=>	"[myvideo]http://www.myvideo.de/watch/5610434/Ballern_VS_Landstrasse[/myvideo]",
		"width"					=>	"Width",
		"video_height"			=>	"Video Height",
		"audio_height"			=>	"Audio Height",
		"credit"				=>	"Use credit for XVE",
		"credit_text"			=>	"Notice: this places a small URL under the inserted flash object with <a href='http://saltwaterc.net/xhtml-video-embed' target='_blank'>&raquo; Powered by XHTML Video Embed</a>. You don't have to use it, so that's why it's disabled by default. If you would like to support the spreading of this plug-in (and me as developer), then please check this option. Otherwize, the plug-in displays it in silent mode (as XHTML comment). If you mind using this, then remove it from the source code.",
		);
	}
