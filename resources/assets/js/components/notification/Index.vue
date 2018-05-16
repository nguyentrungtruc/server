<template>
	<v-card>
		<v-breadcrumbs>
			<v-icon slot="divider">chevron_right</v-icon>
			<v-breadcrumbs-item
			v-for="item in breadcrumbs"
			:key="item.text"
			:disabled="item.disabled"
			:to="{name: item.action}"
			>{{ item.text }}</v-breadcrumbs-item>
		</v-breadcrumbs>
		<v-toolbar dense flat>
			<v-toolbar-title>
				Notifications
			</v-toolbar-title>
		</v-toolbar>
		<v-card-text>
			<v-list dense three-line>
				<template v-for="(notification, index) in notifications">						
					<v-list-tile avatar  :class="{'blue-grey lighten-5': notification.read_at == null}"  @click="readNotification(notification)">
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
								<div>
									Đặt hàng với hóa đơn mã số <strong>{{notification.data.id}}</strong>. Từ: <strong>{{notification.data.store.address}}</strong> Đến: <strong>{{notification.data.address}}</strong></div>
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
						<v-divider :key="index"></v-divider>
					</template>
				</v-list>
			</v-card-text>
		</v-card>
	</template>

	<script>
	import moment from 'moment'
	import image from '@/mixins/imageUrl'
	import {getHeader} from '@/config/config'
	import {mapState} from 'vuex'
	export default {
		mixins: [image],
		data() {
			return {
				breadcrumbs: [
				{
					text: 'Dashboard',
					action: 'Dashboard',
					disabled: false
				},
				{
					text: 'Notifications',
					action: '',
					disabled: true
				}
				],
			}
		},
		methods: {
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