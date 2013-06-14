var map;
var myMarker;		
var input ;
var autocomplete ;
var address ;			
var mapOptions;
var directionDisplay;
var end;
var html="";
var a=3;
var counter=0;
var nbelt, i;
var lat;
var lon;		
var requeteItineraire;
var directionsService;


function initialize() {		
	$('#Loading').hide();
	directionsService = new google.maps.DirectionsService();
	directionsDisplay = new google.maps.DirectionsRenderer();
	input = document.getElementById('searchTextField');
	autocomplete = new google.maps.places.Autocomplete(input);
	address = new google.maps.LatLng(45.769,3.09);		
	end= new google.maps.LatLng(45.764892,3.086801);
	mapOptions = {
		zoom: 17,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		center: address,
		mapTypeControlOptions:
		{
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			mapition: google.maps.ControlPosition.TOP_LEFT
		}
		
	};	
	map = new google.maps.Map(document.getElementById('mapholder'),mapOptions);

	google.maps.event.trigger(map,'resize');
}


function getLocation() {
				//window.location='map.html';
				alert("Loading, please wait...");
				$('#Loading').show();
				$("#mapholder").css({ opacity: 0, zoom: 0 });
				if (navigator.geolocation)
				{
					navigator.geolocation.getCurrentPosition(showPosition,showError);
				}
				else{x.innerHTML="Geolocation is not supported by this browser.";}
			}

			function showPosition(position)
			{
				address = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				mapOptions = {
					zoom: 17,
					center: address,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map(document.getElementById('mapholder'),mapOptions);	
				google.maps.event.trigger(map,'resize');
				myMarker = new google.maps.Marker({
					map: map,
					position: address,
					title: "TEST"
				});	
				$('#mapholder').show();
				$('#liste_class').hide();
				$("#mapholder").css({ opacity: 1, zoom: 1 });
				$('#Loading').hide();
				
				
				
			}

			function showError(error)
			{
				switch(error.code) 
				{
					case error.PERMISSION_DENIED:
					x.innerHTML="User denied the request for Geolocation."
					break;
					case error.POSITION_UNAVAILABLE:
					x.innerHTML="Location information is unavailable."
					break;
					case error.TIMEOUT:
					x.innerHTML="The request to get user location timed out."
					break;
					case error.UNKNOWN_ERROR:
					x.innerHTML="An unknown error occurred."
					break;
				}
			}

			function calcRoute(position,end) {
				directionsDisplay.setMap(map);
				directionsDisplay.setPanel(document.getElementById("mapplacetext"));
				var requeteItineraire = {
					origin: address,
					destination: end,
					region: "fr",
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
				directionsService.route(requeteItineraire, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(response);
					}
				});
				address = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				var selectedMode = document.getElementById("searchTextField").value;
				var requeteItineraire = {
					origin: address,
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
			


		