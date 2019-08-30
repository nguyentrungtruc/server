import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import {localURL, baseURL} from '@/config'

axios.defaults.baseURL = localURL;
axios.interceptors.response.use(function (response) {
	return Promise.resolve(response)
}, function (error) {
	if(error.response.status === 401) {
		window.localStorage.removeItem('jwt_admin')	
		window.location.href = '/'
	}	
	return Promise.reject(error)
})

Vue.use(VueAxios, axios)
