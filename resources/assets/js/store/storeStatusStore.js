import {getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	storeStatus: {},
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
	FETCH_STORE_STATUS(state, payload) {
		state.storeStatus = payload
	},
	LOADING_STORE_STATUS(state) {
		state.loading = !state.loading
	},
	DIALOG_STORE_STATUS(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_STORE_STATUS(state, payload) {
		state.editedIndex = state.storeStatus.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_STORE_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.storeStatus[state.editedIndex], payload)
		} else {
			state.storeStatus.push((payload))
		}
	},
	REMOVE_STORE_STATUS(state, payload) {
		var storeStatus = state.storeStatus
		storeStatus.splice(storeStatus.indexOf(payload), 1)
	},
	ALERT_STORE_STATUS(state, payload) {
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
	fetchStoreStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Store-Status', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_STORE_STATUS', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addStoreStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Store-Status', payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_STORE_STATUS', response.data)
				commit('ALERT_STORE_STATUS', {alert:true, messages: payload.store_status_name+' status has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_STORE_STATUS', {alert:true, messages: payload.store_status_name+' status has already been taken.', type: 'error'})
			}
		})
	}),
	editStoreStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_STORE_STATUS', payload)
	}),
	updateStoreStatus: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Store-Status/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_STORE_STATUS', response.data)
				commit('ALERT_STORE_STATUS', {alert:true, messages: payload.store_status_name+' status has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_STORE_STATUS', {alert:true, messages: payload.store_status_name+' status has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteStoreStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Store-Status/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_STORE_STATUS', payload)
				commit('ALERT_STORE_STATUS', {alert:true, messages: payload.store_status_name+' status has been deleted.', type: 'success'})
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