import axios from 'axios'

const state = {
	notifications: [],
	loading: false,
}

const mutations = {
	FETCH_NOTIFICATION(state, payload) {
		state.notifications = payload.data
	},
	ADD_NOTIFICATION(state, payload) {
		state.notifications.unshift(payload)
	},
	UPDATE_NOTIFICATION(state, payload) {
		const index = state.notifications.findIndex(item => {
			return item.id == payload.id
		})
		Object.assign(state.notifications[index], payload)

	}
}

const actions = {

}

const getters = {
	getNotification: (state) => {
		return state.notifications
	},
	unreadCount: (state) => {
		var count = 0
		state.notifications.forEach(item => {
			if(item.read_at == null) {
				count = ++count  
			}
		})
		return count
	}
}

export default {
	state, mutations, actions, getters
}