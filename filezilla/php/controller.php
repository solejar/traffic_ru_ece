<?php
//including all the module php scripts
include 'traffic_collector.php';
include 'weather_collector.php';
include 'map_communicator.php';
include 'loc_converter.php';

//this is the controller. it's gonna handle the flow

//this guy stores the params
class paramStorage{
	private $conditionParams; 
	private $locParams;
	private $feature;
	/*
	0 - 1 Forecast first, bool, 0 or 1
	l - loc1 - for heatmap, zipcode. For route, start location
	2 - loc2 - for heatmap, range. For route, end location
	3 - weather - for forecast ==1, null. For forecast ==0, from forms
	4 - severity - same for both
	5 - time - same for both
	6 - day - for forecast ==0, take from form. For forecast ==1, parse from date form
	7 - date - for forecast==0, null. For forecast ==1, read from form.
	8 - whichFeature - ‘heatmap’,’route’,’agenda’ based on the folder where index.html is i.e. heatmap/index.html
	*/
	
	//this is where vals are stored
	public function storeParams($inputs){
		$feature = $inputs[8];

		if ($feature == "heatmap"){

			$locParams = array(
				"zip"        => $inputs[1],
				"range"      => $inputs[2],
			);
			$conditionParams = array(
				"weather"    => $inputs[3],
				"severities" => $inputs[4],
				"time"       => $inputs[5],
				"day"        => $inputs[6],
			);
			

		}else if ($feature == "route") {
			$locParams = array(
				"start"      => $inputs[1],
				"end"        => $inputs[2],
			);
			$conditionParams = array(
				"weather"    => $inputs[3],
				"severities" => $inputs[4],
				"time"       => $inputs[5],
				"day"        => $inputs[6],
			);

			
		}

		
	}	
	
	//public getter for params.
	public function getParams($whichParam){
		$output;
		switch ($whichParam) {
			case "all":
				$output = array($locParams,$conditionParams,$feature);
				return $output;
			case "location":
				$output = $locParams;
				return $output;
			case "conditions":
				$output = $conditionParams;
				return $output;
			default:
				$output = array($locParams,$conditionParams,$feature);
				return $output;
		}
	}
}

$user_params = json_decode(stripslashes($_POST['data']));

$lat_north = "enter me";
$lat_south = "enter me";
$lng_west = "enter me";
$lng_east = "enter me";
$bbox = array($lat_north,$lat_south,$lng_west,$lng_east);

$weather_forecast;
$latlng_location;
$route_array;
$traffic_sev_array;
$map;
$graphs;

//collect args from argv
//might be nice to make this a list.
//$user_params = $argv[1];

//if forecast===1, get weather
if($forecast==1){
	$date = $user_params[6];
	$zip = get_zip($user_params);
	$weather_forecast = forecast_weather($date,$zip);
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

$latlng_location = get_location($location_params,$feature);

if($feature == "route"){
	//get route from gmaps
	$route_array = get_route($latlng_location);

	//then get the traffs for those routes
	$traffic_sev_array = get_traffic($route, $location_params, $condition_params);

}else if($feature == "heatmap"){
	//if heatmap, get traffs in area
	$traffic_sev_array = get_traffic($latlng_location,$location_params,$condition_params);

}

echo $traffic_sev_array;

//now we need to get this back to the html page!




?>
