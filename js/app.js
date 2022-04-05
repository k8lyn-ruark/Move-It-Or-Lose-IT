
//javascript.js

//set map options
var myLatLng = { lat: 35.5951, lng: -82.5515 };
var mapOptions = {
    center: myLatLng,
    zoom: 11,
    mapTypeId: google.maps.MapTypeId.ROADMAP

};

//create map
var map = new google.maps.Map(document.getElementById('map'), mapOptions);

document.getElementById("map").style.display = "none";
document.querySelector('#quote-gen').style.display = "none";
document.getElementById("output").style.display = "none";

//create a DirectionsService object to use the route method and get a result for our request
var directionsService = new google.maps.DirectionsService();

//create a DirectionsRenderer object which we will use to display the route
var directionsDisplay = new google.maps.DirectionsRenderer();

//bind the DirectionsRenderer to the map
directionsDisplay.setMap(map);

//define calcRoute function
function calcQuote() {
    alert("This is an estimate. Final cost of service will be determined at time of service.");
    
    document.querySelector('#quote-gen').style.display = "";
    document.getElementById("output").style.display = "";
    //declare total variable
    let totalTime = 0.0;

    //declare estimate variable
    let estimate = 0.0;
    let hours = 0.0;
    let totalGas = 0.0

    //get information from web form
    var rooms = parseFloat(document.getElementById("rooms").value);
    
    const output = document.querySelector('#output');
    
    const quote = document.querySelector('#quote-gen');
    
    //create request
    var request = {
        origin: document.getElementById("from").value,
        destination: document.getElementById("to").value,
        travelMode: google.maps.TravelMode.DRIVING, // could also be WALKING, BYCYCLING, TRANSIT
        unitSystem: google.maps.UnitSystem.IMPERIAL
    }  
    
    if(document.getElementById('heavy-yes').checked == true || document.getElementById('truck-no').checked == true) {  
        //if either an item is over 300 lbs or the driveway is not accessible by truck, no quote is given
        document.getElementById("form-content").style.display = "none";
        document.querySelector('#quote-gen').style.display = "none";
        output.innerHTML = "<i class='fas fa-phone'></i> Please call a representative for a quote at 828-231-1983";
    } else { 
        
        if(document.getElementById('landfill-yes').checked == true){
            //charge $10 for landfill drop
            estimate = estimate + 25;
        }
    
        if(document.getElementById('stairs-yes').checked == true) {   
            // charge 1 hour per room with stairs
            totalTime = totalTime + (1 * rooms);
        } else {
            //charge 30 minutes per room otherwise
            totalTime = totalTime + (.5 * rooms);
        }

        //pass the request to the route method
        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                document.getElementById("map").style.display = "";
          
                //Get distance and time
                var duration = result.routes[0].legs[0].duration.text;
                var distance = result.routes[0].legs[0].distance.text;
                var mileage = parseFloat(distance) 
                var goingRate = 4.0;
                //output.innerHTML = "<div class='alert-info'>From: " + document.getElementById("from").value + ".<br />To: " + document.getElementById("to").value + ".<br /> Driving distance <i class='fas fa-road'></i> : " + mileage + ".<br />Duration <i class='fas fa-hourglass-start'></i> : " + duration + ".</div>";
                
                if(mileage >= 300 || totalTime > 9) {  
                    //if mileage is over or equal to 300 miles OR total time is more than 9 hours, no quote is given
                    document.getElementById("form-content").style.display = "none";
                    document.querySelector('#quote-gen').style.display = "none";
                    document.getElementById("map").style.display = "none";
                    output.innerHTML = "<i class='fas fa-phone'></i> Please call a representative for a quote at 828-231-1983";
                } else { 

                    if(document.getElementById('buncombe-yes').checked == true) {
                        //if both addresses are in Buncombe County, just charge for time
                        estimate = Math.ceil(totalTime * 70.0);   
                    } else {
                        //if either address is outside of Buncombe County, charge for time and mileage
                        totalGas = Math.ceil(mileage * goingRate);
                        hours = totalTime * 60.0;
                        estimate = Math.ceil(estimate + hours + totalGas);
                    }

                    document.getElementById("form-content").style.display = "none";

                    output.innerHTML = "";

                    //display estimate
                    quote.innerHTML = "<h4>Your junk removal quote: $" + estimate + "</h4>";
                        //+ " Rooms: " + rooms + " totalTime: " + totalTime + " hours: " + hours + " totalGas: " + totalGas + " mileage: " + mileage;

                    //if estimated hours is 1 hour or less, make the short job scheduler available
                    if(totalTime<=1){ 
                        output.innerHTML = "<button style='background-color: ghostwhite; border: none; border-radius: 15px; color: black; padding: 15px 25px; cursor: pointer;' id='form-schedule' class='get-schedule' onclick='scheduleShort()'>Schedule A Job</button>";
                    } else if(totalTime<=4) {
                      //if estimated totalTime is between 1 and 4 hours, make the standard job scheduler available
                       output.innerHTML = "<button style='background-color: ghostwhite; border: none; border-radius: 15px; color: black; padding: 15px 25px; cursor: pointer;' id='form-schedule' class='get-schedule' onclick='scheduleStandard()'>Schedule A Job</button>";
                    } else if(totalTime<=9) {
                      //if estimated totalTime is between 1 and 4 hours, make the standard job scheduler available
                       output.innerHTML = "<button style='background-color: ghostwhite; border: none; border-radius: 15px; color: black; padding: 15px 25px; cursor: pointer;' id='form-schedule' class='get-schedule' onclick='scheduleLong()'>Schedule A Job</button>";
                    }

                    //display route
                    directionsDisplay.setDirections(result);
                } 
            } else {
                //delete route from map
                directionsDisplay.setDirections({ routes: [] });
                
                map.setCenter(myLatLng);
                
                //show error message
                //output.innerHTML = "<div id='alert' class='alert-danger'><i class='fas fa-exclamation-triangle'></i>Please call a representative for a quote.</div>";
                document.querySelector('#quote-gen').style.display = "none";
                output.innerHTML = "<i class='fas fa-phone'></i>Please call a representative for a quote at 828-231-1983";
            }
        }); 
    }

} 

function scheduleShort() {
    //open short job calendar in new tab
    window.open("https://moveitorloseitshort.simplybook.me/v2/#book", '_blank');
}

function scheduleStandard() {
    //open standard job calednar in new tab
     window.open("https://moveitorloseit.simplybook.me/v2/#book", '_blank');
}

function scheduleLong() {
    //open standard job calednar in new tab
     window.open("https://moveitorloseitlong.simplybook.me/v2/#book", '_blank');
}

function reset() {
    document.getElementById("form-content").style.display = "";
    document.querySelector('#quote-gen').style.display = "none";
    document.getElementById("output").style.display = "none";
    document.getElementById("form").reset();
    //create map
   document.getElementById("map").style.display = "none";
    quote.innerHTML = "";
    output.innerHTML = "";
}

//create autocomplete objects for all inputs
var options = {
    types: ['(cities)']
}

var input1 = document.getElementById("from");
var autocomplete1 = new google.maps.places.Autocomplete(input1, options);

var input2 = document.getElementById("to");
var autocomplete2 = new google.maps.places.Autocomplete(input2, options);

