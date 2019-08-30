const state = {
    close    : false,
    index    : 0,
    message  : '',
    routeName: '',
    show     : false,
    type     : 'success',
}

const mutations = {
    SHOW_ALERT(state, payload) {
        Object.assign(state, payload)
    },
    CLOSE_ALERT(state) {
        Object.assign(state, {close: false, index: 0, message: '', routeName: '', show: false, type: 'success'})
    }
}

const actions = {
    onAlert({commit}, payload) {
        commit('SHOW_ALERT', payload)
        if(payload.close) {
            setTimeout(() => {
                commit('CLOSE_ALERT')
            }, 4000)
        }
    },
    offAlert({commit}) {
        commit('CLOSE_ALERT')
    }
}

const getters = {

}

export default {
    state, mutations, actions, getters
}