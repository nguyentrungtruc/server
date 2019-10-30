import {RepositoryFactory} from '@/services/Repository/index'
const SizeRepository = RepositoryFactory.get('sizes')

const state = {
	sizes      : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_SIZE(state, payload) {
		state.sizes = [...payload]
	},
	LOADING_SIZE(state) {
		state.loading = !state.loading
	},
	OPEN_SIZE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_SIZE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_SIZE(state, payload) {
		state.editedIndex = state.sizes.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_SIZE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.sizes[state.editedIndex], payload)	
		} else {
			state.sizes.unshift(payload)
		}
	},
	REMOVE_SIZE(state, payload) {
        state.sizes.splice(state.sizes.indexOf(payload), 1)
	},
	CLEAR_SIZE(state) {
		state.sizes = []
	}
}

const actions = {
	fetchSize: ({commit, state}) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_SIZE')
            SizeRepository.get().then(response => {
                if(response.status === 200) {
                    commit('FETCH_SIZE', response.data.sizes)
                }
                resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_SIZE'))
        }
	}),
    editSize: ({commit}, size) => new Promise((resolve) => {
        commit('EDIT_SIZE', size)
        commit('OPEN_SIZE_DIALOG')
        resolve(true)
    }),
    updateSize: ({commit}, size) => new Promise((resolve) => {
        commit('UPDATE_SIZE', size)
        resolve(true)
    }),
    removeSize: ({commit}, size) => new Promise((resolve) => {
		commit('REMOVE_SIZE', size)
		resolve(true)
	}),
	clearSize: ({commit}) => {
		commit('CLEAR_SIZE')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}