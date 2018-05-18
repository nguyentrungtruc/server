import {getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	types: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	alert: {
		alert: false,
		messages: '',
		type: 'success'
	}
}

const mutations = {
	FETCH_TYPE(state, payload) {
		state.types = payload
	},
	LOADING_TYPE(state) {
		state.loading = !state.loading
	},
	DIALOG_TYPE(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_TYPE(state, payload) {
		state.editedIndex = state.types.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_TYPE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.types[state.editedIndex], payload)
		} else {
			state.types.push((payload))
		}
	},
	REMOVE_TYPE(state, payload) {
		var types = state.types
		types.splice(types.indexOf(payload), 1)
	},
	ALERT_TYPE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
		setTimeout(() => {
			state.alert = {
				alert: false,
				messages: '',
				type: 'success'
			}
		}, 5000)
	}

}

const actions = {
	fetchType: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Type', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_TYPE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addType: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Type', payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_TYPE', response.data)
				commit('ALERT_TYPE', {alert:true, messages: payload.type_name+' type has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_TYPE', {alert:true, messages: payload.type_name+' type has already been taken.', type: 'error'})
			}
		})
	}),
	editType: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_TYPE', payload)
	}),
	updateType: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Type/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_TYPE', response.data)
				commit('ALERT_TYPE', {alert:true, messages: payload.type_name+' type has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_TYPE', {alert:true, messages: payload.type_name+' type has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteType: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Type/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_TYPE', payload)
				commit('ALERT_TYPE', {alert:true, messages: payload.type_name+' type has been deleted.', type: 'success'})
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