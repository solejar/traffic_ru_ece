<?php 

//object for each traffic segment
class trafficSegment {
    public $stLat;
    public $stLng;
    public $ndLat;
    public $ndLng;
    public $rdName;
    public $severity;
}

function get_traff($location_params,$input_conditions,$feature){
	//get traffic for route
	if($feature == "route" || $feature == "forecasted_route"){

		$roadName = $location_params["roadName"];
		$startLat = $location_params["startLat"];
		$startLong = $location_params["startLong"];

		$traff_results = query_route_db($roadName, $startLat, $startLong, $input_conditions);

	//get traffic for heat map
	}else if($feature == "heatmap"|| $feature == "forecasted_heatmap"){
		$cent_lat = $location_params["cent_lat"];
		$cent_lng = $location_params["cent_lng"];
		$query_range = $location_params["range"];

		$traff_results = query_heat_db($cent_lat,$cent_lng,$query_range,$input_conditions);

	}

	return $traff_results;
}

//get traff sev from db for heatmap use case
function query_heat_db($cent_lat,$cent_lng,$query_range, $input_conditions){
	//database connection info
	$traff_host_name = "db667824699.db.1and1.com";
	$traff_database  = "db667824699";
	$traff_user_name = "dbo667824699";
	
	$password   = "briansbutt";
	
	//traffic database connection
	$traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}


	//pull inputs from associative array
	$weather=$input_conditions["weather"];
	$time=$input_conditions["time"];
	$sev = $input_conditions["severity"];
	$inputDay = $input_conditions["day"];

	
	//switch the day to a number (used in our database)
	switch ($inputDay){
		case "Sunday":
			$_day = 0;
			break;
		case "Monday":
			$_day = 1;
			break;
		case "Tuesday":
			$_day = 2;
			break;
		case "Wednesday":
			$_day = 3;
			break;
		case "Thursday":
			$_day = 4;
			break;
		case "Friday":
			$_day = 5;
			break;
		case "Satuday":
			$_day = 6;
	}

	//select all the grid id's within the radius specified by the user
	$latRange = $query_range*.0144927536; 		// .0144927536 lat degrees in a mile
	$longRange = $query_range*.0183150183;		// .0183150183 long degrees in a mile 

	$id_arr = array();
	$query = "SELECT id FROM grid WHERE latcenter<= $cent_lat+$latRange AND latcenter >= $cent_lat-$latRange AND lngcenter>=$cent_lng-$longRange AND lngcenter <=$cent_lng+$longRange";
	$result = mysqli_query($traff_con,$query);
	$query_weather;

	//switch the weather condition specified by the user to the standard in our database
	switch ($weather){
		case "AllW":
			$query_weather = "avg_clear, avg_snow, avg_cloudy, avg_rain, avg_fog";
		case "Clear":
			$query_weather = "avg_clear";
			break;
		case "Snow":
			$query_weather = "avg_snow";
			break;
		case "Cloudy":
			$query_weather = "avg_cloudy";
			break;
		case "Rain":
			$query_weather = "avg_rain";
			break;
		case "Fog":
			$query_weather = "avg_fog";
			break;
	}

	//grab all the grid id's
	$result_temp = $result;
	while($row_temp = $result_temp->fetch_assoc()){
		array_push($id_arr, $row_temp["id"]);
	}

	//if no grid id's were found, return false;
	if(sizeof($id_arr) <= 0){
		return false;
	}

	//if the user want to see all times...
	if($time === "AllT"){
		//get the road segment and average severity info based on inputs
		$sevquery = "SELECT road_name, $query_weather, start_lat, start_lng, end_lat, end_lng 
		FROM (severity as s INNER JOIN bounds as b ON (s.gridId = b.grid_id AND b.road_name = s.roadName))
		WHERE day ='$_day' AND end_lat IS NOT NULL AND end_lng IS NOT NULL AND (grid_id IN (".implode(',',$id_arr)."))";

		$sevresult = mysqli_query($traff_con,$sevquery);
		$average = 0;
		//if this query fails, return false
		if(!$sevresult){
			return false;
		}
		//for each road segment that has severities in the radius...
		while($sevrow = $sevresult->fetch_assoc()){
			//extract the info about each one and assign it to a trafficSegment object
			$newSegment = new trafficSegment();
			$newSegment->stLat = $sevrow["start_lat"];
			$newSegment->stLng = $sevrow["start_lng"];
			$newSegment->ndLat = $sevrow["end_lat"];
			$newSegment->ndLng = $sevrow["end_lng"];
			$newSegment->rdName = $sevrow["road_name"];

			//if the user specified all weather conditions
			if($weather === "AllW"){
				$count = 0;
				$averageC = 0;
				$averageS = 0;
				$averageCL = 0;
				$averageR = 0;
				$averageF = 0;

				//extract the averages of each weather condition for this specific segment
				$aC = round($sevrow["avg_clear"]);
				$aS = round($sevrow["avg_snow"]);
				$aCL = round($sevrow["avg_cloudy"]);
				$aR = round($sevrow["avg_rain"]);
				$aF = round($sevrow["avg_fog"]);

				//for each weather condition, only count it if it's severity was of interest by the user
				if($sev[$aC-1]){
					$averageC = $aC;
					$count++;
				}
				else if($sev[$aS-1]){
					$averageS = $aS;
					$count++;
				}
				else if($sev[$aCL-1]){
					$averageCL = $aCL;
					$count++;
				}
				else if($sev[$aR-1]){
					$count++;
					$averageR = $aR;
				}
				else if($sev[$aF-1]){
					$averageF = $aF;
					$count++;
				}
				//as long as there exists some averages, calculate the average severity for this road segment with every weather condition
				if ($count != 0){
					$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
				}
				//set this segments severity to this calculated average
				$newSegment->severity = round($average);

			}
			//otherwise, if the user enters a specific weather condition of interst...
			else{
				//extract the average severity for the specified weather condition if it's avg severity is of interest to the user
				if ($sev[($sevrow["$query_weather"])-1]){
					$newSegment->severity = round($sevrow["$query_weather"]);
				}
				//otherwise set the severity equal to 0 since the user doesnt care about this severity
				else{
					$newSegment->severity = 0;
				}	
			}
			//add this segment to the output array
			$output[] = $newSegment;
		}
	}

	//otherwise the user specified a specific hour of interest
	//this else statement has the same comments/functionality as the above if statement
	//except it additionally queries using the hour specified by the user
	else{
		//get the segment info with the specified hour 
		$sevquery = "SELECT road_name, $query_weather, start_lat, start_lng, end_lat, end_lng 
		FROM (severity as s INNER JOIN bounds as b ON (s.gridId = b.grid_id AND b.road_name = s.roadName))
		WHERE day ='$_day' AND hour ='$time' AND end_lat IS NOT NULL AND end_lng IS NOT NULL AND (grid_id IN (".implode(',',$id_arr)."))";

		$sevresult = mysqli_query($traff_con,$sevquery);
		$average = 0;
		//if the query fails, return false
		if(!$sevresult){
			return false;
		}
		while($sevrow = $sevresult->fetch_assoc()){
			$newSegment = new trafficSegment();
			$newSegment->stLat = $sevrow["start_lat"];
			$newSegment->stLng = $sevrow["start_lng"];
			$newSegment->ndLat = $sevrow["end_lat"];
			$newSegment->ndLng = $sevrow["end_lng"];
			$newSegment->rdName = $sevrow["road_name"];

			if($weather === "AllW"){
				$count = 0;
				$averageC = 0;
				$averageS = 0;
				$averageCL = 0;
				$averageR = 0;
				$averageF = 0;

				$aC = round($sevrow["avg_clear"]);
				$aS = round($sevrow["avg_snow"]);
				$aCL = round($sevrow["avg_cloudy"]);
				$aR = round($sevrow["avg_rain"]);
				$aF = round($sevrow["avg_fog"]);

				if($sev[$aC-1]){
					$averageC = $aC;
					$count++;
				}
				else if($sev[$aS-1]){
					$averageS = $aS;
					$count++;
				}
				else if($sev[$aCL-1]){
					$averageCL = $aCL;
					$count++;
				}
				else if($sev[$aR-1]){
					$count++;
					$averageR = $aR;
				}
				else if($sev[$aF-1]){
					$averageF = $aF;
					$count++;
				}

				if ($count != 0){
					$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
				}
				$newSegment->severity = round($average);
			}
			else{
				if ($sev[($sevrow["$query_weather"])-1]){
					$newSegment->severity = round($sevrow["$query_weather"]);
				}
				else{
					$newSegment->severity = 0;
				}	
			}

			$output[] = $newSegment;

		}
	}
	//return the output array
	return $output;
}

//get traff sev from db for the route use case
function query_route_db($roadName, $startLat, $startLong, $inputConditions){
	//database connectivitiy info
	$traff_host_name = "db667824699.db.1and1.com";
	$traff_database = "db667824699";
	$traff_user_name = "dbo667824699";

	$password   = "briansbutt";

	$traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	//get the specified inputs:
	//find the weather specified from array
	$weather=$inputConditions["weather"];
	//find time of travel specified from the array
	$time=$inputConditions["time"];
	$_day;
	$sev = $inputConditions["severity"];
	$inputDay = $inputConditions["day"];

	//switch the day to numbers we use in our db
	switch ($inputDay){
		case "Sunday":
			$_day = 0;
			break;
		case "Monday":
			$_day = 1;
			break;
		case "Tuesday":
			$_day = 2;
			break;
		case "Wednesday":
			$_day = 3;
			break;
		case "Thursday":
			$_day = 4;
			break;
		case "Friday":
			$_day = 5;
			break;
		case "Satuday":
			$_day = 6;
	}
	$query_weather;
	//switch the weather that was specified
	switch ($weather){
		case "AllW":
			$query_weather = "avg_clear, avg_snow, avg_cloudy, avg_rain, avg_fog";
		case "Clear":
			$query_weather = "avg_clear";
			break;
		case "Snow":
			$query_weather = "avg_snow";
			break;
		case "Cloudy":
			$query_weather = "avg_cloudy";
			break;
		case "Rain":
			$query_weather = "avg_rain";
			break;
		case "Fog":
			$query_weather = "avg_fog";
			break;
	}
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
	// query db for traffic in this grid id with other parameters
	$average = 0;
	//if the user wants all times
	if ($time === "AllT"){
		//dont specify the hour in all the following queries
		if ($weather === "AllW"){
			$q1 = "SELECT * FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if($res1){
				if ($row1 = $res1->fetch_assoc()){
					$count = 0;
					$averageC = 0;
					$averageS = 0;
					$averageCL = 0;
					$averageR = 0;
					$averageF = 0;

					$aC = round($row1["avg_clear"]);
					$aS = round($row1["avg_snow"]);
					$aCL = round($row1["avg_cloudy"]);
					$aR = round($row1["avg_rain"]);
					$aF = round($row1["avg_fog"]);

					if($sev[$aC-1]){
						$averageC = $aC;
						$count++;
					}
					else if($sev[$aS-1]){
						$averageS = $aS;
						$count++;
					}
					else if($sev[$aCL-1]){
						$averageCL = $aCL;
						$count++;
					}
					else if($sev[$aR-1]){
						$count++;
						$averageR = $aR;
					}
					else if($sev[$aF-1]){
						$averageF = $aF;
						$count++;
					}
					//calculate the average for all weather conditions as long as there is one non-zero severity
					if ($count != 0){
						$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
					}
				}
			}
			else{
				//return false if there are no entries for the specific combo of inputs
				return false;
			}
			
		}
		//else the user specified a specific weather condition
		else{
			//select the average severity for that weather condition
			$q1 = "SELECT $query_weather FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if ($res1){
				if ($row1 = $res1->fetch_assoc()){
					$av = round($row1["$query_weather"]);
					if($sev[$av-1]){
						//set the average for this segment equal to the result of the query if the severity is of interest to the user
						$average = $av;
					}
				}
			}
			//if the query fails, return false
			else{
				return false;
			}	
		}
	}
	//otherwise use the specified time from the user 
	else{
		//dont specify the hour in all the following queries
		if ($weather === "AllW"){
			$q1 = "SELECT * FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if($res1){
				if ($row1 = $res1->fetch_assoc()){
					$count = 0;
					$averageC = 0;
					$averageS = 0;
					$averageCL = 0;
					$averageR = 0;
					$averageF = 0;

					$aC = round($row1["avg_clear"]);
					$aS = round($row1["avg_snow"]);
					$aCL = round($row1["avg_cloudy"]);
					$aR = round($row1["avg_rain"]);
					$aF = round($row1["avg_fog"]);

					if($sev[$aC-1]){
						$averageC = $aC;
						$count++;
					}
					else if($sev[$aS-1]){
						$averageS = $aS;
						$count++;
					}
					else if($sev[$aCL-1]){
						$averageCL = $aCL;
						$count++;
					}
					else if($sev[$aR-1]){
						$count++;
						$averageR = $aR;
					}
					else if($sev[$aF-1]){
						$averageF = $aF;
						$count++;
					}

					if ($count != 0){
						//calculate the average for all weather conditions
						$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
					}
				}
			}
			//if the query fails, return false
			else{
				return false;
			}
		}
		//if all a specific weather condition was specified, query based on that as well as the hour specified
		else{
			$q1 = "SELECT $query_weather FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if ($res1){
				if ($row1 = $res1->fetch_assoc()){
					$av = round($row1["$query_weather"]);
					if($sev[$av-1]){
						//set the average equal to the result of the query
						$average = $av;
					}
				}
			}
			//if the query fails, return false
			else{
				return false;
			}	
		}
	}
	
	// return the average for the segment
	return round($average);
}
?>



