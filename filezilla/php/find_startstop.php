<?php

    $traff_host_name = "db667824699.db.1and1.com";
    $traff_database = "db667824699";
    $traff_user_name = "dbo667824699";

    $freq_host_name  = "db670831916.db.1and1.com";
    $freq_database   = "db670831916";
    $freq_user_name  = "dbo670831916";

    $password   = "briansbutt";

    $freq_con = mysqli_connect($freq_host_name, $freq_user_name, $password, $freq_database);
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }   

    $traff_con = mysqli_connect($traff_host_name, $traff_user_name, $password, $traff_database);
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    //$road_name gonna be declared somewhere
    
    //this is run for every road
    $dist = 5;
    $query_start = "SELECT * FROM grid as g WHERE g.lat_north>='$start_lat' AND g.lat_south<='$start_lat' AND g.lng_west <= '$start_lng' AND g.lng_east >= '$start_lng'";
    $res_start = mysqli_query($freq_con,$query_start);

    $query_end = "SELECT * FROM grid as g WHERE g.lat_north>='$start_lat' AND g.lat_south<='$start_lat' AND g.lng_west <= '$start_lng' AND g.lng_east >= '$start_lng'";
    $res_end = mysqli_query($freq_con,$query_end);

    if (mysqli_num_rows($res_start)>0 && mysqli_num_rows($res_end)>0 ){ //this check is just to be safe, but there shouldn't be invalid lat/lng at this point
     // for the current severity
        $row_start = $res_start->fetch_assoc();
        $row_end = $res_end->fetch_assoc();
        $grid_id_start = $row_start["gridId"];
        

        $query_grid_start = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
        $res_grid_start = mysqli_query($traff_con,$query_grid_start)->fetch_assoc();

        //$pt_count_start = $row_grid_start["pt_count"];

        if(mysqli_num_rows($res_grid_start)==0){
            
            if($grid_id_start == $grid_id_end){
                if($start_lng<$end_lng){
                    $query_update =  "INSERT INTO bounds (start_lat,start_lng,end_lat,end_lng,pt_count,road_name,grid_id) VALUES ('$start_lat', '$start_lng', '$end_lat', '$end_lng',2,'$road_name','$grid_id_start') ";
                    mysqli_query($traff_con,$query_update);

                }else{
                    $query_update =  "INSERT INTO bounds (start_lat,start_lng,end_lat,end_lng,pt_count,road_name,grid_id) VALUES ('$end_lat', '$end_lng','$start_lat', '$start_lng', 2,'$road_name','$grid_id_start')";
                    mysqli_query($traff_con,$query_update);
                    //put the start and stop, grid id, road name
                }
            }else{
                $query_update =  "INSERT INTO bounds (start_lat,start_lng,pt_count,road_name,grid_id) VALUES ('$start_lat', '$start_lng',1, '$road_name', '$grid_id_start')";
                mysqli_query($traff_con,$query_update);
            }
            
        }else{
            $row_grid_start = $res_grid_start->fetch_assoc();
            $pt_count_start = $row_grid_start["pt_count"];

            if($pt_count_start == 1){
                if($row_grid_start["start_lng"]<$start_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$start_lat', b.end_lng = '$start_lng',b.pt_count = 2 WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }else if($row_grid_start["start_lng"]>$start_lng){
                    $temp_end_lat = $row_grid_start["start_lat"];
                    $temp_end_lng = $row_grid_start["start_lng"];

                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$start_lat', b.start_lng = '$start_lng', b.end_lat = '$temp_end_lat', b.end_lng = '$temp_end_lng', b.pt_count = 2 WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }

            }/*else{
                if($row_grid_start["start_lng"]>$start_lng){
                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$start_lat', b.start_lng = '$start_lng' WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }else if($row_grid_start["end_lng"]<$start_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$start_lat', b.end_lng = '$start_lng' WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }

            }*/ //this is gonna come back later. for now, this is too rudimentary to iteratively update
        }


        $grid_id_end = $row_end["gridId"];

        $query_grid_end = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
        $res_grid_end = mysqli_query($traff_con,$query_grid_start);
        
        

        if(mysqli_num_rows($res_grid_end)==0){
            
            $query_update =  "INSERT INTO bounds (start_lat,start_lng,pt_count,road_name,grid_id) VALUES  ('$end_lat', '$end_lng',1,'$road_name', '$grid_id_end')";
            mysqli_query($traff_con,$query_update);
            
            
        }else{
            
            $row_grid_end = $res_grid_end->fetch_assoc();
            $pt_count_end = $row_grid_end["pt_count"];

            if($pt_count_end == 1){
                if($row_grid_end["start_lng"]<$end_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$end_lat', b.end_lng = '$end_lng',b.pt_count = 2 WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }else if($row_grid_end["start_lng"]>$end_lng){
                    $temp_end_lat = $row_grid_end["start_lat"];
                    $temp_end_lng = $row_grid_end["start_lng"];

                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$end_lat', b.start_lng = '$end_lng', b.end_lat = '$temp_end_lat', b.end_lng = '$temp_end_lng', b.pt_count = 2 WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }

            }/*else{
            
                if($row_grid_end["start_lng"]>$end_lng){
                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$end_lat', b.start_lng = '$end_lng' WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }else if($row_grid_end["end_lng"]<$end_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$end_lat', b.end_lng = '$end_lng' WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }
            }*/ //this will be updated later. for now this is too rudimentary.
        }           
    }
?>