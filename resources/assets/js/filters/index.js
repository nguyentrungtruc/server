import formatTime from '@/filters/formatTime'
import formatDate from '@/filters/formatDate'
import formatDateTimeHumanize from '@/filters/formatDateTimeHumanize'
import formatPrice from '@/filters/formatPrice'
export default {
	install(Vue) {
		Vue.filter('time', formatTime)
		Vue.filter('formatDate', formatDate)
		Vue.filter('formatDateTimeHumanize', formatDateTimeHumanize)
		Vue.filter('formatPrice', formatPrice)
	}
}