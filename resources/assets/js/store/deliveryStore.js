import {deliveryUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	deliveries: {},
	delivery: {},
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
	FETCH_DELIVERY(state, payload) {
		state.deliveries = payload.data
	},
	LOADING_DELIVERY(state) {
		state.loading = !state.loading
	},
	EDIT_DELIVERY(state, payload) {
		state.editedIndex = state.deliveries.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
	},
	SHOW_DELIVERY(state, payload) {
		state.coupon = Object.assign({}, payload)
	},
	UPDATE_DELIVERY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.deliveries[state.editedIndex], payload.data)
		} else {
			state.deliveries.push((payload.data))
		}
	},
	REMOVE_DELIVERY(state, payload) {
		var deliveries = state.deliveries
		deliveries.splice(deliveries.indexOf(payload), 1)
	},
	DIALOG_DELIVERY(state) {
		state.dialog = true
	},
	DIALOG_DELIVERY_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	ALERT_DELIVERY(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_DELIVERY_CLOSE(state) {
		state.alert = {
			alert: false,
			messages: '',
			type: 'success'
		}	
	},
	CLEAR_DELIVERY(state) {
		state.deliveries = {}
	}
}

const actions = {
	fetchDelivery: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get(deliveryUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_DELIVERY', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	editDelivery: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_DELIVERY', payload)
	}),
	updateDelivery: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(deliveryUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_DELIVERY', response.data)
				commit('ALERT_DELIVERY', {alert:true, messages: response.data.data.name+' delivery has been edited.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_DELIVERY_CLOSE')
				}, 5000)
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_DELIVERY', {alert:true, messages: ' delivery has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteDelivery: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(deliveryUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_DELIVERY', payload)
				commit('ALERT_DELIVERY', {alert:true, messages: 'delivery has been deleted.', type: 'success'})
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