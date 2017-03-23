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

$arrayLC = get_location($locParamsLC, $feature1);
 if ($arrayLC[0] == 40.429105 && $arrayLC[1] == -74.416332) {
 	echo "callLocServHeat Test Passed\n";
 }
 else {
 	echo "callLocServHeat Test Failed\n";
 }



$arrayRouteLC = get_location($locParamsLCRoute, $feature2);
echo "<br>";
echo $arrayRouteLC[0][0];
echo "<br>";
echo $arrayRouteLC[0][1];
echo "<br>";
echo $arrayRouteLC[1][0];
echo "<br>";
echo $arrayRouteLC[1][1];
echo "<br>";

$testZip = get_Zip($locParamsLCRoute, $feature2);

echo $testZip;


?>

