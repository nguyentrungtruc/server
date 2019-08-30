import axios from 'axios'

const state = {
	catalogues : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
	search     : {
		keywords: ''
	}
}

const mutations = {
	FETCH_CATALOGUE(state, payload) {
		state.catalogues = [...payload]
	},
	LOADING_CATALOGUE(state) {
		state.loading = !state.loading
	},
	OPEN_CATALOGUE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_CATALOGUE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_CATALOGUE(state, payload) {
		state.editedIndex = state.catalogues.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_CATALOGUE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.catalogues[state.editedIndex], payload)	
		} else {
			state.catalogues.unshift(payload)
		}
	},
	REMOVE_CATALOGUE(state, payload) {
        state.catalogues.splice(state.catalogues.indexOf(payload), 1)
	},
	CLEAR_CATALOGUE(state) {
		state.catalogues = []
	}
}

const actions = {
	fetchCatalogue: ({commit, state}, id) => new Promise((resolve, reject) => {
        const params = {storeId: id}
        const url    = `Catalogue/Fetch`
        if(!state.loading) {
            commit('LOADING_CATALOGUE')
            axios.get(url, {params, withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_CATALOGUE', response.data.catalogues)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_CATALOGUE'))
        }
	}),
    editCatalogue: ({commit}, catalogue) => new Promise((resolve, reject) => {
        commit('EDIT_CATALOGUE', catalogue)
        commit('OPEN_CATALOGUE_DIALOG')
        resolve(true)
    }),
    updateCatalogue: ({commit}, catalogue) => new Promise((resolve, reject) => {
        commit('UPDATE_CATALOGUE', catalogue)
        resolve(true)
    }),
    removeCatalogue: ({commit}, catalogue) => new Promise((resolve, reject) => {
        commit('REMOVE_CATALOGUE', catalogue)
	}),
	clearCatalogue: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_CATALOGUE')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}