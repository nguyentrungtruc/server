<template>
	<div>
		<v-card>
			<v-container fluid grid-list-md>
				<v-layout row>
					<v-flex xs3>
						<div>ID:</div>
						<div>Title:</div>
						<div>Coupon:</div>
						<div>Notes:</div>
						<div>Discount percent:</div>
						<div>Max coupons:</div>
						<div>Coupon used:</div>
						<div>Start at:</div>
						<div>End at:</div>
						<div>Status:</div>
						<div>Active:</div>
					</v-flex>
					<v-flex xs9>
						<div>{{coupon.id}}</div>
						<div class="red--text"><strong>{{coupon.title}}</strong></div>
						<div class="blue--text"><strong>{{coupon.coupon}}</strong></div>
						<div>{{coupon.notes}}</div>
						<div class="red--text"><strong>{{percent(coupon.discount_percent)}}</strong></div>
						<div>{{coupon.max_coupons}}</div>
						<div>{{coupon.coupon_used}}</div>
						<div>{{coupon.started_at}}</div>
						<div>{{coupon.ended_at}}</div>
						<div :class="{'green--text': coupon.status.coupon_status_name == 'Còn hạn'}"><strong>{{coupon.status.coupon_status_name}}</strong></div>
						<div :class="{'red--text': coupon.actived == 0, 'green--text': coupon.actived == 1}">{{coupon.actived == 0 ? 'Not actived' : 'Actived'}}</div>
					</v-flex>
				</v-layout>
			</v-container>
			<v-card>
				<v-toolbar flat>
					<v-toolbar-title>
						Store list have coupon
					</v-toolbar-title>
					<v-spacer></v-spacer>
					<v-toolbar-items>
						<v-btn flat :disabled="disabled" @click.stop.prevent="update($route.params.coupon)">Update</v-btn>
					</v-toolbar-items>
				</v-toolbar>
				<v-container fluid grid-list-md>
					<v-alert :type="alert.type" dismissible v-model="alert.alert">
						{{alert.messages}}
					</v-alert>
					<v-layout row wrap >						
						<v-flex xs3 v-for="data in cities" :key="data.id" v-if="data.stores.length>0">
							<v-card>
								<v-toolbar flat dense>
									<v-checkbox :label="data.city_name" v-model="selected" @click.native="selectAll(data)" :value="data.id"></v-checkbox>
								</v-toolbar>				

								<v-container>
									<v-tooltip top  v-for="item in data.stores" :key="item.id">
										<v-checkbox slot="activator"  :label="item.store_name" v-model="storeIds" @change="select(data)" :value="item.id"></v-checkbox>
										<span>{{item.store_name}}</span>
									</v-tooltip>
								</v-container>
							</v-card>
						</v-flex>
					</v-layout>				
				</v-container>
			</v-card>			
		</v-card>
	</div>
</template>

<script>
import axios from 'axios'
import storeInformation from '@/mixins/storeInformation'
import {couponUrl, cityWithStoreUrl, getHeader} from '@/config/config'
import {mapState} from 'vuex'
export default {
	mixins: [storeInformation],
	data() {
		return {
			coupon: {
				status: {
					type: Object
				}
			},
			cities: [],
			selected: [],
			allSelected: false,
			storeIds: [],
			disabled: true,
			alert: {
				type: 'success',
				alert: false,
				messages: ''
			}
		}
	},
	methods: {
		fetchCityWithStores() {
			axios.get(cityWithStoreUrl, {headers: getHeader()}).then(response => {
				if(response.status === 200) {
					this.cities = Array.from(response.data)
				}
			})
		},
		selectAll: function(request) {
			var vm = this
			if(this.selected.indexOf(request.id) > -1) {
				Object.keys(request.stores).forEach(function (key) {
					if(vm.storeIds.indexOf(request.stores[key].id) < 0){
						vm.storeIds.push(request.stores[key].id)
					}
				});
			} else {
				Object.keys(request.stores).forEach(function (key) {
					if(vm.storeIds.indexOf(request.stores[key].id) > -1){
						vm.storeIds.splice(vm.storeIds.indexOf(request.stores[key].id), 1)
					}					
				});
			}
		},
		select: function(request) {
			if(this.selected.indexOf(request.id) > -1) {
				this.selected.splice(this.selected.indexOf(request.id), 1)
			}			
		},
		update: function(id) {
			const data = Object.assign({}, {storeIds: this.storeIds})
			axios.post(couponUrl+'/'+ id +'/updateStore', data, {headers: getHeader()}).then(response => {
				if(response.status == 201) {
					this.alert = {type: 'success', alert:true, messages: 'Coupon has been updated.'}
					this.disabled = true
					setInterval(() => {
						this.alert = {
							type: 'success',
							alert: false,
							messages: ''
						}
					},3000)
				}
			})
		}

	},
	computed: {
		...mapState({
			item: state => state.couponStore.coupon
		})
	},
	created() {
		this.fetchCityWithStores()
		this.$store.dispatch('showCoupon', this.$route.params.coupon)
	},
	watch: {
		item(val, oldVal) {
			var vm = this
			this.coupon = Object.assign({}, val)
			Object.keys(val.stores).forEach(function (key) {
				if(vm.storeIds.indexOf(val.stores[key].id) < 0){
					vm.storeIds.push(val.stores[key].id)
				}
			});
		},
		'storeIds': function(val, oldVal) {
			if(val.length != oldVal.length) {
				this.disabled = false
			}
		},
		'selected': function(val, oldVal) {
			if(val.length != oldVal.length) {
				this.disabled = false
			}
		}
	}
}

</script>

<style>

</style>