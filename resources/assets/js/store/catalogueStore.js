import {apiDomain, catalogueUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	catalogues: {},
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
	FETCH_CATALOGUE(state, payload) {
		state.catalogues = payload.data
	},
	LOADING_CATALOGUE(state) {
		state.loading = !state.loading
	},
	DIALOG_CATALOGUE(state) {
		state.dialog = !state.dialog
	},
	DIALOG_CATALOGUE_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_CATALOGUE(state, payload) {
		state.editedIndex = state.catalogues.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_CATALOGUE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.catalogues[state.editedIndex], payload.data)
		} else {
			state.catalogues.push((payload.data))
		}
	},
	REMOVE_CATALOGUE(state, payload) {
		var catalogues = state.catalogues
		catalogues.splice(catalogues.indexOf(payload), 1)
	},
	ALERT_CATALOGUE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_CATALOGUE_CLOSE(state) {
		state.alert = {
			alert: false,
			messages: '',
			type: 'success'
		}
	}

}

const actions = {
	fetchCatalogue: ({commit, state}, sid) => new Promise((resolve, reject) => {
		axios.get(catalogueUrl+'/'+sid, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_CATALOGUE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addCatalogue: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post(catalogueUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_CATALOGUE', response.data)
				commit('ALERT_CATALOGUE', {alert:true, messages: payload.catalogue+' catalogue has been added.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_CATALOGUE_CLOSE')
				}, 5000)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_CATALOGUE', {alert:true, messages: payload.catalogue+' catalogue has already been taken.', type: 'error'})
			}
		})
	}),
	editCatalogue: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_CATALOGUE', payload)
	}),
	updateCatalogue: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(catalogueUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_CATALOGUE', response.data)
				commit('ALERT_CATALOGUE', {alert:true, messages: payload.catalogue+' catalogue has been edited.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_CATALOGUE_CLOSE')
				}, 5000)
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_CATALOGUE', {alert:true, messages: payload.catalogue+' catalogue has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteCatalogue: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(catalogueUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_CATALOGUE', payload)
				commit('ALERT_CATALOGUE', {alert:true, messages: payload.catalogue+' catalogue has been deleted.', type: 'success'})
				setTimeout(() => {
					commit('ALERT_CATALOGUE_CLOSE')
				}, 5000)
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