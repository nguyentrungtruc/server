import {imageURL} from '@/config'
export function image(url) {
	if(url == null) {
		return imageURL+'/img/default.png'
	} else {
		if(url.slice(1, 8) === "storage") {
			return imageURL+url
		} else {
			return url
		}
	}
}