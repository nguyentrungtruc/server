import axios from 'axios'

const state = {
	types      : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_TYPE(state, payload) {
		state.types = [...payload]
	},
	LOADING_TYPE(state) {
		state.loading = !state.loading
	},
	OPEN_TYPE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_TYPE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_TYPE(state, payload) {
		state.editedIndex = state.types.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_TYPE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.types[state.editedIndex], payload)	
		} else {
			state.types.unshift(payload)
		}
	},
	REMOVE_TYPE(state, payload) {
        state.types.splice(state.types.indexOf(payload), 1)
	},
	CLEAR_TYPE(state) {
		state.types = []
	}
}

const actions = {
	fetchType: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `Type/Fetch`
        if(!state.loading) {
            commit('LOADING_TYPE')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_TYPE', response.data.types)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_TYPE'))
        }
	}),
    editType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('EDIT_TYPE', type)
        commit('OPEN_TYPE_DIALOG')
        resolve(true)
    }),
    updateType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('UPDATE_TYPE', type)
        resolve(true)
    }),
    removeType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('REMOVE_TYPE', type)
	}),
	clearType: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_TYPE')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}