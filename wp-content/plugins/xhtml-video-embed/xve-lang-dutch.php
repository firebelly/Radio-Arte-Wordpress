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
		"language"				=>	"Taal",
		"update_button"			=>	"Opties wijzigen",
		"show"					=>	"Tonen",
		"hide"					=>	"Verbergen",
		"nice"					=>	"nette-video-diensten-opties",
		"nice_text"				=>	"De netjes ondersteunde video-diensten zijn diegenen die ingebed kunnen worden door middel van een Flash-object, aangemaakt met alleen maar informatie van de invoer-URI. M.i.v. v0.3 kunt u de standaard-afmetingen voor elk van deze diensten afzonderlijk aanpassen d.m.v. een syntax zoals [tag w=320 h=240]url-to-embed[/tag] hetgeen een object aanmaakt met een breedte van 320 pixels, en een hoogte van 240 pixels.",
		"ugly"					=>	"lelijke-video-diensten-opties",
		"size"					=>	"Flash-afmetingen-opties",
		"size_text"				=>	"In deze sectie kunt u de standaard-afmetingen voor alle Flash-objecten aangeven.",
		"options"				=>	"diverse-opties",
		"options_text"			=>	"In deze sectie kunt u diverse instellingen aanpassen.",
		"collapsed"				=>	"Standaard ingeklapt",
		"use_text"				=>	"Gebruik -generic- -service- ondersteuning",
		"generic"				=>	"algemene",
		"usage"					=>	"Gebruik",
		"usage_swf"				=>	"[swf]pad-naar-SWF-bestand[/swf]",
		"usage_swf_ex"			=>	"Opmerking: het pad kan ofwel relatief, dan wel absoluut zijn. Het absolute pad is aanbevolen als u de nette permalink structuur gebruikt bij het inladen van bestanden die gehost worden op uw eigen server. Door gebruik te maken van absolute paden ontwijkt u zo bepaalde problemen. Natuurlijk is een absoluut pad vereist als u een SWF-object gebruikt dat op een ander domein of subdomein wordt gehost.",
		"usage_flv"				=>	"[flv]pad-naar-FLV-bestand[/flv]",
		"usage_flv_ex"			=>	"Opmerking: het pad kan ofwel relatief, dan wel absoluut zijn. Het absolute pad is aanbevolen als u de nette permalink structuur gebruikt bij het inladen van bestanden die gehost worden op uw eigen server. Door gebruik te maken van absolute paden ontwijkt u zo bepaalde problemen. Natuurlijk is een absoluut pad vereist als u een FLV-object gebruikt dat op een ander domein of subdomein wordt gehost. Houd er a.u.b. wel rekening mee dat een andere licensie van toepassing is als u deze optie gebruikt; JW FLV PLAYER valt n.l. <strong>niet</strong> onder GPL v2.0.",
		"usage_youtube"			=>	"[youtube]http://www.youtube.com/watch?v=5LtYk7wsFnk[/youtube]",
		"usage_youtube_ex"		=>	"Opmerking: de extra parameters worden genegeerd als u een permalink gebruikt zoals deze:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&feature=dir</em>. M.i.v. v0.3 is ondersteuning voor hoge resolutie-content ingevoerd; daardoor kunt u permalinks gebruiken die de fmt parameter hanteren met de ondersteunde waarden, n.l. 6 en 18:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=6</em>;<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=18</em>.",
		"usage_google-video"	=>	"[google-video]http://video.google.com/videoplay?docid=-1770384172897733802[/google-video]",
		"usage_google-video_ex"	=>	"Opmerking: de extra parameters worden genegeerd als u een permalink gebruikt zoals deze:<br />http://video.google.com/videoplay?docid=-1770384172897733802<em>&hl=en</em>",
		"usage_metacafe"		=>	"[metacafe]http://www.metacafe.com/watch/822298/too_much_time_to_waste/[/metacafe]",
		"usage_trilu-video"		=>	"[trilu-video]http://www.trilulilu.ro/SaltwaterC/54fcf02ed330bd[/trilu-video]",
		"usage_trilu-audio"		=>	"[trilu-audio]http://www.trilulilu.ro/ambro/26129477a2da0c[/trilu-audio]",
		"usage_trilu-image"		=>	"[trilu-image]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-image]",
		"usage_trilu-image_ex"	=>	"Alternatieve toepassing:<br /><strong>[trilu-imagine]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-imagine]</strong>. Het [trilu-image] etiket is ingevoerd m.i.v. v0.2.3 om zo de oorspronkelijke taal (Engels) van deze plugin beter te ondersteunen. Het [trilu-imagine] etiket is vanaf het begin van de ontwikkeling van deze plugin ingevoerd, om zo volledige compatibiliteit te garanderen met de plugin van Trilulilu zelf voor WordPress. \"Imagine\" is het Roemeense woord voor \"Afbeelding\" en werd gebruikt omdat Trilulilu een Roemeense video/audio/afbeelding dienst is. In tegenstelling tot eerdere versies, is de standaard Trilulilu afbeeldingen-etiket m.i.v. v0.3 [trilu-image]. Het is aanbevolen het etiket [trilu-image] te hanteren, aangezien het alternatief uitsluitend is toegepast uit compatibiliteits overwegingen.",
		"usage_dailymotion"		=>	"[dailymotion]http://www.dailymotion.com/us/cluster/auto/featured/video/x4g5zc[/dailymotion]",
		"usage_dailymotion_ex"	=>	"Opmerking: de extra parameters die het video-ID flankeren worden genegeerd. Zolang het gedeelte /video/{video_id} - van de URI intact blijft, zal de plugin werken. Gebruik de URI's van de adresbalk van uw browser.",
		"usage_myspace"			=>	"[myspace]http://vids.myspace.com/index.cfm?fuseaction=vids.individual&VideoID=28776245[/myspace]",
		"usage_myspace_ex"		=>	"Opmerking: MySpace TV werkt niet als u een oude Flash Player versie heeft, zoals v7 - het toont dan geen beeld. U zult alleen het geluid horen.",
		"usage_revver"			=>	"[revver]http://revver.com/video/268495/hows-my-driving/[/revver]",
		"usage_spike"			=>	"[spike]http://www.spike.com/video/2881456[/spike]",
		"usage_spike_ex"		=>	"Opmerking: als u een permalink gebruikt zoals deze:<br /><em>http://www.spike.com/video/2881456?cmpnid=800&lkdes=VID_2881456</em>,<br />zullen de parameters die na de video-ID komen genegeerd worden.",
		"usage_vimeo"			=>	"[vimeo]http://www.vimeo.com/212286[/vimeo]",
		"usage_jumpcut"			=>	"[jumpcut]http://www.jumpcut.com/view?id=A08576661C0211DDABFF000423CF4092[/jumpcut]",
		"usage_mogulus"			=>	"[mogulus]http://www.mogulus.com/looneytunesnetwork[/mogulus]",
		"usage_capped"			=>	"[capped]http://capped.tv/playeralt.php?vid=fairlight-panic_room[/capped]",
		"usage_gametrailers"	=>	"[gametrailers]http://www.gametrailers.com/player/39088.html[/gametrailers]",
		"usage_veevo"			=>	"[veevo]http://www.veevo.ro/mojoplayer.swf?id=148be5b09a2fc2[/veevo]",
		"usage_veevo_ex"		=>	"Opmerking: voorlopig moet de URI nog gekopieerd worden van de invoeging (embed) code; niet uit de adresbalk van uw browser. De video-afmetingen moeten gegeven worden voor juiste beeldproporties. Anders worden standaard waarden gebruikt.",
		"usage_collegehumor"	=>	"[collegehumor]http://www.collegehumor.com/video:1795924[/collegehumor]",
		"usage_myvideo"			=>	"[myvideo]http://www.myvideo.de/watch/5610434/Ballern_VS_Landstrasse[/myvideo]",
		"width"					=>	"Breedte",
		"video_height"			=>	"Video-hoogte",
		"audio_height"			=>	"Audio-hoogte",
		"credit"				=>	"XVE credits tonen",
		"credit_text"			=>	"Opmerking: dit plaatst een kleine URI onder het ingevoegde Flash-object, met de tekst <a href='http://saltwaterc.net/xhtml-video-embed' target='_blank'>&raquo; Powered by XHTML Video Embed</a>. U bent niet verplicht dit te tonen; daarom is het standaard uitgeschakeld. Als u deze plugin wilt helpen verspreiden (en daarmee ook mij, als ontwikkelaar), selecteer dan a.u.b. deze optie. Anders zal de plugin het in stille modus tonen (als een XHTML commentaar). Als u hiertegen bezwaar maakt, kunt u dit uit de broncode verwijderen.",
		);
	}
