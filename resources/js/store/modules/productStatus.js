import axios from 'axios'

const state = {
	status     : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_PRODUCT_STATUS(state, payload) {
		state.status = [...payload]
	},
	LOADING_PRODUCT_STATUS(state) {
		state.loading = !state.loading
	},
	OPEN_PRODUCT_STATUS_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_PRODUCT_STATUS_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_PRODUCT_STATUS(state, payload) {
		state.editedIndex = state.status.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_PRODUCT_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.status[state.editedIndex], payload)	
		} else {
			state.status.unshift(payload)
		}
	},
	REMOVE_PRODUCT_STATUS(state, payload) {
        state.status.splice(state.status.indexOf(payload), 1)
	},
	CLEAR_PRODUCT_STATUS(state) {
		state.status = []
	}
}

const actions = {
	fetchProductStatus: ({commit, state}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `ProductStatus/Fetch`
        if(!state.loading) {
            commit('LOADING_PRODUCT_STATUS')
            axios.get(url, data, {withCredentials: true}).then(response => {
                if(response.status === 200) {
					commit('FETCH_PRODUCT_STATUS', response.data.status)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_PRODUCT_STATUS'))
        }
	}),
    editProductStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('EDIT_PRODUCT_STATUS', status)
        commit('OPEN_PRODUCT_STATUS_DIALOG')
        resolve(true)
    }),
    updateProductStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('UPDATE_PRODUCT_STATUS', status)
        resolve(true)
    }),
    removeProductStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('REMOVE_PRODUCT_STATUS', status)
	}),
	clearProductStatus({commit}) {
		commit('CLEAR_PRODUCT_STATUS')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}