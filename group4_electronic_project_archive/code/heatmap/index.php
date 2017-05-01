<?php 
  include '../php/controller.php';
  $today = date('F j, Y');
  $tomorrow = date('F j, Y', strtotime('+'.'1'.' day'));
  $day2 = date('F j, Y', strtotime('+'.'2'.' day'));
  $day3 = date('F j, Y', strtotime('+'.'3'.' day'));
  $day4 = date('F j, Y', strtotime('+'.'4'.' day'));
  $day5 = date('F j, Y', strtotime('+'.'5'.' day'));
  $day6 = date('F j, Y', strtotime('+'.'6'.' day'));
  $day7 = date('F j, Y', strtotime('+'.'7'.' day'));
  $day8 = date('F j, Y', strtotime('+'.'8'.' day'));
  $day9 = date('F j, Y', strtotime('+'.'9'.' day'));
?>

<!DOCTYPE html>

<!--written by: Mhammed Alhayek, Sean Olejar, Lauren Williams, Shubhra Paradkar
debugged by: Mhammed Alhayek, Sean Olejar, Lauren Williams, Shubhra Paradkar
tested by: Sean Olejar, Mhammed Alhayek -->

<html lang="en">

  <head>
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="../img/favicon.png">
    <title>HeatMap</title>
    <!-- Bootstrap CSS -->    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="css/elegant-icons-style.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />    
    <!-- full calendar css-->
    <link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
    <link href="assets/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <!-- easy pie chart-->
    <link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="css/owl.carousel.css" type="text/css">
    <link href="css/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="css/fullcalendar.css">
    <link href="css/widgets.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link href="css/xcharts.min.css" rel=" stylesheet"> 
    <link href="css/jquery-ui-1.10.4.min.css" rel="stylesheet">
    <!-- =======================================================

        Theme Name: NiceAdmin

        Theme URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/

        Author: BootstrapMade

        Author URL: https://bootstrapmade.com

    ======================================================= -->
    <script>
    //these variables are necessary for the included javascript to work

      //bool that tells system if user has hit 'submitbutton'
      var submitted_yet;
      if (!<?= $defaultMap; ?>){
        submitted_yet = true;
      }else{
        submitted_yet = false;
      }

      //let's get input params, if submitbutton has been hit
      if (submitted_yet){
         severity = [
          <?php echo json_encode($severity[0]); ?>,
          <?php echo json_encode($severity[1]); ?>,
          <?php echo json_encode($severity[2]); ?>,
          <?php echo json_encode($severity[3]); ?>,
        ];

        //make associative array of conditions_params
        conditions = {
              "weather"  : "<?php echo $weatherF ?>",
              "severity" : severity,
              "time"     : <?php echo $hourF?>,
              "day"      : "<?php echo $dayF?>",
              "which_feature": "<?php echo $which_feature?>",
        };

        var cent_lat = JSON.parse('<?= json_encode($loc_params["cent_lat"]); ?>');
        var cent_lng = JSON.parse('<?= json_encode($loc_params["cent_lng"]); ?>');
      }else{
        //default conditions, if submitbutton has not been hit
         severity = [
          true,
          true,
          true,
          true,
        ];

         conditions = {
              "weather"  : 0, //want to echo this cause it might be forecasted
              "severity" : severity,
              "time"     : 2,
              "day"      : 5,
        };

	//default cent_lat and cent_lng, just in case there's an error parsing the PHP
        var cent_lat = 30;
        var cent_lng = 70;
      }

      if (submitted_yet){ 
	//collect the steps of the journey 
        var steps = JSON.parse('<?= $stepsJSON; ?>');
        var endIndex = '<?= $count; ?>' - 1;
      }

    </script>
    <script>
      // for hiding/showing for elements based on forecast/noforecast buttons
      var formSubmitted = <?= json_encode(isset($_POST["submitBtn"])); ?>;
      var which_feature = <?= json_encode($which_feature); ?>;
      $(document).ready(function(){
        if(!formSubmitted){
          $("#dayofweek").hide();
          $("#dynamicDate").hide();
          $("#weatherDropdown").hide();
          $("#timeofday").hide();

        } else if(which_feature == "heatmap"){
          $("#timeofday").show();
          $("#dayofweek").show();
          $("#weatherDropdown").show();
          $("#dynamicDate").hide();
          $("#optAllT").show();

          var element = document.getElementById("how_weather");
          element.value = "heatmap";
          document.getElementById("date").removeAttribute("required");
          document.getElementById("day").setAttribute("required", "");
          document.getElementById("weather").setAttribute("required", "");
        } else if (which_feature == "forecasted_heatmap"){
          $("#timeofday").show();
          $("#dayofweek").hide();
          $("#weatherDropdown").hide();
          $("#dynamicDate").show();
          $("#optAllT").hide();

          var element = document.getElementById("how_weather");
          element.value = "forecasted_heatmap";
          document.getElementById("day").removeAttribute("required");
          document.getElementById("weather").removeAttribute("required");
          document.getElementById("date").setAttribute("required", "");
        }
        $("#forecast").click(function(){
          $("#timeofday").show();
          $("#dayofweek").hide();
          $("#weatherDropdown").hide();
          $("#dynamicDate").show();
          $("#optAllT").hide();

          var element = document.getElementById("how_weather");
          element.value = "forecasted_heatmap";
          document.getElementById("day").removeAttribute("required");
          document.getElementById("weather").removeAttribute("required");
          document.getElementById("date").setAttribute("required", "");
          });
        $("#noforecast").click(function(){
          $("#timeofday").show();
          $("#dayofweek").show();
          $("#weatherDropdown").show();
          $("#dynamicDate").hide();
          $("#optAllT").show();
          $("#gasbuttons").show();
          var element = document.getElementById("how_weather");
          element.value = "heatmap";
          document.getElementById("date").removeAttribute("required");
          document.getElementById("day").setAttribute("required", "");
          document.getElementById("weather").setAttribute("required", "");
          });
      });
  
                
      
         
    </script>

    


    <style>
      /* This styling is for the google map */

      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map-canvas {
        height: 100%;
        width: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>

  </head>
  <body>
  <!-- container section start -->
  <section id="container" class="">
      <!-- Color of header -->
      <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
            </div>
            <!--logo start Route-->
            <a href="../heatmap" class="logo">Heat<span class="lite">Map</span></a>
            <!--logo end-->
      </header>      
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">                
                  <li class="sub-menu">
                      <a class="" href="../index.html">
                          <i class="icon_house_alt"></i>
                          <span>Home</span>
                      </a>
                  </li>
                  <li class="active">
                      <a href="../heatmap" class="">
                          <i class="icon_document_alt"></i>
                          <span>HeatMap</span>
                      </a>
                  </li>       
                  <li class="sub-menu">
                      <a href="../route" class="">
                          <i class="icon_desktop"></i>
                          <span>Route</span>
                      </a>
                  </li> 
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">            
           <div class="row">
           <!--Create Map to Display Route-->
            <div class="col-lg-6 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2><i class="fa fa-map-marker red"></i><strong>Map</strong></h2>
                            <div class="panel-actions">
                                <a href="index.php#" class="btn-setting"><i class="fa fa-rotate-right"></i></a>
                                <a href="index.php#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                                <a href="index.php#" class="btn-close"><i class="fa fa-times"></i></a>
                            </div>  
                        </div>
                        <div class="panel-body-map">
                            <div id="map-canvas" ></div>
                            <h4><?php if(isset($_POST["submitBtn"]) && ($which_feature == "forecasted_route" || $which_feature == "forecasted_heatmap")){echo "Weather Forecast for ".$fullDate." at ".date("g:i a", strtotime("".$fullHour.":00")).": ".$weatherF;}?></h4>  
            
                        </div>
                    </div>


                </div>

                <!-- Start input form for route -->
                 <div class="col-lg-6 col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="pull-left">Heat Map Form</div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                    <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                  <div class="padd">
                      <div class="form quick-post">

                      <form id="frm1" action="" method="post" class="form-horizontal">

                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            

                          <!-- Create zip code field -->
                          <div class="form-group">
                            <label class="control-label col-lg-2" for="title">Zipcode</label>
                            <div class="col-lg-10"> 
                              <input type="text" id = "loc1" name="loc1" class="form-control" value = '<? echo $loc1 ?>' required>
                            </div>
                          </div> 
                                                        
                         
                            <!-- Create Severity Check box inputs -->
                            <div class="form-group">
                            <label class="control-label col-lg-2">Severity</label>
                            <div class="form-check form-check-inline col-lg-10">
                                    <label class="form-check-label" name="inlineCheckbox">
                                        <input class="form-check-input" id = "sev1" name="check_list[]" type="checkbox"value="1"  <?php if($severity[0]) echo "checked='checked'"; ?>> 1
                                        <input class="form-check-input" id = "sev2" name="check_list[]" type="checkbox"value="2" <?php if($severity[1]) echo "checked='checked'"; ?>> 2
                                        <input class="form-check-input" id = "sev3" name="check_list[]" type="checkbox"value="3" <?php if($severity[2]) echo "checked='checked'"; ?>> 3
                                        <input class="form-check-input" id = "sev4" name="check_list[]" type="checkbox"value="4"  <?php if($severity[3]) echo "checked='checked'"; ?>> 4
                                    </label>
                                </div>
                            </div>

                            

                            <div class="form-group">
                              <label class="control-label col-lg-9" >Would you like the weather to be forecasted?</label>
                              <div class="col-lg-offset-4 col-lg-9">
                                      <button  type = "button" id = "forecast" class="btn btn-primary" >Forecast</button>
                                      <button type = "button" id = "noforecast" class="btn btn-primary" >No Forecast</button>
                                      <input type="hidden" name="how_weather" id="how_weather">
                              </div>
                            </div>

                            <!-- Create Time of day drop down inputs -->
                          <div class="form-group" id="timeofday">
                            <label class="control-label col-lg-2">Time of Day</label>
                            <div class="col-lg-10">                               
                                <select id ="time" class="form-control" name="time"  required>
                                  <option value="">- Choose Time -</option>
                                  <!--<option value="AllT" <?php if ($hourF=="AllT") {echo "selected='selected'"; } ?> >All Times</option>-->
                                  <option value="0" <?php if ($hourF=="0") {echo "selected='selected'"; } ?> >12:00 AM</option>
                                  <option value="1" <?php if ($hourF=="1") {echo "selected='selected'"; } ?> >1:00 AM</option>
                                  <option value="2" <?php if ($hourF=="2") {echo "selected='selected'"; } ?> >2:00 AM</option>
                                  <option value="3" <?php if ($hourF=="3") {echo "selected='selected'"; } ?> >3:00 AM</option>
                                  <option value="4" <?php if ($hourF=="4") {echo "selected='selected'"; } ?> >4:00 AM</option>
                                  <option value="5" <?php if ($hourF=="5") {echo "selected='selected'"; } ?> >5:00 AM</option>
                                  <option value="6" <?php if ($hourF=="6") {echo "selected='selected'"; } ?> >6:00 AM</option>
                                  <option value="7" <?php if ($hourF=="7") {echo "selected='selected'"; } ?> >7:00 AM</option>
                                  <option value="8" <?php if ($hourF=="8") {echo "selected='selected'"; } ?> >8:00 AM</option>
                                  <option value="9" <?php if ($hourF=="9") {echo "selected='selected'"; } ?> >9:00 AM</option>
                                  <option value="10" <?php if ($hourF=="10") {echo "selected='selected'"; } ?> >10:00 AM</option>
                                  <option value="11" <?php if ($hourF=="11") {echo "selected='selected'"; } ?> >11:00 AM</option>
                                  <option value="12" <?php if ($hourF=="12") {echo "selected='selected'"; } ?> >12:00 PM</option>
                                  <option value="13" <?php if ($hourF=="13") {echo "selected='selected'"; } ?> >1:00 PM</option>
                                  <option value="14" <?php if ($hourF=="14") {echo "selected='selected'"; } ?> >2:00 PM</option>
                                  <option value="15" <?php if ($hourF=="15") {echo "selected='selected'"; } ?> >3:00 PM</option>
                                  <option value="16" <?php if ($hourF=="16") {echo "selected='selected'"; } ?> >4:00 PM</option>
                                  <option value="17" <?php if ($hourF=="17") {echo "selected='selected'"; } ?> >5:00 PM</option>
                                  <option value="18" <?php if ($hourF=="18") {echo "selected='selected'"; } ?> >6:00 PM</option>
                                  <option value="19" <?php if ($hourF=="19") {echo "selected='selected'"; } ?> >7:00 PM</option>
                                  <option value="20" <?php if ($hourF=="20") {echo "selected='selected'"; } ?> >8:00 PM</option>
                                  <option value="21" <?php if ($hourF=="21") {echo "selected='selected'"; } ?> >9:00 PM</option>
                                  <option value="22" <?php if ($hourF=="22") {echo "selected='selected'"; } ?> >10:00 PM</option>
                                  <option value="23" <?php if ($hourF=="23") {echo "selected='selected'"; } ?> >11:00 PM</option>
                                </select>  
                            </div>
                          </div>

                          <!-- Create day of week drop down menu -->
                           <div class="form-group" id="dayofweek">
                            <label class="control-label col-lg-2">Day of Week</label>
                            <div class="col-lg-10">                               
                                <select id = "day" class="form-control" name="day" >
                                  <option value="">- Choose Day of Week -</option>
                                  <option value="Monday" <?php if ($dayF=="Monday") {echo "selected='selected'"; } ?> >Monday</option>
                                  <option value="Tuesday" <?php if ($dayF=="Tuesday") {echo "selected='selected'"; } ?> >Tuesday</option>
                                  <option value="Wednesday" <?php if ($dayF=="Wednesday") {echo "selected='selected'"; } ?> >Wednesday</option>
                                  <option value="Thursday" <?php if ($dayF=="Thursday") {echo "selected='selected'"; } ?> >Thursday</option>
                                  <option value="Friday" <?php if ($dayF=="Friday") {echo "selected='selected'"; } ?> >Friday</option>
                                  <option value="Saturday" <?php if ($dayF=="Saturday") {echo "selected='selected'"; } ?> >Saturday</option>
                                  <option value="Sunday" <?php if ($dayF=="Sunday") {echo "selected='selected'"; } ?> >Sunday</option>
                                </select>  
                            </div>
                          </div> 

                          <!-- Create weather drop down menu -->
                          <div class="form-group" id = "weatherDropdown">
                            <label class="control-label col-lg-2">Weather</label>
                            <div class="col-lg-10">                               
                                <select id = "weather" class="form-control" name="weather"  >
                                  <option value="" >- Choose Weather -</option>
                                  <option value="AllW" <?php if ($weatherF=="AllW") {echo "selected='selected'"; } ?> >Any Weather</option>
                                  <option value="Clear" <?php if ($weatherF=="Clear") {echo "selected='selected'"; } ?>>Clear</option>
                                  <option value="Snow" <?php if ($weatherF=="Snow") {echo "selected='selected'"; } ?>>Snow</option>
                                  <option value="Cloudy"<?php if ($weatherF=="Cloudy") {echo "selected='selected'"; } ?> >Cloudy</option>
                                  <option value="Rain" <?php if ($weatherF=="Rain") {echo "selected='selected'"; } ?>>Rain</option>
                                  <option value="Fog" <?php if ($weatherF=="Fog") {echo "selected='selected'"; } ?>>Fog</option>
                                </select>  
                            </div>
                          </div>

                        <div class="form-group" id="dynamicDate">
                              <label class="control-label col-lg-2">Date</label>
                              <div class="col-lg-10">                               
                                  <select class="form-control" id ="date" name="date" >
                                    <option value="">- Choose Date -</option>
                                    <option value="<?=$today;?>" <?php if ($tempDate==$today) {echo "selected='selected'"; } ?> ><?=$today;?></option>
                                    <option value="<?=$tomorrow;?>" <?php if ($tempDate==$tomorrow) {echo "selected='selected'"; } ?> ><?=$tomorrow;?></option>
                                    <option value="<?=$day2;?>" <?php if ($tempDate==$day2) {echo "selected='selected'"; } ?> ><?=$day2;?></option>
                                    <option value="<?=$day3;?>" <?php if ($tempDate==$day3) {echo "selected='selected'"; } ?> ><?=$day3;?></option>
                                    <option value="<?=$day4;?>" <?php if ($tempDate==$day4) {echo "selected='selected'"; } ?> ><?=$day4;?></option>
                                    <option value="<?=$day5;?>" <?php if ($tempDate==$day5) {echo "selected='selected'"; } ?> ><?=$day5;?></option>
                                    <option value="<?=$day6;?>" <?php if ($tempDate==$day6) {echo "selected='selected'"; } ?> ><?=$day6;?></option>
                                    <option value="<?=$day7;?>" <?php if ($tempDate==$day7) {echo "selected='selected'"; } ?> ><?=$day7;?></option>
                                    <option value="<?=$day8;?>" <?php if ($tempDate==$day8) {echo "selected='selected'"; } ?> ><?=$day8;?></option>
                                    <option value="<?=$day9;?>" <?php if ($tempDate==$day9) {echo "selected='selected'"; } ?> ><?=$day9;?></option>
                                  </select>  
                              </div>
                            </div>
                            
                            <!-- Create range input -->
                          <div class="form-group">
                            <label class="control-label col-lg-2">Range</label>
                            <div class="col-lg-10">                               
                                <select id = "loc2" class="form-control" name="loc2" required>
                                  <option value="">- Choose Range -</option>
                                  <option value="5" <?php if ($loc2=="5") {echo "selected='selected'"; } ?> >5 miles</option>
                                  <option value="10" <?php if ($loc2=="10") {echo "selected='selected'"; } ?> >10 miles</option>
                                  <option value="15" <?php if ($loc2=="15") {echo "selected='selected'"; } ?> >15 miles</option>
                                  <option value="20" <?php if ($loc2=="20") {echo "selected='selected'"; } ?> >20 miles</option>
                                </select>  
                            </div>
                          </div> 
                          

                          

                        <!-- Create "View Heatmap" Button -->
                          <div class="form-group">
                             <div class="col-lg-offset-4 col-lg-9">
                                <button type="submit" value = "heatmap" name = "submitBtn" id = "submitBtn"  class="btn btn-primary">View Heat Map</button>
                               <p id="demo"></p>
                             </div>
                          </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class = "row" >
              <div class = "col">
                      <!--Div that will hold the pie chart-->
                      <div id="prim_chart_div"></div>
              </div>
          </div>

  </section>
    <!-- javascripts -->
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui-1.10.4.min.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <!-- bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- nice scroll -->
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <!-- charts scripts -->
    <script src="assets/jquery-knob/js/jquery.knob.js"></script>
    <script src="js/jquery.sparkline.js" type="text/javascript"></script>
    <script src="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
    <script src="js/owl.carousel.js" ></script>
    <!-- jQuery full calendar -->
    <script src="js/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
    <script src="assets/fullcalendar/fullcalendar/fullcalendar.js"></script>
    <!--script for this page only-->
    <script src="js/calendar-custom.js"></script>
    <script src="js/jquery.rateit.min.js"></script>
    <!-- custom select -->
    <script src="js/jquery.customSelect.min.js" ></script>
    <script src="assets/chart-master/Chart.js"></script>
    <!--custome script for all page-->
    <script src="js/scripts.js"></script>
    <!-- custom script for this page-->
    <script src="js/sparkline-chart.js"></script>
    <script src="js/easy-pie-chart.js"></script>
    <script src="js/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="js/jquery-jvectormap-world-mill-en.js"></script>
    <script src="js/xcharts.min.js"></script>
    <script src="js/jquery.autosize.min.js"></script>
    <script src="js/jquery.placeholder.min.js"></script>
    <script src="js/gdp-data.js"></script>  
    <script src="js/morris.min.js"></script>
    <script src="js/sparklines.js"></script>    
    <script src="js/charts.js"></script>
    <script src="js/jquery.slimscroll.min.js"></script>

    <!--The code that prints the traffic severities onto the Google Map-->
    <script src = "./js/screen_display.js"></script>

    <!--The code that lets the info be reupdated-->
    <script src="../js/info_entry.js"></script>
  
    <!-- Call the Google API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
    async defer></script>

    <!--Load the AJAX API-->
    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // // Load the Visualization API and the corechart package.
      // google.charts.load('current', {'packages':['corechart', 'bar', 'line']});

      // // Set a callback to run when the Google Visualization API is loaded.
      // google.charts.setOnLoadCallback(drawChart);

   
     
      // // Callback that creates and populates a data table,
      // // instantiates the pie chart, passes in the data and
      // // draws it.
      // function drawChart() {
        
      //   if (!<?= $defaultMap; ?>){
      //     var steps = JSON.parse('<?= $stepsJSON; ?>');
      //     var endIndex = '<?= $count; ?>' - 1;
      //     var day = conditions["day"];
      //     var hour = conditions["time"];
      //     var weather = conditions["weather"];

      //     var sevArr = new Array();
      //     var i;
      //     for (i = 0; i<24; i++){
      //       total_this_hour = 0;
      //       var j;
      //       for (j = 0; j<=endIndex; j++){
      //         var sev = steps[j].severity[day][i][weather];
      //         sev = fixSev(sev);

      //         total_this_hour += sev;
      //       }
      //       sevArr[i] = total_this_hour;
      //     }

      //     // Create the data table.
      //     var prim_data = new google.visualization.DataTable();
      //     prim_data.addColumn('number', 'Hour');
      //     prim_data.addColumn('number', 'Total Traffic Severity');
          
      //     for(i=0; i<24; i++){
      //       prim_data.addRows([[i, sevArr[i]]]);
      //     }
      //     var prim_options = {
      //       chart:{
      //         title: 'Total Traffic Severity vs. Hour'
      //       },
      //       series: {
      //         0: {color: '#9966ff'},
      //       }
      //     };

      //     var prim_chart = new google.charts.Line(document.getElementById('prim_chart_div'));
      //     prim_chart.draw(prim_data, google.charts.Line.convertOptions(prim_options));
          
      //   }
      // }
      
    </script>-->

    <?php

      if(isset($_POST["submitBtn"])){
        if(strlen($errorcode)>0){
          $message = $errorcode;
          echo "<script type='text/javascript'>alert('$message');</script>";
          header('Location: index.php');
        }
      }

    ?>
    
  <script>
   //knob
      $(function() {
        $(".knob").knob({
          'draw' : function () { 
            $(this.i).val(this.cv + '%')
          }
        })
      });
      //carousel
      $(document).ready(function() {
          $("#owl-slider").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true
          });
      });
      //custom select box
      $(function(){
          $('select.styled').customSelect();
      });
      /* ---------- Map ---------- */
    $(function(){
      $('#map').vectorMap({
        map: 'world_mill_en',
        series: {
          regions: [{
            values: gdpData,
            scale: ['#000', '#000'],
            normalizeFunction: 'polynomial'
          }]
        },
        backgroundColor: '#eef3f7',
        onLabelShow: function(e, el, code){
          el.html(el.html()+' (GDP - '+gdpData[code]+')');
        }
      });
    });
  </script>

  </body>

</html>
