=== Runners Log ===
Contributors: frold, jaredatch
Donate link: http://www.liljefred.dk
Tags: plugin, sport, training, running, activity log, fitness, stats, statistics, garmin, VDOT, BMI, calculator, Training Zones, Race Time Calculator, Training Pace, Body Mass Index
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 1.6.6

This plugin let you convert your blog into a training log and let you track your running get advance statistics and a variety of running related calculators. See screenshots.

== Description ==
This plugin let you convert your blog into a training log and let you track your distance, time, calories and calculate your speed, time per km(or miles), and let you have advance statistics. See screenshots.

You'r now able to use a variety of calculators; Training Zones Calculator, VDOT calculator, V02maxulator Calculator, Race Time Calculator, Training Pace Calculator, Body Mass Index Calculator, Calculate Predicted effect of change in weight.

In "Settings" >> "Runners Log" you can now specify the fields you like to use.

== Installation ==
This section describes how to install the plugin and get it working.

1. Copy all files to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use this short code `[runners_log_basic]` in a post or page. Alternativly place this `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have basic statistics. It gives you data like: 
    * Meters: 8500
    * Time: 00:49:59
    * Km/hour: 10.2
    * Min/km: 05:52 minutes
    * Puls average: 172 bpmis 86% of Max HR and 80% of HRR
    * Calories: 654 C
    * Garmin Connect Link: http://connect.garmin.com/activity/id
    * Km in 2009: 693.7 km based on 122 runs with an avg of 5.69 km
    * Km in 2010: 100.8 km based on 12 runs with an avg of 8.4 km
4. Use this short code `[runners_log_graph]` in a post or page. Alternativly place this `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have graph based statistics. It gives you a chart of your total distance and hours per month.
5. Runners Log support the following short codes `[runners_log_basic]
	[runners_log_graph]
	[runners_log_graphmini_distance]
	[runners_log_graphmini_hours]
	[runners_log_graphmini_calories]
	[runners_log_pie_distance]
	[runners_log_pie_hours]
	[runners_log_pie_calories]
	[runners_log_bar_distance]
	[runners_log_bar_hours]
	[runners_log_bar_calories]`
6. Runners Log let you use the following tags in your template:`<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>
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
7. You only want to have the chart and stats to show up in the category where the sports data is then see FAQ.

== Frequently Asked Questions ==

= The supported short codes =
	[runners_log_basic]
	[runners_log_graph]
	[runners_log_graphmini_distance]
	[runners_log_graphmini_hours]
	[runners_log_graphmini_calories]
	[runners_log_pie_distance]
	[runners_log_pie_hours]
	[runners_log_pie_calories]
	[runners_log_bar_distance]
	[runners_log_bar_hours]
	[runners_log_bar_calories]

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
3. an example of using `[runners_log_basic]`
4. an example of using `[runners_log_graph]`
5. an example of using `[runners_log_pie_distance]`
6. an example of using `[runners_log_bar_distance]`
7. an example of using `[runners_log_bar_hours]`
8. an example of using `[runners_log_graphmini_distance]`
9. an example of using `[runners_log_graphmini_hours]`
10. an example of using `[runners_log_pie_hours]`
11. an example of using `[runners_log_pie_calories]`
12. an example of using `[runners_log_bar_calories]`
13. an example of using `[runners_log_graphmini_calories]`
14. Heart Rate Training Zones Calculator
15. VDOT and Training Zone Calculator
16. V02max Calculator
17. Race Time Calculator
18. Predicted effect of change in weight


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

= 1.5.1 =
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

= 1.6.0 =
* FL - Added short codes support

= 1.6.5 =
* FL - Adding Runners Log to its own side box
* FL - New field in Admin: Resting Heart Rate
* FL - New field in Admin: Maximum Heart Rate
* FL - New field in Admin: Unit Type: Either metric or english
* FL - New field in Admin: Height: Either centimeters or feet+inch(es)
* FL - New field in Admin: Weight: Either kilograms or pounds
* FL - New field in Admin: Age
* FL - New field in Admin: Gender
* FL - Adding Graphs and Stats to Admin
* FL - Heart Rate Training Zone Calculator
* FL - Edit runnerslog_basic to show data like: Puls average: 162 is 81% of Max HR and 74% of HRR
* FL - Body Mass Index Calculator
* FL - Weight Change Effect Calculator
* FL - V02max Calculator
* FL - Training Pace Calculator
* FL - Moved calculators to a Includes folder

= 1.6.6 =
* FL - Fix spelling error in pulsavg: bpmis 
* FL - Fixing unclosed <li> tag in pulsavg in runners_log_basic
* FL - Tested up to: 2.9.2


== Upgrade Notice ==

= 1.0.0 =
This was the initial release Januar 1st 2010

= 1.0.5 =
This was release Januar 2nd 2010

= 1.0.6 =
This was release Januar 3rd 2010

= 1.0.8 =
This was release Januar 2010

= 1.5.1 =
This is a major update with renaming the custom fields and adding admin support.

= 1.6.0 = 
The short codes release

= 1.6.5 =
The calculator release. Februar 2010.

== To Do ==
	* enable cache
	* more graphs (pulse avg?)
	* gear list
	* weather support
	* multi language support