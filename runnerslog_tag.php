<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////
// Eg: [runners_log year="2010" month="May" type="pie"] 											//
// Year: 2009, 2010																					//
// Month: Jan, Feb, Marts, April, May, June, July, Aug, Sep, Oct, Nov, Dec							//
// Type: graph, bar, pie, mini																		//
//////////////////////////////////////////////////////////////////////////////////////////////////////

//Let us start the function and set the default values, if not set in the [runners_log]-tag
function runners_log_func($atts) {
	extract(shortcode_atts(array(
		'year' => '2010',
		'month' => '',
		'type' => 'bar',
	), $atts));
	
	// Let us include the classes for the graph tool
	// Graph script by:  http://ptype.sourceforge.net/
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pData.class');
	include_once(ABSPATH.PLUGINDIR.'/runners-log/pChart/pChart.class');
	
	// Make $wpdb global
	global $wpdb;
	
	// Let us get the distancetype for further calculations
	$distancetype = get_option('runnerslog_distancetype');
	
//If [runners_log type="bar"] or just the default	
if ($type == 'bar') {

	//Convert the month it even if it is mispelled. 
	//First rip the month value to 3 chars and then set all the chars to lower letters. This way ppl can write FeBruarY and it still works
	$month = strtolower(substr($month, 0, 3));
	
	//Convert the month to a value eg jan -> 1, etc
	$month2value = Array (
		'' => 0,  //adds the value 0 if no month is specified in [runners_log]
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12,
	);	
	$month = $month2value[$month];

	if ($month == '0') {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
				} else {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			AND month($wpdb->posts.post_date)= '$month'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
		}
	
	// is there something to do?
	if (sizeof($dbdata) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}
	
	//Dataset definition 
	$DataSet = new pData;
	foreach ($dbdata as $row) {
		if ($distancetype == meters) {
			// The Y-axis is Km when meters is the choice
			$DataSet->AddPoint((($row->Distance)/1000),"Serie1");
			$DataSet->AddPoint($row->Day,"Serie2");
		} else {
			// The Y-axis is Miles
			$DataSet->AddPoint($row->Distance,"Serie1");
			$DataSet->AddPoint($row->Day,"Serie2");
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
	
	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		0 => 'for all months', //if no months is specified you get data for the whole year
		1 => 'January',
		2 => 'February',
		3 => 'Marts',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December',
	);

	//Write the title
 	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',10);
	if ($distancetype == meters) {
		$Test->drawTitle(0,0,"Km per day $month2str[$month] $year",0,0,0,472,30,FALSE);
	} else {
		$Test->drawTitle(0,0,"Miles per day $month2str[$month] $year",0,0,0,472,30,FALSE);
	}

	//Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-bar-distance'.$month.$year.'.png');
 
	//Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-bar-distance'.$month.$year.'.png', __FILE__ ) . '" alt="Training Graph" />';
}

//If [runners_log type="graph"]
if ($type == 'graph') {

	//Convert the month it even if it is mispelled. 
	//First rip the month value to 3 chars and then set all the chars to lower letters. This way ppl can write FeBruarY and it still works
	$month = strtolower(substr($month, 0, 3));
	
	//Convert the month to a value eg jan -> 1, etc
	$month2value = Array (
		'' => 0,  //adds the value 0 if no month is specified in [runners_log]
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12,
	);	
	$month = $month2value[$month];

	if ($month == '0') {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
				} else {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			AND month($wpdb->posts.post_date)= '$month'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
		}
	
	// is there something to do?
	if (sizeof($dbdata) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Dataset definition 
	$DataSet = new pData;
	foreach ($dbdata as $row) {
		if ($distancetype == meters) {
			// The Y-axis is Km when meters is the choice
			$DataSet->AddPoint((($row->Distance)/1000),"Serie1");
		} else {
			// The Y-axis is Miles
			$DataSet->AddPoint($row->Distance,"Serie1");
		}
		$DataSet->AddPoint($row->Day,"Serie3");
	}

	$DataSet->AddSerie("Serie1"); 
	$DataSet->SetAbsciseLabelSerie("Serie3");
	
	// Initialise the graph
	$Test = new pChart(475,230);
	$Test->drawGraphAreaGradient(90,90,90,90,TARGET_BACKGROUND);

	// Prepare the graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);
	$Test->setGraphArea(60,40,428,190);

	// Initialise graph area
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/tahoma.ttf',8);

	// Draw the axis-names
	if ($distancetype == meters) {
		$DataSet->SetYAxisName("Km");
	} else {
		$DataSet->SetYAxisName("Miles");
	}	
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,0);
	$Test->drawGraphAreaGradient(40,40,40,-50);
	$Test->drawGrid(4,TRUE,230,230,230,10);
	$Test->setShadowProperties(3,3,0,0,0,30,4);
	$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
	$Test->clearShadow();
	$Test->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);
	$Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
	
	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		0 => 'for all months', //if no months is specified you get data for the whole year
		1 => 'January',
		2 => 'February',
		3 => 'Marts',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December',
	);

	// Write the title
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/MankSans.ttf',14);
	$Test->setShadowProperties(1,1,0,0,0);
	if ($distancetype == meters) {
		$Test->drawTitle(0,0,"Km per day $month2str[$month] $year",255,255,255,472,30,TRUE);
	} else {
		$Test->drawTitle(0,0,"Miles per day $month2str[$month] $year",255,255,255,472,30,TRUE);
	}
	$Test->clearShadow();
	
	// Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(251,227,"Runners Log a Wordpress plugin by Frederik Liljefred",255,255,255); 

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-distance'.$month.$year.'.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-distance'.$month.$year.'.png', __FILE__ ) . '" alt="Training Graph Distance" />';
}

//If [runners_log type="pie"]
if ($type == 'pie') {

	//Convert the month it even if it is mispelled. 
	//First rip the month value to 3 chars and then set all the chars to lower letters. This way ppl can write FeBruarY and it still works
	$month = strtolower(substr($month, 0, 3));
	
	//Convert the month to a value eg jan -> 1, etc
	$month2value = Array (
		'' => 0,  //adds the value 0 if no month is specified in [runners_log]
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12,
	);	
	$month = $month2value[$month];

	if ($month == '0') {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
				} else {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			AND month($wpdb->posts.post_date)= '$month'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
		}
	
	// is there something to do?
	if (sizeof($dbdata) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}

	//Dataset definition 
	$DataSet = new pData;	
	foreach ($dbdata as $row) {
		if ($distancetype == meters) {
			// The Y-axis is Km when meters is the choice
			$DataSet->AddPoint((($row->Distance)/1000),"Serie1");
			$DataSet->AddPoint($row->Day,"Serie2");
		} else {
			// The Y-axis is Miles
			$DataSet->AddPoint($row->Distance,"Serie1");
			$DataSet->AddPoint($row->Day,"Serie2");
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
	
	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		0 => 'for all months', //if no months is specified you get data for the whole year
		1 => 'January',
		2 => 'February',
		3 => 'Marts',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December',
	);
	
	// Draw the Distance per month graph
	if ($distancetype == meters) {
		$Test->drawTitle(15,20,"The Percentage of Km $month2str[$month] $year",0,0,0);
	} else {
		$Test->drawTitle(15,20,"The Percentage of Miles $month2str[$month] $year",0,0,0);
	}
 
	//Draw the title Copyright. Please dont edit this.  
	$Test->setFontProperties(ABSPATH.PLUGINDIR.'/runners-log/Fonts/GeosansLight.ttf',8);
	$Test->drawTitle(249,192,"Runners Log a Wordpress plugin by Frederik Liljefred",0,0,0); 

	// Render the picture with a relative path
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-distance_pie'.$month.$year.'.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-distance_pie'.$month.$year.'.png', __FILE__ ) . '" alt="Training Graph Distance Pie" />';
}

//If [runners_log type="mini"]
if ($type == 'mini') {

	//Convert the month it even if it is mispelled. 
	//First rip the month value to 3 chars and then set all the chars to lower letters. This way ppl can write FeBruarY and it still works
	$month = strtolower(substr($month, 0, 3));
	
	//Convert the month to a value eg jan -> 1, etc
	$month2value = Array (
		'' => 0,  //adds the value 0 if no month is specified in [runners_log]
		'jan' => 1,
		'feb' => 2,
		'mar' => 3,
		'apr' => 4,
		'may' => 5,
		'jun' => 6,
		'jul' => 7,
		'aug' => 8,
		'sep' => 9,
		'oct' => 10,
		'nov' => 11,
		'dec' => 12,
	);	
	$month = $month2value[$month];

	if ($month == '0') {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
				} else {
		$dbdata = $wpdb->get_results("
			SELECT $wpdb->posts.post_date AS Fulldate, DAY( $wpdb->posts.post_date ) AS
			Day , MONTH( $wpdb->posts.post_date ) AS
			Month , $wpdb->postmeta.meta_value AS Distance
			FROM $wpdb->postmeta, $wpdb->posts
			WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
			AND $wpdb->posts.post_status = 'publish'
			AND $wpdb->postmeta.post_id = $wpdb->posts.id
			AND year($wpdb->posts.post_date)= '$year'
			AND month($wpdb->posts.post_date)= '$month'
			ORDER BY `$wpdb->posts`.`post_date` ASC");
		}
	
	// is there something to do?
	if (sizeof($dbdata) == 0)
	{
		echo "No data available.<br/>\n";
		return;
	}
	
	// Dataset definition 
	$DataSet = new pData;

	foreach ($dbdata as $row) {
		if ($distancetype == meters) {
			// The Y-axis is Km when meters is the choice
			$DataSet->AddPoint((($row->Distance)/1000),"Serie1");
		} else {
			// The Y-axis is Miles
			$DataSet->AddPoint($row->Distance,"Serie1");
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
	$Test->Render(ABSPATH.PLUGINDIR.'/runners-log/Cache/runners-log-graph-distance_mini'.$month.$year.'.png');
 
	// Insert the image and give it a absolute path
	echo '<img src="' . plugins_url( 'Cache/runners-log-graph-distance_mini'.$month.$year.'.png', __FILE__ ) . '" alt="Training Graph Distance Mini" />';
}

//Here we end the function
}
add_shortcode('runners_log', 'runners_log_func');

/* 
// Database snippet for local validation
SELECT wp_posts.post_date AS Fulldate, DAY( wp_posts.post_date ) AS
Day , MONTH( wp_posts.post_date ) AS
Month , wp_postmeta.meta_value AS Distance
FROM wp_postmeta, wp_posts
WHERE wp_postmeta.meta_key = '_rl_distance_value'
AND wp_posts.post_status = 'publish'
AND wp_postmeta.post_id = wp_posts.id
AND year(wp_posts.post_date)='2010'
AND month(wp_posts.post_date)='2'
ORDER BY `wp_posts`.`post_date` DESC
*/

?>