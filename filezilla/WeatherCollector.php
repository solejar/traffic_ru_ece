<?php

$testArray=array(
		"forecast" => true, //will always be true
		"zipcode" => "08755",
		"radius" => "10",
		"weather" => null, //what needs to be changed and sent back
		"severity" =>[true, true, false, false],
		"time" => "9",
		"day" => null,
		"date" => [3, 20, 2017],
		"whichFeature" =>"heatmap"
	);
$inputTime="9:00 P.M";
$weather=getForecast($testArray);
//echo "returned weather: <br>";
//echo $weather;
//assuming the input array is the big one that is created from info entry
function getForecast($allParams){
	$paramArray=array(
			"date" => $allParams["date"],
			"time" => $allParams["time"],
			"loc" => $allParams["zipcode"],
			"feature" => $allParams["whichFeature"]
		);

	callWeatherService($paramArray);
	//Getter function, takes an array of date/time info as parameters, outputs array of strings that detail weather forecast
	//returns an array 
}
function getInputIndex($inputTime)
{
		/*//echo "<br> hello world <br>";
		date_default_timezone_set("America/New_York");
		$curr_time= date("h:i:sa");
		//echo "The time is " . date("h:i:sa");
		//echo "<br>";	
		//print_r($curr_time);
		//echo "<br>";
		$d=strtotime($inputTime);
		$input_Time= date("h:i:sa",$d);
		//echo "Created time is " . date("h:i:sa", $d);
		//echo "<br>";
		//print_r($input_Time);
		//echo "<br>";
		$first  = new DateTime($input_Time);
		$second = new DateTime($curr_time);
		$diff = $first->diff( $second );
		//echo $diff->format( '%H' );
		$input_index= (int) $diff->format( '%H' );
		//echo "<br>";
		//print_r($input_index);
		//echo "<br>";
		return $input_index;*/
	    date_default_timezone_set("America/New_York");
		$curr_time= date("Y-m-d h:i:sa");
		//echo  date("Y-m-d h:i:sa");
		//echo "<br>";	
		//print_r($curr_time);
		//echo "<br>";
		$d=strtotime($inputTime);
		$input_Time= date("Y-m-d h:i:sa",$d);
		//echo "Created time is " . date("h:i:sa", $d);
		//echo "<br>";
		//print_r($input_Time);
		//echo "<br>";
		$first  = new DateTime($input_Time);
		$second = new DateTime($curr_time);
		$diff = $first->diff( $second );
		//echo $diff->format( '%D' );
		$input_indexD=  (int) $diff->format( '%D' );
		$input_indexH=  (int) $diff->h;
		//echo "<br>";
		$input_Index=  ($input_indexH +($diff->days*24));
		//print_r($input_Index);
		//echo "<br>";
		return $input_Index;
	
}
function callWeatherService($allParams){

	$wundergroundKey="2b40266932a5be8a";
	$zip=$allParams["loc"];

		echo "zip ";
		echo $zip;

		echo " \n In heat map \n";

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
		curl_setopt($chWC, CURLOPT_URL, $urlWeather);
		curl_setopt($chWC, CURLOPT_RETURNTRANSFER, TRUE);
		$dataWC = curl_exec($chWC);
		curl_close($chWC);
		
		$parseWC = json_decode($dataWC);
		//var_dump($parseWC);
		//parse the JSON here
		$myHourlyForecast=$parseWC->hourly_forecast;
		//echo "<br>";
		//echo "looking for it" ;
		//echo "<br>";
		//print_r($parseWC->hourly_forecast[11]->FCTTIME->pretty);
		//print_r("<br>");
		$date_time=$parseWC->hourly_forecast[getInputIndex("12:00am March 25 2017")]->FCTTIME->pretty;
		//print_r($date_time);
		//$minute=$parseWC->hourly_forecast[0]->FCTTIME->min;
		//print_r($hour + ":" + $minute);
		
		
		//$myConditionA= $parseWC->hourly_forecast[$input_index]->condition;
		
		$myCondition= $parseWC->hourly_forecast[getInputIndex("12:00am March 25 2017")]->condition;
		print_r("<br>");
		print_r($myCondition);
		//echo "<br>";
		//print_r($myConditionA);
		//$time=$parseWC->FCCTIME;
		//print_r($time);
		$weatherArray=array($date_time,$myCondition);
		echo "<br>";
		print_r(array_values($weatherArray));
		//echo weatherSwitch($myCondition);
		return weatherSwitch($myCondition);
		//this is the returned weather for a specific location at a specific time
		}
	
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
				$myCondition = "Snow";
				break;
			case "Mostly Cloudy":
			case "Overcast":
				$myCondition = "Cloudy";
				break;
			case "Clear":
			case "Partly Cloudy":
			case "Scattered Clouds":
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
				$myCondition= "Rain";
				break;
			default:
				// what should we make any different things go in as?
				$weatherDN = "Invalid";
				break;
		}
		//echo $myCondition;
	
	//Helper function used by getForecast(). Makes an API call to the weather service, and formats the API’s JSON response into an array of forecast info
	//return array
}


?>