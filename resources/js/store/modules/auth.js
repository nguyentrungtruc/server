import axios from 'axios'

const state = {
    isAuth       : !!localStorage.getItem('jwt_admin'),
    info         : null,
    loading      : false,
    notifications: null,
    token        : !!localStorage.getItem('jwt_admin') ? JSON.parse(localStorage.getItem('jwt_admin')).access_token: null
}

const mutations = {
    SET_AUTH(state, payload) {
        state.info   = payload.data
        state.isAuth = true
    },
    REMOVE_AUTH(state) {
        state.info   = null
        state.isAuth = false
    }
}

const actions = {
    getAuth: ({commit}) => new Promise((resolve, reject) => {
        const data = []
        const url  = `/Credentials/Dofuu`
        axios.post(url, data, {withCredentials:true}).then(response => {
            if(response.status === 200) {
                commit('SET_AUTH', response.data)
            }
            resolve(response)
        })
    }),
    logout: ({commit}) => new Promise((resolve, reject) => {
        commit('REMOVE_AUTH')
    })
}

const getters = {

}

export default {
    state, mutations, actions, getters
}