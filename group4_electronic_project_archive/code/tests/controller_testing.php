<?php
//this can be run by going to onwardtraffic.com/tests/integration_test.php

//written by: Sean Olejar
//debugged by: Sean Olejar
//tested by: Sean Olejar


//including all the module php scripts
include '../php/traffic_collector.php';
include '../php/weather_collector.php';
include '../php/map_communicator.php';
include '../php/loc_converter.php';
include '../php/gas_calculator.php';

//THIS IS THE VERSION FOR TESTING THE CONTROLLER
//GOOGLE MAPS DOESNT PRINT THE RESULTS OF THIS SCRIPT

//This function will connect to the traffic database
function connect_to_db($which_db){

    $password   = "briansbutt";

    if($which_db == "traffic"){
        $traff_host_name = "db667824699.db.1and1.com";
        $traff_database  = "db667824699";
        $traff_user_name = "dbo667824699";
 
        //traffic database connection
        $conn = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
        // Check connection
        if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }elseif($which_db == "frequency"){
        //connection to frequency table can go here..
    }

    return $conn;
}

//If location hasn't changed, function will return true
function just_forecast($loc1,$loc2,$old_loc1,$old_loc2){
    if($loc1==$old_loc1 && $loc2 == $old_loc2){
        return true;
    }else{
        return false;
    }
}

//this function lets me test controller file with various "valid" and "invalid" input conditions
function test_func($validity_array,$userParams){
//if(isset($_POST["submit"])){
    
    if($validity_array->input_valid==true){
    	$defaultMap = json_encode(true);
    	$which_feature = $userParams["which_feature"];
    	$loc1 =          $userParams["loc1"];
    	$loc2 =          $userParams["loc2"];
        $forecastF =     $userParams["forecast"];
        $hourF =         $userParams["time"];
        $dayF =          $userParams["day"];
        $weatherF =      $userParams["weather"];

        $errorcode = "";

    }else{
        $errorcode = "Inputs are invalid. Try again";
        return $errorcode;
    }

	if ($forecastF){

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

        $show_gas = $userParams["show_gas"];
        $mpg = $userParams["mpg"];
        $fuel_type = $userParams["fuel_type"];


        //If the user would like to see the gas value, get the current price
        if ($show_gas){

            $num_of_attempts = 0;
            //$all_gas_prices = get_gas_price();
            $all_gas_prices = false;
            $index = 0;

            $validity_gas = $validity_array->gas_api_valid; 

            //try calling gas api 3 times. if it fails 3 times, exit w/ error
            while(!$all_gas_prices){
                if($num_of_attempts>3){
                    $errorcode = "Gas cost calculator not working, try again in a little bit.";

                    return $errorcode;
                }else{
                    if($validity_gas[$index]==true){
                        $all_gas_prices = get_gas_price();
                    }else{
                        $all_gas_prices = false;
                    }
                    $index++;
                    $num_of_attempts++;
                }
            }

            
        }

        $num_of_attempts = 0;
    	//$route = get_route($location_params);
        $route = false;
        $index = 0;
        $validity_route = $validity_array->route_api_valid;   

        //testing error handling of route api
        while(!$route){
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
	        $loc_params = array(
    	        "roadName"   => $testSteps[$i]->rdName,
    	        "startLat" => $testSteps[$i]->stLat,
    	        "startLong"  => $testSteps[$i]->stLng,
            	);
	        $testSteps[$i]->severity = get_traff($loc_params, $condition_params,$which_feature);
	        
	        //there must be a non-zero severity to make a new map
	    }
        //need to prevent default map from showing on refresh
	    $defaultMap = json_encode(false);
	    //testSteps now is the array of structs for the map draw
	    $stepsJSON = json_encode($testSteps);

        if ($show_gas){
            $route_dist = parse_distance($route, $i);
            $route_cost[] = route_cost($all_gas_prices[$fuel_type], $mpg, $route_dist);
        }
	
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

        $loc_params = array(
            "cent_lat" => $latlng_location[0],
            "cent_lng" => $latlng_location[1],
            "range" => $location_params["range"],
        );

	$testSteps = get_traff($loc_params,$condition_params,$which_feature);
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
