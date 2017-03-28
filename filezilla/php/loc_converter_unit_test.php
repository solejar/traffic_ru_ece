<?php
include("loc_converter.php");

$locParamsLC =  array(
				"zip"        => "08816",
				"range"      => "10",
			);

$locParamsLCRoute =  array(
				"start"      => "504 Merrywood Drive Edison NJ",
				"end"      => "7 Hancock Court East Brunswick NJ",
			);

$feature1 = "heatmap";
$feature2 = "route";

echo "Testing callLocServHeat. Expected Result: Passed\n";
$arrayLC = get_location($locParamsLC, $feature1);
 if ($arrayLC[0] == 40.429105 && $arrayLC[1] == -74.416332) {
 	echo "callLocServHeat Test Passed\r\n";
 }
 else {
 	echo "callLocServHeat Test Failed\n";
 }


echo "Testing callLocServRoute. Expected Result: Passed\n";

$arrayRouteLC = get_location($locParamsLCRoute, $feature2);
if ($arrayRouteLC[0][0] == 40.5274373 && $arrayRouteLC[0][1] == -74.410707 && $arrayRouteLC[1][0] == 40.4095288 && $arrayRouteLC[1][1]==-74.4169299  ){
	echo "callLocServRoute Test Passed\n";


}
else {
	echo "callLocServRoute Test Failed\n";
}

echo "Testing get_Zip for Route. Expected Result: Passed\n";

$testZip = getZip($locParamsLCRoute, $feature2);

if ($testZip == "08817"){
	echo "get_Zip for Route Passed\n";
}
else {
	echo "get_Zip for Route Failed\n";
}

echo "Testing get_Zip for Heat Map. Expected Result: Passed\n";
$testZipHeat = get_Zip($locParamsLC, $feature1);

if ($testZipHeat == "08816"){
	echo "get_Zip for Heat Map Passed\n";
}
else {
	echo "get_Zip for Heat Map Failed\n";
}



?>

