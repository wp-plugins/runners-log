<div class="wrap">
<p><?php echo "<h2>" . __( 'Runners Log Weather Settings' ) . "</h2>"; ?></p>

<?php 
	//load currently selected unit
	$unittype = get_option('runnerslog_unittype');
	$woeid = get_option('runnerslog_woeid');
	if($_POST['runnerslog_op_hidden'] == 'Y') {
		//Form data sent and new WOEID saved
		$woeid = $_POST['runnerslog_woeid'];
		update_option('runnerslog_woeid', $woeid);
		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} else {
		//Load current WOEID
		$woeid = get_option('runnerslog_woeid');
	}
?>

Here you can change your location and specify whether your data should be queried from the Yahoo Weather data via its Where On Earth Identifiers (WOEID) or not. More information on WOEID can be found <a href=http://developer.yahoo.com/geo/geoplanet/guide/concepts.html# target="_blank">here</a>.<br> 
To gain your WOEID just go to the <a href=http://weather.yahoo.com/  target="_blank"> Yahoo Weather </a> service, search for your current location and copy it from the URL. It should be a number with about six digits at the end of the URL, separated from your city's name with a horizontal pipe.
<p>
<form name="runnerslog_ops_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
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
		<input type="submit" name="Submit" value="<?php _e('Update WOEID', 'runnerslog_ops' ) ?>" />
	</p>
</form>

</p>

<?php 
if ($unittype == 'metric'){
	$selectedType='c';
} else {
	$selectedType='f';
}
add_option('runnerslog_woeid', $woeid, '','yes');
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
