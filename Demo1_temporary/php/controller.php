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

if(isset($_POST["submit"])){
    $alertRouteE = false;
	$defaultMap = json_encode(true);
	$which_feature = $_POST["submit"];
	$loc1 = $_POST["loc1"];
	$loc2 = $_POST["loc2"];
    $forecastF = false;

    $errorcode = "";
    
    $lat_north = "enter me";
    $lat_south = "enter me";
    $lng_west = "enter me";
    $lng_east = "enter me";
    $bbox = array($lat_north,$lat_south,$lng_west,$lng_east);

    if ($which_feature == "forecasted_route" || $which_feature == "forecasted_heatmap"){
    	$forecastF = true;
    }
    
    $hourF = $_POST["time"];
    $fullHour = $hourF;
    $dayF = $_POST["day"];
    $fullDate = $dayF;
    $weatherF = $_POST["weather"];
    $severity = array(false, false, false, false);

    if(!empty($_POST['check_list'])) {
      foreach($_POST['check_list'] as $check) {
        $severity[($check-1)]=true; //echoes the value set in the HTML form for each checked checkbox.
                     //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                   //in your case, it would echo whatever $row['Report ID'] is equivalent to.
      }
    }
	
	
    $tempDate;
	if ($forecastF){
            $tempDate = $dayF;

            $num_of_attempts = 0;
            $routeZip = getZip($loc1, $which_feature);

            while(!$routeZip){
                if($num_of_attempts>3){
                    $errorcode = "API service not working, try again in a little bit.";
                    return;
                }else{
                    $routeZip = getZip($loc1,$which_feature);
                    $num_of_attempts++;
                }
            }
            
            $num_of_attempts = 0;
            $weatherF = getForecast($dayF,$hourF,$routeZip);
            while(!$weatherF){
                if($num_of_attempts>3){
                    $errorcode = "API service not working, try again in a little bit.";
                    return;
                }else{
                    $weatherF = getForecast($dayF,$hourF,$routeZip);
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
    	$route = get_route($location_params);        
        while(!$route){
            $alertRouteE=true;
            if($num_of_attempts>3){
                $errorcode = "API service not working, try again in a little bit.";
                return;
            }else{
                $route = get_route($location_params);
                $num_of_attempts++;
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
		$latlng_location = get_location($location_params,$which_feature);
        while(!$latlng_location){
            if($num_of_attempts>3){
                $errorcode = "API service not working, try again in a little bit.";
                return;
            }else{
                $latlng_location = get_location($location_params,$which_feature);
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
  }
  else{
    $defaultMap = json_encode(true);
  }




?>
