<?php
//this is the controller. it's gonna handle the flow

//this guy stores the params
class paramStorage{
	$conditionParams
	$locParams
	
	//this is where vals are stored
	function storeParams($inputs){

	}	
	
	function getParams($whichParam){
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
