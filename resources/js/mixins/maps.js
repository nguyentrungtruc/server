import axios from 'axios'
export default {
    methods: {
        init(position) {
            return new Promise((resolve, reject) => {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom             : 17,
                    center           : { lat: position.lat, lng: position.lng },
                    mapTypeControl   : false,
                    streetViewControl: false
                });   
                resolve(map) 
            })
        },
    
        marker(position, map) {
            return new Promise((resolve, reject) => {
                var marker = new google.maps.Marker({
                    position : position,
                    map      : map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                })
                resolve(marker)
            })       
        },
    
        geocodePosition(position) {
            const url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.lat},${position.lng}&key=${apiKey}`
            return new Promise((resolve, reject) => {
                axios.get(url).then(response => {
                    resolve(response.data.results)
                }).catch(error => {
                    console.log('error geocode position', error)
                    reject(error)
                })
            })   
        },
    
        getPredictions: (address) => new Promise((resolve, reject) => {        
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function(response, status) {
                if(status === 'OK') {
                    resolve(response)
                } else {
                    reject('Geocode was not successful for the following reason: ' + status)
                }
            })
        }),
    
        getCurrentLocation() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve(position)
                    },
                    (error) => console.log(error),
                    { 
                        enableHighAccuracy: false, timeout: 2000, maximumAge: 3600000
                    }
                );
            })        
        }
    }
}