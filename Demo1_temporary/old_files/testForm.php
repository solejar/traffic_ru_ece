<?php 
  include '../heatmap/map_communicator.php';
  //include '../heatmap/TrafficCollector.php';

  if(isset($_POST["submit"])){
    $startingAdd = $_POST["startLoc"];
    $stoppingAdd = $_POST["endLoc"];
    $hourF = $_POST["time"];
    $dayF = $_POST["day"];
    $weatherF = $_POST["weather"];
    $severity = array();

    if(!empty($_POST['check_list'])) {
	    foreach($_POST['check_list'] as $check) {
	            array_push($severity, $check); //echoes the value set in the HTML form for each checked checkbox.
		                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
	                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
	    }
	}	

    $routeDetails = array($startingAdd, $stoppingAdd);

    $route = get_route($routeDetails);
    $testSteps = parse_route($route);
    $count = sizeof($testSteps);

    $testArr = array(true, "08755", "50", $weatherF, $severity, $hourF, $dayF, 0, "route");

    for ($i = 0; $i<$count; $i++){
        $rd = $ptestSteps[$i]->rdName;
        $sLat = $testStes[$i]->stLat;
        $sLo = $testSteps[$i]->stLng;
        $testSteps[$i]->severity = getRouteTraff($rd, $sLat, $sLo, $testArr);
        /*
        echo "Step: ".$i.": "."<br>";
        echo "Starting Coordinates: ".$testSteps[$i]->stLat.", ".$testSteps[$i]->stLng."<br>";
        echo "Ending Coordinates: ".$testSteps[$i]->ndLat.", ".$testSteps[$i]->ndLng."<br>";
        echo "Road Name: ".$testSteps[$i]->rdName."<br>";
        echo "Severity: ".$testSteps[$i]->severity."<br>"."<br>";*/
    }
    //testSteps now is the array of structs for the map draw
    $stepsJSON = json_encode($testSteps);
  }
  else echo "fuck me!";

  /*<?php if( isset($testSteps) ) {echo $testSteps[0]->rdName;} else { echo "fuck"; } //print the result above the form ?>
*/
?>


<!DOCTYPE html>

<html lang="en">

  <head>

  <!--<script src="infoEntry.js">
  </script>-->
  <script type="text/javascript">
	$(document).ready(function () {
	    $('#submit').click(function() {
	      checked = $("input[type=checkbox]:checked").length;

	      if(!checked) {
	        alert("You must check at least one checkbox.");
	        return false;
	      }

	    });
	});

	</script>
   </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Creative - Bootstrap 3 Responsive Admin Template">
    <meta name="author" content="GeeksLabs">
    <meta name="keyword" content="Creative, Dashboard, Admin, Template, Theme, Bootstrap, Responsive, Retina, Minimal">
    <link rel="shortcut icon" href="img/favicon.png">
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
            <!--logo start Heat Map-->
            <a href="index.html" class="logo">Route <span class="lite">Analysis </span></a>
            <!--logo end-->
            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">                    
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Search" type="text">
                        </form>
                    </li>                    
                </ul>
                <!--  search form end -->                
            </div>
      </header>      
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">                
                  <li class="active">
                      <a class="" href="index.html">
                          <i class="icon_house_alt"></i>
                          <span>Home</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a href="javascript:;" class="">
                          <i class="icon_document_alt"></i>
                          <span>HeatMap</span>
                          <!--<span class="menu-arrow arrow_carrot-right"></span>-->
                      </a>
                  </li>       
                  <li class="sub-menu">
                      <a href="javascript:;" class="">
                          <i class="icon_desktop"></i>
                          <span>Route</span>
                          <!--<span class="menu-arrow arrow_carrot-right"></span>-->
                      </a>
                  </li>
                  <li>
                      <a class="" href="widgets.html">
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
                                <a href="index.html#" class="btn-setting"><i class="fa fa-rotate-right"></i></a>
                                <a href="index.html#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                                <a href="index.html#" class="btn-close"><i class="fa fa-times"></i></a>
                            </div>  
                        </div>
                        <div class="panel-body-map">
                            <div id="map-canvas" ></div>  
                        </div>
                    </div>
                </div>
                 <div class="col-lg-6 col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="pull-left">Route Analysis Form</div>
                  <div class="widget-icons pull-right">
                    <a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a> 
                    <a href="#" class="wclose"><i class="fa fa-times"></i></a>
                  </div>  
                  <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                  <div class="padd">
                      <div class="form quick-post">
                                      <!-- Edit profile form (not working)-->

                                      <form id="frm1" action="testForm.php" method="post" class="form-horizontal">
                            
                                        <!--</div>-->
                                          <div class="form-group">
                                            <label class="control-label col-lg-2">Forecast</label>
                                            <div class="form-check form-check-inline">
                                                    <label class="form-check-label" name="inlineCheckbox">
                                                        <input id = "forecast" name="forecast" class="form-check-input" type="checkbox" value="false" >
                                                    </label>
                                                </div>
                                            </div> 

                                          <div class="form-group">
                                            <label class="control-label col-lg-2" for="title">Starting Address</label>
                                            <div class="col-lg-10"> 
                                              <input type="text" id = "startLoc" name="startLoc" class="form-control" required>
                                            </div>
                                          </div> 
                                           <div class="form-group">
                                            <label class="control-label col-lg-2" for="title">Ending Address</label>
                                            <div class="col-lg-10"> 
                                              <input type="text" id = "endLoc" name="endLoc" class="form-control" required>
                                            </div>
                                          </div>   
                                          <!-- Cateogry -->
                                          <div class="form-group">
                                            <label class="control-label col-lg-2">Weather</label>
                                            <div class="col-lg-10">                               
                                                <select id = "weather" class="form-control" name="weather" required>
                                                  <option value="">- Choose Weather -</option>
                                                  <option value="AllW" >Any Weather</option>
                                                  <option value="Clear" >Clear</option>
                                                  <option value="Snow" >Snow</option>
                                                  <option value="Cloudy" >Cloudy</option>
                                                  <option value="Rain" >Rain</option>
                                                  <option value="Fog" >Fog</option>
                                                </select>  
                                            </div>
                                          </div>     
                                         
                                            <div class="form-group">
                                            <label class="control-label col-lg-2">Severity</label>
                                            <div class="form-check form-check-inline">
                                                    <label class="form-check-label" name="inlineCheckbox">
                                                        <input class="form-check-input" name="check_list[]" type="checkbox"value="1"> 1
                                                        <input class="form-check-input" name="check_list[]" type="checkbox"value="2"> 2
                                                        <input class="form-check-input" name="check_list[]" type="checkbox"value="3"> 3
                                                        <input class="form-check-input" name="check_list[]" type="checkbox"value="4"> 4
                                                    </label>
                                                </div>
                                            </div> 
                                          <div class="form-group">
                                            <label class="control-label col-lg-2">Time of Day</label>
                                            <div class="col-lg-10">                               
                                                <select id ="time" class="form-control" name="time" required>
                                                  <option value="">- Choose Time -</option>
                                                  <option value="AllT">All Times</option>
                                                  <option value="0">12:00 AM</option>
                                                  <option value="1">1:00 AM</option>
                                                  <option value="2">2:00 AM</option>
                                                  <option value="3">3:00 AM</option>
                                                  <option value="4">4:00 AM</option>
                                                  <option value="5">5:00 AM</option>
                                                  <option value="6">6:00 AM</option>
                                                  <option value="7">7:00 AM</option>
                                                  <option value="8">8:00 AM</option>
                                                  <option value="9">9:00 AM</option>
                                                  <option value="10">10:00 AM</option>
                                                  <option value="11">11:00 AM</option>
                                                  <option value="12">12:00 PM</option>
                                                  <option value="13">1:00 PM</option>
                                                  <option value="14">2:00 PM</option>
                                                  <option value="15">3:00 PM</option>
                                                  <option value="16">4:00 PM</option>
                                                  <option value="17">5:00 PM</option>
                                                  <option value="18">6:00 PM</option>
                                                  <option value="19">7:00 PM</option>
                                                  <option value="20">8:00 PM</option>
                                                  <option value="21">9:00 PM</option>
                                                  <option value="22">10:00 PM</option>
                                                  <option value="23">11:00 PM</option>
                                                </select>  
                                            </div>
                                          </div> 
                                           <div class="form-group">
                                            <label class="control-label col-lg-2">Day of Week</label>
                                            <div class="col-lg-10">                               
                                                <select id = "day" class="form-control" name="day" required>
                                                  <option value="">- Choose Day of Week -</option>
                                                  <option value="Monday">Monday</option>
                                                  <option value="Tuesday">Tuesday</option>
                                                  <option value="Wednesday">Wednesday</option>
                                                  <option value="Thursday">Thursday</option>
                                                  <option value="Friday">Friday</option>
                                                  <option value="Saturday">Saturday</option>
                                                  <option value="Sunday">Sunday</option>
                                                </select>  
                                            </div>
                                          </div> 
                                        
                                          <div class="form-group">
                                            
                                             <div class="col-lg-offset-4 col-lg-9">
                                                <input type="submit" value="View Route" name = "submit" id = "submit" class="btn btn-primary" onclick="initMap()"></button>
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
        mapTypeId: 'roadmap'
      };

      // NOTE: for the options above, we can choose where to center the map when it loads, i would center it at the center of the grid we load

      // rendering options are specificially for the route
      var renderOptionsSev4 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "red",
                                strokeWeight: 6,
                                strokeOpacity: 0.6}
      }

      var renderOptionsSev3 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "orange",
                                strokeWeight: 6,
                                strokeOpacity: 0.6}
      }

    var renderOptionsSev2 = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "yellow",
                                strokeWeight: 6,
                                strokeOpacity: 0.6}
      }

    var renderOptionsSevDefault = {
            suppressMarkers: true, 
            polylineOptions: {
                                strokeColor: "#7FB6FA",
                                strokeWeight: 6,
                                strokeOpacity: 0.5}
      }

      // declaring map variable
      var map;

      // this initMap() function gets called when the website loads
      function initMap() {

        // setting map to display in the "map-canvas" div
        map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
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

        // requestDirections("48.1252,11.5407","48.13376,11.5535",renderOptionsSev4);
        // requestDirections("48.130605, 11.539121","48.119498, 11.540567",renderOptionsSev3);


        /*testing for scalability
        requestDirections("62 guilden st new brunswick nj","52 guildin st new brunswick nj",renderOptionsSev4);
        requestDirections("51 guilden st new brunswick nj","42 guildin st new brunswick nj",renderOptionsSev3);
        requestDirections("41 guilden st new brunswick nj","32 guildin st new brunswick nj",renderOptionsSev2);
        requestDirections("31 guilden st new brunswick nj","22 guildin st new brunswick nj",renderOptionsSev4);
        requestDirections("21 guilden st new brunswick nj","12 guildin st new brunswick nj",renderOptionsSev3);
        requestDirections("11 guilden st new brunswick nj","2 guildin st new brunswick nj",renderOptionsSev2);
        requestDirections("62 delafield st new brunswick nj","52 delafield st new brunswick nj",renderOptionsSev4);
        requestDirections("51 delafield st new brunswick nj","42 delafield st new brunswick nj",renderOptionsSev3);
        requestDirections("41 delafield st new brunswick nj","32 delafield st new brunswick nj",renderOptionsSev2);
        requestDirections("31 delafield st new brunswick nj","22 delafield st new brunswick nj",renderOptionsSev4);
        requestDirections("90 easton ave new brunswick nj","100 easton ave new brunswick nj",renderOptionsSev3);
        */

        var steps = JSON.parse('<?= $stepsJSON; ?>');
        var endIndex = '<?= $count; ?>' - 1;
        for (i = 0; i <=endIndex; i++){

          //alert("The first road name is " + steps[0].rdName + ".");
          if (steps[i].severity != 0){
            //switch (steps[i].severity)
            requestDirections(steps[i].stLat + ", " + steps[i].stLng, steps[i].ndLat + ", " + steps[i].ndLng,renderOptionsSev2);
          }

        }

        requestDirections(steps[0].stLat + ", " + steps[0].stLng, steps[endIndex].ndLat + ", " + steps[endIndex].ndLng,renderOptionsSevDefault);
        // requestDirections("41 delafield st new brunswick nj","32 delafield st new brunswick nj",renderOptionsSev2);
        // requestDirections("31 delafield st new brunswick nj","22 delafield st new brunswick nj",renderOptionsSev4);
        // requestDirections("90 easton ave new brunswick nj","100 easton ave new brunswick nj",renderOptionsSev3);
        /*

        requestDirections("40.7428759,-74.00584719999999","40.7422925,-74.004457",renderOptionsSev2);
        requestDirections("40.7422925,-74.004457","40.7421744,-74.0045361",renderOptionsSev4);
        requestDirections("40.7421744,-74.0045361","40.7416627,-74.0049708",renderOptionsSev3);

        requestDirections("40.7416627,-74.0049708","40.7428202, -74.0077185",renderOptionsSev2);
        requestDirections("40.7428202, -74.0077185","40.7577372, -73.996854",renderOptionsSev4);
        requestDirections("40.7577372, -73.996854","40.7581447, -73.9977895",renderOptionsSev3);

        requestDirections("40.7581447, -73.9977895","40.7589141, -73.9996907",renderOptionsSev2);
        requestDirections("40.7589141, -73.9996907","40.7595325, -73.999242",renderOptionsSev4);
        requestDirections("40.7595325, -73.999242","40.7593444, -73.9988302",renderOptionsSev3);*/

        //requestDirections("40.7428759, -74.0058472","40.8120978, -74.0724079",renderOptionsSevDefault);
        // requestDirections("41 delafield st new brunswick nj","32 delafield st new brunswick nj",renderOptionsSev2);
        // requestDirections("31 delafield st new brunswick nj","22 delafield st new brunswick nj",renderOptionsSev4);
        // requestDirections("90 easton ave new brunswick nj","100 easton ave new brunswick nj",renderOptionsSev3);*/


      }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
    async defer></script>

    
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
    /*for(n=0; n<x.length; n++){
        text1 += n + ":" + x[n].value +" ";
    }
    document.write(text1);*/

    var forecast= x[0].value;
    var zipcode= x[1].value;
    var weather=x[2].value;
    var severity=[x[3].value,x[4].value,x[5].value,x[6].value];
    var time= x[7].value;
    var day=x[8].value;
    var radius= x[9].value;
    
    var date=null;

    //if forecast is needed, go to weather collector
    if(forecast){
        //get date from user here, code needed
        forecast=1;
        weather=null;

    }
    else{
        forecast=0;
        date=null;
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

    var inputParams = [forecast, zipcode, radius, weather, severity, time, day, date];


    document.write("forecast: " + forecast +"zip: "+zipcode+"radius: "+radius+"1: "+severity[0]+"2: "+severity[1]+"3: "+severity[2]+"4: "+severity[3]+"time: "+time+"day: "+day);

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

