<?php 
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent
		$distancetype = $_POST['runnerslog_distancetype'];
		$unittype = $_POST['runnerslog_unittype'];
		$gender = $_POST['runnerslog_gender'];
		$pulsavg = $_POST['runnerslog_pulsavg'];
		$garminconnect = $_POST['runnerslog_garminconnectlink'];
		$calories = $_POST['runnerslog_caloriescount'];
		$heightcm = $_POST['runnerslog_cm'];
		$heightfeets = $_POST['runnerslog_feets'];
		$heightinches = $_POST['runnerslog_inches'];
		$weight = $_POST['runnerslog_weight'];
		$age = $_POST['runnerslog_age'];
		$hrrest = $_POST['runnerslog_hrrest'];
		$hrmax = $_POST['runnerslog_hrmax'];
		update_option('runnerslog_distancetype', $distancetype);
		update_option('runnerslog_unittype', $unittype);
		update_option('runnerslog_gender', $gender);
		update_option('runnerslog_pulsavg', $pulsavg);		
		update_option('runnerslog_garminconnectlink', $garminconnect);		
		update_option('runnerslog_caloriescount', $calories);
		update_option('runnerslog_cm', $heightcm);
		update_option('runnerslog_feets', $heightfeets);
		update_option('runnerslog_inches', $heightinches);
		update_option('runnerslog_weight', $weight);
		update_option('runnerslog_age', $age);
		update_option('runnerslog_hrrest', $hrrest);
		update_option('runnerslog_hrmax', $hrmax);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$distancetype = get_option('runnerslog_distancetype');
		$unittype = get_option('runnerslog_unittype');
		$gender = get_option('runnerslog_gender');
		$pulsavg = get_option('runnerslog_pulsavg');
		$garminconnect = get_option('runnerslog_garminconnectlink');
		$calories = get_option('runnerslog_caloriescount');
		$heightcm = get_option('runnerslog_cm');
		$heightfeets = get_option('runnerslog_feets');
		$heightinches = get_option('runnerslog_inches');
		$weight = get_option('runnerslog_weight');
		$age = get_option('runnerslog_age');
		$hrrest = get_option('runnerslog_hrrest');
		$hrmax = get_option('runnerslog_hrmax');
	}
?>

<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log Options', 'runnerslog_ops' ) . "</h2>"; ?>
<p>Set the options below.</p>

<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>

			<!-- Distance type: runnerslog_distancetype -->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_distancetype"><?php _e('Distance type') ?></label></th>
				<td>
				<?php
				$type = '<select name="runnerslog_distancetype" id="runnerslog_distancetype" style="width: 100px;"><option value="meters"';
				if ( 'meters' == get_option('runnerslog_distancetype') ) $type .= ' selected="selected"';
				$type .= '>' . __('Meters') . '</option><option value="miles"';
				if ( 'miles' == get_option('runnerslog_distancetype') ) $type .= ' selected="selected"';
				$type .= '>' . __('Miles') . '</option></select>';
				echo $type;
				?>
				<span class="description">Meters versus Miles</span>
				</td>
			</tr>
			
			<!-- Unit type: runnerslog_unittype -->	
			<tr valign="top">
				<th scope="row"><label for="runnerslog_unittype"><?php _e('Unit type') ?></label></th>
				<td>
				<?php
				$type = '<select name="runnerslog_unittype" id="runnerslog_unittype" style="width: 100px;"><option value="metric"';
				if ( 'metric' == get_option('runnerslog_unittype') ) $type .= ' selected="selected"';
				$type .= '>' . __('Metric') . '</option><option value="english"';
				if ( 'english' == get_option('runnerslog_unittype') ) $type .= ' selected="selected"';
				$type .= '>' . __('English') . '</option></select>';
				echo $type;
				?>
				<span class="description">Kg and centimeters versus pounds, feet and inches</span>
				</td>
			</tr>
			
			<!-- Gender: runnerslog_gender -->	
			<tr valign="top">
				<th scope="row"><label for="runnerslog_gender"><?php _e('Gender') ?></label></th>
				<td>
				<?php
				$type = '<select name="runnerslog_gender" id="runnerslog_gender" style="width: 100px;"><option value="male"';
				if ( 'male' == get_option('runnerslog_gender') ) $type .= ' selected="selected"';
				$type .= '>' . __('Male') . '</option><option value="female"';
				if ( 'female' == get_option('runnerslog_gender') ) $type .= ' selected="selected"';
				$type .= '>' . __('Female') . '</option></select>';
				echo $type;
				?>
				<span class="description">Choose your gender</span>
				</td>
				</td>
			</tr>
			
			<!-- Enable Pulse Average: runnerslog_pulsavg -->
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_pulsavg">
				<input name="runnerslog_pulsavg" id="runnerslog_pulsavg" value="1"<?php checked('1', get_option('runnerslog_pulsavg')); ?> type="checkbox">
				<?php _e('Enable Pulse Average') ?></label>
				</th>
			</tr>

			<!-- Enable Link to Garmin Connect: runnerslog_garminconnectlink -->
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_garminconnectlink">
				<input name="runnerslog_garminconnectlink" id="runnerslog_garminconnectlink" value="1"<?php checked('1', get_option('runnerslog_garminconnectlink')); ?> type="checkbox">
				<?php _e('Enable Link to Garmin Connect') ?></label>
				</th>
			</tr>
			
			<!-- Enable Calories Count: runnerslog_caloriescount -->
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_caloriescount">
				<input name="runnerslog_caloriescount" id="runnerslog_caloriescount" value="1"<?php checked('1', get_option('runnerslog_caloriescount')); ?> type="checkbox">
				<?php _e('Enable Calories Count') ?></label>
				</th>
			</tr>
			
			<!-- Your Height: If metric: runnerslog_cm  If english: runnerslog_feets AND runnerslog_inches-->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_height"><?php _e('Your Height') ?></label></th>
				<td><?php
					if ( $unittype == metric ) {
						echo '<input name="runnerslog_cm" type="text" id="runnerslog_cm"  value="', form_option('runnerslog_cm'), '" class="small-text" />';
						echo '<span class="description"> Centimeters</span>';
							} else {
						echo '<input name="runnerslog_feets" type="text" id="runnerslog_feets"  value="', form_option('runnerslog_feets'), '" class="small-text" />';
						echo '<span class="description"> Feet </span>';
						echo '<input name="runnerslog_inches" type="text" id="runnerslog_inches"  value="', form_option('runnerslog_inches'), '" class="small-text" />';
						echo '<span class="description"> Inc(es)</span>';
					}
					?>
				</td>
			</tr>
			
			<!-- Your Weight: runnerslog_weight -->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_weight"><?php _e('Your Weight') ?></label></th>
				<td>
					<input name="runnerslog_weight" type="text" id="runnerslog_weight"  value="<?php form_option('runnerslog_weight'); ?>" class="small-text" />
					<span class="description"><?php 
													// print descript as either kg or pounds based on metric or english unittype
													if ( $unittype == metric ) {
														_e('Kilograms') ;														;
													} else {
														_e('Pounds. Note: 8 ounces = 0.5 pounds') ; 
													}
											  ?></span>
				</td>
			</tr>
			
			<!-- Your age: runnerslog_age -->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_age"><?php _e('Your age') ?></label></th>
				<td>
					<input name="runnerslog_age" type="text" id="runnerslog_age"  value="<?php form_option('runnerslog_age'); ?>" class="small-text" />
					<span class="description"><?php _e('Years') ?></span>
				</td>
			</tr>
			
			<!-- Resting Heart Rate: runnerslog_hrrest -->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_hrrest"><?php _e('Resting Heart Rate') ?></label></th>
				<td>
					<input name="runnerslog_hrrest" type="text" id="runnerslog_hrrest"  value="<?php form_option('runnerslog_hrrest'); ?>" class="small-text" />
					<span class="description"><?php _e('"Resting Heart Rate" is the number of times our heart beats when we are at completely at rest.') ?></span>
				</td>
			</tr>
			
			<!-- Maximum Heart Rate: runnerslog_hrmax -->
			<tr valign="top">
				<th scope="row"><label for="runnerslog_hrmax"><?php _e('Maximum Heart Rate') ?></label></th>
				<td>
					<input name="runnerslog_hrmax" type="text" id="runnerslog_hrmax"  value="<?php form_option('runnerslog_hrmax'); ?>" class="small-text" />
					<span class="description"><?php _e('"Maximum Heart Rate" is the highest number of times your heart can contract in one minute.') ?></span>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save and update options', 'runnerslog_ops' ) ?>" />
	</p>
</form>
</div>