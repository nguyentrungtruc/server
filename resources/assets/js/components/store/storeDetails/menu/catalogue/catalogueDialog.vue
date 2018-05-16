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
					label="Name" 
					v-model="editedItem.catalogue"
					:counter="45"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:45'"
					data-vv-name="name"
					></v-text-field>
					<v-text-field 
					label="English name" 
					v-model="editedItem._catalogue"
					:counter="45"
					:error-messages="errors.collect('English name')"
					v-validate="'max:45'"
					data-vv-name="English name"
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
				catalogue: '',
				_catalogue: ''
			},
			defaultItem: {
				catalogue: '',
				_catalogue: ''
			},
			disabled: true,
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_CATALOGUE_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Status
		save: function(request) {
			var vm = this
			Object.assign(this.editedItem, {sid: this.$route.params.sid})
			if (vm.editedIndex > -1) {
				//Accept Edit Status
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateCatalogue', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Status
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addCatalogue', vm.editedItem).then(response => {
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
			dialog: state => state.catalogueStore.dialog,
			editedIndex: state => state.catalogueStore.editedIndex,
			item: state => state.catalogueStore.editedItem,
			alert: state => state.catalogueStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Catalogue' : 'Edit Catalogue'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.catalogue': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem._catalogue': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},

	},
	mounted() {

	}
}
</script>

<style>

</style>