<?php 

//**********************************************************************************************************************************************//	
//****************************************************** DB CONNECTIVITY, API KEYS, and FUNCTIONS *********************************************//
//*********************************************************************************************************************************************//	


	//weather.com api keys
	$w_keys = array("bbc28e9076215cb5","4e7177537bc57072","03b51d9a0728e20c","4c22265185030920","a0dc0b90ca53db30","7b5fe87d7a89fc4b");
	$t_keys = array("493f5e1f3f1adcb7","2a2c87c0f8cd2977","28ec20b38268e0a8","3d06cc72319012a3","4596e8cdc5dd0a7f","2b6b384a70a3a87c");
	$g_keys = array("AIzaSyAKKpYTpoXNty9nZq-I1QEL1o1giJt2jvY","AIzaSyDGgrDMMM15V9xwAhszp8V6F7OakvCc7W8","AIzaSyDCg9hwVJX-lqR-xz2Wkq9Ug85Gy-GiysE","AIzaSyAls2jkbBS0EdGDRw0wRrjjGxvPi9eIJao","AIzaSyDGLc2dSmfIUtRu7DG9RPa9DWJNwYEdBcw","AIzaSyDFZXFaYwGwQHd3P5bpp5L28NtHeMbZk7A");


	$host_name  = "db675664411.db.1and1.com";
    $database   = "db675664411";
    $user_name  = "dbo675664411";
    $password   = "briansbutt";
    $dbCon = true;
    $setTest = true;

    $testing_con = mysqli_connect($host_name, $user_name, $password, $database);
	// Check connection
	if (mysqli_connect_errno()){
		$dbCon = false;
	}

	function switchWeather($weatherDN){
		switch ($weatherDN){
			case "Snow":
			case "Heavy Snow":
			case "Light Snow":
			case "Low Drifting Snow":
			case "Heavy Low Drifting Snow":
			case "Light Low Drifting Snow":
			case "Thunderstorms and Snow":
			case "Heavy Thunderstorms and Snow":
			case "Light Thunderstorms and Snow":
			case "Snow Showers":
			case "Heavy Snow Showers":
			case "Light Snow Showers":
			case "Snow Grains":
			case "Heavy Snow Grains":
			case "Light Snow Grains":
			case "Ice Pellets":
			case "Heavy Ice Pellets":
			case "Light Ice Pellets":
			case "Ice Pellet Showers":
			case "Heavy Ice Pellet Showers":
			case "Light Ice Pellet Showers":
			case "Thunderstorms and Ice Pellets":
			case "Heavy Thunderstorms and Ice Pellets":
			case "Light Thunderstorms and Ice Pellets":
			case "Ice Crystals":
			case "Heavy Ice Crystals":
			case "Light Ice Crystals":
			case "Hail":
			case "Heavy Hail":
			case "Light Hail":
			case "Thunderstorms with Hail":
			case "Heavy Thunderstorms with Hail":
			case "Light Thunderstorms with Hail":
			case "Thunderstorms with Small Hail":
			case "Heavy Thunderstorms with Small Hail":
			case "Light Thunderstorms with Small Hail":
			case "Hail Showers":
			case "Heavy Hail Showers":
			case "Light Hail Showers":
			case "Small Hail Showers":
			case "Heavy Small Hail Showers":
			case "Light Small Hail Showers":
			case "Blowing Snow":
			case "Heavy Blowing Snow":
			case "Light Blowing Snow":
			case "Small Hail":
				$weatherDN = "Snow";
				break;
			case "Mostly Cloudy":
			case "Overcast":
				$weatherDN = "Cloudy";
				break;
			case "Clear":
			case "Partly Cloudy":
			case "Scattered Clouds":
				$weatherDN = "Clear";
				break;
			case "Fog":
			case "Light Fog":
			case "Heavy Fog":
			case "Fog Patches":
			case "Light Fog Patches":
			case "Heavy Fog Patches":
			case "Patches of Fog":
			case "Shallow Fog":
			case "Partial Fog":
			case "Haze":
			case "Light Haze":
			case "Heavy Haze":
				$weatherDN = "Fog";
				break;
			case "Drizzle":
			case "Light Drizzle":
			case "Heavy Drizzle":
			case "Freezing Drizzle":
			case "Light Freezing Drizzle":
			case "Heavy Freezing Drizzle":
			case "Rain":
			case "Heavy Rain":
			case "Light Rain":
			case "Freezing Rain":
			case "Heavy Freezing Rain":
			case "Light Freezing Rain":
			case "Rain Mist":
			case "Heavy Rain Mist":
			case "Light Rain Mist":
			case "Rain Showers":
			case "Heavy Rain Showers":
			case "Light Rain Showers":
			case "Thunderstorms and Rain":
			case "Heavy Thunderstorms and Rain":
			case "Light Thunderstorms and Rain":
				$weatherDN = "Rain";
				break;
			default:
				// what should we make any different things go in as?
				$weatherDN = "Invalid";
				break;
		}
		return $weatherDN;
	}


	function setSev($weath, $grid, $zipReg, $rdName, $DW, $H, $sever){
		global $setTest;
		global $testing_con;
		if ($weath === "Clear"){
			//check to see if there is already an entry for this weather condition in this grid box for the road
			$q0 = "SELECT sev_clear FROM severity WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
			$res0 = mysqli_query($testing_con,$q0);
			if (!$res0){
				$setTest = false;
			}

			if (mysqli_num_rows($res0) > 0){ //if there already exists an entry in the db.. the nonused sev columns will be 0 //should be 1 row

				while ($row = $res0->fetch_assoc()){ // for the current severity
					$sev = $row["sev_clear"];

					$qu =  "UPDATE severity SET sev_clear = (sev_clear + '$sever') WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
					$test = mysqli_query($testing_con,$qu);
					if (!$test){
						$setTest = false;
					}
				}
			}
			else{ //else insert a new entry

				$q3 = "INSERT INTO severity (gridId, zipRegion, roadName, day, hour, sev_clear) VALUES ('$grid','$zipReg','$rdName', '$DW','$H', '$sever')";
				$test = mysqli_query($testing_con,$q3);
				if (!$test){
					$setTest = false;
				}
			}
		}
		else if ($weath === "Snow"){
			//check to see if there is already an entry for this weather condition in this grid box for the road
			$q0 = "SELECT sev_snow FROM severity WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
			$res0 = mysqli_query($testing_con,$q0);
			if (!$res0){
				$setTest = false;
			}

			if (mysqli_num_rows($res0) > 0){ //if there already exists an entry in the db.. the nonused sev columns will be 0 //should be 1 row

				while ($row = $res0->fetch_assoc()){ // for the current severity
					$sev = $row["sev_snow"];

					$qu =  "UPDATE severity SET sev_snow = (sev_snow + '$sever') WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
					$test = mysqli_query($testing_con,$qu);
					if (!$test){
						$setTest = false;
					}
				}
			}
			else{ //else insert a new entry

				$q3 = "INSERT INTO severity (gridId, zipRegion, roadName, day, hour, sev_snow) VALUES ('$grid','$zipReg','$rdName', '$DW','$H', '$sever')";
				$test = mysqli_query($testing_con,$q3);
				if (!$test){
					$setTest = false;
				}
			}
		}
		else if ($weath === "Cloudy"){
			//check to see if there is already an entry for this weather condition in this grid box for the road
			$q0 = "SELECT sev_cloudy FROM severity WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
			$res0 = mysqli_query($testing_con,$q0);
			if (!$res0){
				$setTest = false;
			}

			if (mysqli_num_rows($res0) > 0){ //if there already exists an entry in the db.. the nonused sev columns will be 0 //should be 1 row

				while ($row = $res0->fetch_assoc()){ // for the current severity
					$sev = $row["sev_cloudy"];

					$qu =  "UPDATE severity SET sev_cloudy = (sev_cloudy + '$sever') WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
					$test = mysqli_query($testing_con,$qu);
					if (!$test){
						$setTest = false;
					}
				}
			}
			else{ //else insert a new entry

				$q3 = "INSERT INTO severity (gridId, zipRegion, roadName, day, hour, sev_cloudy) VALUES ('$grid','$zipReg','$rdName', '$DW','$H', '$sever')";
				$test = mysqli_query($testing_con,$q3);
				if (!$test){
					$setTest = false;
				}
			}
		}
		else if ($weath === "Rain"){
			//check to see if there is already an entry for this weather condition in this grid box for the road
			$q0 = "SELECT sev_rain FROM severity WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
			$res0 = mysqli_query($testing_con,$q0);
			if (!$res0){
				$setTest = false;
			}

			if (mysqli_num_rows($res0) > 0){ //if there already exists an entry in the db.. the nonused sev columns will be 0 //should be 1 row

				while ($row = $res0->fetch_assoc()){ // for the current severity
					$sev = $row["sev_rain"];

					$qu =  "UPDATE severity SET sev_rain = (sev_rain + '$sever') WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
					$test = mysqli_query($testing_con,$qu);
					if (!$test){
						$setTest = false;
					}
				}
			}
			else{ //else insert a new entry

				$q3 = "INSERT INTO severity (gridId, zipRegion, roadName, day, hour, sev_rain) VALUES ('$grid','$zipReg','$rdName', '$DW','$H', '$sever')";
				$test = mysqli_query($testing_con,$q3);
				if (!$test){
					$setTest = false;
				}
			}
		}
		else if ($weath === "Fog"){
			//check to see if there is already an entry for this weather condition in this grid box for the road
			$q0 = "SELECT sev_fog FROM severity WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
			$res0 = mysqli_query($testing_con,$q0);
			if (!$res0){
				$setTest = false;
			}

			if (mysqli_num_rows($res0) > 0){ //if there already exists an entry in the db.. the nonused sev columns will be 0 //should be 1 row

				while ($row = $res0->fetch_assoc()){ // for the current severity
					$sev = $row["sev_fog"];

					$qu =  "UPDATE severity SET sev_fog = (sev_fog + '$sever') WHERE gridId = '$grid' AND roadName = '$rdName' AND day = '$DW' AND hour = '$H'";
					$test = mysqli_query($testing_con,$qu);
					if (!$test){
						$setTest = false;
					}
				}
			}
			else{ //else insert a new entry

				$q3 = "INSERT INTO severity (gridId, zipRegion, roadName, day, hour, sev_fog) VALUES ('$grid','$zipReg','$rdName', '$DW','$H', '$sever')";
				$test = mysqli_query($testing_con,$q3);
				if (!$test){
					$setTest = false;
				}
			}
		}
	}


//**********************************************************************************************************************************************//	
//****************************************************** WEATHER COLLECTION FOR ZIPREGIONS ******************************************************//
//**********************************************************************************************************************************************//

	//array of zip codes:

	//boonton nj, newark nj, elizabeth nj, jersey city nj, little falls nj, paterson nj, red bank nj, dover nj, summit nj, barnegat nj, 
	//camden nj, absecon nj, seabrook nj, atlantic city nj, allentown nj, trenton nj, lakewood nj, annandale nj, new brunswick nj, suffern ny, amawalk ny,
	//brooklyn ny, alberston ny, babylon ny...

	$zips = array("7005","7101","7201","7306","7424","7501","7601","7701","7801","7901","8005","8101","8201","8302","8401","8501","8601","8701","8801","8901","10901","10501","10451","11517","11702","12721","12602","12502","12401","13730","18405","18320","18001","18901","19001","18810","18603","18701","18201","19505","19401","19301","19801","17813","17921","17101","17501","21902","19701","21001","21612","19902","21104");		

	$totalZips = sizeof($zips); 	//total number of zipRegion codes
	for ($k = 0; $k < $totalZips; $k++){
		$weatherAPI = true;
		$testWeather = true;
		//each zip code
		$zip = $zips[$k];
		//get the weather for it
		$urlWeather = 'http://api.wunderground.com/api/'.$w_keys[($k%6)].'/conditions/q/'.$zip.'.json';
		if (!function_exists('curl_init')){
			die('Can\'t find cURL module');	
			$weatherAPI = false;
		}
		$chW = curl_init();
		if (!$chW){
			die('Couldn\'t initialize a cURL module');	
			$weatherAPI = false;
		}
		curl_setopt($chW, CURLOPT_URL, $urlWeather);
		curl_setopt($chW, CURLOPT_RETURNTRANSFER, TRUE);
		
		$dataW = curl_exec($chW);
		curl_close($chW);

		// The code mo wrote for parsing the data starts here //
		$parseW = json_decode($dataW);

		//weather description for the zipRegion code:
		$weatherDSC = $parseW->current_observation->weather;
		

		// change weatherD to 5 descriptions we actually use
		$weatherDesc = switchWeather($weatherDSC);

		$time = $parseW->current_observation->observation_time_rfc822;
		$dt0 = date_create($time);
		$fullDate = $dt0->format('Y-m-d H:i:s');
		date_default_timezone_set("America/New_York");

		//actual hour and day of week for our database
		$hour = date('H');
		$day = date('w'); //sunday is 0 -> saturday is 6
		
		
		//check to see if theres anything already in the freq_database for this zip region at the certain time and day of week
		$q1="SELECT * FROM frequency WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
		$res = mysqli_query($testing_con,$q1);	
		if(!$res){
			$testWeather = false;
		}

		//if the zipRegion already exists in the db...
		if (mysqli_num_rows($res) > 0){
			//if the weather description is clear
			if ($weatherDesc === "Clear"){
				//fetch the already existing frequency count for this weather description
				while ($row = $res->fetch_assoc()){
					$freq_count = $row["freq_clear"];
				}
				//increment the frequency count for this weather condition
				$freq_count++; 
				//update the freq_database
				$q2="UPDATE frequency SET freq_clear='$freq_count' WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			//if the weather description is fog
			else if ($weatherDesc === "Snow"){
				//fetch the already existing frequency count for this weather description
				while ($row = $res->fetch_assoc()){
					$freq_count = $row["freq_snow"];
				}
				//increment the frequency count for this weather condition
				$freq_count++; 
				//update the freq_database
				$q2="UPDATE frequency SET freq_snow='$freq_count' WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			//if the weather description is cloudy
			else if ($weatherDesc === "Cloudy"){
				//fetch the already existing frequency count for this weather description
				while ($row = $res->fetch_assoc()){
					$freq_count = $row["freq_cloudy"];
				}
				//increment the frequency count for this weather condition
				$freq_count++; 
				//update the freq_database
				$q2="UPDATE frequency SET freq_cloudy='$freq_count' WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			//if the weather description is rain
			else if ($weatherDesc === "Rain"){
				//fetch the already existing frequency count for this weather description
				while ($row = $res->fetch_assoc()){
					$freq_count = $row["freq_rain"];
				}
				//increment the frequency count for this weather condition
				$freq_count++; 
				//update the freq_database
				$q2="UPDATE frequency SET freq_rain='$freq_count' WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			//if the weather description is fog
			else if ($weatherDesc === "Fog"){
				//fetch the already existing frequency count for this weather description
				while ($row = $res->fetch_assoc()){
					$freq_count = $row["freq_fog"];
				}
				//increment the frequency count for this weather condition
				$freq_count++; 
				//update the freq_database
				$q2="UPDATE frequency SET freq_fog='$freq_count' WHERE zipRegion = '$zip' AND hour='$hour' AND day='$day'";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
		}
		else{//the zip region doesnt exist in the db...we must add it and set the inital values of the columns
			if ($weatherDesc === "Clear"){
				$q2 = "INSERT INTO frequency (freq_clear, zipRegion, hour, day, observedAt) VALUES (1, '$zip','$hour','$day','$fullDate')";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			else if ($weatherDesc === "Snow"){
				$q2 = "INSERT INTO frequency (freq_snow, zipRegion, hour, day, observedAt) VALUES (1, '$zip','$hour','$day','$fullDate')";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			else if ($weatherDesc === "Cloudy"){
				$q2 = "INSERT INTO frequency (freq_cloudy, zipRegion, hour, day, observedAt) VALUES (1, '$zip','$hour','$day','$fullDate')";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			else if ($weatherDesc === "Rain"){
				$q2 = "INSERT INTO frequency (freq_rain, zipRegion, hour, day, observedAt) VALUES (1, '$zip','$hour','$day','$fullDate')";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
			else if ($weatherDesc === "Fog"){
				$q2 = "INSERT INTO frequency (freq_fog, zipRegion, hour, day, observedAt) VALUES (1, '$zip','$hour','$day','$fullDate')";
				$test = mysqli_query($testing_con,$q2);
				if(!$test){
					$testWeather = false;
				}
			}
		}
	}



//**********************************************************************************************************************************************//	
//****************************************************** TRAFFIC INCIDENT COLLECTION  **********************************************************//
//**********************************************************************************************************************************************//
	$trafficAPI = true;
	//bing traffic api 
	$url = 'http://dev.virtualearth.net/REST/V1/Traffic/Incidents/39,-79,43,-73/true?t=1,2,3,4,5,6,7,8,10,11&s=1,2,3,4&key=AtfNAQ2-7DO88zFVrB7xChxDexS3OHilo5phzX00d7qohRMS9WcERj2gv6zLr67s';
	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
		$trafficAPI = false;
	}
	$ch = curl_init();
	if (!$ch){
		die('Couldn\'t initialize a cURL module');
		$trafficAPI = false;	
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	$data = curl_exec($ch);
	curl_close($ch);

	// The code mo wrote for parsing the data starts here //
	$parse = json_decode($data);
	$info = $parse->resourceSets[0]->resources;

	$total = $parse->resourceSets[0]->estimatedTotal;
	
	// for each traffic incident 

	for ($i = 0; $i < $total; $i++) {
		$geoAPI = true;
		$testTraffic = true;
		//collect all the needed data from it
		$zipRegion = NULL;
		$lat = $info[$i]->point->coordinates[0];
		$long = $info[$i]->point->coordinates[1];
		$desc = $info[$i]->description;
		$lastModified = $info[$i]->lastModified;
		preg_match('/(\d{10})(\d{3})/', $lastModified, $matches);
		$dt0 = DateTime::createFromFormat("U.u",vsprintf('%2$s.%3$s', $matches));
		$dt0->setTimeZone(new DateTimeZone('America/New_York'));
		$lastModifiedDT = $dt0->format('Y-m-d H:i:s');
		$end= $info[$i]->end;
		preg_match('/(\d{10})(\d{3})/', $end, $matches);
		$dt1 = DateTime::createFromFormat("U.u",vsprintf('%2$s.%3$s', $matches));
		$dt1->setTimeZone(new DateTimeZone('America/New_York'));
		$endDT = $dt1->format('Y-m-d H:i:s');
		$start= $info[$i]->start;
		preg_match('/(\d{10})(\d{3})/', $start, $matches);
		$dt2 = DateTime::createFromFormat("U.u",vsprintf('%2$s.%3$s', $matches));
		$dt2->setTimeZone(new DateTimeZone('America/New_York'));
		$startDT = $dt2->format('Y-m-d H:i:s');
		$incidentId= $info[$i]->incidentId;
		$roadClosed= $info[$i]->roadClosed;
		$severity= $info[$i]->severity;
		$type= $info[$i]->type;
		$latEnd = $info[$i]->toPoint->coordinates[0];
		$longEnd = $info[$i]->toPoint->coordinates[1];

		$urlZip = 'http://api.geonames.org/findNearbyPostalCodesJSON?lat='.$lat.'&lng='.$long.'&username=bmonticello23';
		if (!function_exists('curl_init')){
			die('Can\'t find cURL module');	
			$geoAPI = false;
		}
		$chZip = curl_init();
		if (!$chZip){
			die('Couldn\'t initialize a cURL module');	
			$geoAPI = false;
		}

		curl_setopt($chZip, CURLOPT_URL, $urlZip);
		curl_setopt($chZip, CURLOPT_RETURNTRANSFER, TRUE);
		
		$dataZip = curl_exec($chZip);
		curl_close($chZip);

		// The code mo wrote for parsing the data starts here //
		$parseZip = json_decode($dataZip);

		//zip code of the traffic incident 
		$zipCode = $parseZip->postalCodes[0]->postalCode;
		//convert the zipCode into a prefixed zipcode:
		//extract the first 3 digits of $zipCode - 

		if ($zipCode[0] != '0') {//its a full zipcode
			$zipPrefix = substr($zipCode, 0, 3); //first 3 digits
		}
		else { //its a nj zipcode with 0 missing
			$zipPrefix = substr($zipCode, 1, 2);
		}
		$q9 = "SELECT DISTINCT zipRegion FROM frequency WHERE zipRegion LIKE '$zipPrefix%'";
		$resZip = mysqli_query($testing_con, $q9);
		if(!$resZip){
			$testTraffic = false;
		}

		if ($row1 = $resZip->fetch_assoc()){
			$zipRegion = $row1["zipRegion"];
		}
		//if we are actually interested in this zip code
		if ($zipRegion){

			//get the current weather
			$urlWeather = 'http://api.wunderground.com/api/'.$t_keys[($i%6)].'/conditions/q/'.$zipRegion.'.json';
			if (!function_exists('curl_init')){
				die('Can\'t find cURL module');	
				$weatherAPI = false;
			}
			$chW = curl_init();
			if (!$chW){
				die('Couldn\'t initialize a cURL module');	
				$weatherAPI = false;
			}

			curl_setopt($chW, CURLOPT_URL, $urlWeather);
			curl_setopt($chW, CURLOPT_RETURNTRANSFER, TRUE);
			
			$dataW = curl_exec($chW);
			curl_close($chW);
			$parseW = json_decode($dataW);

			//weather description, temperature, and precipitation
			$weatherDTN = $parseW->current_observation->weather;

			// change weatherD to 5 descriptions we actually use
			$weatherD = switchWeather($weatherDTN);

			$temp = $parseW->current_observation->temp_f;
			$precip = $parseW->current_observation->precip_today_in;
			if ($precip < 0.00){
				$precip = 0.00;			
			}

			//get the gridbox of the incident

			//grab the grid id(s) for the incident
			$q10 = "SELECT id FROM grid 
			WHERE ('$lat' >= latS AND '$lat' <= latN AND '$long' >= longW AND '$long' <= longE)
			OR ('$latEnd' >= latS AND '$latEnd' <= latN AND '$longEnd' >= longW AND '$longEnd' <= longE)";
			$res2 = mysqli_query($testing_con,$q10);
			if(!$res2){
				$testTraffic = false;
			}

			//for each grid box the incident is contained in....
			$grids = array();
			while($row = $res2->fetch_assoc()){
				array_push($grids, $row["id"]);
			}
			// start reverse geocoding
			$urlRevereseGeo = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key='.$g_keys[($i%6)];
			if (!function_exists('curl_init')){
				die('Can\'t find cURL module');	
				$geoAPI = false;
			}

			$chRG = curl_init(); //RG = Reverse Geocoding
			if (!$chRG){
				die('Couldn\'t initialize a cURL module');	
				$geoAPI = false;
			}
			curl_setopt($chRG, CURLOPT_URL, $urlRevereseGeo);
			curl_setopt($chRG, CURLOPT_RETURNTRANSFER, TRUE);
			
			$dataRG = curl_exec($chRG);
			curl_close($chRG);

			$parseRG = json_decode($dataRG);
			//road name of the traffic incident
			$type = $parseRG->results[0]->address_components[0]->types[0];

			if ($type === "street_number"){
				$type1 = $parseRG->results[0]->address_components[1]->types[0];
				if ($type1 != "street_number"){
					$roadName = $parseRG->results[0]->address_components[1]->long_name;
				}
			}
			else{
				$roadName = $parseRG->results[0]->address_components[0]->long_name;
			}

			$road_name = $roadName;
            		$start_lat = $lat;
            		$start_lng = $long;
            		$end_lat = $latEnd;
            		$end_lng = $longEnd;
            		include('find_startstop_testing.php');

			//start day of the week and hour
			$startDt0 = date_create($startDT);
			$startDW = $startDt0->format('w');
			$startH = $startDt0->format('H');
			$endDT0 = date_create($endDT);
			$endH = $endDT0->format('H');

			$curTime = new DateTime();
			$curDW = $curTime->format('w');
			$curH = $curTime->format('H');
			//if the current incident already exists in the database and it's last modified time is later than whats currently in the db, update everything,
			//because any of the data points could have changed.
			$q3 = "SELECT * FROM traffic_data WHERE incidentId = '$incidentId'";
			$result = mysqli_query($testing_con,$q3);
			if(!$result){
				$testTraffic = false;
			}

			//if there is already an incident in the db with the same incidentID don't increment the severity table at all
			if (mysqli_num_rows($result) > 0){
				//don't update that incident just yet
				$shouldUpdate = FALSE;
				while ($row = $result->fetch_assoc()){
					//if the lastModified datetime for this incident in the db is not the same as the current lastModified datetime pulled from the API for this incident
					if ($row["lastModified"] != $lastModifiedDT){
						//then we should update this record
						$shouldUpdate = TRUE;
					}
				}
				//update the record
				if ($shouldUpdate){
					//update existing entry that matches incident id with all the variables
					$q4 = "UPDATE traffic_data SET startLat='$lat', endLat='$latEnd', startLong='$long', endLong='$longEnd', zipCode='$zipCode',description='$desc', startTime='$startDT', endTime='$endDT', severity='$severity', type='$type', roadClosed='$roadClosed', lastModified = '$lastModifiedDT' WHERE incidentId='$incidentId'";
					$test = mysqli_query($testing_con,$q4);
					if(!$test){
						$testTraffic = false;
					}
				}
				//only if the current zipRegion is in our freq table:
				foreach ($grids as $gridBox) {
					setSev($weatherD, $gridBox, $zipRegion, $roadName, $curDW, $curH, $severity);
				}
			}
			//otherwise this is a new incident, so we have to insert it and also update the severity table and averages
			else{
				//insert the variables into the database
				$q4="INSERT INTO traffic_data (incidentId, startLat, endLat, startLong, endLong, zipCode, description, startTime, endTime, severity, type, roadClosed, lastModified, weather, temp, precip) VALUES ('$incidentId','$lat','$latEnd','$long','$longEnd','$zipCode','$desc','$startDT','$endDT','$severity','$type','$roadClosed','$lastModifiedDT','$weatherD','$temp','$precip')";
				$test = mysqli_query($testing_con,$q4);
				if(!$test){
					$testTraffic = false;
				}
				//now the new traffic incident is in the database	

				//for each NEW traffic incident, we have to update / add the new entry to the severity table and recalculate the averages
				//the new frequency for each weather condition has also been added in the very beginning of this file
				//only if the current zipRegion is in our freq table
				foreach ($grids as $gridBox) {
					setSev($weatherD, $gridBox, $zipRegion, $roadName, $curDW, $curH, $severity);
				}
			}
		}
	}



//**********************************************************************************************************************************************//	
//****************************************************** AVERAGE SEVERITY CALCULATION  *********************************************************//
//**********************************************************************************************************************************************//	
	$testAvg = true;
//grab all the entries in the severity table to update them
	$qu = "SELECT * FROM severity";
	$resU = mysqli_query($testing_con,$qu);
	if(!$resU){
		$testAvg = false;
	}

	//loop through all entries from the resulting query
	while ($rowU = $resU->fetch_assoc()){
		$id = $rowU["id"];
		$sevC = $rowU["sev_clear"];
		$sevS = $rowU["sev_snow"];
		$sevCL = $rowU["sev_cloudy"];
		$sevR = $rowU["sev_rain"];
		$sevF = $rowU["sev_fog"];
		$dayWeek = $rowU["day"];
		$hourDay = $rowU["hour"];

		//for each entry in severity 
		//1. get the frequency for each weather condition of the zipRegion associated with the severity entry
		//select the zipcode of the severity entry
		$zipR = $rowU["zipRegion"];

		$qf = "SELECT * FROM frequency WHERE zipRegion = '$zipR' AND day = '$dayWeek' AND hour = '$hourDay'"; 
		$resf = mysqli_query($testing_con,$qf);
		if(!$resf){
			$testAvg = false;
		}

		while ($rowf = $resf->fetch_assoc()){ //should only be 1 row
			$freqC = $rowf["freq_clear"];
			$freqS = $rowf["freq_snow"];
			$freqCL = $rowf["freq_cloudy"];
			$freqR = $rowf["freq_rain"];
			$freqF = $rowf["freq_fog"];
		}


		if ($freqC != 0){
			$avg_clear = $sevC / $freqC; //new average severity
			$qI = "UPDATE severity SET avg_clear = '$avg_clear' WHERE id='$id'";
			$test = mysqli_query($testing_con,$qI);	
			if(!$test){
				$testAvg = false;
			}
		}
		if ($freqS != 0){
			$avg_snow = $sevS / $freqS; //new average severity	
			$qI = "UPDATE severity SET avg_snow = '$avg_snow' WHERE id='$id'";
			$test = mysqli_query($testing_con,$qI);
			if(!$test){
				$testAvg = false;
			}
		}
		if ($freqCL != 0){
			$avg_cloudy = $sevCL / $freqCL; //new average severity
			$qI = "UPDATE severity SET avg_cloudy = '$avg_cloudy' WHERE id='$id'";
			$test = mysqli_query($testing_con,$qI);	
			if(!$test){
				$testAvg = false;
			}
		}
		if ($freqR != 0){
			$avg_rain = $sevR / $freqR; //new average severity
			$qI = "UPDATE severity SET avg_rain = '$avg_rain' WHERE id='$id'";
			$test = mysqli_query($testing_con,$qI);
			if(!$test){
				$testAvg = false;
			}	
		}
		if ($freqF != 0){
			$avg_fog = $sevF / $freqF; //new average severity
			$qI = "UPDATE severity SET avg_fog = '$avg_fog' WHERE id='$id'";
			$test = mysqli_query($testing_con,$qI);
			if(!$test){
				$testAvg = false;
			}	
		}
	}

	$testCleanup = true;
	//clean up the 0's in weather
	$q5 = "SELECT * FROM traffic_data WHERE weather IS NULL OR weather = ' '";
	$res = mysqli_query($testing_con,$q5);
	if(!$res){
		$testCleanup = false;
	}

	//clean up the 0s and reset the identity seed

	if (mysqli_num_rows($res) > 0){ //if there are entries with null weather...
		$q6 = "DELETE FROM traffic_data WHERE weather IS NULL OR weather = ' '";
		$test = mysqli_query($testing_con,$q6);
		if(!$test){
			$testCleanup = false;
		}

		//reset the identity seed
		$q7 = "ALTER TABLE traffic_data DROP id";
		$test = mysqli_query($testing_con,$q7);
		if(!$test){
			$testCleanup = false;
		}

		$q8 = "ALTER TABLE traffic_data ADD id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
		$test=mysqli_query($testing_con,$q8);
		if(!$test){
			$testCleanup = false;
		}
	}

	mysqli_close($testing_con);
	mysqli_close($testing_con);
?>
