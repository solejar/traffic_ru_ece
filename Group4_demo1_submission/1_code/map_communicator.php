<?php

// struct for the steps
class routeStep {
    public $stLat;
    public $stLng;
    public $ndLat;
    public $ndLng;
    public $rdName;
    public $severity;
}

// function returns a decoded json for the route to get from my_location[start] to my_location[end]
function get_route($my_location){
    // get starting and ending location from $my_location
    $route_start = urlencode($my_location["start"]);
    $route_end = urlencode($my_location["end"]);

    // api key used for google maps api
    $loc_api_key = AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE;

    // $route_url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$route_start."&destination=".$route_end."&key=".$loc_api_key."&alternatives=true"; this is for detours
    
    // url for the api call
    $route_url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$route_start."&destination=".$route_end."&key=".$loc_api_key;

    // checks to see if server has curl_init function
    if (!function_exists('curl_init')){
        die('Can\'t find cURL module'); 
    }

    $chRD = curl_init(); //RD = Route Directions

    // making sure $chRD is successfully initialized
    if (!$chRD){
        die('Couldn\'t initialize a cURL module');  
    }

    curl_setopt($chRD, CURLOPT_URL, $route_url);
    curl_setopt($chRD, CURLOPT_RETURNTRANSFER, TRUE);
    
    // $dataRD is the json returned from the api call        
    $dataRD = curl_exec($chRD);
    curl_close($chRD);

    // decodes the json
    $routes = json_decode($dataRD);

    // if the api call return wasn't successful, return false
    if ($routes->status != "OK"){
        return false;
    }

    // return the decoded json
    return $routes;
    
}

// parses the json for the route to return an array of steps
// a step is a struct defined above
function parse_route($routeJSON){

    // parsing the json for the part that contains the steps for the directions
    $stepsArr = $routeJSON->routes[0]->legs[0]->steps;

    // number of steps
    $numSteps = sizeof($stepsArr);


    //$geo_api_key = AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE;       // extra api key
    $geo_api_key = AIzaSyBuY2jslM2opU5EqACMofNnJRxEog6KVEs;         // api key used

    // iterate through all steps
    for ($i = 0; $i < $numSteps; $i++){
        // initialize a new step
        $step = new routeStep();

        // get lat/lng for step's starting and ending points
        $startLat = $stepsArr[$i]->start_location->lat;
        $startLong = $stepsArr[$i]->start_location->lng;

        $step->stLat = $startLat;
        $step->stLng = $startLong;
        $step->ndLat = $stepsArr[$i]->end_location->lat;
        $step->ndLng = $stepsArr[$i]->end_location->lng;

        
        // get road name of the starting point
        $geo_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$startLat.",".$startLong."&key=".$geo_api_key;

        // checks to see if server has curl_init function
        if (!function_exists('curl_init')){
            die('Can\'t find cURL module'); 
        }

        $chGeo = curl_init(); //geocoding curl

        // making sure $chGeo is successfully initialized
        if (!$chGeo){
            die('Couldn\'t initialize a cURL module');  
        }

        curl_setopt($chGeo, CURLOPT_URL, $geo_url);
        curl_setopt($chGeo, CURLOPT_RETURNTRANSFER, TRUE);
            
        // $dataGeo is the json returned from the api call
        $dataGeo = curl_exec($chGeo);
        curl_close($chGeo);

        // decodes the json
        $ReverseGeo = json_decode($dataGeo);

        // if the api call return wasn't successful, return false
        if ($ReverseGeo->status != "OK"){
            return false;
        }

        // Parsing the road name of the incident, saving it in $roadName
        $type = $ReverseGeo->results[0]->address_components[0]->types[0];

        if ($type === "street_number"){
            $type1 = $ReverseGeo->results[0]->address_components[1]->types[0];
            if ($type1 != "street_number"){
                $roadName = $ReverseGeo->results[0]->address_components[1]->long_name;
            }
        }
        else{
            $roadName = $ReverseGeo->results[0]->address_components[0]->long_name;
        }

        // save the road name for that step
        $step->rdName = $roadName;

        // append that step to the end of the array
        $returnSteps[] = $step;


    }

    // return the $returnSteps
    return $returnSteps;

}


?>
