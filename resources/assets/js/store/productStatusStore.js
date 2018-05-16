import {productStatusUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	productStatus: {},
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
	FETCH_PRODUCT_STATUS(state, payload) {
		state.productStatus = payload
	},
	LOADING_PRODUCT_STATUS(state) {
		state.loading = !state.loading
	},
	DIALOG_PRODUCT_STATUS(state) {
		state.dialog = !state.dialog
	},
	DIALOG_PRODUCT_STATUS_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_PRODUCT_STATUS(state, payload) {
		state.editedIndex = state.productStatus.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_PRODUCT_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.productStatus[state.editedIndex], payload)
		} else {
			state.productStatus.push((payload))
		}
	},
	REMOVE_PRODUCT_STATUS(state, payload) {
		var productStatus = state.productStatus
		productStatus.splice(productStatus.indexOf(payload), 1)
	},
	ALERT_PRODUCT_STATUS(state, payload) {
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
	fetchProductStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get(productStatusUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_PRODUCT_STATUS', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addProductStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post(productStatusUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_PRODUCT_STATUS', response.data)
				commit('ALERT_PRODUCT_STATUS', {alert:true, messages: payload.product_status_name+' status has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_PRODUCT_STATUS', {alert:true, messages: payload.product_status_name+' status has already been taken.', type: 'error'})
			}
		})
	}),
	editProductStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_PRODUCT_STATUS', payload)
	}),
	updateProductStatus: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(productStatusUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_PRODUCT_STATUS', response.data)
				commit('ALERT_PRODUCT_STATUS', {alert:true, messages: payload.product_status_name+' status has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_PRODUCT_STATUS', {alert:true, messages: payload.product_status_name+' status has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteProductStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(productStatusUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_PRODUCT_STATUS', payload)
				commit('ALERT_PRODUCT_STATUS', {alert:true, messages: payload.product_status_name+' status has been deleted.', type: 'success'})
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