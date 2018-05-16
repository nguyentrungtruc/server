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
					label="Status" 
					v-model="editedItem.coupon_status_name"
					:counter="25"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:25'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="Description" 
					v-model="editedItem.coupon_status_description"
					:counter="255"
					:error-messages="errors.collect('description')"
					v-validate="'max:255'"
					data-vv-name="description"
					></v-text-field>
					
					<v-select
					label="Color"
					:items="colors"
					v-model="editedItem.color"
					chips
					item-text="name"
					item-value="color"
					>
					<template slot="selection" slot-scope="data">
						<v-chip outline :color="data.item.color">
							<v-icon :color="data.item.color">lens</v-icon> {{data.item.name}}
						</v-chip>
					</template>
					<template slot="item" slot-scope="data">
						<template >
							<v-list-tile>
								<v-icon :color="data.item.color">lens</v-icon>
							</v-list-tile>
							<v-list-tile-content>
								<v-list-tile-title v-html="data.item.name"></v-list-tile-title>
							</v-list-tile-content>
						</template>
					</template>
				</v-select>
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
import axios from 'axios'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			editedItem: {
				coupon_status_name: '',
				coupon_status_description: '',
				color:''
			},
			defaultItem: {
				coupon_status_name: '',
				coupon_status_description: '',
				color: ''
			},
			disabled: true,
			colors: [
			{
				name: 'red',
				color: 'red accent-4'
			},
			{
				name: 'green',
				color:'green accent-4'
			},
			{
				name: 'grey',
				color: 'grey'
			},
			{
				name: 'yellow',
				color: 'yellow accent-4'
			}
			]
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_COUPON_STATUS_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Status
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Status
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateCouponStatus', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Status
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addCouponStatus', vm.editedItem).then(response => {
							if(response.status == 200) {
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
			dialog: state => state.couponStatusStore.dialog,
			editedIndex: state => state.couponStatusStore.editedIndex,
			item: state => state.couponStatusStore.editedItem,
			alert: state => state.couponStatusStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Status' : 'Edit Status'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.coupon_status_name': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem.coupon_status_description': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.color': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		}
	},
	mounted() {

	}
}
</script>

<style>

</style>