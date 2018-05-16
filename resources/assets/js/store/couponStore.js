import {couponUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	coupons: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	coupon: {},
	alert: {
		alert: false,
		messages: '',
		type: 'success'
	}
}

const mutations = {
	FETCH_COUPON(state, payload) {
		state.coupons = payload
	},
	LOADING_COUPON(state) {
		state.loading = !state.loading
	},
	DIALOG_COUPON(state) {
		state.dialog = !state.dialog
	},
	DIALOG_COUPON_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_COUPON(state, payload) {
		state.editedIndex = state.coupons.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	SHOW_COUPON(state, payload) {
		state.coupon = Object.assign({}, payload)
	},
	UPDATE_COUPON(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.coupons[state.editedIndex], payload)
		} else {
			state.coupons.push((payload))
		}
	},
	REMOVE_COUPON(state, payload) {
		var coupons = state.coupons
		coupons.splice(coupons.indexOf(payload), 1)
	},
	ALERT_COUPON(state, payload) {
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
	fetchCoupon: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get(couponUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_COUPON', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addCoupon: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post(couponUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_COUPON', response.data)
				commit('ALERT_COUPON', {alert:true, messages: payload.title+' coupon has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_COUPON', {alert:true, messages: payload.title+' coupon has already been taken.', type: 'error'})
			}
		})
	}),
	editCoupon: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_COUPON', payload)
	}),
	showCoupon: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get(couponUrl+'/'+payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('SHOW_COUPON', response.data)
			}
		}).catch(errors => {
			reject(errors)
		})
	}),
	updateCoupon: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(couponUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_COUPON', response.data)
				commit('ALERT_COUPON', {alert:true, messages: payload.title+' coupon has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_COUPON', {alert:true, messages: payload.title+' coupon has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteCoupon: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(couponUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_COUPON', payload)
				commit('ALERT_COUPON', {alert:true, messages: payload.title+' coupon has been deleted.', type: 'success'})
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