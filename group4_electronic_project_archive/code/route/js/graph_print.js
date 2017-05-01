//written by: Mhammed Alhayek, Shubhra Paradkar, Lauren Williams
//tested by: Mhammed Alhayek, Shubhra Paradkar, Lauren Williams, Ridwan Khan
//debugged by: Mhammed Alhayek, Shubhra Paradkar, Lauren Williams

if(typeof submitted_yet == 'undefined'){
  var submitted_yet = false;
}
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart', 'bar', 'line']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);   

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
  
  if (submitted_yet){

    //collect the current user conditions
    var day = conditions["day"];
    var hour = conditions["time"];
    var weather = conditions["weather"];

    var primSevArr = new Array();
    
    //let's get a severity for every hour 
    for (hour = 0; hour<24; hour++){
      total_this_hour = 0;
      
      
      for (i = 0; i<=primEndIndex; i++){
        var sev_array = primSteps[i].severity;
        if(!sev_array){
          //this is for error handling
          continue;
        }
        var sev = sev_array[day][hour][weather];
        sev = fixSev(sev);

        total_this_hour += sev;
      }
      //add the sev total for this hour to the array
      primSevArr[hour] = total_this_hour;
    }

    // Create the data table.
    var prim_data = new google.visualization.DataTable();
    prim_data.addColumn('number', 'Hour');
    prim_data.addColumn('number', 'Primary Route Total Traffic Severity');

    //if alt route exists, get data for it and put it in a graph
    if(alt){

      var altSevArr = new Array();
      //get traff data for alt route
      for (i = 0; i<24; i++){
        alt_total_this_hour = 0;
        for (j = 0; j<=altEndIndex; j++){
          var sev_array = altSteps[j].severity;
          if(!sev_array){
            //this is for error handling
            continue;
          }
          var alt_sev = sev_array[day][i][weather];
          alt_sev = fixSev(alt_sev);

          alt_total_this_hour += alt_sev;
        }
        altSevArr[i] = alt_total_this_hour;
      }
      
      //add this data to the graph
      prim_data.addColumn('number', 'Alternate Total Traffic Severity');
      for(i=0; i<24; i++){
        prim_data.addRows([[i, primSevArr[i], altSevArr[i]]]);
      }
      //some rendering options for the graph
      var alt_options = {
        chart:{
          title: 'Alternate & Primary Route Total Traffic Severity vs. Hour'
        },
        series: {
          0: {color: '#9966ff'},
          1: {color: 'black'}
        }
     };
      
      //this chart is for the alt route, if applicable
      var alt_chart = new google.charts.Line(document.getElementById('prim_chart_div'));
      alt_chart.draw(prim_data, google.charts.Line.convertOptions(alt_options));
    }
    else{
      for(i=0; i<24; i++){
        prim_data.addRows([[i, primSevArr[i]]]);
      }
      var prim_options = {
        chart:{
          title: 'Primary Route Total Traffic Severity vs. Hour'
        },
        series: {
          0: {color: '#9966ff'},
        }
      };

      var prim_chart = new google.charts.Line(document.getElementById('prim_chart_div'));
      prim_chart.draw(prim_data, google.charts.Line.convertOptions(prim_options));
    }
  }
}
