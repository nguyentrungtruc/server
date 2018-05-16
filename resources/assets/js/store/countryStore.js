import {apiDomain, countryUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	countries: {},
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
	FETCH_COUNTRY(state, payload) {
		state.countries = payload
	},
	LOADING_COUNTRY(state) {
		state.loading = !state.loading
	},
	DIALOG_COUNTRY(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_COUNTRY(state, payload) {
		state.editedIndex = state.countries.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_COUNTRY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.countries[state.editedIndex], payload)
		} else {
			state.countries.push((payload))
		}
	},
	REMOVE_COUNTRY(state, payload) {
		var countries = state.countries
		countries.splice(countries.indexOf(payload), 1)
	},
	ALERT_COUNTRY(state, payload) {
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
	fetchCountry: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get(countryUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_COUNTRY', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addCountry: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post(countryUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_COUNTRY', response.data)
				commit('ALERT_COUNTRY', {alert:true, messages: payload.country_name+' country has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_COUNTRY', {alert:true, messages: payload.country_name+' country has already been taken.', type: 'error'})
			}
		})
	}),
	editCountry: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_COUNTRY', payload)
	}),
	updateCountry: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(countryUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_COUNTRY', response.data)
				commit('ALERT_COUNTRY', {alert:true, messages: payload.country_name+' country has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_COUNTRY', {alert:true, messages: payload.country_name+' country has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteCountry: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(countryUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_COUNTRY', payload)
				commit('ALERT_COUNTRY', {alert:true, messages: payload.country_name+' country has been deleted.', type: 'success'})
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