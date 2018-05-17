import axios from 'axios'

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