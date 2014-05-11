		function initialize() {
			var input = /** @type {HTMLInputElement} */(
      				document.getElementById('placeSearch'));
			var autocomplete = new google.maps.places.Autocomplete(input);
			var strictBounds = new google.maps.LatLngBounds(
				new google.maps.LatLng(85, -180),           // top left corner of map
				new google.maps.LatLng(-85, 180)            // bottom right corner
			);
			//autocomplete.setBounds(strictBounds);
			autocomplete.setTypes(['(regions)']);
			var infowindow = new google.maps.InfoWindow();
			
			var service = new google.maps.places.PlacesService(input);
			
			
			google.maps.event.addListener(autocomplete, 'place_changed', function() {

				var place = autocomplete.getPlace();								
				var address = '';
                console.log(place.address_components);
				if (place.address_components) {	
					land =	(place.address_components[(place.address_components.length-1)] && place.address_components[(place.address_components.length-1)].short_name || '');
				}
				document.getElementById('locName').value= getLocality(place.address_components);
				
				document.getElementById('land').value = getCountry(place.address_components);
                document.getElementById('countryCode').value = getCountryCode(place.address_components);
				document.getElementById('lat').value = place.geometry.location.lat();
				document.getElementById('lng').value = place.geometry.location.lng();
			  });
		  }
		google.maps.event.addDomListener(window, 'load', initialize);
		
		function getCountry(component){
			var geocoderAddressComponent,addressComponentTypes,address;
         	for (var i = 0; i < component.length; i++) {
				geocoderAddressComponent = component[i];
             	address = geocoderAddressComponent["long_name"];
             	addressComponentTypes = geocoderAddressComponent["types"];
             	for (var k = 0; k < addressComponentTypes.length; k++) {
               		if (addressComponentTypes[k] == 'country') {
                		return address;
               		}
           		}
         	}	
        	return 'Unknown';
		}
		
		function getLocality(component){
			var geocoderAddressComponent,addressComponentTypes,address;
            console.log(component);
         	for (var i = 0; i < component.length; i++) {
				geocoderAddressComponent = component[i];
             	address = geocoderAddressComponent["long_name"];
             	addressComponentTypes = geocoderAddressComponent["types"];
             	for (var k = 0; k < addressComponentTypes.length; k++) {
					  if (addressComponentTypes[k] == 'locality' || addressComponentTypes[k] == 'sublocality' || addressComponentTypes[k] == 'administrative_area_level_2' || addressComponentTypes[k] == 'administrative_area_level_1') {
                		return address;
               		}
           		}
         	}	
        	return 'Unknown';
		}

        function getCountryCode(component){
            var geocoderAddressComponent,addressComponentTypes,address;
            for (var i = 0; i < component.length; i++) {
                geocoderAddressComponent = component[i];
                address = geocoderAddressComponent["short_name"];
                addressComponentTypes = geocoderAddressComponent["types"];
                for (var k = 0; k < addressComponentTypes.length; k++) {
                    if (addressComponentTypes[k] == 'country') {
                        return address;
                    }
                }
            }
            return 'Unknown';
        }