
<?php
	
$keyZipLC = "O1qklLIMiUT3c2AUfNfFqS4yYT3EybUndkHy6qlpkesxg3rwQHbhzgPy9TcI566i";
$keyGeoCodLC = "AIzaSyAn4WHKnArDlLswqx47mjkBRmFbTgtvoxk";


/*$locParamsLC =  array(
				"zip"        => "08817",
				"range"      => "10",
			);

$locParamsLCRoute =  array(
				"start"      => "7 Hancock Court East Brunswick NJ",
				"end"      => "504 Merrywood Drive Edison NJ",
			);

$feature1 = "heatmap";

$arrayLC = get_location($locParamsLC, $feature1);

echo $arrayLC[0];
echo "<br>";
echo $arrayLC[1];

echo "<br>";
$feature2 = "route";
$arrayRouteLC = get_location($locParamsLCRoute, $feature2);
echo "<br>";
echo $arrayRouteLC[0];
echo "<br>";
echo $arrayRouteLC[1];
echo "<br>";
echo $arrayRouteLC[2];
echo "<br>";
echo $arrayRouteLC[3];
*/


function get_location($locParams, $feature){

	if ($feature === "heatmap")
	{

		$latLongLC = callLocServHeat($locParams); //array of (latitude,longitiude)
		return $latLongLC;

	}
	else if ($feature === "route"){
		//echo "route";

		$latLongLC = callLocServRoute($locParams); //array of (startlat,startlng,endlat, endlng)
		return $latLongLC;

	}

}

function callLocServHeat($locParams) {

	//global $keyZipLC;
	//$zipCode = "08816";
	$zipCodeLC = $locParams["zip"];

	$urlZipLC = 'https://www.zipcodeapi.com/rest/'.$keyZipLC.'/info.json/'.$zipCodeLC.'/degrees';

	if (!function_exists('curl_init')){
			die('Can\'t find cURL module');	
	}
	$chLC = curl_init();
	if (!$chLC){
		die('Couldn\'t initialize a cURL module');	
	}

	curl_setopt($chLC, CURLOPT_URL, $urlZipLC);
	curl_setopt($chLC, CURLOPT_RETURNTRANSFER, TRUE);

	$dataZipLC = curl_exec($chLC);
	curl_close($chLC);

		//echo $dataZipLC;

	$parseZip = json_decode($dataZipLC);
		//print_r($parseZip);
		
		//echo $parseZip->lat;
	$myLat = $parseZip->lat; 
		//echo "<br>";
		//echo $parseZip->lng;
	$myLng = $parseZip->lng;

	$heatArrayLC = array($myLat, $myLng);
	return $heatArrayLC;

}

function callLocServRoute($locParams){

	//global $keyGeoCodLC;

	$urlStartLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams["start"]).'&key='.$keyGeoCodLC;

	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$chGeoLCStart = curl_init();
	if (!$chGeoLCStart){
		die('Couldn\'t initialize a cURL module');	
	}
	else {
					/*
					echo "We good";
					echo "<br>";
					*/
	}

	curl_setopt($chGeoLCStart, CURLOPT_URL, $urlStartLC);
	curl_setopt($chGeoLCStart, CURLOPT_RETURNTRANSFER, TRUE);

	$dataStartLC = curl_exec($chGeoLCStart);
	curl_close($chGeoLCStart);
				
	$parseStartLC = json_decode($dataStartLC);
				//print_r($parseStartLC->results[0]->geometry->location->lat);
				
	$startLatLC = $parseStartLC->results[0]->geometry->location->lat;
	$startLngLC = $parseStartLC->results[0]->geometry->location->lng;

	$urlEndLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams["end"]).'&key='.$keyGeoCodLC;

	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$chGeoLCEnd = curl_init();
	if (!$chGeoLCEnd){
		die('Couldn\'t initialize a cURL module');	
	}
	else {
					/*
					echo "We good";
					echo "<br>";
					*/
	}

	curl_setopt($chGeoLCEnd, CURLOPT_URL, $urlEndLC);
	curl_setopt($chGeoLCEnd, CURLOPT_RETURNTRANSFER, TRUE);

	$dataEndLC = curl_exec($chGeoLCEnd);
	curl_close($chGeoLCEnd);
				
	$parseEndLC = json_decode($dataEndLC);
				/*
				echo "<br>";
				echo "hello again";
				echo "<br>";

				print_r($parseEndLC->results[0]->geometry->location->lat);
				*/

				
	$endLatLC = $parseEndLC->results[0]->geometry->location->lat;
	$endLngLC = $parseEndLC->results[0]->geometry->location->lng;
	
	$start_coordinates = array($startLatLC,$startLngLC);
	$end_coordinates = array($endLatLC,$endLngLC);
	$routeArrayLC = array($start_coordinates, $end_coordinates);

	return $routeArrayLC;

}






?>
