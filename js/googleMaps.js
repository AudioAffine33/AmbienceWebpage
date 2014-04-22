		function initialize() {
			var input = /** @type {HTMLInputElement} */(
      				document.getElementById('placeSearch'));
			var autocomplete = new google.maps.places.Autocomplete(input);
			var strictBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(85, -180),           // top left corner of map
				new google.maps.LatLng(-85, 180)            // bottom right corner
			);
			autocomplete.setBounds(strictBounds);
			autocomplete.setTypes(['(cities)']);
			var infowindow = new google.maps.InfoWindow();
			
			var service = new google.maps.places.PlacesService(input);
			
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
				infowindow.close();
				var place = autocomplete.getPlace();								
				var address = '';
				if (place.address_components) {	
				  land =	(place.address_components[(place.address_components.length-1)] && place.address_components[(place.address_components.length-1)].short_name || '');
				}
		
				//document.getElementById('land').value = land;
				//document.getElementById('locationLat').value = place.geometry.location.lat();
				//document.getElementById('locationLong').value = place.geometry.location.lng();
				alert (print_r(place.photos));
			  });
		  }
		google.maps.event.addDomListener(window, 'load', initialize);