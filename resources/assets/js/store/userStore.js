import {apiDomain, userUrl, getHeader} from '@/config/config.js'
import axios from 'axios'
const state = {
	users: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	alert: {
		type:Array,
		alert: false,
		messages: '',
		type: 'success'
	}
}

const mutations = {
	
	FETCH_USER (state, payload) {
		state.users = payload
	},
	
	LOADING_USER(state) {
		state.loading = !state.loading
	},
	
	DIALOG_USER(state) {
		state.dialog = !state.dialog
	},

	DIALOG_USER_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	
	EDIT_USER(state, payload) {
		state.editedIndex = state.users.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	
	UPDATE_USER(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.users[state.editedIndex], payload)	
		} else {
			state.users.unshift(payload)
		}

	},
	
	REMOVE_USER(state, payload) {
		var users = state.users
		users.splice(users.indexOf(payload), 1)
	},

	ALERT_USER(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	}
}

const actions = {
	fetchUser: ({commit}) => new Promise((resolve, reject) => {
		var vm = this
		axios.get('/api/Dofuu-User', {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('FETCH_USER', response.data)
			}
			resolve(response)
		}).catch(errors => {
			resolve(errors)
		})
	}),
	addUser: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.post('/api/Dofuu-User', payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_USER', response.data)
				commit('ALERT_USER', {alert:true, messages: payload.email+' email has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			commit('ALERT_USER', {alert:true, messages: payload.email+' is already taken.', type: 'success'})
			reject(errors)
		})
	}),
	editUser: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_USER', payload)
	}),
	updateUser: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this 
		axios.put('/api/Dofuu-User/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_USER', response.data)
				commit('ALERT_USER', {alert:true, messages: payload.email+ ' account has been updated', type: 'success'})
			} 
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	deleteUser: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-User/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_USER', payload)
				commit('ALERT_USER', {alert:true, messages: payload.email+' account has been deleted.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}