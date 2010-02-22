﻿<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log Coverter Toolbox', 'runnerslog_ops' ) . "</h2>"; ?>

<?php
//We have to be sure that we have the variables needed for the calculations
$convert_distance = get_option('runnerslog_convert_distance');
$convert_time = get_option('runnerslog_convert_time');
$convert_min = get_option('runnerslog_convert_min');
$convert_sec = get_option('runnerslog_convert_sec');
$convert_speedperhour = get_option('runnerslog_convert_speedperhour');
$distancetype = get_option('runnerslog_distancetype');
?>

<?php 
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent
		$convert_distance = $_POST['runnerslog_convert_distance'];
		$convert_time = $_POST['runnerslog_convert_time'];
		$convert_min = $_POST['runnerslog_convert_min'];
		$convert_sec = $_POST['runnerslog_convert_sec'];
		$convert_speedperhour = $_POST['runnerslog_convert_speedperhour'];
		update_option('runnerslog_convert_distance', $convert_distance);
		update_option('runnerslog_convert_time', $convert_time);
		update_option('runnerslog_convert_min', $convert_min);
		update_option('runnerslog_convert_sec', $convert_sec);
		update_option('runnerslog_convert_speedperhour', $convert_speedperhour);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$convert_distance = get_option('runnerslog_convert_distance');
		$convert_time = get_option('runnerslog_convert_time');
		$convert_min = get_option('runnerslog_convert_min');
		$convert_sec = get_option('runnerslog_convert_sec');
		$convert_speedperhour = get_option('runnerslog_convert_speedperhour');
	}

$hms = $convert_time;

function sec2hmsdiff($sec, $useColon = false)
{
	// holds formatted string
	$hms = "";
	
	// there are 3600 seconds in an hour, so if we
	// divide total seconds by 3600 and throw away
	// the remainder, we've got the number of hours
	$hours = intval(intval($sec) / 3600); 
	
	// add to $hms, with a leading 0 if asked for
	if ($hours > 0){
		$hms .= ($useColon) 
		      ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
		      : $hours. 'hours';
	}elseif ($useColon){
		$hms .= '00:';	
	}
	 
	// dividing the total seconds by 60 will give us
	// the number of minutes, but we're interested in 
	// minutes past the hour: to get that, we need to 
	// divide by 60 again and keep the remainder
	$minutes = intval(($sec / 60) % 60); 
	
	// then add to $hms (with a leading 0 if needed)
	if ($minutes > 0)
	$hms .= ($useColon) 
		      ? str_pad($minutes, 2, "0", STR_PAD_LEFT). ':'
		      : $minutes. 'min';
	
	// seconds are simple - just divide the total
	// seconds by 60 and keep the remainder
	$seconds = intval($sec % 60); 
	
	// add to $hms, again with a leading 0 if needed
	$hms .= ($useColon) 
		      ? str_pad($seconds, 2, "0", STR_PAD_LEFT)
		      : $seconds. 'sec';
	
	return $hms;
}
?>
<h2>Calculate Speed</h2>
<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_distance"><?php _e('Distance:') ?></label></th>
				<td><?php
					if ( $distancetype == meters ) {
						echo '<input name="runnerslog_convert_distance" type="text" id="runnerslog_convert_distance"  value="', form_option('runnerslog_convert_distance'), '" class="small-text" />';
						echo '<span class="description"> meters</span>';
							} else {
						echo '<input name="runnerslog_convert_distance" type="text" id="runnerslog_convert_distance"  value="', form_option('runnerslog_convert_distance'), '" class="small-text" />';
						echo '<span class="description"> miles (eg. 1.58)</span>';
					}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_time"><?php _e('Time:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_convert_time" type="text" id="runnerslog_convert_time"  value="', form_option('runnerslog_convert_time'), '" size="7" maxlength="8" />';
						echo '<span class="description"> Must be formated as hh:mm:ss like 00:37:45 for 37min and 45sec</span>';
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_speed"><?php _e('Speed:') ?></label></th>
				<td><?php
						if ( $convert_distance && $convert_time ) {
							if ( $distancetype == meters ) {
								$speed = sec2hmsdiff(hms2sec($convert_time)/($convert_distance/1000));
								$speedpersec = ROUND(($convert_distance)/hms2sec($convert_time),2);
								$speedperhours = ROUND((($convert_distance/1000)/hms2sec($convert_time)*3600),2);
								echo '<span class="description">'.$speed.' per km</span><br/>';
								echo '<span class="description">'.$speedpersec.' m/sec</span><br/>';
								echo '<span class="description">'.$speedperhours.' km/hours</span><br/>';
									} else {
								$speed = sec2hmsdiff(hms2sec($convert_time)/($convert_distance));
								$speedpersec = ROUND(($convert_distance*5280)/hms2sec($convert_time),2);
								$speedperhours = ROUND((($convert_distance)/hms2sec($convert_time)*3600),2);
								echo '<span class="description">'.$speed.' per mile</span><br/>';
								echo '<span class="description">'.$speedpersec.' feet/sec</span><br/>';
								echo '<span class="description">'.$speedperhours.' miles/hours</span><br/>';
							}
						}
					?>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Calculate Speed', 'runnerslog_ops' ) ?>" />
	</p>
</form>

<h2>Calculate Race Time</h2>
<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_distance"><?php _e('Distance:') ?></label></th>
				<td><?php
					if ( $distancetype == meters ) {
						echo '<input name="runnerslog_convert_distance" type="text" id="runnerslog_convert_distance"  value="', form_option('runnerslog_convert_distance'), '" class="small-text" />';
						echo '<span class="description"> meters</span>';
							} else {
						echo '<input name="runnerslog_convert_distance" type="text" id="runnerslog_convert_distance"  value="', form_option('runnerslog_convert_distance'), '" class="small-text" />';
						echo '<span class="description"> miles (eg. 1.58)</span>';
					}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_speed"><?php _e('Speed:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_convert_min" type="text" id="runnerslog_convert_min"  value="', form_option('runnerslog_convert_min'), '" size="2" maxlength="2" />min ';
						echo '<input name="runnerslog_convert_sec" type="text" id="runnerslog_convert_sec"  value="', form_option('runnerslog_convert_sec'), '" size="3" maxlength="3" />sec';
						if ( $distancetype == meters ) {
							echo '<span class="description"> per km</span>';
								} else {
							echo '<span class="description"> per mile</span>';
						}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_racetime"><?php _e('Race Time:') ?></label></th>
				<td><?php
						if ( $convert_distance && $convert_min ) {
							if ( $distancetype == meters ) {
								$racetime = sec2hmsdiff(($convert_distance/1000)*(($convert_min*60)+$convert_sec));
								echo '<span class="description">'.$racetime.'</span>';
									} else {
								$racetime = sec2hmsdiff($convert_distance*(($convert_min*60)+$convert_sec));
								echo '<span class="description">'.$racetime.'</span>';
							}
						}
					?>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Calculate Race Time', 'runnerslog_ops' ) ?>" />
	</p>
</form>

<h2>Calculate Distance</h2>
<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_time"><?php _e('Time:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_convert_time" type="text" id="runnerslog_convert_time"  value="', form_option('runnerslog_convert_time'), '" size="7" maxlength="8" />';
						echo '<span class="description"> Must be formated as hh:mm:ss like 00:37:45 for 37min and 45sec</span>';
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_speed"><?php _e('Speed:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_convert_min" type="text" id="runnerslog_convert_min"  value="', form_option('runnerslog_convert_min'), '" size="2" maxlength="2" />min ';
						echo '<input name="runnerslog_convert_sec" type="text" id="runnerslog_convert_sec"  value="', form_option('runnerslog_convert_sec'), '" size="3" maxlength="3" />sec';
						if ( $distancetype == meters ) {
							echo '<span class="description"> per km</span>';
								} else {
							echo '<span class="description"> per mile</span>';
						}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_distance"><?php _e('Distance:') ?></label></th>
				<td><?php
				if ( $convert_time && $convert_min ) {
					if ( $distancetype == meters ) {
						$runningdistance = ROUND(((hms2sec($convert_time)/(($convert_min*60)+$convert_sec))*1000),0);
						echo '<span class="description">'.$runningdistance.' meters</span>';
							} else {
						$runningdistance = ROUND(((hms2sec($convert_time)/(($convert_min*60)+$convert_sec))),2);
						echo '<span class="description">'.$runningdistance.' miles</span>';
					}
				}
					?>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Calculate Distance', 'runnerslog_ops' ) ?>" />
	</p>
</form>

<h2>Convert speed to min per distance</h2>
<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_speedperhour"><?php _e('Speed per hour:') ?></label></th>
				<td><?php
						echo '<input name="runnerslog_convert_speedperhour" type="text" id="runnerslog_convert_speedperhour"  value="', form_option('runnerslog_convert_speedperhour'), '" size="4" maxlength="5" />';
						if ( $distancetype == meters ) {
							echo '<span class="description">km/hour eg. 12.5</span>';
								} else {
							echo '<span class="description">miles/hour</span>';
						}
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_convert_timeperdistance"><?php _e('Time per Distance:') ?></label></th>
				<td><?php
				if ( $convert_speedperhour ) {
					if ( $distancetype == meters ) {
						$timeperdistance = sec2hmsdiff(3600/$convert_speedperhour);
						echo '<span class="description">'.$timeperdistance.' per km</span>';
							} else {
						$timeperdistance = sec2hmsdiff(3600/$convert_speedperhour);
						echo '<span class="description">'.$timeperdistance.' per mile</span>';
					}
				}
					?>
				</td>
			</tr>			
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Calculate Time per Distance', 'runnerslog_ops' ) ?>" />
	</p>
</form>

</div>