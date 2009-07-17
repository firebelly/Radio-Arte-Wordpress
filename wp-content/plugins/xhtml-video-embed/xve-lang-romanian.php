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
		"language"				=>	"Limba",
		"update_button"			=>	"Actualizeaza Optiuni",
		"show"					=>	"Arata",
		"hide"					=>	"Ascunde",
		"nice"					=>	"optiuni-servicii-video-suportate-direct",
		"nice_text"				=>	"Serviciile video suportate direct sunt acele servicii video pentru care se poate genera un obiect flash utilizand doar informatiile oferite de catre URL. Incepand cu versiunea 0.3 se pot utiliza taguri ce pot modifica dimensiunile unificare salvate in optiuni. Folosind un tag de genul: [tag w=320 h=240]url-pentru-embed[/tag] se va obtine un obiect flash cu latimea de 320 pixeli si inaltimea de 240 pixeli.",
		"ugly"					=>	"optiuni-servicii-video-suportate-indirect",
		"size"					=>	"optiuni-dimensiuni-flash",
		"size_text"				=>	"Aceasta sectiune permite personalizarea dimensiunilor unificate ale obiectelor flash.",
		"options"				=>	"optiuni-de-tot-felul",
		"collapsed"				=>	"Inchis in mod implicit",
		"use_text"				=>	"Utilizez suport -generic- pentru -service-",
		"generic"				=>	"generic",
		"usage"					=>	"Utilizare",
		"usage_swf"				=>	"[swf]calea-catre-fisierul-swf[/swf]",
		"usage_swf_ex"			=>	"Nota: calea poate fi relativa sau absoluta. Calea absoluta este recomandata daca se utilizeaza structura de link-uri optimizata pentru motoarele de cautare atunci cand se incarca fisiere ce sunt gazduite de catre tine. Utilizand cai absolute se pot evita anumite disfunctionalitati. Desigur, calea absoluta este necesara daca utilizezi un fisier SWF gazduit pe alt domeniu sau subdomeniu.",
		"usage_flv"				=>	"[flv]calea-catre-fisierul-flv[/flv]",
		"usage_flv_ex"			=>	"Nota: calea poate fi relativa sau absoluta. Calea absoluta este recomandata daca se utilizeaza structura de link-uri optimizata pentru motoarele de cautare atunci cand se incarca fisiere ce sunt gazduite de catre tine. Utilizand cai absolute se pot evita anumite disfunctionalitati. Desigur, calea absoluta este necesara daca utilizezi un fisier FLV gazduit pe alt domeniu sau subdomeniu. Atentie: Aceasta functionalitate are alti termeni de licentiere din moment ce JW FLV MEDIA PLAYER <strong>nu</strong> este lansat sub GPL v2.0.",
		"usage_youtube"			=>	"[youtube]http://www.youtube.com/watch?v=5LtYk7wsFnk[/youtube]",
		"usage_youtube_ex"		=>	"Nota: Parametrii suplimentari aflati in URL sunt ignorati, spre exemplu<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&feature=dir</em>. Incepand cu versiunea 0.3 a fost introdus suport pentru continut de calitate superioara, deci se vor putea utiliza URL-urile ce au parametrul fmt si valorile suportate, 6 si 18:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=6</em>;<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=18</em>.",
		"usage_google-video"	=>	"[google-video]http://video.google.com/videoplay?docid=-1770384172897733802[/google-video]",
		"usage_google-video_ex"	=>	"Nota: Parametrii suplimentari aflati in URL sunt ignorati, spre exemplu<br />http://video.google.com/videoplay?docid=-1770384172897733802<em>&hl=en</em>",
		"usage_metacafe"		=>	"[metacafe]http://www.metacafe.com/watch/822298/too_much_time_to_waste/[/metacafe]",
		"usage_trilu-video"		=>	"[trilu-video]http://www.trilulilu.ro/SaltwaterC/54fcf02ed330bd[/trilu-video]",
		"usage_trilu-audio"		=>	"[trilu-audio]http://www.trilulilu.ro/ambro/26129477a2da0c[/trilu-audio]",
		"usage_trilu-image"		=>	"[trilu-image]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-image]",
		"usage_trilu-image_ex"	=>	"Mod de utilizare alternativ:<br /><strong>[trilu-imagine]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-imagine]</strong>. Tag-ul [trilu-image] a fost introdus incepand cu versiunea 0.2.3 pentru a pastra suportul nativ de limba a acestui plugin. In acel moment era limba engleza. [trilu-imagine] a fost introdus inca din prima versiune si este utilizat pentru a pastra compatibilitatea cu pluginul de WordPress scris de Trilulilu. Spre deosebire de versiunile anterioare, [trilu-image] este tag-ul standard incepand cu versiunea 0.3, deci recomandarea este sa-l utilizezi pe acesta.",
		"usage_dailymotion"		=>	"[dailymotion]http://www.dailymotion.com/us/cluster/auto/featured/video/x4g5zc[/dailymotion]",
		"usage_dailymotion_ex"	=>	"Nota: parametrii suplimentari ce se pot gasi pe langa video ID sunt ignorati. Atata timp cat structura /video/{video_id} din URL este intacta, plugin-ul functioneaza. Utilizeaza URL-uri din bara de adresa a browserului.",
		"usage_myspace"			=>	"[myspace]http://vids.myspace.com/index.cfm?fuseaction=vids.individual&VideoID=28776245[/myspace]",
		"usage_myspace_ex"		=>	"Nota: MySpace TV nu functioneaza cu o versiune veche de Flash Player cum ar fi versiunea 7. Imaginea nu se vede. Doar sunetul functioneaza.",
		"usage_revver"			=>	"[revver]http://revver.com/video/268495/hows-my-driving/[/revver]",
		"usage_spike"			=>	"[spike]http://www.spike.com/video/2881456[/spike]",
		"usage_spike_ex"		=>	"Nota: atunci cand se utilizeaza linkuri de forma <br /><em>http://www.spike.com/video/2881456?cmpnid=800&lkdes=VID_2881456</em>,<br />parametrii de dupa video ID sunt ignorati.",
		"usage_vimeo"			=>	"[vimeo]http://www.vimeo.com/212286[/vimeo]",
		"usage_jumpcut"			=>	"[jumpcut]http://www.jumpcut.com/view?id=A08576661C0211DDABFF000423CF4092[/jumpcut]",
		"usage_mogulus"			=>	"[mogulus]http://www.mogulus.com/looneytunesnetwork[/mogulus]",
		"usage_capped"			=>	"[capped]http://capped.tv/playeralt.php?vid=fairlight-panic_room[/capped]",
		"usage_gametrailers"	=>	"[gametrailers]http://www.gametrailers.com/player/39088.html[/gametrailers]",
		"usage_veevo"			=>	"[veevo]http://www.veevo.ro/mojoplayer.swf?id=148be5b09a2fc2[/veevo]",
		"usage_veevo_ex"		=>	"Nota: pentru moment, URL-ul trebuie copiat din codul embed si nu din bara de adresa a browserului Web. Dimensiunile trebuie specificate in functie de video. In caz contrar, vor fi folosite dimensiunile unificate ce sunt implicite.",
		"usage_collegehumor"	=>	"[collegehumor]http://www.collegehumor.com/video:1795924[/collegehumor]",
		"usage_myvideo"			=>	"[myvideo]http://www.myvideo.de/watch/5610434/Ballern_VS_Landstrasse[/myvideo]",
		"width"					=>	"Latime obiect",
		"video_height"			=>	"Inaltime obiect Video",
		"audio_height"			=>	"Inaltime obiect Audio",
		"credit"				=>	"Utilizati link catre autor",
		"credit_text"			=>	"Nota: aceasta optiune plaseaza un mic link sub fiecare obiect flash cu <a href='http://saltwaterc.net/xhtml-video-embed' target='_blank'>&raquo; Powered by XHTML Video Embed</a>. Nu trebuie sa il utilizezi, deci de aceea este dezactivat in mod implicit. Daca doresti sa ajuti la raspandirea acestui plug-in (si pe mine ca dezvoltator al lui), atunci te rog sa bifezi aceasta optiune. In caz contrar, plug-in-ul afiseaza un text pe post de comentariu XHTML ce va fi invizibil pentru vizitatori, dar va fi vizibil in sursa paginii. Daca te deranjeaza acest aspect, atunci indeparteaza aceasta notificare din codul sursa.",
		);
	}
