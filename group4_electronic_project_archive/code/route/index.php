
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

<html lang="en">
<!--written by: Mhammed Alhayek, Sean Olejar, Lauren Williams, Shubhra Paradkar
debugged by: Mhammed Alhayek, Sean Olejar, Lauren Williams, Shubhra Paradkar
tested by: Sean Olejar, Mhammed Alhayek -->
  <head>
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
      }

      //defaults for alternativeroutes, and number of routes
      var alt = false;
      var num_routes = 0;

      if (submitted_yet){
        var num_routes = <?= $num_of_routes; ?>;
        var alt = <?= $alternatives; ?>;
        var primSteps = JSON.parse('<?= $stepsJSON[0]; ?>');
        var primEndIndex = '<?= $count[0]; ?>' - 1;

        if (alt && (num_routes == 2)){
          var altSteps = JSON.parse('<?= $stepsJSON[1]; ?>');
          var altEndIndex = '<?= $count[1]; ?>' - 1;
        }
      }
    </script>
    
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="../img/favicon.png">
    <title>Route</title>
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
      // for hiding/showing for elements based on forecast/noforecast buttons
      var formSubmitted = <?= json_encode(isset($_POST["submitBtn"])); ?>;
      var which_feature = <?= json_encode($which_feature); ?>;
      $(document).ready(function(){
        //hide certain buttons if user has not yet hit submit button
        if(!formSubmitted){

          $("#dayofweek").hide();
          $("#dynamicDate").hide();
          $("#weatherDropdown").hide();
          $("#timeofday").hide();
          $("#gasbuttons").hide();

        }else if(which_feature == "route"){
          //show buttons if user has submitted info

          $("#timeofday").show();
          $("#dayofweek").show();
          $("#weatherDropdown").show();
          $("#dynamicDate").hide();
          $("#optAllT").show();
          $("#gasbuttons").show();
          var element = document.getElementById("how_weather");
          element.value = "route";
          document.getElementById("date").removeAttribute("required");
          document.getElementById("day").setAttribute("required", "");
          document.getElementById("weather").setAttribute("required", "");

        }else if (which_feature == "forecasted_route"){
          //show buttons if user has submitted info

          $("#timeofday").show();
          $("#dayofweek").hide();
          $("#weatherDropdown").hide();
          $("#dynamicDate").show();
          $("#optAllT").hide();
          $("#gasbuttons").show();
          var element = document.getElementById("how_weather");
          element.value = "forecasted_route";
          document.getElementById("day").removeAttribute("required");
          document.getElementById("weather").removeAttribute("required");
          document.getElementById("date").setAttribute("required", "");

        }

        //change forms dynamically when 'forecast weather' selected
        $("#forecast").click(function(){
          $("#weather_print").show();
          $("#timeofday").show();
          $("#dayofweek").hide();
          $("#weatherDropdown").hide();
          $("#dynamicDate").show();
          $("#optAllT").hide();
          $("#gasbuttons").show();
          //$("submitBtn").show();
          var element = document.getElementById("how_weather");
          element.value = "forecasted_route";
          document.getElementById("day").removeAttribute("required");
          document.getElementById("weather").removeAttribute("required");
          document.getElementById("date").setAttribute("required", "");
          });

        //change forms dynamically when 'no forecast weather' selected
        $("#noforecast").click(function(){
          $("#weather_print").hide();
          $("#timeofday").show();
          $("#dayofweek").show();
          $("#weatherDropdown").show();
          $("#dynamicDate").hide();
          $("#optAllT").show();
          $("#gasbuttons").show();
          //$("submitBtn").show();
          var element = document.getElementById("how_weather");
          element.value = "route";
          document.getElementById("date").removeAttribute("required");
          document.getElementById("day").setAttribute("required", "");
          document.getElementById("weather").setAttribute("required", "");
          });
      });
  
      //for displaying a tooltip on mpg
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip(); 
      });

      // for hiding/showing for elements based on gas/nogas buttons
      var show_gas = <?= json_encode($show_gas); ?>;
      $(document).ready(function(){
        if(!formSubmitted){
          $("#enter_mpg").hide();
          $("#enter_fuel_type").hide();
          $("#gas_widget").hide();
          $("#nogas").hide();   // remove the button that says no
          //$("#submitBtn").hide();
          var element = document.getElementById("show_gas");
          element.value = "false";
          document.getElementById("mpg").removeAttribute("required");
          document.getElementById("fuel_type").removeAttribute("required");
        } else if(show_gas == "true"){
          $("#enter_mpg").show();
          $("#enter_fuel_type").show();
          $("#gas_widget").show();
          $("#nogas").show(); // add the button that says no
          $("#gas").hide();   // remove the button that says yes
          var element = document.getElementById("show_gas");
          element.value = "true";
          document.getElementById("mpg").setAttribute("required", "");
          document.getElementById("fuel_type").setAttribute("required", "");          
        } else if (show_gas == "false"){
          $("#enter_mpg").hide();
          $("#enter_fuel_type").hide();          
          $("#gas_widget").hide();
          $("#gas").show(); // add the button that says yes
          $("#nogas").hide();   // remove the button that says no
          var element = document.getElementById("show_gas");
          element.value = "false";
          document.getElementById("mpg").removeAttribute("required");
          document.getElementById("fuel_type").removeAttribute("required");
        }
        $("#gas").click(function(){
          $("#enter_mpg").show();
          $("#enter_fuel_type").show();          
          $("#gas_widget").show();
          $("#nogas").show(); // add the button that says no
          $("#gas").hide();   // remove the button that says yes
          //$("#submitBtn").show();
          var element = document.getElementById("show_gas");
          element.value = "true";
          document.getElementById("mpg").setAttribute("required", "");
          document.getElementById("fuel_type").setAttribute("required", "");
          });
        $("#nogas").click(function(){
          $("#enter_mpg").hide();
          $("#enter_fuel_type").hide();
          $("#gas_widget").hide();
          $("#gas").show(); // add the button that says yes
          $("#nogas").hide();   // remove the button that says no
          //$("#submitBtn").show();
          var element = document.getElementById("show_gas");
          element.value = "false";
          document.getElementById("mpg").removeAttribute("required");
          document.getElementById("fuel_type").removeAttribute("required");          
          });


          //for hiding/showing the prim/alt buttons for routes
          if(alt){
            $("#primRoute").show();
            $("#altRoute").show();
          }
          else{
            $("#primRoute").hide();
            $("#altRoute").hide();
          }

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
            <a href="../route" class="logo">Route<span class="lite"></span></a>
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
                  <li class="sub-menu">
                      <a href="../heatmap" class="">
                          <i class="icon_document_alt"></i>
                          <span>HeatMap</span>
                      </a>
                  </li>       
                  <li class="active">
                      <a href="../route" class="">
                          <i class="icon_desktop"></i>
                          <span>Route</span>
                      </a>
                  </li>
                  <li>  
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
                        </div>
                    </div>

                    <div>
                      <button type="button" id = "primRoute" class="btn btn-primary" onclick="switchRoutes(this.id)">Primary</button>
                            <button type="button" id = "altRoute" class="btn btn-info" onclick="switchRoutes(this.id)">Alternate</button>
                            <h4 id = "weather_print"><?php if(isset($_POST["submitBtn"]) && ($which_feature == "forecasted_route")){echo "Weather Forecast for ".$fullDate." at ".date("g:i a", strtotime("".$fullHour.":00")).": ".$weatherF."</br>";}?></h4>
                            <h4><?php if($show_gas=="true"){echo "Estimated cost for primary route is $".$route_cost[$primIndex]."</br>";}?></h4>
                            <h4><?php if(($show_gas == "true") && json_decode($alternatives)){echo "Estimated cost for alternate route is $".$route_cost[$altIndex]."</br>";}?></h4>
                            <div id = "gas_widget" >
                                <iframe frameborder='0' width='255' height='405' src='http://www.fueleconomy.gov/feg/widgets/find-a-car/facwidget.html'></iframe>
                            </div>
                    </div>

                </div>

                <!-- Start input form for route -->
                 <div class="col-lg-6 col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="pull-left">Route Form</div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                    <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                  <div class="padd">
                      <div class="form quick-post">

                        <!-- bootstrap input form -->
                      <form id="frm1" action="" method="post" class="form-horizontal">

                      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            

                          <!-- Create starting address field -->
                          <div class="form-group">
                            <label class="control-label col-lg-2" for="title">Starting Address</label>
                            <div class="col-lg-10"> 
                              <input type="text" id = "loc1" name="loc1" class="form-control" value = '<? echo $loc1 ?>' required>
                            </div>
                          </div> 

                          <!-- Create ending address field -->
                           <div class="form-group">
                            <label class="control-label col-lg-2" for="title">Ending Address</label>
                            <div class="col-lg-10"> 
                              <input type="text" id = "loc2" name="loc2" class="form-control" value = '<? echo $loc2 ?>' required>
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

                            <!-- Create Alternative Route Check box inputs -->
                            <div class="form-group">
                            <label class="control-label col-lg-2">Alternative Routes</label>
                            <div class="form-check form-check-inline col-lg-10">
                                    <label class="form-check-label" name="inlineCheckbox">
                                        <input class="form-check-input" name="alternative" type="checkbox" value="true" <?php if(json_decode($alternatives)) echo "checked='checked'"; ?>>
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
                            <div class="form-group" id = "gasbuttons" >
                              <label class="control-label col-lg-9" >Would you like to see an estimated gas cost for your route?</label>
                              <div class="col-lg-offset-4 col-lg-9">
                                      <button  type = "button" id = "gas" class="btn btn-primary" >Yes</button>
                                      <button type = "button" id = "nogas" class="btn btn-primary" >No</button>
                                      <input type="hidden" name="show_gas" id="show_gas">
                              </div>
                            </div>
                          <!-- Create input gas mpg field -->
                          <div class="form-group" id="enter_mpg">
                            <label class="control-label col-lg-2" for="title">Vehicle Combined MPG?</label>
                            <div class="col-lg-8" data-toggle="tooltip" title="Don't know? Use the Widget!" > 
                              <input type="range" id = "mpg" name="mpg" class="form-control" min = "10" max = "50" value = '<? echo $vehicle_mpg ?>' oninput="mpgOutput.value = mpg.value">
                              <output name="mpgOutput" id="mpgOutput"></output>
                            </div>
                          </div> 
                          <!-- Create input gas type field -->
                          <div class="form-group" id="enter_fuel_type">
                            <label class="control-label col-lg-2" for="title">Vehicle Fuel Type?</label>
                            <div class="col-lg-10" > 
                              <select class="form-control" id = "fuel_type" name = "fuel_type" >
                                <option value="">- Choose Fuel Type - </option>
                                <option value = "regular" <?php if ($fuel_type=="regular") {echo "selected='selected'"; } ?> >Regular</option>
                                <option value = "midgrade" <?php if ($fuel_type=="midgrade") {echo "selected='selected'"; } ?> >Midgrade</option>
                                <option value = "premium" <?php if ($fuel_type=="premium") {echo "selected='selected'"; } ?> >Premium</option>
                                <option value = "diesel" <?php if ($fuel_type=="diesel") {echo "selected='selected'"; } ?> >Diesel</option>
                              </select>
                            </div>
                          </div> 

                        <!-- Create "View Route" Button -->
                          <div class="form-group">
                             <div class="col-lg-offset-4 col-lg-9">
                                <button type="submit" value = "route" name = "submitBtn" id = "submitBtn" class="btn btn-primary">View Route</button>
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
              <div class = "col" style="border: 1px solid #ccc">
                      <!--Div that will hold the bar chart-->
                      <div id="prim_chart_div"></div>
              </div>
              <div class = "col-6" style="border: 1px solid #ccc">
                      <!--Div that will hold the primary route chart-->
                       <div id="alt_chart_div"></div>
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

    <!--The code that prints the traffic severities on the map-->
    <script src ="./js/screen_display.js"></script>
    
    <!--The code that lets the info be reupdated-->
    <script src="../js/info_entry.js"></script>
    
    <!-- Call the Google API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
    async defer></script>

    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!--Print the graphs-->
    <script src="./js/graph_print.js"></script>

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
