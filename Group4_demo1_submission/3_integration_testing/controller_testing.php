<?php
//including all the module php scripts
include 'TrafficCollector.php';
include 'WeatherCollector.php';
include 'map_communicator.php';
include 'loc_converter.php';

//THIS IS THE VERSION FOR TESTING THE CONTROLLER
//GOOGLE MAPS DOESNT PRINT THE RESULTS OF THIS SCRIPT

//this guy stores the params
class paramStorage{
	public $conditionParams; 
	public $locParams;
	public $feature;
	
}

function test_func($validity_array,$userParams){
//if(isset($_POST["submit"])){
    
    if($validity_array->input_valid==true){
    $alertRouteE = false;
	$defaultMap = json_encode(true);
	$which_feature = $userParams["which_feature"];
	$loc1 = $userParams["loc1"];
	$loc2 = $userParams["loc2"];
    $forecastF = $userParams["forecast"];

    $errorcode = "";

    /*if ($which_feature == "forecasted_route" || $which_feature == "forecasted_heatmap"){
    	$forecastF = true;
    }*/ //just gonna pass it in
    
    $hourF = $userParams["time"];
    $fullHour = $hourF;
    $dayF = $userParams["day"];
    $fullDate = $dayF;
    $weatherF = $userParams["weather"];
    $severity = $userParams["severity"];
    }else{
        $errorcode = "Inputs are invalid. Try again";
        return $errorcode;
    }
	
    $tempDate;
	if ($forecastF){
            $tempDate = $dayF;

            $num_of_attempts = 0;

            $index = 0;
            $validity_zip = $validity_array->zip_api_valid;
            //$routeZip = getZip($loc1, $which_feature);
            $routeZip = false;

            //testing the error handling for geocoding api
            while(!$routeZip){
                if($num_of_attempts>3){
                    $errorcode = "Zip code API service not working, try again in a little bit.";
                    return $errorcode;
                }else{
                    if($validity_zip[$index]==true){
                        $routeZip = getZip($loc1,$which_feature);
                    }else{
                        $routeZip = false;
                    }   
                    $num_of_attempts++;
                    $index++;
                }
            }
            
            $num_of_attempts = 0;
            //$weatherF = getForecast($dayF,$hourF,$routeZip);
            $weatherF = false;

            $index = 0;
            $validity_forecast = $validity_array->forecast_api_valid;

            //testing error handling of weather api
            while(!$weatherF){
                if($num_of_attempts>3){
                    $errorcode = "Weather API service not working, try again in a little bit.";
                    return $errorcode;
                }else{
                    if($validity_forecast[$index]==true){
                        $weatherF = getForecast($dayF,$hourF,$routeZip);
                    }else{
                        $weatherF = false;
                    }
                    $index++;
                    $num_of_attempts++;
                }
            }

            $dayF = date( "w", strtotime($dayF));
    }

    $condition_params = array(
				"weather"		 => $weatherF,
				"severity"		 => $severity,
                "time"           => $hourF,
                "day"            => $dayF,
	);
	
	if($which_feature=="route" || $which_feature == "forecasted_route"){
		$location_params = array(
				"start"		 => $loc1,
				"end"		 => $loc2
		);

        $num_of_attempts = 0;
    	//$route = get_route($location_params);
        $route = false;
        $index = 0;
        $validity_route = $validity_array->route_api_valid;    

        //testing error handling of route api
        while(!$route){
            $alertRouteE=true;
            if($num_of_attempts>3){
                $errorcode = "Route API service not working, try again in a little bit.";
                return $errorcode;
            }else{
                if($validity_route[$index]==true){
                    $route = get_route($location_params);
                }else{
                    $route = false;
                }
                $num_of_attempts++;
                $index++;
            }
        }

    	$testSteps = parse_route($route);
    	$count = sizeof($testSteps);
    	//this is necessary

	    for ($i = 0; $i<$count; $i++){
	        $rd = $testSteps[$i]->rdName;
	        $sLat = $testSteps[$i]->stLat;
	        $sLo = $testSteps[$i]->stLng;
	        $testSteps[$i]->severity = getRouteTraff($rd, $sLat, $sLo, $condition_params);
	        
	        //there must be a non-zero severity to make a new map
	    }
        //need to prevent default map from showing on refresh
	    $defaultMap = json_encode(false);
	    //testSteps now is the array of structs for the map draw
	    $stepsJSON = json_encode($testSteps);
	
    }
    else if($which_feature == "forecasted_heatmap" || $which_feature == "heatmap"){
		$location_params = array(
				"zip"		 => $loc1,
				"range"		 => $loc2
		);

		$num_of_attempts = 0;
        $index = 0;
		//$latlng_location = get_location($location_params,$which_feature);
        $latlng_location = false;
        $validity_loc = $validity_array->loc_api_valid;

        //testing error handling for geocoding api
        while(!$latlng_location){
            if($num_of_attempts>3){
                $errorcode = "Location API service not working. Try again in a little bit.";
                
                return $errorcode;
            }else{
                if($validity_loc[$index]==true){
                    $latlng_location = get_location($location_params,$which_feature);
                }else{
                    $latlng_location = false;
                }

                $index++;
                $num_of_attempts++;
            }
        }

        //change this to be generic
		$testSteps = getHeatTraff($latlng_location[0],$latlng_location[1],$location_params["range"],$condition_params);
		$count = sizeof($testSteps);
		$stepsJSON = json_encode($testSteps);

		if ($count != 0){
			for ($i = 0; $i<$count; $i++){
		        if ($testSteps[$i]->severity != 0){
		        	$defaultMap = json_encode(false);
		        }
	   		}
		}
	}



  return $errorcode;
}
?>
