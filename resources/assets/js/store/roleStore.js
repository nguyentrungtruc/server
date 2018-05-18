import {apiDomain, loginUrl, roleUrl, getHeader} from '@/config/config.js'
import axios from 'axios'
const state = {
	roles: {},
	loading: false,
	dialog: false,
	editedIndex: -1,
	editedItem: {},
	alert: {
		type:Array,
		alert: false,
		messages: '',
		type: 'success'
	}
}

const mutations = {
	FETCH_ROLE (state, payload) {
		state.roles = payload
	},

	LOADING_ROLE(state) {
		state.loading = !state.loading
	},

	DIALOG_ROLE(state) {
		state.dialog = !state.dialog
	},

	DIALOG_CLOSE(state) {
		state.dialog = false
		state.editedIndex = -1
	},

	EDIT_ROLE(state, payload) {
		state.editedIndex = state.roles.indexOf(payload)
		state.editedItem = Object.assign({}, payload)
		state.dialog = true
	},
	
	UPDATE_ROLE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.roles[state.editedIndex], payload)
		} else {
			state.roles.push((payload))
		}
	},
	REMOVE_ROLE(state, payload) {
		var roles = state.roles
		roles.splice(roles.indexOf(payload), 1)
	},
	ALERT_ROLE(state, payload) {
		state.alert = Object.assign(state.alert, payload)
	}
}

const actions = {
	fetchRole: ({commit}) => new Promise((resolve, reject) => {
		var vm = this
		axios.get('/api/Dofuu-Role', {headers: getHeader()}).then(response => {
			if(response.status == 200) {
				commit('FETCH_ROLE', response.data)
			}
			resolve(response)
		}).catch(errors => {
			resolve(errors)
		})
	}),
	addRole: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this 
		axios.post('/api/Dofuu-Role', payload, {headers: getHeader()}).then(response => {
			if(response.status === 200) {
				commit('UPDATE_ROLE', response.data)
				commit('ALERT_ROLE', {alert:true, messages: payload.name+' role has been added.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			commit('ALERT_ROLE', {alert:true, messages: errors.response.data.name[0], type: 'error'})
			reject(errors)
		})
	}),
	editRole: ({commit}, payload) => new Promise((resolve, reject) => {
		commit('EDIT_ROLE', payload)
	}),
	updateRole: ({commit}, payload) => new Promise((resolve, reject) =>  {
		var vm = this 
		axios.put('/api/Dofuu-Role/'+payload.id, payload, {headers: getHeader()}).then(response => {
			if(response.status == 201) {
				commit('UPDATE_ROLE', response.data)
				commit('ALERT_ROLE', {alert:true, messages: payload.name+' role has been edited.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	}),
	deleteRole: ({commit}, payload) => new Promise((resolve, reject) => {
		var vm = this
		axios.delete('/api/Dofuu-Role/'+payload.id, {headers: getHeader()}).then(response => {
			if(response.status == 204) {
				commit('REMOVE_ROLE', payload)
				commit('ALERT_ROLE', {alert:true, messages: payload.name+' role has been deleted.', type: 'success'})
			}
			resolve(response)
		}).catch(errors => {
			reject(errors)
		})
	})
}

const getters = {
	roleAll:(state, getters) => {
		if(state.roles.length>0) {
			return state.roles
		} else {
			return []
		}
	},
	roleNotPartner: (state, getters) => {
		const partner = new String("partner").toLowerCase()
		if (state.roles.length > 0) {
			return state.roles.filter(role => new String(role.name).toLowerCase() != partner)
		} else {
			return state.roles
		}
	}
}

export default {
	state, mutations, actions, getters
}