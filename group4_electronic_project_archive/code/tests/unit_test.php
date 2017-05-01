<html>
  <head>
    <title>Unit Tests</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
  </head>

<?php
    include("../php/controller.php");
	echo "Onward Traffic Unit Testing<br><br>";
	echo "Unit Testing for Weather Collector Functions: <br>";
	// ----------------- Testing Weather Collector ----------------- //
	//include("weather_collector.php");

	//written by: Lauren Williams, Shubhra Paradkar, Mhammed Alhayek
	//tested by: Lauren Williams, Shubhra Paradkar, Mhammed Alhayek
	//debugged by: Lauren Williams, Shubhra Paradkar, Mhammed Alhayek

	$timeWC = "23";
	$zipWC = "08817";
	date_default_timezone_set("America/New_York");
	$myDate = date('F d Y ');

	$weather=getForecast($myDate, $timeWC, $zipWC);

	echo "Testing Weather Collector getForecast. Expected Result: Passed<br>";
	if (!$weather){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Weather was: ".$weather."<br>";
	}

	echo "Testing Weather Collector getForecast. Expected Result: Failed<br>";

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
    //include("traffic_collector.php");
    $testLat = 40.946605;
    $testLng = -74.075082;
    $testRange = 15;
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

    $traffTest = query_heat_db($testLat, $testLng, $testRange);

	echo "Testing Traffic Collector query_heat_db(). Expected Result: Passed<br>";
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "First traffic severity was: ".$traffTest[0]->severity[$testDay][$testTime][$testWeath]."<br>";
	}
	
	$testLat = 0;
    $testLng = -50;
    	
	$loc_params = array(
		"cent_lat" => $testLat,
		"cent_lng" => $testLng,
		"range"    => $testRange,
	);

    $traffTest = query_heat_db($testLat, $testLng, $testRange);	


	echo "Testing Traffic Collector query_heat_db(). Expected Result: Failed<br>";
	
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "First traffic severity was: ".$traffTest[0]->severity[$testDay][$testTime][$testWeath]."<br>";
	}
	
	$testLat = 40.713757;
    $testLng = -74.1402826;
	$testRd = "New Jersey Turnpike";
	
	$loc_params = array(
		"roadName" => $testRd,
		"startLat" => $testLat,
		"startLong"=> $testLng, 
	);
	$traffTest = get_segment_sev($testRd, $testLat, $testLng);
	
	echo "Testing Traffic Collector get_segment_sev(). Expected Result: Passed<br>";
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Traffic severity was: ".$traffTest[$testDay][$testTime][$testWeath]."<br>";
	}
	
	$testLat = 0;
    $testLng = -50;
	
	$loc_params = array(
		"roadName" => $testRd,
		"startLat" => $testLat,
		"startLong"=> $testLng, 
	);
	$traffTest = get_segment_sev($testRd, $testLat, $testLng);
	
	echo "Testing Traffic Collector get_segment_sev(). Expected Result: Failed<br>";
	
	if (!$traffTest){
		echo "Failed<br>";
	}
	else{
		echo "Passed<br>";
		echo "Traffic severity was: ".$traffTest[$testDay][$testTime][$testWeath]."<br>";
	}
	
	echo "<br><br>";
	echo "Unit Testing for Location Converter Functions: <br>";

	// ----------------- Testing Location Converter ----------------- //
	//include("loc_converter.php");
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
    
    echo "Testing getZip for Route. Expected Result: Failed<br>";
    $testZip = getZip($locParamsLCRoute2["start"], $feature2);

    if (!$testZip){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZip."<br>";
    }

    echo "Testing getZip for Heat Map. Expected Result: Passed<br>";
    $testZipHeat = getZip($locParamsLC1["zip"], $feature1);

    if (!$testZipHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Return Zip Code: ".$testZipHeat."<br>";
    }
    
    echo "Testing getZip for Heat Map. Expected Result: Failed<br>";
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
	    echo "Returns: ".$testHeat[0].", ".$testHeat[1]."<br>";
    }
    
    echo "Testing callLocServHeat. Expected Result: Failed<br>";
    $testHeat = callLocServHeat($locParamsLC2);

    if (!$testHeat){
	    echo "Failed.<br>";
    }
    else {
	    echo "Passed. ";
	    echo "Returns: ".$testHeat[0].", ".$testHeat[1]."<br>";
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
	    echo "Returns: ".$testRoute[0][0].", ".$testRoute[0][1]."<br>";
    }
	
	echo "<br><br>";
	echo "Unit Testing for Map Communicator Functions: <br>";

	// ----------------- Testing Map Communicator ----------------- //
    //include("map_communicator.php");

    $my_loc_test= array(
        "start"=> "2035 Basswood Ct, Toms River, NJ 08755",
        "end"=> "Times Square New York, New York",
        "alternative"    => "false"
    );
    $testJSON=get_route($my_loc_test);
    echo "Testing Map Communicator get_route. Expected Result: Passed. <br>";
    if(!$testJSON){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "First Instruction is: ".$testJSON[0]->legs[0]->steps[0]->html_instructions."<br>";
        echo "Distance is: ".parse_distance($testJSON,0)."<br>";
    }
    
    $my_loc_test= array(
        "start"=> "abcdefghijklmnopqrstuvwxyz",
        "end"=> "Times Square New York, New York",
        "alternative"    => "false"
    );
    $testJSONFail=get_route($my_loc_test);
    echo "Testing Map Communicator get_route. Expected Result: Failed. <br>";
    if(!$testJSONFail){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
		echo "First Instruction is: ".$testJSONFail[0]->legs[0]->steps[0]->html_instructions."<br>";
    }

    echo "Testing Map Communicator parse_route. Expected Result: Passed. <br>";
    $rtn = parse_route($testJSON, 0);
    if(!$rtn){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "First Road Name is: ".$rtn[0]->rdName."<br>";
    }

    echo "Testing Map Communicator parse_route. Expected Result: Failed. <br>";
    $rtn = parse_route($testJSONFail, 0);
    if(!$rtn){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "First Road Name is: ".$rtn[0]->rdName."<br>";
    }

    echo "<br><br>";
	echo "Unit Testing for Gas Calculator Functions: <br>";
    // ----------------- Testing Gas Calculator ----------------- //
    //include("gas_calculator.php");

    echo "Testing Gas Calculator get_gas_price. Expected Result: Passed. <br>";

    $test_gas_prices = get_gas_price();

    if(!$test_gas_prices){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "Regular Gas Price is: $".$test_gas_prices["regular"]."<br>";
    }

    echo "Function get_gas_price() takes no parameters, therefore can't force failure.<br><br>";

    echo "Testing Gas Calculator route_cost. Expected Result: Passed. <br>";

    $test_cost = route_cost(2.44, 25, 100000);
    if($test_cost != 6.06){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "Route Cost is: $".$test_cost."<br>";
    }

    echo "Testing Gas Calculator route_cost. Expected Result: Failed. <br>";

    $test_cost = route_cost(2.44, "twenty five", 100000);
    if($test_cost != 6.06){
        echo "Failed<br>";
    }
    else{
        echo "Passed<br>";
        echo "Route Cost is: $".$test_cost."<br>";
    }
?>

<!-- -------------------------- Testing Screen Display ----------------------------- -->
<html>
	</br>Testing Screen Display Functions</br>

	<div id = "map-canvas" style="height: 50%; width:50%"></div>
	<div id = "map-canvas2" style="height: 50%; width:50%"></div>
</html>
<script>
  // setting map options
  var myOptions = {
    mapTypeId: 'roadmap',
    center: {lat: 40.523148, lng: -74.458818},
    zoom: 8
  };

  var myOptionsRoute = {
    mapTypeId: 'roadmap'
  };

  // NOTE: for the options above, it is possible to choose where to center the map is when it loads

var renderOptionsSevDefault = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "#4285f4",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 2}
  }

  // declaring map variable
  var map;
  var default_map_test = true;

  // this initMap() function gets called when the website loads
  function initMap() {
	map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
	routeMap();
  }

  function routeMap() {
    // setting map to display in the "map-canvas" div
    map = new google.maps.Map(document.getElementById("map-canvas2"), myOptionsRoute);
    // dirService variable used for finding routes
    var dirService = new google.maps.DirectionsService();   

    // you need a new DirectionsRenderer for each new polyline, this is called in requestDirections function
    // when using alternate directions, routeIndex = 1, else = 0
    function renderDirections(result, options, routeIndex){
        var dirRenderer = new google.maps.DirectionsRenderer(options);
        dirRenderer.setMap(map);
        dirRenderer.setDirections(result);
        dirRenderer.setRouteIndex(routeIndex); 
    }

    // this is the function used for each route to display, options is where you choose the color
    // when using alternate directions, routeIndex = 1, else = 0
    function requestDirections(start,end,options, routeIndex){
        var request = {
            origin: start,
            destination: end,
            //waypoints: [{location:"48.12449,11.5536"}, {location:"48.12515,11.5569"}],
            travelMode: google.maps.TravelMode.DRIVING
        };
        dirService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                renderDirections(result,options, routeIndex);
            }
        });
    }

    var renderOption;

    requestDirections("40.503291, -74.451948", "40.343842, -74.651952", renderOptionsSevDefault)

   
  }

</script>

<!-- Call the Google API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
async defer></script>
