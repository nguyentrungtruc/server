import {RepositoryFactory} from '@/services/Repository/index'
const StoreRepository = RepositoryFactory.get('stores')

const state = {
    stores     : [],
    store      : null,
    pagination : null,
    loading    : false,
    dialog     : false,
    editedIndex: -1,
    editedItem : {
        typeId     : null,
        name       : '',
        phone      : '',
        prepareTime: 20,
        cityId     : null,
        districtId : null,
        address    : '',
        lat        : 10.0452,
        lng        : 105.7469,
        discount   : 0,
        priority   : 0,
        isShow     : false,
        isVerify   : false,
        statusId   : 1
    },
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
        state.dialog     = false
        state.editedItem = {
            typeId     : null,
            name       : '',
            phone      : '',
            prepareTime: 20,
            cityId     : null,
            districtId : null,
            address    : '',
            lat        : 10.0452,
            lng        : 105.7469,
            discount   : 0,
            priority   : 0,
            isShow     : false,
            isVerify   : false,
            statusId   : 1
        }
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
        if(!state.loading) {
            commit('LOADING_STORE')
            StoreRepository.get(params).then(response => {
                if(response.status === 200) {
                    commit('FETCH_STORE', response.data)
                }
                resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_STORE'))
        }
    }),
    showStore: ({commit}, storeId) => new Promise((resolve, reject) => {
        if(!state.loading) {
            commit('LOADING_STORE')
            StoreRepository.show(storeId).then(response => {
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
    editAvatar: ({commit}, store) => new Promise((resolve, reject) => {
        commit('EDIT_STORE', store)
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