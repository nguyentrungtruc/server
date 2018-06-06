import {imageURL} from '@/config/config' 
export default {
	methods: {
		image(url) {
			if(url == null) {
				return imageURL+'/img/default.png'
			}
			return imageURL+url
		},
	}
}