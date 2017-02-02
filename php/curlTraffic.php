<?php 

	$url = 'http://dev.virtualearth.net/REST/V1/Traffic/Incidents/39,-79,43,-73/true?t=1,2,3,4,5,6,7,8,9,10,11&s=1,2,3,4&key=AtfNAQ2-7DO88zFVrB7xChxDexS3OHilo5phzX00d7qohRMS9WcERj2gv6zLr67s';
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
	//$string = json_encode($data);
	//echo $string;

	// The code mo wrote for parsing the data starts here //
	$parse = json_decode($data);
	$info = $parse->resourceSets[0]->resources;
	print_r($info[0]);

	$total = $parse->resourceSets[0]->estimatedTotal;
	
	// You can use this for loop to iterate through each set of coordinates in the json ////////////
	// I simply echo the information so you guys can see it, but obviously we wouldnt need to do that
	// we would simply check if its in the data base, if not, push to database
	echo "<br><br> Coordinates <br>";
	for ($i = 0; $i < $total; $i++) {
		echo "The coordinates for incident ", $i, " is ";
		for ($j = 0; $j<2; $j++){
			echo $info[$i]->point->coordinates[$j], " ";
		}
		echo "<br>";
	}


	// You can use this for loop to iterate through each description in the json ////////////
	// echo "<br><br> Descriptions <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The description for incident ", $i, " is ";
	// 	echo $info[$i]->description;
	// 	echo "<br>";
	// }

	//You can use this for loop to iterate through each end time in the json ////////////
	// echo "<br><br> End Time <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The end time for incident ", $i, " is ";
	// 	echo $info[$i]->end;
	// 	echo "<br>";
	// }

	//You can use this for loop to iterate through each start time in the json ////////////
	// echo "<br><br> Start Time <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The start time for incident ", $i, " is ";
	// 	echo $info[$i]->start;
	// 	echo "<br>";
	// }

	//You can use this for loop to iterate through each incidentID in the json ////////////
	// echo "<br><br> Incident ID <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The Incident ID for incident ", $i, " is ";
	// 	echo $info[$i]->incidentId;
	// 	echo "<br>";
	// }

	//You can use this for loop to iterate through each incidentID in the json ////////////
	// echo "<br><br> Incident ID <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The Incident ID for incident ", $i, " is ";
	// 	echo $info[$i]->incidentId;
	// 	echo "<br>";
	// }

	// You can use this for loop to iterate through each roadClosed in the json ////////////
	// 1 is true, meaning the road is closed
	// echo "<br><br> roadClosed? <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The roadClosed for incident ", $i, " is ";
	// 	echo $info[$i]->roadClosed;
	// 	echo "<br>";
	// }

	// You can use this for loop to iterate through each severity in the json ////////////
	// echo "<br><br> Severity <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The severity for incident ", $i, " is ";
	// 	echo $info[$i]->severity;
	// 	echo "<br>";
	// }

	// You can use this for loop to iterate through each set of coordinates for the end coordinates in the json ////////////
	echo "<br><br> Ending Coordinates <br>";
	for ($i = 0; $i < $total; $i++) {
		echo "The ending coordinates for incident ", $i, " is ";
		for ($j = 0; $j<2; $j++){
			echo $info[$i]->toPoint->coordinates[$j], " ";
		}
		echo "<br>";
	}

	// You can use this for loop to iterate through each type in the json ////////////
	// echo "<br><br> Type <br>";
	// for ($i = 0; $i < $total; $i++) {
	// 	echo "The type for incident ", $i, " is ";
	// 	echo $info[$i]->type;
	// 	echo "<br>";
	// }
?>