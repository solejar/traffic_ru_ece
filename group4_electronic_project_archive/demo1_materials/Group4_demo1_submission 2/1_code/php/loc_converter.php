
<?php
	
$keyZipLC = "AIzaSyAFd0n6zIrq0Xscr4Wq5eKdUBBIeobFoJs"; //Mo Zip code APi key
$keyGeoCodLC = "AIzaSyAn4WHKnArDlLswqx47mjkBRmFbTgtvoxk"; //Ridwan gmail API key
$keyGeoZip = "AIzaSyBHHotUySVfCduC-qH6j_aKsIYAcb5qqWE"; //Ridwan Scarlet Mail API key


//returns a zip code based on parameters and feature
function getZip($locParams, $feature) {
	global $keyGeoCodLC;
	//based on feature, return appropriate zip code

	if ($feature === "heatmap" || $feature === "forecasted_heatmap") {
		if (strlen($locParams)==5) {
		
			return $locParams;
		}

	}

	else if ($feature === "route" || $feature === "forecasted_route") {
		//api call to get zip code of starting addres
		$urlStartZipLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams).'&result_type=postal_code&key='.$keyGeoCodLC;
		//set up cURL module
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

		//check status response of API call
		if ($parseStartZip->status != "OK"){
	 	
	 		return false;
		 }

		//get size of returned JSON results of address components
		$counted = sizeof($parseStartZip->results[0]->address_components);

		//find the postal code for the starting
		for ($i = 0; $i < $counted; $i++){
			//parse the json response
			if ($parseStartZip->results[0]->address_components[$i]->types[0]== "postal_code"){
				//store the zip code from json response by parsing it
				$startZip = $parseStartZip->results[0]->address_components[$i]->long_name;

			}
			
		}
		//return the zip code
		return $startZip;
	}
}


//returns latitude and longitude pair(s) based on paramters and features
function get_location($locParams, $feature){
	//based on the feature, select what service to call to get latitude and longitude pairs
	if ($feature === "heatmap" || $feature === "forecasted_heatmap")
	{

		$latLongLC = callLocServHeat($locParams); //array of (latitude,longitiude)
		return $latLongLC;

	}
	else if ($feature === "route" || $feature === "forecasted_route"){

		$latLongLC = callLocServRoute($locParams); //array of (startlat,startlng,endlat, endlng)
		return $latLongLC;

	}

}

//calls Google geocoding API to get latitude and longitude for a center of a zip code
function callLocServHeat($locParams) {

	global $keyZipLC;

	$zipCodeLC = $locParams["zip"];
	//use inputted zip code for API call
	$urlZipLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$zipCodeLC.'&key='.$keyZipLC;

	//set up curl module
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



	$parseZip = json_decode($dataZipLC);

	//check status response of API call
		
	if ($parseZip->status != "OK"){
	 	
	 	return false;
	 }
		
	//parse to get latitude
	$myLat = $parseZip->results[0]->geometry->location->lat; 
	//parse to get longitude
	$myLng = $parseZip->results[0]->geometry->location->lng; 
	//create a return to values
	$heatArrayLC = array($myLat, $myLng);
	return $heatArrayLC;

}

function callLocServRoute($locParams){

	global $keyGeoCodLC;
	//use inputted starting address for API call
	$urlStartLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams["start"]).'&key='.$keyGeoCodLC;

	//set up curl module
	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$chGeoLCStart = curl_init();
	if (!$chGeoLCStart){
		die('Couldn\'t initialize a cURL module');	
	}
	else {
					//success
	}

	curl_setopt($chGeoLCStart, CURLOPT_URL, $urlStartLC);
	curl_setopt($chGeoLCStart, CURLOPT_RETURNTRANSFER, TRUE);

	$dataStartLC = curl_exec($chGeoLCStart);
	curl_close($chGeoLCStart);
				
	$parseStartLC = json_decode($dataStartLC);

	//check API response status
	if ($parseStartLC->status != "OK"){
	 	
	 	return false;
	 }
				
	//parse to get latitude	
	$startLatLC = $parseStartLC->results[0]->geometry->location->lat;
	//parse to get longitude
	$startLngLC = $parseStartLC->results[0]->geometry->location->lng;

	//use inputted ending address for API call
	$urlEndLC = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($locParams["end"]).'&key='.$keyGeoCodLC;

	//set up curl module

	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$chGeoLCEnd = curl_init();
	if (!$chGeoLCEnd){
		die('Couldn\'t initialize a cURL module');	
	}
	else {
			//success
	}

	curl_setopt($chGeoLCEnd, CURLOPT_URL, $urlEndLC);
	curl_setopt($chGeoLCEnd, CURLOPT_RETURNTRANSFER, TRUE);

	$dataEndLC = curl_exec($chGeoLCEnd);
	curl_close($chGeoLCEnd);
				
	$parseEndLC = json_decode($dataEndLC);
	//check status response of API call
	if ($parseEndLC->status != "OK"){
	 	
	 	return false;
	 }
				
	//parse to get latitude	
	$endLatLC = $parseEndLC->results[0]->geometry->location->lat;
	//parse to get longitude
	$endLngLC = $parseEndLC->results[0]->geometry->location->lng;
	
	//return an array of 2 arrays, with pairs of lat and lng
	$start_coordinates = array($startLatLC,$startLngLC);
	$end_coordinates = array($endLatLC,$endLngLC);
	$routeArrayLC = array($start_coordinates, $end_coordinates);

	return $routeArrayLC;

}






?>
