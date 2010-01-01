=== Runners Log ===
Contributors: frold
Donate link: http://www.liljefred.dk
Tags: sport, training, running, activity log, fitness, stats, statistics
Requires at least: 2.7
Tested up to: 2.9
Stable tag: 1.0.4

This plugin lets you convert your blog to a training log. 

== Description ==

Based on 4 custom fields:

*   Metres
*   Time
*   Pulsavg
*   GarminConnectLink

it lets you calculate your speed, time per km, and lets you have a chart of your total distance and minutes per month.

== Installation ==

This section describes how to install the plugin and get it working.

1. Copy all files to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php runners_log_basic(); ?>` in your templates to have basic statistics. It gives you data like: 
    * Meters: 9270
    * Time: 01:00:00
    * Km/hour: 9.27
    * Min/km: 06:28 minutes
    * Puls average: 162
    * Garmin Connect Link: http://connect.garmin.com/activity/21097094
    * Total meters run in 2009: 171610 in km: 171.61
4. Place `<?php runners_log_graph(); ?>` in your templates to have graph based statistics. It gives you a chart of your total distance and hours per month.
5. You only want to have the chart and stats to show up in the category where the sports data is then see FAQ.
6. Every time you post an activity in your Sport or Traing Category add the custom fields: `Meters` and `Time`. Its optional to put in `Pulsavg` and `GarminConnectLink` for your cours.
7. `Time` needed to be formated as HH:MM:SS eg. like `00:37:20` for 37min and 20 seconds.
8. Runners Log let you use the following tags in your template:
    * `<?php runners_log_basic(); ?>`
	* `<?php runners_log_graph(); ?>`
	* `<?php runners_log_pie_hours(); ?>`
	* `<?php runners_log_pie_km(); ?>`
	* `<?php runners_log_bar_km(); ?>`
	* `<?php runners_log_bar_hours(); ?>`
	* `<?php runners_log_graphmini_km(); ?>`
	* `<?php runners_log_graphmini_hours(); ?>`

== Frequently Asked Questions ==

= I only want my graphs to show up in a special category =
If you only want your graphs to show up in the category "training" with the category ID = 6 then use it like this:

    * `<?php if ( in_category('6') ): ?>`
    * `<?php runners_log_basic(); ?>`
	* `<?php runners_log_graph(); ?>`
	* `<?php runners_log_pie_hours(); ?>`
	* `<?php runners_log_pie_km(); ?>`
	* `<?php runners_log_bar_km(); ?>`
	* `<?php runners_log_bar_hours(); ?>`
	* `<?php runners_log_graphmini_km(); ?>`
	* `<?php runners_log_graphmini_hours(); ?>`
	* `<?php endif; ?>`

= What does this plugin require =
Runners Log is using pChart and therefore is using the GD library to create pictures. 
You must compile the GD library with the freetype extension when installing PHP on a linux server. 
On windows operating system you must add the GD extension in your php.ini file. GD support is a mandatory prerequisite and cannot be overriden. 
You can use the following tutorial http://www.e-gineer.com/v1/instructions/install-gd13-for-php-with-apache-on-linux.htm if you don't know how to install it on a linux server.

= Why is my server load high? =
If you have a very visited blog and using this plugin it could cause high server load as this plugin doesn't use cache for rendering the graphs.
If that's a problem either uninstall this plugin, help making it work with cache :D or wait for a later release.

== Screenshots ==

1. show you how your custom fields should look like
2. gives gives an example of using `<?php runners_log_basic(); ?>`
3. gives gives an example of using `<?php runners_log_graph(); ?>`
4. gives gives an example of using `<?php runners_log_pie_hours(); ?>`
5. gives gives an example of using `<?php runners_log_pie_km(); ?>`
6. gives gives an example of using `<?php runners_log_bar_km(); ?>`
7. gives gives an example of using `<?php runners_log_bar_hours(); ?>`
9. gives gives an example of using `<?php runners_log_graphmini_hours(); ?>`

== Changelog ==

= 1.0.0 =
* Initial Release

= 1.0.1 =
* Fixing Screenshots

= 1.0.2 =
* Fixing Screenshots again

= 1.0.3 =
* Fixing if ( category ID = 6 ) {

= 1.0.4 =
* More info to readme.txt

== Upgrade Notice ==

= 1.0.0 =
This was the initial release Januar 1st 2010

== Screenshots explanation ==

	* screenshot-1.png show you how your custom fields should look like
	* screenshot-2.png gives gives an example of using `<?php runners_log_basic(); ?>`
	* screenshot-3.png gives gives an example of using `<?php runners_log_graph(); ?>`
	* screenshot-4.png gives gives an example of using `<?php runners_log_pie_hours(); ?>`
	* screenshot-5.png gives gives an example of using `<?php runners_log_pie_km(); ?>`
	* screenshot-6.png gives gives an example of using `<?php runners_log_bar_km(); ?>`
	* screenshot-7.png gives gives an example of using `<?php runners_log_bar_hours(); ?>`
	* screenshot-8.png gives gives an example of using `<?php runners_log_graphmini_km(); ?>`
	* screenshot-9.png gives gives an example of using `<?php runners_log_graphmini_hours(); ?>`

== To Do ==

	* enable cache
	* more graphs 
	* add miles support