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
		</v-card-title>
		
		<v-alert :type="alert.type" dismissible v-model="alert.show">
			{{alert.message}}
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
									disabled
									></v-select>
									<v-layout row wrap>
										<v-flex xs12>
											<v-checkbox
											:label="`Delivery: ${editedItem.deliveryActived ? 'actived' : 'deactive'}`"
											v-model="editedItem.deliveryActived"
											color="success"
											></v-checkbox>
										</v-flex>
										
										<v-flex xs12>
											<v-text-field
											name="input-3"
											label="Min amount"
											v-model="editedItem.minAmount"
											></v-text-field>
										</v-flex>
										
										<v-flex xs12>
											<v-text-field
											name="input-3"
											v-model="editedItem.maxAmount"
											label="Max amount"
											></v-text-field>
										</v-flex>
										
										<v-flex xs12>
											<v-text-field
											name="input-3"
											v-model="editedItem.minRange"
											label="Min range"
											></v-text-field>
										</v-flex>

										<v-flex xs12>
											<v-text-field
											name="input-3"
											v-model="editedItem.maxRange"
											label="Max range"
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
						<v-flex xs12 sm6 md4 v-for="(data, i) in items" :key="i">
							<v-card>
								<v-toolbar dense class="elevation-0" dark color="primary">
									<v-tooltip top>
										<v-avatar slot="activator" size="16" class="grey lighten-4">
											<v-icon :color="data.service.deliveryActived ? 'green darken-3' : 'grey'">beenhere</v-icon>
										</v-avatar>					
										<span>{{data.service.deliveryActived ? 'Đã mở dịch vụ vận chuyển' : 'Đã tắt dịch vụ vận chuyển'}}</span>
									</v-tooltip>
									<v-toolbar-title>
										{{data.name}}
									</v-toolbar-title>									
									<v-spacer></v-spacer>
									<v-tooltip top>
										<v-btn slot="activator" icon @click="showForm(i, data)" dark>
											<v-icon>edit</v-icon>
										</v-btn>
										<span>Edit Service</span>
									</v-tooltip>
									
								</v-toolbar>
								<v-card-text>
									<v-layout row wrap>
										<v-flex xs8>Delivery actived</v-flex>
										<v-flex xs4>{{data.service.deliveryActived}}</v-flex>
										<v-flex xs8>Min amount</v-flex>
										<v-flex xs4>{{data.service.minAmount}}</v-flex>

										<v-flex xs8>Max amount</v-flex>
										<v-flex xs4>{{data.service.maxAmount}}</v-flex>

										<v-flex xs8>Min range</v-flex>
										<v-flex xs4>{{data.service.minRange}}</v-flex>

										<v-flex xs8>Max range</v-flex>
										<v-flex xs4>{{data.service.maxRange}}</v-flex>

										<v-flex xs8>Start time</v-flex>
										<v-flex xs4>{{data.service.startTime}}</v-flex>
										<v-flex xs8>End time</v-flex>
										<v-flex xs4>{{data.service.endTime}}</v-flex>
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
			title: 'Service List',
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Service List',
				disabled: true
			}
			],
			items: [],
			editedItem: {
				id: null,
				deliveryActived: null,
				maxAmount: 0,
				minAmount: 0,
				minRange: 0,
				maxRange: 0,
				startTime: null,
				endTime: null
			},
			editedDefault: {
				id: null,
				deliveryActived: null,
				maxAmount: 0,
				minAmount: 0,
				minRange: 0,
				maxRange: 0,
				startTime: null,
				endTime: null
			},
			progress:true,
			disabled: true,
			dialog: false,
			editedIndex: -1,
			alert: {
				show: false,
				type: 'success',
				message: ''
			}
			
		}
	},
	methods: {
		//Show Form
		showForm: async function(index, item) {
			var vm = this
			//Show Form Edit 
			if(index > -1) {
				// vm.cancel()
				vm.editedIndex = vm.items.indexOf(item)
				vm.editedItem = Object.assign({}, {id: item.id, deliveryActived: item.service.deliveryActived, maxAmount: item.service.maxAmount, minAmount: item.service.minAmount, minRange: item.service.minRange, maxRange: item.service.maxRange})
			}
			vm.dialog = true
		},
		//Cancel Form
		cancel: async function() {
			this.disabled = true
			this.dialog = false
			this.editedIndex = -1
			this.editedItem = await Object.assign({}, this.editedDefault)
		},
		//Update Service
		save: async function(request) {
			var vm = this
			//Accept Update Service
			vm.$validator.validateAll().then( async function(result){
				if(result) {			
					const data = vm.editedItem
					axios.post('/api/Dofuu-Service/UpdateService', data, {headers: getHeader()}).then(response => {
						if(response.status == 200){
							Object.assign(vm.items[vm.editedIndex], response.data.data)
							vm.alert = Object.assign({}, {show:true, type: 'success', message: response.data.message})
							vm.cancel()
						}
					}).catch(error => {

					})
				}
			})
		},
		//Active Delivery
		activeDelivery: async function(item) {
			var vm = this

			const data = Object.assign({}, item)
			vm.$swal({
				title: 'Are you sure?',
				text: "You want to change delivery status.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Save',
				cancelButtonText: 'Cancel',
				confirmButtonClass: 'btn primary',
				cancelButtonClass: 'btn',
				buttonsStyling: false,
				reverseButtons: true
			}).then( async (result) => {
				if (result.value) {
					vm.$swal(
						'Deleted!',
						item.name+ ' delivery service has been changed.',
						'success'
						).then(async () => {
							await axios.post(activeDeliveryUrl+'/'+item.id, data, {headers: getHeader()}).then(response => {
								if(response.status === 200) {
									vm.$store.commit('UPDATE_DELIVERY', response.data)
								}
							})
							vm.cancel()
						})
					} else{
						await vm.$swal('Cancelled', '', 'error')
						vm.cancel()
					}
				})
		}
	},
	computed: {
		//Delivery Store
		...mapState({
			cities: state => Array.from(state.cityStore.cities),
		}),
		formTitle: function() {
			return this.editedIndex > -1 ? 'Edit Service' : 'Add Service'
		}
	},
	watch: {
		'cities': function(val, oldVal) {
			if(val) {
				this.items = JSON.parse(JSON.stringify(val))
			}
		},
		'editedItem.deliveryActived': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != null ) {
				this.disabled = false
			}
		},
		'editedItem.minAmount': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != 0) {
				this.disabled = false
			}
		},
		'editedItem.maxAmount': function(val,oldVal) {
			if(this.editedIndex > -1 && oldVal != 0) {
				this.disabled = false
			}
		},
		'editedItem.minRange': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != 0) {
				this.disabled = false
			}
		},
		'editedItem.maxRange': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != 0) {
				this.disabled = false
			}
		}
	},
	created: async function() {
		//Call Fetch
		await this.$store.dispatch('fetchCity')
		this.progress = false
	},
	beforeDestroy: function() {
		//Clear Fetch 
		this.$store.commit('CLEAR_CITY')
	}
}
</script>

