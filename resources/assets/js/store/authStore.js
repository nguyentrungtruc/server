import {apiDomain, loginUrl, logoutUrl, adminUrl, getHeader} from '@/config/config.js'
import axios from 'axios'
const state = {
	isAuth:!!localStorage.getItem('authUser'),
	authUser: null,
	loading: false,
	notifications: null,
	token: !!localStorage.getItem('authUser') ? JSON.parse(localStorage.getItem('authUser')).access_token : null
}

const mutations = {
	SET_AUTH_USER(state, payload) {
		state.authUser = payload.data
		state.isAuth   = true
	},
	CLEAR_AUTH_USER(state) {
		state.authUser = null
		state.isAuth   = false
		window.localStorage.removeItem('authUser')
	},
	LOADING_AUTH(state) {
		state.loading = !state.loading
	},
	ADD_NOTIFICATION(state, payload) {
		state.notifications = payload
	}
}

const actions = {
	getAuth: ({commit}) => new Promise((resolve, reject) => {
		var vm = this
		const data = []
		axios.post('/api/DOFUU-AUTH/8420be8df65a43e246a0a6215c5ed977bb099cb8/me', data, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('SET_AUTH_USER', response.data)
			}
			resolve(response)
		}).catch(errors => {
			// window.location.href = '/'
			reject(errors)
		})
	}),
	logout: ({commit}) => new Promise((resolve, reject) => {
		const data = []
		axios.post('/api/DOFUU-AUTH/8420be8df65a43e246a0a6215c5ed977bb099cb8/logout', data, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('CLEAR_AUTH_USER')
			}
			resolve(response)
		}).catch(error => {
			reject(error)
		})
	})
}

const getters = {
	token: (state) => {
		return state.token
	},
	notifications: (state) => {
		return state.notifications
	}
}

export default {
	state, mutations, actions, getters
}