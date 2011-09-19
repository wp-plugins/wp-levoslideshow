=== LEVO Slideshow ===

Contributors: wpslideshow.com
Author URI: http://wpslideshow.com/levo-slidehsow/
Tags: slideshow, flash
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: trunk

LEVO Slideshow is a plugin that allows you to display a slideshow on your website.

== Description ==

LEVO Slideshow is a plugin that allows you to display a flash slideshow on your website.
It is also allows to use it as a widget. You can also enable this LEVO slideshow on your wordpress site by placing code snippet in your template php file.

**Features**

* Customizable gallery width and gallery height
* Customizable image height and width
* Image description
* Customizable font sizes and colors
* Customizable auto play time
* Play/Pause, next,previous images

For working demo : http://wpslideshow.com/levo-slidehsow/

Requirements/Restrictions:
-------------------------
 * Works with Wordpress 3.0, WPMU (Wordpress 3.0+ is highly recommended)
 * PHP5 

 For working demo : http://wpslideshow.com/levo-slidehsow/

== Installation ==

1. Install automatically through the `Plugins`, `Add New` menu in WordPress, or upload the `wp-levoslideshow` folder to the `/wp-content/plugins/` directory. 

2. Activate the plugin through the `Plugins` menu in WordPress. Look for the "LEVO Slideshow settings" link  on left side menu to configure the Options. 

3. Click on "LEVO Slideshow settings" on leftside menu, you can find "make config.xml" on right side panel bottom location, click on it. 

4. Add the shortcode `[levo_slideshow]` in a Page, Post. Here is how: Log into your blog admin dashboard. Click `Pages`, click `Add New`, add a title to your page, enter the shortcode `[levo_slideshow]` in the page, click `Publish`.

5.Click on "Make Config.xml" below plugin parameters list.

6. Please make "wp-content/plugins/wp-levoslideshow/images","wp-content/plugins/wp-levoslideshow/thumbs", directories writeable(755)" directories writeable(755)

you have problems in using this plugin please contact at addons@wpslideshow.com

For working demo : http://wpslideshow.com/levo-slidehsow/

== Screenshots ==

1. screenshot-1.png is the front-end slideshow page.

2. screenshot-2.png is the  tab on the `Admin Plugins` page.

3. screenshot-3.png is the settings of the slideshow page.

4. screenshot-4.png adding the shortcode `[levo_slideshow]` in a Page.

5. screenshot-5.png Making Config Button below plugin parameters.

== How to use it as a widget ==

After installing "wp-yasslideshow" plugin in your theme just follow below instructions.

1. Go to Appearance > Widgets, we can simply drag & drop "YAS Slideshow" widget where ever you want to display it.

2. To configure your slideshow settings click on  "YAS Slideshow settings" on the leftside, and edit settings and click on "make config.xml" button.

3. If you want display this widget on any certain pages of your site just you need to install 
"widget-context" (http://wordpress.org/extend/plugins/widget-context/) plugin . 


== How to use plug-in in the template code ==

After installing "wp-levoslideshow" plugin, follow the instructions below.

1. Open your theme php file and add the line <?php echo do_shortcode('[levo_slideshow]');?> where ever you like to show the slide show.

