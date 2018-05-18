import {getHeader} from '@/config/config.js'
import axios from 'axios'
const state = {
	stores: {},
	store: {},
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
	FETCH_STORE (state, payload) {
		state.stores = payload.data
	},
	
	LOADING_STORE(state) {
		state.loading = !state.loading
	},
	
	DIALOG_STORE(state) {
		state.dialog = !state.dialog
	},

	DIALOG_STORE_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	
	SHOW_STORE(state, payload) {
		state.store = Object.assign({}, payload.data)
	},

	EDIT_STORE(state, payload) {
		state.editedIndex = state.stores.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	
	UPDATE_STORE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.stores[state.editedIndex], payload.data)	
		} else {
			state.stores.unshift(payload.data)
		}

	},

	ALERT_STORE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	}
}

const actions = {
	fetchStore: ({commit}) => new Promise((resolve, reject) => {
		var vm = this
		axios.get('/api/Dofuu-Store', {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('FETCH_STORE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			resolve(errors)
		})
	}),
	addStore: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		console.log(payload)
		axios.post('/api/Dofuu-Store', payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_STORE', response.data)
				commit('ALERT_STORE', {alert:true, messages: payload.name+' store has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			commit('ALERT_STORE', {alert:true, messages: payload.name+' is already taken.', type: 'success'})
			reject(errors)
		})
	}),
	showStore: ({commit}, id) => new Promise((resolve, reject) => {
		var vm = this 
		axios.get('/api/Dofuu-Store/'+id, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('SHOW_STORE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	editStore: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_STORE', payload)
	}),
	updateStore: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.put('/api/Dofuu-Store/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_STORE', response.data)
				commit('ALERT_STORE', {alert:true, messages: payload.name+ ' store has been updated', type: 'success'})
			} 
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	deleteStore: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Store/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_STORE', payload)
				commit('ALERT_STORE', {alert:true, messages: payload.name+' name has been deleted.', type: 'success'})
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