import axios from 'axios'
import {districtUrl, getHeader} from '@/config/config.js'

const state = {
	districts: {},
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
	FETCH_DISTRICT(state, payload) {
		state.districts = payload
	},
	LOADING_DISTRICT(state) {
		state.loading = !state.loading
	},
	DIALOG_DISTRICT(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_DISTRICT(state, payload) {
		state.editedIndex = state.districts.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_DISTRICT(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.districts[state.editedIndex], payload)
		} else {
			state.districts.push((payload))
		}
	},
	REMOVE_DISTRICT(state, payload) {
		var districts = state.districts
		districts.splice(districts.indexOf(payload), 1)
	},
	ALERT_DISTRICT(state, payload) {
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
	fetchDistrict: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get(districtUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_DISTRICT', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addDistrict: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.post(districtUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_DISTRICT', response.data)
				commit('ALERT_DISTRICT', {alert:true, messages: payload.district_name+' district has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_DISTRICT', {alert:true, messages: payload.district_name+' district has already been taken.', type: 'error'})
			}
		})
	}),
	editDistrict: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_DISTRICT', payload)
	}),
	updateDistrict: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(districtUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_DISTRICT', response.data)
				commit('ALERT_DISTRICT', {alert:true, messages: payload.district_name+' district has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_DISTRICT', {alert:true, messages: payload.district_name+' district has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteDistrict: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(districtUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_DISTRICT', payload)
				commit('ALERT_DISTRICT', {alert:true, messages: payload.district_name+' district has been deleted.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	})
}

const getters = {
	getDistrictAll: (state, getters) => {
		return state.districts
	},
	getDistrictByCityId: (state, getters) => id => {
		return state.districts.filter(district => district.city_id === id)
	},
}

export default {
	state, mutations, actions, getters
}