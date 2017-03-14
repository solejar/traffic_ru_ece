<?php 
	//weather.com api keys
	//03b51d9a0728e20c   ---- mo's rate exceeded key
	$keys = array("bbc28e9076215cb5","4e7177537bc57072","03b51d9a0728e20c","4c22265185030920","a0dc0b90ca53db30","7b5fe87d7a89fc4b");
	$con = mysqli_connect("db667824699.db.1and1.com","dbo667824699","briansbutt","db667824699");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$url = 'http://dev.virtualearth.net/REST/V1/Traffic/Incidents/39,-79,43,-73/true?t=1,2,3,4,5,6,7,8,10,11&s=1,2,3,4&key=AtfNAQ2-7DO88zFVrB7xChxDexS3OHilo5phzX00d7qohRMS9WcERj2gv6zLr67s';
	if (!function_exists('curl_init')){
		die('Can\'t find cURL module');	
	}
	$ch = curl_init();
	if (!$ch){
		die('Couldn\'t initialize a cURL module');	
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	$data = curl_exec($ch);
	curl_close($ch);

	// The code mo wrote for parsing the data starts here //
	$parse = json_decode($data);
	$info = $parse->resourceSets[0]->resources;

	$total = $parse->resourceSets[0]->estimatedTotal;
	
	// You can use this for loop to iterate through each set of coordinates in the json ////////////
	// I simply echo the information so you guys can see it, but obviously we wouldnt need to do that
	// we would simply check if its in the data base, if not, push to database
	for ($i = 0; $i < $total; $i++) {
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
			}
			$chZip = curl_init();
			if (!$chZip){
				die('Couldn\'t initialize a cURL module');	
			}

			curl_setopt($chZip, CURLOPT_URL, $urlZip);
			curl_setopt($chZip, CURLOPT_RETURNTRANSFER, TRUE);
			
			$dataZip = curl_exec($chZip);
			curl_close($chZip);

			// The code mo wrote for parsing the data starts here //
			$parseZip = json_decode($dataZip);
			$zipCode = $parseZip->postalCodes[0]->postalCode;
	
			//if the current incident already exists in the database and it's last modified time is later than whats currently in the db, update everything,
			//because any of the data points could have changed.
			$query = "SELECT * FROM group_4 WHERE incidentId = '$incidentId'";
			$result = mysqli_query($con,$query);
			//if there is already an incident in the db with the same incidentID
			if (mysqli_num_rows($result) > 0){
				//don't update that incident just yet
				$shouldUpdate = FALSE;
				$needWeather = FALSE;
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
					$q1 = "UPDATE group_4 SET startLat='$lat', endLat='$latEnd', startLong='$long', endLong='$longEnd', zipCode='$zipCode',description='$desc', startTime='$startDT', endTime='$endDT', severity='$severity', type='$type', roadClosed='$roadClosed', lastModified = '$lastModifiedDT' WHERE incidentId='$incidentId'";
					mysqli_query($con,$q1);
				}
			}
			else{
				$urlWeather = 'http://api.wunderground.com/api/'.$keys[($i%6)].'/conditions/q/'.$zipCode.'.json';
				if (!function_exists('curl_init')){
					die('Can\'t find cURL module');	
				}
				$chW = curl_init();
				if (!$chW){
					die('Couldn\'t initialize a cURL module');	
				}

				curl_setopt($chW, CURLOPT_URL, $urlWeather);
				curl_setopt($chW, CURLOPT_RETURNTRANSFER, TRUE);
				
				$dataW = curl_exec($chW);
				curl_close($chW);

				// The code mo wrote for parsing the data starts here //
				$parseW = json_decode($dataW);
				$weatherDescription = $parseW->current_observation->weather;
				$temp = $parseW->current_observation->temp_f;
				$precip = $parseW->current_observation->precip_today_in;
				if ($precip < 0.00){
					$precip = 0.00;			
				}
				//insert the variables into the database
				$q2="INSERT INTO group_4 (incidentId, startLat, endLat, startLong, endLong, zipCode, description, startTime, endTime, severity, type, roadClosed, lastModified, weather, temp, precip) VALUES ('$incidentId','$lat','$latEnd','$long','$longEnd','$zipCode','$desc','$startDT','$endDT','$severity','$type','$roadClosed','$lastModifiedDT','$weatherDescription','$temp','$precip')";
				mysqli_query($con,$q2);
			}
	}
	mysqli_close($con);
?>
