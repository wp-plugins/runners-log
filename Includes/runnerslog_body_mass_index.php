<div class="wrap">
<p><?php echo "<h2>" . __( 'Runners Log Body Mass Calculator' ) . "</h2>"; ?></p>

<?php 
$unittype = get_option('runnerslog_unittype');

//Get the values needed to calculate the BMI
if ($unittype == metric) {
	$heightcm = get_option('runnerslog_cm');
		} else {
	$heightfeets = get_option('runnerslog_feets');
	$heightinches = get_option('runnerslog_inches');
}
$weight = get_option('runnerslog_weight');

if ( $unittype == metric ) {
	if ( $weight && $heightcm) {
		$bmi = ROUND($weight/(($heightcm/100)*($heightcm/100)),2); // For meters we use the formular: BMI kg/m^2 = weight in kilograms / height in meters^2
	}
		} else {
	if ( $weight && $heightfeets && $heightinches) {
		$bmi = ROUND(($weight*703)/((($heightfeets*12)+$heightinches)*(($heightfeets*12)+$heightinches)),2); // For english unit type we use the formular: BMI = (weight in pounds * 703 ) / (height in inches)^2
	}
}		
if ( $bmi < 18.5 ) {
	$weightstatus = 'Underweight';
}
if ($bmi >= 18.5 && $bmi <= 24.9) {
	$weightstatus = 'Normal';
}
if ($bmi >= 25 && $bmi <= 29.9) {
	$weightstatus = 'Overweight';
}
if ($bmi >= 30 && $bmi <= 34.9) {
	$weightstatus = 'Obesity grade I';
}
if ($bmi >= 35 && $bmi <= 39.9) {
	$weightstatus = 'Obesity grade II';
}
if ($bmi >= 40) {
	$weightstatus = 'Obesity grade III';
}

//Print the heigt and weight
if ( $unittype == metric ) {
	if ( $weight AND $heightcm ) {
		echo 'Your height is set to: ';
		echo $heightcm;
		echo ' centimeters <br/>';
		echo 'And your weight to: ';
		echo $weight;
		echo ' kg<br/>';
		echo '<p>Your Body Mass Index is <b>',$bmi,'</b> indicating your weight is in the <b>',$weightstatus,'</b> category for adults of your height</p>';
	} else {
		echo '<p>To calculate <b>YOUR</b> BMI you have to type in your weight and height in Runners Log Settings</p>';
	}
 } else {
	//When the setting is unit type: English
	if ( $heightfeets AND $heightinches ) {
		echo 'Your height is set to: ';
		echo $heightfeets;
		echo ' feet ';
		echo $heightinches;
		echo ' inch(es)<br/>';
		echo 'and your weight to: ';
		echo $weight;
		echo ' pounds</br>';
		echo '<p>Your Body Mass Index is <b>',$bmi,'</b> indicating your weight is in the <b>',$weightstatus,'</b> category for adults of your height</p>';
	} else {
		echo '<p>To calculate <b>YOUR</b> BMI you have to type in your weight and height in Runners Log Settings</p>';	
	}
}

?>

<p><b>BMI Categories: </b></p> 
<ul> 
  <li>Underweight = &lt;18.5</li> 
  <li>Normal weight = 18.5-24.9 </li> 
  <li>Overweight = 25-29.9 </li> 
  <li>Obesity = BMI of 30 or greater </li> 
</ul> 

</div>