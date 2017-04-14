<?php
	echo "Onward Traffic Unit Testing<br><br>";
	echo "Unit Testing for Weather Collector Functions: <br>";
	// ----------------- Testing Weather Collector ----------------- //
	include("WeatherCollector.php");
	$timeWC = "23";
	$zipWC = "08817";
	date_default_timezone_set("America/New_York");
	$myDate = date('F d Y ');

	$weather=getForecast($myDate, $timeWC, $zipWC);

	echo "Testing Weather Collector getForecast Expected Result: Passed<br>";
	if (!$weather){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Weather was: ".$weather."<br>";
	}

	echo "Testing Weather Collector getForecast Expected Result: Failed<br>";

	$weatherFail = getForecast($myDate, '23', '00000');
	//echo $weatherFail.""<br>"";
	if (!$weatherFail){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Weather was: ".$weather."<br>";
	}

	echo "<br><br>";
	echo "Unit Testing for Traffic Collector Functions: <br>";
	// ----------------- Testing Traffic Collector ----------------- //
    include("TrafficCollector.php");
    $testLat = 40.946605;
    $testLng = -74.075082;
    $testRange = 20;
    $testWeath = "Clear";
    $testSev = array(false, false, false, true);
    $testTime = 12;
    $testDay = "Monday";
    $testInputConditions = array(
				"weather"		 => $testWeath,
				"severity"		 => $testSev,
                "time"           => $testTime,
                "day"            => $testDay,
	);
    
	$loc_params = array(
		"cent_lat" => $testLat,
		"cent_lng" => $testLng,
		"range"    => $testRange,
	);

    $traffTest = get_traff($loc_params, $testInputConditions,"heatmap");

	echo "Testing Traffic Collector getHeatTraff Expected Result: Passed<br>";
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "First traffic severity was: ".$traffTest[0]->severity."<br>";
	}
	
	$testLat = 0;
    $testLng = -50;
    	
	$loc_params = array(
		"cent_lat" => $testLat,
		"cent_lng" => $testLng,
		"range"    => $testRange,
	);

    $traffTest = get_traff($loc_params, $testInputConditions,"heatmap");	


	echo "Testing Traffic Collector getHeatTraff Expected Result: Failed<br>";
	
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "First traffic severity was: ".$traffTest[0]->severity."<br>";
	}
	
	$testLat = 40.713757;
    $testLng = -74.1402826;
	$testRd = "New Jersey Turnpike";
	
	$loc_params = array(
		"roadName" => $testRd,
		"startLat" => $testLat,
		"startLong"=> $testLng, 
	);
	$traffTest = get_traff($loc_params, $testInputConditions,"route");
	
	echo "Testing Traffic Collector getRouteTraff Expected Result: Passed<br>";
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Traffic severity was: ".$traffTest."<br>";
	}
	
	$testLat = 0;
    $testLng = -50;
	
	$loc_params = array(
		"roadName" => $testRd,
		"startLat" => $testLat,
		"startLong"=> $testLng, 
	);
	$traffTest = get_traff($loc_params, $testInputConditions,"route");
	
	echo "Testing Traffic Collector getRouteTraff Expected Result: Failed<br>";
	
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Traffic severity was: ".$traffTest."<br>";
	}
	
	echo "<br><br>";
	echo "Unit Testing for Location Converter Functions: <br>";
	// ----------------- Testing Location Converter ----------------- //
	include("loc_converter.php");
	$locParamsLC1 =  array(
				"zip"        => "08816",
				"range"      => "10",
			);

    $locParamsLCRoute1 =  array(
				"start"      => "504 Merrywood Drive Edison NJ",
				"end"      => "7 Hancock Court East Brunswick NJ",
			);
			
	$locParamsLC2 =  array(
				"zip"        => "0881645678",
				"range"      => "10",
			);

    $locParamsLCRoute2 =  array(
				"start"      => "",
				"end"      => "7 Hancock Court East Brunswick NJ",
			);

    $feature1 = "heatmap";
    $feature2 = "route";

	echo "Testing getZip for Route. Expected Result: Passed<br>";
    $testZip = getZip($locParamsLCRoute1["start"], $feature2);
    if (!$testZip){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZip."<br>";
    }
    
    echo "Testing get_Zip for Route. Expected Result: Failed<br>";
    $testZip = getZip($locParamsLCRoute2["start"], $feature2);

    if (!$testZip){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZip."<br>";
    }

    echo "Testing get_Zip for Heat Map. Expected Result: Passed<br>";
    $testZipHeat = getZip($locParamsLC1["zip"], $feature1);

    if (!$testZipHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZipHeat."<br>";
    }
    
    echo "Testing get_Zip for Heat Map. Expected Result: Failed<br>";
    $testZipHeat = getZip($locParamsLC2["zip"], $feature1);

    if (!$testZipHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZipHeat."<br>";
    }
    
	echo "Testing callLocServHeat. Expected Result: Passed<br>";
    $testHeat = callLocServHeat($locParamsLC1);
    
    if (!$testHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Returns: ".$testHeat[0].$testHeat[1]."<br>";
    }
    
    echo "Testing callLocServHeat. Expected Result: Failed<br>";
    $testHeat = callLocServHeat($locParamsLC2);

    if (!$testHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Returns: ".$testHeat[0].$testHeat[1]."<br>";
    }
	
	echo "Testing callLocServRoute. Expected Result: Passed<br>";
    $testRoute = callLocServRoute($locParamsLCRoute1);
    if (!$testRoute){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Returns: ".$testRoute[0][0].", ".$testRoute[0][1]."<br>";
    }
    
    echo "Testing callLocServRoute. Expected Result: Failed<br>";
    $testRoute = callLocServRoute($locParamsLCRoute2);

    if (!$testRoute){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Returns: ".$testRoute[0].$testRoute[1]."<br>";
    }
	
	echo "<br><br>";
	echo "Unit Testing for Map Communicator Functions: <br>";
	// ----------------- Testing Map Communicator ----------------- //
    include("map_communicator.php");

    $my_loc_test= array(
        "start"=> "2035 Basswood Ct, Toms River, NJ 08755",
        "end"=> "Times Square New York, New York"
    );
    $rtn=get_route($my_loc_test);
    echo "Testing Map Communicator route. Expected Result: Passed. <br>";
    if(!$rtn){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "First Instruction is: ".$rtn->routes[0]->legs[0]->steps[0]->html_instructions."<br>";
    }
    
    $my_loc_test= array(
        "start"=> "abcdefghijklmnopqrstuvwxyz",
        "end"=> "Times Square New York, New York"
    );
    $rtn=get_route($my_loc_test);
    echo "Testing Map Communicator route. Expected Result: Failed. <br>";
    if(!$rtn){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
		echo "First Instruction is: ".$rtn->routes[0]->legs[0]->steps[0]->html_instructions."<br>";
    }

?>
