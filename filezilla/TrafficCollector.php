

<?php 

//traffic collector
$testLoc=array("40.514647", "-74.392843");
$testArray=array(true, "08755", "50", "Clear", [true, true, false, false], "19", "Monday", "3, 20, 2017", "heatmap");
$final_result = getHeatTraff($testLoc, $testArray);
echo "Final Result: "."<br><br>";
print_r($final_result);

echo $_day."<br><br><br>";
function getHeatTraff($locationArray, $Array){
	//ARRAY WAS NOT IN ORIGINAL FUNCTION PARAMETER, WHY?? I ADDED IT IN BECAUSE WE NEED IT FOR WEATHER AND TIME/DAY
	//assume location converter has been called and sends lat and long in the location array, and the big array from the input is $ARRAY, includes weather etc

	//$response = http_get('https://www.zipcodeapi.com/rest/<api_key>/radius-sql.<format>/<lat>/<long>/<lat_long_units>/<distance>/<units>/<lat_field_name>/<long_field_name>/6');

	//send response and original array to queryHeatDB to query the DB

	$heatDB=queryHeatDB($locationArray, $Array);

	//add in the color codes for severity here (1=green, 2=orange etc)

	//return the sent array with only DB entries from the day of week, time, location and weather specified

	//Getter function, takes in a location bounding box as input, returns an array of traffic severities of roads inside that bounding box. 
	//returns an Array
	return $heatDB;

}

function getRouteTraff($routeArray, $Array){

	$routDB=queryRouteDB($locationArray, $Array);

	//send response and original array to queryRouteDB to query the DB

	//$routeDB=queryRouteDB($locationArray, $response);

//Getter function, takes in an array of routes, returns an array of traffic severities for all roads in the routes.
// returns an Array
}
function queryHeatDB($locationArray, $Array){


	$key="uX7cmJEJvlOoTbWRThUMeozf0vYThpHMnPYJyDs3YhrStoKmGE8U3Ia3e8WvxEhU";

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
	$weather=$Array[3];

	//find the day of week specified from the array
	$dayOfWeek=$Array[6];

	//find time of travel specified from the array
	$time=$Array[5];

	//find the where clause to use to query the DB from ZipCode API and lat and long 
	$response = 'https://www.zipcodeapi.com/rest/'.$key.'/radius-sql.json/'.$locationArray[0].'/'.$locationArray[1].'/degrees/'.$Array[2].'/mile/latcenter/lngcenter/1';
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

	if($Array[6] === "Sunday"){
		$_day = 0;
	}
	if($Array[6] === "Monday"){
		$_day = 1;
	}
	if($Array[6] === "Tuesday"){
		$_day = 2;
	}
	if($Array[6] === "Wednesday"){
		$_day = 3;
	}
	if($Array[6] === "Thursday"){
		$_day = 4;
	}
	if($Array[6] === "Friday"){
		$_day = 5;
	}
	if($Array[6] === "Saturday"){
		$_day = 6;
	}

	$LocWhere=$parseTC->where_clause;
	//query the DB from the list of zip codes/locations of the bounding box
	$query= "SELECT id FROM grid WHERE ". $LocWhere;
	$result = mysqli_query($traff_con,$query);

	$avg_arr = array();
	$road_arr = array();
	$gridArray = array();
	$return = array();
	
	while ($row = $result->fetch_assoc()){
		array_push($gridArray, $row["id"]);
	}
	echo "grid id: "."<br>";
	foreach ($gridArray as &$gridID) {
		echo $gridID."<br>";
		//for each grid id...
		if ($weather === "Clear"){
			$q1 = "SELECT avg_clear, roadName FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'";
			$res1 = mysqli_query($traff_con, $q1);

			while ($row1 = $res1->fetch_assoc()){
				array_push($avg_arr, $row1["avg_clear"]);
				array_push($road_arr, $row1["roadName"]);
			}
			//now combine the key value pairs
			$return = array_combine($road_arr, $avg_arr);
		}
		if ($weather === "Snow"){
			$q1 = "SELECT avg_snow, roadName FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'";
			$res1 = mysqli_query($traff_con, $q1);

			while ($row1 = $res1->fetch_assoc()){
				array_push($avg_arr, $row1["avg_snow"]);
				array_push($road_arr, $row1["roadName"]);
			}
			//now combine the key value pairs
			$return = array_combine($road_arr, $avg_arr);
		}
		if ($weather === "Cloudy"){
			$q1 = "SELECT avg_cloudy, roadName FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'";
			$res1 = mysqli_query($traff_con, $q1);

			while ($row1 = $res1->fetch_assoc()){
				array_push($avg_arr, $row1["avg_cloudy"]);
				array_push($road_arr, $row1["roadName"]);
			}
			//now combine the key value pairs
			$return = array_combine($road_arr, $avg_arr);
		}
		if ($weather === "Rain"){
			$q1 = "SELECT avg_rain, roadName FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'";
			$res1 = mysqli_query($traff_con, $q1);

			while ($row1 = $res1->fetch_assoc()){
				array_push($avg_arr, $row1["avg_rain"]);
				array_push($road_arr, $row1["roadName"]);
			}
			//now combine the key value pairs
			$return = array_combine($road_arr, $avg_arr);
		}
		if ($weather === "Fog"){
			$q1 = "SELECT avg_fog, roadName FROM severity WHERE gridId = '$gridID' AND day = '$_day' AND hour = '$time'";
			$res1 = mysqli_query($traff_con, $q1);

			while ($row1 = $res1->fetch_assoc()){
				array_push($avg_arr, $row1["avg_fog"]);
				array_push($road_arr, $row1["roadName"]);
			}
			//now combine the key value pairs
			$return = array_combine($road_arr, $avg_arr);
		}
	}

	//convert the query from SQL to json through the convertQuery function below, will need to be changed 


	return $return;
//Helper function for getHeatTraff(). Takes a location bounding box and array of weather, date, and time conditions as parameters. Queries the DB for all traffic severities that match those params, returns those severities as array.

}

function queryRouteDB($routeArray, $Array){


//Helper function for getRouteTraff(). Takes an array of routes and array of weather, date, and time conditions as parameters. Queries the DB for all traffic severities that match those params, returns those severities as array.
// returns an Array

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



