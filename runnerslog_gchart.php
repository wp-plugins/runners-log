<<<<<<< .mine<?php
/*
[runners_log_gchart type="pie" format="d" year="2010" month="May" color="224499" width="600" height="300"]

Type: bar, graph, pie, 3dpie
Format: d="distance", ds="distance sum", ts="Time sum", h="heart rate" d="distance" c="calories" p="pulse average"
Year: 2009, 2010, 2011
Month: Jan, Feb, Marts, April, May, June, July, Aug, Sep, Oct, Nov, Dec
Color: Is the color scheme used eg: "224499" for the html color #224499
Width: The width of the chart: Default: 475 pixel
Height: The height of the chart: Default: 250 pixel	
*/


function runners_log_gchart_func($atts) {
/* W I K I  --  T H E  G U I D E  T O  A L L  T H E  V A R I A B L E S

		**********************************************************************************
		**			RELATED TO THE [runners_log_gchart] TAG			**
		**********************************************************************************
		
$type	//store the type selected in [runners_log_gchart] it can be eather: bar, graph, pie, 3dpie. Default is: bar
$format	//store the kind of data that would be show; d for distance, c for calories, p for pulse average
$year	//store the year from witch data would like to be calculated. Default: 2010
$month 	//store the month specified in the [runners_log_gchart] tag. Default: 0 //The same as all month == a given year
$olor	//store the color scheme html-code used for the google-api to generate the graphs. Default depends of the type of graph
$width	//The width of the chart: Default: 475 pixel
$height	//The height of the chart: Default: 250 pixel

		**********************************************************************************
		**				RELATED TO THE DATABASE HANDLING		**
		**********************************************************************************

$month2value //Convert the month to a given equivalent value like "jan-->1", "feb-->2" etc. Default is 0 == all months, the same as a whole year
$month2str //Convert the month, if it is a value, back to its real name
$dbdata	//stores the database result as an array
$xaxislable	//The x-labels printed from the database

		**********************************************************************************
		**				RELATED TO THE GOOGLE API			**
		**********************************************************************************
$chf	Gradient Fills http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_gradient_fills
$chxl	Custom Axis Labels http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_labels
$chxr	Axis Range http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_range
$chxp	Axis Label Positions http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_label_positions
$chxs	Axis Label Styles http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_label_styles
$chxtc 	Axis Tick Mark Styles http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_tick_marks
$chxt	Visible Axes http://code.google.com/intl/da/apis/chart/docs/chart_params.html#axis_type
$chbh	Bar Width and Spacing http://code.google.com/intl/da/apis/chart/docs/gallery/bar_charts.html#chbh
$chs	Chart Size http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_chs
$cht	Chart Type http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_cht
$chco	Series Colors http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_series_color
$chds	Text Format with Custom Scaling http://code.google.com/intl/da/apis/chart/docs/data_formats.html#data_scaling
$chd	Chart Data String http://code.google.com/intl/da/apis/chart/docs/data_formats.html
$chdl	Chart Legend Text and Style http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_legend
$chl	Chart Legend Text, when it is a pie
$chdlp 	Where the legand is placed. Top, right, left etc
$chg	Grid Lines http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_grid_lines
$chls	Line Styles http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_line_styles
$chm	Shape Markers http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_shape_markers
$chtt	Chart Title http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_chart_title

		**********************************************************************************
		**				SETTINGS VARIABLE				**
		**********************************************************************************
$distancetype	Could be either meters or miles

		**********************************************************************************
		**				LINKS						**
		**********************************************************************************

Code Playground
http://code.google.com/apis/ajax/playground/?type=visualization#toolbar

Dynamic Examples
http://code.google.com/intl/da/apis/visualization/documentation/examples.html

Chart Editor
http://imagecharteditor.appspot.com/
*/

	extract(shortcode_atts(array(
		'type' => 'bar',
		'format' => 'd',
		'year' => '2011',
		'month' => '',
		'width' => '470',
		'height' => '240',
		'color' => '224499',
	), $atts));

	global $wpdb, $distancetype;	

  /* T O O L  B O X */
	//Let us convert the month from [runners_log_gchart month="feBruarY"] to the value 2 etc
	$month = strtolower(substr($month, 0, 3));	//First rip the month value to 3 chars and then set all the chars to lower letters. 
	$month2value = Array (	//Convert the month to a given equivalent value
		'' => 0,  //adds the value 0 if no month is specified in [runners_log_gchart]
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
		'dec' => 12
	);
	$month = $month2value[$month]; 	//Covert the 3 charts to a equivalent value using the array "month2value"
	
	//Let us convert the value to a the month name used in the X-axis in google chart API
	//Convert the Value 1 -> Jan, etc
	$month2str = Array (
		0 => '', //if no months is specified you get data for the whole year
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
		12 => 'December'
	);
	
	
  /* T H E   F O R M A T  S W I T C H */
	switch ($format){
	
		case "d" : //Distances per day period - month or year
			if ($month == '0') {
				$dbdata = $wpdb->get_results("
					SELECT $wpdb->posts.post_date AS Fulldate, 
							DAY( $wpdb->posts.post_date ) AS Day , 
							MONTH( $wpdb->posts.post_date ) AS Month , 
							$wpdb->postmeta.meta_value AS Distance
					FROM $wpdb->postmeta, $wpdb->posts
					WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
					AND $wpdb->posts.post_status = 'publish'
					AND $wpdb->postmeta.post_id = $wpdb->posts.id
					AND year($wpdb->posts.post_date)= '$year'
					ORDER BY `$wpdb->posts`.`post_date` ASC");
			} else {
				$dbdata = $wpdb->get_results("
					SELECT $wpdb->posts.post_date AS Fulldate, 
							DAY( $wpdb->posts.post_date ) AS Day , 
							MONTH( $wpdb->posts.post_date ) AS Month ,				
							$wpdb->postmeta.meta_value AS Distance
					FROM $wpdb->postmeta, $wpdb->posts
					WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
					AND $wpdb->posts.post_status = 'publish'
					AND $wpdb->postmeta.post_id = $wpdb->posts.id
					AND year($wpdb->posts.post_date)= '$year'
					AND month($wpdb->posts.post_date)= '$month'
					ORDER BY `$wpdb->posts`.`post_date` ASC");
			}	
			if (sizeof($dbdata) == '0') // is there something to do?
			{
				echo "No data available.<br/>\n";
				return;
			}
			foreach ($dbdata as $row) {
				if ($distancetype == 'Meters') {
					$monthsList[] = (($row->Distance)/1000);
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$xaxislable[] = sprintf(__('%1$d %2$s'), $row->Day, substr( $month2str[$row->Month], 0, 3 ) );
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
				} else { //else it is miles
					$monthsList[] = $row->Distance;
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$xaxislable[] = sprintf(__('%1$d %2$s'), $row->Day, substr( $month2str[$row->Month], 0, 3 ) );
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
				}
			}
			//Google Chart API Varibles
			if ($distancetype == 'Meters') {
				$chdl = 'Km';
			} else {
				$chdl = 'Miles';
			}
			if ($month == '0') {
				$chtt = 'Distances  in ' .$year. '';
			} else { 
				$chtt = 'Distances per day in '. $month2str[$row->Month] .' ' .$year. '';
			}
		break;
		
		case "ds" : //Distance Sum preveous year. This part doesnt support year. It is always the previous year from today.
			$dbdata = $wpdb->get_results("
				SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS Runyearmonth,
					MONTH( $wpdb->posts.post_date ) AS Runmonth,
					(SUM( $wpdb->postmeta.meta_value )/1000) AS Runkm,
					SUM( $wpdb->postmeta.meta_value ) AS Runmiles
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
				WHERE $wpdb->postmeta.meta_key = '_rl_distance_value'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
				GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )
			");
			if (sizeof($dbdata) == '0') { // is there something to do?
				echo "No data available.<br/>\n";
				return;
			}
			foreach ($dbdata as $row) {
				if ($distancetype == 'Meters') {
					$monthsList[] = $row->Runkm;
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$xaxislable[] = sprintf(__('%1$s'), substr( $month2str[$row->Runmonth], 0, 3 ) );
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
				} else { //else it is miles
					$monthsList[] = $row->Runmiles;
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$xaxislable[] = sprintf(__('%1$s'), substr( $month2str[$row->Runmonth], 0, 3 ) );
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
				}
			}
			//Google Chart API Varibles
			if ($distancetype == 'Meters') {
				$chdl = 'Km';
			} else {
				$chdl = 'Miles';
			}
			$chtt = 'Distances per Month the Previous Year';
		break;

		case "ts" :	//Time Sum per Month
			$dbdata = $wpdb->get_results("
				SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS Runyearmonth, 
					MONTH( $wpdb->posts.post_date ) AS Runmonth,
					DATE_FORMAT( wp_posts.post_date, '%y' ) AS Runyear,
					ROUND((SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) )/3600), 2) AS Runhours,
					sec_to_time( SUM( time_to_sec( STR_TO_DATE( $wpdb->postmeta.meta_value, '%T' ) ) ) ) AS Runtime
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
				WHERE $wpdb->postmeta.meta_key = '_rl_time_value'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->postmeta.post_id = $wpdb->posts.id
				AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
				GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )	
			");
			if (sizeof($dbdata) == '0') { // is there something to do?
				echo "No data available.<br/>\n";
				return;
			}
			foreach ($dbdata as $row) {
				$monthsList[] = $row->Runhours;
				$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
				$xaxislable[] = sprintf(__('%1$s'), substr( $month2str[$row->Runmonth], 0, 3 ) );
				$sumvalue = array_sum($monthsList);
				$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
			}
			//Google Chart API Varibles
			$chdl = 'Hours';
			$chtt = 'Time per Month the Previous Year';
		break;
		
		case "cs" : //Calories Sum per Month
			$dbdata = $wpdb->get_results("
				SELECT DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' ) AS Runyearmonth, 
					MONTH( $wpdb->posts.post_date ) AS Runmonth,
					DATE_FORMAT( wp_posts.post_date, '%y' ) AS Runyear,
					SUM( $wpdb->postmeta.meta_value ) AS Runcalories
				FROM $wpdb->postmeta
				INNER JOIN $wpdb->posts ON ( $wpdb->postmeta.post_id = $wpdb->posts.id )
				WHERE $wpdb->postmeta.meta_key = '_rl_calories_value'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->postmeta.post_id = $wpdb->posts.id
				AND $wpdb->posts.post_date >= DATE_ADD( NOW(), INTERVAL -1 YEAR)
				GROUP BY DATE_FORMAT( $wpdb->posts.post_date, '%Y-%m' )	
			");
			if (sizeof($dbdata) == '0') { // is there something to do?
				echo "No data available.<br/>\n";
				return;
			}
			foreach ($dbdata as $row) {
				$monthsList[] = $row->Runcalories;
				$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
				$xaxislable[] = sprintf(__('%1$s'), substr( $month2str[$row->Runmonth], 0, 3 ) );
				$sumvalue = array_sum($monthsList);
				$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
			}
			//Google Chart API Varibles
			$chdl = 'Cal.';
			$chtt = 'Calories per Month the Previous Year';
		break;
		
		case "p" :  //Pulse avg
			if ($month == '0') {
				$dbdata = $wpdb->get_results("
					SELECT $wpdb->posts.post_date AS Fulldate, 
							DAY( $wpdb->posts.post_date ) AS Day , 
							MONTH( $wpdb->posts.post_date ) AS Month , 
							$wpdb->postmeta.meta_value AS Pulse
					FROM $wpdb->postmeta, $wpdb->posts
					WHERE $wpdb->postmeta.meta_key = '_rl_pulsavg_value'
					AND $wpdb->posts.post_status = 'publish'
					AND $wpdb->postmeta.post_id = $wpdb->posts.id
					AND year($wpdb->posts.post_date)= '$year'
					ORDER BY `$wpdb->posts`.`post_date` ASC
					");
			} else {
				$dbdata = $wpdb->get_results("
					SELECT $wpdb->posts.post_date AS Fulldate, 
							DAY( $wpdb->posts.post_date ) AS Day , 
							MONTH( $wpdb->posts.post_date ) AS Month ,				
							$wpdb->postmeta.meta_value AS Pulse
					FROM $wpdb->postmeta, $wpdb->posts
					WHERE $wpdb->postmeta.meta_key = '_rl_pulsavg_value'
					AND $wpdb->posts.post_status = 'publish'
					AND $wpdb->postmeta.post_id = $wpdb->posts.id
					AND year($wpdb->posts.post_date)= '$year'
					AND month($wpdb->posts.post_date)= '$month'
					ORDER BY `$wpdb->posts`.`post_date` ASC
					");
			}	
			if (sizeof($dbdata) == '0') // is there something to do?
			{
				echo "No data available.<br/>\n";
				return;
			}
			foreach ($dbdata as $row) {
				if ($distancetype == 'Meters') {
					$monthsList[] = (($row->Pulse)/1000);
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$minvalue = min($monthsList); //We need the min-value to have the righ x-axis
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
					$xaxislable[] = sprintf(__('%1$d %2$s'), $row->Day, substr( $month2str[$row->Month], 0, 3 ) );
				} else { //else it is miles
					$monthsList[] = $row->Pulse;
					$maxvalue = max($monthsList); //We need the max-value to have the righ Y-axis
					$minvalue = min($monthsList); //We need the min-value to have the righ x-axis
					$sumvalue = array_sum($monthsList);
					$valuepercents = array_map(function ($a) use($sumvalue) { return ROUND((($a/$sumvalue)*100),0); }, $monthsList);   // will only work in php5.3??
					$xaxislable[] = sprintf(__('%1$d %2$s'), $row->Day, substr( $month2str[$row->Month], 0, 3 ) );
				}
			}
			//Google Chart API Varibles
			$chdl = 'Bpm';
			if ($month == '0') {
				$chtt = 'Pulse Average  in ' .$year. '';
			} else { 
				$chtt = 'Pulse Average per day in '. $month2str[$row->Month] .' ' .$year. '';
			}
		break;
		
		default :
		break;
   }
	
  /* T H E   T Y P E  S W I T C H */	
	//Do what ever wanted based on the type - the type could be set to: bar, graph, pie */
	switch ($type){
		//Google Chart API can be found here: http://code.google.com/apis/chart/docs/making_charts.html
		//Chart Parameters: http://code.google.com/intl/da/apis/chart/docs/chart_params.html#gcharts_cht
		//Image Chart Editor: http://imagecharteditor.appspot.com/
		case "bar" :
			$chf = 'a,s,000000CD|bg,lg,0,EFEFEF,0,BBBBBB,1|c,lg,0,EFEFEF,0,BBBBBB,1';
			$chxl = '1:|'. join( '|', $xaxislable ) .'';
			$chxr =	'0,0,'. ($maxvalue + 1) .'';
			$chxp = '';
			$chxs = '';
			$chxtc = '';
			$chxt = 'y,x';
			$chbh = 'a,2,0';
			$chs = '' . esc_attr( $width ) . 'x' . esc_attr( $height ) .'';
			$cht = 'bvs';	//http://code.google.com/intl/da/apis/chart/docs/gallery/bar_charts.html#bar_types eg. "bvg"
			$chco = $color;
			$chds = '0,'. ($maxvalue + 1) .'';
			$chd = implode(',',  $monthsList);
			//$chdl = ''; Chart Legend Tekst moved to format switch do it is Bpm when pulse and eg. km when distances
			$chdlp = 't';
			$chg = '14.3,-1,1,1';
			$chls = '2,4,0';
			$chm = '';
			//$chtt = ''; //Title is based on the format therefor the value is in the format switch
		break;

		case "graph" :
			$chf = 'a,s,000000CD|bg,lg,0,EFEFEF,0,BBBBBB,1|c,lg,0,EFEFEF,0,BBBBBB,1';
			$chxl = '1:|'. join( '|', $xaxislable ) .'';
			$chxp = '';
			$chxr =	'0,'. ($minvalue -1) .','. ($maxvalue + 1) .'';
			$chxs = '';
			$chxtc = '';
			$chxt = 'y,x';
			$chbh = '';
			$chs = '' . esc_attr( $width ) . 'x' . esc_attr( $height ) .'';
			$cht = 'lc';	//http://code.google.com/intl/da/apis/chart/docs/gallery/line_charts.html
			$chco = $color;
			$chds = ($minvalue -5) .','. ($maxvalue + 1) .'';
			$chd = implode(',',  $monthsList);
			//$chdl = ''; Chart Legend Tekst moved to format switch do it is Bpm when pulse and eg. km when distances
			$chdlp = 't';
			$chg = '14.3,-1,1,1';
			$chls = '2,4,0';
			$chm = 'B,C5D4B5BB,0,0,0';
			//$chtt = 'Overskrift';
		break;
		
		case "pie" :
			$chf = 'a,s,000000CD|bg,lg,0,EFEFEF,0,BBBBBB,1';
			$chxl = '';
			$chxp = '';
			$chxr =	'';
			$chxs = '';
			$chxtc = '';
			$chxt = '';
			$chbh = '';
			$chs = '' . esc_attr( $width ) . 'x' . esc_attr( $height ) .'';
			$cht = 'p';	//http://code.google.com/intl/da/apis/chart/docs/gallery/pie_charts.html#chart_types
			$chco = $color;
			$chds = '0,'. ($maxvalue + 1) .'';
			$chd = implode(',',  $monthsList);
			foreach ($valuepercents as $key=>$val) $valuepercents[$key] = sprintf("%d%%25", $val); //We need to have a percent (%=%25) char after each value
			$chl = implode("|", $valuepercents); //We need to list the values like 
			$chdl = join( '|', $xaxislable ); //Date like: 31 dec
			$chg = '';
			$chls = '';
			$chm = '';
			//$chtt = 'Overskrift';
		break;
		
		case "3dpie" :
			$chf = 'a,s,000000CD|bg,lg,0,EFEFEF,0,BBBBBB,1';
			$chxl = '';
			$chxp = '';
			$chxr =	'';
			$chxs = '';
			$chxtc = '';
			$chxt = '';
			$chbh = '';
			$chs = '' . esc_attr( $width ) . 'x' . esc_attr( $height ) .'';
			$cht = 'p3';	//http://code.google.com/intl/da/apis/chart/docs/gallery/pie_charts.html#chart_types
			$chco = $color;
			$chds = '0,'. ($maxvalue + 1) .'';
			$chd = implode(',',  $monthsList);
			foreach ($valuepercents as $key=>$val) $valuepercents[$key] = sprintf("%d%%25", $val); //We need to have a percent (%=%25) char after each value
			$chl = implode("|", $valuepercents); //We need to list the values like 
			$chdl = join( '|', $xaxislable ); //Date like: 31 dec
			$chg = '';
			$chls = '';
			$chm = '';
			//$chtt = 'Overskrift';
		break;

		default : //same as "bar"
		break;
   }	
	
	$charturl = ($chf ? "&chf=$chf" : "").($chxl ? "&chxl=$chxl" : "").($chxr ? "&chxr=$chxr" : "").($chxp ? "&chxp=$chxp" : "").($chxs ? "&chxs=$chxs" : "").($chxtc ? "&chxtc=$chxtc" : "").($chxt ? "&chxt=$chxt" : "").($chbh ? "&chbh=$chbh" : "").($chs ? "&chs=$chs" : "").($cht ? "&cht=$cht" : "").($chco ? "&chco=$chco" : "").($chds ? "&chds=$chds" : "").($chd ? "&chd=$chd" : "").($chdl ? "&chdl=$chdl" : "").($chl ? "&chl=$chl" : "").($chdlp ? "&chdlp=$chlp" : "").($chg ? "&chg=$chg" : "").($chls ? "&chls=$chls" : "").($chm ? "&chm=$chm" : "").($chtt ? "&chtt=$chtt" : "");
			
	return "<img src='http://chart.apis.google.com/chart?".$charturl."' />"
// Debugging start	
			.$month."<<-month<br />"
			.$maxvalue."<<-maxvalue<br />"
			.$minvalue."<<-minvalue<br />"
			.$year."<<-year<br />"
			.$width."<<-width<br />"
			.$height."<<-height<br />"
			.$type."<<-type<br />"
			.$color."<<-color<br />"
			.$xaxislable."<<-xaxislable<br />"
			.$monthsList."<<-monthsList<br />"
			.$format."<<-format<br />"
			.$sumvalue = array_sum($monthsList)."<br />"
			.print_r($valuepercents)."<<-valuepercents<br />"
			.$distancetype."<<-distancetype<br />"
//debugging end */
			;
	
	/* Debuging 
	print_r($distance_per_month) ;
	
	foreach($distance_per_month as $item) { $monthsList[] = $item->Runkm; } echo implode(',',  $monthsList); // or $list[0]->Runmonth
	*/
	
	//http://chart.apis.google.com/chart?cht=p3&chs=250x100&chd=t:60,40&chl=Hello|World
	/*
	echo '<img src="http://chart.apis.google.com/chart?chxl=0:|Jan|Feb|Mar|Jun|Jul|Aug|1:|100|50|0&chxt=x,y&chs=300x150&cht=lc&chds=0,106.49&chd=t:'?><?php foreach($distance_per_month as $item) { $monthsList[] = $item->Runkm; } echo implode(',',  $monthsList); ?><?php echo '&chg=25,50&chls=1" width="300" height="150" alt="" />';
	*/
// }
	
}
add_shortcode('runners_log_gchart', 'runners_log_gchart_func');
?>