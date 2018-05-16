import Vue from 'vue'
import moment from 'moment'

Vue.filter('formatTime', function(time) {
	if(time) {
		return moment(time,'HH:mm:ss').format('HH:mm')
	}
})

Vue.filter('formatDateTimeHumanize', function(date) {
	if(date) {
		var start = moment(date, 'DD-MM-YYYY HH:mm')
		return start.startOf().locale('vi').fromNow()
	}
})

Vue.filter('formatDate', function(date) {
	if(date) {
		return moment(date, 'YYYY-MM-DD').format('DD-MM-YYYY')
	}
})

Vue.filter('formatPrice', function(price) {
	return numeral(price).format('0,0$')
})

Vue.filter('subPrice', function(price, qty) {
	return numeral(Math.floor(price*qty)).format('0,0$')
})