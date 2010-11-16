﻿<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log VDOT Race Time Calculator', 'runnerslog_ops' ) . "</h2>"; ?>

<?php
//We have to be sure that we have the variables needed for the calculations
$vdot_distance = get_option('runnerslog_vdot_distance');
$vdot_time = get_option('runnerslog_vdot_time');
$distancetype = get_option('runnerslog_distancetype');

//First we make the arrays for the VDOTs 
//				0		1		2		3		4		5	6		7		8		9		10			11		12			13		14		15
$vdot = array("VDOT", "1500", "Mile", "3k", "2-mile", "5k", "8k", "5-mile", "10k", "15k", "10-mile", "20k", "1/2 Marathon", "25k", "30k", "Marathon");

if ( $distancetype == meters ) {
	$vdotdistance = array("", "1.5", "1.609344", "3", "3.218688", "5", "8", "8.04672", "10", "15", "16.09344", "20", "21.1", "25", "30", "42.195");
		} else {
	$vdotdistance = array("", "0.932056788", "1", "1.86411358", "2", "3.10685596", "4.97096954", "5", "6.21371192", "9.32056788", "10", "12.4274238", "13.1109322", "15.5342798", "18.6411358", "26.2187575");
} 
	
$vdot85 = array("85", "3:23.5", "3:39.6", "7:14.1", "7:48.9", "12:37", "20:50", "20:58", "26:19", "40:17", "43:26", "54:40", "57:50", "1:09:33", "1:24:33", "2:01:10");
$vdot84 = array("84", "3:25.5", "3:41.8", "7:18.5", "7:53.7", "12:45", "21:02", "21:10", "26:34", "40:42", "43:53", "55:14", "58:25", "1:10:15", "1:25:25", "2:02:24");
$vdot83 = array("83", "3:27.6", "3:44.1", "7:23.1", "7:58.7", "12:53", "21:16", "21:24", "26:51", "41:06", "44:19", "55:48", "59:01", "1:10:59", "1:26:18", "2:03:40");
$vdot82 = array("82", "3:29.7", "3:46.4", "7:27.8", "8:03.7", "13:01", "21:29", "21:37", "27:07", "41:32", "44:47", "56:23", "59:38", "1:11:43", "1:27:12", "2:04:57");
$vdot81 = array("81", "3:31.9", "3:48.7", "7:32.5", "8:08.9", "13:09", "21:42", "21:50", "27:24", "41:58", "45:15", "56:58", "1:00:15", "1:12:28", "1:28:07", "2:06:17");
$vdot80 = array("80", "3:34.2", "3:51.2", "7:37.5", "8:14.2", "13:18", "21:56", "22:04", "27:41", "42:25", "45:44", "57:34", "1:00:54", "1:13:15", "1:29:04", "2:07:38");
$vdot79 = array("79", "3:36.5", "3:53.7", "7:43", "8:20", "13:26", "22:10", "22:18", "27:59", "42:52", "46:13", "58:12", "1:01:34", "1:14:03", "1:30:02", "2:09:02");
$vdot78 = array("78", "3:38.8", "3:56.2", "7:48", "8:25", "13:35", "22:24", "22:32", "28:17", "43:20", "46:44", "58:51", "1:02:15", "1:14:52", "1:31:02", "2:10:27");
$vdot77 = array("77", "3:41", "3:58", "7:53", "8:31", "13:44", "22:39", "22:48", "28:36", "43:49", "47:15", "59:30", "1:02:56", "1:15:41", "1:32:02", "2:11:54");
$vdot76 = array("76", "3:44", "4:02", "7:58", "8:37", "13:54", "22:55", "23:03", "28:55", "44:18", "47:46", "1:00:10", "1:03:39", "1:16:33", "1:33:05", "2:13:23");
$vdot75 = array("75", "3:46", "4:04", "8:04", "8:43", "14:03", "23:10", "23:18", "29:14", "44:48", "48:19", "1:00:52", "1:04:23", "1:17:26", "1:34:09", "2:14:55");
$vdot74 = array("74", "3:49", "4:07", "8:10", "8:49", "14:13", "23:26", "23:34", "29:34", "45:19", "48:52", "1:01:34", "1:05:08", "1:18:20", "1:35:14", "2:16:29");
$vdot73 = array("73", "3:52", "4:10", "8:16", "8:55", "14:23", "23:42", "23:51", "29:55", "45:51", "49:27", "1:02:17", "1:05:54", "1:19:15", "1:36:22", "2:18:05");
$vdot72 = array("72", "3:54", "4:13", "8:22", "9:02", "14:33", "23:59", "24:08", "30:16", "46:24", "50:02", "1:03:03", "1:06:42", "1:20:13", "1:37:31", "2:19:44");
$vdot71 = array("71", "3:57", "4:16", "8:28", "9:09", "14:44", "24:16", "24:25", "30:38", "46:58", "50:39", "1:03:49", "1:07:31", "1:21:11", "1:38:42", "2:21:26");
$vdot70 = array("70", "4:00", "4:19", "8:34", "9:16", "14:55", "24:34", "24:43", "31:00", "47:32", "51:16", "1:04:36", "1:08:21", "1:22:11", "1:39:55", "2:23:10");
$vdot69 = array("69", "4:03", "4:23", "8:41", "9:23", "15:06", "24:52", "25:01", "31:23", "48:08", "51:55", "1:05:24", "1:09:12", "1:23:13", "1:41:10", "2:24:57");
$vdot68 = array("68", "4:06", "4:26", "8:48", "9:30", "15:18", "25:11", "25:20", "31:46", "48:44", "52:34", "1:06:14", "1:10:05", "1:24:16", "1:42:27", "2:26:47");
$vdot67 = array("67", "4:10", "4:30", "8:55", "9:37", "15:29", "25:30", "25:40", "32:11", "49:22", "53:15", "1:07:06", "1:11:00", "1:25:22", "1:43:46", "2:28:40");
$vdot66 = array("66", "4:13", "4:33", "9:02", "9:45", "15:42", "25:50", "25:59", "32:35", "50:00", "53:56", "1:07:59", "1:11:56", "1:26:29", "1:45:08", "2:30:36");
$vdot65 = array("65", "4:16", "4:37", "9:09", "9:53", "15:54", "26:10", "26:20", "33:01", "50:40", "54:39", "1:08:53", "1:12:53", "1:27:38", "1:46:31", "2:32:35");
$vdot64 = array("64", "4:20", "4:41", "9:17", "10:01", "16:07", "26:32", "26:41", "33:28", "51:21", "55:23", "1:09:50", "1:13:53", "1:28:49", "1:47:57", "2:34:38");
$vdot63 = array("63", "4:24", "4:45", "9:25", "10:10", "16:20", "26:53", "27:03", "33:55", "52:03", "56:09", "1:10:47", "1:14:54", "1:30:02", "1:49:26", "2:36:44");
$vdot62 = array("62", "4:27", "4:49", "9:33", "10:18", "16:34", "27:15", "27:25", "34:23", "52:47", "56:56", "1:11:47", "1:15:57", "1:31:18", "1:50:57", "2:38:54");
$vdot61 = array("61", "4:31", "4:53", "9:41", "10:27", "16:48", "27:38", "27:49", "34:52", "53:32", "57:45", "1:12:48", "1:17:02", "1:32:35", "1:52:31", "2:41:08");
$vdot60 = array("60", "4:35", "4:57", "9:50", "10:37", "17:03", "28:02", "28:13", "35:22", "54:18", "58:35", "1:13:51", "1:18:09", "1:33:55", "1:54:08", "2:43:25");
$vdot59 = array("59", "4:39", "5:02", "9:58", "10:46", "17:17", "28:26", "28:36", "35:52", "55:06", "59:26", "1:14:57", "1:19:18", "1:35:18", "1:55:48", "2:45:47");
$vdot58 = array("58", "4:44", "5:06", "10:08", "10:56", "17:33", "28:52", "29:02", "36:24", "55:55", "1:00:20", "1:16:05", "1:20:30", "1:36:44", "1:57:31", "2:48:14");
$vdot57 = array("57", "4:48", "5:11", "10:17", "11:06", "17:49", "29:18", "29:29", "36:57", "56:46", "1:01:14", "1:17:14", "1:21:43", "1:38:11", "1:59:17", "2:50:45");
$vdot56 = array("56", "4:53", "5:16", "10:27", "11:17", "18:05", "29:45", "29:55", "37:31", "57:39", "1:02:12", "1:18:26", "1:23:00", "1:39:43", "2:01:07", "2:53:20");
$vdot55 = array("55", "4:57", "5:21", "10:37", "11:28", "18:22", "30:12", "30:23", "38:06", "58:33", "1:03:10", "1:19:40", "1:24:18", "1:41:16", "2:03:00", "2:56:01");
$vdot54 = array("54", "5:02", "5:27", "10:47", "11:39", "18:40", "30:41", "30:52", "38:42", "59:30", "1:04:12", "1:20:57", "1:25:40", "1:42:53", "2:04:58", "2:58:47");
$vdot53 = array("53", "5:07", "5:32", "10:58", "11:50", "18:58", "31:11", "31:23", "39:20", "1:00:28", "1:05:14", "1:22:17", "1:27:04", "1:44:34", "2:06:59", "3:01:39");
$vdot52 = array("52", "5:13", "5:38", "11:09", "12:02", "19:17", "31:42", "31:54", "39:59", "1:01:29", "1:06:20", "1:23:39", "1:28:31", "1:46:17", "2:09:04", "3:04:36");
$vdot51 = array("51", "5:18", "5:44", "11:21", "12:15", "19:36", "32:14", "32:26", "40:39", "1:02:31", "1:07:27", "1:25:05", "1:30:02", "1:48:05", "2:11:13", "3:07:39");
$vdot50 = array("50", "5:24", "5:50", "11:33", "12:28", "19:57", "32:47", "32:59", "41:21", "1:03:36", "1:08:37", "1:26:33", "1:31:35", "1:49:56", "2:13:27", "3:10:49");
$vdot49 = array("49", "5:30", "5:56", "11:45", "12:41", "20:18", "33:22", "33:34", "42:04", "1:04:44", "1:09:50", "1:28:05", "1:33:12", "1:51:52", "2:15:47", "3:14:06");
$vdot48 = array("48", "5:36", "6:03", "11:58", "12:55", "20:39", "33:58", "34:10", "42:50", "1:05:53", "1:11:05", "1:29:40", "1:34:53", "1:53:52", "2:18:11", "3:17:29");
$vdot47 = array("47", "5:42", "6:10", "12:12", "13:10", "21:02", "34:34", "34:47", "43:36", "1:07:06", "1:12:24", "1:31:19", "1:36:38", "1:55:56", "2:20:40", "3:21:00");
$vdot46 = array("46", "5:49", "6:17", "12:26", "13:25", "21:25", "35:13", "35:26", "44:25", "1:08:22", "1:13:46", "1:33:02", "1:38:27", "1:58:06", "2:23:16", "3:24:39");
$vdot45 = array("45", "5:56", "6:25", "12:40", "13:40", "21:50", "35:54", "36:07", "45:16", "1:09:40", "1:15:10", "1:34:49", "1:40:20", "2:00:20", "2:25:57", "3:28:26");
$vdot44 = array("44", "6:03", "6:32", "12:55", "13:56", "22:15", "36:35", "36:49", "46:09", "1:11:02", "1:16:38", "1:36:40", "1:42:17", "2:02:39", "2:28:45", "3:32:23");
$vdot43 = array("43", "6:11", "6:41", "13:11", "14:13", "22:41", "37:19", "37:32", "47:04", "1:12:27", "1:18:10", "1:38:36", "1:44:20", "2:05:05", "2:31:39", "3:36:28");
$vdot42 = array("42", "6:19", "6:49", "13:28", "14:31", "23:09", "38:04", "38:18", "48:01", "1:13:56", "1:19:46", "1:40:36", "1:46:27", "2:07:35", "2:34:40", "3:40:43");
$vdot41 = array("41", "6:27", "6:58", "13:45", "14:49", "23:38", "38:52", "39:06", "49:01", "1:15:29", "1:21:26", "1:42:42", "1:48:40", "2:10:13", "2:37:49", "3:45:09");
$vdot40 = array("40", "6:35", "7:07", "14:03", "15:08", "24:08", "39:41", "39:56", "50:03", "1:17:06", "1:23:11", "1:44:53", "1:50:59", "2:12:57", "2:41:06", "3:49:45");
$vdot39 = array("39", "6:44", "7:17", "14:21", "15:29", "24:39", "40:33", "40:48", "51:09", "1:18:47", "1:24:59", "1:47:10", "1:53:24", "2:15:49", "2:44:32", "3:54:34");
$vdot38 = array("38", "6:54", "7:27", "14:41", "15:49", "25:12", "41:27", "41:42", "52:17", "1:20:33", "1:26:54", "1:49:33", "1:55:55", "2:18:48", "2:48:06", "3:59:35");
$vdot37 = array("37", "7:04", "7:38", "15:01", "16:11", "25:46", "42:24", "42:39", "53:29", "1:22:24", "1:28:53", "1:52:03", "1:58:34", "2:21:55", "2:51:51", "4:04:50");
$vdot36 = array("36", "7:14", "7:49", "15:23", "16:34", "26:22", "43:23", "43:39", "54:44", "1:24:20", "1:30:58", "1:54:40", "2:01:19", "2:25:11", "2:55:45", "4:10:19");
$vdot35 = array("35", "7:25", "8:01", "15:45", "16:58", "27:00", "44:26", "44:42", "56:03", "1:26:22", "1:33:09", "1:57:24", "2:04:13", "2:28:36", "2:59:51", "4:16:03");
$vdot34 = array("34", "7:37", "8:14", "16:09", "17:24", "27:39", "45:31", "45:48", "57:26", "1:28:30", "1:35:27", "2:00:17", "2:07:16", "2:32:12", "3:04:08", "4:22:03");
$vdot33 = array("33", "7:49", "8:27", "16:33", "17:50", "28:21", "46:41", "46:58", "58:54", "1:30:45", "1:37:52", "2:03:18", "2:10:27", "2:35:58", "3:08:39", "4:28:22");
$vdot32 = array("32", "8:02", "8:41", "16:59", "18:18", "29:05", "47:54", "48:11", "1:00:26", "1:33:07", "1:40:25", "2:06:29", "2:13:49", "2:39:56", "3:13:23", "4:34:59");
$vdot31 = array("31", "8:15", "8:55", "17:27", "18:48", "29:51", "49:10", "49:28", "1:02:03", "1:35:36", "1:43:05", "2:09:50", "2:17:21", "2:44:06", "3:18:22", "4:41:57");
$vdot30 = array("30", "8:30", "9:11", "17:56", "19:19", "30:40", "50:32", "50:50", "1:03:46", "1:38:14", "1:45:55", "2:13:21", "2:21:04", "2:48:29", "3:23:37", "4:49:17");
?>

<?php 
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent
		$vdot_distance = $_POST['runnerslog_vdot_distance'];
		$vdot_time = $_POST['runnerslog_vdot_time'];
		update_option('runnerslog_vdot_distance', $vdot_distance);
		update_option('runnerslog_vdot_time', $vdot_time);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$vdot_distance = get_option('runnerslog_vdot_distance');
		$vdot_time = get_option('runnerslog_vdot_time');
	}
?>

<p>This calculator use the VDOT tables found in <a href="http://www.amazon.co.uk/gp/product/0736054928/" target=_blank">Daniels' Running Formula - 2<sup>nd</sup> Edition.</a></p>
<h3>By using standard values for running economy and by having a timed Performance over at least one running distance, a fitness ("VDOT") value can be assigned to you for training and race-prediction purposes.</br>
VDOT is an adjusted V02max (which may or may not match a laboratory-generated V02max), which tells you how you might race for other distances associated with the same VDOT.</br>
A longer race (15K to 25K, for example) will usually be a better marathon predictor than would be a 1-mile or 5K race.</h3>

<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_vdot_distance"><?php _e('Distance:') ?></label></th>
				<td><?php
					if ( $distancetype == meters ) {
						echo '<input name="runnerslog_vdot_distance" type="text" id="runnerslog_vdot_distance"  value="', form_option('runnerslog_vdot_distance'), '" class="small-text" />';
						echo '<span class="description"> Meters (eg. 2500)</span>';
							} else {
						echo '<input name="runnerslog_vdot_distance" type="text" id="runnerslog_vdot_distance"  value="', form_option('runnerslog_vdot_distance'), '" class="small-text" />';
						echo '<span class="description"> Miles (eg. 1.58)</span>';
					}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_vdot_time"><?php _e('Time:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_vdot_time" type="text" id="runnerslog_vdot_time"  value="', form_option('runnerslog_vdot_time'), '" size="7" maxlength="8" />';
						echo '<span class="description"> Must be formated as hh:mm:ss like 01:37:45 for 1 hour 37min and 45sec</span>';
					?>
				</td>
			</tr>				
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Calculate VDOT-value', 'runnerslog_ops' ) ?>" />
	</p>
</form>

<?php

//First we convert the time to second
$hms = $vdot_time;

function hms2secv2($hms) {
	list($h, $m, $s) = explode (":", $hms);
  if (strlen($hms) <= 5) {
	$seconds = 0;
	$seconds += (intval($h) * 60);
	$seconds += (intval($m));
	$seconds += (intval($s));
	return $seconds;
		} else {
	$seconds = 0;
	$seconds += (intval($h) * 3600);
	$seconds += (intval($m) * 60);
	$seconds += (intval($s));
	return $seconds;
	}
}
// Call the function
$seconds = hms2secv2($hms);

function sec2hmsv2($sec, $padHours = false) {
    $hms = "";
    $minutes = intval(($sec / 60) % 60); 
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
    $seconds = intval($sec % 60); 
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
    return $hms;  
}

//we now calculate the velocity based on the time in seconds
if ($vdot_distance && $seconds) {
	$velocity = $vdot_distance/($seconds/60);
}

//Let us calculate the VDOT
if ($distancetype == meters) {
	$vdot = ROUND((-4.60+(0.182258*$velocity)+(0.000104*($velocity*$velocity)))/(0.8+(0.1894393*exp(-0.012778*($seconds/60)))+(0.2989558*exp(-0.1932605*($seconds/60)))),1);
	$roundvdot = ROUND($vdot,0);
	$pacelang = 'min/km';
		} else {
	$vdot = ROUND((-4.60+(0.182258*($velocity*1609.344))+(0.000104*(($velocity*1609.344)*($velocity*1609.344))))/(0.8+(0.1894393*exp(-0.012778*($seconds/60)))+(0.2989558*exp(-0.1932605*($seconds/60)))),1);
	$roundvdot = ROUND($vdot,0);
	$pacelang = 'min/mile';
}
	
if ( $vdot < 30 or $vdot >85 ) {
	echo 'Sorry, but this calculator only supports a VDOT value between 30 and 85. The calculated VDOT is: <b>',$vdot,'</b>';
  } else {
	echo '<p>The calculated VDOT is: <b>',$vdot, '</b> and the equivalent race performancens is:</p>';
	echo'<table border="0" cellpadding="2" cellspacing="0" style="width: 250px;">
	<thead>
		<tr>
			<th style="background-color: rgb(169, 169, 169)"; ><b>Distance</b></th>
			<th style="background-color: rgb(169, 169, 169)"; ><b>Time</b></th>
			<th style="background-color: rgb(169, 169, 169)"; ><b>Pace ('.$pacelang.')</b></th>
		</tr>
	</thead>
	<tbody>
		<tr style="background-color: rgb(252, 208, 195);">
			<td>1500 m</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[1].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[1])/$vdotdistance[1]) .'</td>
		</tr>
		<tr style="background-color: rgb(244, 248, 203);">
			<td>1 Mile</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[2].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[2])/$vdotdistance[2]) .'</td>
		</tr>
		<tr style="background-color: rgb(194, 237, 253);">
			<td>3K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[3].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[3])/$vdotdistance[3]) .'</td>
		</tr>
		<tr style="background-color: rgb(194, 237, 253);">
			<td>2 Mile</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[4].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[4])/$vdotdistance[4]) .'</td>
		</tr>
		<tr style="background-color: rgb(193, 215, 254);">
			<td>5K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[5].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[5])/$vdotdistance[5]) .'</td>
		</tr>
		<tr style="background-color: rgb(193, 215, 254);">
			<td>8K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[6].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[6])/$vdotdistance[6]) .'</td>
		</tr>
		<tr style="background-color: rgb(200, 201, 247);">
			<td>5 Mile</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[7].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[7])/$vdotdistance[7]) .'</td>
		</tr>
		<tr style="background-color: rgb(200, 201, 247);">
			<td>10K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[8].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[8])/$vdotdistance[8]) .'</td>
		</tr>
		<tr style="background-color: rgb(200, 201, 247);">
			<td>15K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[9].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[9])/$vdotdistance[9]) .'</td>
		</tr>
		<tr style="background-color: rgb(204, 198, 243);">
			<td>10 Mile</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[10].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[10])/$vdotdistance[10]) .'</td>
		</tr>
		<tr style="background-color: rgb(209, 195, 238);">
			<td>20K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[11].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[11])/$vdotdistance[11]) .'</td>
		</tr>
		<tr style="background-color: rgb(209, 195, 238);">
			<td>&frac12;Marathon</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[12].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[12])/$vdotdistance[12]) .'</td>
		</tr>
		<tr style="background-color: rgb(209, 195, 238);">
			<td>25K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[13].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[13])/$vdotdistance[13]) .'</td>
		</tr>
		<tr style="background-color: rgb(214, 193, 233);">
			<td>30K</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[14].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[14])/$vdotdistance[14]) .'</td>
		</tr>
		<tr style="background-color: rgb(214, 193, 228);">
			<td>Marahon</td>
			<td style="text-align: center"; >'.${"vdot$roundvdot"}[15].'</td>
			<td style="text-align: right"; >'. sec2hmsv2(hms2secv2(${"vdot$roundvdot"}[15])/$vdotdistance[15]) .'</td>
		</tr>
	</tbody>
</table>';
}
?>
</div>