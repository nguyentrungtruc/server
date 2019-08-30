import axios from 'axios'

const state = {
	countries  : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_COUNTRY(state, payload) {
		state.countries = [...payload]
	},
	LOADING_COUNTRY(state) {
		state.loading = !state.loading
	},
	OPEN_COUNTRY_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_COUNTRY_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_COUNTRY(state, payload) {
		state.editedIndex = state.countries.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_COUNTRY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.countries[state.editedIndex], payload)	
		} else {
			state.countries.unshift(payload)
		}
	},
	REMOVE_COUNTRY(state, payload) {
        state.countries.splice(state.countries.indexOf(payload), 1)
	},
	CLEAR_COUNTRY(state) {
		state.countries = []
	}
}

const actions = {
	fetchCountry: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `Country/Fetch`
        if(!state.loading) {
            commit('LOADING_COUNTRY')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_COUNTRY', response.data.countries)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_COUNTRY'))
        }
	}),
    editCountry: ({commit}, country) => new Promise((resolve, reject) => {
        commit('EDIT_COUNTRY', country)
        commit('OPEN_COUNTRY_DIALOG')
        resolve(true)
    }),
    updateCountry: ({commit}, country) => new Promise((resolve, reject) => {
        commit('UPDATE_COUNTRY', country)
        resolve(true)
    }),
    removeCountry: ({commit}, country) => new Promise((resolve, reject) => {
        commit('REMOVE_COUNTRY', country)
	}),
	clearCountry: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_COUNTRY')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}