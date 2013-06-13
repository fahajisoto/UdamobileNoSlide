          var directionDisplay;
          var directionsService = new google.maps.DirectionsService();
          var map;
		  var address = new google.maps.LatLng(<?php echo $latA.','.$longA; ?>);
		  var end = new google.maps.LatLng(<?php echo $latB.','.$longB; ?>);
          function initialize() 
		  {
               directionsDisplay = new google.maps.DirectionsRenderer();
 
               var optionsCarte = {
                    zoom: 7,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: pos,
						mapTypeControlOptions: 
						{
						  style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
						  position: google.maps.ControlPosition.TOP_LEFT
						}
               }
               maCarte = new google.maps.Map(document.getElementById("EmplacementDeMaCarte"), optionsCarte);
               directionsDisplay.setMap(maCarte);
               directionsDisplay.setPanel(document.getElementById("EmplacementItineraireTexte"));
               var requeteItineraire = {
                    origin: pos,
                    destination: end,
                    region: "fr",
					travelMode: google.maps.DirectionsTravelMode.DRIVING
               };
               directionsService.route(requeteItineraire, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                         directionsDisplay.setDirections(response);
                    }
               });
			   
          }
		  function calcRoute() {
				  var selectedMode = document.getElementById("mode").value;
				  var requeteItineraire = {
					  origin: pos,
					  destination: end,
					  region: "fr",
					  travelMode: google.maps.TravelMode[selectedMode]
							};
					directionsService.route(requeteItineraire, function(response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                         directionsDisplay.setDirections(response);
                    }
					});
			}