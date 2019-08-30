import {imageURL} from '@/config'
export default {
	methods: {
		image(url) {
			if(url == null) {
				return imageURL+'/img/default.png'
			} else {
				if(url.slice(1, 8) === "storage") {
					return imageURL+url
				} else {
					return url
				}
			}
		},	
		typeIcon(value) {
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
	},
	computed: {
		cameraOverlay: function() {
			return this.$vuetify.breakpoint.smAndDown ? `height: 30%`: `height: 100%`
		}
	}
}