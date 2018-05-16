<template>
	<v-toolbar app fixed :clipped-left="$vuetify.breakpoint.mdAndUp" dark color="blue darken-3">
		<v-toolbar-side-icon @click.native="toggle()"></v-toolbar-side-icon>
		<v-toolbar-title>Admin Panel</v-toolbar-title>
		<v-spacer></v-spacer>
		<v-menu
		:close-on-content-click="false"
		v-model="menu"
		:min-width="432"
		offset-y
		allow-overflow
		>
		<v-badge slot="activator" color="red" overlap>
			<span slot="badge">{{unreadCount}}</span>
			<v-btn color="indigo" dark icon>
				<v-icon>
					notifications
				</v-icon>
			</v-btn>
		</v-badge>

		<v-card>
			<v-tabs	v-model="tab"	color="primary"	dark fixed-tabs slider-color="primary" height="40">
				<v-tab>
					Recent
				</v-tab>
				<v-tab>
					Old
				</v-tab>
			</v-tabs>

			<v-divider></v-divider>
			<v-card-text style="height: 300px;" class="scroll-y" v-if="notifications.length>0">
				<v-list dense three-line>
					<template v-for="(notification, index) in notifications" v-if="tab==0">						
						<v-list-tile avatar  :class="{'blue-grey lighten-5': notification.read_at == null}"  @click="readNotification(notification)" v-if="notification.read_at == null">
							<v-list-tile-avatar>
								<img :src="image(notification.data.owner.image)">
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title>
									<strong>
										{{notification.data.name}}
									</strong>
								</v-list-tile-title>
								<v-list-tile-title>
									Đặt hàng với hóa đơn mã số {{notification.data.id}}
								</v-list-tile-title>
								<v-list-tile-sub-title>
									<v-icon small>access_time</v-icon>	{{ formatTime(notification.data.bookingDate) }}
								</v-list-tile-sub-title>
							</v-list-tile-content>
							<v-list-tile-action v-if="notification.read_at != null">
								<v-tooltip top>									
									<v-icon slot="activator" color="success">check</v-icon>
									<span>Seen</span>
								</v-tooltip>
								<v-list-tile-action-text></v-list-tile-action-text>
								<v-list-tile-action-text>{{ notification.read_at | formatDateTimeHumanize }}</v-list-tile-action-text>
							</v-list-tile-action>
						</v-list-tile>
						<v-divider :key="index" v-if="notification.read_at == null"></v-divider>
					</template>

					<template v-for="(notification, index) in notifications" v-if="tab==1">						
						<v-list-tile avatar  @click="readNotification(notification)" v-if="notification.read_at != null">
							<v-list-tile-avatar>
								<img :src="image(notification.data.owner.image)">
							</v-list-tile-avatar>
							<v-list-tile-content>
								<v-list-tile-title>
									<strong>
										{{notification.data.name}}
									</strong>
								</v-list-tile-title>
								<v-list-tile-title>
									Đặt hàng với hóa đơn mã số {{notification.data.id}}
								</v-list-tile-title>
								<v-list-tile-sub-title>
									<v-icon small>access_time</v-icon> {{ formatTime(notification.data.bookingDate) }}
								</v-list-tile-sub-title>
							</v-list-tile-content>
							<v-list-tile-action v-if="notification.read_at != null">
								<v-tooltip top>									
									<v-icon slot="activator" color="success">check</v-icon>
									<span>Seen</span>
								</v-tooltip>
								<v-list-tile-action-text></v-list-tile-action-text>
								<v-list-tile-action-text>{{ notification.read_at | formatDateTimeHumanize }}</v-list-tile-action-text>
							</v-list-tile-action>
						</v-list-tile>
						<v-divider v-if="notification.read_at != null"></v-divider>
					</template>
				</v-list>
			</v-card-text>
			<v-divider></v-divider>
			<v-list>
				<v-system-bar status color="primary" lights-out class="justify-center">
					<router-link :to="{name: 'Notification'}"> See All </router-link>
				</v-system-bar>
			</v-list>
		</v-card>
	</v-menu>
	<v-toolbar-items>
		<v-btn flat @click.stop="logout()"><v-icon left dark>power_settings_new</v-icon> Logout</v-btn>
	</v-toolbar-items>
</v-toolbar>	
</template>
<script>
import moment from 'moment'
import image from '@/mixins/imageUrl'
import {getHeader} from '@/config/config'
import {mapState} from 'vuex'
export default {
	mixins: [image],
	props: ['mini'],
	data() {
		return {
			menu: false,
			tab:0
		}
	},
	methods: {
		toggle: function() {
			this.$emit('DRAWER')
		},
		logout: function() {
			this.$store.dispatch('logout')
		},
		formatTime(date) {
			var start = moment(date, 'DD-MM-YYYY HH:mm')
			return start.startOf().locale('vi').fromNow()
		},
		readNotification: function(notifiable) {
			const data = notifiable
			if(notifiable.read_at == null) {
				axios.post('/api/Dofuu-Notification/ReadNotification', data, {headers: getHeader(), withCredentials: true}).then(response => {
					if(response.status == 200) {
						this.$store.commit('UPDATE_NOTIFICATION', response.data.data)
					}
				})
			}
			this.$router.replace({name: 'OrderDetails', params: {oid: notifiable.data.id}})
			window.location.reload()
		}
	},
	computed: {
		notifications() {
			return this.$store.getters.getNotification
		},
		unreadCount() {
			return this.$store.getters.unreadCount
		}
	}
}
</script>
<style>

</style>