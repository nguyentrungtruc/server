import {RepositoryFactory} from '@/services/Repository/index'
const CouponStatusRepository = RepositoryFactory.get('couponStatus')

const state = {
	status     : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
}

const mutations = {
	FETCH_COUPON_STATUS(state, payload) {
		state.status = [...payload]
	},
	LOADING_COUPON_STATUS(state) {
		state.loading = !state.loading
	},
	OPEN_COUPON_STATUS_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_COUPON_STATUS_DIALOG(state) {
        state.dialog      = false
        state.editedIndex = -1
    },
    EDIT_COUPON_STATUS(state, payload) {
		state.editedIndex = state.status.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_COUPON_STATUS(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.status[state.editedIndex], payload)	
		} else {
			state.status.unshift(payload)
		}
	},
	REMOVE_COUPON_STATUS(state, payload) {
        state.status.splice(state.status.indexOf(payload), 1)
	},
	CLEAR_COUPON_STATUS(state) {
		state.status = []
	}
}

const actions = {
	fetchCouponStatus: ({commit, state}) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_COUPON_STATUS')
			CouponStatusRepository.get().then(response => {
                if(response.status === 200) {
					commit('FETCH_COUPON_STATUS', response.data.status)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_COUPON_STATUS'))
        }
	}),
    editCouponStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('EDIT_COUPON_STATUS', status)
        commit('OPEN_COUPON_STATUS_DIALOG')
        resolve(true)
    }),
    updateCouponStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('UPDATE_COUPON_STATUS', status)
        resolve(true)
    }),
    removeCouponStatus: ({commit}, status) => new Promise((resolve, reject) => {
        commit('REMOVE_COUPON_STATUS', status)
	}),
	clearCouponStatus({commit}) {
		commit('CLEAR_COUPON_STATUS')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}