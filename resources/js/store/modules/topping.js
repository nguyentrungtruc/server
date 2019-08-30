import axios from 'axios'

const state = {
	toppings   : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
	search     : {
		keywords: ''
	}
}

const mutations = {
	FETCH_TOPPING(state, payload) {
		state.toppings = [...payload]
	},
	LOADING_TOPPING(state) {
		state.loading = !state.loading
	},
	OPEN_TOPPING_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_TOPPING_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_TOPPING(state, payload) {
		state.editedIndex = state.toppings.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_TOPPING(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.toppings[state.editedIndex], payload)	
		} else {
			state.toppings.unshift(payload)
		}
	},
	REMOVE_TOPPING(state, payload) {
        state.toppings.splice(state.toppings.indexOf(payload), 1)
	},
	CLEAR_TOPPING(state) {
		state.toppings = []
	}
}

const actions = {
	fetchTopping: ({commit, state}, id) => new Promise((resolve, reject) => {
        const params = {storeId: id}
        const url    = `Topping/Fetch`
        if(!state.loading) {
            commit('LOADING_TOPPING')
            axios.get(url, {params, withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_TOPPING', response.data.toppings)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_TOPPING'))
        }
	}),
    editTopping: ({commit}, topping) => new Promise((resolve, reject) => {
        commit('EDIT_TOPPING', topping)
        commit('OPEN_TOPPING_DIALOG')
        resolve(true)
    }),
    updateTopping: ({commit}, topping) => new Promise((resolve, reject) => {
        commit('UPDATE_TOPPING', topping)
        resolve(true)
    }),
    removeTopping: ({commit}, topping) => new Promise((resolve, reject) => {
        commit('REMOVE_TOPPING', topping)
	}),
	clearTopping: ({commit}) => new Promise((resolve, reject) => {
		commit('CLEAR_TOPPING')
	})
}

const getters = {

}

export default {
	state, mutations, actions, getters
}