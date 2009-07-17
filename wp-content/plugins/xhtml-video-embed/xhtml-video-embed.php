<?php
/*
Plugin Name: XHTML Video Embed
Plugin URI: http://saltwaterc.net/xhtml-video-embed
Description: XVE is a WordPress plug-in which embeds the videos in the right way, the XHTML way. The plug-in is self documented, so please check <a href="options-general.php?page=xhtml-video-embed.php">Options/Settings &raquo; XVE</a> for configuration details and usage information.
Version: 0.3.6
Author: SaltwaterC
Author URI: http://saltwaterc.net/
License: GPL v2.0
*/


// *************************************** XHTML Video Embed ***************************************
/* -------------------------------------------------------------------------------------------------
This is the section which provides all the calls to the existing, predefined functions by adding the
hooks to the WordPress engine, or defining the globals which are going to be used.
------------------------------------------------------------------------------------------------- */

// These hooks actually add the functionality to WordPress. One of them adds the admin pane, while
// the other one adds the tag-to-object conversion to the public side of this plug-in.
if((function_exists('add_filter'))&&(function_exists('add_action')))
	{
	add_filter('the_content', 'swc_xhtml_video_embed');
	add_action('admin_menu', 'swc_xhtml_video_admin');
	}
else
	{
	echo "Dissalowed request due to security reasons.";
	exit(0);
	}


// -------------------------------------------------------------------------------------------------


// *************************************** XVE API Functions ***************************************
/* -------------------------------------------------------------------------------------------------
swc_backward_compatibility(); - provides backward compatibility with the previous versions
swc_iif(); - a PHP implementation of Immediate if
swc_repl_amp(); - function which replaces the & with &amp; and fixes the broken entities
swc_xhtml_video_embed(); - the main user function
swc_content_filter(); - replaces the markup with proper flash objects
swc_flash_object(); - generates flash objects based on various variables
swc_convert_url(); - converts an input URL to a Embed URL. It also filters the input data
swc_xhtml_video_admin(); - the function which adds the hook for the admin panel
swc_xhtml_video_admin_panel(); - self explanatory, the admin panel by itself
swc_update_button(); - creates the localized update button within the admin
swc_collapsible_section(); - creates the hide/show options
swc_ucfirst_all(); - makes the first letter of every word as uppercased
swc_video_service_item(); - creates a video service item within the admin panel
swc_the_video_excerpt(); - a function which is callable from the template
swc_content_filter_first(); - swc_the_video_excerpt() helper function
------------------------------------------------------------------------------------------------- */


// *************************************** Backward Compatibility ***************************************
/* ------------------------------------------------------------------------------------------------------
This function maintais the backward compatibility with the older versions of XVE. Roughly it's a
translator which converts the options to a single string which is stored within the database.
------------------------------------------------------------------------------------------------------ */
function swc_backward_compatibility()
	{
	$_xveVersion="0.3.6";
	$_xveArrays=array("version", "nicely", "ugly", "sizes", "misc");
	$_xveObsoleteSizes=array("swc_flash_width", "swc_vflash_height", "swc_aflash_height");
	
	// Defining the string which is going to contain the options array coded as string
	add_option("XHTML_Video_Embed", false);
	$_xveOptions=get_option("XHTML_Video_Embed");
	
	if(!$_xveOptions)
		{
		// The global string is a boolean which is false ... doing the v0.2-to-v0.3 conversion
		// This switch does the actual conversion by defining the new data structures
		// This branch also defines the new data structures for new v0.3 users
		foreach($_xveArrays as $_xveArray)
			switch($_xveArray)
				{
				case "version":
				// The version parameter was introduced in v0.3.
				// The database value is going to be compared with the hardcoded value.
				// If there's a mismatch, then we need this function
				$_xveOptions="version|".$_xveVersion."!";
				break;
				
				case "nicely":
				// Initializing the "Nicely Supported" array;
				// Making sure that we have an empty string as temp storage 
				$_xveOptions.="nicely";
				$_tmpVar="";
				// SWF conversion
				if(get_option("swc_swf_support")=="yes")
					{
					$_tmpVar.="|swf";
					delete_option("swc_swf_support");
					}
				// FLV conversion - added after the development of v0.3 has been started
				if(get_option("swc_flv_supoport")=="yes")
					{
					$_tmpVar.="|flv";
					delete_option("swc_flv_support");
					}
				// Trilulilu Video conversion
				if(get_option("swc_trilu_video_support")=="yes")
					{
					$_tmpVar.="|trilu-video";
					delete_option("swc_trilu_video_support");
					}
				// Trilulilu Audio conversion
				if(get_option("swc_trilu_audio_support")=="yes")
					{
					$_tmpVar.="|trilu-audio";
					delete_option("swc_trilu_audio_support");
					}
				// Trilulilu Image conversion
				if(get_option("swc_trilu_image_support")=="yes")
					{
					$_tmpVar.="|trilu-imagine|trilu-image";
					delete_option("swc_trilu_image_support");
					}
				// Metacafe conversion
				if(get_option("swc_metacafe_support")=="yes")
					{
					$_tmpVar.="|metacafe";
					delete_option("swc_metacafe_support");
					}
				// Google Video conversion
				if(get_option("swc_google_support")=="yes")
					{
					$_tmpVar.="|google-video";
					delete_option("swc_google_support");
					}
				// YouTube conversion
				if(get_option("swc_youtube_support")=="yes")
					{
					$_tmpVar.="|youtube";
					delete_option("swc_youtube_support");
					}
				// Dailymotion conversion
				if(get_option("swc_daily_support")=="yes")
					{
					$_tmpVar.="|dailymotion";
					delete_option("swc_daily_support");
					}
				// MySpace TV conversion
				if(get_option("swc_myspace_support")=="yes")
					{
					$_tmpVar.="|myspace";
					delete_option("swc_myspace_support");
					}
				// Revver conversion
				if(get_option("swc_revver_support")=="yes")
					{
					$_tmpVar.="|revver";
					delete_option("swc_revver_support");
					}
				// Spike conversion
				if(get_option("swc_spike_support")=="yes")
					{
					$_tmpVar.="|spike";
					delete_option("swc_spike_support");
					}
				// Vimeo conversion
				if(get_option("swc_vimeo_support")=="yes")
					{
					$_tmpVar.="|vimeo";
					delete_option("swc_vimeo_support");
					}
				// Adding the collected information to the global variable
				if(!empty($_tmpVar)) // Conversion
					$_xveOptions.=$_tmpVar;
				else // New usage
					$_xveOptions.="|flv|trilu-video|trilu-audio|trilu-imagine|metacafe|google-video|youtube|dailymotion|myspace|revver|spike|vimeo|jumpcut|mogulus|trilu-image";
				$_xveOptions.="|jumpcut|mogulus|capped|gametrailers|veevo|collegehumor!";
				break;
				
				case "ugly":
				// Not implemented in pre v0.3, not implemented in v0.3
				// Defined in order to define the data structure compatibility
				$_xveOptions.="ugly!";
				break;
				
				case "sizes":
				$_xveOptions.="sizes";
				$_tmpVar="";
				$_tmpCheck=false;
				foreach($_xveObsoleteSizes as $_xveObsoleteSize)
					{
					$_tmpVal=0;
					$_tmpVal=get_option($_xveObsoleteSize);
					$_tmpVar.="|".$_tmpVal;
					if(empty($_tmpVal))
						$_tmpCheck=true;
					delete_option($_xveObsoleteSize);
					}
				if(empty($_tmpCheck))
					$_xveOptions.=$_tmpVar; // Conversion
				else
					$_xveOptions.="|448|386|46"; // New usage
				$_xveOptions.="!";
				unset($_xveObsoleteSizes, $_xveObsoleteSize, $_tmpCheck, $_tmpVal, $_tmpVar);
				break;
				
				case "misc":
				$_xveOptions.="misc".swc_iif(get_option("swc_display_credit")=="yes", "|credit", "");
				delete_option("swc_display_credit");
				break;
				
				default:
				die ("CODING ERROR!!!");
				}
			unset ($_xveArray);
		update_option("XHTML_Video_Embed", $_xveOptions);
		// Getting it back from the database as the options are returned by this function
		$_xveOptions=get_option("XHTML_Video_Embed");
		}
	else
		{
		// String to Array option conversion
		$_xveOptions=explode("!", $_xveOptions);
		
		// Defining the small arrays
		$_videoVers=$_xveOptions[0];
		$_videoVers=explode("|", $_videoVers);
		
		if($_videoVers[1]!=$_xveVersion) // There's an update on the way
			{
			$_videoVers[1]=$_xveVersion;
			// Defining the small arrays
			$_videoNice=$_xveOptions[1]; // We need to update this array with the new service
			// String to Array option conversion of the small pieces
			$_videoNice=explode("|", $_videoNice);
			// Adding the services support for each version upgrade
			if($_videoVers[1]=="0.3")
				$_videoNice[]="mogulus|capped|gametrailers|veevo|collegehumor|myvideo";
			if($_videoVers[1]=="0.3.4")
				$_videoNice[]="capped|gametrailers|veevo|collegehumor|myvideo";
			if($_videoVers[1]=="0.3.5")
				$_videoNice[]="veevo|collegehumor|myvideo";
			if($_videoVers[1]=="0.3.6")
				$_videoNice[]="myvideo";
			// Merging back the arrays and saving them to the database
			$_xveOptions[0]=implode("|", $_videoVers);
			$_xveOptions[1]=implode("|", $_videoNice);
			$_xveOptions=implode("!", $_xveOptions);
			update_option("XHTML_Video_Embed", $_xveOptions);
			
			// Resetting the admin panel as some older version may have a security flaw
			remove_action('admin_menu', 'swc_xhtml_video_admin');
			add_action('admin_menu', 'swc_xhtml_video_admin');
			
			// Doing a refresh because the first load of the plug-in after the update is broken
			$_refreshURL='http'.swc_iif(strtolower($_SERVER['HTTPS'])=='on', 's').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			echo <<<JS
<script type="text/javascript">window.location.href=window.location.href;</script><noscript><span style="display:block">It looks like you have turned off the JavaScript support. By requesting this page, you updated the <a href="http://saltwaterc.net/xhtml-video-embed" target="_blank">XHTML Video Embed</a> installation. In order to see the changes, you have to <a href="{$_refreshURL}">manually reload</a> this page as the JavaScript solution failed.</span></noscript>
JS;
			}
		}
	
	// This is the admin options data structure, separated from the user side
	add_option("XHTML_Video_Embed_Admin", false);
	$_xveOptionsAdmin=get_option("XHTML_Video_Embed_Admin");
	if(!$_xveOptionsAdmin)
		{
		$_xveOptionsAdmin="";
		$_xveOptionsAdmin.="collapse";
		
		// Video Nice Collapse
		if(get_option("swc_collapse_video_nice")=="yes")
			{
			$_xveOptionsAdmin.="|vnice";
			delete_option("swc_collapse_video_nice");
			}
		
		// Video Ugly Collapse
		// Not defined - the string is based on flags which mark their presence
		// by being part of the string by itself
		
		// Video Flash Sizes Collapse
		if(get_option("swc_collapse_flash_sizes")=="yes")
			{
			$_xveOptionsAdmin.="|fsizes";
			delete_option("swc_collapse_flash_sizes");
			}
		
		// Misc Options Collapse
		if(get_option("swc_collapse_misc_options")=="yes")
			{
			$_xveOptionsAdmin.="|mopt";
			delete_option("swc_collapse_misc_options");
			}
		
		$_xveOptionsAdmin.="!";
		update_option("XHTML_Video_Embed_Admin", $_xveOptionsAdmin);
		}
	/*
	else
		{
		// We have a variable which is a string, but we may call it 
		// some other time in the future versions for adding new stuff
		// to the already existing string. At the moment this branch is
		// useless.
		}
	*/
	
	// Since the backward compatibility is always called once within the public or within
	// the admin side of the blog, the options are returned by this in order to reduce the
	// database queries.
	return array($_xveOptions, $_xveOptionsAdmin);
	}
// ------------------------------------------------------------------------------------------------------


// *************************************** PHP IIF() ***************************************
/* -----------------------------------------------------------------------------------------
This function adds a small thing which misses from PHP: the iif() evaluation
aka "Immediate if". This implementation avoids the short-circuit evaluation
of the iif() which is based upon the ternary operation conversion to iif() 
----------------------------------------------------------------------------------------- */
function swc_iif($expression, $returntrue, $returnfalse='')
	{
	if ($expression)
    	return $returntrue;
	else
    	return $returnfalse;
	}
// -----------------------------------------------------------------------------------------


// *************************************** Replace Ampersands*******************************
/* -----------------------------------------------------------------------------------------
This function does a good job: it makes sure that all the input amperdands, the & char, are
properly escaped for the XHTML output. The proper escaping means that all the ampersands
must be written as their HTML entity, in this case, &amp;
----------------------------------------------------------------------------------------- */
function swc_repl_amp($string)
	{
	return preg_replace("@&amp;(\#\d+);|&amp;(\w+);@i", "&$1$2;", preg_replace("@&(?!amp;)@i", "&amp;", $string));
	}
// -----------------------------------------------------------------------------------------

// *************************************** XVE ***************************************
/* -----------------------------------------------------------------------------------
This is the main public side function which does the magic. The rest of the functions
are called from this baby. Since the new data structures of v0.3, this function has
been dramatically reduced.
----------------------------------------------------------------------------------- */
function swc_xhtml_video_embed($_theContent='')
	{
	// Doing some memory "caching" since this function is called for every post
	static $_xveOptions, $_xveNicely, $_xveSizes, $_xveMisc, $_isCompatible=false;
	
	// Calling the Backward Compatibility function which makes sure that the old options system gets
	// dropped while adding the new system. If the old system is unavailable, then it defines them at
	// the first pass, while adding the stuff to the $_xveOptions string. The second pass just reads
	// and places the data into the $_xveOptions string, without the need of converting the array.
	// Since v0.3.5 this is called only once in order to avoid getting the same data from the database.
	// Since v0.3.6 the $_xveOptions[0] is an array passed by swc_backward_compatibility()
	if(!$_isCompatible)
		{
		$_xveOptions=swc_backward_compatibility();
		// We don't need the admin though
		$_xveOptions=$_xveOptions[0];
		$_isCompatible=true;
		}
	// Since this function is called with every post, let's avoid querying the database for the
	// same stuff over and over again
	if((empty($_xveNicely))||(empty($_xveSizes))||(empty($_xveMisc)))
		{
		// Getting the options as the main string is already split into smaller strings
		$_xveNicely=explode("|", $_xveOptions[1]);
		// $_xveUgly=explode("|", $_xveOprions[2]); // Not implemented, yet
		$_xveSizes=explode("|", $_xveOptions[3]);
		$_xveMisc=explode("|", $_xveOptions[4]);
		}
	unset($_xveNicely[0]); // Droping the string tag
	foreach($_xveNicely as $_xveNice)
		{
		switch($_xveNice)
			{
			case "flv":
			$_fType="flv";
			break;
			
			case "trilu-audio":
			$_fType="audio";
			break;
			
			default:
			$_fType="video";
			break;
			}
		$_theContent=swc_content_filter($_theContent, $_fType, $_xveNice, $_xveSizes, $_xveMisc);
		}
	return $_theContent;
	}
// -----------------------------------------------------------------------------------


// *************************************** Content Filter ***************************************
/* ----------------------------------------------------------------------------------------------
This function does the markup matching and replacing. It is helped by a couple of functions which
make video ID sanitizing, Video URL to Embed URL conversion, and the generation of the flash
object itself.
---------------------------------------------------------------------------------------------- */
function swc_content_filter($_theContent='', $_fileType, $_supportedService, $_xveSizes, $_xveMisc)
	{
	if($n=preg_match_all("@\[".$_supportedService."((\040*w=(\d+)\040*h=(\d+))|(\040*h=(\d+)\040*w=(\d+)))?\](.*)\[/".$_supportedService."\]@i", $_theContent, $_theMatches))
		{
		if($_supportedService=="mogulus")
			$_xveSizes[2]=round($_xveSizes[2]*1.1); // The Mogulus object is weird, thus 4:3 is NOT the answer
		$i=0;
		while($i<$n)
			{
			if((!empty($_theMatches[3][$i]))&&(!empty($_theMatches[4][$i])))
				{
				if($_fileType!="audio")
					{
					$_xveSizes[1]=$_theMatches[3][$i];
					$_xveSizes[2]=$_theMatches[4][$i];
					}
				else
					{
					$_xveSizes[1]=$_theMatches[3][$i];
					$_xveSizes[3]=$_theMatches[4][$i];
					}
				}
			elseif((!empty($_theMatches[6][$i]))&&(!empty($_theMatches[7][$i])))
				{
				if($_fileType!="audio")
					{
					$_xveSizes[1]=$_theMatches[6][$i];
					$_xveSizes[2]=$_theMatches[7][$i];
					}
				else
					{
					$_xveSizes[1]=$_theMatches[6][$i];
					$_xveSizes[3]=$_theMatches[7][$i];
					}
				}
			$_theContent=str_replace($_theMatches[0][$i], str_replace("&*DATA*&", swc_convert_url($_theMatches[8][$i], $_supportedService), swc_flash_object($_fileType, $_xveSizes, $_xveMisc)), $_theContent);
			$i++;
			}
		}
	return $_theContent;
	}
// ----------------------------------------------------------------------------------------------


// *************************************** Flash Object ***************************************
function swc_flash_object($_fileType, $_xveSizes, $_xveMisc)
	{
	$_flashObject=<<<FLASH
<object type="application/x-shockwave-flash" style="width:{$_xveSizes[1]}px;height:
FLASH;
	$_flashObject.=swc_iif((($_fileType=="video")||($_fileType=="svideo")||($_fileType=="flv")), $_xveSizes[2], $_xveSizes[3]);
	$_flashObject.=<<<FLASH
px" data="&*DATA*&"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="quality" value="best" />
FLASH;
	$_flashObject.=swc_iif($_fileType!="flv", '<param name="wmode" value="transparent" />'); // This paramerer breaks the embedded flv player
	$_flashObject.=<<<FLASH
<param name="movie" value="&*DATA*&" /><param name="pluginspage" value="http://www.macromedia.com/go/getflashplayer" />If you can see this, then you might need a Flash Player upgrade or you need to install Flash Player if it's missing. Get <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash Player</a> from Adobe.</object><br/>
FLASH;
	static $_theCredit;
	if(empty($_theCredit)) // Another sticky var if the function is called several times at the same execution (highly probable)
		{
		$_theCredit=swc_iif((!in_array("credit", $_xveMisc)), "
		<!-- Valid XHTML flash object delivered by XHTML Video Embed. Get it at: http://saltwaterc.net/xhtml-video-embed -->
		","<span style='display:block;text-align:left;font-size:xx-small'><a href='http://saltwaterc.net/xhtml-video-embed' target='_blank' title='XHTML Video Embed - Delivers valid XHTML flash content into your WordPress blog'>&raquo; Powered by XHTML Video Embed</a></span>");
		}
	$_flashObject.=$_theCredit;
	return $_flashObject;
	}
// --------------------------------------------------------------------------------------------


// *************************************** Convert URL ***************************************
/* -------------------------------------------------------------------------------------------
This function does a quite important job - filtering the URLs for bad data, while returning an
embed link for a given permalink.
------------------------------------------------------------------------------------------- */
function swc_convert_url($_iURL, $_supportedService)
	{
	switch ($_supportedService)
		{
		case "swf":
		return swc_repl_amp($_iURL);
		break;
		
		case "flv":
		static $_mpPath; // Avoids the multiple definition of this file when the script is called several times for a flv case
		if(empty($_mpPath))
			{
			if(preg_match("@windows@i", php_uname('s'))) // Code for Windows
				{
				preg_match("@(wp\-content\\\\.*?)$@i", dirname(__file__), $_filePath);
				$_filePath=preg_replace("@\\\\@", "/", $_filePath[1]);
				}
			else // Code for *NIX
				{
				preg_match("@(wp\-content\/.*?)$@i", dirname(__file__), $_filePath);
				$_filePath=$_filePath[1];
				}
			$_mpPath=get_option("home");
			$_mpPath=swc_iif(substr($_mpPath, strlen($_mpPath)-1)=="/", $_mpPath, $_mpPath."/").swc_iif(substr($_filePath, strlen($_filePath)-1)=="/", $_filePath, $_filePath."/")."mediaplayer.swf";
			}
		static $_playerColor="4f4f4f"; // RGB HEX, short codes won't work!
		return swc_repl_amp($_mpPath."?flv=".$_iURL."&amp;autoplay=0&amp;autoload=0&amp;volume=100&amp;bgcolor1=".$_playerColor."&amp;bgcolor2=".$_playerColor."&amp;showstop=1&amp;showvolume=1&amp;showtime=2&amp;showloading=always&amp;showfullscreen=1&amp;&amp;ondoubleclick=fullscreen&amp;shortcut=1&amp;loadonstop=0&amp;margin=4&amp;showiconplay=1&amp;iconplaybgalpha=50");
		break;
		
		case "youtube":
		preg_match("@v=([a-zA-Z0-9\_\-]+)\&?@i", $_iURL, $_theMatch);
		// Support for 'Hi-Def' content
		preg_match("@fmt=(\d+)&?@", $_iURL, $_theHD);
		if(($_theHD[1]==6)||($_theHD[1]==18)||($_theHD[22]))
			return "http://www.youtube.com/v/".$_theMatch[1]."&amp;hl=en&amp;fs=1&amp;ap=%2526fmt%3D".$_theHD[1];
		else
			return "http://www.youtube.com/v/".$_theMatch[1]."&amp;hl=en&amp;fs=1";
		break;
		
		case "google-video":
		preg_match("@docid=([0-9\-]+)\&?@i", $_iURL, $_theMatch);
		return "http://video.google.com/googleplayer.swf?docId=".$_theMatch[1]."&amp;hl=en&amp;fs=true";
		break;
		
		case "metacafe":
		$_smallPieces=explode("/", $_iURL);
		return "http://www.metacafe.com/fplayer/".preg_replace("@[^0-9]@", "", $_smallPieces[4])."/".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[5]).".swf";
		break;
		
		case "trilu-video":
		$_smallPieces=explode("/", $_iURL);
		return "http://embed.trilulilu.ro/player/evideoplayer.swf?hash=".preg_replace("@[^a-zA-Z0-9]@", "", $_smallPieces[4])."&amp;userid=".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[3]);
		break;
		
		case "trilu-audio":
		$_smallPieces=explode("/", $_iURL);
		return "http://embed.trilulilu.ro/player/eaudioplayer.swf?hash=".preg_replace("@[^a-zA-Z0-9]@", "", $_smallPieces[4])."&amp;userid=".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[3]);
		break;
		
		case "trilu-imagine":
		$_smallPieces=explode("/", $_iURL);
		return "http://embed.trilulilu.ro/player/eimageplayer.swf?hash=".preg_replace("@[^a-zA-Z0-9]@", "", $_smallPieces[4])."&amp;userid=".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[3]);
		break;
		
		case "trilu-image":
		$_smallPieces=explode("/", $_iURL);
		return "http://embed.trilulilu.ro/player/eimageplayer.swf?hash=".preg_replace("@[^a-zA-Z0-9]@", "", $_smallPieces[4])."&amp;userid=".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[3]);
		break;
		
		case "dailymotion":
		$_smallPieces=explode("/", $_iURL);
		$_theKey=preg_grep("@^video$@i", $_smallPieces);
		$_theKey=array_keys($_theKey);
		$_theKey=$_theKey[0];
		$_smallPieces=explode("_", $_smallPieces[$_theKey+1]);
		return "http://www.dailymotion.com/swf/".preg_replace("@[^a-zA-Z0-9]@", "", $_smallPieces[0])."&amp;v3=1&amp;related=0";
		break;
		
		case "myspace":
		preg_match("@VideoID=(\d+)@i", $_iURL, $_theMatch);
		return "http://lads.myspace.com/videos/vplayer.swf?m=".$_theMatch[1]."&amp;v=2&amp;type=video";
		break;
		
		case "revver":
		$_smallPieces=explode("/", $_iURL);
		$_theKey=preg_grep("@^video$@i", $_smallPieces);
		$_theKey=array_keys($_theKey);
		$_theKey=$_theKey[0];
		return "http://flash.revver.com/player/1.0/player.swf?mediaId=".preg_replace("@[^0-9]@", "", $_smallPieces[$_theKey+1]);
		break;
		
		case "spike":
		$_smallPieces=explode("/", $_iURL);
		$_theKey=preg_grep("@^video$@i", $_smallPieces);
		$_theKey=array_keys($_theKey);
		$_theKey=$_theKey[0];
		$_smallPieces=explode("?", $_smallPieces[$_theKey+1]);
		return "http://www.spike.com/efp?flvbaseclip=".preg_replace("@[^0-9]@", "", $_smallPieces[0]);
		break;
		
		case "vimeo":
		$_smallPieces=explode("/", $_iURL);
		return "http://www.vimeo.com/moogaloop.swf?clip_id=".preg_replace("@[^0-9]@", "", $_smallPieces[3])."&amp;server=www.vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0";
		break;
		
		case "jumpcut":
		preg_match("@id=([A-Z0-9]+)\&?@i", $_iURL, $_theMatch);
		return "http://www.jumpcut.com/media/flash/jump.swf?id=".$_theMatch[1]."&amp;asset_type=movie&amp;asset_id=".$_theMatch[1]."&amp;eb=1";
		break;
		
		case "mogulus":
		$_smallPieces=explode("/", $_iURL);
		return "http://static.mogulus.com/grid/PlayerV2.swf?backgroundColor=0xffffff&amp;allowFullScreen=true&amp;allowScriptAccess=false&amp;channel=".preg_replace("@[^a-zA-Z0-9\_]@", "", $_smallPieces[3])."&amp;layout=playerEmbedDefault&amp;backgroundAlpha=1&amp;backgroundGradientStrength=0&amp;chromeColor=0x333333&amp;headerBarGlossEnabled=true&amp;controlBarGlossEnabled=true&amp;chatInputGlossEnabled=false&amp;uiWhite=true&amp;uiAlpha=0.5&amp;uiSelectedAlpha=1&amp;dropShadowEnabled=true&amp;dropShadowHorizontalDistance=10&amp;dropShadowVerticalDistance=10&amp;paddingLeft=10&amp;paddingRight=10&amp;paddingTop=10&amp;paddingBottom=10&amp;cornerRadius=10&amp;backToDirectoryURL=http://www.mogulus.com/guide/category&amp;bannerURL=null&amp;bannerText=null&amp;bannerWidth=320&amp;bannerHeight=50&amp;showViewers=true&amp;embedEnabled=true&amp;chatEnabled=true&amp;onDemandEnabled=true&amp;programGuideEnabled=false&amp;fullScreenEnabled=true&amp;reportAbuseEnabled=false&amp;gridEnabled=false&amp;initialIsOn=false&amp;initialIsMute=false&amp;initialVolume=10&amp;wmode=window";
		break;
		
		case "capped":
		preg_match("@vid=([a-z0-9\-\_]+)&?@i", $_iURL, $_theMatch);
		return "http://capped.micksam7.com/playeralt.swf?vid=".$_theMatch[1];
		break;
		
		case "gametrailers":
		if(preg_match("@\/player\/([0-9]+)\.html$@i", $_iURL, $_theMatch))
			return "http://www.gametrailers.com/remote_wrap.php?mid=".$_theMatch[1];
		elseif(preg_match("@\/player\/usermovies\/([0-9]+)\.html$@i", $_iURL, $_theMatch))
			return "http://www.gametrailers.com/remote_wrap.php?umid=".$_theMatch[1];
		break;
		
		case "veevo":
		preg_match("@id=([a-zA-Z0-9\_\-]+)@i", $_iURL, $_theMatch);
		return "http://www.veevo.ro/mojoplayer.swf?id=".$_theMatch[1];
		break;
		
		case "collegehumor":
		preg_match("@^http\:\/\/www\.collegehumor\.com\/video\:(\d+)@i", $_iURL, $_theMatch);
		return "http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id=".$_theMatch[1]."&amp;fullscreen=1";
		break;
		
		case "myvideo":
		preg_match("@watch\/([0-9]+)@i", $_iURL, $_theMatch);
		return "http://www.myvideo.de/movie/".$_theMatch[1];
		break;
		
		default:
		die ("CODING ERROR!!!");
		}
	}
// -------------------------------------------------------------------------------------------


// *************************************** XVE Admin ***************************************
/* -----------------------------------------------------------------------------------------
This function adds the XVE Options panel to the WP admin.
----------------------------------------------------------------------------------------- */
function swc_xhtml_video_admin()
	{
	if(function_exists('add_options_page'))
		add_options_page('xhtml-video-embed', 'XVE', 10, basename(__FILE__), 'swc_xhtml_video_admin_panel');
	}
// -----------------------------------------------------------------------------------------


// *************************************** XVE Admin Panel ***************************************
/* -----------------------------------------------------------------------------------------------
This is the annoyingly long function which creates and manages the admin options panel. Most of it
is the same from the v0.2.x, but it handles the new and compact data structures.
----------------------------------------------------------------------------------------------- */
function swc_xhtml_video_admin_panel()
	{
	// Doing backward compatibility while getting the options from the database
	list($_xveOptions, $_xveOptionsAdmin)=swc_backward_compatibility();
		
	// String to Array option conversion
	$_xveOptionsAdmin=explode("!", $_xveOptionsAdmin);
	
	// Defining the small arrays
	$_videoVers=$_xveOptions[0];
	$_videoNice=$_xveOptions[1];
	$_videoUgly=$_xveOptions[2]; // Not implemented, yet
	$_videoSize=$_xveOptions[3];
	$_videoMisc=$_xveOptions[4];
	
	$_adminColl=$_xveOptionsAdmin[0];
	$_adminLang=$_xveOptionsAdmin[1];
	
	// String to Array option conversion of the small pieces
	$_videoVers=explode("|", $_videoVers);
	$_videoNice=explode("|", $_videoNice);
	$_videoUgly=explode("|", $_videoUgly); // Not implemented, yet
	$_videoSize=explode("|", $_videoSize);
	$_videoMisc=explode("|", $_videoMisc);
	
	$_adminColl=explode("|", $_adminColl);
	$_adminLang=explode("|", $_adminLang);
	
	$_videoNiceServices=array(
		"swf"			=> "SWF",
		"flv"			=> "FLV",
		"youtube"		=> "YouTube",
		"google-video"	=> "Google Video",
		"metacafe"		=> "Metacafe",
		"trilu-video"	=> "Trilulilu Video",
		"trilu-audio"	=> "Trilulilu Audio",
		"trilu-image"	=> "Trilulilu Image",
		"dailymotion"	=> "Dailymotion",
		"myspace"		=> "MySpace",
		"revver"		=> "Revver",
		"spike"			=> "Spike",
		"vimeo"			=> "Vimeo",
		"jumpcut"		=> "Jumpcut",
		"mogulus"		=> "Mogulus",
		"capped"		=> "Capped TV",
		"gametrailers"	=> "GameTrailers",
		"veevo"			=> "Veevo",
		"collegehumor"	=> "CollegeHumor",
		"myvideo"		=> "MyVideo",
		);
		
	// The structured interface by itself
	$_centWidth="50%";
	
	// The localization support	location
	$_langPath=dirname(__FILE__);
	$_langPath=swc_iif(substr($_langPath, strlen($_langPath)-1)=="/", $_langPath, $_langPath."/");
	if(!$_dirHandle=@opendir($_langPath))
		echo "<div class='error' style='text-align:justify'>Can't open the plugin's directory, thus I can't get any language file. Please fix your XHTML Video Embed v".$_videoVers[1]." installation.</div>";
	else
		{
		// Reading the plugin's directory
		$_langList=array();
		while(false!==($_theFile=readdir($_dirHandle)))
			if(preg_match("@xve\-lang\-([a-z]+)\.php@", $_theFile, $_theMatch))
				$_langList[]=$_theMatch[1];
		unset($_theFile, $_theMatch);
		closedir($_dirHandle);
		
		if(count($_langList)==0)
			echo "<div class='error' style='text-align:justify'>There isn't any valid language file. Please place at least one language file into the same directory as xhtml-video-embed.php. A valid language file <span style='font-weight:bold'>MUST</span> have a name like xve-lang-english.php. The 'english' part <span style='font-weight:bold'>MUST</span> be all in lowercase and <span style='font-weight:bold'>MUST NOT</span> contain any numbers or special chars. A valid language file <span style='font-weight:bold'>MUST NOT</span> contain any syntax error. It also <span style='font-weight:bold'>NEEDS</span> a properly defined: swc_return_language(); - function.</div>";
		else
			{
			// Creating the language option
			if(empty($_adminLang[0])) // We don't have a default language!
				{
				$_adminLang[0]="language";
				if(in_array("english", $_langList))
					$_adminLang[1]="english";
				else
					$_adminLang[1]=$_langList[0];
				// Merge the arrays and save to the database
				update_option("XHTML_Video_Embed_Admin", implode("|", $_adminColl)."!".implode("|", $_adminLang));
				}
			
			define("load_protection", true);
			if(is_file($_langPath . "xve-lang-" . $_adminLang[1] . ".php")) // Patch for the malformed saved languages
				require($_langPath . "xve-lang-" . $_adminLang[1] . ".php");
			elseif(is_file($_langPath . "xve-lang-english.php")) // Loading the English if possible
				require($_langPath . "xve-lang-english.php");
			else // If the English isn't there and the saved language isn't there ... stop the function and display a message
				{
				echo "<div class='error' style='text-align:justify'>The language file: xve-lang-" . $_adminLang[1] . ".php"." can't be loaded. Even more, the English file is missing. Provide either the proper file for the specified language or the English file in order to be able to use this panel.</div>";
				return;
				}
			$_language=swc_return_language();
			
			$_collapseList=array(
				"nice"		=> $_language['nice'],
			//	"ugly"		=> $_language['ugly'], // Shall be uncommented after the cache code for ugly video services is implemented
				"size"		=> $_language['size'],
				"options"	=> $_language['options']
			);
			
			// Process the POST if there's any
			if ($_SERVER['REQUEST_METHOD']=="POST")
				{
				if(isset($_POST['xve_update']))
					{
					// Update the options the sane way ...
					$_videoNice="nicely";
					foreach($_videoNiceServices as $_videoNiceService => $_videoNiceServiceUpper)
						{
						if($_videoNiceService!="trilu-image")
							$_videoNice.=swc_iif(($_POST['use_'.$_videoNiceService]=="on"), "|".$_videoNiceService, "");
						else // The damn Trilulilu's plugin compatibility
							$_videoNice.=swc_iif(($_POST['use_trilu-image']=="on"), "|trilu-imagine|trilu-image", "");
						}
					$_videoNice.="!";
					
					$_videoUgly="ugly!";
					
					$_videoSize="sizes";
					
					$_tmpVal=preg_replace("@[^0-9]@", "", $_POST['uni_w']);
					if(empty($_tmpVal))
						{
						$_tmpVal="|448";
						$_videoSize.=$_tmpVal;
						}
					else
						$_videoSize.="|".$_tmpVal;
					
					$_tmpVal=preg_replace("@[^0-9]@", "", $_POST['uni_vh']);
					if(empty($_tmpVal))
						{
						$_tmpVal="|386";
						$_videoSize.=$_tmpVal;
						}
					else
						$_videoSize.="|".$_tmpVal;
					
					$_tmpVal=preg_replace("@[^0-9]@", "", $_POST['uni_ah']);
					if(empty($_tmpVal))
						{
						$_tmpVal="|46";
						$_videoSize.=$_tmpVal;
						}
					else
						$_videoSize.="|".$_tmpVal;
					
					$_videoSize.="!";
					
					$_videoMisc="misc".swc_iif(($_POST['use_credit']=="on"), "|credit", "");
					
					$_adminColl="collapse";
					
					foreach($_collapseList as $_collapseItem => $_localizedValue)
						$_adminColl.=swc_iif($_POST['collapse_'.$_collapseItem], "|".$_collapseItem, "");
					
					unset($_collapseItem, $_localizedValue);
					
					$_adminColl.="!";
					
					$_xveLangP=preg_replace("@[^\w]@", "", $_POST['xve-lang']);
					if(!in_array($_xveLangP, $_langList))
						$_xveLangP="english"; // Resetting the language if the shit hits the fan
					$_adminLang="language|".$_xveLangP;
					
					// Writing the changes to the database as delete+add instead of update - this makes sure that if some options get corrupted => the arrays are resetted
					// Since basically the MySQL UPDATE is actually a DELETE+INSERT this won't be such a "big issue" at database level, with the only notable exception
					// that this solution requires a couple of more queries. I know ... I'm a paranoid ...
					delete_option("XHTML_Video_Embed");
					delete_option("XHTML_Video_Embed_Admin");
					add_option("XHTML_Video_Embed", implode("|", $_videoVers)."!".$_videoNice.$_videoUgly.$_videoSize.$_videoMisc);
					add_option("XHTML_Video_Embed_Admin", $_adminColl.$_adminLang);
					}
				// Refresh the page after a POST request as the language might change. Since the language is loaded before the POST, but some data structures depend on the
				// localization support, this refresh is a must!
				echo <<<JS
<script type="text/javascript">window.location.href=window.location.href;</script><noscript><div class="error">It looks like you have turned off the JavaScript support. Please <a href="options-general.php?page=xhtml-video-embed.php">reload this page</a> in order to see the changes!</div></noscript>
JS;
				return;
				}
			
			// THE JavaScript :)
			echo <<<JS
<script type="text/javascript">
//<!--
function showhide(targetID)
	{
	//change target element mode
	var elementmode = document.getElementById(targetID).style;
	elementmode.display = (!elementmode.display) ? 'none' : '';
	}
function changetext(changee,oldText,newText)
	{
	//changes text in source element
	var elementToChange = document.getElementById(changee);
	elementToChange.innerHTML = (elementToChange.innerHTML == oldText) ? newText : oldText;
	}
function workforchange(targetID,sourceID,oldContent,newContent)
	{
	showhide(targetID);
	changetext(sourceID,oldContent,newContent);
	}
// -->
</script>
JS;
			echo '<div class="wrap"><h2>XHTML Video Embed v'.$_videoVers[1].'</h2><form method="post" action="' . swc_iif(($_SERVER['HTTPS']=="on"), "https://", "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">';
			
			swc_update_button($_language['update_button']);
			
			$_langOpti="";
			foreach($_langList as $_certLang)
				$_langOpti.='<option value="'.$_certLang.'"'.swc_iif($_certLang==$_adminLang[1], ' selected="selected"', '').'>'.ucfirst($_certLang).'</option>';
			echo '<div style="text-align:right">'.$_language['language'].': <select name="xve-lang">'.$_langOpti.'</select></div>';
			unset($_langOpti, $_certLang);
			
			foreach($_collapseList as $_collapseItem => $_workerName) // This loop is the main loop - it creates the sections
				{
				$_checkedCollapse=in_array($_collapseItem, $_adminColl);
				swc_collapsible_section($_centWidth, $_checkedCollapse, $_workerName, $_language['show'], $_language['hide'], 'collapse_'.$_collapseItem, $_language['collapsed']);
				echo '<table width="100%" id="'.$_workerName.'"'.swc_iif(!$_checkedCollapse, '', 'style="display:none"').'>';
				if(!empty($_language[$_collapseItem.'_text']))
					echo '<tr><td colspan="3" style="font-size:small;text-align:justify">'.$_language[$_collapseItem.'_text'].'<br /><br /></td></tr>';
				switch($_collapseItem)
					{
					case "nice":
					foreach($_videoNiceServices as $_videoNiceService => $_videoNiceServiceUpper) // The nicely video services
						{
						$_isChecked=in_array($_videoNiceService, $_videoNice);
						swc_video_service_item($_videoNiceService, $_videoNiceServiceUpper, $_language['use_text'], $_language['generic'], $_isChecked, $_language['usage'], $_language['usage_'.$_videoNiceService], $_language['usage_'.$_videoNiceService.'_ex'], $_language['show'], $_language['hide']);
						}
					break;
					
					case "size":
					echo <<<XHTML
<tr><td align="right" valign="top" width="25%">{$_language['width']}:</td><td align="left" valign="top"><input type="text" name="uni_w" size="2" value="{$_videoSize[1]}" onclick="this.focus(); this.select();" /></td></tr><tr><td align="right" valign="top" width="25%">{$_language['video_height']}:</td><td align="left" valign="top"><input type="text" name="uni_vh" size="2" value="{$_videoSize[2]}" onclick="this.focus(); this.select();" /></td></tr><tr><td align="right" valign="top" width="25%">{$_language['audio_height']}:</td><td align="left" valign="top"><input type="text" name="uni_ah" size="2" value="{$_videoSize[3]}" onclick="this.focus(); this.select();" /></td></tr>
XHTML;
					break;
					
					case "options":
					if(in_array('credit', $_videoMisc))
						$_checkAdd=' checked="checked"';
					echo <<<XHTML
<tr><td align="right" valign="top" width="25%">{$_language['credit']}?</td><td align="center" valign="top" width="3%"><input type="checkbox" name="use_credit" class="tog" style="margin-left:+10px;margin-right:+10px"{$_checkAdd} /></td><td style="text-align:justify">{$_language['credit_text']}</td></tr>
XHTML;
					break;
					}
				echo '</table>';
				}
			swc_update_button($_language['update_button']);
			echo '</form><h2>Credits</h2><br /><ul><li><a href="http://saltwaterc.net/" title="Open my eyes, saltwater rain ..." target="_blank">SaltwaterC</a> + <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" title="GNU General Public License, version 2" target="_blank">GPL v2.0</a> - XHTML Video Embed</li><li><a href="http://jopogo.com/resources/showhide.php" title="workforchange() - a simple show/hide javascript" target="_blank">workforchange()</a> - Show/Hide script</li><li><a href="http://flv-player.net/" title="FLV Player" target="_blank">FLV Player</a> + <a href="http://creativecommons.org/licenses/by-sa/3.0/" title="Creative Commons - Attribution-Share Alike 3.0 Unported" target="_blank">CC Attribution-Share Alike 3.0 Unported</a> + <a href="http://www.mozilla.org/MPL/" title="Mozilla Public License 1.1" target="_blank">MPL 1.1</a> - embedded FLV player</li><li><a href="http://nv1962.net/" title="el guateque progreguay gratis total" target="_blank">&#193;lvaro</a> + <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" title="GNU General Public License, version 2" target="_blank">GPL v2.0</a> - Spanish, Dutch</li><li><a href="http://www.dreamproduction.ro/blog/" title="Dream Production" target="_blank">Dream Production</a> + <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html" title="GNU General Public License, version 2" target="_blank">GPL v2.0</a> - Veevo support</li><li><a href="http://www.itelevision.nl/" title="itelevision.nl: Online Video & TV Blog : Gratis films kijken, Digitale TV, Online Video, Series, Live TV, Torrent, Software ..." target="_blank">Joris</a> - YouTube fullscreen hint, CollegeHumor initial implementation</li></ul></div>';
			}
		}
	}
// -----------------------------------------------------------------------------------------------


// *************************************** XVE Update Button ***************************************
function swc_update_button($_buttonLanguage)
	{
	echo <<<XHTML
<div style="width:100"><p class="submit" style="text-align:left"><input type="submit" name="xve_update" value="{$_buttonLanguage} &raquo;" /></p></div>
XHTML;
	}
// -------------------------------------------------------------------------------------------------


// *************************************** XVE Collapsible Section *********************************
function swc_collapsible_section($_centWidth, $_checkedCollapse, $_workerName, $_showName, $_hideName, $_checkBoxName, $_collapseText)
	{
	$_showOptions='[+] '.$_showName." ".swc_ucfirst_all(preg_replace("@\-@", " ", $_workerName));
	$_hideOptions='[-] '.$_hideName." ".swc_ucfirst_all(preg_replace("@\-@", " ", $_workerName));
	$_showOptionsURL=rawurlencode($_showOptions);
	$_hideOptionsURL=rawurlencode($_hideOptions);
	echo '<table width="100%"><tr><td align="left" width="'.$_centWidth.'"><h3>'.swc_iif(!$_checkedCollapse, '<a href="javascript:workforchange(\''.$_workerName.'\',\'changer-'.$_workerName.'\',\''.$_hideOptionsURL.'\',\''.$_showOptionsURL.'\');" id="changer-'.$_workerName.'">'.$_hideOptions.'</a></h3></td><td align="left"><input type="checkbox" name="'.$_checkBoxName.'" class="tog"', '<a href="javascript:workforchange(\''.$_workerName.'\',\'changer-'.$_workerName.'\',\''.$_showOptionsURL.'\',\''.$_hideOptionsURL.'\');" id="changer-'.$_workerName.'">'.$_showOptions.'</a></h3></td><td align="left"><input type="checkbox" name="'.$_checkBoxName.'" class="tog" checked="checked"').' />&nbsp;'.$_collapseText.'</td></tr></table>';
	}
// -------------------------------------------------------------------------------------------------


// *************************************** UCFirst() For Strings ***********************************
function swc_ucfirst_all($_string)
	{
	$_words=explode(" ", $_string);
	$_string="";
	foreach($_words as $_word)
		$_string.=ucfirst($_word)." ";
	return trim($_string);
	}
// -------------------------------------------------------------------------------------------------


// *************************************** Video Service Item **************************************
function swc_video_service_item($_videoService, $_videoServiceUpper, $_useText, $_genericText, $_isChecked, $_usageText, $_usageMode, $_usageModeExtra='', $_showText, $_hideText)
	{
	switch($_videoService)
		{
		case "swf":
		$_useText=str_replace('-generic-', $_genericText, str_replace('-service-', $_videoServiceUpper, $_useText));
		break;
			
		case "flv":
		$_useText=str_replace('-generic-', $_genericText, str_replace('-service-', $_videoServiceUpper, $_useText));
		break;
		
		default:
		$_useText=str_replace('-generic- ', '', str_replace('-service-', $_videoServiceUpper, $_useText));
		break;
		}
	echo '<tr><td align="right" valign="top" width="25%">'.$_useText.'?</td><td align="center" valign="top" width="3%"><input type="checkbox" name="use_'.$_videoService.'" class="tog" style="margin-left:+10px;margin-right:+10px"';
	if($_isChecked)
		echo ' checked="checked"';
	echo ' /></td><td>'.$_usageText.': <strong>'.swc_repl_amp($_usageMode).'&nbsp;</strong>';
	if(!empty($_usageModeExtra))
		echo '<br /><a href="javascript:workforchange(\''.$_videoService.'\',\'changer-'.$_videoService.'\',\'[+]'.$_showText.'\',\'[-]'.$_hideText.'\');" id=\'changer-'.$_videoService.'\'>[+]'.$_showText.'</a><div id="'.$_videoService.'" style="display:none;text-align:justify">'.swc_repl_amp($_usageModeExtra).'</div>';
	echo '</td></tr>';
	}
// -------------------------------------------------------------------------------------------------

// *************************************** The Video Excerpt ***************************************
/* -------------------------------------------------------------------------------------------------
This function is callable from the blog template. It was named after the_excerpt() template tag.
Actually its purpose is to sit where the_excerpt() sits, or it can replace the_excerpt() if you
would like to display a "video of the day" section which may be based onto a category. The function
must be either called within the loop, or you may specify a category ID. Do not mix the calls as the
function generates its own loop if the category is specified. There are some WordPress issues with
the nested loops, thus the template tags might have an unexpected behavior if you fail to comply.
If you call it within the loop, then the content is taken from get_the_content() template tag.
Unlike the_content() template tag, get_the_content() returns the content instead of outputting it.

NOTICE: the WordPress support for the get_the_content(), setup_postdata(), get_posts() template
tags might require a WordPress version higher than the specified minimal version (2.0). 
------------------------------------------------------------------------------------------------- */
function swc_the_video_excerpt($w=0, $vh=0, $ah=0, $category=0)
	{
	$_theContent='';
	if(empty($category)) // Try to get some content from the current loop
		$_theContent=get_the_content();
	else // Create own loop while getting the last post of the specified category ID
		{
		if(empty($_theContent)) // Making sure that there's no content defined by get_the_content()
			{
			$_myPosts=get_posts("numberposts=1&category=".$category);
			foreach($_myPosts as $_thePost)
				{
				setup_postdata($_thePost);
				$_theContent=get_the_content();
				}
			}
		}
	// Basically this is a swc_xhtml_video_embed() rewrite
	static $_xveOptions, $_xveNicely, $_xveSizes, $_xveMisc, $_isCompatible=false;
	if(!$_isCompatible)
		{
		$_xveOptions=swc_backward_compatibility();
		$_xveOptions=$_xveOptions[0];
		$_isCompatible=true;
		}
	if((empty($_xveNicely))||(empty($_xveSizes))||(empty($_xveMisc)))
		{
		$_xveNicely=explode("|", $_xveOptions[1]);
		// $_xveUgly=explode("|", $_xveOprions[2]); // Not implemented, yet
		$_xveSizes=explode("|", $_xveOptions[3]);
		$_xveMisc=explode("|", $_xveOptions[4]);
		}
	if((!empty($w))&&((!empty($vh))||(!empty($ah))))
		$_xveSizes=array('sizes', $w, $vh, $ah);
	unset($_xveNicely[0]); // Droping the string tag
	foreach($_xveNicely as $_xveNice)
		{
		switch($_xveNice)
			{
			case "flv":
			$_fType="flv";
			break;
			
			case "trilu-audio":
			$_fType="audio";
			break;
			
			default:
			$_fType="video";
			break;
			}
		$_theOutput=swc_content_filter_first($_theContent, $_fType, $_xveNice, $_xveSizes, $_xveMisc);
		if($_theOutput[0])
			{
			echo $_theOutput[1];
			return true;
			}
		}
	return false;
	}
// ----------------------------------------------------------------------------------------------------

// *************************************** Content Filter First ***************************************
function swc_content_filter_first($_theContent='', $_fileType, $_supportedService, $_xveSizes, $_xveMisc)
	{
	if(preg_match("@\[".$_supportedService."((\040*w=(\d+)\040*h=(\d+))|(\040*h=(\d+)\040*w=(\d+)))?\](.*)\[/".$_supportedService."\]@i", $_theContent, $_theMatch))
		{
		if((!empty($_theMatch[3]))&&(!empty($_theMatch[4])))
			{
			if($_fileType!="audio")
				{
				$_xveSizes[1]=$_theMatch[3];
				$_xveSizes[2]=$_theMatch[4];
				}
			else
				{
				$_xveSizes[1]=$_theMatch[3];
				$_xveSizes[3]=$_theMatch[4];
				}
			}
		elseif((!empty($_theMatch[6]))&&(!empty($_theMatch[7])))
			{
			if($_fileType!="audio")
				{
				$_xveSizes[1]=$_theMatch[6];
				$_xveSizes[2]=$_theMatch[7];
				}
			else
				{
				$_xveSizes[1]=$_theMatch[6];
				$_xveSizes[3]=$_theMatch[7];
				}
			}
		return array(true, str_replace("&*DATA*&", swc_convert_url($_theMatch[8], $_supportedService), swc_flash_object($_fileType, $_xveSizes, $_xveMisc)));
		}
	else
		return array(false, '');
	}
