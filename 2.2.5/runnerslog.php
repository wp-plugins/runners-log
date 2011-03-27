<?php
/*
Plugin Name: Runners Log
Plugin URI: http://wordpress.org/extend/plugins/runners-log/
Description: This plugin let you convert your blog into a training log and let you track your activities. You get advance statistics and running related calculators. See screenshots.
Author: Frederik Liljefred
Author URI: http://www.liljefred.dk
Contributors: frold, TheRealEyeless, jaredatch, michaellasmanis
Version: 2.2.5
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Requires WordPress 2.7 or later.

/*  
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Version check */
global $wp_version;	

$exit_msg='Runners Log requires WordPress 2.7 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version,"2.7","<")) {
	exit ($exit_msg);
}

include('runnerslog_tag.php');
include('runnerslog_gchart.php');
include('runnerslog_gear.php');

/* Get the plugin-base-url for use of the gear-list */
$gear_plugIn_base_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?page=runners-log-gear';

// Do this when user activates the plugin (Update Script)
register_activation_hook(__FILE__, 'runners_log_update');
register_activation_hook(__FILE__, 'wp_gear_manager_install');

// Update the old custom fields to match the new one used from version 1.5.0
	function runners_log_update()
	{ 
		global $wpdb;
		//Meters
		$sql = $wpdb->get_results("
			UPDATE $wpdb->postmeta
			SET $wpdb->postmeta.meta_key = '_rl_distance_value'
			WHERE $wpdb->postmeta.meta_key = 'Meters'");	
		//Time
		$sql = $wpdb->get_results("
			UPDATE $wpdb->postmeta
			SET $wpdb->postmeta.meta_key = '_rl_time_value'
			WHERE $wpdb->postmeta.meta_key = 'Time'	");	
		//GarminConnectLink
		$sql = $wpdb->get_results("
			UPDATE $wpdb->postmeta
			SET $wpdb->postmeta.meta_key = '_rl_garminconnectlink_value'
			WHERE $wpdb->postmeta.meta_key = 'GarminConnectLink'");	
		//Pulsavg
		$sql = $wpdb->get_results("
			UPDATE $wpdb->postmeta
			SET $wpdb->postmeta.meta_key = '_rl_pulsavg_value'
			WHERE $wpdb->postmeta.meta_key = 'Pulsavg'");
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
}

//Add a new table in the DB for the gear manager
	function wp_gear_manager_install()
	{
		global $wpdb;
	    $table = $wpdb->prefix."gear";
	    $structure = "CREATE TABLE $table (
			`gear_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`gear_brand` VARCHAR( 100 ) NOT NULL ,
			`gear_name` VARCHAR( 100 ) NOT NULL ,
			`gear_price` VARCHAR( 100 ) NOT NULL ,
			`gear_distance` VARCHAR( 100 ) NOT NULL ,
			`gear_distance_calc` VARCHAR( 100 ) NOT NULL ,
			`gear_desc` TEXT NOT NULL ,
			`gear_dateTo` DATE NOT NULL ,
			`gear_isDone` TINYINT NOT NULL ,
			`gear_image` VARCHAR( 255 ) NOT NULL 
			) ENGINE = MYISAM";
	    $wpdb->query( $structure );
	    update_option( OPTION_DATE_FORMAT, 'd/m/Y' ); //Standart date format for the use of the gear manager
	}

// Add a settings option in wp-admin/plugins.php
	function rl_filter_plugin_actions($links) 
	{
		$new_links = array();
		$new_links[] = '<a href="admin.php?page=runners-log">' . __('Settings', 'runners-log') . '</a>';
		return array_merge($new_links, $links);
	}
	add_action('plugin_action_links_' . plugin_basename(__FILE__), 'rl_filter_plugin_actions');

// Add FAQ and support information and a little more in wp-admin/plugins.php
	function rl_filter_plugin_links($links, $file)
	{
		if ( $file == plugin_basename(__FILE__) )
		{
			$links[] = '<a href="http://wordpress.org/extend/plugins/runners-log/faq/">' . __('FAQ', 'runners-log') . '</a>';
			$links[] = '<a href="http://wordpress.org/tags/runners-log?forum_id=10">' . __('Support', 'runners-log') . '</a>';
			$links[] = '<a href="http://wordpress.org/support/topic/358411">' . __('Share where you use it', 'runners-log') . '</a>';
		}
	return $links;
	}
	add_filter('plugin_row_meta', 'rl_filter_plugin_links', 10, 2);

/* Let us create the functions */
    //[runners_log_basic]
	function runners_log_basic() 
	{
		global $wpdb, $post; 
		$hms = get_post_meta($post->ID, "_rl_time_value", $single = true); // Get the running time
		$distance = get_post_meta($post->ID, "_rl_distance_value", $single = true); // Get the distance
		$url = get_post_meta($post->ID, "_rl_garminconnectlink_value", $single = true); // Get the Garmin Connect Link
		$pulsavg = get_post_meta($post->ID, "_rl_pulsavg_value", $single = true); // Get pulsavg.
		$calories = get_post_meta($post->ID, "_rl_calories_value", $single = true); // Get calories.
		$cadence = get_post_meta($post->ID, "_rl_cadence_value", $single = true); // Get cadence.

		// Get [runners_log_basic] settings
		$show_distance = get_option('runnerslog_show_distance');
		$show_time = get_option('runnerslog_show_time');
		$show_speed = get_option('runnerslog_show_speed');
		$show_speedperdistance = get_option('runnerslog_show_speedperdistance');
		$show_pulse = get_option('runnerslog_show_pulse');
		$show_calories = get_option('runnerslog_show_calories');
		$show_cadence = get_option('runnerslog_show_cadence');
		$show_garminconnect = get_option('runnerslog_show_garminconnect');
		$show_distance2009 = get_option('runnerslog_show_distance2009');
		$show_distance2010 = get_option('runnerslog_show_distance2010');
		$show_distance2011 = get_option('runnerslog_show_distance2011');
		$show_distance_sum = get_option('runnerslog_show_distance_sum');
		$show_garminmap = get_option('runnerslog_show_garminmap');
	
		// We want to calculate the %of Max HR and the %of HRR
		$hrrest = get_option('runnerslog_hrrest');
		$hrmax = get_option('runnerslog_hrmax');
		if ($hrmax && $hrrest) 
		{
			$procofmaxhr = ROUND(($pulsavg/$hrmax)*100,0); 	//Calculate %of Max HR
			$procofhrr = ROUND((($pulsavg-$hrrest)/($hrmax-$hrrest)*100),0);	//Calculate %of Heart Rate Reserve
		}
	
		$seconds = hms2sec($hms); // Use the hms2sec function on $hms (the running time)
	
		$distancetype = get_option('runnerslog_distancetype'); // Let us get the distancetype for further calculations
		
		if ($distance) // Calculate the avg running speed per hour
		{
			$km_per_hour = Round((($distance/1000) / ($seconds/3600)),2); //First we calculate it per km and round it to 2 decimals
			$miles_per_hour = Round((($distance) / ($seconds/3600)),2); //Here we calculate it per miles and round it to 2 decimals
		}
		
		if ($distance) // Calculate number of minutes per km
		{
			$min_per_km= ($seconds) / ($distance/1000);
			$minutes = floor($min_per_km/60);
			$secondsleft = $min_per_km%60;
			if($minutes<10)
				$minutes = "0" . $minutes;
			if($secondsleft<10)
				$secondsleft = "0" . $secondsleft;
		}		
	
		if ($distance) // Calculate number of minutes per miles
		{ 
			$min_per_miles= ($seconds) / ($distance);
			$minutes_miles = floor($min_per_miles/60);
			$secondsleft_miles = $min_per_miles%60;
			if($minutes_miles<10)
				$minutes_miles = "0" . $minutes_miles;
			if($secondsleft_miles<10)
				$secondsleft_miles = "0" . $secondsleft_miles;
		}

 /* 2 0 0 9 */
		// Connect to DB and calculate the sum of distance runned in 2009
		$distance_sum_2009 = $wpdb->get_var($wpdb->prepare("
			SELECT SUM($wpdb->postmeta.meta_value)
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'	
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2009'"));
		$km_sum_2009 = round($distance_sum_2009/1000, 1); // Convert distance to km when the user use "meters"

		// Connect to DB and calculate the number of runs in 2009
		$number_of_runs_2009 = $wpdb->get_var($wpdb->prepare("
			SELECT COUNT($wpdb->postmeta.meta_value)
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'	
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2009'"));
	
		if ($distance_sum_2009) // Calculate the avg per run in 2009
		{
			$avg_km_per_run_2009 = ROUND(($distance_sum_2009/1000) / $number_of_runs_2009, 2);
			$avg_miles_per_run_2009 = ROUND(($distance_sum_2009) / $number_of_runs_2009, 2);
		}

 /* 2 0 1 0 */	
		// Connect to DB and calculate the sum of distance runned in 2010
		$distance_sum_2010 = $wpdb->get_var($wpdb->prepare("
			SELECT SUM($wpdb->postmeta.meta_value), COUNT($wpdb->postmeta.meta_value) as numberofrun2010
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2010'"));
		$km_sum_2010 = round($distance_sum_2010/1000, 1); // Convert distance to km when the user use "meters"
	
		//Connect to DB and calculate the number of runs in 2010
		$number_of_runs_2010 = $wpdb->get_var($wpdb->prepare("
			SELECT COUNT($wpdb->postmeta.meta_value)
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'	
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2010'"));
	
		if ( $distance_sum_2010 ) // Calculate the avg per run in 2010
		{
			$avg_km_per_run_2010 = ROUND(($distance_sum_2010/1000) / $number_of_runs_2010, 2);
			$avg_miles_per_run_2010 = ROUND(($distance_sum_2010) / $number_of_runs_2010, 2);
		}
        
 /* 2 0 1 1 */	
		// Connect to DB and calculate the sum of distance runned in 2011
		$distance_sum_2011 = $wpdb->get_var($wpdb->prepare("
			SELECT SUM($wpdb->postmeta.meta_value), COUNT($wpdb->postmeta.meta_value) as numberofrun2011
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2011'"));
		$km_sum_2011 = round($distance_sum_2011/1000, 1); // Convert distance to km when the user use "meters"
	
		//Connect to DB and calculate the number of runs in 2011
		$number_of_runs_2011 = $wpdb->get_var($wpdb->prepare("
			SELECT COUNT($wpdb->postmeta.meta_value)
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'	
			AND $wpdb->postmeta.post_id=$wpdb->posts.id  
			AND year($wpdb->posts.post_date)='2011'"));
	
		if ( $distance_sum_2011 ) // Calculate the avg per run in 2011
		{
			$avg_km_per_run_2011 = ROUND(($distance_sum_2011/1000) / $number_of_runs_2011, 2);
			$avg_miles_per_run_2011 = ROUND(($distance_sum_2011) / $number_of_runs_2011, 2);
		}
	
 /* S U M  A T  A L L */	
		// Connect to DB and calculate the sum of distance runned at all
		$distance_sum = $wpdb->get_var($wpdb->prepare("
			SELECT SUM($wpdb->postmeta.meta_value), COUNT($wpdb->postmeta.meta_value) as numberofrun
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id=$wpdb->posts.id"));
		$km_sum = round($distance_sum/1000, 1); // Convert distance to km when the user use "meters"
	
		//Connect to DB and calculate the number of runs at all
		$number_of_runs = $wpdb->get_var($wpdb->prepare("
			SELECT COUNT($wpdb->postmeta.meta_value)
			FROM $wpdb->postmeta, $wpdb->posts 
			WHERE $wpdb->postmeta.meta_key='_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'	
			AND $wpdb->postmeta.post_id=$wpdb->posts.id"));
	
		if ( $distance_sum ) // Calculate the avg per run at all
		{
			$avg_km_per_run = ROUND(($distance_sum/1000) / $number_of_runs, 2);
			$avg_miles_per_run = ROUND(($distance_sum) / $number_of_runs, 2);
		}
	
	echo "<ul class='post-meta'>"; //Print it all

	if ($show_distance == '1') // Distance
	{
		if ( $distancetype == meters ) 
		{
			if ($distance > '0') //..let us print the distance in meters but only if distance is greather then 0...
			{
				echo "<li><span class='post-meta-key'>Meters:</span> $distance</li>";
			}
		} else {
			if ($distance > '0') //..else it must be miles and therefore print the distance in miles but only if distance is greather then 0...
			{
				echo "<li><span class='post-meta-key'>Miles:</span> $distance</li>";
			}
		}
	}
	if ($show_time == '1') // Time
	{
		if ($hms) 
		{
			echo "<li><span class='post-meta-key'>Time:</span> $hms</li>";
		}
	}
	if ($show_speed == '1') // Distance per hours
	{
		if ( $distancetype == meters ) //..let us get the speed in km/hours. (But only if km/hour is greather then 0...)
		{
			if ( $km_per_hour > '0') 
			{
				echo "<li><span class='post-meta-key'>Km/hour:</span> $km_per_hour</li>";
			}
		} else {
			if ( $miles_per_hour > '0') //..else it must be miles/hours. (But only if miles/hour is greather then 0...)
			{
				echo "<li><span class='post-meta-key'>Miles/hour:</span> $miles_per_hour</li>";
			}
		}
	}
	if ($show_speedperdistance == '1') // Min per distance
	{
		if ( $distancetype == 'meters') //..let us get the speed in min per km... (But only if minutes is greather then 0...)
		{
			if ($minutes > '0') 
			{
				echo "<li><span class='post-meta-key'>Min/km:</span> $minutes:$secondsleft minutes</li>";
			}
		} else {
			if ($minutes_miles > '0') //..else it must be min per miles. (But only if minutes_miles is greather then 0...)
			{
				echo "<li><span class='post-meta-key'>Min/miles:</span> $minutes_miles:$secondsleft_miles minutes</li>";
			}
		}
	}
	if ($show_pulse == '1') // Pulsavg
	{
		if ($pulsavg) 
		{
			echo "<li><span class='post-meta-key'>Pulse average:</span> $pulsavg bpm"; 
			if ($procofmaxhr && $procofhrr) 
			{ 
				echo " is $procofmaxhr% of Max HR and $procofhrr% of HRR"; 
			} 
			echo "</li>";
		}
	}
	if ($show_calories == '1') // Calories
	{	
		if ($calories) 
		{
			echo "<li><span class='post-meta-key'>Calories:</span> $calories C</li>";
		}
	}
	if ($show_cadence == '1') // Cadence
	{	
		if ($cadence) 
		{
			echo "<li><span class='post-meta-key'>Cadence:</span> $cadence</li>";
		}
	}
	if ($show_garminconnect == '1') //Garmin Connect Link
	{
		if ($url) 
		{
			echo "<li><span class='post-meta-key'>Garmin Link:</span> <a href='$url' target='_blank'>$url</a></li>";
		}
	}
	if ($show_distance2009 == '1') // Totals 2009
	{
		if ($distancetype == 'meters') 
		{
			if ($number_of_runs_2009 == '1')
			{
				echo "<li><span class='post-meta-key'>2009:</span> <strong>$km_sum_2009</strong> km based on <strong>$number_of_runs_2009</strong> run with an avg of <strong>$avg_km_per_run_2009</strong> km</li>";
			}
			if ($number_of_runs_2009 > '1') 
			{
				echo "<li><span class='post-meta-key'>2009:</span> <strong>$km_sum_2009</strong> km based on <strong>$number_of_runs_2009</strong> runs with an avg of <strong>$avg_km_per_run_2009</strong> km</li>";
			}
		} else {
			if ($number_of_runs_2009 == '1') 
			{
				echo "<li><span class='post-meta-key'>2009:</span> <strong>$distance_sum_2009</strong> miles based on <strong>$number_of_runs_2009</strong> run with an avg of <strong>$avg_miles_per_run_2009</strong> mi</li>";
			} 
			if ($number_of_runs_2009 > '1') 
			{
				echo "<li><span class='post-meta-key'>2009:</span> <strong>$distance_sum_2009</strong> miles based on <strong>$number_of_runs_2009</strong> runs with an avg of <strong>$avg_miles_per_run_2009</strong> mi</li>";
			}
		}
	}
	if ($show_distance2010 == '1') // Totals 2010
	{	
		if ($distancetype == 'meters') 
		{
			if ($number_of_runs_2010 == '1') 
			{
				echo "<li><span class='post-meta-key'>2010:</span> <strong>$km_sum_2010</strong> km based on <strong>$number_of_runs_2010</strong> run with an avg of <strong>$avg_km_per_run_2010</strong> km</li>";
			} 
			if ($number_of_runs_2010 > '1') 
			{
				echo "<li><span class='post-meta-key'>2010:</span> <strong>$km_sum_2010</strong> km based on <strong>$number_of_runs_2010</strong> runs with an avg of <strong>$avg_km_per_run_2010</strong> km</li>";
			}
		} else {
			if ($number_of_runs_2010 == '1') 
			{
				echo "<li><span class='post-meta-key'>2010:</span> <strong>$distance_sum_2010</strong> miles based on <strong>$number_of_runs_2010</strong> run with an avg of <strong>$avg_miles_per_run_2010</strong> mi</li>";
			} 
			if ($number_of_runs_2010 > '1') 
			{		
				echo "<li><span class='post-meta-key'>2010:</span> <strong>$distance_sum_2010</strong> miles based on <strong>$number_of_runs_2010</strong> runs with an avg of <strong>$avg_miles_per_run_2010</strong> mi</li>";
			}
		}
	}
	if ($show_distance2011 == '1') // Totals 2011
	{	
		if ($distancetype == 'meters') 
		{
			if ($number_of_runs_2011 == '1') 
			{
				echo "<li><span class='post-meta-key'>2011:</span> <strong>$km_sum_2011</strong> km based on <strong>$number_of_runs_2011</strong> run with an avg of <strong>$avg_km_per_run_2011</strong> km</li>";
			} 
			if ($number_of_runs_2011 > '1') 
			{
				echo "<li><span class='post-meta-key'>2011:</span> <strong>$km_sum_2011</strong> km based on <strong>$number_of_runs_2011</strong> runs with an avg of <strong>$avg_km_per_run_2011</strong> km</li>";
			}
		} else {
			if ($number_of_runs_2011 == '1') 
			{
				echo "<li><span class='post-meta-key'>2011:</span> <strong>$distance_sum_2011</strong> miles based on <strong>$number_of_runs_2011</strong> run with an avg of <strong>$avg_miles_per_run_2011</strong> mi</li>";
			} 
			if ($number_of_runs_2011 > '1') 
			{		
				echo "<li><span class='post-meta-key'>2011:</span> <strong>$distance_sum_2011</strong> miles based on <strong>$number_of_runs_2011</strong> runs with an avg of <strong>$avg_miles_per_run_2011</strong> mi</li>";
			}
		}
	}
	if ($show_distance_sum == '1') // Total at all
	{	
		if ($distancetype == meters) 
		{
			if ($number_of_runs == '1') 
			{
				echo "<li><span class='post-meta-key'>At all:</span> <strong>$km_sum</strong> km based on <strong>$number_of_runs</strong> run with an avg of <strong>$avg_km_per_run</strong> km</li>";
			} 
			if ($number_of_runs > '1') 
			{
				echo "<li><span class='post-meta-key'>At all:</span> <strong>$km_sum</strong> km based on <strong>$number_of_runs</strong> runs with an avg of <strong>$avg_km_per_run</strong> km</li>";
			}
		} else {
			if ($number_of_runs == '1') 
			{
				echo "<li><span class='post-meta-key'>At all:</span> <strong>$distance_sum</strong> miles based on <strong>$number_of_runs</strong> run with an avg of <strong>$avg_miles_per_run</strong> mi</li>";
			} 
			if ($number_of_runs_2011 > '1') 
			{		
				echo "<li><span class='post-meta-key'>At all:</span> <strong>$distance_sum</strong> miles based on <strong>$number_of_runs</strong> runs with an avg of <strong>$avg_miles_per_run</strong> mi</li>";
			}
		}
	}
	if ($show_garminmap == '1') // Insert embed Garmin Connnect Map based on the used Garmin Connect Link
	{
		if ($url) 
		{
			$mapurl = substr($url, strrpos($url, '/') + 1);
			echo "<iframe width='465' height='548' frameborder='0' src='http://connect.garmin.com:80/activity/embed/".$mapurl."'></iframe>";
		}
	}
	echo "</ul>"; // End function runners_log_basic()
}
add_shortcode('runners_log_basic', 'runners_log_basic');

/* Show weather information */
    //[runners_log_weather]
	function runners_log_weather() 
	{
		global $wpdb, $post;
		
		// Retrieve the weather settings
		$show_weather_temperature = get_option('runnerslog_weather_temperature'); // get temperature
		$show_weather_windchill = get_option('runnerslog_weather_windchill'); // get windchill
		$show_weather_humidity = get_option('runnerslog_weather_humidity'); // get humidity
		$show_weather_description = get_option('runnerslog_weather_description'); //get textual weather description
	
	
	echo "<ul class='post-meta'>"; //use post-meta for css formation

	//check every option whether its selected and show it if it's selected and a value is available
	if (get_option('runnerslog_weather_temperature') == '1')
	{
		$temperature = get_post_meta($post->ID, "_rl_weather_temperature_value", $single = true);
		if ( $temperature > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li><span class='post-meta-key'>Temperature :</span> $temperature</li>";
			}
	}
	if (get_option('runnerslog_weather_humidity') == '1')
	{
		$humidity = get_post_meta($post->ID, "_rl_weather_humidity_value", $single = true);
		if ( $humidity > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li><span class='post-meta-key'>Humidity :</span> $humidity</li>";
			}
	}
	if (get_option('runnerslog_weather_windchill') == '1')
	{
		$windchill = get_post_meta($post->ID, "_rl_weather_windchill_value", $single = true);
		if ( $windchill > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li><span class='post-meta-key'>Windchill :</span> $windchill</li>";
			}
	}
	if (get_option('runnerslog_weather_description') == '1')
	{
		$description = get_post_meta($post->ID, "_rl_weather_description_value", $single = true);
		if ( strlen($description) > 0 ) //should avoid displaying if not set for this post 
		{
				echo "<li><span class='post-meta-key'>Description :</span> $description</li>";
			}
	}
	echo "</ul>";
} // End function runners_log_weather()
add_shortcode('runners_log_weather', 'runners_log_weather');

    //[runners_log_weather_footer]
	function runners_log_weather_footer() 
	{
		global $wpdb, $post;
		
		// Retrieve the weather settings
		$show_weather_temperature = get_option('runnerslog_weather_temperature'); // get temperature
		$show_weather_windchill = get_option('runnerslog_weather_windchill'); // get windchill
		$show_weather_humidity = get_option('runnerslog_weather_humidity'); // get humidity
		$show_weather_description = get_option('runnerslog_weather_description'); //get textual weather description
	
	
	echo "<div style='color:#999999;margin-bottom:5px;font-size:10px;'>"; //use div-style for css formation
    echo "<p style='margin-bottom: 2px;'>-- Weather on Post Time --";
    echo "<ul style='display:inline;'>";

	//check every option whether its selected and show it if it's selected and a value is available
	if (get_option('runnerslog_weather_temperature') == '1')
	{
		$temperature = get_post_meta($post->ID, "_rl_weather_temperature_value", $single = true);
		if ( $temperature > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li style='padding:0px 3px;display:inline;'>Temperature : $temperature</li>";
			}
	}
	if (get_option('runnerslog_weather_humidity') == '1')
	{
		$humidity = get_post_meta($post->ID, "_rl_weather_humidity_value", $single = true);
		if ( $humidity > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li style='padding:0px 3px;display:inline;'>Humidity : $humidity</li>";
			}
	}
	if (get_option('runnerslog_weather_windchill') == '1')
	{
		$windchill = get_post_meta($post->ID, "_rl_weather_windchill_value", $single = true);
		if ( $windchill > '0' ) //should avoid displaying if not set for this post 
		{
				echo "<li style='padding:0px 3px;display:inline;'>Windchill : $windchill</li>";
			}
	}
	if (get_option('runnerslog_weather_description') == '1')
	{
		$description = get_post_meta($post->ID, "_rl_weather_description_value", $single = true);
		if ( strlen($description) > 0 ) //should avoid displaying if not set for this post 
		{
				echo "<li style='padding:0px 3px;display:inline;'>Description : $description</li>";
			}
	}
	echo "</ul>";
    echo "</p></div>";
} // End function runners_log_weather()
add_shortcode('runners_log_weather_footer', 'runners_log_weather_footer');

  //[runners_log_graph]
  function runners_log_graph() {
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class'); 	// Let us include the classes for the graph tool
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');	// Graph script by:  http://pchart.sourceforge.net/
 
	global $wpdb; // Make $wpdb global

	// Let us get the distancetype for further calculations
	$distancetype = get_option('runnerslog_distancetype');	
	
	// Connect to DB and get the distance sum either as km-->runkm or miles-->runmiles and list it per month
	$distance_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm,  SUM( $wpdb->postmeta.meta_value ) AS runmiles
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
	// Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) )/3600) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_time_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
	// Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);
	
	// Dataset definition 
	$DataSet = new pData;

	foreach ($distance_per_month as $row) {
		if ($distancetype == meters) {
			// The Y-axis is "Km per month" when meters is the choice
			$DataSet->AddPoint($row->runkm,"Serie1");
		} else {
			// The Y-axis is "Miles per month"
			$DataSet->AddPoint($row->runmiles,"Serie1");
		}
			// The X-axis is "The months" and we transform 1,2,3.. to Jan, Feb, March... using $month2str
			$DataSet->AddPoint($month2str[$row->runmonth],"Serie3");
	}
 	
	foreach ($hours_per_month as $row) {
		// Y-axis to the right "Hours per month"
		$DataSet->AddPoint($row->runhours,"Serie2");
	}
	$DataSet->AddSerie("Serie1"); 
	$DataSet->SetAbsciseLabelSerie("Serie3");
	// Here we set the titel of the Data Serie 1
	if ($distancetype == meters) {
		$DataSet->SetSerieName("Km per month","Serie1");
	} else {
		$DataSet->SetSerieName("Miles per month","Serie1");
	}
	// Here we set the titel of the Data Serie 2
	$DataSet->SetSerieName("Hours per month","Serie2");

	// Initialise the graph
	$Test = new pChart(475,230);
	$Test->drawGraphAreaGradient(90,90,90,90,TARGET_BACKGROUND);

	// Prepare the graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(60,40,428,190);

	// Initialise graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);

	// Draw the Distance per month graph
	if ($distancetype == meters) {
		$DataSet->SetYAxisName("Km per month");
	} else {
		$DataSet->SetYAxisName("Miles per month");
	}	
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,0);
	$Test->drawGraphAreaGradient(40,40,40,-50);
	$Test->drawGrid(4,TRUE,230,230,230,10);
	$Test->setShadowProperties(3,3,0,0,0,30,4);
	$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
	$Test->clearShadow();
	$Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);
	$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

/*
	//Uncomment if you want to add labels to your "Distance per month" graph 	
	//Write values on Serie1
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
*/

	// Clear the scale
	$Test->clearScale();

	// Draw the 2nd graph the "Hours per month" graph
	$DataSet->RemoveSerie("Serie1");
	$DataSet->AddSerie("Serie2");
	$DataSet->SetYAxisName("Hours per month");
	$Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,0);
	$Test->drawGrid(4,TRUE,230,230,230,10);
	$Test->setShadowProperties(3,3,0,0,0,30,4);
	$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
	$Test->clearShadow();
	$Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);
	$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

/* 
	// Uncomment if you want to add labels to your "Hours per month" graph 	
	// Write values on Serie2
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");
*/

	// Write the legend (box less)
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawLegend(360,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,255,255,255,FALSE);

	// Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/MankSans.ttf',18);
	$Test->setShadowProperties(1,1,0,0,0);
	$Test->drawTitle(0,0,"Distance and Hours Graph",255,255,255,472,30,TRUE);
	$Test->clearShadow();
	
	// Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(251,227,"Runners Log a Wordpress plugin by Frederik Liljefred",255,255,255); 

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph.png', __FILE__ ) . '" alt="Training Graph" />';

// End function runners_log_graph()
}
add_shortcode('runners_log_graph', 'runners_log_graph');

  //[runners_log_graphmini_distance]
  function runners_log_graphmini_distance() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');
	
	// Make $wpdb global
	global $wpdb;
	
	// Let us get the distancetype for further calculations
	$distancetype = get_option('runnerslog_distancetype');	

	// Connect to DB and get the distance sum either as km-->runkm or miles-->runmiles and list it per month
	$distance_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm,  SUM( $wpdb->postmeta.meta_value ) AS runmiles
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
	// is there something to do?
	if (sizeof($distance_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	// Dataset definition 
	$DataSet = new pData;

	foreach ($distance_per_month as $row) {
		if ($distancetype == meters) {
			// The Y-axis is "Km per month" when meters is the choice
			$DataSet->AddPoint($row->runkm,"Serie1");
		} else {
			// The Y-axis is "Miles per month"
			$DataSet->AddPoint($row->runmiles,"Serie1");
		}
	}	
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie();

	// Initialise the graph
	$Test = new pChart(100,30);
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawFilledRoundedRectangle(2,2,98,28,2,230,230,230);
	$Test->setGraphArea(5,5,95,25);
	$Test->drawGraphArea(255,255,255);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   

	// Draw the line graph
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-mini-distance.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-mini-distance.png', __FILE__ ) . '" alt="Training Graph Mini distance" />';

//End function runners_log_graphmini_distance()
}
add_shortcode('runners_log_graphmini_distance', 'runners_log_graphmini_distance');

  //[runners_log_graphmini_hours]
  function runners_log_graphmini_hours() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	// Make $wpdb global
	global $wpdb;

	// Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) )/3600) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_time_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
	// is there something to do?
	if (sizeof($hours_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	// Dataset definition 
	$DataSet = new pData;
 	foreach ($hours_per_month as $row) {
		//The Y-axis "Minutes per month"
		$DataSet->AddPoint($row->runhours,"Serie1");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie();

	// Initialise the graph
	$Test = new pChart(100,30);
	// Change the plotgraf from green into red
	$Test->setColorPalette(0,255,0,0);
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawFilledRoundedRectangle(2,2,98,28,2,230,230,230);
	$Test->setGraphArea(5,5,95,25);
	$Test->drawGraphArea(255,255,255);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   

	// Draw the line graph
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-mini-hours.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-mini-hours.png', __FILE__ ) . '" alt="Training Graph Mini Hours" />';

//End function runners_log_graphmini_hours()
}
add_shortcode('runners_log_graphmini_hours', 'runners_log_graphmini_hours');

  //runners_log_graphmini_calories
  function runners_log_graphmini_calories() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	// Make $wpdb global
	global $wpdb;

	// Connect to DB and get the sum of calories
	$calories_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( $wpdb->postmeta.meta_value ) AS runcalories
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_calories_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
	// is there something to do?
	if (sizeof($calories_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	// Dataset definition 
	$DataSet = new pData;
 	foreach ($calories_per_month as $row) {
		$DataSet->AddPoint($row->runcalories,"Serie1");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie();

	// Initialise the graph
	$Test = new pChart(100,30);
	// Change the plotgraf from into blue
	$Test->setColorPalette(0,82,124,148);
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawFilledRoundedRectangle(2,2,98,28,2,230,230,230);
	$Test->setGraphArea(5,5,95,25);
	$Test->drawGraphArea(255,255,255);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   

	// Draw the line graph
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-mini-calories.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-mini-calories.png', __FILE__ ) . '" alt="Training Graph Mini Calories" />';

//End function runners_log_graphmini_calories()
}
add_shortcode('runners_log_graphmini_calories', 'runners_log_graphmini_calories');

  //[runners_log_pie_distance]
  function runners_log_pie_distance() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	//Make $wpdb global
	global $wpdb;
	
	// Let us get the distancetype for further calculations
	$distancetype = get_option('runnerslog_distancetype');	

	// Connect to DB and get the distance sum either as km-->runkm or miles-->runmiles and list it per month
	$distance_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm,  SUM( $wpdb->postmeta.meta_value ) AS runmiles
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($distance_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);
	
	//Dataset definition 
	$DataSet = new pData;	
	foreach ($distance_per_month as $row) {
		if ($distancetype == meters) {
			// The Y-axis is "Km per month" when meters is the choice
			$DataSet->AddPoint($row->runkm,"Serie1");
			$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
		} else {
			// The Y-axis is "Miles per month"
			$DataSet->AddPoint($row->runmiles,"Serie1");
			$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
		}
	}	
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Serie2"); 

	//Initialise the graph
	$Test = new pChart(475,200);
	
	//Set ColorsPalette
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/piepalette.txt'); 
	$Test->drawFilledRoundedRectangle(7,7,471,193,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,473,195,5,230,230,230);

	//Draw the pie chart
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),250,90,110,PIE_PERCENTAGE,TRUE,50,20,5);
	$Test->drawPieLegend(410,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
  
	//Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	
	// Draw the Distance per month graph
	if ($distancetype == meters) {
		$Test->drawTitle(15,20,"The Percentage of Km per Month",0,0,0);
	} else {
		$Test->drawTitle(15,20,"The Percentage of Miles per Month",0,0,0);
	}
 
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,192,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-pie-distance.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-pie-distance.png', __FILE__ ) . '" alt="Training Graph Pie distance" />';

//End function runners_log_pie_distance()
}
add_shortcode('runners_log_pie_distance', 'runners_log_pie_distance');

  //[runners_log_pie_hours]
  function runners_log_pie_hours() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	//Make $wpdb global
	global $wpdb;

	//Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_time_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($hours_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);
	
	//Dataset definition 
	$DataSet = new pData;
 	foreach ($hours_per_month as $row) {
		$DataSet->AddPoint($row->runhours,"Serie1");
		$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Serie2");

	//Initialise the graph
	$Test = new pChart(475,200);
 
	//Set ColorsPalette
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/piepalette.txt'); 
	$Test->drawFilledRoundedRectangle(7,7,471,193,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,473,195,5,230,230,230);

	//Draw the pie chart
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),250,90,110,PIE_PERCENTAGE,TRUE,50,20,5);
	$Test->drawPieLegend(410,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
  
	//Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	$Test->drawTitle(15,20,"The Percentage of Hours per Month",0,0,0); 
 
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,192,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-pie-hours.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-pie-hours.png', __FILE__ ) . '" alt="Training Graph Pie Hours" />';

 
 //End function runners_log_pie_hours()
}
add_shortcode('runners_log_pie_hours', 'runners_log_pie_hours');

  //[runners_log_pie_calories]
  function runners_log_pie_calories() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');
	
	//Make $wpdb global
	global $wpdb;

	// Connect to DB and get the sum of calories
	$calories_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( $wpdb->postmeta.meta_value ) AS runcalories
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_calories_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($calories_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);
	
	//Dataset definition 
	$DataSet = new pData;
 	foreach ($calories_per_month as $row) {
		$DataSet->AddPoint($row->runcalories,"Serie1");
		$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie("Serie2");

	//Initialise the graph
	$Test = new pChart(475,200);
 
	//Set ColorsPalette
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/piepalette.txt'); 
	$Test->drawFilledRoundedRectangle(7,7,471,193,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,473,195,5,230,230,230);

	//Draw the pie chart
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),250,90,110,PIE_PERCENTAGE,TRUE,50,20,5);
	$Test->drawPieLegend(410,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
  
	//Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	$Test->drawTitle(15,20,"The Percentage of Calories per Month",0,0,0); 
 
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,192,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-pie-calories.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-pie-calories.png', __FILE__ ) . '" alt="Training Graph Pie Calories" />';

 //End function runners_log_pie_calories()
}
add_shortcode('runners_log_pie_calories', 'runners_log_pie_calories');

  //[runners_log_bar_distance]
  function runners_log_bar_distance() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	//Make $wpdb global
	global $wpdb;
	
	// Let us get the distancetype for further calculations
	$distancetype = get_option('runnerslog_distancetype');
	
	// Connect to DB and get the distance sum either as km-->runkm or miles-->runmiles and list it per month
	$distance_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm,  SUM( $wpdb->postmeta.meta_value ) AS runmiles
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($distance_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);
	
	//Dataset definition 
	$DataSet = new pData;
	foreach ($distance_per_month as $row) {
		if ($distancetype == meters) {
			// The Y-axis is "Km per month" when meters is the choice
			$DataSet->AddPoint($row->runkm,"Serie1");
			$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
		} else {
			// The Y-axis is "Miles per month"
			$DataSet->AddPoint($row->runmiles,"Serie1");
			$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
		}
	}		
	$DataSet->AddSerie("Serie1");
	$DataSet->SetAbsciseLabelSerie("Serie2");

	//Initialise the graph
	$Test = new pChart(475,230);
	
	//Change the color of the bar to a blue softtone
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/barpalette.txt'); 
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(50,30,462,190);
	$Test->drawFilledRoundedRectangle(7,7,470,223,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,472,225,5,230,230,230);
	$Test->drawGraphArea(255,255,255,TRUE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);   
	$Test->drawGrid(4,TRUE,230,230,230,50);

	//Draw the bar graph
	$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
  
	//Write values on Serie1
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
  
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,222,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Write the title
 	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	if ($distancetype == meters) {
		$Test->drawTitle(0,0,"Km per month",0,0,0,472,30,FALSE);
	} else {
		$Test->drawTitle(0,0,"Miles per month",0,0,0,472,30,FALSE);
	}

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-bar-distance.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-bar-distance.png', __FILE__ ) . '" alt="Training Bar Graph Distance" />';

//End function runners_log_bar_distance()
}
add_shortcode('runners_log_bar_distance', 'runners_log_bar_distance');

  //[runners_log_bar_hours]
  function runners_log_bar_hours() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	//Make $wpdb global
	global $wpdb;
	
	//Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ROUND((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) )/3600), 2) AS runhours, sec_to_time( SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) ) AS runtime
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_time_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($hours_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);

  
	//Dataset definition 
	$DataSet = new pData;
 	foreach ($hours_per_month as $row) {
		$DataSet->AddPoint($row->runhours,"Serie1");
		$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
	}
	$DataSet->AddSerie("Serie1");
	$DataSet->SetAbsciseLabelSerie("Serie2");

	//Initialise the graph
	$Test = new pChart(475,230);
	
	//Change the color of the bar to a blue softtone
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/barpalette.txt'); 
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(50,30,462,190);
	$Test->drawFilledRoundedRectangle(7,7,470,223,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,472,225,5,230,230,230);
	$Test->drawGraphArea(255,255,255,TRUE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);   
	$Test->drawGrid(4,TRUE,230,230,230,50);

	//Draw the bar graph
	$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
  
	//Write values on Serie1
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
  
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,222,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Write the title
 	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	$Test->drawTitle(0,0,"Hours per month",0,0,0,472,30,FALSE);

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-bar-hours.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-bar-hours.png', __FILE__ ) . '" alt="Training Bar Graph Hours" />';

//End function runners_log_bar_hours()
}
add_shortcode('runners_log_bar_hours', 'runners_log_bar_hours');

  //[runners_log_bar_calories]
  function runners_log_bar_calories() {
	// Let us include the classes for the graph tool
	// Graph script by:  http://pchart.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');

	//Make $wpdb global
	global $wpdb;
	
	// Connect to DB and get the sum of calories
	$calories_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( $wpdb->postmeta.meta_value ) AS runcalories
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = '_rl_calories_value'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

	// is there something to do?
	if (sizeof($calories_per_month) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'Aug',
		9 => 'Sep',
		10 => 'Oct',
		11 => 'Nov',
		12 => 'Dec',
	);

	//Dataset definition 
	$DataSet = new pData;
 	foreach ($calories_per_month as $row) {
		$DataSet->AddPoint($row->runcalories,"Serie1");
		$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
	}
	$DataSet->AddSerie("Serie1");
	$DataSet->SetAbsciseLabelSerie("Serie2");

	//Initialise the graph
	$Test = new pChart(475,230);
	
	//Change the color of the bar to a blue softtone
	$Test->loadColorPalette(ABSPATH.PLUGINDIR.'/runners-log/Palettes/barpalette.txt'); 
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(50,30,462,190);
	$Test->drawFilledRoundedRectangle(7,7,470,223,5,240,240,240);
	$Test->drawRoundedRectangle(5,5,472,225,5,230,230,230);
	$Test->drawGraphArea(255,255,255,TRUE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE);   
	$Test->drawGrid(4,TRUE,230,230,230,50);

	//Draw the bar graph
	$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
  
	//Write values on Serie1
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
  
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,222,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	//Write the title
 	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	$Test->drawTitle(0,0,"Calories per month",0,0,0,472,30,FALSE);

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-bar-calories.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-bar-calories.png', __FILE__ ) . '" alt="Training Bar Graph Calories" />';

//End function runners_log_bar_calories()
}
add_shortcode('runners_log_bar_calories', 'runners_log_bar_calories');

  //[runners_log_garminmap]
  function runners_log_garminmap() {
	// Make $wpdb and $post global
	global $wpdb, $post;
	
	// Get the Garmin Connect Link
	$url = get_post_meta($post->ID, "_rl_garminconnectlink_value", $single = true);
	
	// Insert embed Garmin Connnect Map based on the used Garmin Connect Link
	if ( $url) {
	$mapurl = substr($url, strrpos($url, '/') + 1);
	echo "<iframe width='465' height='548' frameborder='0' src='http://connect.garmin.com:80/activity/embed/".$mapurl."'></iframe>";
	}

//End function runners_log_garminmap()
}
add_shortcode('runners_log_garminmap', 'runners_log_garminmap');

// Let us convert the total running time into seconds
function hms2sec ($hms)	{
		list($h, $m, $s) = explode (":", $hms);
		$seconds = 0;
		$seconds += (intval($h) * 3600);
		$seconds += (intval($m) * 60);
		$seconds += (intval($s));
		return $seconds;
	}

/*  Some admin stuff  */
	// Post Write Panel (Meta box)
	include('runnerslog_metabox.php');

	// Admin Options - Start adding the admin menu
	function runnerslog_admin() {  
		include('runnerslog_admin.php');
	}

	function runnerslog_training_zones() {  
		include('Includes/runnerslog_training_zones.php');
	}

	function runnerslog_v02max() {  
		include('Includes/runnerslog_v02max.php');
	} 

	function runnerslog_vdot_race_time() {  
		include('Includes/runnerslog_vdot_race_time.php');
	}
	
	function runnerslog_vdot_training_pace() {  
		include('Includes/runnerslog_vdot_training_pace.php');
	} 

	function runnerslog_stats_graphs() {  
		include('Includes/runnerslog_stats_graphs.php');
	}

	function runnerslog_body_mass_index() {  
		include('Includes/runnerslog_body_mass_index.php');
	} 

	function runnerslog_weight_change_effect() {  
		include('Includes/runnerslog_weight_change_effect.php');
	}

	function runnerslog_converter_toolbox() {  
		include('Includes/runnerslog_converter_toolbox.php');
	}
	
	function runnerslog_weather() {  
		include('Includes/runnerslog_weather_settings.php');
	}

	function runnerslog_admin_menu() {
    // Add a new top-level menu: Runners Log with Submenus
    add_menu_page('Runners Log', 'Runners Log', 'administrator', 'runners-log', 'runnerslog_admin');
    add_submenu_page('runners-log', 'Graphs and Stats', 'Graphs and Stats', 'administrator', 'runners-log-stats-graphs', 'runnerslog_stats_graphs');
    add_submenu_page('runners-log', 'HR Training Zones', 'HR Training Zones', 'administrator', 'runners-log-training-zones', 'runnerslog_training_zones');
    add_submenu_page('runners-log', 'V0<sub>2</sub>max Calculator', 'V0<sub>2</sub>max Calculator', 'administrator', 'runners-log-v02max', 'runnerslog_v02max');
    add_submenu_page('runners-log', 'Race Time Calc.', 'Race Time Calc.', 'administrator', 'runners-log-vdot-race-time', 'runnerslog_vdot_race_time');
    add_submenu_page('runners-log', 'Training Pace Calc.', 'Training Pace Calc.', 'administrator', 'runners-log-vdot-training-pace', 'runnerslog_vdot_training_pace');
	add_submenu_page('runners-log', 'Body Mass Index', 'Body Mass Index', 'administrator', 'runners-log-body-mass-index', 'runnerslog_body_mass_index');	
	add_submenu_page('runners-log', 'Weight Change Effect', 'Weight Change Effect', 'administrator', 'runners-log-weight-change-effect', 'runnerslog_weight_change_effect');	
	add_submenu_page('runners-log', 'Coverter Toolbox', 'Coverter Toolbox', 'administrator', 'runners-log-converter-toolbox', 'runnerslog_converter_toolbox');
	add_submenu_page('runners-log', 'Weather Setting', 'Weather Settings', 'administrator', 'runners-log-weather-settings', 'runnerslog_weather');	
	}
	// Hook for adding admin menus
	add_action('admin_menu', 'runnerslog_admin_menu');

// Set a few default options on plugin activation
register_activation_hook( __FILE__, 'runnerslog_activate' );

	function runnerslog_activate() 
	{
		update_option('runnerslog_distancetype', 'meters');
		update_option('runnerslog_unittype', 'metric');
		update_option('runnerslog_gender', 'male');
		update_option('runnerslog_pulsavg', '1');
		update_option('runnerslog_caloriescount', '1');
		update_option('runnerslog_cadence', '1');
		update_option('runnerslog_garminconnectlink', '1');
		update_option('runnerslog_show_distance', '1');
		//Settings for [runners_log_basic]
		update_option('runnerslog_show_time', '1');
		update_option('runnerslog_show_speed', '1');
		update_option('runnerslog_show_speedperdistance', '1');
		update_option('runnerslog_show_pulse', '1');
		update_option('runnerslog_show_calories', '1');
		update_option('runnerslog_show_cadence', '1');
		update_option('runnerslog_show_garminconnect', '1');
		update_option('runnerslog_show_distance2009', '1');
		update_option('runnerslog_show_distance2010', '1');
		update_option('runnerslog_show_distance2011', '1');
		update_option('runnerslog_show_distance_sum', '1');
		update_option('runnerslog_show_garminmap', '1');
	}
	
//Create the gear-list menu-box
add_action('admin_menu', 'wp_gear_manager_create_menu');

	function wp_gear_manager_create_menu() {
		$show_gearmanager = get_option('runnerslog_show_gearmanager');

		if($show_gearmanager == '1'){
			add_menu_page( 'Gear Manager', 'Gear Manager', 1, 'runners-log-gear', 'wp_gear_manager_page_dispatcher', IMG_DIRECTORY.'ico16.png');
			add_submenu_page( 'runners-log-gear', 'New Gear', 'Add new gear', 1, 'runners-log-gear&amp;gear=new', 'wp_gear_manager_page_dispatcher' );
		}
	}
?>
