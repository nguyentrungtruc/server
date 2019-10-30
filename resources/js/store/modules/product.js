import {RepositoryFactory} from '@/services/Repository/index'
const ProductRepository = RepositoryFactory.get('products')

const state = {
	products   : [],
	loading    : false,
	dialog     : false,
	editedIndex: -1,
	editedItem : {},
	search     : {
		keywords: ''
	}
}

const mutations = {
	FETCH_PRODUCT(state, payload) {
		state.products = [...payload]
	},
	LOADING_PRODUCT(state) {
		state.loading = !state.loading
	},
	OPEN_PRODUCT_DIALOG(state) {
		state.dialog = true
    },
    CLOSE_PRODUCT_DIALOG(state) {
		state.dialog      = false
		state.editedItem  = {}
		state.editedIndex = -1
    },
    EDIT_PRODUCT(state, payload) {
		state.editedIndex = state.products.indexOf(payload)
		state.editedItem  = Object.assign({}, payload)
	},
    UPDATE_PRODUCT(state, payload) {
		if(state.editedIndex > -1) {
			Object.assign(state.products[state.editedIndex], payload)	
		} else {
			state.products.unshift(payload)
		}
	},
	REMOVE_PRODUCT(state, payload) {
        state.products.splice(state.products.indexOf(payload), 1)
	},
	SEARCH_PRODUCT(state, payload) {
		state.search = {...payload}
	},
	CLEAR_PRODUCT(state) {
		state.products = []
	}
}

const actions = {
	fetchProduct: ({commit, state}, id) => new Promise((resolve, reject) => {
        const params = {storeId: id}
        if(!state.loading) {
            commit('LOADING_PRODUCT')
            ProductRepository.get(params).then(response => {
                if(response.status === 200) {
					commit('FETCH_PRODUCT', response.data.products)
				}
				resolve(response)
            }).catch(error => {reject(error)}).finally(() => commit('LOADING_PRODUCT'))
        }
	}),
    editProduct: ({commit}, product) => new Promise((resolve, reject) => {
		commit('EDIT_PRODUCT', product)
		commit('OPEN_PRODUCT_DIALOG')	
        resolve(true)
	}),
	editAvatar: ({commit}, product) => new Promise((resolve, reject) => {
		commit('EDIT_PRODUCT', product)
        resolve(true)
	}),
    updateProduct: ({commit}, product) => new Promise((resolve, reject) => {
        commit('UPDATE_PRODUCT', product)
        resolve(true)
    }),
    removeProduct: ({commit}, product) => new Promise((resolve, reject) => {
        commit('REMOVE_PRODUCT', product)
	}),
	searchProduct: ({commit}, search) => new Promise((resolve, reject) => {
		commit('SEARCH_PRODUCT', search)
	}),
	clearProduct({commit}) {
		commit('CLEAR_PRODUCT')
	}
}

const getters = {

}

export default {
	state, mutations, actions, getters
}