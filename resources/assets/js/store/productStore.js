import axios from 'axios'
import {getHeader} from '@/config/config.js'

const state = {
	products: {},
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
	FETCH_PRODUCT(state, payload) {
		state.products = payload.data
	},
	LOADING_PRODUCT(state) {
		state.loading = !state.loading
	},
	DIALOG_PRODUCT(state) {
		state.dialog = !state.dialog
	},
	DIALOG_PRODUCT_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_PRODUCT(state, payload) {
		state.editedIndex = state.products.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_PRODUCT(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.products[state.editedIndex], payload.data)
		} else {
			state.products.push((payload.data))
		}
	},
	REMOVE_PRODUCT(state, payload) {
		var products = state.products
		products.splice(products.indexOf(payload), 1)
	},
	ALERT_PRODUCT(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_PRODUCT_CLOSE(state) {
		state.alert = {
			alert: false,
			messages: '',
			type: 'success'
		}
	}
}

const actions = {
	fetchProduct: ({commit}, sid) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Product/'+sid, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_PRODUCT', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addProduct: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Product', payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_PRODUCT', response.data)
				commit('ALERT_PRODUCT', {alert:true, messages: payload.name+' product has been added.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_PRODUCT_CLOSE')
				},5000)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_PRODUCT', {alert:true, messages: payload.name+' product has already been taken.', type: 'error'})
			}
		})
	}),
	editProduct: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_PRODUCT', payload)
	}),
	updateProduct: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Product/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_PRODUCT', response.data)
				commit('ALERT_PRODUCT', {alert:true, messages: payload.name+' product has been edited.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_PRODUCT_CLOSE')
				},5000)
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_PRODUCT', {alert:true, messages: payload.name+' product has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteProduct: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Product/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_PRODUCT', payload)
				commit('ALERT_PRODUCT', {alert:true, messages: payload.name+' product has been deleted.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_PRODUCT_CLOSE')
				},5000)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	})
}

const getters = {
	districtByCityId: (state, getters) => id => {
		if (state.districts.length > 0) {
			return state.districts.filter(district => district.city_id === id)
		} else {
			return state.districts
		}
	},
}

export default {
	state, mutations, actions, getters
}