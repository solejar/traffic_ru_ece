<?php

//holds the sequence of tests
class success_elem{
    public $input_valid;
    public $zip_api_valid;
    public $forecast_api_valid;
    public $loc_api_valid;
    public $route_api_valid;
    public $expected_output;

    function __construct($a1,$a2,$a3,$a4,$a5,$a6){
        $this->input_valid = $a1;
        $this->zip_api_valid = $a2;
        $this->forecast_api_valid = $a3;
        $this->loc_api_valid = $a4;
        $this->route_api_valid = $a5;
        $this->expected_output = $a6;
    }
}

//include the controller script
include 'controller_testing.php';

//the params for route feature 
    $userParams = array(
        "which_feature" => "route",
        "loc1"          => "72 Flocktown Road, Long Valley, NJ",
        "loc2"          => "52 Guilden St, New Brunswick, NJ",
        "forecast"      => false,
        "time"          => 17,
        "day"           => 4,
        "severity"      => array(true,true,true,true),
        "weather"       => "clear",
    );

    //test feature
    integration_test("route",$userParams);

//the params for forecasted_route feature
    $userParams = array(
        "which_feature" => "forecasted_route",
        "loc1"          => "72 Flocktown Road, Long Valley, NJ",
        "loc2"          => "52 Guilden St, New Brunswick, NJ",
        "forecast"      => true,
        "time"          => 17,
        "day"           => "March 31, 2017",
        "severity"      => array(true,true,true,true),
        "weather"       => "",
    );

    //test feature
    integration_test("forecasted_route",$userParams); 

//the params for heatmap feature
    $userParams = array(
        "which_feature" => "heatmap",
        "loc1"          => "07853",
        "loc2"          => 20,
        "forecast"      => false,
        "time"          => 17,
        "day"           => 4,
        "severity"      => array(true,true,true,true),
        "weather"       => "clear",
    );   

    //test feature
    integration_test("heatmap",$userParams);

//the params for forecasted_heatmap
    $userParams = array(
        "which_feature" => "forecasted_heatmap",
        "loc1"          => "07853",
        "loc2"          => 20,
        "forecast"      => true,
        "time"          => 17,
        "day"           => "March 31, 2017",
        "severity"      => array(true,true,true,true),
        "weather"       => "",
    );
    
    //test feature
    integration_test("forecasted_heatmap",$userParams);


//this function tests the feature 
//to ensure that every system state and transition is made.
function integration_test($feature,$inputs){

    //get all the permutations of input conditions from func
    $func = 'instantiate_'.$feature.'_cond';
    $success_matrix = $func();

    $which_feature = $feature;

    echo "<br>For feature ".$which_feature.":<br><br>";

    //run code for every permutation of input conditions ()
    foreach($success_matrix as $item){

        $errorcode = test_func($item,$inputs);
        $expected = $item->expected_output;

        //compare errorcode to expected output
        if($errorcode == ""){
            echo "Expected ".$expected.", got: Successful execution<br>";
        }else{
            echo "Expected ".$expected.", got: ".$errorcode."<br>";
        }

    }
}

//get permutations of inputs for route feature
function instantiate_route_cond(){
    $output_matrix = array();

    //this array represents 5 successive failed api calls. it should stop controller
    $invalid_arr = array(false,false,false,false,false);
    //this array represents 2 failed api calls, then a success call. It should allow script to proceed.
    $valid_arr = array(false,false,true);

    //these are all permutations of execution conditions
    $success_element = new success_elem(0,0,0,0,0,"invalid input");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,0,0,0,$invalid_arr,"invalid route");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,0,0,0,$valid_arr,"success");
    array_push($output_matrix,$success_element);


    return $output_matrix;
}

//get permutations of inputs for forecasted_route feature
function instantiate_forecasted_route_cond(){
    $output_matrix = array();

    //this array represents 5 successive failed api calls. it should stop controller
    $invalid_arr = array(false,false,false,false,false);
    //this array represents 2 failed api calls, then a success call. It should allow script to proceed.
    $valid_arr = array(false,false,true);

    //these are all permutations of execution conditions
    $success_element = new success_elem(0,0,0,0,0,"invalid input");
    array_push($output_matrix,$success_element);
    
    $success_element = new success_elem(true,$invalid_arr,0,0,0,"invalid zip code api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$invalid_arr,0,0,"invalid weather api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$valid_arr,0,$invalid_arr, "invalid route api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$valid_arr,0,$valid_arr,"success");
    array_push($output_matrix,$success_element);

    return $output_matrix;
}

//get permutations of inputs for heatmap feature
function instantiate_heatmap_cond(){
    $output_matrix = array();

    //this array represents 5 successive failed api calls. it should stop controller
    $invalid_arr = array(false,false,false,false,false);
    //this array represents 2 failed api calls, then a success call. It should allow script to proceed.
    $valid_arr = array(false,false,true);

    //these are all permutations of execution conditions
    $success_element = new success_elem(0,0,0,0,0,"invalid input");
    array_push($output_matrix,$success_element);
    
    $success_element = new success_elem(true,0,0,$invalid_arr,0,"invalid location api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,0,0,$valid_arr,0,"success");
    array_push($output_matrix,$success_element);

    return $output_matrix;
}

//get permutations of inputs for forecasted_heatmap feature
function instantiate_forecasted_heatmap_cond(){
    $output_matrix = array();

    //this array represents 5 successive failed api calls. it should stop controller
    $invalid_arr = array(false,false,false,false,false);
    //this array represents 2 failed api calls, then a success call. It should allow script to proceed.
    $valid_arr = array(false,false,true);

    //these are all permutations of execution conditions
    $success_element = new success_elem(false,0,0,0,0,"invalid inputs");
    array_push($output_matrix,$success_element);
    
    $success_element = new success_elem(true,$invalid_arr,0,0,0,"invalid zip code api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$invalid_arr,0,0,"invalid weather api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$valid_arr,$invalid_arr,0,"invalid location api");
    array_push($output_matrix,$success_element);

    $success_element = new success_elem(true,$valid_arr,$valid_arr,$valid_arr,0,"success");
    array_push($output_matrix,$success_element);

    return $output_matrix;
}
?>