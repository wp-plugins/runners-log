<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log HR Training Zones Calculator' ) . "</h2>"; ?>
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
echo '<p>Based on a Resting Heart Rate at: <b>'.$hrrest.'</b> and a Maximum Heart Rate at: <b>' .$hrmax.'</b> the commanded training zones are:</p>';
echo '
<h2>
	Heart Rate Training Zones (Beginner)</h2>
<table cellpadding="0" cellspacing="0" class="cooper" style="width: 700px;">
	<tbody>
		<tr>
			<td style="background-color: rgb(128, 128, 128); width: 70px; text-align: center;">
				<strong>Pulse Zone</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 60px; text-align: center;">
				<strong>Pulse</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 70px; text-align: center;">
				<strong>% of HRR<br />
				</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 120px; text-align: center;">
				<strong>Type</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 380px; text-align: center;">
				<strong>What it does<br />
				</strong></td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 0); width: 70px; text-align: center;">
				<strong>1</strong></td>
			<td style="background-color: rgb(255, 255, 0); width: 60px; text-align: center;">
				'.$hrr50.'-'.$hrr60.'</td>
			<td style="background-color: rgb(255, 255, 0); width: 70px; text-align: center;">
				50-60%</td>
			<td style="background-color: rgb(255, 255, 0); width: 120px; text-align: center;">
				Moderate activity</td>
			<td style="background-color: rgb(255, 255, 0); width: 380px; text-align: center;">
				Maintenance or warm up</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 204, 0); width: 70px; text-align: center;">
				<strong>2</strong></td>
			<td style="background-color: rgb(255, 204, 0); width: 60px; text-align: center;">
				'.$hrr60.'-'.$hrr70.'</td>
			<td style="background-color: rgb(255, 204, 0); width: 70px; text-align: center;">
				60-70%</td>
			<td style="background-color: rgb(255, 204, 0); width: 120px; text-align: center;">
				The Energy Efficient or Recovery Zone</td>
			<td style="background-color: rgb(255, 204, 0); width: 380px; text-align: center;">
				Training within this zone develops basic endurance and aerobic capacity.</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 153, 0); width: 70px; text-align: center;">
				<strong>3</strong></td>
			<td style="background-color: rgb(255, 153, 0); width: 60px; text-align: center;">
				'.$hrr70.'-'.$hrr80.'</td>
			<td style="background-color: rgb(255, 153, 0); width: 70px; text-align: center;">
				70-80%</td>
			<td style="background-color: rgb(255, 153, 0); width: 120px; text-align: center;">
				The Aerobic Zone</td>
			<td style="background-color: rgb(255, 153, 0); width: 380px; text-align: center;">
				Training in this zone will develop your cardiovascular system (Also Known As: cardio zone). Train the ability to transport oxygen to, and carbon dioxide away from, the working muscles.</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 51, 0); width: 70px; text-align: center;">
				<strong>4</strong></td>
			<td style="background-color: rgb(255, 51, 0); width: 60px; text-align: center;">
				'.$hrr80.'-'.$hrr90.'</td>
			<td style="background-color: rgb(255, 51, 0); width: 70px; text-align: center;">
				80-90%</td>
			<td style="background-color: rgb(255, 51, 0); width: 120px; text-align: center;">
				The Anaerobic Zone</td>
			<td style="background-color: rgb(255, 51, 0); width: 380px; text-align: center;">
				Training in this zone will develop your lactic acid system. The point at which the body cannot remove lactic acid as quickly as it is produced is called the lactate threshold (LT) or anaerobic threshold (AT).</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 0, 0); width: 70px; text-align: center;">
				<strong>5</strong></td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				'.$hrr90.'-'.$hrr100.'</td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				90-100%</td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				VO<sub>2</sub>max or &quot;Red line zone&quot;</td>
			<td style="background-color: rgb(255, 0, 0); width: 380px; text-align: center;">
				It effectively trains your fast twitch muscle fibres and helps to develop speed. This zone is reserved for interval running.</td>
		</tr>
	</tbody>
</table>
<br />
<h2>
	Heart Rate Training Zones (Elite)</h2>
<table cellpadding="0" cellspacing="0" class="cooper" style="width: 700px;">
	<tbody>
		<tr>
			<td style="background-color: rgb(128, 128, 128); width: 70px; text-align: center;">
				<strong>Pulse Zone</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 60px; text-align: center;">
				<strong>Pulse</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 70px; text-align: center;">
				<strong>% of HRR<br />
				</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 120px; text-align: center;">
				<strong>Type</strong></td>
			<td style="background-color: rgb(128, 128, 128); width: 380px; text-align: center;">
				<strong>What it does<br />
				</strong></td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 0); width: 70px; text-align: center;">
				<strong>1</strong></td>
			<td style="background-color: rgb(255, 255, 0); width: 60px; text-align: center;">
				'.$hrr55.'-'.$hrr65.'</td>
			<td style="background-color: rgb(255, 255, 0); width: 70px; text-align: center;">
				50-60%</td>
			<td style="background-color: rgb(255, 255, 0); width: 120px; text-align: center;">
				Moderate activity</td>
			<td style="background-color: rgb(255, 255, 0); width: 380px; text-align: center;">
				Maintenance or warm up</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 204, 0); width: 70px; text-align: center;">
				<strong>2</strong></td>
			<td style="background-color: rgb(255, 204, 0); width: 60px; text-align: center;">
				'.$hrr65.'-'.$hrr75.'</td>
			<td style="background-color: rgb(255, 204, 0); width: 70px; text-align: center;">
				60-70%</td>
			<td style="background-color: rgb(255, 204, 0); width: 120px; text-align: center;">
				The Energy Efficient or Recovery Zone</td>
			<td style="background-color: rgb(255, 204, 0); width: 380px; text-align: center;">
				Training within this zone develops basic endurance and aerobic capacity.</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 153, 0); width: 70px; text-align: center;">
				<strong>3</strong></td>
			<td style="background-color: rgb(255, 153, 0); width: 60px; text-align: center;">
				'.$hrr75.'-'.$hrr85.'</td>
			<td style="background-color: rgb(255, 153, 0); width: 70px; text-align: center;">
				70-80%</td>
			<td style="background-color: rgb(255, 153, 0); width: 120px; text-align: center;">
				The Aerobic Zone</td>
			<td style="background-color: rgb(255, 153, 0); width: 380px; text-align: center;">
				Training in this zone will develop your cardiovascular system (Also Known As: cardio zone). Train the ability to transport oxygen to, and carbon dioxide away from, the working muscles.</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 51, 0); width: 70px; text-align: center;">
				<strong>4</strong></td>
			<td style="background-color: rgb(255, 51, 0); width: 60px; text-align: center;">
				'.$hrr85.'-'.$hrr95.'</td>
			<td style="background-color: rgb(255, 51, 0); width: 70px; text-align: center;">
				80-90%</td>
			<td style="background-color: rgb(255, 51, 0); width: 120px; text-align: center;">
				The Anaerobic Zone</td>
			<td style="background-color: rgb(255, 51, 0); width: 380px; text-align: center;">
				Training in this zone will develop your lactic acid system. The point at which the body cannot remove lactic acid as quickly as it is produced is called the lactate threshold (LT) or anaerobic threshold (AT).</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 0, 0); width: 70px; text-align: center;">
				<strong>5</strong></td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				'.$hrr95.'-'.$hrr100.'</td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				90-100%</td>
			<td style="background-color: rgb(255, 0, 0); width: 60px; text-align: center;">
				VO<sub>2</sub>max or &quot;Red line zone&quot;</td>
			<td style="background-color: rgb(255, 0, 0); width: 380px; text-align: center;">
				It effectively trains your fast twitch muscle fibres and helps to develop speed. This zone is reserved for interval running.</td>
		</tr>
	</tbody>
</table>';
		} else {
	echo '<p>To calculate <b>YOUR</b> traing zones you have to type in your resting pulse and max pulse in Runners Log Settings.</p>';	
}
?>
</div>