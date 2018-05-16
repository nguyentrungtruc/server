import {apiDomain, activityUrl, getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	activities: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	alert: {
		show: false,
		messages: '',
		type: 'success'
	}
}

const mutations = {
	FETCH_ACTIVITY(state, payload) {
		state.activities = payload.data
	},
	LOADING_ACTIVITY(state) {
		state.loading = !state.loading
	},
	DIALOG_ACTIVITY(state) {
		state.dialog = true
	},
	DIALOG_CLOSE_ACTIVITY(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_ACTIVITY(state, payload) {
		state.editedIndex = state.activities.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	}, 
	UPDATE_ACTIVITY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.activities[state.editedIndex], payload.data)
		} else {
			state.activities.push(payload.data)
		}
	},
	REMOVE_ACTIVITY(state, payload) {
		var activities = state.activities
		activities.splice(activities.indexOf(payload), 1)
	},
	ALERT_ACTIVITY(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	},
	ALERT_ACTIVITY_CLOSE(state, payload) {
		alert = {
			show: false,
			messages: '',
			type: 'success'
		}
	}

}

const actions = {
	fetchActivity: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get(activityUrl, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_ACTIVITY', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addActivity: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post(activityUrl, payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_ACTIVITY', response.data)
				commit('ALERT_ACTIVITY', {show: true, messages: payload.activity+' activity has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_ACTIVITY', {show: true, messages: payload.activity+' activity has already been taken.', type: 'error'})
			}
		})
	}),
	editActivity: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_ACTIVITY', payload)
	}),
	updateActivity: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put(activityUrl+'/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('UPDATE_ACTIVITY', response.data)
				commit('ALERT_ACTIVITY', {show: true, messages: payload.activity+' activity has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_ACTIVITY', {show: true, messages: payload.activity+' activity has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteActivity: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete(activityUrl+'/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_ACTIVITY', payload)
				commit('ALERT_ACTIVITY', {show: true, messages: payload.activity+' activity has been deleted.', type: 'success'})
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