import {RepositoryFactory} from '@/services/Repository/index'
const UserRepository = RepositoryFactory.get('users')

const state = {
	users      : [],
	user       : null,
	pagination : null,
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
	search     : {
		keywords: '',
		isActive: null,
		isBan   : null,
	},
}

const mutations = {
	FETCH_USER(state, payload) {
		state.users      = [...payload.users]
		state.pagination = payload.pagination
	},
	LOADING_USER(state) {
		state.loading = !state.loading
	},
	OPEN_USER_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_USER_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_USER(state, payload) {
		state.editedIndex = state.users.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_USER(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.users[state.editedIndex], payload)	
		} else {
			state.users.unshift(payload)
		}
	},
	REMOVE_USER(state, payload) {
        state.users.splice(state.users.indexOf(payload), 1)
	},
	SEARCH_USER(state, payload) {
		state.search = {...payload}
	},
	CLEAR_USER(state){
        state.users = []
    },
}

const actions = {
	fetchUser: ({commit, state}, params) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_USER')
            UserRepository.get(params).then(response => {
                if(response.status === 200) {
					commit('FETCH_USER', response.data)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_USER'))
        }
	}),
    editUser: ({commit}, user) => new Promise((resolve, reject) => {
        commit('EDIT_USER', user)
        commit('OPEN_USER_DIALOG')
        resolve(true)
    }),
    updateUser: ({commit}, user) => new Promise((resolve, reject) => {
        commit('UPDATE_USER', user)
        resolve(true)
    }),
    removeUser: ({commit}, user) => new Promise((resolve, reject) => {
        commit('REMOVE_USER', user)
	}),
	searchUser: ({commit}, search) => new Promise((resolve, reject) => {
		commit('SEARCH_USER', search)
	}),
	clearUser: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_USER')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}