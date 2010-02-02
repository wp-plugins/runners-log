<?php 
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent
		$distancetype = $_POST['runnerslog_distancetype'];
		$pulsavg = $_POST['runnerslog_pulsavg'];
		$garminconnect = $_POST['runnerslog_garminconnectlink'];
		$calories = $_POST['runnerslog_caloriescount'];
		$hrrest = $_POST['runnerslog_hrrest'];
		$hrmax = $_POST['runnerslog_hrmax'];
		update_option('runnerslog_distancetype', $distancetype);
		update_option('runnerslog_pulsavg', $pulsavg);		
		update_option('runnerslog_garminconnectlink', $garminconnect);
		update_option('runnerslog_caloriescount', $calories);
		update_option('runnerslog_hrrest', $hrrest);
		update_option('runnerslog_hrmax', $hrmax);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$distancetype = get_option('runnerslog_distancetype');
		$pulsavg = get_option('runnerslog_pulsavg');
		$garminconnect = get_option('runnerslog_garminconnectlink');
		$calories = get_option('runnerslog_caloriescount');
		$hrrest = $_POST['runnerslog_hrrest'];
		$hrmax = $_POST['runnerslog_hrmax'];
	}
?>

<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log Options', 'runnerslog_ops' ) . "</h2>"; ?>
<p>Set the options below.</p>

<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_distancetype"><?php _e('Distance type') ?></label></th>
				<td>
				<?php
				$type = '<select name="runnerslog_distancetype" id="runnerslog_distancetype" style="width: 100px;"><option value="meters"';
				if ( 'meters' == get_option('runnerslog_distancetype') ) $type .= ' selected="selected"';
				$type .= '>' . __('meters') . '</option><option value="miles"';
				if ( 'miles' == get_option('runnerslog_distancetype') ) $type .= ' selected="selected"';
				$type .= '>' . __('miles') . '</option></select>';
				echo $type;
				?>
				</td>
			</tr>
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_pulsavg">
				<input name="runnerslog_pulsavg" id="runnerslog_pulsavg" value="1"<?php checked('1', get_option('runnerslog_pulsavg')); ?> type="checkbox">
				<?php _e('Enable Pulse Average?') ?></label>
				</th>
			</tr>			
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_garminconnectlink">
				<input name="runnerslog_garminconnectlink" id="runnerslog_garminconnectlink" value="1"<?php checked('1', get_option('runnerslog_garminconnectlink')); ?> type="checkbox">
				<?php _e('Enable Garmin.connect Link?') ?></label>
				</th>
			</tr>
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_caloriescount">
				<input name="runnerslog_caloriescount" id="runnerslog_caloriescount" value="1"<?php checked('1', get_option('runnerslog_caloriescount')); ?> type="checkbox">
				<?php _e('Enable Calories Count?') ?></label>
				</th>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_hrrest"><?php _e('Resting Heart Rate') ?></label></th>
				<td>
					<input name="runnerslog_hrrest" type="text" id="runnerslog_hrrest"  value="<?php form_option('runnerslog_hrrest'); ?>" class="small-text" />
					<span class="description"><?php _e('Resting Heart Rate is the number of times our heart beats, when we are at completely at rest.') ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_hrmax"><?php _e('Maximum Heart Rate') ?></label></th>
				<td>
					<input name="runnerslog_hrmax" type="text" id="runnerslog_hrmax"  value="<?php form_option('runnerslog_hrmax'); ?>" class="small-text" />
					<span class="description"><?php _e('Maximum Heart Rate is the highest number of times your heart can contract in one minute.') ?></span>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save and update options', 'runnerslog_ops' ) ?>" />
	</p>
</form>
<?php 
//Get the hrrest and hrmax
$hrrest = get_option('runnerslog_hrrest');
$hrmax = get_option('runnerslog_hrmax');

//Let us calculate the HR limits
//We use the formular: Target Heart Rate = ((Maximum Heart Rate – Resting Heart Rate) × %Intensity) + Resting Heart Rate
$hrr50 = ROUND((($hrmax-$hrrest)*0.5)+$hrrest,0);  	// 50% of HRR
$hrr55 = ROUND((($hrmax-$hrrest)*0.55)+$hrrest,0);  // 55% of HRR
$hrr60 = ROUND((($hrmax-$hrrest)*0.6)+$hrrest,0);	// 60% of HRR
$hrr65 = ROUND((($hrmax-$hrrest)*0.65)+$hrrest,0);  // 65% of HRR
$hrr70 = ROUND((($hrmax-$hrrest)*0.7)+$hrrest,0);	// 70% of HRR
$hrr75 = ROUND((($hrmax-$hrrest)*0.75)+$hrrest,0);  // 75% of HRR
$hrr80 = ROUND((($hrmax-$hrrest)*0.8)+$hrrest,0);	// 80% of HRR
$hrr85 = ROUND((($hrmax-$hrrest)*0.85)+$hrrest,0);  // 85% of HRR
$hrr90 = ROUND((($hrmax-$hrrest)*0.9)+$hrrest,0);	// 90% of HRR
$hrr95 = ROUND((($hrmax-$hrrest)*0.95)+$hrrest,0);  // 95% of HRR
$hrr100 = ROUND((($hrmax-$hrrest)*1)+$hrrest,0);	// 100% of HRR

if ( $hrrest AND $hrmax ) {
echo '
<br />
<h1>Heart Rate Training Zones (Beginner)</h1>
<table>
	<tr>
		<td style="width: 70px; text-align: center;">Pulse zone</td>
		<td style="width: 60px; text-align: center;">Pulse</td>
		<td style="width: 180px; text-align: center;">% of Heart Rate Reserve</td>
		<td style="width: 150px; text-align: center;">Type</td>
		<td style="text-align: left;">What it does</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">1</td>
		<td style="width: 60px; text-align: center;">'.$hrr50.'-'.$hrr60.'</td>
		<td style="text-align: center;">50-60%</td>
		<td style="width: 100px; text-align: center;">Moderate activity</td>
		<td style="text-align: left;">Maintenance or warm up</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">2</td>
		<td style="width: 60px; text-align: center;">'.$hrr60.'-'.$hrr70.'</td>
		<td style="text-align: center;">60-70%</td>
		<td style="width: 100px; text-align: center;">The Energy Efficient or Recovery Zone</td>
		<td style="text-align: left;">Training within this zone develops basic endurance and aerobic capacity.</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">3</td>
		<td style="width: 60px; text-align: center;">'.$hrr70.'-'.$hrr80.'</td>
		<td style="text-align: center;">70-80%</td>
		<td style="width: 100px; text-align: center;">The Aerobic Zone</td>
		<td style="text-align: left;">Training in this zone will develop your cardiovascular system (Also Known As: cardio zone). Train the ability to transport oxygen to, and carbon dioxide away from, the working muscles.</td>
	</tr>	
	<tr>
		<td style="width: 70px; text-align: center;">4</td>
		<td style="width: 60px; text-align: center;">'.$hrr80.'-'.$hrr90.'</td>
		<td style="text-align: center;">80-90%</td>
		<td style="width: 100px; text-align: center;">The Anaerobic Zone</td>
		<td style="text-align: left;">Training in this zone will develop your lactic acid system. The point at which the body cannot remove lactic acid as quickly as it is produced is called the lactate threshold (LT) or anaerobic threshold (AT).</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">5</td>
		<td style="width: 60px; text-align: center;">'.$hrr90.'-'.$hrr100.'</td>
		<td style="text-align: center;">90-100%</td>
		<td style="width: 100px; text-align: center;">VO<sub>2</sub>max or "Red line zone"</td>
		<td style="text-align: left;">It effectively trains your fast twitch muscle fibres and helps to develop speed. This zone is reserved for interval running.</td>
	</tr>	
</table>
<br />
<h1>Heart Rate Training Zones (Elite)</h1>
<table>
	<tr>
		<td style="width: 70px; text-align: center;">Pulse zone</td>
		<td style="width: 60px; text-align: center;">Pulse</td>
		<td style="width: 180px; text-align: center;">% of Heart Rate Reserve</td>
		<td style="width: 150px; text-align: center;">Type</td>
		<td style="text-align: left;">What it does</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">1</td>
		<td style="width: 60px; text-align: center;">'.$hrr55.'-'.$hrr65.'</td>
		<td style="text-align: center;">50-60%</td>
		<td style="width: 100px; text-align: center;">Moderate activity</td>
		<td style="text-align: left;">Maintenance or warm up</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">2</td>
		<td style="width: 60px; text-align: center;">'.$hrr65.'-'.$hrr75.'</td>
		<td style="text-align: center;">60-70%</td>
		<td style="width: 100px; text-align: center;">The Energy Efficient or Recovery Zone</td>
		<td style="text-align: left;">Training within this zone develops basic endurance and aerobic capacity.</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">3</td>
		<td style="width: 60px; text-align: center;">'.$hrr75.'-'.$hrr85.'</td>
		<td style="text-align: center;">70-80%</td>
		<td style="width: 100px; text-align: center;">The Aerobic Zone</td>
		<td style="text-align: left;">Training in this zone will develop your cardiovascular system (Also Known As: cardio zone). Train the ability to transport oxygen to, and carbon dioxide away from, the working muscles.</td>
	</tr>	
	<tr>
		<td style="width: 70px; text-align: center;">4</td>
		<td style="width: 60px; text-align: center;">'.$hrr85.'-'.$hrr95.'</td>
		<td style="text-align: center;">80-90%</td>
		<td style="width: 100px; text-align: center;">The Anaerobic Zone</td>
		<td style="text-align: left;">Training in this zone will develop your lactic acid system. The point at which the body cannot remove lactic acid as quickly as it is produced is called the lactate threshold (LT) or anaerobic threshold (AT).</td>
	</tr>
	<tr>
		<td style="width: 70px; text-align: center;">5</td>
		<td style="width: 60px; text-align: center;">'.$hrr95.'-'.$hrr100.'</td>
		<td style="text-align: center;">90-100%</td>
		<td style="width: 100px; text-align: center;">VO<sub>2</sub>max or "Red line zone"</td>
		<td style="text-align: left;">It effectively trains your fast twitch muscle fibres and helps to develop speed. This zone is reserved for interval running.</td>
	</tr>	
</table>';
}
?>
</div>