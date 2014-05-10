var map;
function initialize() {


    var mapOptions = {
        zoom: 3,
        center: new google.maps.LatLng(locs[locs.length-1]['location']['latitude'],locs[locs.length-1]['location']['longitude'])
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    for (var i = 0; i<locs.length; i++){
        var Latlng = new google.maps.LatLng(locs[i]['location']['latitude'],locs[i]['location']['longitude']);

        var marker = new google.maps.Marker({
            position: Latlng,
            map: map,
            title: locs[i]['ambience']['name']
        });
    }
}



google.maps.event.addDomListener(window, 'load', initialize);