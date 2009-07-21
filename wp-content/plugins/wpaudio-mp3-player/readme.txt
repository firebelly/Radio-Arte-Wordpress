=== WPaudio MP3 Player ===
Contributors: toddiceton
Donate link: http://wpaudio.com/donate
Tags: audio, embed, media, mp3, music
Requires at least: 2.5
Tested up to: 2.8.1
Stable tag: 1.2

Play mp3s in your posts with the simplest, cleanest mp3 player.  Supports other players' tags and separate download URLs.  CSS styling coming soon.

== Description ==

### All the other WordPress audio players were crappy or ugly so I made a better one.  
  
![WPaudio](http://wpaudio.com/screenshot.png)

Deactivate that lame Flash player and install a plugin that makes you proud to embed mp3s. WPaudio installs in seconds, looks great, and uses simple tags... all without bulky files or affecting page load times.

#### Easy to install, easy to use

Install directly from WordPress (just go to *Plugins* -> *Add New*) and start embedding mp3s immediately. You supply the URL -- WPaudio does the rest. It's as easy as `[wpaudio url="http://url.to/your.mp3"]`.

#### Clean design with intuitive controls

Everything's tucked out of the way until you click play. Jog the track by clicking the position bar. Download by clicking the title. Simple.

#### Compatible with your old audio player tags

Want to switch to a better player? Just deactivate your old plugin and let WPaudio give your old posts the dignity they deserve. WPaudio is compatible with Audio Player tags, with support for more on the way.

#### Won't slow down your site

WPaudio was written to be as lightweight and fast-loading as possible. It uses Google's content delivery network for maximum speed.

### How to use WPaudio

  

1. Upload your mp3 by clicking the musical note next to *Upload/Insert* when editing a post.
1. Copy the mp3's URL.  You can get it in the *Link URL* section by clicking *File URL*.  Exit the *Add Audio* window.
1. Use the `[wpaudio]` tag to embed the mp3 in your post.  Here are your options.
		
	* Let WPaudio read artist and song info from the mp3

			[wpaudio url="http://url.to/your.mp3"]

	* Or specify the text you'd like displayed on the player (optional)

			[wpaudio url="http://url.to/your.mp3" text="Artist - Song"]
		
	* You can also change the download URL if you'd like to use a file host like Mediafire or YSI (optional)
	
			[wpaudio url="http://url.to/your.mp3" text="Artist - Song" dl="http://download-host.com/song.mp3"]
  
(Powered by the JW Player - http://www.longtailvideo.com)

== Installation ==

Follow these instructions to install WPaudio.

1. Unzip `wpaudio-mp3-player.1.2.zip` in the `/wp-content/plugins/` directory.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. If you want WPaudio to handle old Audio Player tags (`[audio:http://url.to/your.mp3]`), go to *Settings* -> *WPaudio* and select that option.

== Frequently Asked Questions ==

= I use (some other mp3 player).  Do I have to go back and change ALL my tags? =

Nope.  If you used Audio Player, just tell WPaudio to handle it on the *Settings* -> *WPaudio* page.  If you used another plugin, email me and I'll get support for it into the next version.

= Should I include the *text* parameter? =

It's not necessary, but the player will load marginally faster, so I try to include it.

= What if I don't want readers to download the mp3 from my server? =

Just add the *dl* option to your shortcode like this.

	[wpaudio url="http://url.to/your.mp3" dl="http://download-host.com/mp3_download_url"]


== Screenshots ==

1. WPaudio player in action.  The blue bar indicates current position, the gray bar indicates the data loaded so far, and the light gray bar indicates total duration.  Download by clicking the name of the clip.  (Optionally, you can change the download link for use with Mediafire, YSI, etc.)
2. WPaudio player before play is clicked.
3. WPaudio player in action.
4. Editing the tag.

== Changelog ==

= 1.2 =
* Fixed directory settings
* Improved readme

= 1.1 =
* Text support, smoother ID3 reading
* Download link parameter

= 1.0 =
* Shortcode support
* Google CDN-served jQuery and SWFObject
