<?php

// struct for the steps
class routeStep {
    public $stLat;
    public $stLng;
    public $ndLat;
    public $ndLng;
    public $rdName;
}

//hello I'm  a little map communicator
function get_route($my_location){
    $route_start = $my_location[0];
    $route_end = $my_location[1];

    $loc_api_key = AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE;
    //$route_url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$route_start."&destination=".$route_end."&key=".$loc_api_key."&alternatives=true"; this is for detours
    $route_url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$route_start."&destination=".$route_end."&key=".$loc_api_key;

    if (!function_exists('curl_init')){
        die('Can\'t find cURL module'); 
    }

    $chRD = curl_init(); //RD = Route Directions

    if (!$chRD){
        die('Couldn\'t initialize a cURL module');  
    }

    curl_setopt($chRD, CURLOPT_URL, $route_url);
    curl_setopt($chRD, CURLOPT_RETURNTRANSFER, TRUE);
            
    $dataRD = curl_exec($chRD);
    curl_close($chRD);

    $routes = json_decode($dataRD);

    return $routes;
}

function parse_route($routeJSON){
    $stepsArr = $routeJSON->routes[0]->legs[0]->steps;
    //print_r($stepsArr[0]->start_location);

    $numSteps = sizeof($stepsArr);
    //echo $numSteps.PHP_EOL;

    $geo_api_key = AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE;


    for ($i = 0; $i < $numSteps; $i++){
        $step = new routeStep();

        // get lat/lng for step's starting point
        $startLat = $stepsArr[$i]->start_location->lat;
        $startLong = $stepsArr[$i]->start_location->lng;

        $step->stLat = $startLat;
        $step->stLng = $startLong;
        $step->ndLat = $stepsArr[$i]->end_location->lat;
        $step->ndLng = $stepsArr[$i]->end_location->lng;
        //echo $startLat.PHP_EOL.$startLong.PHP_EOL;
        
        // get road name of that point
        $geo_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$startLat.",".$startLong."&key=".$geo_api_key;
        
        if (!function_exists('curl_init')){
            die('Can\'t find cURL module'); 
        }

        $chGeo = curl_init(); //RD = Route Directions

        if (!$chGeo){
            die('Couldn\'t initialize a cURL module');  
        }

        curl_setopt($chGeo, CURLOPT_URL, $geo_url);
        curl_setopt($chGeo, CURLOPT_RETURNTRANSFER, TRUE);
            
        $dataGeo = curl_exec($chGeo);
        curl_close($chGeo);

        $ReverseGeo = json_decode($dataGeo);
        //print_r($ReverseGeo);

        //road name of the traffic incident
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

        //echo $roadName.PHP_EOL;

        $step->rdName = $roadName;

        $returnSteps[] = $step;

        $stepsJSON = json_encode($returnSteps);

    }

    return $stepsJSON;

}

$testStart = "75+9th+Ave+New+York,+NY";
$testEnd = "MetLife+Stadium+1+MetLife+Stadium+Dr+East+Rutherford,+NJ+07073";
$testRoutes = array($testStart,$testEnd);

$testJSON = get_route($testRoutes);
$testSteps = parse_route($testJSON);

$count = sizeof($testSteps);
echo $count.PHP_EOL;
for ($i = 0; $i<$count; $i++){
    echo "Step: ".$i.": ".PHP_EOL;
    echo "Starting Coordinates: ".$testSteps[$i]->stLat.", ".$testSteps[$i]->stLng.PHP_EOL;
    echo "Ending Coordinates: ".$testSteps[$i]->ndLat.", ".$testSteps[$i]->ndLng.PHP_EOL;
    echo "Road Name: ".$testSteps[$i]->rdName.PHP_EOL.PHP_EOL;
}

?>
