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

  <head>

  <!--<script src="infoEntry.js">
  </script>-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  
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

  </head>

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

  <body>
  <!-- container section start -->
  <section id="container" class="">
      <!-- Color of header -->
      <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
            </div>
            <!--logo start Heat Map-->
            <a href="../heatmap" class="logo">Heat<span class="lite">Map </span></a>
            <!--logo end-->
      </header>      
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">                
                  <li class="submenu">
                      <a class="" href="../index.php">
                          <i class="icon_house_alt"></i>
                          <span>Home</span>
                      </a>
                  </li>
                  <li class="active">
                      <a href="../heatmap" class="">
                          <i class="icon_document_alt"></i>
                          <span>HeatMap</span>
                          <!--<span class="menu-arrow arrow_carrot-right"></span>-->
                      </a>
                  </li>       
                  <li class="sub-menu">
                      <a href="../route" class="">
                          <i class="icon_desktop"></i>
                          <span>Route</span>
                          <!--<span class="menu-arrow arrow_carrot-right"></span>-->
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="#">
                          <i class="icon_genius"></i>
                          <span>Agenda</span>
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
                            <h4><?php if(isset($_POST["submit"])){echo "Weather Forecast for ".$fullDate." at ".date("g:i a", strtotime("".$fullHour.":00")).": ".$weatherF;}?></h4>  
                        </div>
                    </div>
                </div>
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
                                        <div class="form-group">
                                          <div class="col-lg-offset-4 col-lg-9">
                                            <a href="http://onwardtraffic.com/heatmap" class="btn btn-default">Back</a>
                                           <p id="demo"></p>
                                          </div>
                                        </div>

                                          <!-- Title -->
                                          <div class="form-group">
                                            <label class="control-label col-lg-2" for="title">Zip Code</label>
                                            <div class="col-lg-10"> 
                                              <input type="text" name="loc1" id="loc1" class="form-control" value = '<? echo $loc1 ?>' required>
                                            </div>
                                          </div>   
                                          <!-- Cateogry -->   
                                         
                                            <div class="form-group">
                                            <label class="control-label col-lg-2">Severity</label>
                                            <div class="form-check form-check-inline">
                                                    <label class="form-check-label" name="inlineCheckbox">
                                                      <input class="form-check-input" name="check_list[]" type="checkbox"value="1" <?php if($severity[0]) echo "checked='checked'"; ?>> 1
                                                      <input class="form-check-input" name="check_list[]" type="checkbox"value="2" <?php if($severity[1]) echo "checked='checked'"; ?>> 2
                                                      <input class="form-check-input" name="check_list[]" type="checkbox"value="3" <?php if($severity[2]) echo "checked='checked'"; ?>> 3
                                                      <input class="form-check-input" name="check_list[]" type="checkbox"value="4" <?php if($severity[3]) echo "checked='checked'"; ?>> 4
                                                    </label>
                                                </div>
                                            </div> 
                                          <div class="form-group">
                                            <label class="control-label col-lg-2">Time of Day</label>
                                            <div class="col-lg-10">                               
                                                <select id ="time" class="form-control" name="time" required>
                                                  <option value="">- Choose Time -</option>
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
                                           <div class="form-group">
                                            <label class="control-label col-lg-2">Date</label>
                                            <div class="col-lg-10">                               
                                                <select class="form-control" name="day" required>
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

                                          <div class="form-group">
                                            <label class="control-label col-lg-2" for="tags">Range</label>
                                            <div class="col-lg-10">
                                             <!-- <input type="text" class="form-control" id="tags">-->
                                              <select class = "form-control" id = "loc2" name="loc2" required>
                                                <option value="">- Choose Range -</option>
                                                <option value="5" <?php if ($loc2=="5") {echo "selected='selected'"; } ?> >5 miles</option>
                                                <option value="10" <?php if ($loc2=="10") {echo "selected='selected'"; } ?> >10 miles</option>
                                                <option value="15" <?php if ($loc2=="15") {echo "selected='selected'"; } ?> >15 miles</option>
                                                <option value="20" <?php if ($loc2=="20") {echo "selected='selected'"; } ?> >20 miles</option>
                                              </select>
                                            </div>
                                          </div>
                                          <!--<div class="checkbox">
                                         <label>
                                         <input type="checkbox" name="forecast"value="false">Forecast</label>
                                         </div>-->
                                        
                                        
                                          <div class="form-group">
                                            
                                             <div class="col-lg-offset-4 col-lg-9">
                                                <button type="submit" value = "forecasted_heatmap" name = "submit" id = "submit" class="btn btn-primary" >View Heat Map</button>
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
  </section>
  <!-- container section start -->
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
    <<script src="js/fullcalendar.min.js"></script> <!-- Full Google Calendar - Calendar -->
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

    <script>

      // reference: http://stackoverflow.com/questions/13310776/google-maps-api-is-it-possible-to-highlight-specific-streets

      // setting map options
      var myOptions = {
        mapTypeId: 'roadmap',
        center: {lat: 40.523148, lng: -74.458818},
        zoom: 8
      };

      var myOptionsRoute = {
        mapTypeId: 'roadmap'
      };

      // NOTE: for the options above, we can choose where to center the map when it loads, i would center it at the center of the grid we load

      // rendering options are specificially for the route
      var renderOptionsSev4 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "red",
                                strokeWeight: 6,
                                strokeOpacity: 0.8,
                                zIndex: 2}
      }

      var renderOptionsSev3 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "orange",
                                strokeWeight: 6,
                                strokeOpacity: 0.8,
                                zIndex: 2}
      }

    var renderOptionsSev2 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "yellow",
                                strokeWeight: 6,
                                strokeOpacity: 0.8,
                                zIndex: 2}
      }

    var renderOptionsSev1 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "green",
                                strokeWeight: 6,
                                strokeOpacity: 0.8,
                                zIndex: 2}
      }

    var renderOptionsSevDefault = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "#4285f4",
                                strokeWeight: 6,
                                strokeOpacity: 0.8,
                                zIndex: 1}
      }

      // declaring map variable
      var map;

      function initMap() {
        if (<?= $defaultMap; ?>){ //'<?= $defaultMap; ?>'
          map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
        }
        else{
          routeMap();
        }
      }

      // this initMap() function gets called when the website loads
      function routeMap() {
        var bounds = new google.maps.LatLngBounds();

        // setting map to display in the "map-canvas" div
        map = new google.maps.Map(document.getElementById("map-canvas"), myOptionsRoute);
        // dirService variable used for finding routes
        var dirService = new google.maps.DirectionsService();   

        // you need a new DirectionsRenderer for each new polyline, this is called in requestDirections function
        function renderDirections(result,options){
            var dirRenderer = new google.maps.DirectionsRenderer(options);
            dirRenderer.setMap(map);
            dirRenderer.setDirections(result); 
        }

        // this is the function used for each route to display, options is where you choose the color
        function requestDirections(start,end,options){
            var request = {
                origin: start,
                destination: end,
                //waypoints: [{location:"48.12449,11.5536"}, {location:"48.12515,11.5569"}],
                travelMode: google.maps.TravelMode.DRIVING
            };
            dirService.route(request, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    renderDirections(result,options);
                }
            });
        }
        
        var steps = JSON.parse('<?= $stepsJSON; ?>');
        var endIndex = '<?= $count; ?>' - 1;
        var renderOption;

        //for each step:
        for (i = 0; i <=endIndex; i++){
          //console.log(steps[i].severity);
          //alert("The first severity is " + steps[i].severity + ".");
          if (steps[i].severity != 0){
            console.log(steps[i].severity);
            switch(steps[i].severity){
                case 1:
                    renderOption = renderOptionsSev1;
                    break;
                case 2:
                    renderOption = renderOptionsSev2;
                    break;
                case 3:
                    renderOption = renderOptionsSev3;
                    break;
                case 4:
                    renderOption = renderOptionsSev4;
                    break;
            }
            requestDirections(steps[i].stLat + ", " + steps[i].stLng, steps[i].ndLat + ", " + steps[i].ndLng,renderOption);
            var boundsAdderStart = new google.maps.LatLng(steps[i].ndLat, steps[i].ndLng);
            var boundsAdderEnd = new google.maps.LatLng(steps[i].ndLat, steps[i].ndLng);
            bounds.extend(boundsAdderStart);
            bounds.extend(boundsAdderEnd);
          }
        }
      }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
    async defer></script>

    <?php 
      function pc_validate_zipcode($zipcode) {
        return preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $zipcode);
      }
      if(isset($_POST["submit"])){
        $zipCodeCheck = $_POST["loc1"];
        if (pc_validate_zipcode($zipCodeCheck)) {
            //do nothing
        } 
        else {
            // this is not an okay Zip Code, print an error message
            $message = "Invalid Zip Code";
            echo "<script type='text/javascript'>alert('$message');</script>";
            header('Location: forecastIndex.php');
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
  

<script>
function myFunction() {
    var x = document.getElementById("frm1");
    var text = "";
    var i;
    for (i = 0; i < x.length; i++) { 
      if (x[i].checked) {
          x[i].value= "true";
        }
        text += x.elements[i].value + "<br>";    
    }
    document.write(text);
    document.write("<br>");
    var text1="";
    var n;
    for(n=0; n<x.length; n++){
        text1 += n + ":" + x[n].value +" ";
    }
    document.write(text1);

    var zipcode= x[0].value;
    var severity=[x[1].value,x[2].value,x[3].value,x[4].value];
    var time= x[5].value;
    var date=x[6].value;
    var radius= x[7].value;
    var forecast= x[8].value;

    var day;

    //if forecast is needed, go to weather collector
    if(forecast==true){
        //get date from user here, code needed
        forecast=1;
        weather=null;

    }
    else{
        forecast=0;
    }
    //testing for valid zip code
    var valZip;
    if((!isNaN(parseFloat(zipcode))&& isFinite(zipcode))==false){
        valZip=false;
        //add error handling here
    }
    else if(zipcode<0||zipcode>99999){
        valZip=false;
        //add error handling here
    }
    else{
        valZip=true;
    }


    //else/if to find day from date 
    if(date == "March 26, 2017" || date == "April 2, 2017" || date == "April 9, 2017" || date == "April 16, 2017" || date == "April 23, 2017" || date == "April 30, 2017" || date == "May 7, 2017"){
        day="Sunday";
    }
    else if(date == "March 27, 2017" || date == "April 3, 2017" || date == "April 10, 2017" || date == "April 17, 2017" || date == "April 24, 2017" || date == "May 1, 2017" || date == "May 8, 2017"){
        day="Monday";
    }
    else if(date == "March 28, 2017" || date == "April 4, 2017" || date == "April 11, 2017" || date == "April 18, 2017" || date == "April 25, 2017" || date == "May 2, 2017" || date == "May 9, 2017"){
        day="Tuesday";
    }
    else if(date == "March 29, 2017" || date == "April 5, 2017" || date == "April 12, 2017" || date == "April 19, 2017" || date == "April 26, 2017" || date == "May 3, 2017" || date == "May 10, 2017"){
        day="Wednesday";
    }
    else if(date == "March 30, 2017" || date == "April 6, 2017" || date == "April 13, 2017" || date == "April 20, 2017" || date == "April 27, 2017" || date == "May 4, 2017" || date == "May 11, 2017"){
        day="Thursday";
    }
    else if(date == "March 31, 2017" || date == "April 7, 2017" || date == "April 14, 2017" || date == "April 21, 2017" || date == "April 28, 2017" || date == "May 5, 2017" || date == "May 12, 2017"){
        day="Friday";
    }
    else{
        day="Saturday";
    }
    document.write("The day is ");
    document.write(day);
    var inputParams = [forecast, zipcode, radius, weather, severity, time, day, date];
    //myAjax(inputParams);


    
}

//here is where we're actually sending the parameters to the controller, not exactly sure what else to do/how to test
/*<script>
    <script type = "text/javascript">
function myAjax(var dataString){// this is your array from the forms!
    var jsonString = JSON.stringify(dataString); //this is a json encoded array. note that this is a javascript function
    $.ajax({
        type: "POST",
        url: "./php/controller.php",
        data: {data : jsonString}, 
        cache: false,

        success: function(data){
            alert("OK");
        }
        error: function(xhr){
            alert("error");
        }
    });
    }*/
    </script>

  </body>

</html>


