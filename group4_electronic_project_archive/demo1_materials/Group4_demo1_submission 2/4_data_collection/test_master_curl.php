<?php 
	include "masterCurl_testing.php";
	//testing of swtichWeather function:
	echo "Unit Testing for masterCurl.php: "."<br>"."<br>";
	$weather1 = "Heavy Low Drifting Snow";
	$weather2 = "Light Fog";
	echo "Testing switchWeather... ";
	if ((switchWeather($weather1) === "Snow") && (switchWeather($weather2)) === "Fog"){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	//testing of db connection

	echo "Testing database connection...";
	if($dbCon){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	//testing of setSev function:
	echo "Testing setSev...";
	if($setTest){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	echo "Testing weather API...";
	if($weatherAPI){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	echo "Testing weather frequency insertion/update...";
	if($testWeather){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	echo "Testing traffic incident API...";
	if($trafficAPI){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}
	echo "Testing geocoding API...";
	if($geoAPI){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	echo "Testing traffic incident insertion/update...";
	if($testTraffic){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}

	echo "Testing average severity insertion/update...";
	if($testAvg){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}
	echo "Testing clean up insertion/updates...";
	if($testCleanup){
		echo "Pass!"."<br><br>";
	}
	else{
		echo "Falied!"."<br><br>";	
	}
?>