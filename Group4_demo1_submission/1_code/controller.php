<?php
//including all the module php scripts
include 'TrafficCollector.php';
include 'WeatherCollector.php';
include 'map_communicator.php';
include 'loc_converter.php';

//this is the controller. it's gonna handle the flow

//this guy stores the params
class paramStorage{
	public $conditionParams; 
	public $locParams;
	public $feature;	
}

//if no POSTed inputs, use the default Google Map
$defaultMap = json_encode(true);

//if inputs have been POSTed, try making a map
if(isset($_POST["submit"])){

    //receive POSTed inputs
	$which_feature = $_POST["submit"];
	$loc1 = $_POST["loc1"];
	$loc2 = $_POST["loc2"];    
    $hourF = $_POST["time"];
    $weatherF = $_POST["weather"];
    $dayF = $_POST["day"];

    //store the values of day/time, just in case they get changed
    $fullHour = $hourF;
    $fullDate = $dayF;
    
    //default severity to false
    $severity = array(false, false, false, false);

    if(!empty($_POST['check_list'])) {
        foreach($_POST['check_list'] as $check) {
            $severity[($check-1)]=true; //receives checkmarks from 
        }
    }

    //errorcode defaults to a null string
    $errorcode = "";
	
    //set forecast based on feature type
    $forecastF = false;
    if ($which_feature == "forecasted_route" || $which_feature == "forecasted_heatmap"){
        $forecastF = true;
    }
	
    //if forecasting, need to parse day of week from a specific user date.
    $tempDate;
	if ($forecastF){
        $tempDate = $dayF;

        $num_of_attempts = 0;
        $routeZip = getZip($loc1, $which_feature);

        //try calling zip code api 3 times. if fails 3 time, exit w/ error
        while(!$routeZip){
            if($num_of_attempts>3){
                $errorcode = "Zip code API service not working, try again in a little bit.";
                return;
            }else{
                $routeZip = getZip($loc1,$which_feature);
                $num_of_attempts++;
            }
        }
        
        $num_of_attempts = 0;
        $weatherF = getForecast($dayF,$hourF,$routeZip);

        //try calling weather api 3 times. if fails 3 times, exit w/ error
        while(!$weatherF){
            if($num_of_attempts>3){
                $errorcode = "Weather forecasting not working, try again in a little bit.";
                return;
            }else{
                $weatherF = getForecast($dayF,$hourF,$routeZip);
                $num_of_attempts++;
            }
        }

        //parse day of week from date
        $dayF = date( "w", strtotime($dayF));
    }

    //make associative array of conditions_params
    $condition_params = array(
				"weather"		 => $weatherF,
				"severity"		 => $severity,
                "time"           => $hourF,
                "day"            => $dayF,
	);
	
    //this is route feature
	if($which_feature=="route" || $which_feature == "forecasted_route"){
		
        //associative array of loc_params
        $location_params = array(
				"start"		 => $loc1,
				"end"		 => $loc2
		);

        $num_of_attempts = 0;
    	$route = get_route($location_params); 

        //try getting directions from gmaps 3 times. 
        //if it fails 3 times, exit w/ error       
        while(!$route){
            if($num_of_attempts>3){
                $errorcode = "Route API service not working. This probably means Google Maps didn't recognize your inputs!";
                return;
            }else{
                $route = get_route($location_params);
                $num_of_attempts++;
            }
        }

        //parse route, get count of steps
    	$testSteps = parse_route($route);
    	$count = sizeof($testSteps);

        //collect traff severity for every leg of the route
	    for ($i = 0; $i<$count; $i++){
            $loc_params = array(
    	        "roadName"   => $testSteps[$i]->rdName,
    	        "startLat" => $testSteps[$i]->stLat,
    	        "startLong"  => $testSteps[$i]->stLng,
            );
	        $testSteps[$i]->severity = get_traff($loc_params, $condition_params,$which_feature);
	        
	        //we technically don't need error handling here
            //b/c even if no sev's returned, blue line connects start/stop
	    }

        //disable the default map
	    $defaultMap = json_encode(false);

	    //testSteps now is the array of structs for the map draw
	    $stepsJSON = json_encode($testSteps);
	
    }//now let's do the heatmap
    else if($which_feature == "forecasted_heatmap" || $which_feature == "heatmap"){

        //associative array of loc_params
		$location_params = array(
				"zip"		 => $loc1,
				"range"		 => $loc2
		);

		$num_of_attempts = 0;
		$latlng_location = get_location($location_params,$which_feature);

        //try calling geocoding 3 times. if it fails, exit w/ error 
        while(!$latlng_location){
            if($num_of_attempts>3){
                $errorcode = "Location API service not working, try again in a little bit.";
                return;
            }else{
                $latlng_location = get_location($location_params,$which_feature);
                $num_of_attempts++;
            }
        }

        $loc_params = array(
            "cent_lat" => $latlng_location[0],
            "cent_lng" => $latlng_location[1],
            "range" => $location_params["range"],
        );

		$testSteps = get_traff($loc_params,$condition_params,$which_feature);

        //try getting traff severity for heatmap. if it fails, exit w/ error
        if(!testSteps){
            $errorcode = "Your inputs are returning no traffic. Make sure that your search is within the tri-state area, and try to make it more general if possible!";
            return;
        }

        //get the steps and encode them for the map drawing
		$count = sizeof($testSteps);
		$stepsJSON = json_encode($testSteps);

        //set the default to false
		if ($count != 0){
			for ($i = 0; $i<$count; $i++){
		        if ($testSteps[$i]->severity != 0){
		        	$defaultMap = json_encode(false);
		        }
	   		}
		}
	}
}

?>
