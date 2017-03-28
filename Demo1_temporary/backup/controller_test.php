<?php
    $traff_host_name = "db667824699.db.1and1.com";
    $traff_database = "db667824699";
    $traff_user_name = "dbo667824699";

    $password   = "briansbutt";
    
    $traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }*/
     /*this is dummy vars for testing */
    $road_name = "brian has a foot fetish";
    $grid_id_start = -40;
    $grid_id_end = -69;
    
    $start_lat = 40;
    $start_lng = -30;

    $end_lat = 41;
    $end_lng = -31;

    include('find_startstop.php');
?>