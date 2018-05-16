<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			
			<v-alert :type="alert.type" dismissible v-model="alert.show">
				{{alert.message}}
			</v-alert>

			<v-card-text>
				<v-form>
					<v-text-field 
					label="Payment Method" 
					v-model="editedItem.paymentName"
					:counter="25"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:25'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="Description" 
					v-model="editedItem.description"
					:counter="255"
					:error-messages="errors.collect('description')"
					v-validate="'max:255'"
					data-vv-name="description"
					></v-text-field>
					
					<v-switch
					:label="`Show: ${editedItem.show.toString()}`"
					v-model="editedItem.show"
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
import axios from 'axios'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			editedItem: {
				paymentName: '',
				description: '',
				show: false
			},
			defaultItem: {
				paymentName: '',
				description: '',
				show: false
			},
			disabled: true
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_PAYMENT_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Payment Method
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Payment Method
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updatePayment', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Payment Method
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addPayment', vm.editedItem).then(response => {
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
			dialog: state      => state.paymentStore.dialog,
			editedIndex: state => state.paymentStore.editedIndex,
			item: state        => state.paymentStore.editedItem,
			alert: state       => state.paymentStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Payment Method' : 'Edit Payment Method'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.paymentName': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem.description': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.show': function(val, oldVal) {
			if(val != oldVal) {
				this.disabled = false
			}	
		}
	}
}
</script>

<style>

</style>