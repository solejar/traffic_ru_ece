<?php
//hello I'm  a little map communicator
function get_route($my_location){
    $route_start = $my_location[0];
    $route_end = $my_location[1];

    $loc_api_key = AIzaSyAKKpYTpoXNty9nZq-I1QEL1o1giJt2jvY;
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

?>
