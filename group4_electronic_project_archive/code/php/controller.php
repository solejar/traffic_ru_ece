<?php

//written by: Mhammed Alhayek, Sean Olejar
//Tested by: Mhammed Alhayek, Sean Olejar
//Debugged by: Mhammed Alhayek, Sean Olejar

//including all the module php scripts
include 'traffic_collector.php';
include 'weather_collector.php';
include 'map_communicator.php';
include 'loc_converter.php';
include 'gas_calculator.php';

//This is the controller, which will handle the flow

//if no POSTed inputs, use the default Google Map
$defaultMap = json_encode(true);

//default alternatives to false
$alternatives = json_encode(false);

//default conditions
$severity[0] = true;
$severity[1] = true;
$severity[2] = true;
$severity[3] = true;

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
        //connection to other tables can go here in the future
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

//Whether the forecast or no forecast has been registered
if(isset($_POST["how_weather"])){
    $_POST["submitBtn"] = $_POST["how_weather"];
}

//if inputs have been POSTed, try making a map
if(isset($_POST["submitBtn"])){ 

    //receive POSTed inputs
	$which_feature = $_POST["how_weather"];

    //need to compare old against new...this lets us know
    //if we need to update traffic or just the forecast
    if(!is_null($loc1) && !is_null($loc2)){
        $old_loc1 = $loc1;
        $old_loc2 = $loc2;
    }else{
        $old_loc1 = 0;
        $old_loc2 = 0;
    }

	$loc1 = $_POST["loc1"];
	$loc2 = $_POST["loc2"];    
    
    //need to get forecasted weather back up to html level

    $weatherF = $_POST["weather"];        
    $hourF = $_POST["time"];

    // if its checked off, change it to true
    if (!empty($_POST["alternative"])){
        $alternatives = $_POST["alternative"];
    }

    //creating a new severity array and setting the default values to false
    $severity = array(false, false, false, false);

    //Change the severity array if the user has checked off some severity checkboxes
    if(!empty($_POST['check_list'])) {
        foreach($_POST['check_list'] as $check) {
            $severity[($check-1)]=true; 
        }
    }

    //errorcode defaults to a null string
    $errorcode = "";
	
    //set forecast based on feature type
    $forecastF = false;
    if ($which_feature == "forecasted_route" || $which_feature == "forecasted_heatmap"){
        $forecastF = true;
        $dayF = $_POST["date"];
    }
    else if ($which_feature == "route" || $which_feature == "heatmap"){
        $dayF = $_POST["day"];
    }

    //this is the date/time as string
    //we store it in temp vars before it gets formatted to a numeric representation
    $fullHour = $hourF;
    $fullDate = $dayF;

    $tempDate = $fullDate;
	
    //if forecasting, need to parse day of week from a specific user date.
	if ($forecastF){
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
        $dayF = date( "l", strtotime($dayF));

    }

    //If location hasn't changed, no more needs to be done, so exit
    if(just_forecast($loc1,$loc2,$old_loc1,$old_loc2)){
        exit();
    }

    //This will only run if the route feature is selected
	if($which_feature=="route" || $which_feature == "forecasted_route"){
		
        $show_gas = $_POST["show_gas"];
        $mpg = $_POST["mpg"];
        $fuel_type = $_POST["fuel_type"];

        //If the user would like to see the gas value, get the current price
        if ($show_gas){

            $num_of_attempts = 0;
            $all_gas_prices = get_gas_price();

            //try calling gas api 3 times. if it fails 3 times, exit w/ error
            while(!$all_gas_prices){
                if($num_of_attempts>3){
                    $errorcode = "Gas cost calculator not working, try again in a little bit.";
                    return;

                }else{
                    $all_gas_prices = get_gas_price();
                    $num_of_attempts++;
                }
            }
        }

        //associative array of loc_params
        $location_params = array(
				"start"		     => $loc1,
				"end"		     => $loc2,
                "alternative"    => $alternatives
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
        // number of routes is always 1 or 2 because we are only doing one alternative
        $num_of_routes = ( sizeof($route)>1 ? 2 : 1);  

        //parse the route and get count of steps
        for ($i = 0; $i<$num_of_routes; $i++){
            $testSteps = parse_route($route, $i);
            $count[] = sizeof($testSteps);

            $empty_sev_template = create_sev_array();

            //collect traffic severity for every leg of the route
            for ($j = 0; $j<$count[$i]; $j++){

                $loc_params = array(
                    "roadName"   => $testSteps[$j]->rdName,
                    "startLat" => $testSteps[$j]->stLat,
                    "startLong"  => $testSteps[$j]->stLng,
                );
                
                //insert traffic into sevs array
                $sev_array = get_traff($loc_params,$which_feature);

                $testSteps[$j]->severity = $sev_array;
                
                //No error handling needed here because even if no severities returned, blue line connects start/stop
            }

            if ($show_gas){
                $route_dist = parse_distance($route, $i);
                $route_cost[] = route_cost($all_gas_prices[$fuel_type], $mpg, $route_dist);
            }

            //testSteps now is the array of structs for the map draw
            $stepsJSON[] = json_encode($testSteps);
        }

        $primIndex = 0;
        $altIndex = 1;

        //disable the default map
	    $defaultMap = json_encode(false);
	
    }
    //Code to execute the heatmap
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

        //creating the location parameters array 
        $loc_params = array(
            "cent_lat" => $latlng_location[0],
            "cent_lng" => $latlng_location[1],
            "range" => $location_params["range"],
        );

        $testSteps = array();

        $testSteps = get_traff($loc_params,$which_feature);

        //try getting traff severity for heatmap. if it fails, exit w/ error
        if(!$testSteps){
            $errorcode = "Your inputs are returning no traffic. Make sure that your search is within the tri-state area, and try to make it more general if possible!";
            return;
        }

        //get the steps and encode them for the map drawing
		$count = sizeof($testSteps);
		$stepsJSON = json_encode($testSteps);

        //set the default to false
		$defaultMap = json_encode(false);

	}
}


?>
