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
					label="Range from" 
					v-model.trim.number="editedItem.from"
					:counter="4"
					:error-messages="errors.collect('Range from')"
					v-validate="{ required: true, max: 4, regex: /\d+\,?\d*$/ }"
					data-vv-name="Range from"
					suffix="Km"
					type="number"
					></v-text-field>

					<v-text-field 
					label="Range to" 
					v-model.trim.number="editedItem.to"
					:counter="4"
					:error-messages="errors.collect('Range to')"
					v-validate="{ required: true, max: 4, regex: /\d+\,?\d*$/ }"
					data-vv-name="Range to"
					suffix="Km"
					type="number"
					></v-text-field>

					<v-text-field 
					label="Description" 
					v-model="editedItem.description"
					:counter="255"
					:error-messages="errors.collect('description')"
					v-validate="'max:255'"
					data-vv-name="description"
					@keyup.enter="save"
					></v-text-field>
					
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
				from: 0,
				to: 0,
				description: ''
			},
			defaultItem: {
				from: '',
				to: '',
				description: ''
			},
			disabled: true,
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_RANGE_CLOSE')
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
						vm.$store.dispatch('updateRange', vm.editedItem).then(response => {
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
						vm.$store.dispatch('addRange', vm.editedItem).then(response => {
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
			dialog: state => state.rangeStore.dialog,
			editedIndex: state => state.rangeStore.editedIndex,
			item: state => state.rangeStore.editedItem,
			alert: state => state.rangeStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Range' : 'Edit Range'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.from': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.to': function(val, oldVal) {
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
		}
	},
	mounted() {

	}
}
</script>

<style>

</style>