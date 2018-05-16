import {orderStatusUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	orderStatus: {},
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
	FETCH_ORDER_STATUS(state, payload) {
		state.orderStatus = payload.data
	},
	LOADING_ORDER_STATUS(state) {
		state.loading = !state.loading
	},
	DIALOG_ORDER_STATUS(state) {
		state.dialog = !state.dialog
	},
	DIALOG_ORDER_STATUS_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_ORDER_STATUS(state, payload) {
		state.editedIndex = state.orderStatus.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_ORDER_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.orderStatus[state.editedIndex], payload.data)
		} else {
			state.orderStatus.push((payload.data))
		}
	},
	REMOVE_ORDER_STATUS(state, payload) {
		var orderStatus = state.orderStatus
		orderStatus.splice(orderStatus.indexOf(payload), 1)
	},
	ALERT_ORDER_STATUS(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_ORDER_STATUS_CLOSE(state, payload) {
		state.alert = {
			show: false,
			message: '',
			type: 'success'
		}
	}

}

const actions = {
	fetchOrderStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Status/Order', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_ORDER_STATUS', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addOrderStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Status/Order', payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_ORDER_STATUS', response.data)
				commit('ALERT_ORDER_STATUS', {show:true, message: response.data.message , type: 'success'})
				setTimeout(() => {
					commit('ALERT_ORDER_STATUS_CLOSE')
				}, 3000)
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_ORDER_STATUS', {show:true, message: payload.name+' status has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	editOrderStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_ORDER_STATUS', payload)
	}),
	updateOrderStatus: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Status/Order/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_ORDER_STATUS', response.data)
				commit('ALERT_ORDER_STATUS', {show:true, message: response.data.message, type: 'success'})
				setTimeout(() => {
					commit('ALERT_ORDER_STATUS_CLOSE')
				}, 3000)
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_ORDER_STATUS', {show:true, message: payload.name+' status has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteOrderStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Status/Order/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_ORDER_STATUS', payload)
				commit('ALERT_ORDER_STATUS', {show:true, message: payload.name+' status has been deleted.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_ORDER_STATUS_CLOSE')
				}, 3000)
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