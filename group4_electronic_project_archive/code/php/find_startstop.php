<?php
//written by: Sean Olejar
//Tested by: Mhammed Alhayek, Brian Monticello, Sean Olejar
//debugged by: Mhammed Alhayek, Brian Monticello, Sean Olejar

    //this is run for every road    
    
    //get the grid for the starting incident
    $query_start = "SELECT * FROM grid as g WHERE g.latN>='$start_lat' AND g.latS<='$start_lat' AND g.longW <= '$start_lng' AND g.longE >= '$start_lng'";
    $res_start = mysqli_query($traff_con,$query_start);

    //get the grid for the ending incident
    $query_end = "SELECT * FROM grid as g WHERE g.latN>='$end_lat' AND g.latS<='$end_lat' AND g.longW <= '$end_lng' AND g.longE >= '$end_lng'";
    $res_end = mysqli_query($traff_con,$query_end);

    if (mysqli_num_rows($res_start)>0 && mysqli_num_rows($res_end)>0 ){ //this check is just to be safe, but there shouldn't be invalid lat/lng at this point
    
        //get the start, end grids
        $row_start = $res_start->fetch_assoc();
        $row_end = $res_end->fetch_assoc();
        $grid_id_start = $row_start["id"];
        
        //see how many road points are known on this grid box.
        $query_grid_start = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
        $res_grid_start = mysqli_query($traff_con,$query_grid_start);

        $pt_count_start = $row_grid_start["pt_count"];

        //if no knowledge yet
        if(mysqli_num_rows($res_grid_start)==0){

            //if incident is contained in 1 box
            if($grid_id_start == $grid_id_end){

                //put the start, stop of incident in as the start&end of the road
                if($start_lng<$end_lng){
                    $query_update =  "INSERT INTO bounds (start_lat,start_lng,end_lat,end_lng,pt_count,road_name,grid_id) VALUES ('$start_lat', '$start_lng', '$end_lat', '$end_lng',2,'$road_name','$grid_id_start') ";
                    mysqli_query($traff_con,$query_update);

                //put the start, stop of incident in as the end&start of the road
                }else{
                    $query_update =  "INSERT INTO bounds (start_lat,start_lng,end_lat,end_lng,pt_count,road_name,grid_id) VALUES ('$end_lat', '$end_lng','$start_lat', '$start_lng', 2,'$road_name','$grid_id_start')";
                    mysqli_query($traff_con,$query_update);
                    //put the start and stop, grid id, road name
                }
            }else{
            //if start/stop in diff grids, just put the start into this grid.
                $query_update =  "INSERT INTO bounds (start_lat,start_lng,pt_count,road_name,grid_id) VALUES ('$start_lat', '$start_lng',1, '$road_name', '$grid_id_start')";
                mysqli_query($traff_con,$query_update);
            }
            
        }else{
            $row_grid_start = $res_grid_start->fetch_assoc();
            $pt_count_start = $row_grid_start["pt_count"];

            //if currently start of road in grid is known, but not end of grid
            if($pt_count_start == 1){

                $is_same_lat= $row_grid_start["start_lat"]==$start_lat+0.0001||$row_grid_start["start_lat"]==$start_lat-0.0001;
                $is_same_lng = $row_grid_start["start_lng"]==$start_lng+0.0001||$row_grid_start["start_lng"]==$start_lng-0.0001;
                
                //make sure that the same incident doesn't get counted twice.
                if($is_same_lat&&$is_same_lng){
            
                }
                //if start of road is more west than start of incident, make incident start the end of road
                else if($row_grid_start["start_lng"]<$start_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$start_lat', b.end_lng = '$start_lng',b.pt_count = 2 WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }//if start of road is more eastward than start of incident, make incident start the start of road.
                else if($row_grid_start["start_lng"]>$start_lng){
                    $temp_end_lat = $row_grid_start["start_lat"];
                    $temp_end_lng = $row_grid_start["start_lng"];

                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$start_lat', b.start_lng = '$start_lng', b.end_lat = '$temp_end_lat', b.end_lng = '$temp_end_lng', b.pt_count = 2 WHERE b.grid_id = '$grid_id_start' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }

            }/*else{
                //this is where bounds will be updated going forward
            }*/ 
        }

        //now let's do the same for the end of incident
        $grid_id_end = $row_end["id"];

        $query_grid_end = "SELECT * FROM bounds as b WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
        $res_grid_end = mysqli_query($traff_con,$query_grid_end);
                

        //if no knowledge of road on grid, add end of incident as start of road.
        if(mysqli_num_rows($res_grid_end)==0){
            
             
            $query_update =  "INSERT INTO bounds (start_lat,start_lng,pt_count,road_name,grid_id) VALUES  ('$end_lat', '$end_lng',1,'$road_name', '$grid_id_end')";
            mysqli_query($traff_con,$query_update);
            
            
        }else{
 
            $row_grid_end = $res_grid_end->fetch_assoc();
            $pt_count_end = $row_grid_end["pt_count"];

            //if only knowledge of start
            if($pt_count_end == 1){

                $is_same_lat= $row_grid_end["start_lat"]==$end_lat+0.0001||$row_grid_end["start_lat"]==$end_lat-0.0001;
                $is_same_lng = $row_grid_end["start_lng"]==$end_lng+0.0001||$row_grid_end["start_lng"]==$end_lng-0.0001;
                //make sure same incident doen'st get updated twice.
                if($is_same_lat&&$is_same_lng){
            
                }
                //if end is more eastward than start, make the end of the road.
                else if($row_grid_end["start_lng"]<$end_lng){
                    $query_update =  "UPDATE bounds as b SET b.end_lat = '$end_lat', b.end_lng = '$end_lng',b.pt_count = 2 WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }//if end is more westward than start, make it the start of the road.
                else if($row_grid_end["start_lng"]>$end_lng){
                    
                    $temp_end_lat = $row_grid_end["start_lat"];
                    $temp_end_lng = $row_grid_end["start_lng"];

                    $query_update =  "UPDATE bounds as b SET b.start_lat = '$end_lat', b.start_lng = '$end_lng', b.end_lat = '$temp_end_lat', b.end_lng = '$temp_end_lng', b.pt_count = 2 WHERE b.grid_id = '$grid_id_end' AND b.road_name = '$road_name'";
                    mysqli_query($traff_con,$query_update);
                }

            }/*else{
            //this will be updated later. this is where boudns will be updated.            
            }*/ 
        }           
    }
?>