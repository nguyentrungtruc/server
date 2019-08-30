export function initMap(position) {
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom             : 17,
		center           : { lat: position.lat, lng: position.lng },
		mapTypeControl   : false,
		streetViewControl: false
	});

	return map
}

export function getLocation(value) {
	var vm = this
	return new Promise((resolve, reject) => {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({'address': value}, function(response, status) {
			if(status === 'OK') {
				resolve(response)
			} else {
				reject('Geocode was not successful for the following reason: ' + status)
			}
		})
	})
}

export function typeIcon(value) {
	var status = new String(value).toLowerCase()
	switch(status) {
		case 'quán ăn': 
		return {url: '/img/qa_blue.png'}
		break
		case 'trà sữa': 
		return {url: '/img/ts_blue.png'}
		break
		case 'cà phê': 
		return {url: '/img/cp_blue.png'}
		break
		case 'ăn vặt': 
		return {url: '/img/av_blue.png'}
		break
		case 'thức ăn nhanh': 
		return {url: '/img/ff_blue.png'}
		break
		case 'vỉa hè': 
		return {url: '/img/vh_blue.png'}
		break
	}
}

export function calculateDirection(map, start, destination) {
	var start             = new google.maps.LatLng(start.lat, start.lng);
	var end               = new google.maps.LatLng(destination.lat, destination.lng);
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;

	var request = {
		origin     : start,
		destination: end,
		travelMode : google.maps.DirectionsTravelMode.DRIVING
	}

	return new Promise((resolve, reject) => {	
		directionsService.route(request, function(response, status) {
			if (status === 'OK') 
			{				
				var service = new google.maps.DistanceMatrixService()
				var options = {
					map       : map,
					directions: response
				}
				new google.maps.DirectionsRenderer(options)
				resolve(response)
			} else {
				window.alert('Directions request failed due to ' + status);
			}
		})
	})
}
