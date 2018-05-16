import axios from 'axios'

export const http = {
	request (method, url, data, response = null, error = null) {
		axios.request({url, data, method: method.toLowerCase()}).then(response).catch(error)
	},

	get (url, response = null, error = null) {
		return this.request('get', url, {}, response, error)
	},

	post (url, data, response = null, error = null) {
		return this.request('post', url, data, response, error)
	},

	put (url, data, response = null, error = null) {
		return this.request('put', url, data, response, error)
	},

	delete (url, data = {}, response = null, error = null) {
		return this.request('delete', url, data, response, error)
	},
	
	init() {
		axios.interceptors.request.use(config => {
			config.headers.Authorization = "Bearer "+JSON.parse(window.localStorage.getItem('authUser')).access_token
			return config
		})
	}
}