import {RepositoryFactory} from '@/services/Repository/index'
const DistrictRepository = RepositoryFactory.get('districts')

const state = {
	districts  : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_DISTRICT(state, payload) {
		state.districts = [...payload]
	},
	LOADING_DISTRICT(state) {
		state.loading = !state.loading
	},
	OPEN_DISTRICT_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_DISTRICT_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_DISTRICT(state, payload) {
		state.editedIndex = state.districts.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_DISTRICT(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.districts[state.editedIndex], payload)	
		} else {
			state.districts.unshift(payload)
		}
	},
	REMOVE_DISTRICT(state, payload) {
        state.districts.splice(state.districts.indexOf(payload), 1)
	},
	CLEAR_DISTRICT(state) {
		state.districts = []
	}
}

const actions = {
	fetchDistrict: ({commit, state}) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_DISTRICT')
            DistrictRepository.get().then(response => {
                if(response.status === 200) {
                    commit('FETCH_DISTRICT', response.data.districts)
                }
                resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_DISTRICT'))
        }
	}),
    editDistrict: ({commit}, district) => new Promise((resolve) => {
        commit('EDIT_DISTRICT', district)
        commit('OPEN_DISTRICT_DIALOG')
        resolve(true)
    }),
    updateDistrict: ({commit}, district) => new Promise((resolve) => {
        commit('UPDATE_DISTRICT', district)
        resolve(true)
    }),
    removeDistrict: ({commit}, district) => new Promise((resolve) => {
		commit('REMOVE_DISTRICT', district)
		resolve(true)
	}),
	clearDistrict: ({commit}) => {
		commit('CLEAR_DISTRICT')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}