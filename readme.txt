=== Runners Log ===
Contributors: frold, TheRealEyeless, jaredatch, michaellasmanis
Donate link: http://www.liljefred.dk
Tags: plugin, sport, training, running, activity log, fitness, stats, statistics, garmin, VDOT, BMI, calculator, Training Zones, Race Time Calculator, Training Pace, Body Mass Index, gear, gear management
Requires at least: 2.7
Tested up to: 3.1.3
Stable tag: 2.2.5

This plugin let you convert your blog into a training log and let you track your activities. You get advance statistics and running related calculators. See screenshots.

== Description ==
This plugin let you convert your blog into a training log and let you track your activities. You get advance statistics and running related calculators. See screenshots.

Using Runners-log you'r able to use a variety of calculators; Training Zones Calculator, VDOT calculator, V02max-Calculator, Race Time Calculator, Training Pace Calculator, Body Mass Index Calculator, Calculate Predicted effect of change in weight.

Thanks to TheRealEyeless we have weather support and now having multilanguage support. Other features including a Gear Manager that let you track the use of your equipment when it is fully integrated (a beta function atm).

You can add graphs using Google Chart or pChart see FAQ for howto use it.

== Installation ==
This section describes how to install the plugin and get it working.

1. Copy all files to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use this short code `[runners_log_basic]` in a post or page. 
Alternativly place this `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have basic statistics. It gives you data like: 
    * Meters: 8500
    * Time: 00:49:59
    * Km/hour: 10.2
    * Min/km: 05:52 minutes
    * Puls average: 172 bpmis 86% of Max HR and 80% of HRR
    * Calories: 654 C
    * Garmin Connect Link: http://connect.garmin.com/activity/id
    * Km in 2009: 693.7 km based on 122 runs with an avg of 5.69 km
    * Km in 2010: 100.8 km based on 12 runs with an avg of 8.4 km
	* ~embed garmin connect map~
4. Use this short code `[runners_log_graph]` in a post or page. Alternativly place this `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your templates to have a chart of your total distance and hours per month.
5. Runners Log support the following short codes using pChart
	* `[runners_log]`
	* `[runners_log_basic]`
	* `[runners_log_graph]`
	* `[runners_log_graphmini_distance]`
	* `[runners_log_graphmini_hours]`
	* `[runners_log_graphmini_calories]`
	* `[runners_log_pie_distance]`
	* `[runners_log_pie_hours]`
	* `[runners_log_pie_calories]`
	* `[runners_log_bar_distance]`
	* `[runners_log_bar_hours]`
	* `[runners_log_bar_calories]`
	* `[runners_log_garminmap]`
	* `[runners_log_weather]`
	* `[runners_log_weather_footer]`
6. Runners Log let you use the following tags in your template:
	* `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>`
	* `<?php if (function_exists(runners_log_graph)) echo runners_log_graph(); ?>`
	* `<?php if (function_exists(runners_log_graphmini_distance)) echo runners_log_graphmini_distance(); ?>`
	* `<?php if (function_exists(runners_log_graphmini_hours)) echo runners_log_graphmini_hours(); ?>`
	* `<?php if (function_exists(runners_log_graphmini_calories)) echo runners_log_graphmini_calories(); ?>`
	* `<?php if (function_exists(runners_log_pie_distance)) echo runners_log_pie_distance(); ?>`
	* `<?php if (function_exists(runners_log_pie_hours)) echo runners_log_pie_hours(); ?>`
	* `<?php if (function_exists(runners_log_pie_calories)) echo runners_log_pie_calories(); ?>`
	* `<?php if (function_exists(runners_log_bar_distance)) echo runners_log_bar_distance(); ?>`
	* `<?php if (function_exists(runners_log_bar_hours)) echo runners_log_bar_hours(); ?>`
	* `<?php if (function_exists(runners_log_bar_calories)) echo runners_log_bar_calories(); ?>`
	* `<?php if (function_exists(runners_log_garminmap)) echo runners_log_bar_garminmap(); ?>`
	* `<?php if (function_exists(runners_log_weather)) echo runners_log_weather(); ?>`
	* `<?php if (function_exists(runners_log_weather_footer)) echo runners_log_weather_footer(); ?>`
7. You only want to have the chart and stats to show up in the category where the sports data is then see FAQ.
8. Runners Log support the following short codes when you want to use Google Chart
	* `[runners_log_gchart type="pie" format="d" year="2010" month="May" color="224499" width="600" height="300"]`

    *Type: bar, graph, pie, 3dpie
    *Format: d="distance", ds="distance sum", ts="time sum",  cs="calories sum", p="pulse average"
    *Year: 2009, 2010, 2011
    *Month: Jan, Feb, Marts, April, May, June, July, Aug, Sep, Oct, Nov, Dec
    *Color: Is the color scheme used eg: "224499" for the html color #224499
    *Width: The width of the chart: Default: 475 pixel
    *Height: The height of the chart: Default: 250 pixel

== Frequently Asked Questions ==

= How to help translating this plugin? =
We recommand you install the Codestyling Localization plugin and use that to generate a .po file specific for your language. Read more here: http://www.code-styling.de/english/development/wordpress-plugin-codestyling-localization-en

Feel free to share the language by sending me an email.

= The supported short codes for using pChart =
	`[runners_log]
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
	[runners_log_garminmap]
    	[runners_log_weather]
    	[runners_log_weather_footer]`

= Howto use Google Chart =
Eg: `[runners_log_gchart type="pie" format="d" year="2010" month="May" color="224499" width="600" height="300"]`

    *Type: bar, graph, pie, 3dpie
    *Format: d="distance", ds="distance sum", ts="time sum",  cs="calories sum", p="pulse average"
    *Year: 2009, 2010, 2011
    *Month: Jan, Feb, Marts, April, May, June, July, Aug, Sep, Oct, Nov, Dec
    *Color: Is the color scheme used eg: "224499" for the html color #224499
    *Width: The width of the chart: Default: 475 pixel
    *Height: The height of the chart: Default: 250 pixel
	
= Howto use [runners_log] =
This tag support: 

Year: could be set to 2010 or 2009 or what you want
Month: could be "february", "FeBRUary" or just "feb". You need to specify at least the first 3 chars of the month name.
Type: could be bar, graph, pie or mini

By using `[runners_log]` the default setting is year="2010" type="bar" month="0" (which is the same as all months in the choosen year)

Other exambles of using this tag could be: `[runners_log type='pie' month='marts' year='2009']` gives you a Pie chart of your tracked distances in Marts in 2009
or `[runners_log type='mini']`gives you a mini-graph with distances for the whole 2010

= Howto use [runners_log_basic] =
To have the basic information about your posted course like:

    `* Meters: 8500
    * Time: 00:49:59
    * Km/hour: 10.2
    * Min/km: 05:52 minutes
    * Puls average: 172 bpmis 86% of Max HR and 80% of HRR
    * Calories: 654 C
    * Garmin Connect Link: http://connect.garmin.com/activity/id
    * Km in 2009: 693.7 km based on 122 runs with an avg of 5.69 km
    * Km in 2010: 100.8 km based on 12 runs with an avg of 8.4 km
	* ~embed garmin connect map~`

Use this short code `[runners_log_basic]` in a post or page. 

Alternativly place `<?php if (function_exists(runners_log_basic)) echo runners_log_basic(); ?>` in your template.

= Howto use [runners_log_weather] =
If you have enabled weather support the weather is stored while you add your post. To paste in the weather use  `[runners_log_weather]` to have something like:
 
    `* Temperature : 3
    * Humidity : 100
    * Windchill : 3
    * Description : Mostly Cloudy`
    
Alternativly place `<?php if (function_exists(runners_log_weather)) echo runners_log_weather(); ?>` in your template.

To have your weather data in the footer of the page or post use: `[runners_log_weather_footer]`.

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
If you have a very visited blog and using this plugin it could cause high server load if you generate graphs using pChart. We then recommand using Google Chart. See faq for how to use.

= Gear Manager =
I would like to thanks Thomas Genin for his plugin WP-Task-Manager which the Gear Manager is based on.

Plugin URI: http://thomas.lepetitmonde.net/en/index.php/projects/wordpress-task-manager
Description: Integrate in Wordpress, a small task manager system. The plugin is very young, so you should be kind with him.
Author: Thomas Genin
Author URI: http://thomas.lepetitmonde.net/
Version: 1.2

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
19. Converter Toolbox
20. Embed Garmin Connect Map in [runners_log_basic] and/or an example of using [runners_log_garminmap]
21. Weather Settings
22. Gear Manager
23. Add new Gear to the Gear Manager
24. Pulsavg for a whole year using Google Chart. (Type: bar)
25. Pulsavg for a whole year using Google Chart. (Type: graph)
26. Pulsavg a given month using Google Chart. (Type: 3dpie)
27. Pulsavg a given month using Google Chart. (Type: pie)
28. Google Chart

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

= 1.6.7 =
* FL - Fixed bug reported by klojo and fixed by klojo. You are now able to use [runners_log_basic] twice.
* FL - Fixed some typos
* FL - Added Coverter Toolbox including: Calculate Speed, Calculate Race Time, Calculate Distance, Convert speed to min per distance

= 1.6.8 =
* FL - Fixed missing include of converter toolbox file
* ML - Stats page throws error with zero data http://wordpress.org/support/topic/367176?replies=3

= 1.8.0 =
* FL - Wordpress 3.0 validated
* FL - runnerslog_admin.php spell checking and more
* FL - Includes/runnerslog_stats_graphs.php minor spelling fixes
* FL - Includes/runnerslog_training_zones.php fixed Heart Rate Training Zones (Elite)
* FL - Includes/runnerslog_v02mac.php minor style changes
* FL - Includes/runnerslog_vdot_race_time.php minor spelling fixes
* FL - Includes/runnerslog_vdot_training_pace.php minor spelling fixes
* FL - Includes/runnerslog_body_mass_index.php minor spelling fixes and style changes
* FL - Includes/runnerslog_weight_change_effect.php minor style changes
* FL - Includes/runnerslog_converter_toolbox fixed to remember data i all fields

= 1.8.1 =
* FL - Added settings for [runners_log_basic] Now you can set what to show
* FL - Pulse spelling error in [runners_log_basic]
* FL - Added embed Garmin Connect map to [runners_log_basic] and you can enable or disable it
* FL - Added a new shortcode [runners_log_garminmap] which let you insert a embed map of you route. The map is based on the path in "Garmin Connect Link"

= 1.8.2 =
* FL - Added more options to the plugin control panel like a link to setting, FAQ, Support and a link to Share where you use this plugin
* FL - Added Km at all to [runners_log_basic]
* FL - Minor changes to runnerslog_metabox.php

= 1.8.5 =
* FL - Fixing minor bug in the bar-charts in runnerslog.php
* FL - By request by TheRealEyeless http://wordpress.org/support/topic/347464/page/2?replies=47 added a whole new tag [runners_log year="2010" month="May" type="pie"]. See FAQ for howto use it.

= 2.0.2 =
* FL - Added a Gear List Manager Based on Thomas Genin WP-Task-Manager v.1.2.
* TR - Weather support
* TR - [runners_log_weather] Using the meta-style like [runners_log_basic]
* FL - [runners_log_weather_footer] - to put the weather data in the footer of the post or page. Thanks to Weather Postin' Plugin By bigdawggi

= 2.0.5 =
* FL - Added 2011 support

= 2.2 =
* FL - Added Google Chart suppport. See Faq and Screenshots

= 2.2.1 =
* FL - Google Chart: Better color palettes
* FL - Google Chart: Markers for the type bar
* FL - Serious bug fix thanks to salathe @ #php @ freenode: http://wordpress.org/support/topic/plugin-runners-log-shots-screen-of-death-after-plugin-upgrade

= 2.2.2 =
* FL - Added a new option "Cadence"

= 2.2.5 =
* FL - Crash tested with Wordpress 3.1

= 2.3.0 =
* TR - Multilanguage support
* TR - German Language files
* FL - Danish Language files
* FL - English language fixes

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

= 1.6.7 =
The Converter Toolbox release. Februar 21th 2010.

= 1.6.8 =
Marts 3rd 2010 

= 1.8.1 =
June 27th 2010 

= 1.8.2 =
June 30th 2010

= 1.8.5 =
July 20th 2010

= 2.0.2 =
November 16th 2010

= 2.0.5 =
January 8th 2011

= 2.2 =
February 9th 2011

= 2.2.1 =
February 11th 2011

= 2.2.5 =
Marts 27th

= 2.3.0 =
Summer 2011

== To Do ==
	* enable cache (done - not an issue when using google chart)
	* more graphs (pulse avg?) (done with google chart)
	* gear list (started)
	* weather support (done)
	* multi language support (done)
    	* multi user support
