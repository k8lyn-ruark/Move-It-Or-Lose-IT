
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
        output.innerHTML = "<i class='fas fa-exclamation-triangle'></i> Please call a representative for a quote.";
    } else { 
        
        if(document.getElementById('landfill-yes').checked == true){
            //charge $10 for landfill drop
            estimate = estimate +10;
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
                var goingRate = 3.0;
                //output.innerHTML = "<div class='alert-info'>From: " + document.getElementById("from").value + ".<br />To: " + document.getElementById("to").value + ".<br /> Driving distance <i class='fas fa-road'></i> : " + mileage + ".<br />Duration <i class='fas fa-hourglass-start'></i> : " + duration + ".</div>";
                
                if(document.getElementById('buncombe-yes').checked == true) {
                    //if both addresses are in Buncombe County, just charge for time
                    estimate = Math.ceil(totalTime * 60.0);   
                } else {
                    //if either address is outside of Buncombe County, charge for time and mileage
                    totalGas = Math.ceil(mileage * goingRate);
                    hours = totalTime * 60.0;
                    estimate = Math.ceil(estimate + hours + totalGas);
                }
                
                document.getElementById("form-content").style.display = "none";
                
                output.innerHTML = "";
                
                //display estimate
                quote.innerHTML = "Your junk removal quote: $" + estimate;
                    //+ " Rooms: " + rooms + " totalTime: " + totalTime + " hours: " + hours + " totalGas: " + totalGas + " mileage: " + mileage;

                //display route
                directionsDisplay.setDirections(result);
            } else {
                //delete route from map
                directionsDisplay.setDirections({ routes: [] });
                
                map.setCenter(myLatLng);
                
                //show error message
                //output.innerHTML = "<div id='alert' class='alert-danger'><i class='fas fa-exclamation-triangle'></i>Please call a representative for a quote.</div>";
                document.querySelector('#quote-gen').style.display = "none";
                output.innerHTML = "<i class='fas fa-exclamation-triangle'></i>Please call a representative for a quote.";
            }
        }); 
    }

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

