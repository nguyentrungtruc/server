import axios from 'axios'

const state = {
    stores     : [],
    store      : null,
    pagination : null,
    loading    : false,
    dialog     : false,
    editedIndex: -1,
    editedItem : {},
    search     : {
        keywords: '',
        typeId  : -1,
        cityId  : 10001,
        isShow  : null,
    },
}

const mutations = {
	FETCH_STORE(state, payload) {
        state.stores     = payload.stores
        state.pagination = payload.pagination
    },
    SHOW_STORE(state, payload) {
        state.store = {...payload.store}
    },
	LOADING_STORE(state) {
		state.loading = !state.loading
    },
	OPEN_STORE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_STORE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_STORE(state, payload) {
		state.editedIndex = state.stores.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_STORE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.stores[state.editedIndex], payload)	
		} else {
			state.stores.unshift(payload)
		}
	},
	REMOVE_STORE(state, payload) {
        state.stores.splice(state.stores.indexOf(payload), 1)
    },
	SEARCH_STORE(state, payload) {
		state.search = {...payload}
    },
    CLEAR_STORE(state){
        state.stores = []
    },
}

const actions = {
	fetchStore: ({commit, state}, params) => new Promise((resolve, reject) => {
        const url = `Store/Fetch`
        if(!state.loading) {
            commit('LOADING_STORE')
            axios.get(url, {params, withCredentials: true}).then(response => {
                if(response.status === 200) {
                    commit('FETCH_STORE', response.data)
                }
                resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_STORE'))
        }
    }),
    showStore: ({commit}, storeId) => new Promise((resolve, reject) => {
        const url = `Store/${storeId}/Show`
        if(!state.loading) {
            commit('LOADING_STORE')
            axios.get(url, {withCredentials: true}).then(response => {
                if(response.status === 200) {   
                    commit('SHOW_STORE', response.data)
                }
                resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_STORE'))
        }
    }),
    editStore: ({commit}, store) => new Promise((resolve, reject) => {
        commit('EDIT_STORE', store)
        commit('OPEN_STORE_DIALOG')
        resolve(true)
    }),
    updateStore: ({commit}, store) => new Promise((resolve, reject) => {
        commit('UPDATE_STORE', store)
        resolve(true)
    }),
    removeStore: ({commit}, store) => new Promise((resolve, reject) => {
        commit('REMOVE_STORE', store)
        resolve(true)
	}),
	searchStore: ({commit}, search) => new Promise((resolve, reject) => {
        commit('SEARCH_STORE', search)
        resolve(true)
    }),
    clearStore({commit}) {
        commit('CLEAR_STORE')
    }
}

const getters = {

}

export default {
	state, mutations, actions, getters
}