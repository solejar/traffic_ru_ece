//written by: Mhammed Alhayek
//tested by: Mhammed Alhayek, Sean Olejar
//debugged by: Mhammed Alhayek, Sean Olejar

//safety net in case this var is not parsed successfully
if (typeof submitted_yet == 'undefined'){
    var submitted_yet = false;
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

  // this initMap() function gets called when the website loads
  function initMap() {

    if (!submitted_yet){
      map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);

    }
    else{
      routeMap();
    }
  }

  function routeMap() {
    var myLatLng = {lat: cent_lat, lng: cent_lng};
    var bounds = new google.maps.LatLngBounds();

    // setting map to display in the "map-canvas" div
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptionsRoute);
    // dirService variable used for finding routes
    var dirService = new google.maps.DirectionsService();  
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Center of Zip'
      }); 

    // you need a new DirectionsRenderer for each new polyline, this is called in requestDirections function
    // when using alternate directions, routeIndex = 1, else = 0
    function renderDirections(result, options){
        console.log("renderDirections just called");
        var dirRenderer = new google.maps.DirectionsRenderer(options);
        dirRenderer.setMap(map);
        dirRenderer.setDirections(result);
        //dirRenderer.setRouteIndex(routeIndex); 
    }

    // this is the function used for each route to display, options is where you choose the color
    // when using alternate directions, routeIndex = 1, else = 0
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

    var renderOption;

    //get the user params from the array where they're stored
    var day = conditions["day"];
    var hour = conditions["time"];
    var weather = conditions["weather"];

    var default_map = true;

    //for all traffic severities collected
    for (i = 0; i <=endIndex; i++){

      //collec the severity pertinent to the selected user params
      var sev = steps[i].severity[day][hour][weather];

      //make sure severity is rounded properly
      sev = fixSev(sev);

      if (sev != 0){
        default_map = false;
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
        //console.log(sev);
        requestDirections(steps[i].stLat + ", " + steps[i].stLng, steps[i].ndLat + ", " + steps[i].ndLng,renderOption);
      }

    }

    if (default_map){
      map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    }
}
