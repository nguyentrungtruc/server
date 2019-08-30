import axios from 'axios'

const state = {
	status     : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {}
}

const mutations = {
	FETCH_STORE_STATUS(state, payload) {
		state.status = [...payload]
	},
	LOADING_STORE_STATUS(state) {
		state.loading = !state.loading
	},
	OPEN_STORE_STATUS_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_STORE_STATUS_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_STORE_STATUS(state, payload) {
		state.editedIndex = state.status.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_STORE_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.status[state.editedIndex], payload)	
		} else {
			state.status.unshift(payload)
		}
	},
	REMOVE_STORE_STATUS(state, payload) {
        state.status.splice(state.status.indexOf(payload), 1)
	},
	CLEAR_STORE_STATUS(state) {
		state.status = []
	}
}

const actions = {
	fetchStoreStatus: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `StoreStatus/Fetch`
        if(!state.loading) {
            commit('LOADING_STORE_STATUS')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_STORE_STATUS', response.data.status)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_STORE_STATUS'))
        }
	}),
    editStoreStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('EDIT_STORE_STATUS', status)
        commit('OPEN_STORE_STATUS_DIALOG')
        resolve(true)
    }),
    updateStoreStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('UPDATE_STORE_STATUS', status)
        resolve(true)
    }),
    removeStoreStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('REMOVE_STORE_STATUS', status)
	}),
	clearStoreStatus({commit}) {
		commit('CLEAR_STORE_STATUS')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}