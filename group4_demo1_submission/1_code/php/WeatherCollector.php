<?php


//input to this file is getForecast(A string of date, number representing time, zip code);
//function to returns weatherService on input parameters
function getForecast($dateWC, $timeWC, $zipWC){
	$timeStr = getTimeStr($timeWC);	

	$paramArray=array(
			"date" => $dateWC,
			"time" => $timeStr,
			"loc" => $zipWC,
		);

	return callWeatherService($paramArray);
	//Getter function, takes an array of date/time info as parameters, outputs array of strings that detail weather forecast
	//returns an array 
}

//function that returns input_index for the returned JSON array from api
function getInputIndex($inputTime)
{
		
	    date_default_timezone_set("America/New_York");
		$curr_time= date("Y-m-d h:i:sa");
		
		$d=strtotime($inputTime);
		$input_Time= date("Y-m-d h:i:sa",$d); //input_Time is changed to a date time
		
		$first  = new DateTime($input_Time); 
		$second = new DateTime($curr_time);
		$diff = $first->diff( $second );
		//echo $diff->format( '%D' );
		$input_indexD=  (int) $diff->format( '%D' );
		$input_indexH=  (int) $diff->h;
		//echo "<br>";
		$input_Index=  ($input_indexH +($diff->days*24)); //contains the input index which is difference in time, and days from current input time to user input time
		return $input_Index;
	
}

//function that calls the weather api and parses data for other functions
function callWeatherService($allParams){

   $wundergroundKey="2b40266932a5be8a";
	$zip=$allParams["loc"];

		//call weather service by zip code here 
		$urlWeather = 'http://api.wunderground.com/api/'.$wundergroundKey.'/hourly10day/q/'.$zip.'.json'; 
		if (!function_exists('curl_init')){
			die('Can\'t find cURL module');	
		}
		else{
			//echo "works\n";
		}
		$chWC = curl_init();
		if (!$chWC){
			die('Couldn\'t initialize a cURL module');	
		}
		else{
			//echo "works\n";
		}
		//set up curl module 
		curl_setopt($chWC, CURLOPT_URL, $urlWeather);
		curl_setopt($chWC, CURLOPT_RETURNTRANSFER, TRUE);
		$dataWC = curl_exec($chWC);
		curl_close($chWC);
		
		$parseWC = json_decode($dataWC); 
		//parse the JSON here
		$myHourlyForecast=$parseWC->hourly_forecast;

		$indexInput = $allParams["time"]." ".$allParams["date"];
		//retrieve all neccessary parsed data from JSON output
		$date_time=$parseWC->hourly_forecast[getInputIndex($indexInput)]->FCTTIME->pretty;
				
		$myCondition= $parseWC->hourly_forecast[getInputIndex($indexInput)]->condition;
		
		$weatherArray=array($date_time,weatherSwitch($myCondition));
	
		return weatherSwitch($myCondition); //this is the returned weather for a specific location at a specific time
		}
	
//switch statement that includes all possible weather conditions outputted from the weather api 
function weatherSwitch($myCondition)
{
		//switch statement to take the weather from the API and make it what we want
		switch($myCondition){
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
			case "Chance of Flurries":
			case "Chance of Sleet":
			case "Chance of Snow":
			case "Flurries":
			case "Blowing Snow":
			case "Chance of Snow Showers":
			case "Chance of Ice Pellets":
			case "Blizzard":
				$myCondition = "Snow";
				break;
			case "Mostly Cloudy":
			case "Overcast":
				$myCondition = "Cloudy";
				break;
			case "Clear":
			case "Partly Cloudy":
			case "Scattered Clouds":
			case "Very Hot":
			case "Very Cold":
				$myCondition= "Clear";
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
			case "Scattered Clouds":
				$myCondition= "Fog";
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
			case "Chance of Rain":
			case "Chance of a Thunderstorm":
			case "Chance Rain":
			case "Chance of Freezing Rain":
			case "Chance of Thunderstorms":
			case "Thunderstorm":
			case "Thunderstorms":
			case "Chance of Showers":
			case "Showers":
			
				$myCondition= "Rain";
				break;
			default:
				$myCondition = false;
				break;
		}
		//echo $myCondition;
	
	//Helper function used by getForecast(). Makes an API call to the weather service, and formats the API’s JSON response into an array of forecast info
	
	return $myCondition;
}

//switch statements that allows for form input entry time to be converted to user friendly outputs
function getTimeStr($timeWC){
 	switch($timeWC){
 		case 0;
 			$timeStr = "12:00am";
 			break;
 		case 1;
 			$timeStr = "1:00am";
 			break;
 		case 2;
 			$timeStr = "2:00am";
 			break;
 		case 3;
 			$timeStr = "3:00am";
 			break;
 		case 4;
 			$timeStr = "4:00am";
 			break;
 		case 5;
 			$timeStr = "5:00am";
 			break;
 		case 6;
 			$timeStr = "6:00am";
 			break;
 		case 7;
 			$timeStr = "7:00am";
 			break;
 		case 8;
 			$timeStr = "8:00am";
 			break;
 		case 9;
 			$timeStr = "9:00am";
 			break;
 		case 10;
 			$timeStr = "10:00am";
 			break;
 		case 11;
 			$timeStr = "11:00am";
 			break;
 		case 12;
 			$timeStr = "12:00pm";
 			break;
 		case 13;
 			$timeStr = "1:00pm";
 			break;
 		case 14;
 			$timeStr = "2:00pm";
 			break;
 		case 15;
 			$timeStr = "3:00pm";
 			break;
 		case 16;
 			$timeStr = "4:00pm";
 			break;
 		case 17;
 			$timeStr = "5:00pm";
 			break;
 		case 18;
 			$timeStr = "6:00pm";
 			break;
 		case 19;
 			$timeStr = "7:00pm";
 			break;
 		case 20;
 			$timeStr = "8:00pm";
 			break;
 		case 21;
 			$timeStr = "9:00pm";
 			break;
 		case 22;
 			$timeStr = "10:00pm";
 			break;
 		case 23;
 			$timeStr = "11:00pm";
 			break;

 	}  
 	return $timeStr;
 }

?>