<?php
/*
Plugin Name: Runners Log
Plugin URI: http://wordpress.org/extend/plugins/runners-log/
Description: This plugin let your convert your blog into a training log. Based on 4 custom fields it let you calculate your speed, time per km, and let you have a chart of your total distance and minutes per month.
Author: Frederik Liljefred
Author URI: http://www.liljefred.dk
Version: 1.0.7
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Requires WordPress 2.7 or later.

== Use the follow tags in your template ==
    <?php runners_log_basic(); ?>
	<?php runners_log_graph(); ?>
	<?php runners_log_pie_hours(); ?>
	<?php runners_log_pie_km(); ?>
	<?php runners_log_bar_km(); ?>
	<?php runners_log_bar_hours(); ?>
	<?php runners_log_graphmini_km(); ?>
	<?php runners_log_graphmini_hours(); ?>

== I only want my graphs to show up in a special category ==
If you only want your graphs to show up in the category "training" with the category ID = 6 then use it like this eg in single.php:

	<?php if ( in_category('6') ): ?>
	<?php runners_log_basic(); ?>
	<?php runners_log_graph(); ?>
	<?php runners_log_graphmini_km(); ?>
	<?php runners_log_graphmini_hours(); ?>
	<?php runners_log_pie_km(); ?>
	<?php runners_log_pie_hours(); ?>
	<?php runners_log_bar_km(); ?>
	<?php runners_log_bar_hours(); ?>
	<?php endif; ?>

== I only want my graphs to show up in a special page ==
If you only want your graphs to show up in the page with the name "Training Stats" then use it like this eg. in page.php:
BE WARE: <?php runners_log_basic(); ?> only works in categories

	<?php if (is_page('Training Stats')) { ?>
	<?php runners_log_graph(); ?>
	<?php runners_log_graphmini_km(); ?>
	<?php runners_log_graphmini_hours(); ?>
	<?php runners_log_pie_km(); ?>
	<?php runners_log_pie_hours(); ?>
	<?php runners_log_bar_km(); ?>
	<?php runners_log_bar_hours(); ?>
	<?php } ?>
	
*/
 function runners_log_basic() {
 //Make $wpdb and $post global
	global $wpdb, $post;
 //Get the running time 
	$hms = get_post_meta($post->ID, "Time", $single = true);
 //Get the distance
	$meters = get_post_meta($post->ID, "Meters", $single = true);
 //Get the Garmin Connect Link
	$url = get_post_meta($post->ID, "GarminConnectLink", $single = true);
 //Get pulsavg.
	$pulsavg = get_post_meta($post->ID, "Pulsavg", $single = true);

 //Let us convert the total running time into seconds
	function hms2sec ($hms) {
	list($h, $m, $s) = explode (":", $hms);
	$seconds = 0;
	$seconds += (intval($h) * 3600);
	$seconds += (intval($m) * 60);
	$seconds += (intval($s));
	return $seconds;
	}
 //Call the function
	$seconds = hms2sec($hms);

 //Calculate the avg running speed in km/hour
	$km_per_hour = (($meters/1000) / ($seconds/3600));
	$km_per_hour_round = round($km_per_hour, 2);

 //Calculate number of minutes per km
	$min_per_km = ($seconds) / ($meters/1000);
	$minutes = floor($min_per_km/60);
	$secondsleft = $min_per_km%60;
	if($minutes<10)
		$minutes = "0" . $minutes;
	if($secondsleft<10)
		$secondsleft = "0" . $secondsleft;

 //Connect to DB and calculate the sum of meters run in 2009
	$meter_sum_2009 = $wpdb->get_var($wpdb->prepare("
	SELECT SUM($wpdb->postmeta.meta_value)
	FROM $wpdb->postmeta, $wpdb->posts 
	WHERE $wpdb->postmeta.meta_key='Meters'
	AND $wpdb->posts.post_status = 'publish'	
	AND $wpdb->postmeta.post_id=$wpdb->posts.id  
	AND year($wpdb->posts.post_date)='2009'"));
 //Convert meters to km
	$km_sum_2009 = round($meter_sum_2009/1000, 1);
	
 //Connect to DB and calculate the number of runs in 2009
	$number_of_runs_2009 = $wpdb->get_var($wpdb->prepare("
	SELECT COUNT($wpdb->postmeta.meta_value)
	FROM $wpdb->postmeta, $wpdb->posts 
	WHERE $wpdb->postmeta.meta_key='Meters'
	AND $wpdb->posts.post_status = 'publish'	
	AND $wpdb->postmeta.post_id=$wpdb->posts.id  
	AND year($wpdb->posts.post_date)='2009'"));
	
 //Calculate the avg km per run in 2009
	$avg_km_per_run_2009 = ROUND(($meter_sum_2009/1000) / $number_of_runs_2009, 2);
	
 //Connect to DB and calculate the sum of meters run in 2010
	$meter_sum_2010 = $wpdb->get_var($wpdb->prepare("
	SELECT SUM($wpdb->postmeta.meta_value), COUNT($wpdb->postmeta.meta_value) as numberofrun2010
	FROM $wpdb->postmeta, $wpdb->posts 
	WHERE $wpdb->postmeta.meta_key='Meters'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->postmeta.post_id=$wpdb->posts.id  
	AND year($wpdb->posts.post_date)='2010'"));
 //Convert meters to km
	$km_sum_2010 = round($meter_sum_2010/1000, 1);
	
 //Connect to DB and calculate the number of runs in 2010
	$number_of_runs_2010 = $wpdb->get_var($wpdb->prepare("
	SELECT COUNT($wpdb->postmeta.meta_value)
	FROM $wpdb->postmeta, $wpdb->posts 
	WHERE $wpdb->postmeta.meta_key='Meters'
	AND $wpdb->posts.post_status = 'publish'	
	AND $wpdb->postmeta.post_id=$wpdb->posts.id  
	AND year($wpdb->posts.post_date)='2010'"));
	
 //Calculate the avg km per run in 2010
	$avg_km_per_run_2010 = ROUND(($meter_sum_2010/1000) / $number_of_runs_2010, 2);
	
 //Print it all
	echo "<ul class='post-meta'>";
	echo "<li><span class='post-meta-key'>Meters:</span> $meters</li>";
	echo "<li><span class='post-meta-key'>Time:</span> $hms</li>";
	echo "<li><span class='post-meta-key'>Km/hour:</span> $km_per_hour_round</li>";
	echo "<li><span class='post-meta-key'>Min/km:</span> $minutes:$secondsleft minutes</li>";
	if ($pulsavg) {
	echo "<li><span class='post-meta-key'>Puls average:</span> $pulsavg</li>";
	}
	if ($url) {
	echo "<li><span class='post-meta-key'>Garmin Connect Link:</span> <a href='$url' target='_blank'>$url</a></li>";
	}
	if ($km_sum_2009 && $number_of_runs_2009 == 1 ) {
	echo "<li><span class='post-meta-key'>Km in 2009:</span> <strong>$km_sum_2009</strong> km based on <strong>1</strong> run with an avg of <strong>$km_sum_2009</strong></li>";
	} else {
	echo "<li><span class='post-meta-key'>Km in 2009:</span> <strong>$km_sum_2009</strong> km based on <strong>$number_of_runs_2009</strong> runs with an avg of <strong>$avg_km_per_run_2009</strong> km</li>";
	}	
	if ($km_sum_2010 && $number_of_runs_2010 == 1 ) {
	echo "<li><span class='post-meta-key'>Km in 2010:</span> <strong>$km_sum_2010</strong> km based on <strong>1</strong> run with an avg of <strong>$km_sum_2010</strong></li>";
	} else {
	echo "<li><span class='post-meta-key'>Km in 2010:</span> <strong>$km_sum_2010</strong> km based on <strong>$number_of_runs_2010</strong> runs with an avg of <strong>$avg_km_per_run_2010</strong> km</li>";
	}	
	echo "</ul>";
//End function runners_log_basic()
 }

 function runners_log_graph() {
 //Let us include the classes for the graph tool
 //Graph script by:  http://pchart.sourceforge.net/
	include(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');
 
 //Make $wpdb global
	global $wpdb;
	
 //Connect to DB and get the sum of meters and convert them to km and list it per month
	$km_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Meters'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
 //Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) )/3600) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Time'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
 //Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Marts',
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
 //Add the datapoint one by one...
 	foreach ($km_per_month as $row) {
 //The Y-axis "Km per month"
	$DataSet->AddPoint($row->runkm,"Serie1");
 //The X-axis "The months" and we transform 1,2,3.. to Jan, Feb, Marts... using $month2str
	$DataSet->AddPoint($month2str[$row->runmonth],"Serie3");
	}
 	foreach ($hours_per_month as $row) {
 //Y-axis to the right "Hours per month" - Later add database for this too
	$DataSet->AddPoint($row->runhours,"Serie2");
	}
	$DataSet->AddSerie("Serie1"); 
	$DataSet->SetAbsciseLabelSerie("Serie3");
 //Here you can set the titel of the Data Serie 1
	$DataSet->SetSerieName("Km per month","Serie1");
 //Here you can set the titel of the Data Serie 2
	$DataSet->SetSerieName("Hours per month","Serie2");

 //Initialise the graph
	$Test = new pChart(475,230);
	$Test->drawGraphAreaGradient(90,90,90,90,TARGET_BACKGROUND);

 //Prepare the graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(60,40,428,190);

 //Initialise graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);

 //Draw the "Km per month" graph
	$DataSet->SetYAxisName("Km per month");
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,0);
	$Test->drawGraphAreaGradient(40,40,40,-50);
	$Test->drawGrid(4,TRUE,230,230,230,10);
	$Test->setShadowProperties(3,3,0,0,0,30,4);
	$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
	$Test->clearShadow();
	$Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);
	$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);

/*
 //Uncomment if you want to add labels to your "Km per month" graph 	
 //Write values on Serie1
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
*/

 //Clear the scale
	$Test->clearScale();

 //Draw the 2nd graph the "Hours per month" graph
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
 //Uncomment if you want to add labels to your "Hours per month" graph 	
 //Write values on Serie2
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);   
	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");
*/

 //Write the legend (box less)
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawLegend(360,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,255,255,255,FALSE);

 //Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/MankSans.ttf',18);
	$Test->setShadowProperties(1,1,0,0,0);
	$Test->drawTitle(0,0,"Training Graph",255,255,255,472,30,TRUE);
	$Test->clearShadow();
	
 //Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(251,227,"Runners Log a Wordpress plugin by Frederik Liljefred",255,255,255); 

 //Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph.png');
 
 //Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph.png', __FILE__ ) . '" alt="Training Graph" />';

 //End function runners_log_graph()
 }

 function runners_log_graphmini_km() {

 //Make $wpdb global
	global $wpdb;

 //Connect to DB and get the sum of meters and convert them to km and list it per month
	$km_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, (SUM( $wpdb->postmeta.meta_value )/1000) AS runkm
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Meters'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
 //Dataset definition 
	$DataSet = new pData;
 	foreach ($km_per_month as $row) {
 //The Y-axis "Km per month"
	$DataSet->AddPoint($row->runkm,"Serie1");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie();

	//Initialise the graph
	$Test = new pChart(100,30);
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawFilledRoundedRectangle(2,2,98,28,2,230,230,230);
	$Test->setGraphArea(5,5,95,25);
	$Test->drawGraphArea(255,255,255);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   

	//Draw the line graph
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-mini-km.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-mini-km.png', __FILE__ ) . '" alt="Training Graph Mini Km" />';

 //End function runners_log_graphmini_km()
 }

 function runners_log_graphmini_hours() {

 //Make $wpdb global
	global $wpdb;

 //Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) )/3600) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Time'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");
	
 //Dataset definition 
	$DataSet = new pData;
 	foreach ($hours_per_month as $row) {
 //The Y-axis "Minutes per month"
	$DataSet->AddPoint($row->runhours,"Serie1");
	}
	$DataSet->AddAllSeries();
	$DataSet->SetAbsciseLabelSerie();

	//Initialise the graph
	$Test = new pChart(100,30);
	//Change the plotgraf from green into red
	$Test->setColorPalette(0,255,0,0);
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->drawFilledRoundedRectangle(2,2,98,28,2,230,230,230);
	$Test->setGraphArea(5,5,95,25);
	$Test->drawGraphArea(255,255,255);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2);   

	//Draw the line graph
	$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-mini-hours.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-mini-hours.png', __FILE__ ) . '" alt="Training Graph Mini Hours" />';

 //End function runners_log_graphmini_hours()
 }

 function runners_log_pie_km() {

 //Make $wpdb global
	global $wpdb;

 //Connect to DB and get the sum of meters runned and list it per month
	$km_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( $wpdb->postmeta.meta_value ) AS runkm
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Meters'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

 //Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Marts',
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
 	foreach ($km_per_month as $row) {
 //The Y-axis "Km per month"
	$DataSet->AddPoint($row->runkm,"Serie1");
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
	$Test->drawTitle(15,20,"The Percentage of Km per Month",0,0,0); 
 
 //Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,192,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

 //Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-pie-km.png');
 
 //Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-pie-km.png', __FILE__ ) . '" alt="Training Graph Pie Km" />';

 //End function runners_log_pie_km()
 }

 function runners_log_pie_hours() {

 //Make $wpdb global
	global $wpdb;

 //Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) AS runhours
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Time'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

 //Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Marts',
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
 //The Y-axis "Hours per month"
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

 function runners_log_bar_km() {

 //Make $wpdb global
	global $wpdb;
	
 //Connect to DB and get the sum of meters and convert them to km and list it per month
	$km_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ROUND((SUM( $wpdb->postmeta.meta_value )/1000),1) AS runkm
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Meters'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

 //Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Marts',
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
 	foreach ($km_per_month as $row) {
	$DataSet->AddPoint($row->runkm,"Serie1");
	$DataSet->AddPoint($month2str[$row->runmonth],"Serie2");
	}
	$DataSet->AddAllSeries();
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
	$Test->drawTitle(0,0,"Km per month",0,0,0,472,30,FALSE);

 //Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-bar-km.png');
 
 //Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-bar-km.png', __FILE__ ) . '" alt="Training Bar Graph Km" />';

 //End function runners_log_bar_km()
 }

 function runners_log_bar_hours() {

 //Make $wpdb global
	global $wpdb;
	
 //Connect to DB and get the sum of minutes and convert them to hours and list it per month. And the minutes is rounded to 0 decimals.
	$hours_per_month = $wpdb->get_results("
	SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS runyearmonth, MONTH( $wpdb->posts.post_date ) AS runmonth, ROUND((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) )/3600), 2) AS runhours, sec_to_time( SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) ) AS runtime
	FROM $wpdb->postmeta
	INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
	WHERE $wpdb->postmeta.meta_key = 'Time'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
	GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
	");

 //Convert the Value 1 -> Jan, etc
	$month2str = Array (
		1 => 'Jan',
		2 => 'Feb',
		3 => 'Marts',
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
?>