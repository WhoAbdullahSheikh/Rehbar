function initMap(position) {
    const bounds = new google.maps.LatLngBounds();
    const markersArray = [];
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    var mylatlog = {lat: latitude, lng: longitude};
    const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: latitude, lng: longitude },
        zoom: 15,
    });
    map.setCenter(mylatlog);
    var marker = new google.maps.Marker({
        position: mylatlog,
        map: map,
        title: 'Your Location'

    });
}
//window.initMap = initMap;
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(initMap);

    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    alert("Latitude: " + latitude + ", Longitude: " + longitude);
    
}
getLocation()
function getDirections() {
    var startLocation = document.getElementById('start').value;
    var endLocation = document.getElementById('end').value;

    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: {lat: 41.85, lng: -87.65}
    });
    directionsRenderer.setMap(map);

    var request = {
        origin: startLocation,
        destination: endLocation,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function(response, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}