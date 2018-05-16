export default {
	methods: {
		image(value) {
			if(value == null) {
				return '/img/noimage.png'
			}
			return value
		}
	}
}