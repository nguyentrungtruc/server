<template>
	<v-app id="inspire">
		<vue-navigation v-if="isAuthentication" :drawer.sync="drawer" :items.sync="items"></vue-navigation>

		<vue-toolbar v-if="isAuthentication" @DRAWER="drawer = !drawer"></vue-toolbar>

		<v-content >
			<v-container v-if="isAuthentication" >
				<v-layout row wrap>
					<v-flex xs12>
						<router-view></router-view>
					</v-flex>
				</v-layout>
			</v-container>

			<vue-login v-if="!isAuthentication"></vue-login>
			<v-footer height="auto" class="blue darken-3 white--text">
				<v-layout justify-center>
					<v-flex text-xs-center white--text>
						&copy;2018 — <strong>Dofuu.com</strong>
					</v-flex>
				</v-layout>
			</v-footer>
		</v-content>
		<vue-broadcasting v-if="isAuthentication" ref="broadcasting"/>
	</v-app>  
</template>

<script>
import axios from 'axios'
import {getHeader} from '@/config/config'
import Login from '@/components/credential/Login'
import Toolbar from '@/components/toolbar/Toolbar'
import Navigation from '@/components/navigation/Navigation'
import Broadcast from '@/components/broadcasting/Broadcast'
import {mapState} from 'vuex'
export default{
	components: {
		'vue-login' : Login,
		'vue-toolbar' : Toolbar,
		'vue-navigation' : Navigation,
		'vue-broadcasting' : Broadcast
	},
	data: () => ({
		drawer: false,
		items: [		
		{ icon: 'receipt', title: 'Manage Orders', isAdmin: true, isEmployee:true, action: 'Order'},
		{ icon: 'store', title: 'Manage Stores', isAdmin: true, isEmployee:true, action: 'Store'},
		{
			icon: 'group',
			title: 'Manage Users',
			isAdmin: true, isEmployee:true,
			model:false,
			children: [
			{ title: 'Users', isEmployee:true, action:'User' },
			{ title: 'Roles' , isEmployee:false, action:'Role'},
			{ title: 'Passports', isEmployee:false , action:'Passport'},
			]
		},
		{ icon: 'redeem', title: 'Manage Coupons', action: 'Coupon',  isAdmin: true, isEmployee:true},
		{
			icon: 'settings',
			title: 'Manage Services',
			isAdmin: true, 
			isEmployee:true,
			model:false,
			children: [
			{ title: 'Services', isEmployee:true, action: 'Service'},
			{ title: 'Delivery Locations', isEmployee:true, action: 'Delivery'},
			{ title: 'Range For Delivery', isEmployee:true, action: 'Range'},
			]
		},
		{heading: 'Store', isAdmin: true, isEmployee:false},
		{
			icon: 'format_list_numbered',
			title: 'Manage Types', 
			isAdmin: true,	
			isEmployee:false,
			model:false,
			children: [
			{ title: 'Types Of Stores', action: 'Type'}
			]
		},
		{ icon: 'access_time', title: 'Manage Activity', action: 'Activity', isAdmin: true, isEmployee:false},
		{heading: 'System', isAdmin: true,	isEmployee:false},
		{
			icon: 'place',
			title: 'Manage Locations',
			isAdmin: true,	
			isEmployee:false,
			active: true,
			model:false,
			children: [
			{ title: 'Districts', action: 'District'},
			{ title: 'Cities', action: 'City'},
			{ title: 'Countries', action: 'Country'},
			]
		},		
		{
			icon: 'lens',
			title: 'Manage Status',
			isAdmin: true,
			isEmployee:false,
			model:false,
			children: [
			{ title: 'Order Status', action: 'OrderStatus'},
			{ title: 'Coupon Status', action: 'CouponStatus'},
			{ title: 'Product Status', action: 'ProductStatus'},
			{ title: 'Store Status', action: 'StoreStatus'},
			]
		},
		{ icon: 'payment', title: 'Payment Methods', isAdmin: true,	isEmployee:false, action: 'PaymentMethod'},
		]
	}),
	props: {
		source: String
	},
	methods: {
		login: () => {

		},
		getNotification: function() {
			const data = {confirm: true}
			axios.post('/api/Dofuu-Notification/GetNotification', data, {headers: getHeader()}).then(response => {
				if(response.status == 200) {
					this.$store.commit('FETCH_NOTIFICATION', response.data)
				}
			})
		},
	},
	computed: {
		...mapState({
			isAuthentication: state => state.authStore.isAuth,
			progress: state         => state.progress,
			currentUser: state      => state.authStore.authUser
		})
	},
	watch: {
		'currentUser': function(val) {
			if(val != null) {
				this.getNotification()
			}
		}
	},
	created() {
		setInterval(() => {
			this.$store.dispatch('getAuth')
		}, 300000)
	},
	mounted() {

	}
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
	transition: opacity .5s
}
.fade-enter, .fade-leave-active {
	opacity: 0
}
</style>

