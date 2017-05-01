<?php

//written by: Mhammed Alhayek
//Tested by: Mhammed Alhayek, Lauren Williams, Shubhra Paradkar
//debugged by: Mhammed Alhayek

//This function will get the current gas price from the fueleconomy.gov API
function get_gas_price(){
	$gas_url = "http://www.fueleconomy.gov/ws/rest/fuelprices";

	// checks to see if server has curl_init function
    if (!function_exists('curl_init')){
        die('Can\'t find cURL module'); 
    }

    $chGas = curl_init(); 

    // making sure $chGas is successfully initialized
    if (!$chGas){
        die('Couldn\'t initialize a cURL module');  
    }

    curl_setopt($chGas, CURLOPT_URL, $gas_url);
    curl_setopt($chGas, CURLOPT_RETURNTRANSFER, TRUE);
    
    // $XMLGas is the json returned from the api call        
    $XMLGas = curl_exec($chGas);
    curl_close($chGas);

    if(!($parse_xml = simplexml_load_string($XMLGas))){ // failure
        return false;
        die("Error: Cannot create object");
    }

    //Parsing through the returned JSON object
    $gas_prices = array(
				"regular"		 => $parse_xml->regular[0],
				"midgrade"		 => $parse_xml->midgrade[0],
                "premium"        => $parse_xml->premium[0],
                "diesel"         => $parse_xml->diesel[0],
	);

    return $gas_prices;

}

// distance must be in meters, which is what google returns
// this function will calculate the gas cost for the specific route and car make/model
function route_cost($gas_price, $vehicle_mpg, $distance){
	$distance_miles = $distance*0.000621371;
	$cost = ((float) $gas_price)*$distance_miles/$vehicle_mpg;

	//echo $gas_price.PHP_EOL.$distance_miles.PHP_EOL.$vehicle_mpg.PHP_EOL.$cost.PHP_EOL;
	return number_format($cost, 2);
}

?>
