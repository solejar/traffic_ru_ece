<?php 

class trafficSegment {
    public $stLat;
    public $stLng;
    public $ndLat;
    public $ndLng;
    public $rdName;
    public $severity;
}

//traffic collector

$testArray=array("forecast"=>true, "zip"=>"08755", "range"=>"50", "weather"=>"Clear", "severity"=>[true, true, false, false], "time"=>"9", "day"=>"Tuesday", "date"=>"March 28, 2017", "feature"=>"route");
$final_result = getHeatTraff("40.8041562","-74.0717843", "50", $testArray);
echo "Final Result: ". $final_result;

function getHeatTraff($temp_lat,$temp_lng, $temp_range, $inputConditions){
	//assume location converter has been called and sends lat and long in the location array, and the big array from the input is $ARRAY, includes weather etc

	//$response = http_get('https://www.zipcodeapi.com/rest/<api_key>/radius-sql.<format>/<lat>/<long>/<lat_long_units>/<distance>/<units>/<lat_field_name>/<long_field_name>/6');

	//send response and original array to queryHeatDB to query the DB
	
	$heatDB=queryHeatDB($temp_lat,$temp_lng, $temp_range,$inputConditions);

	//add in the color codes for severity here (1=green, 2=orange etc)

	//return the sent array with only DB entries from the day of week, time, location and weather specified

	//Getter function, takes in a location bounding box as input, returns an array of traffic severities of roads inside that bounding box. 
	//returns an Array
	return $heatDB;

}

function getRouteTraff($roadN, $startL, $startLo, $Arr){

	//this should be changed so the for loop is in here.
	$routeDB=queryRouteDB($roadN, $startL, $startLo, $Arr);	

	//send response and original array to queryRouteDB to query the DB

	//$routeDB=queryRouteDB($locationArray, $response);

//Getter function, takes in an array of routes, returns an array of traffic severities for all roads in the routes.
// returns an Array
	return $routeDB;
}
function queryHeatDB($cent_lat,$cent_lng,$query_range, $inputConditions){


	$key="uX7cmJEJvlOoTbWRThUMeozf0vYThpHMnPYJyDs3YhrStoKmGE8U3Ia3e8WvxEhU";

	$traff_host_name = "db667824699.db.1and1.com";
	$traff_database  = "db667824699";
	$traff_user_name = "dbo667824699";
	$freq_host_name  = "db670831916.db.1and1.com";
	$freq_database   = "db670831916";
	$freq_user_name  = "dbo670831916";

	$password   = "briansbutt";

	$freq_con = mysqli_connect($freq_host_name, $freq_user_name, $password, $freq_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	

	$traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	//find the weather specified from array
	$weather=$inputConditions["weather"];

	//find time of travel specified from the array
	$time=$inputConditions["time"];

	//array of selected severities
	$sev = $inputConditions["severity"];

	//find the where clause to use to query the DB from ZipCode API and lat and long 
	$response = 'https://www.zipcodeapi.com/rest/'.$key.'/radius-sql.json/'.$cent_lat.'/'.$cent_lng.'/degrees/'.$query_range.'/mile/latcenter/lngcenter/1';
	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$chTC = curl_init();
	if (!$chTC){
		die('Couldn\'t initialize a cURL module');	
	}
	curl_setopt($chTC, CURLOPT_URL, $response);
	curl_setopt($chTC, CURLOPT_RETURNTRANSFER, TRUE);
	$dataTC = curl_exec($chTC);
	curl_close($chTC);
	//echo $dataZipLC;
	$parseTC = json_decode($dataTC);

	$inputDay = $inputConditions["day"];
	
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

	$LocWhere=$parseTC->where_clause;
	//query the DB from the list of zip codes/locations of the bounding box
	//$query= "SELECT id FROM grid WHERE ". $LocWhere;
	$latRange = $query_range*.0144927536; 		// .0144927536 lat degrees in a mile
	$longRange = $query_range*.0183150183;		// .0183150183 long degrees in a mile 
	$query = "SELECT id FROM grid WHERE latcenter<= $cent_lat+$latRange AND latcenter >= $cent_lat-$latRange AND lngcenter>=$cent_lng-$longRange AND lngcenter <=$cent_lng+$longRange";
	$result = mysqli_query($traff_con,$query);
	$query_weather;
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

	$result_temp = $result;
	while($row_temp = $result_temp->fetch_assoc()){
		$id_arr[] = $row_temp["id"];
	}

	if($time === "AllT"){
		$sevquery = "SELECT road_name, $query_weather, start_lat, start_lng, end_lat, end_lng 
		FROM (severity as s INNER JOIN bounds as b ON (s.gridId = b.grid_id AND b.road_name = s.roadName))
		WHERE day ='$_day' AND end_lat IS NOT NULL AND end_lng IS NOT NULL AND (grid_id IN (".implode(',',$id_arr)."))";

		$sevresult = mysqli_query($traff_con,$sevquery);
		$average = 0;
		//$output = array();
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
			//array_push($output,$newSegment);
		}
	}
	else{
		$sevquery = "SELECT road_name, $query_weather, start_lat, start_lng, end_lat, end_lng 
		FROM (severity as s INNER JOIN bounds as b ON (s.gridId = b.grid_id AND b.road_name = s.roadName))
		WHERE day ='$_day' AND hour ='$time' AND end_lat IS NOT NULL AND end_lng IS NOT NULL AND (grid_id IN (".implode(',',$id_arr)."))";

		$sevresult = mysqli_query($traff_con,$sevquery);
		$average = 0;
		//$output = array();
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
			//array_push($output,$newSegment);
		}
	}
	return $output;
//Helper function for getHeatTraff(). Takes a location bounding box and array of weather, date, and time conditions as parameters. Queries the DB for all traffic severities that match those params, returns those severities as array.

}

function queryRouteDB($roadName, $startLat, $startLong, $inputConditions){
//Helper function for getRouteTraff(). Takes an array of routes and array of weather, date, and time conditions as parameters. Queries the DB for all traffic severities that match those params, returns those severities as array.
// returns an Array
	$traff_host_name = "db667824699.db.1and1.com";
	$traff_database = "db667824699";
	$traff_user_name = "dbo667824699";
	$freq_host_name  = "db670831916.db.1and1.com";
	$freq_database   = "db670831916";
	$freq_user_name  = "dbo670831916";

	$password   = "briansbutt";

	$freq_con = mysqli_connect($freq_host_name, $freq_user_name, $password, $freq_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	

	$traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
	// Check connection
	if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	//find the weather specified from array
	$weather=$inputConditions["weather"];

	//find time of travel specified from the array
	$time=$inputConditions["time"];
	$_day;
	$sev = $inputConditions["severity"];
	$inputDay = $inputConditions["day"];
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
	// get gridid for these coords
	$q10 = "SELECT id FROM grid WHERE ('$startLat' >= latS AND '$startLat' <= latN AND '$startLong' >= longW AND '$startLong' <= longE)";
	$res2 = mysqli_query($traff_con,$q10);
	//for only the first grid box the incident is contained in...
	if($row = $res2->fetch_assoc()){
		$gridID = $row["id"];
	}
	// query db for traffic in this grid id with other parameters
	$average = 0;
	//if the user wants all times
	if ($time === "AllT"){
		//dont specify the hour in all the following queries
		if ($weather === "AllW"){
			$q1 = "SELECT * FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
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
					$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
				}
			}
		}
		else if ($weather === "Clear"){
			$q1 = "SELECT avg_clear FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_clear"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Snow"){
			$q1 = "SELECT avg_snow FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_snow"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Cloudy"){
			$q1 = "SELECT avg_cloudy FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_cloudy"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Rain"){
			$q1 = "SELECT avg_rain FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_rain"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Fog"){
			$q1 = "SELECT avg_fog FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_fog"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
	}
	//otherwise use the specified time from the user
	else{
		if ($weather === "AllW"){
			$q1 = "SELECT * FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
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
					$average = ($averageC + $averageS + $averageCL + $averageR + $averageF)/$count;
				}
			}
		}
		else if ($weather === "Clear"){
			$q1 = "SELECT avg_clear FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);
			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_clear"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Snow"){
			$q1 = "SELECT avg_snow FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_snow"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Cloudy"){
			$q1 = "SELECT avg_cloudy FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_cloudy"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Rain"){
			$q1 = "SELECT avg_rain FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_rain"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
		else if ($weather === "Fog"){
			$q1 = "SELECT avg_fog FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time' AND roadName = '$roadName'";
			$res1 = mysqli_query($traff_con, $q1);

			if ($row1 = $res1->fetch_assoc()){
				$av = round($row1["avg_fog"]);
				if($sev[$av-1]){
					$average = $av;
				}
			}
		}
	}
	
	// set the severity in this $routeArray
	// return $routeArray which should now have severity updated
	return round($average);
}


function convertQuery(){
	//how to convert the sql query to a json
	$mysqli = new mysqli('localhost','user','password','myDatabaseName');
	$myArray = array();
	if ($result = $mysqli->query("SELECT * FROM phase1")) {

    	while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $myArray[] = $row;
    	}
    	echo json_encode($myArray);
	}
	$result->close();
	$mysqli->close();
}
?>



