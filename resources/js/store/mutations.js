const mutations = {
    CHANGE_DRAWER_STATE(state) {
        console.log(state)
        state.drawer = !state.drawer
    }
}

export default mutations