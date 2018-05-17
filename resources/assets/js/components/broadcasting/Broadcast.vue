<template>
	<div id="laravel-echo">
		<template v-if="isConnected">
			<v-icon color="success" small>lens</v-icon>
		</template>
		<template v-else-if="currentUser">
			<v-icon color="error" small>lens</v-icon>
		</template>
	</div>
</template>
<script>
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import Auth from '@/utils/auth'
import {mapState} from 'vuex'
import {getHeader} from '@/config/config'
export default {
	data() {
		return {
			echo: null
		}
	},
	methods: {
		connect(){
			console.log('broadcasting:connecting:pusher', this.$broadcastUrl)
			if(!this.echo){
				this.echo = new Echo({
					broadcaster: 'pusher',
					key: '063bdced98d87f38a6d2',
					cluster: 'ap1',
					encrypted: true,
					authEndpoint: 'http://167.99.74.115/broadcasting/auth',
					auth: {
						headers: getHeader()
					},
					// csrfToken: null,
					// namespace: 'App',
				})

				this.echo.connector.pusher.connection.bind('connected', (event) => {
					return this.connected(event)
				})

				this.echo.connector.pusher.connection.bind('disconnected', () => this.disconnected())
			}
			this.echo.connector.pusher.config.auth.headers.Authorization = 'Bearer ' + this.token
			this.echo.connector.pusher.connect()
		},
		bindChannels(){
			let vm = this
			this.echo.private('users' + '.' + this.currentUser.id)
			.notification((object) => {
				vm.$store.commit('ADD_NOTIFICATION', object)
			})
		},
		disconnect(){
			if(!this.echo) return
				this.echo.disconnect()
		},
		connected(value) {
			return value
		},
		disconnected() {
			return false
		} 
	},
	computed: {
		token: {
			cache: false, 
			get(){ return this.$store.getters.token } 
		},
		isConnected: {
			cache: false, 
			get(){ 
				return (this.echo &&   this.echo.connector.pusher.connection.connection !== null) 
			} 
		},
		// notification: {
		// 	cache: false, 
		// 	get() { 
		// 		return this.$store.getters.notifications.reverse()
		// 	} 
		// },
		...mapState({
			currentUser: state => state.authStore.authUser
		})
	},
	watch: {
		currentUser: { 
			handler(currentUser){ 
				(currentUser !== null ? this.connect() : this.disconnect())
			} 
		},
		isConnected: { 
			handler(isConnected){     
				if(isConnected) {
					console.log(isConnected)
					this.bindChannels()
				}
				this.$emit('broadcasting:status', isConnected) 
			} 
		}
	},
	mouted() {
		
	}
}
</script>