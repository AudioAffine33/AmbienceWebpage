var map;
function initialize() {


    var mapOptions = {
        zoom: 3,
        center: new google.maps.LatLng(locs[0]['location']['latitude'],locs[0]['location']['longitude']),
        streetViewControl: false,
        mapTypeControl : false,
        mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    for (var i = 0; i<locs.length; i++){
        var marker;
        var Latlng;
        if (locs[i]['ambience']['name'] != "dummy"){

            Latlng = new google.maps.LatLng(locs[i]['location']['latitude'],locs[i]['location']['longitude']);
            marker = new google.maps.Marker({
                position: Latlng,
                map: map,
                title: locs[i]['ambience']['name']
            });

            google.maps.event.addListener(marker, "dblclick", function () {
                map.setCenter(this.position);
                map.setZoom(8);
            });
        }
    }
}



google.maps.event.addDomListener(window, 'load', initialize);