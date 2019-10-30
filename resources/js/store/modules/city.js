import {RepositoryFactory} from '@/services/Repository/index'
const CityRepository = RepositoryFactory.get('cities')

const state = {
	cities     : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {}
}

const mutations = {
	FETCH_CITY(state, payload) {
		state.cities = [...payload]
	},
	LOADING_CITY(state) {
		state.loading = !state.loading
	},
	OPEN_CITY_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_CITY_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_CITY(state, payload) {
		state.editedIndex = state.cities.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_CITY(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.cities[state.editedIndex], payload)	
		} else {
			state.cities.unshift(payload)
		}
	},
	REMOVE_CITY(state, payload) {
        state.cities.splice(state.cities.indexOf(payload), 1)
	},
	CLEAR_CITY(state) {
		state.cities = []
	}
}

const actions = {
	fetchCity: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `City/Fetch`
        if(!state.loading) {
			commit('LOADING_CITY')
			CityRepository.get().then(response => {
                if(response.status === 200) {
                    commit('FETCH_CITY', response.data.cities)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_CITY'))
        }
	}),
    editCity: ({commit}, city) => new Promise((resolve) => {
        commit('EDIT_CITY', city)
        commit('OPEN_CITY_DIALOG')
        resolve(true)
    }),
    updateCity: ({commit}, city) => new Promise((resolve) => {
        commit('UPDATE_CITY', city)
        resolve(true)
    }),
    removeCity: ({commit}, city) => new Promise((resolve) => {
		commit('REMOVE_CITY', city)
		resolve(true)
	}),
	clearCity: ({commit}) => {
		commit('CLEAR_CITY')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}