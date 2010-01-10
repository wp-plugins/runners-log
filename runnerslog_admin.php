<?php 
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent
		$distancetype = $_POST['runnerslog_distancetype'];
		$garminconnect = $_POST['runnerslog_garminconnectlink'];
		$calories = $_POST['runnerslog_caloriescount'];
		update_option('runnerslog_distancetype', $distancetype);
		update_option('runnerslog_garminconnectlink', $garminconnect);
		update_option('runnerslog_caloriescount', $calories);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Normal page display
		$distancetype = get_option('runnerslog_distancetype');
		$garminconnect = get_option('runnerslog_garminconnectlink');
		$calories = get_option('runnerslog_caloriescount');
	}
?>

<div class="wrap">
<?php echo "<h2>" . __( 'Runners Log Options', 'runnerslog_ops' ) . "</h2>"; ?>
<p>Set the options below. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Morbi commodo, ipsum sed pharetra gravida, orci magna rhoncus neque, id pulvinar odio lorem non turpis. Nullam sit amet enim. Suspendisse id velit vitae ligula volutpat condimentum. Aliquam erat volutpat.</p>

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
				<label for="runnerslog_garminconnectlink">
				<input name="runnerslog_garminconnectlink" id="runnerslog_garminconnectlink" value="1"<?php checked('1', get_option('runnerslog_garminconnectlink')); ?> type="checkbox">
				<?php _e('Enable link to Garmin.connect?') ?></label>
				</th>
			</tr>
			<tr>
				<th scope="row" colspan="2" class="th-full">
				<label for="runnerslog_caloriescount">
				<input name="runnerslog_caloriescount" id="runnerslog_caloriescount" value="1"<?php checked('1', get_option('runnerslog_caloriescount')); ?> type="checkbox">
				<?php _e('Enable calories count?') ?></label>
				</th>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save and update options', 'runnerslog_ops' ) ?>" />
	</p>
</form>
</div>