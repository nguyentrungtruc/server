import Vue from 'vue'
import axios from 'axios'
import Auth from '@/utils/auth'
Vue.use(Auth)
export const http = {
	init() {
		axios.interceptors.request.use(config => {
			config.headers.common        = {'Accept' : 'application/json', 'X-Requested-With' : 'XMLHttpRequest'}
			config.headers.Authorization = `Bearer `+ Vue.auth.getToken()
			return config
		})

		axios.interceptors.response.use(response => {
			const token = response.headers['Authorization'] || response.data['token']
			token && window.localStorage.setItem('jwt_admin', token)
			return response
		},function (error) {
			if(error.response.status === 401) {
				window.localStorage.removeItem('jwt_admin');
			}
			return Promise.reject(error);
		})
	}
}