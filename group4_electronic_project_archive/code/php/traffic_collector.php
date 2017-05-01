<?php 

//written by: Sean Olejar, Mhammed Alhayek
//Tested by: Lauren Williams, Shubhra Paradkar, Mhammed Alhayek, Sean Olejar
//Debugged by: Sean Olejar, Mhammed Alhayek


//struct for each traffic segment
//knows start coordinates, end coordinates, roadname, & traff severity
class trafficSegment {
    public $stLat;
    public $stLng;
    public $ndLat;
    public $ndLng;
    public $rdName;
    public $severity;
}

$weather_options = array('AllW','Clear', 'Snow', 'Cloudy', 'Rain', 'Fog');
$time_options = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$day_options = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

//creates a template array of weather, time, and day options 
//individual severity will be accessed like: array[day][hour][weather];
function create_sev_array(){

	global $day_options;
	global $weather_options;
	global $time_options;

    $d_array = array();
    foreach($day_options as $day){
        $t_array = array();
        foreach($time_options as $time){
            $w_array = array();
            foreach($weather_options as $weather){
                $w_array[$weather] = false;
            }
            $t_array[$time] = $w_array;
        }
        $d_array[$day] = $t_array;
    }

    return $d_array;
}

//SQL table represents day as number, but we need it as string
//so this function does that switch
function switch_day($inputDay){
		$_day;
	switch ($inputDay){
		case 0:
			$_day = "Sunday";
			break;
		case 1:
			$_day = "Monday";
			break;
		case 2:
			$_day = "Tuesday";
			break;
		case 3:
			$_day = "Wednesday";
			break;
		case 4:
			$_day = "Thursday";
			break;
		case 5:
			$_day = "Friday";
			break;
		case 6:
			$_day = "Satuday";
	}
	return $_day;
}

//SQL table represents weather  as show in the "cases"
//but we need it as a string, so we switch it here
function switch_weather($inputWeather){
		$_weather;
	switch ($inputWeather){
	case "avg_clear":
		$_weather = "Clear";
		break;
	case "avg_snow":
		$_weather = "Snow";
		break;
	case "avg_cloudy":
		$_weather = "Cloudy";
		break;
	case "avg_rain":
		$_weather = "Rain";
		break;
	case "avg_fog":
		$_weather = "Fog";
		break;
	}

	return $_weather;
}

//this function is called by both route/heatmap to get traffic severities
//that we can print
function get_traff($location_params,$feature){

	//get traffic for route
	if($feature == "route" || $feature == "forecasted_route"){

		$roadName = $location_params["roadName"];
		$startLat = $location_params["startLat"];
		$startLong = $location_params["startLong"];
		
		$results = get_segment_sev($roadName, $startLat, $startLong);

	//get traffic for heat map
	}else if($feature == "heatmap"|| $feature == "forecasted_heatmap"){

		$cent_lat = $location_params["cent_lat"];
		$cent_lng = $location_params["cent_lng"];
		$query_range = $location_params["range"];

		$results = query_heat_db($cent_lat,$cent_lng,$query_range);
	}

	return $results;
}

//get traff sev from db for heatmap use case
function query_heat_db($cent_lat,$cent_lng,$query_range){

	$traff_con = connect_to_db("traffic");

	//select all the grid id's within the radius specified by the user
	$latRange = $query_range*.0144927536; 		// .0144927536 lat degrees in a mile
	$longRange = $query_range*.0183150183;		// .0183150183 long degrees in a mile 

	$id_arr = array();
	$query = "SELECT id FROM grid WHERE latcenter<= $cent_lat+$latRange AND latcenter >= $cent_lat-$latRange AND lngcenter>=$cent_lng-$longRange AND lngcenter <=$cent_lng+$longRange";
	$result = mysqli_query($traff_con,$query);

	//grab all the grid id's
	$result_temp = $result;
	while($row_temp = $result_temp->fetch_assoc()){
		array_push($id_arr, $row_temp["id"]);
	}

	//if no grid id's were found, return false;
	if(sizeof($id_arr) <= 0){
		return false;
	}

	//get the road segment and average severity info based on inputs
	$sevquery = "SELECT * FROM (severity as s INNER JOIN bounds as b ON (s.gridId = b.grid_id AND b.road_name = s.roadName))
				WHERE end_lat IS NOT NULL AND end_lng IS NOT NULL AND (grid_id IN (".implode(',',$id_arr)."))";

	$sevresult = mysqli_query($traff_con,$sevquery);

	//if this query fails, return false
	if(!$sevresult){
		return false;
	}

	//array will hold traffic along all roads in query reqion
	$road_array = array();

	//tripwire, for error handling
	$anything_returned = false;

	while($sevrow = $sevresult->fetch_assoc()){
		
		//extract the info about each one and assign it to a trafficSegment object
		$newSegment = new trafficSegment();

		$temp_lat = $sevrow["start_lat"];
		$temp_lng = $sevrow["start_lng"];
		$temp_name = $sevrow["road_name"];

		$newSegment->stLat = $temp_lat;
		$newSegment->stLng = $temp_lng;
		$newSegment->ndLat = $sevrow["end_lat"];
		$newSegment->ndLng = $sevrow["end_lng"];
		$newSegment->rdName = $temp_name;
		
		//for each road segment in query region, get the traffic along it
		$sev_array = get_segment_sev($temp_name,$temp_lat,$temp_lng);

		//if any traffic was found, trigger tripwire
		if($sev_array != false){
			$anything_returned = true;
		}
		
		$newSegment->severity = $sev_array;

		//add this segment to the output array
		$road_array[] = $newSegment;
	}

	if($anything_returned){
		return $road_array;
	}else{
		//this is for error handling if nothing was returned
		return false;
	}
	

}

//get traff sev along a single road segment
function get_segment_sev($roadName, $startLat, $startLong){
	
	$sev_array = create_sev_array();
	$traff_con = connect_to_db("traffic");

	//get gridid for the starting location
	$q10 = "SELECT id FROM grid WHERE ('$startLat' >= latS AND '$startLat' <= latN AND '$startLong' >= longW AND '$startLong' <= longE)";
	$res2 = mysqli_query($traff_con,$q10);
	//for only the first grid box the incident is contained in...
	if($res2){
		if($row = $res2->fetch_assoc()){
			$gridID = $row["id"];
		}
	}
	//if the query fails, return false
	else{
		return false;
	}
	

	// query db for all traffic on this road segment

	$q1 = "SELECT * FROM severity WHERE gridId = '$gridID' AND roadName = '$roadName'";
	$res1 = mysqli_query($traff_con, $q1);	

	$weather_list = array("avg_clear","avg_snow","avg_rain","avg_fog","avg_cloudy");
	$condition_list = array("Clear","Snow","Rain","Fog","Cloudy","AllW");

	//keep running total, let's us handle invalid returns later on
	$total = 0;
	if($res1){
		//get all traffic along road segments
		while($row = $res1->fetch_assoc()){
			$this_day = switch_day($row["day"]);	
			$this_hour = $row["hour"];

			//sum and count used for average "allweather" severity
			$curr_sum = 0;
			$count = 0;

			foreach($weather_list as $w){
				$sev_temp = $row[$w];

				$sev_array[$this_day][$this_hour][switch_weather($w)] = round($row[$w]);

				//increment sum used for reporting "allweather" severity
				$curr_sum += $row[$w];
				$count++;
			}
			if($count>0){
				$sev_array[$this_day][$this_hour]["AllW"] = $curr_sum/$count;	
			}else{
				$sev_array[$this_day][$this_hour]["AllW"] = 0;
			}

			//add to the running total
			$total += $curr_sum;

		}
	}

	if($total>0){
		return $sev_array;
	}
	//if nothing was ever returned, return false (for error handling)
	else{
		return false;
	}
	
	}
	
?>



