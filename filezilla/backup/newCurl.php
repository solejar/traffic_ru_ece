<?php 
	//weather.com api keys
	$keys = array("bbc28e9076215cb5","4e7177537bc57072","03b51d9a0728e20c","4c22265185030920","a0dc0b90ca53db30","7b5fe87d7a89fc4b");
	$con = mysqli_connect("db670831916.db.1and1.com","dbo670831916","briansbutt","dbo670831916");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$zips = array("07652","08901","07936","08816");		//array of zip codes
	// instead of an array, could we perhaps pull from a file?
	$totalZips = sizeof($zips); 	//need the total number of zips here
	for ($i = 0; $i < $totalZips; $i++){

		$urlWeather = 'http://api.wunderground.com/api/'.$keys[($i%6)].'/conditions/q/'.$zips[$i].'.json';
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
		// change weatherDescription to 5 descriptions we actually use
		switch ($weatherDescription){

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
				$weatherDescription = "Snow";
				break;
			case "Mostly Cloudy":
			case "Overcast":
				$weatherDescription = "Cloudy";
				break;
			case "Clear":
			case "Partly Cloudy":
			case "Scattered Clouds":
				$weatherDescription = "Clear";
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
				$weatherDescription = "Fog";
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
				$weatherDescription = "Rain";
				break;
			default:
				// what should we make any different things go in as?
				$weatherDescription = "Invalid";
				break;
		}
		echo $weatherDescription;

		$temp = $parseW->current_observation->temp_f;
		$precip = $parseW->current_observation->precip_today_in;
		if ($precip < 0.00){
			$precip = 0.00;			
		}
		$time = $parseW->current_observation->observation_time_rfc822;
		$dt0 = date_create($time);
		$fullDate = $dt0->format('Y-m-d H:i:s');
		$hour = $dt0->format('H');
		$day = $dt0->format('w'); //sunday is 0 -> saturday is 6

		//insert the variables into the database
		$q2="INSERT INTO group_4 (incidentId, startLat, endLat, startLong, endLong, zipCode, description, startTime, endTime, severity, type, roadClosed, lastModified, weather, temp, precip) VALUES ('$incidentId','$lat','$latEnd','$long','$longEnd','$zipCode','$desc','$startDT','$endDT','$severity','$type','$roadClosed','$lastModifiedDT','$weatherDescription','$temp','$precip')";
		mysqli_query($con,$q2);
		

	}
	
	mysqli_close($con);
?>
