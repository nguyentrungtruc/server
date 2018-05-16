import Vue from 'vue'

import axios from 'axios'
import VueAxios from 'vue-axios'

axios.interceptors.response.use(function (response) {
	return Promise.resolve(response)
}, function (error) {
	if(error.response.status === 401) {
		window.location.href = '/'
		window.localStorage.removeItem('authUser')	
	}	
	return Promise.reject(error)
})

Vue.use(VueAxios, axios)
