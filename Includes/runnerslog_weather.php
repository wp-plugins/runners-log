<div class="wrap">
<p><?php echo "<h2>" . __( 'Runners Log Weather Settings' ) . "</h2>"; ?></p>

<?php 
	//load currently selected unit
	$unittype = get_option('runnerslog_unittype');
	$woeid = get_option('runnerslog_woeid');
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent and new WOEID saved
		$weather_temperature = $_POST['runnerslog_weather_temperature'];
		$weather_windchill = $_POST['runnerslog_weather_windchill'];
		$weather_humidity = $_POST['runnerslog_weather_humidity'];
		$weather_description = $_POST['runnerslog_weather_description'];
		$weather_yahoo = $_POST['runnerslog_weather_yahoo'];
		$woeid = $_POST['runnerslog_woeid'];
		update_option('runnerslog_woeid', $woeid);
		update_option('runnerslog_weather_temperature', $weather_temperature);
		update_option('runnerslog_weather_windchill', $weather_windchill);
		update_option('runnerslog_weather_humidity', $weather_humidity);
		update_option('runnerslog_weather_description', $weather_description);
		update_option('runnerslog_weather_yahoo', $weather_yahoo);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Load current WOEID
		$woeid = get_option('runnerslog_woeid');
		$weather_temperature = get_option('runnerslog_weather_temperature');
		$weather_windchill = get_option('runnerslog_weather_windchill');
		$weather_humidity = get_option('runnerslog_weather_humidity');
		$weather_description = get_option('runnerslog_weather_description');
		$weather_yahoo = get_option('runnerslog_weather_yahoo');
	}
?>

<p>
<div class="tool-box">
<h3 class="title">Weather Options</h3>
For every class you activate here a field in the meta box is added so you can store information about it with every post.
<form name="runnerslog_ops_form" method="post">
<input type="hidden" name="runnerslog_op_hidden" value="Y" />
<table class="form-table">
	<tr>
		<th scope="row" colspan="2" class="th-full">
		<label for="runnerslog_weather_temperature">
		<input name="runnerslog_weather_temperature" id="runnerslog_weather_temperature" value="1"<?php checked('1', get_option('runnerslog_weather_temperature')); ?> type="checkbox">
		<?php _e('Enable Temperature Measurement') ?></label>
		</th>
	</tr>
	<tr>
		<th scope="row" colspan="2" class="th-full">
		<label for="runnerslog_weather_windchill">
		<input name="runnerslog_weather_windchill" id="runnerslog_weather_windchill" value="1"<?php checked('1', get_option('runnerslog_weather_windchill')); ?> type="checkbox">
		<?php _e('Enable Windchill Measurement') ?></label>
		</th>
	</tr>
	<tr>
		<th scope="row" colspan="2" class="th-full">
		<label for="runnerslog_weather_humidity">
		<input name="runnerslog_weather_humidity" id="runnerslog_weather_humidity" value="1"<?php checked('1', get_option('runnerslog_weather_humidity')); ?> type="checkbox">
		<?php _e('Enable Humidity Measurement') ?></label>
		</th>
	</tr>
	<tr>
		<th scope="row" colspan="2" class="th-full">
		<label for="runnerslog_weather_description">
		<input name="runnerslog_weather_description" id="runnerslog_weather_description" value="1"<?php checked('1', get_option('runnerslog_weather_description')); ?> type="checkbox">
		<?php _e('Enable Textbased Weather Description') ?></label>
		</th>
	</tr>
	<tr>
		<th scope="row" colspan="2" class="th-full">
		<label for="runnerslog_weather_description">
		<input name="runnerslog_weather_yahoo" id="runnerslog_weather_yahoo" value="1"<?php checked('1', get_option('runnerslog_weather_yahoo')); ?> type="checkbox">
		<?php _e('Enable Weather Data from Yahoo') ?></label>
		</th>
	</tr>
</table>

</p>
</div>

<div class="tool-box">
<h3 class="title">Yahoo Weather Options</h3>
Here you can change your location and specify whether your data should be queried from the Yahoo Weather data via its Where On Earth Identifiers (WOEID) or not. More information on WOEID can be found <a href=http://developer.yahoo.com/geo/geoplanet/guide/concepts.html# target="_blank">here</a>. 
To gain your WOEID just go to the <a href=http://weather.yahoo.com/  target="_blank"> Yahoo Weather </a> service, search for your current location and copy it from the URL. It should be a number with about six digits at the end of the URL, separated from your city's name with a horizontal pipe. <br />
If you want to use the Yahoo Weather don't forget to activate it above.
<p>
	<input type="hidden" name="runnerslog_op_hidden" value="Y" />
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="runnerslog_woeid"><?php _e('Current WOEID:') ?></label></th>
				<td><?php
					echo $woeid;
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="runnerslog_woeid"><?php _e('New WOEID:') ?></label></th>
				<td><?php
					echo '<input name="runnerslog_woeid" type="text" id="runnerslog_woeid"  value="" class="small-text" />';
					echo '<span class="description"> WOEID for your city (eg 687337 for Ratisbona)</span>';
					?>
				</td>
			</tr>				
		</tbody>
	</table>
	<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update and save', 'runnerslog_ops' ) ?>" />
	</p>
</form>

</p>
</div>

<?php 
if ($unittype == 'metric'){
	$selectedType='c';
} else {
	$selectedType='f';
}
add_option('runnerslog_woeid', $woeid, '','yes');
add_option('runnerslog_weather_temperature', $weather_temperature, '','yes');
add_option('runnerslog_weather_windchill', $weather_windchill, '','yes');
add_option('runnerslog_weather_humidity', $weather_humidity, '','yes');
add_option('runnerslog_weather_description', $weather_description, '','yes');
add_option('runnerslog_weather_yahoo', $weather_yahoo, '','yes');

runnerslog_retrieveWeather($woeid,$selectedType);
?>


<?php
//retrieve information from Yahoo Weather channel depending on selected WOEID
function runnerslog_retrieveWeather($woid,$unit) {
$weather_feed = file_get_contents('http://weather.yahooapis.com/forecastrss?w='.$woid.'&u='.$unit.'');
if(!$weather_feed) die('weather failed, check feed URL');
$weather = simplexml_load_string($weather_feed);

$channel_yweather = $weather->channel->children('http://xml.weather.yahoo.com/ns/rss/1.0');

foreach($channel_yweather as $x => $channel_item)
	foreach($channel_item->attributes() as $k => $attr)
		$yw_channel[$x][$k] = $attr;

echo '<div id="weather">';
echo 'city: '.$yw_channel[location][city].'<br />';
echo 'humidity: '.$yw_channel[atmosphere][humidity].'%<br />';
echo 'wind chill: '.$yw_channel[wind][chill].'<br />';
echo 'wind direction: '.$yw_channel[wind][direction].'<br />';
echo 'wind speed:  '.$yw_channel[wind][speed].'<br />';
echo '</div>';

//retrieve information from Yahoo Weather channel item depending on selected WOEID
$item_yweather = $weather->channel->item->children('http://xml.weather.yahoo.com/ns/rss/1.0');

foreach($item_yweather as $x => $yw_item) {
	foreach($yw_item->attributes() as $k => $attr) {
		if($k == 'day') $day = $attr;
		if($x == 'forecast') { $yw_forecast[$x][$day . ''][$k] = $attr;	} 
		else { $yw_forecast[$x][$k] = $attr; }
	}
}

echo '<div id="weather">';
echo 'temperature: '.$yw_forecast[condition][temp].'<br />';
echo 'weather: '.$yw_forecast[condition][text].'<br />';
echo '</div>';
}
?>
