import axios from 'axios'

// export function getLocation(value) {
// 	var vm = this
// 	return new Promise((resolve, reject) => {
// 		axios.get(`http://maps.googleapis.com/maps/api/geocode/json?address="+${value}+"&sensor=false`).then(response => {
// 			console.log(response)
// 			if(response.status == 200){
// 				if(response.data.results.length > 0) {
// 					resolve(response)
// 				} else {
// 					vm.getLocation(value)
// 				}				
// 			}
// 		}).catch(errors => {
// 			reject(errors)
// 		})
// 	})
// }
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