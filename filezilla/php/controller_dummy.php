<?php
//this is the controller. it's gonna handle the flow

//this guy stores the params
class paramStorage{
	private $conditionParams; 
	private $locParams;
	private $feature;
	/*
	0 - 1 Forecast first, bool, 0 or 1
	l - loc1 - for heatmap, zipcode. For route, start location
	2 - loc2 - for heatmap, range. For route, end location
	3 - weather - for forecast ==1, null. For forecast ==0, from forms
	4 - severity - same for both
	5 - time - same for both
	6 - day - for forecast ==0, take from form. For forecast ==1, parse from date form
	7 - date - for forecast==0, null. For forecast ==1, read from form.
	8 - whichFeature - ‘heatmap’,’route’,’agenda’ based on the folder where index.html is i.e. heatmap/index.html
	*/
	
	//this is where vals are stored
	public function storeParams($inputs){
		$feature = $inputs[8];

		if ($feature == "heatmap")
		{
			if ($inputs[0] =="0"){
				//user inputted weather
				$userLoc = $inputs[1];
				$range = $inputs[2];
				$weather = $inputs[3];
				$sevArray = $inputs[4];
				$time = $inputs[5];
				$day = $inputs[6];

			}

			else if ($input[0] == "1") {
				$userLoc = $inputs[1];
				$range = $inputs[2];
				// $weather = get from weather collector
				$sevArray = $inputs[4];
				$time = $inputs[5];
				$day = $inputs[6];

				
			}

			$locParams = array($userLoc, $$range);
			$conditionParams = array($weather, $sevArray, $time, $day);
		} //end of heat map

		else if ($feature == "route"){
			if ($inputs[0] == "0"){
				//user inputted weather
				$startLoc = $inputs[1];
				$endLoc = $inputs[2];
				$weather = $inputs[3];
				$sevArray = $inputs[4];
				$time = $inputs[5];
				$day = $inputs[6];




			}

			else if ($input[0] == "1") {
				//forecasted weather
				$startLoc = $inputs[1];
				$endLoc = $inputs[2];
				// $weather = get from weather collector
				$sevArray = $inputs[4];
				$time = $inputs[5];
				$day = $inputs[6];

			}
			$locParams = array($startLoc, $endLoc);
			$conditionParams = array($weather, $sevArray, $time, $day);

		} //end of route 



	}	
	
	public function getParams($whichParam){

	}
}

//collect args from argv

//if forecast===1,
if($forecast===1){
	$wf = shell_exec('/usr/bin/php weather_collector.php params_go_here');
	array_push($userParams, $wf)
}

paramStore = new paramStorage();

paramStore.storeParams($userParams);



?>
