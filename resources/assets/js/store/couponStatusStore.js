import {getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	couponStatus: {},
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
	FETCH_COUPON_STATUS(state, payload) {
		state.couponStatus = payload
	},
	LOADING_COUPON_STATUS(state) {
		state.loading = !state.loading
	},
	DIALOG_COUPON_STATUS(state) {
		state.dialog = !state.dialog
	},
	DIALOG_COUPON_STATUS_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_COUPON_STATUS(state, payload) {
		state.editedIndex = state.couponStatus.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_COUPON_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.couponStatus[state.editedIndex], payload)
		} else {
			state.couponStatus.push((payload))
		}
	},
	REMOVE_COUPON_STATUS(state, payload) {
		var couponStatus = state.couponStatus
		couponStatus.splice(couponStatus.indexOf(payload), 1)
	},
	ALERT_COUPON_STATUS(state, payload) {
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
	fetchCouponStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Coupon-Status', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_COUPON_STATUS', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addCouponStatus: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Coupon-Status', payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_COUPON_STATUS', response.data)
				commit('ALERT_COUPON_STATUS', {alert:true, messages: payload.coupon_status_name+' status has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_COUPON_STATUS', {alert:true, messages: payload.coupon_status_name+' status has already been taken.', type: 'error'})
			}
		})
	}),
	editCouponStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_COUPON_STATUS', payload)
	}),
	updateCouponStatus: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Coupon-Status/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_COUPON_STATUS', response.data)
				commit('ALERT_COUPON_STATUS', {alert:true, messages: payload.coupon_status_name+' status has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_COUPON_STATUS', {alert:true, messages: payload.coupon_status_name+' status has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteCouponStatus: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Coupon-Status/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_COUPON_STATUS', payload)
				commit('ALERT_COUPON_STATUS', {alert:true, messages: payload.coupon_status_name+' status has been deleted.', type: 'success'})
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