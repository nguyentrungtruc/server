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
					label="Type" 
					v-model="editedItem.type_name"
					:counter="50"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:50'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="Icon" 
					v-model="editedItem.type_icon"
					:counter="50"
					:error-messages="errors.collect('icon')"
					v-validate="'required|max:50'"
					data-vv-name="icon"
					></v-text-field>

					<v-switch
					:label="`Show: ${editedItem.type_show.toString()}`"
					v-model="editedItem.type_show"
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
				type_name: '',
				type_icon: '',
				type_show: true,
			},
			defaultItem: {
				type_name: '',
				type_icon: '',
				type_show: true,
			},
			disabled: true,
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Type
		save: function(request) {
			var vm = this
			var data = Object.assign({}, vm.editedItem)
			if (vm.editedIndex > -1) {
				//Accept Edit Type
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateType', data).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Type
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addType', data).then(response => {
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
			dialog: state => state.typeStore.dialog,
			editedIndex: state => state.typeStore.editedIndex,
			item: state => state.typeStore.editedItem,
			alert: state => state.typeStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Type' : 'Edit Type'
		}
	},
	watch: {
		name(val) {

		},
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.type_name': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem.type_icon': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.type_show': function(val, oldVal) {
			
			if(this.editedIndex > -1 && val != oldVal && oldVal != true ) {
				this.disabled = false
			} else if (this.editedIndex == -1 && val != oldVal ) {
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