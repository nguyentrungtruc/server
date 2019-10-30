import {RepositoryFactory} from '@/services/Repository/index'
const OrderStatusRepository = RepositoryFactory.get('orderStatus')

const state = {
	status     : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_ORDER_STATUS(state, payload) {
		state.status = [...payload]
	},
	LOADING_ORDER_STATUS(state) {
		state.loading = !state.loading
	},
	OPEN_ORDER_STATUS_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_ORDER_STATUS_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_ORDER_STATUS(state, payload) {
		state.editedIndex = state.status.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_ORDER_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.status[state.editedIndex], payload)	
		} else {
			state.status.unshift(payload)
		}
	},
	REMOVE_ORDER_STATUS(state, payload) {
        state.status.splice(state.status.indexOf(payload), 1)
	},
	CLEAR_ORDER_STATUS(state) {
		state.status = []
	}
}

const actions = {
	fetchOrderStatus: ({commit, state}) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_ORDER_STATUS')
            OrderStatusRepository.get().then(response => {
                if(response.status === 200) {
					commit('FETCH_ORDER_STATUS', response.data.status)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_ORDER_STATUS'))
        }
	}),
    editOrderStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('EDIT_ORDER_STATUS', status)
        commit('OPEN_ORDER_STATUS_DIALOG')
        resolve(true)
    }),
    updateOrderStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('UPDATE_ORDER_STATUS', status)
        resolve(true)
    }),
    removeOrderStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('REMOVE_ORDER_STATUS', status)
	}),
	clearOrderStatus({commit}) {
		commit('CLEAR_ORDER_STATUS')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}