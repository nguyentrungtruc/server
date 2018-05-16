import {apiDomain, countryUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	payments: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	alert: {
		show: false,
		message: '',
		type: 'success'
	}
}

const mutations = {
	FETCH_PAYMENT(state, payload) {
		state.payments = payload.data
	},
	LOADING_PAYMENT(state) {
		state.loading = !state.loading
	},
	DIALOG_PAYMENT(state) {
		state.dialog = !state.dialog
	},
	DIALOG_PAYMENT_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_PAYMENT(state, payload) {
		state.editedIndex = state.payments.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_PAYMENT(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.payments[state.editedIndex], payload.data)
		} else {
			state.payments.push((payload.data))
		}
	},
	REMOVE_PAYMENT(state, payload) {
		var payments = state.payments
		payments.splice(payments.indexOf(payload), 1)
	},
	ALERT_PAYMENT(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_PAYMENT_CLOSE(state, payload) {
		state.alert = {
			show: false,
			messages: '',
			type: 'success'
		}
	}

}

const actions = {
	fetchPayment: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Payment/Method', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_PAYMENT', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addPayment: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Payment/Method', payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_PAYMENT', response.data)
				commit('ALERT_PAYMENT', {show:true, message: response.data.message, type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_PAYMENT', {show:true, message: payload.paymentName+' payment has already been taken.', type: 'error'})
			}
		})
	}),
	editPayment: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_PAYMENT', payload)
	}),
	updatePayment: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Payment/Method/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_PAYMENT', response.data)
				commit('ALERT_PAYMENT', {show:true, message: response.data.message, type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_PAYMENT', {show:true, message: payload.paymentName+' payment has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deletePayment: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Payment/Method/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_PAYMENT', payload)
				commit('ALERT_PAYMENT', {show:true, message: payload.paymentName+' payment has been deleted.', type: 'success'})
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