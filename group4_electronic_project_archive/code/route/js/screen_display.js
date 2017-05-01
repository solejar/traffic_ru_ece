//written by: Mhammed Alhayek
//tested by: Mhammed Alhayek, Sean Olejar
//debugged by: Mhammed Alhayek, Sean Olejar
  
//safety net in case these vars is not parsed successfully
  if (typeof submitted_yet == 'undefined'){
    var submitted_yet = false;
  }
  if (typeof alt == 'undefined'){
    var alt = false;
  }
  if (typeof num_routes == 'undefined'){
    var num_routes = 0;
  }


  // setting map options
  var myOptions = {
    mapTypeId: 'roadmap',
    center: {lat: 40.523148, lng: -74.458818},
    zoom: 8
  };

  var myOptionsRoute = {
    mapTypeId: 'roadmap'
  };

  // NOTE: for the options above, it is possible to choose where to center the map is when it loads

  // rendering options are specificially for the route
  var renderOptionsSev4 = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "red",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 3}
  }

  var renderOptionsSev3 = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "orange",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 3}
  }

var renderOptionsSev2 = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "yellow",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 3}
  }

var renderOptionsSev1 = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "green",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 3}
  }

var renderOptionsSevDefault = {
        suppressMarkers: true, 
        polylineOptions: {
                            strokeColor: "#4285f4",
                            strokeWeight: 6,
                            strokeOpacity: 0.8,
                            zIndex: 2}
  }

var renderOptionsAlt = {
    suppressMarkers: true, 
    polylineOptions: {
                        //strokeColor: "#33ccff",
                        strokeColor: "grey",
                        strokeWeight: 7,
                        strokeOpacity: 0.8,
                        zIndex: 1}
    //routeIndex: 1
  }

  // declaring map variable
  var map;
  
  var primIndex = 0;    // for displaying traffic on prim route
  var altIndex = 1;     // for displaying traffic on alt route

  //when the user clicks on primRoute or altRoute, switch the route display
  function switchRoutes(type){
      var temp_steps = primSteps;
      var temp_end_index = primEndIndex;

      primSteps = altSteps;
      primEndIndex = altEndIndex;

      altSteps = temp_steps;
      altEndIndex = temp_end_index;

      primIndex = 1 - primIndex;
      altIndex = 1 - altIndex;

      //redraw maps and charts with prim vs alt route switched
      routeMap();
      drawChart();
  }

  // this initMap() function gets called when the website loads
  function initMap() {
    if (!submitted_yet){
      map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
    }
    else{
      routeMap();
    }
  }
  
  //rounds the sev returned to an integer 0,1,2,3,or 4
  function fixSev(sev){
    var rtn;
    if (sev>4){
        sev = 4;
    }else{
      sev = Math.round(sev);
    }
    if (!conditions["severity"][sev-1]){
      rtn = 0;
    }else{
      rtn = sev;
    }
    return rtn;
  }
 
  //prints the traffic severities on the google map
  function routeMap() {
    // setting map to display in the "map-canvas" div
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptionsRoute);
    // dirService variable used for finding routes
    var dirService = new google.maps.DirectionsService();   

    // you need a new DirectionsRenderer for each new polyline, this is called in requestDirections function
    // when using alternate directions, routeIndex = 1, else = 0
    function renderDirections(result, options, routeIndex){
        var dirRenderer = new google.maps.DirectionsRenderer(options);
        dirRenderer.setMap(map);
        dirRenderer.setDirections(result);
        dirRenderer.setRouteIndex(routeIndex); 
    }

    // this is the function used for each route to display, options is where you choose the color
    // when using alternate directions, routeIndex = 1, else = 0
    function requestDirections(start,end,options, routeIndex){
        var request = {
            origin: start,
            destination: end,
            //waypoints: [{location:"48.12449,11.5536"}, {location:"48.12515,11.5569"}],
            provideRouteAlternatives: alt,
            travelMode: google.maps.TravelMode.DRIVING
        };
        dirService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                renderDirections(result,options, routeIndex);
            }
        });
    }

    var renderOption;

    //get the user params from the array where they're stored
    var day = conditions["day"];
    var hour = conditions["time"];
    var weather = conditions["weather"];

    //for all steps of the journey
    for (i = 0; i <=primEndIndex; i++){
      //pull specific sev from sev_array
  
      var sev_array = primSteps[i].severity;
      if(!sev_array){
        //this is for error handling
        continue;
      }
      
      //collect the severity pertinent to the selected user params
      var sev = sev_array[day][hour][weather];
     
     //make sure this sev is rounded properly
      sev = fixSev(sev);

      if (sev != 0){
        switch(sev){
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
        requestDirections(primSteps[i].stLat + ", " + primSteps[i].stLng, primSteps[i].ndLat + ", " + primSteps[i].ndLng,renderOption, primIndex);
      }

    }
    requestDirections(primSteps[0].stLat + ", " + primSteps[0].stLng, primSteps[primEndIndex].ndLat + ", " + primSteps[primEndIndex].ndLng,renderOptionsSevDefault, primIndex);

    //also do this if alternatives has been checked off and 2 routes have been successfully found
    if (alt && (num_routes == 2)){
      requestDirections(altSteps[0].stLat + ", " + altSteps[0].stLng, altSteps[altEndIndex].ndLat + ", " + altSteps[altEndIndex].ndLng,renderOptionsAlt, altIndex);
    }
  }
