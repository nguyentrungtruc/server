import axios from 'axios'

const state = {
	roles      : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_ROLE(state, payload) {
		state.roles = [...payload]
	},
	LOADING_ROLE(state) {
		state.loading = !state.loading
	},
	OPEN_ROLE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_ROLE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_ROLE(state, payload) {
		state.editedIndex = state.roles.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_ROLE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.roles[state.editedIndex], payload)	
		} else {
			state.roles.unshift(payload)
		}
	},
	REMOVE_ROLE(state, payload) {
        state.roles.splice(state.roles.indexOf(payload), 1)
	},
	CLEAR_ROLE(state) {
		state.roles = []
	}
}

const actions = {
	fetchRole: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `Role/Fetch`
        if(!state.loading) {
            commit('LOADING_ROLE')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
                    commit('FETCH_ROLE', response.data.roles)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_ROLE'))
        }
	}),
    editRole: ({commit}, role) => new Promise((resolve, reject) => {
        commit('EDIT_ROLE', role)
        commit('OPEN_ROLE_DIALOG')
        resolve(true)
    }),
    updateRole: ({commit}, role) => new Promise((resolve, reject) => {
        commit('UPDATE_ROLE', role)
        resolve(true)
    }),
    removeRole: ({commit}, role) => new Promise((resolve, reject) => {
        commit('REMOVE_ROLE', role)
	}),
	clearRole: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_ROLE')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}