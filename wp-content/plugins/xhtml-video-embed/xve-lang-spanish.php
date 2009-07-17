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
		"language"				=>	"Idioma",
		"update_button"			=>	"Actualizar opciones",
		"show"					=>	"Mostrar",
		"hide"					=>	"Ocultar",
		"nice"					=>	"servicios-de-vídeo-bien-apoyados",
		"nice_text"				=>	"Los servicios de vídeo bien apoyados son aquellos que pueden ser incrustados mediante un objeto Flash que sólo requiere información derivada de su dirección URI. A partir de la v0.3 puede modificar los tamaños predeterminados para cada uno de estos servicios mediante una sintaxis como [tag w=320 h=240]url-to-embed[/tag] que genera un objeto con 320 píxeles de ancho y 240 píxeles de alto.",
		"ugly"					=>	"opciones-de-vídeo-mal-apoyados",
		"size"					=>	"opciones-de-tamaño-de-flash",
		"size_text"				=>	"En esta sección puede modificar los tamaños predefinidos para los objetos Flash.",
		"options"				=>	"opciones-diversas",
		"options_text"			=>	"Esta sección contiene las opciones misceláneas.",
		"collapsed"				=>	"Colapsado por defecto",
		"use_text"				=>	"¿Usar modo -generic- -service-",
		"generic"				=>	"genérico",
		"usage"					=>	"Uso",
		"usage_swf"				=>	"[swf]ruta-al-archivo-swf[/swf]",
		"usage_swf_ex"			=>	"Nota: el nombre de la ruta puede ser o bien relativa, o absoluta. Se recomienda usar la ruta absoluta cuando usa la estructura de enlaces permanentes sencillos, para cargar archivos típicamente hospedados por usted. Al usar rutas absolutas, puede evitar que se den determinados fallos. Por supuesto, se requiere una ruta absoluta si usa un objeto SWF hospedado en otro dominio o subdominio.",
		"usage_flv"				=>	"[flv]ruta-al-archivo-flv[/flv]",
		"usage_flv_ex"			=>	"Nota: la ruta puede ser o bien relativa, o absoluta. Se recomienda usar la ruta absoluta cuando usa la estructura de enlaces permanentes sencillos, para cargar archivos típicamente hospedados por usted. Al usar rutas absolutas, puede evitar que se den determinados fallos. Por supuesto, se requiere una ruta absoluta si usa un objeto FLV hospedado en otro dominio o subdominio. Por favor, recuerde que se aplica una licencia de uso distinta cuando usa esta función, ya que JW FLV MEDIA PLAYER <strong>no</strong> es GPL v2.0.",
		"usage_youtube"			=>	"[youtube]http://www.youtube.com/watch?v=5LtYk7wsFnk[/youtube]",
		"usage_youtube_ex"		=>	"Nota: se descartan los parámetros extras cuando usa un enlace permanente como éste:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&feature=dir</em>. A partir de la v0.3 se introdujo apoyo para contenido de alta definición, por lo que puede usar el parámetro fmt con los valores válidos, que son 6 y 18:<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=6</em>;<br />http://www.youtube.com/watch?v=5LtYk7wsFnk<em>&fmt=18</em>.",
		"usage_google-video"	=>	"[google-video]http://video.google.com/videoplay?docid=-1770384172897733802[/google-video]",
		"usage_google-video_ex"	=>	"Nota: se descartan los parámetros extras cuando usa un enlace permanente como éste:<br />http://video.google.com/videoplay?docid=-1770384172897733802<em>&hl=en</em>",
		"usage_metacafe"		=>	"[metacafe]http://www.metacafe.com/watch/822298/too_much_time_to_waste/[/metacafe]",
		"usage_trilu-video"		=>	"[trilu-video]http://www.trilulilu.ro/SaltwaterC/54fcf02ed330bd[/trilu-video]",
		"usage_trilu-audio"		=>	"[trilu-audio]http://www.trilulilu.ro/ambro/26129477a2da0c[/trilu-audio]",
		"usage_trilu-image"		=>	"[trilu-image]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-image]",
		"usage_trilu-image_ex"	=>	"Uso alternativo:<br /><strong>[trilu-imagine]http://www.trilulilu.ro/SaltwaterC/336f07c30b8849[/trilu-imagine]</strong>. La etiqueta [trilu-image] se introdujo con v0.2.3 para ajustarse al idioma nativo (inglés) de este plugin. Se brindó soporte a [trilu-imagine] desde un principio, para preservar la plena compatibilidad con el plugin propio de Trilulilu para WordPress. \"Imagine\" es rumano para \"Imagen\" y se usó porque Trilulilu es un servicio rumano de video/audio/imagen. A diferencia de anteriores versiones, y desde v0.3 la etiqueta estándar de Trilulilu para imágenes es [trilu-image]. Se recomienda usar la etiqueta [trilu-image] dado que la alternativa sólo se implementa por motivos de compatibilidad.",
		"usage_dailymotion"		=>	"[dailymotion]http://www.dailymotion.com/us/cluster/auto/featured/video/x4g5zc[/dailymotion]",
		"usage_dailymotion_ex"	=>	"Nota: los parámetros adicionales que acompañan el número ID del vídeo son descartados. Siempre que se mantenga intacto el /video/{video_id} - del URI, el plugin funcionará. Use los URI a partir de la barra de dirección de su navegador.",
		"usage_myspace"			=>	"[myspace]http://vids.myspace.com/index.cfm?fuseaction=vids.individual&VideoID=28776245[/myspace]",
		"usage_myspace_ex"		=>	"Nota: MySpace TV no funciona cuando usa una versión de Flash Player antigua, como v7 - no mostrará imagen alguna. Sólo oirá el sonido.",
		"usage_revver"			=>	"[revver]http://revver.com/video/268495/hows-my-driving/[/revver]",
		"usage_spike"			=>	"[spike]http://www.spike.com/video/2881456[/spike]",
		"usage_spike_ex"		=>	"Nota: cuando usa enlaces permanentes como éste:<br /><em>http://www.spike.com/video/2881456?cmpnid=800&lkdes=VID_2881456</em>,<br />los parámetros que siguen el número ID del vídeo se descartan.",
		"usage_vimeo"			=>	"[vimeo]http://www.vimeo.com/212286[/vimeo]",
		"usage_jumpcut"			=>	"[jumpcut]http://www.jumpcut.com/view?id=A08576661C0211DDABFF000423CF4092[/jumpcut]",
		"usage_mogulus"			=>	"[mogulus]http://www.mogulus.com/looneytunesnetwork[/mogulus]",
		"usage_capped"			=>	"[capped]http://capped.tv/playeralt.php?vid=fairlight-panic_room[/capped]",
		"usage_gametrailers"	=>	"[gametrailers]http://www.gametrailers.com/player/39088.html[/gametrailers]",
		"usage_veevo"			=>	"[veevo]http://www.veevo.ro/mojoplayer.swf?id=148be5b09a2fc2[/veevo]",
		"usage_veevo_ex"		=>	"Nota: de momento, debe copiarse el enlace del código de incrustación, no de la barra de dirección de su navegador. Debe indicar el tamaño del vídeo para la proporción correcta de imagen; si no, se usará el valor por defecto.",
		"usage_collegehumor"	=>	"[collegehumor]http://www.collegehumor.com/video:1795924[/collegehumor]",
		"usage_myvideo"			=>	"[myvideo]http://www.myvideo.de/watch/5610434/Ballern_VS_Landstrasse[/myvideo]",
		"width"					=>	"Ancho",
		"video_height"			=>	"Altura del vídeo",
		"audio_height"			=>	"Altura del audio",
		"credit"				=>	"Mostrar crédito para XVE",
		"credit_text"			=>	"Nota: esta opción coloca un pequeño enlace debajo del objeto Flash insertado, con <a href='http://saltwaterc.net/xhtml-video-embed' target='_blank'>&raquo; Powered by XHTML Video Embed</a>. No tiene la obligación de usarlo, por lo que está deshabilitado por defecto. Si desea apoyar la divulgación de este plugin (y a mí, como su desarrollador), entonces por favor seleccione esta opción. De lo contrario, el plugin lo muestra en modo silenciado (como comentario XHTML). Si tiene objeción a ello, puede quitarlo en el código fuente.",
		);
	}
