import axios from 'axios'

const state = {
	activities : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {}
}

const mutations = {
	FETCH_ACTIVITY(state, payload) {
		state.activities = [...payload]
	},
	LOADING_ACTIVITY(state) {
		state.loading = !state.loading
	},
	OPEN_ACTIVITY_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_ACTIVITY_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_ACTIVITY(state, payload) {
		state.editedIndex = state.activities.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_ACTIVITY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.activities[state.editedIndex], payload)	
		} else {
			state.activities.unshift(payload)
		}
	},
	REMOVE_ACTIVITY(state, payload) {
        state.activities.splice(state.activities.indexOf(payload), 1)
	},
	CLEAR_ACTIVITY(state) {
		state.activities = []
	}
}

const actions = {
	fetchActivity: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `Activity/Fetch`
        if(!state.loading) {
            commit('LOADING_ACTIVITY')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_ACTIVITY', response.data.activities)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_ACTIVITY'))
        }
	}),
    editActivity: ({commit}, activity) => new Promise((resolve, reject) => {
        commit('EDIT_ACTIVITY', activity)
        commit('OPEN_ACTIVITY_DIALOG')
        resolve(true)
    }),
    updateActivity: ({commit}, activity) => new Promise((resolve, reject) => {
        commit('UPDATE_ACTIVITY', activity)
        resolve(true)
    }),
    removeActivity: ({commit}, activity) => new Promise((resolve, reject) => {
        commit('REMOVE_ACTIVITY', activity)
        resolve(true)
	}),
	clearActivity: ({commit}) => new Promise((resolve, reject) => {
        commit('CLEAR_ACTIVITY')
        resolve(true)
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}