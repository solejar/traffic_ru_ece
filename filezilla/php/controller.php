<?php
//including all the module php scripts
include 'traffic_collector.php';
include 'weather_collector.php';
include 'map_communicator.php';
include 'loc_converter.php';

//this is the controller. it's gonna handle the flow

//this guy stores the params
class paramStorage{
	$conditionParams;
	$locParams;
	
	//this is where vals are stored
	function storeParams($inputs){

	}	
	
	function getParams($whichParam){
	}
}

$user_params;
$weather_forecast;
$latlng_location;
$route_array;
$traffic_sev_array;
$map;
$graphs;

//adding flag for feature type
$which_feature;

//collect args from argv
//might be nice to make this a list.
$user_params = $argv[1];

//if forecast===1, get weather
if($forecast==1){
	$date = $user_params[7];
	
	//$weather_command = sprintf('/usr/bin/php weather_collector.php %s',$date);
	//this is a func from 'weather_collector.php'
	$weather_command = forecast_weather($date);
	//$weather_forecast = shell_exec($weather_command);
	user_params[3] =  $weather_forecast;
}

paramStore = new paramStorage();
//make param storage, put params in it
paramStore.storeParams($user_params);

//get all params back
$all_params = paramStore.getParams('all');

//parse return of functions

//this is info about the location
$location_params = $all_params[0];

//this is date,time, weather
$condition_params = $all_params[1];

//this is the feature I'm on!
$feature = $all_params[2];

//$loc_command = sprintf('/usr/bin/php loc_converter.php %s, %s'
$latlng_location = get_location($location_params,$feature);
//$latlng_location = shell_exec('/usr/bin/php loc_converter.php paramsgohere');

$traffic_sev_array = shell_exec('/usr/bin/php traffic_collector.php params');

$map = shell_exec('/usr/bin/php map_communicator.php params');

//not sure how this gets passed back to javascript

?>
