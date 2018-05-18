import {getHeader} from '@/config/config.js'
import axios from 'axios'

const state = {
	ranges: {},
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
	FETCH_RANGE(state, payload) {
		state.ranges = payload
	},
	LOADING_RANGE(state) {
		state.loading = !state.loading
	},
	DIALOG_RANGE(state) {
		state.dialog = !state.dialog
	},
	DIALOG_RANGE_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_RANGE(state, payload) {
		state.editedIndex = state.ranges.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	UPDATE_RANGE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.ranges[state.editedIndex], payload)
		} else {
			state.ranges.push((payload))
		}
	},
	REMOVE_RANGE(state, payload) {
		var ranges = state.ranges
		ranges.splice(ranges.indexOf(payload), 1)
	},
	ALERT_RANGE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
		setTimeout(() => {
			state.alert = {
				alert: false,
				messages: '',
				type: 'success'
			}
		}, 5000)
	},
	CLEAR_RANGE(state) {
		state.ranges = {}
	}
}

const actions = {
	fetchRange: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Range', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_RANGE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	addRange: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.post('/api/Dofuu-Range', payload, {headers: getHeader()}).then(response => {
			if(response.status === 201) {
				commit('UPDATE_RANGE', response.data)
				commit('ALERT_RANGE', {alert:true, messages: 'the range has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
			if(errors.response.status === 422) {
				commit('ALERT_RANGE', {alert:true, messages: 'the range has already been taken.', type: 'error'})
			}
		})
	}),
	editRange: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_RANGE', payload)
	}),
	updateRange: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Range/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_RANGE', response.data)
				commit('ALERT_RANGE', {alert:true, messages:'the range has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			if(errors.response.status == 422) {
				commit('ALERT_RANGE', {alert:true, messages:'the range has already been taken.', type: 'error'})
			}
			reject(errors)
		})
	}),
	deleteRange: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Range/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_RANGE', payload)
				commit('ALERT_RANGE', {alert:true, messages: 'the range has been deleted.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	})
}

const getters = {
	fetchRange: (state, getters) => {
		return state.ranges
	}
}

export default {
	state, mutations, actions, getters
}