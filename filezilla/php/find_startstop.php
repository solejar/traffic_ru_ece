<?php
    /*
    needs connection to DB already as traff_con
    needs these vars already
    $road_name = "brian has a foot fetish";
    $grid_id_start = -40;
    $grid_id_end = -69;
    
    $start_lat = 40;
    $start_lng = -30;

    $end_lat = 41;
    $end_lng = -31;*/
    //this is run for every incident as they come in
    

    $query_start = "SELECT * FROM grid as g WHERE g.latN>='$start_lat' AND g.latS<='$start_lat' AND g.longW <= '$start_lng' AND g.longE >= '$start_lng'";
    $res_start = mysqli_query($traff_con,$query_start);

    $query_end = "SELECT * FROM grid as g WHERE g.latN>='$end_lat' AND g.latS<='$end_lat' AND g.longW <= '$end_lng' AND g.longE >= '$end_lng'";
    $res_end = mysqli_query($traff_con,$query_end);

    if (mysqli_num_rows($res_start)>0 && mysqli_num_rows($res_end)>0 ){ //this check is just to be safe, but there shouldn't be invalid lat/lng at this point
     // for the current severity
        
        $row_start = $res_start->fetch_assoc();
        $row_end = $res_end->fetch_assoc();
        $grid_id_start = $row_start["id"];
        

        $query_grid_start = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
        $res_grid_start = mysqli_query($traff_con,$query_grid_start);

        $pt_count_start = $row_grid_start["pt_count"];

        if(mysqli_num_rows($res_grid_start)==0){
            //echo "no entry yet for start<br>";

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
            //echo "entry exists for start<br>";
            $row_grid_start = $res_grid_start->fetch_assoc();
            $pt_count_start = $row_grid_start["pt_count"];

            if($pt_count_start == 1){
            
                echo "count = $pt_count_start <br>";
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


        $grid_id_end = $row_end["id"];

        $query_grid_end = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
        $res_grid_end = mysqli_query($traff_con,$query_grid_end);
        
        

        if(mysqli_num_rows($res_grid_end)==0){
            
            //echo "no entry for end <br>"; 
            $query_update =  "INSERT INTO bounds (start_lat,start_lng,pt_count,road_name,grid_id) VALUES  ('$end_lat', '$end_lng',1,'$road_name', '$grid_id_end')";
            mysqli_query($traff_con,$query_update);
            
            
        }else{
            //echo "entry for end <br>"; 
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
    //echo "ostensibly finished";           
    }
?>