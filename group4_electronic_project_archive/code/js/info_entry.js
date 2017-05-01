//written by: Sean Olejar
//debugged by: Sean Olejar, Mhammed Alhayek
//tested by: Sean Olejar

//array of subscribers to info entry, currently only screen display cares 
var subscribers = [
  'screen_display',
];


function select_route_subset_alert_info(type){
  //do route parsing based on $stepsJSON
 
  //only parse route if the user has already submitted all info 
  if (submitted_yet){
      switch (type){
          //change params based on input clicked
          case "day":
              conditions["day"] = document.getElementById(type).value;
              break;

          case "time":
              var curr_feature = conditions["which_feature"];
              var curr_time = document.getElementById(type).value;
              conditions["time"] = curr_time;

              console.log("button was pressed with this: "+curr_feature);
              if(curr_feature=="route"||curr_feature == "heatmap"){
                  
                  break;
              }else if(curr_feature=="forecasted_route"||curr_feature == "forecasted_heatmap"){

                //need to reload page here, because forecast time has changed!
                document.forms[0].submit();

                break;
              }

          case "weather":
              conditions["weather"] = document.getElementById(type).value;

              break;

          case "sev1":
              conditions["severity"][0] = document.getElementById(type).checked;

              break;

          case "sev2":
              conditions["severity"][1] = document.getElementById(type).checked;

              break;
          case "sev3":
              conditions["severity"][2] = document.getElementById(type).checked;

              break;
          case "sev4":
              conditions["severity"][3] = document.getElementById(type).checked;
              break;
          case "date":
              var date_string = document.getElementById(type).value;

              //have to parse date to day.
              var d = new Date(date_string);
              var n = d.getDay();
              var day_string;
              switch(n){
                case 0:
                  day_string = "Sunday";
                  break;
                case 1:
                  day_string = "Monday";
                  break;
                case 2:
                  day_string = "Tuesday";
                  break;
                case 3:
                  day_string = "Wednesday";
                  break;
                case 4:
                  day_string = "Thursday";
                  break;
                case 5:
                  day_string = "Friday";
                  break;
                case 6:
                  day_string = "Saturday";
                  break;

              }
              conditions["day"] = day_string;

              //need to reload page here, because forecast date has changed!
              document.forms[0].submit();  

              break;
      }
  }
}

function form_change(which_form){
    console.log(which_form);
    //var length = subscribers.length;
    //for(var i = 0;i<length;i++){

      select_route_subset_alert_info(which_form); 

      if (submitted_yet){
        routeMap();
        drawChart(); 
      }          

}

//have all the buttons call the "alert" function upon being clicked
document.getElementById("weather").addEventListener("change",function(){
  form_change("weather")
},false);

document.getElementById("time").addEventListener("change",function(){
  form_change("time")
},false);

document.getElementById("day").addEventListener("change",function(){
  form_change("day")
},false);

document.getElementById("date").addEventListener("change",function(){
  form_change("date")
},false);

document.getElementById("sev1").addEventListener("click",function(){
  form_change("sev1")
},false);

document.getElementById("sev2").addEventListener("click",function(){
  form_change("sev2")
},false);

document.getElementById("sev3").addEventListener("click",function(){
  form_change("sev3")
},false);

document.getElementById("sev4").addEventListener("click",function(){
  form_change("sev4")
},false);
