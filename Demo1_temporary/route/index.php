<?php 
  include '../php/controller.php';
?>

<!DOCTYPE html>

<html lang="en">

  <head>

  <!--<script src="infoEntry.js">
  </script>-->
   </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  
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
                          <!--<span class="menu-arrow arrow_carrot-right"></span>-->
                      </a>
                  </li>       
                  <li class="active">
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
                        </div>
                    </div>
                </div>
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

                      <form id="frm1" action="" method="post" class="form-horizontal">
            
                        <!--</div>-->
                          <div class="form-group">
                            <div class="col-lg-offset-4 col-lg-9">
                                                <a href="http://onwardtraffic.com/route/forecastIndex.php" class="btn btn-default">Forecast</a>
                                               <p id="demo"></p>
                                             </div>
                                          </div>

                          <div class="form-group">
                            <label class="control-label col-lg-2" for="title">Starting Address</label>
                            <div class="col-lg-10"> 
                              <input type="text" id = "loc1" name="loc1" class="form-control" value = '<? echo $loc1 ?>' required>
                            </div>
                          </div> 
                           <div class="form-group">
                            <label class="control-label col-lg-2" for="title">Ending Address</label>
                            <div class="col-lg-10"> 
                              <input type="text" id = "loc2" name="loc2" class="form-control" value = '<? echo $loc2 ?>' required>
                            </div>
                          </div>   
                          <!-- Cateogry -->
                          <div class="form-group">
                            <label class="control-label col-lg-2">Weather</label>
                            <div class="col-lg-10">                               
                                <select id = "weather" class="form-control" name="weather" required>
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
                                  <option value="AllT" <?php if ($hourF=="AllT") {echo "selected='selected'"; } ?> >All Times</option>
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
                            <label class="control-label col-lg-2">Day of Week</label>
                            <div class="col-lg-10">                               
                                <select id = "day" class="form-control" name="day" required>
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
                        
                          <div class="form-group">
                            
                             <div class="col-lg-offset-4 col-lg-9">
                                <button type="submit" value = "route" name = "submit" id = "submit" class="btn btn-primary" >View Route</button>
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
          //alert("The first road name is " + steps[0].rdName + ".");
          if (steps[i].severity != 0){
            //switch (steps[i].severity)
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
          }

        }
        requestDirections(steps[0].stLat + ", " + steps[0].stLng, steps[endIndex].ndLat + ", " + steps[endIndex].ndLng,renderOptionsSevDefault);
      }


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFuL6yafR8BuhdbzupDAcorVH4sAO2YpE&callback=initMap"
    async defer></script>

    <?php
      if(isset($_POST["submit"])){
        if($alertRouteE){
          $message = "Invalid Route Addresses";
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

    var forecast=0;
    var date=null;
    var zipcode= x[0].value;
    var weather=x[1].value;
    var severity=[x[2].value,x[3].value,x[4].value,x[5].value];
    var time= x[6].value;
    var day=x[7].value;
    var radius= x[8].value;
    
    var date=null;

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

    </script>

  </body>

</html>
