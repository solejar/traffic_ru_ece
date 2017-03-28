
<?php
	
$keyZipLC = "AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE"; //Mo Zip code APi key
$keyGeoCodLC = "AIzaSyAn4WHKnArDlLswqx47mjkBRmFbTgtvoxk"; //Ridwan gmail API key
$keyGeoZip = "AIzaSyBHHotUySVfCduC-qH6j_aKsIYAcb5qqWE"; //Ridwan Scarlet Mail API key



function getZip($locParams, $feature) {
	global $keyGeoCodLC;

	if ($feature === "heatmap" || $feature === "forecasted_heatmap") {
		if (strlen($locParams)==5) {
		
			return $locParams;
		}

	}

	else if ($feature === "route" || $feature === "forecasted_route") {
		$urlStartZipLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams).'&result_type=postal_code&key='.$keyGeoCodLC;
		if (!function_exists('curl_init')){
			die('Can\'t find cURL module');	
		}
		$chGeoLCZip = curl_init();
		if (!$chGeoLCZip){
			die('Couldn\'t initialize a cURL module');	
		}
		else {
				//success		
		}
		curl_setopt($chGeoLCZip, CURLOPT_URL, $urlStartZipLC);
		curl_setopt($chGeoLCZip, CURLOPT_RETURNTRANSFER, TRUE);


		$dataStartZipLC = curl_exec($chGeoLCZip);
		curl_close($chGeoLCZip);
		$parseStartZip = json_decode($dataStartZipLC);

		if ($parseStartZip->status != "OK"){
	 	
	 		return false;
		 }

		//print_r($parseStartZip->results[0]->address_components[6]->types[0]);
		//print_r($parseStartZip->results[0]->address_components[6]->long_name);
		$counted = sizeof($parseStartZip->results[0]->address_components);
		for ($i = 0; $i < $counted; $i++){
			if ($parseStartZip->results[0]->address_components[$i]->types[0]== "postal_code"){
				//echo "success";
				$startZip = $parseStartZip->results[0]->address_components[$i]->long_name;

			}
			
		}
		return $startZip;
	}
}


function get_location($locParams, $feature){

	if ($feature === "heatmap" || $feature === "forecasted_heatmap")
	{

		$latLongLC = callLocServHeat($locParams); //array of (latitude,longitiude)
		return $latLongLC;

	}
	else if ($feature === "route" || $feature === "forecasted_route"){
		//echo "route";


		$latLongLC = callLocServRoute($locParams); //array of (startlat,startlng,endlat, endlng)
		return $latLongLC;

	}

}

function callLocServHeat($locParams) {

	global $keyZipLC;
	//$zipCode = "08816";
	$zipCodeLC = $locParams["zip"];
	$urlZipLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$zipCodeLC.'&key='.$keyZipLC;

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
	if ($parseZip->status != "OK"){
	 	
	 	return false;
	 }
		
		//echo $parseZip->lat;
	$myLat = $parseZip->results[0]->geometry->location->lat; 
		//echo "<br>";
		//echo $parseZip->lng;
	$myLng = $parseZip->results[0]->geometry->location->lng; 

	$heatArrayLC = array($myLat, $myLng);
	return $heatArrayLC;

}

function callLocServRoute($locParams){

	global $keyGeoCodLC;

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
	if ($parseEndLC->status != "OK"){
	 	
	 	return false;
	 }
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
