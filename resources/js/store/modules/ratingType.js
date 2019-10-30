import {RepositoryFactory} from '@/services/Repository/index'
const RatingTypeRepository = RepositoryFactory.get('ratingType')

const state = {
	types      : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_RATING_TYPE(state, payload) {
		state.types = [...payload]
	},
	LOADING_RATING_TYPE(state) {
		state.loading = !state.loading
	},
	OPEN_RATING_TYPE_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_RATING_TYPE_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_RATING_TYPE(state, payload) {
		state.editedIndex = state.types.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_RATING_TYPE(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.types[state.editedIndex], payload)	
		} else {
			state.types.unshift(payload)
		}
	},
	REMOVE_RATING_TYPE(state, payload) {
        state.types.splice(state.types.indexOf(payload), 1)
	},
	CLEAR_RATING_TYPE(state) {
		state.types = []
	}
}

const actions = {
	fetchRatingType: ({commit, state}) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_RATING_TYPE')
            RatingTypeRepository.get().then(response => {
                if(response.status === 200) {
					commit('FETCH_RATING_TYPE', response.data.types)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_RATING_TYPE'))
        }
	}),
    editRatingType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('EDIT_RATING_TYPE', type)
        commit('OPEN_RATING_TYPE_DIALOG')
        resolve(true)
    }),
    updateRatingType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('UPDATE_RATING_TYPE', type)
        resolve(true)
    }),
    removeRatingType: ({commit}, type) => new Promise((resolve, reject) => {
        commit('REMOVE_RATING_TYPE', type)
	}),
	clearRatingType: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_RATING_TYPE')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}