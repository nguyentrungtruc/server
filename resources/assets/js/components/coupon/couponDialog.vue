<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			
			<v-alert :type="alert.type" dismissible v-model="alert.alert">
				{{alert.messages}}
			</v-alert>

			<v-card-text>
				<v-form>
					<v-text-field 
					prepend-icon="text_format"
					label="Title" 
					v-model="editedItem.title"
					:error-messages="errors.collect('title')"
					v-validate="'required|max:254'"
					data-vv-name="title"
					></v-text-field>					
					
					<v-text-field 
					prepend-icon="redeem"
					label="Coupon" 
					v-model="editedItem.coupon"
					:error-messages="errors.collect('coupon')"
					v-validate="'required|max:15'"
					data-vv-name="coupon"
					></v-text-field>	

					<v-text-field 
					prepend-icon="subject"
					label="Notes" 
					v-model="editedItem.notes"
					:error-messages="errors.collect('notes')"
					v-validate="'required'"
					data-vv-name="notes"
					></v-text-field>	

					<v-text-field 
					prepend-icon="looks_5"
					label="Discount percent" 
					v-model="editedItem.discount_percent"
					:error-messages="errors.collect('discount_percent')"
					v-validate="'required|numeric'"
					data-vv-name="discount_percent"
					suffix="%"
					></v-text-field>	

					<v-text-field 
					prepend-icon="vertical_align_top"
					label="Max coupons" 
					v-model="editedItem.max_coupons"
					:error-messages="errors.collect('quantity')"
					v-validate="'required|numeric'"
					data-vv-name="quantity"
					></v-text-field>	

					<v-menu
					ref="start"
					lazy
					:close-on-content-click="false"
					v-model="start"
					transition="scale-transition"
					offset-y
					full-width
					:nudge-right="40"
					min-width="290px"
					:return-value.sync="editedItem.started_at"
					>
					<v-text-field
					slot="activator"
					label="Start time"
					v-model="editedItem.started_at"
					prepend-icon="event"
					readonly
					></v-text-field>
					<v-date-picker v-model="editedItem.started_at" no-title scrollable>
						<v-spacer></v-spacer>
						<v-btn flat color="primary" @click="start = false">Cancel</v-btn>
						<v-btn flat color="primary" @click="$refs.start.save(editedItem.started_at)">OK</v-btn>
					</v-date-picker></v-menu>

					<v-menu
					ref="end"
					lazy
					:close-on-content-click="false"
					v-model="end"
					transition="scale-transition"
					offset-y
					full-width
					:nudge-right="40"
					min-width="290px"
					:return-value.sync="editedItem.ended_at"
					>
					<v-text-field
					slot="activator"
					label="End time"
					v-model="editedItem.ended_at"
					prepend-icon="event"
					readonly
					></v-text-field>
					<v-date-picker v-model="editedItem.ended_at" no-title scrollable>
						<v-spacer></v-spacer>
						<v-btn flat color="primary" @click="end = false">Cancel</v-btn>
						<v-btn flat color="primary" @click="$refs.end.save(editedItem.ended_at)">OK</v-btn>
					</v-date-picker></v-menu>

					<v-select
					prepend-icon="lens"
					label="Status"
					chips
					:items="status"
					v-model="editedItem.status_id"
					:error-messages="errors.collect('status')"
					v-validate="'required'"
					data-vv-name="status"
					item-text="coupon_status_name"
					item-value="id"
					>
					<template slot="selection" slot-scope="data">
						<v-chip outline :color="data.item.color">
							<v-icon :color="data.item.color">lens</v-icon> {{data.item.coupon_status_name}}
						</v-chip>
					</template>
					<template slot="item" slot-scope="data">
						<template >
							<v-list-tile>
								<v-icon :color="data.item.color">lens</v-icon>
							</v-list-tile>
							<v-list-tile-content>
								<v-list-tile-title v-html="data.item.coupon_status_name"></v-list-tile-title>
							</v-list-tile-content>
						</template>
					</template>
				</v-select>
				<v-switch
				:label="`Show: ${editedItem.actived.toString()}`"
				v-model="editedItem.actived"
				color="primary"></v-switch>
			</v-form>
		</v-card-text>

		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click.native="close">Cancel</v-btn>
			<v-btn color="blue darken-1" flat @click.native="save" :disabled="disabled" >Save</v-btn>
		</v-card-actions>
	</v-card>
</v-dialog>
</template>

<script>
import {ErrorBag, Validator} from 'vee-validate'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			editedItem: {
				title: '',
				coupon: '',
				notes: '',
				discount_percent: 0,
				max_coupons: 0,
				started_at: null,
				ended_at: null,
				actived: false,
				status_id: 1
			},
			defaultItem: {
				title: '',
				coupon: '',
				notes: '',
				discount_percent: 0,
				max_coupons: 0,
				started_at: null,
				ended_at: null,
				actived: false,
				status_id: 1
			},
			disabled: true,
			start:false,
			end: false
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.stores = []
			this.$store.commit('DIALOG_COUPON_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Coupon
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Coupon
				vm.$validator.validateAll().then(async function(result){
					if(result) {			
						vm.$store.dispatch('updateCoupon', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Coupon
				vm.$validator.validateAll().then(async (result) => {
					if(result) {
						vm.$store.dispatch('addCoupon', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})
					}
				})				
			}
		}
	},
	computed: {
		...mapState({
			status: state => Array.from(state.couponStatusStore.couponStatus),
			dialog: state => state.couponStore.dialog,
			editedIndex: state => state.couponStore.editedIndex,
			item: state => state.couponStore.editedItem,
			alert: state => state.couponStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Coupon' : 'Edit Coupon'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
			this.editedItem.started_at = val.started_at.slice(0,10)
			this.editedItem.ended_at = val.ended_at.slice(0,10)
		},
		'editedItem.title': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.coupon': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.notes': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.discount_percent': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != 0) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != 0) {
				this.disabled = false
			}
		},
		'editedItem.max_coupons': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != 0) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != 0) {
				this.disabled = false
			}
		},
		'editedItem.started_at': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != null) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != null) {
				this.disabled = false
			}
		},
		'editedItem.ended_at': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != null) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != null) {
				this.disabled = false
			}
		}
	},
	created() {
		this.$store.dispatch('fetchCity').then(response => {
			if(response.status === 200) {
				this.$store.dispatch('fetchCouponStatus')
				this.$store.dispatch('fetchCoupon')
			}
		})
	},
	mounted() {

	}
}
</script>

<style>

</style>