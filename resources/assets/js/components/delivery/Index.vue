<template>
	<v-card>
		<v-progress-linear :indeterminate="true" height="5" v-show="progress"></v-progress-linear>
		<v-breadcrumbs>
			<v-icon slot="divider">chevron_right</v-icon>
			<v-breadcrumbs-item
			v-for="item in breadcrumbs"
			:key="item.text"
			:disabled="item.disabled"
			>{{ item.text }}</v-breadcrumbs-item>
		</v-breadcrumbs>

		<v-divider></v-divider>
		
		<v-card-title primary-title>
			<div>
				<div class="headline">{{title}}</div>
			</div>
			<v-spacer></v-spacer>
			<v-btn color="primary" :disabled="dialog" :loading="loading" class="mb-2" @click.native="showForm(-1, null)">New Delivery</v-btn>
		</v-card-title>
		
		<v-alert :type="alert.type" dismissible v-model="alert.alert">
			{{alert.messages}}
		</v-alert>

		<v-container fluid grid-list-lg >
			<v-layout row wrap>
				<v-slide-x-transition>
					<v-flex xs12 sm6 md4  v-if="dialog">					
						<v-card >
							<v-toolbar  dense class="elevation-0">
								<v-toolbar-title>
									{{formTitle}}
								</v-toolbar-title>
							</v-toolbar>
							<v-card-text>
								<v-form>
									<v-select
									:items="cities"
									v-model="editedItem.id"
									label="Select"
									item-value="id"
									item-text="name"
									single-line
									:disabled="editedIndex>-1"
									></v-select>
									<v-layout v-for="(item, i) in editedItem.ranges" :key="i" row wrap>
										<v-flex xs3>
											<v-text-field
											name="input-3"
											label="From"
											:value="item.from"
											disabled
											></v-text-field>
										</v-flex>
										<v-flex xs3>
											<v-text-field
											name="input-3"
											label="To"
											:value="item.to"
											disabled
											></v-text-field>
										</v-flex>
										<v-flex xs6>
											<v-text-field
											name="input-3"
											v-model="item.price"
											label="Price"
											></v-text-field>
										</v-flex>
									</v-layout>
								</v-form>
							</v-card-text>
							<v-card-actions>
								<v-spacer></v-spacer>
								<v-btn @click.native="cancel">
									Cancel
								</v-btn>
								<v-btn color="primary" :disabled="disabled" @click.native="save">
									Save
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-flex>
				</v-slide-x-transition>	
				<v-flex xs12 md6 md8>
					<v-layout row wrap>
						<v-flex xs12 sm6 md4 v-for="(data, i) in deliveries" :key="i">
							<v-card>
								<v-toolbar dense class="elevation-0" dark color="primary">
									<v-tooltip top>
										<v-avatar slot="activator" size="16" class="grey lighten-4">
											<v-icon :color="data.service.deliveryActived ? 'green darken-3' : 'grey'">beenhere</v-icon>
										</v-avatar>		 -->					
										<span>{{data.service.deliveryActived ? 'Đã mở dịch vụ vận chuyển' : 'Đã tắt dịch vụ vận chuyển'}}</span>
									</v-tooltip>
									<v-toolbar-title>
										{{data.name}}
									</v-toolbar-title>									
									<v-spacer></v-spacer>
									<v-menu bottom left>
										<v-btn icon slot="activator" dark>
											<v-icon>more_vert</v-icon>
										</v-btn>
										<v-list subheader>
											<v-list-tile>
												<v-subheader>Settings</v-subheader>
											</v-list-tile>
											<v-divider></v-divider>
											<v-list-tile @click="showForm(i, data)">
												<v-list-tile-avatar>
													<v-icon>edit</v-icon>
												</v-list-tile-avatar>
												<v-list-tile-title>Edit</v-list-tile-title>
											</v-list-tile>
											<v-list-tile @click="deleteItem(data)">
												<v-list-tile-avatar>
													<v-icon color="red">delete</v-icon>
												</v-list-tile-avatar>
												<v-list-tile-title class="red--text">Remove</v-list-tile-title>
											</v-list-tile>
										</v-list>
									</v-menu>
								</v-toolbar>
								<v-card-text>
									<v-layout row wrap>
										<v-flex xs3>From</v-flex>
										<v-flex xs3>To</v-flex>
										<v-flex xs6>Price</v-flex>
									</v-layout>		
									<v-divider></v-divider>
									<v-layout v-for="(item, index) in data.deliveries" :key="index" row wrap>
										<v-flex xs3>{{item.from}}</v-flex>
										<v-flex xs3>{{item.to}}</v-flex>
										<v-flex xs4>{{this.$numeral(item.price).format('0,0$')}}</v-flex>
										<v-flex xs2>
											<v-tooltip top>
												<v-icon slot="activator" size="10">help</v-icon>
												<span>{{item.description}}</span>
											</v-tooltip>
										</v-flex>
									</v-layout>									
								</v-card-text>
							</v-card>
						</v-flex>
					</v-layout>
				</v-flex>
			</v-layout>
		</v-container>
	</v-card>	
</template>

<script>
import {activeDeliveryUrl, getHeader} from '@/config/config.js'
import {mapState} from 'vuex'
import axios from 'axios'
export default {
	data: function() {
		return {
			title: 'Delivery Services',
			editedItem: {
				id: null,
				ranges: []
			},
			editedDefault: {
				id: null,
				ranges: []	
			},
			progress:true,
			disabled: true,
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Range List',
				disabled: true
			}
			]
		}
	},
	methods: {
		//Delete Delivery
		deleteItem (item) {
			var vm = this 
			vm.$swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel!',
				confirmButtonClass: 'btn error',
				cancelButtonClass: 'btn',
				buttonsStyling: false,
				reverseButtons: true
			}).then((result) => {
				if (result.value) {
					vm.$swal(
						'Deleted!',
						item.name+ ' services has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteDelivery', item)
						})
					} else{
						vm.$swal('Cancelled', '', 'error')
					}
				})
		},
		//Show Form
		showForm: async function(index, item) {
			var vm = this
			//Show Form Add
			if(index === -1 && item == null) {
				await this.$store.commit('LOADING_DELIVERY')
				await this.$store.dispatch('fetchCitiesDoesntHaveDelivery')
				const ranges = this.$store.getters.fetchRange
				var array = []
				ranges.map(item => {
					array.push({id: item.id, price: 0, from: item.from, to: item.to})
					return item
				})
				this.editedItem.ranges = array
				await this.$store.commit('LOADING_DELIVERY')
			}
			//Show Form Edit 
			else if(index > -1) {
				vm.cancel()
				await this.$store.dispatch('fetchCity')
				await this.$store.commit('EDIT_DELIVERY', item)
				this.editedItem.id = item.id
				var array = []
				item.deliveries.map(item => {
					array.push({id: item.id, price: item.price, from: item.from, to: item.to})
					return item
				})
				this.editedItem.ranges = array
			}
			this.$store.commit('DIALOG_DELIVERY')
		},
		//Cancel Form
		cancel: async function() {
			this.disabled = true
			await this.$store.commit('DIALOG_DELIVERY_CLOSE')
			this.editedItem = await Object.assign({}, this.editedDefault)

		},
		//Update Delivery
		save: async function(request) {
			var vm = this
			//Accept Update Delivery
			vm.$validator.validateAll().then( async function(result){
				if(result) {			
					await vm.$store.dispatch('updateDelivery', vm.editedItem)	
					vm.cancel()
				}
			})
		},
	},
	computed: {
		//Delivery Store
		...mapState({
			loading: state => state.deliveryStore.loading,
			editedIndex: state => state.deliveryStore.editedIndex,
			alert: state => state.deliveryStore.alert,
			dialog: state => state.deliveryStore.dialog,
			cities: state => Array.from(state.cityStore.cities),
			deliveries: state => Array.from(state.deliveryStore.deliveries),
		}),
		formTitle: function() {
			return this.editedIndex > -1 ? 'Edit Delivery' : 'Add Delivery'
		}
	},
	watch: {
		'editedItem.ranges': {
			handler: function(after, before) {
				this.disabled = true
				if(this.editedIndex > -1) {
					if(before.length > 0) {
						this.disabled = false
					}
				} else if(this.editedIndex == -1) {
					if(before.length>0) {
						this.disabled = false
					}
				}
			},
			deep:true
		},
		'editedItem.city_id': function(val, oldVal) {
			if(this.editedIndex == -1 && oldVal != null) {
				this.disabled == false
			}
		}
	},
	created: async function() {
		//Call Fetch
		await this.$store.dispatch('fetchDelivery')
		await this.$store.dispatch('fetchRange')
		this.progress = false
	},
	beforeDestroy: function() {
		//Clear Fetch 
		this.$store.commit('CLEAR_RANGE')
		this.$store.commit('CLEAR_CITY')
		this.$store.commit('CLEAR_DELIVERY')
	}
}
</script>

