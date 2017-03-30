<?php
	include("WeatherCollector.php");
	$dateWC = "March 30, 2017";
	$timeWC = "11";
	$zipWC = "08817";


	$weather=getForecast($dateWC, $timeWC, $zipWC);
	//echo "returned weather: <br>";


	echo $weather."\n";
?>