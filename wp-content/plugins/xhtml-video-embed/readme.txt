=== Plugin Name ===
Contributors: SaltwaterC
Tags: xhtml, embed, flash, video, embedding, swf, youtube, youtube hd, google video, metacafe, trilulilu, dailymotion, myspace tv, spike, ifilm, vimeo, flv, jumpcut, myspace, mogulus, capped, capped tv, gametrailers, veevo, collegehumor, myvideo
Requires at least: 2.0
Tested up to: 2.7
Stable tag: 0.3.6

XHTML Video Embed is a simple yet powerful way to add flash content to your WordPress blog.

== Description ==

XHTML Video Embed is a simple yet powerful way to add flash content to your WordPress blog. The plug-in uses a simple syntax which is based on few BB-like tags. The supported tags along with the supported services are described into the preferences panel of the plug-in which also serves as self-documentation. Basically the usage is something like [tag]http://example.com/video-url[/tag], where the http://example.com/video-url is the permalink of the supported video service which you would like to embed.

I wrote this plug-in because I was kinda unhappy with the existing solutions. I really care about the XHTML validation of my blog and other stuff such as the usability while maintaining the security. If you also care about these things, then this plug-in might be the solution for you. Most of the video services provide the embed code which can be placed into your blog's posts, but most of the time the code is broken because it uses proprietary HTML tags such as embed. Another downside is the fact that embed itself is rejected by WordPress when using the rich text editor - TinyMCE.

The plug-in is also safe. When adding content by using XVE, its engine makes sure that the disallowed chars which could harm your blog are filtered by regular expressions. You won't hurt your blog by adding potentially harmful content. I know that for most of the users the security is some kind of paranoid stuff, but better be safe than sorry. The only harmful content might be the SWF embedding, so please, be careful when using external sources. The flash files can execute JavaScript code without any warning.

= Features: =

1. Unified sizes for all flash objects (based on file type). Unified custom sizes for the flash objects (since v0.2.2). The possibility to override the unified sizes by using a specially formatted tag as [tag w=320 h=240]url-to-embed[/tag] which generates a flash object with the specified sizes (since v0.3). This example uses 320x240 as modified resolution.
1. Admin panel which allows you to check which services you would like to use and other various options (since v0.2). This panel also provides self documentation. The self documentation and options are now collapsible in order to use the space more efficient (since v0.2.3).
1. Transparency for the object which exceeds the actual resolution of the SWF file. Another flash parameter is the quality which defaults to "best" (since v0.2.3).
1. Localization support (since v0.3). The support includes English and Romanian (thanks to yours truly). It also includes Spanish and Dutch localization (thanks to Alvaro, http://nv1962.net/ ). For other languages, I would need some volunteers.
1. Support for embedding videos from your posts directly into your template (sice v0.3.5) via the `swc_the_video_excerpt()` function. Read the FAQ for more details. This feature is targeted ar uses with average WordPress templating knowledge.
1. Generic SWF support as video resolution (since v0.2).
1. Generic FLV embedding support by using FLV Player, http://flv-player.net/ (since v0.3.3). This feature was available since v0.2.4, but it used another embedded FLV player which has a restrictive license, although the previous player is Open Source as well.
1. YouTube embed support. The support for high definition content by using the proper URLs (since v0.3.5, previous versions were broken). 
1. Google Video embed support.
1. Metacafe embed support.
1. Trilulilu embed support (video, audio, image) - yes, this is more that videos ;). Full compatibility with Trilulilu's own WordPress plug-in.
1. Dailymotion embed support (since v0.2.3).
1. MySpace TV embed support (since v0.2.3).
1. Revver embed support (since v0.2.3).
1. Spike (ex iFilm) embed support (since v0.2.3).
1. Vimeo embed support (since v0.2.3).
1. Jumpcut embed support (since v0.3).
1. Mogulus embed support (since v0.3.2). Since v0.3.3, in order to keep a decent aspect of the embedded window, the height of this service is automatically multiplied with 1.1 which is one of the best compromises for the default 4:3 aspect ratio that is used by XVE.
1. Capped TV embed support (since v0.3.4).
1. GameTrailers embed support (since v0.3.4).
1. Veevo embed support (since v0.3.5).
1. CollegeHumor support (since v0.3.5).
1. MyVideo support (since v0.3.6).

== Installation ==

1. Upload `xhtml-video-embed.php` to the `/wp-content/plugins/xhtml-video-embed` directory.
1. Upload at least a language file to the `/wp-content/plugins/xhtml-video-embed` directory. The `xve-lang-english.php` file is not mandatory, but it's highly recommended to upload this file as well as your localization.
1. Upload `mediaplayer.swf` to the `/wp-content/plugins/xhtml-video-embed` directory. Notice: the directory for the plug-in installation can have any name since v0.2.4.1, but `mediaplayer.swf` and `xhtml-video-embed.php` *MUST* be placed into the same directory. As with v0.2.4.2, the installation path *can* be `wp-content/plugins`, but this isn't recommended as the future version will include more files. Please use a subdirectory from now on.
1. Activate the plug-in through the 'Plugins' menu in WordPress.
1. Check the preferences panel (Options -> XVE for WordPress up to 2.3.3, Settings -> XVE for WordPress 2.5 and above) for more details and fine tuning.

== Frequently Asked Questions ==

= Does the plug-in affect the blog's performance? =

The impact should be minimal as the code is efficient. Since the v0.3 it is even more faster as it uses less database connections, precisely, onto the user side the plugin uses just a single MySQL query which is packaged by the WP API. The page load time is increased though as the browser needs to download data from the external service(s) when loading a certain page. However, checking off the support for the video services which you don't use should make the plug-in even faster as it has less matches into the replacement pattern. This reduces the so called "bloat" by using only the video services which you would like to embed.

= Why `swc_the_video_excerpt()`? =

Why not? Since there's a template tag named `the_exerpt()` which people may use into their templates in order to create text exerpts, why shouldn't we have a function that does the same thing for the embedded flash content? So here's my solution to this.

= How can I call the `swc_the_video_excerpt()` function? =

You need it to either call it from within The Loop, so it automatically takes the content from the posts that pass through this function. Otherwise, you can specify a category ID. If you specify an ID, the function creates it's own internal loop in order to access the WordPress template tags. Do not mix these methods ... some issues may appear as some(most?) of the WordPress versions are not pretty happy with nested post loops.

The recommended call should look like:

`if(function_exists('swc_the_video_excerpt')) swc_the_video_excerpt();`

The function has four parameters which are optional: width, video height, audio height (for trilu-audio), category ID. If you want custom sizes for videos, you need to specify at least with and video height.

`if(function_exists('swc_the_video_excerpt')) swc_the_video_excerpt(320, 240);`

If you want to use a custom category outside The Loop, you need to specify all parameters.

`if(function_exists('swc_the_video_excerpt')) swc_the_video_excerpt(320, 240, 60, 1);`

If the custom sizes aren't specified, the function uses the unified sizes which are defined into the XVE admin. If you specify the sizes from the embedding tag with the w and h parameters, then all sizes specified above are overrided by this setting.

= Why are you mean and you won't add Hulu.com? =

Well, there are a couple of reasons. The first reason which is pretty strong, thus the main reason, is the fact that I can not develop something that I can not use as Hulu.com is only available for the U.S. consumers. The other reason is that I won't code the support for a locally available service into a plug-in which is globally available to any WordPress user. The service must be at least supported by U.S. and E.U.

= How do I install the support for the FLV embedding? =

Basically you need to place the `mediaplayer.swf` file within the same directory as `xhtml-video-embed.php`.

= How do I install the language support? =

As the FLV support, you need to place the language file which looks like `xve-lang-english.php` into the same installation directory.

= How do I remove a language? =

Simply delete the file. It's recommended to pick the new language from the menu, then delete the file, otherwise it automatically tries to load the English file. If the English file fails to load, then the plug-in throws an error. It's recommended to keep the English language in case something bad happens.

= The flash object isn't displayed. Did I do something wrong? =

This question doesn't have an exact answer. The flash won't load if the video service times out. Check if it works. This could be your error as well, so check the permalink if it's intact. For best results, use the URL provided by the browser's address bar. If this isn't the case, and the video service is in uptime, but the flash object still won't load, then maybe one of my security policies is too restrictive for that video sharing service. Drop me a comment onto the plug-in's official page which is located on my blog. I'll try to reproduce the issue, so please provide the URL which makes the plug-in to malfunction. I can't debug something that I can't reproduce.

= Why the plug-in doesn't have a quick-tag support? =

Basically one of the main reasons is the fact that my JavaScript skills are less than average. I am mainly a PHP/MySQL coder which does backends. The other main reason is the fact that actually the lack of interface makes the plug-in to be compatible with lots of WordPress versions without any update to the XVE core (yes, I love code portability). It should be compatible with v1.5 (untested), but I don't recommend it, so that's why the WordPress page specifies v2.0 as the lower supported version.

= What should the PHP host support? =

The PHP host should support PCRE (Perl Compatible Regular Expressions) as the plug-in uses them to match and replace the tags from your post with the properly formated flash objects. PHP v4 is enough, thus you won't need a PHP v5 host. The plug-in works on Windows hosts as well as on *nix hosts. The only cross-platform issue was the path detection for the embedded FLV media player which was fixed by v0.2.4.2.

= The tags should be written as described into the documentation? =

The tags are case insensitive, thus the plug-in works for either [youtube] or [YouTube], etc. Your choice.

= What's the supported syntax for the contend between the tags? =

For generic SWF/FLV the URL should be an absolute path to the .swf/.flv file. If you're not using pretty permalinks, then you can use a relative path. For externally hosted SWF/FLV files obviously you need to use an absolute path. For the supported video services you need to use the permalink to the file you would like to embed within your blog. For video services such as MySpace TV which use both "VideoID" and "videoid" into the permalink, the plug-in matches the VideoID text as case insensitive. This applies wherever is possible.

= Does the extra parameter(s) from the permalink break the behavior of the plug-in? =

If the URL is properly formatted (Example: you copy the URL from the browser's address bar), then everything should be OK. Some video services like YouTube may use extra parameters within the URL besides the video ID. Other services such as Dailymotion use a permalink structure which isn't very friendly, but XVE handles it nicely as long as the video ID is valid.

= Why the plug-in supports only flash objects? =

Because it just works. I spent countless hours on figuring out how to embed various video types under various platforms and I was kinda disappointed. I am not just a Windows user, I also use Linux, FreeBSD with Linux compatibility for Flash Player, occasionally Mac OS X, so I would like to see it working, no matter which is the OS. Most of the video services still use video flash players for the same reason. Please notice the fact that these words come from a person that isn't a flash lover (myself).

= How's the compatibility with various WordPress versions? =

Pretty good actually since the plug-in doesn't use anything from WordPress code base except the hooks which add the extra functionality, and the functions which manage the options. The processing is done internally so I am the only one which provides the compatibility among various versions.

= Do I have to use the credits link? =

No, that's not mandatory. Actually, that option comes as disabled by default. If you would like to help me spread the plug-in, then use the option as checked. It places a small "Powered by XHTML Video Embed" under all the inserted flash objects. If the option is disabled, then the credit is placed as XHTML comment which is ignored, but it can be seen by the visitors who view the XHTML source of the page which contains the object. If you don't like having comments within your source code, then comment the line which places this message, but it would be nice not to alter the code since I spent my spare time to code this stuff. Your choice.