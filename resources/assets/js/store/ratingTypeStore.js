import { getHeader } from '@/config/config.js'
import axios from 'axios'

const state = {
	ratingTypes: {},
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
	FETCH_RATING_TYPE(state, payload) {
		state.ratingTypes = payload
	},
	LOADING_RATING_TYPE(state) {
		state.loading = !state.loading
	},
	DIALOG_RATING_TYPE(state) {
		state.dialog = !state.dialog
	},
	DIALOG_RATING_TYPE_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},
	EDIT_RATING_TYPE(state, payload) {
		state.editedIndex = state.ratingTypes.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
		state.dialog      = true
	},
	UPDATE_RATING_TYPE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.ratingTypes[state.editedIndex], payload)
		} else {
			state.ratingTypes.push((payload))
		}
	},
	REMOVE_RATING_TYPE(state, payload) {
		var ratingTypes = state.ratingTypes
		ratingTypes.splice(ratingTypes.indexOf(payload), 1)
	},
	ALERT_RATING_TYPE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
		setTimeout(() => {
			state.alert = {
				alert: false,
				messages: '',
				type: 'success'
			}
		}, 5000)
	},
	CLEAR_RATING_TYPE(state) {
		state.ranges = {}
	}
}

const actions = {
	fetchRatingType: ({commit, state}, payload) => new Promise((resolve, reject) => {
		axios.get('/api/Dofuu-Rating-Type', {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('FETCH_RATING_TYPE', response.data.ratingTypes)
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	editRatingType: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_RATING_TYPE', payload)
	})
}

const getters = {
}

export default {
	state, mutations, actions, getters
}