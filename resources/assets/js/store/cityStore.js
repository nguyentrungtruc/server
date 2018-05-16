import axios from 'axios'
import {cityUrl, citiesDoesntHaveDeliveryUrl, getHeader} from '@/config/config.js'

const state = {
	cities: {},
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
	FETCH_CITY(state, payload) {
		state.cities = payload.data
	},
	LOADING_CITY(state) {
		state.loading = !state.loading
	},
	DIALOG_CITY(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_CITY(state, payload) {
		state.editedIndex = state.cities.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_CITY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.cities[state.editedIndex], payload.data)
		} else {
			state.cities.push((payload.data))
		}
	},
	REMOVE_CITY(state, payload) {
		var cities = state.cities
		cities.splice(cities.indexOf(payload), 1)
	},
	ALERT_CITY(state, payload) {
		state.alert = Object.assign(state.alert, payload)
		setTimeout(() => {
			state.alert = {
				alert: false,
				messages: '',
				type: 'success'
			}
		}, 5000)
	},
	CLEAR_CITY(state) {
		state.cities = {}
	}
}

const actions = {
	fetchCity: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get(cityUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_CITY', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	fetchCitiesDoesntHaveDelivery: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-CitiesDoesntHaveDelivery', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_CITY', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addCity: ({commit}, payload) => new Promise((resolve, reject) => {
		axios.post(cityUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_CITY', response.data)
				commit('ALERT_CITY', {alert:true, messages: payload.name+' city has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_CITY', {alert:true, messages: payload.name+' city has already been taken.', type: 'error'})
			}
		})
	}),
	editCity: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_CITY', payload)
	}),
	updateCity: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(cityUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_CITY', response.data)
				commit('ALERT_CITY', {alert:true, messages: payload.name+' city has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_CITY', {alert:true, messages: payload.name+' city has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteCity: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(cityUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_CITY', payload)
				commit('ALERT_CITY', {alert:true, messages: payload.name+' city has been deleted.', type: 'success'})
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