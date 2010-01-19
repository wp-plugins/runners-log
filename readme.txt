=== Runners Log ===
Contributors: frold, jaredatch
Donate link: http://www.liljefred.dk
Tags: plugin, sport, training, running, activity log, fitness, stats, statistics, garmin
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: 1.5.0

This plugin let you convert your blog into a training log and let you track your distance, time, calories and calculate your speed, time per km(or miles), and let you have advance statistics. See screenshots.

== Description ==
This plugin let you convert your blog into a training log and let you track your distance, time, calories and calculate your speed, time per km(or miles), and let you have advance statistics. See screenshots.
At the moment you can specify:
	* Meters
	* Time
	* Pulsavg
	* Calories
	* Garmin Connect Link
In "Settings" >> "Runners Log" you can now specify the fields you like to use.

== Installation ==

This section describes how to install the plugin and get it working.

1. Copy all files to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have basic statistics. It gives you data like: 
    * Meters: 10000
    * Time: 01:04:36
    * Km/hour: 9.29
    * Min/km: 06:27 minutes
    * Puls average: 169
	* Calories: 700 C
    * Garmin Connect Link: http://connect.garmin.com/activity/21569332
    * Km in 2009: 693.7 km based on 122 runs with an avg of 5.69 km
    * Km in 2010: 10 km based on 1 run with an avg of 10 km
4. Place `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have graph based statistics. It gives you a chart of your total distance and hours per month.
5. You only want to have the chart and stats to show up in the category where the sports data is then see FAQ.
6. Runners Log let you use the following tags in your template:
`<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>
<?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?>
<?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?>
<?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?>
<?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?>
<?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?>
<?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?>
<?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?>
<?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?>
<?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?>
<?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?>`

== Frequently Asked Questions ==

= I only want my graphs to show up in a special category =
If you only want your graphs to show up in the category "training" with the category ID = 6 then use it like this eg in single.php:

`<?php if ( in_category('6') ): ?>
<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>
<?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?>
<?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?>
<?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?>
<?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?>
<?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?>
<?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?>
<?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?>
<?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?>
<?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?>
<?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?>
<?php endif; ?>`

	
= I only want my graphs to show up in a special page =
If you only want your graphs to show up in the page with the name "Training Stats" then use it like this eg. in page.php:
BE WARE: <?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?> only works in categories

`<?php if (is_page('Training Stats')) { ?>
<?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?>
<?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?>
<?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?>
<?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?>
<?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?>
<?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?>
<?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?>
<?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?>
<?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?>
<?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?>
<?php } ?>`

= What does this plugin require =
Runners Log is using pChart and therefore is using the GD library to create pictures. 
You must compile the GD library with the freetype extension when installing PHP on a linux server. 
On windows operating system you must add the GD extension in your php.ini file. GD support is a mandatory prerequisite and cannot be overriden. 
You can use the following tutorial http://www.e-gineer.com/v1/instructions/install-gd13-for-php-with-apache-on-linux.htm if you don't know how to install it on a linux server.

= Why is my server load high? =
If you have a very visited blog and using this plugin it could cause high server load as this plugin doesn't use cache for rendering the graphs.
If that's a problem either uninstall this plugin, help making it work with cache :D or wait for a later release.

== Screenshots ==

1. show the Runners Log box
2. the Settings in Admin
3. an example of using `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>`
4. an example of using `<?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?>`
5. an example of using `<?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?>`
6. an example of using `<?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?>`
7. an example of using `<?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?>`
8. an example of using `<?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?>`
9. an example of using `<?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?>`
10. an example of using `<?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?>`
11. an example of using `<?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?>`
12. an example of using `<?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?>`
13. an example of using `<?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?>`


== Changelog ==

= 1.0.0 =
* Initial Release

= 1.0.1 =
* Fixing Screenshots

= 1.0.2 =
* Fixing Screenshots again

= 1.0.3 =
* Fixing if ( category ID = 6 ) { and moved it to templates. This way its easier to upgrade Runners Log

= 1.0.4 =
* More info to readme.txt

= 1.0.5 =
* Optimazing code
* Added 2010 to runners_log_basic()

= 1.0.6 =
* Added the number of run per year and avg per run like: Km in 2009: 693.7 km based on 122 runs with an avg of 5.69 km
* New runners_log_basic() screenshot
* In runners_log_bar_hours() runhours is rounded to 2 instead of 4 decimals

= 1.0.7 =
* The jared^ release

= 1.0.8 =
* Added WP version check
* Now check is $hms, $meters is empty or not
* Added GPL licens
* Changed all templates tags to include if function exist
* Update readme

= 1.5.0 =
* JA - Added support to hide/disable GarminConnectLink
* JA - Added Runners Log write panel for post screen
* JA - Started to add support for Miles
* FL - Ended Miles support
* FL - Added a new field called Calories
* FL - Added support to hide/disable Calories thanks to JA
* FL - Added runners_log_graphmini_calories(), runners_log_pie_calories(), runners_log_bar_calories()
* FL - Renamed runners_log_graphmini_km() to runners_log_graphmini_distance()
* FL - Renamed runners_log_pie_km() to runners_log_pie_distance()
* FL - Renamed runners_log_bar_km() to runners_log_bar_distance()
* FL - Database updater that rename the old custom fields to match the new one
* FL - New screenshots
* FL - Readme update
* FL - Added support to hide/disable Pulse Average

== Upgrade Notice ==

= 1.0.0 =
This was the initial release Januar 1st 2010

= 1.0.5 =
This was release Januar 2nd 2010

= 1.0.6 =
This was release Januar 3rd 2010

= 1.0.8 =
This was release Januar 2010

= 1.5.0 =
This is a major update with renaming the custom fields and adding admin support.

== To Do ==
	* shortcode support
	* enable cache
	* more graphs (pulse avg?)
	* gear list
	* weather support