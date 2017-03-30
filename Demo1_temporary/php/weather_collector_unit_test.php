<?php
	include("WeatherCollector.php");
	$timeWC = "23";
	$zipWC = "08817";

	date_default_timezone_set("America/New_York");
	
	$myDate = date('F d Y ');

	//echo $myDate;
	$weather=getForecast($myDate, $timeWC, $zipWC);
	//echo $weather."\n";
	//echo "returned weather: <br>";
	echo "Testing Weather Collector getForecast Expected Result: Passed\n";
	if ($weather != "Invalid"){
		echo "Passed\n";
	}
	else if ($weather === "Invalid"){
		echo "Failed";
	}

	echo "Testing Weather Collector getForecast Expected Result: Failed\n";

	$weatherFail = getForecast($myDate, '23', '00000');
	//echo $weatherFail."\n";

	if ($weatherFail != "Invalid"){
		echo "Passed\n";
	}
	else if ($weatherFail === "Invalid"){
		echo "Failed\n";
	}



?>