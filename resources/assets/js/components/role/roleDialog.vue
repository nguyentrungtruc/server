<template>
	<v-dialog v-model="dialog" persistent max-width="500px">
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
					label="Role name" 
					v-model="editedItem.name"
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
					v-validate="'required|max:255'"
					data-vv-name="description"
					></v-text-field>
				</v-form>
			</v-card-text>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="blue darken-1" flat @click.native="close">Cancel</v-btn>
				<v-btn color="blue darken-1" flat @click.native="save" :disabled="disabled" >Save</v-btn>
			</v-card-actions>
			{{editedItem}}
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
				name: '',
				description:''
			},
			defaultItem: {
				name: '',
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
			this.$store.commit('DIALOG_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Accept Update Role
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Role
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateRole', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Role
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addRole', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						}).catch(errors => {
							if(errors.response.status == 422) {
							
							} 
						})
					}
				})				
			}
		}
	},
	computed: {
		...mapState({
			dialog: state => state.roleStore.dialog,
			editedIndex: state => state.roleStore.editedIndex,
			item: state => state.roleStore.editedItem,
			alert: state => state.roleStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.name': function(val, oldVal) {
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
	},
	mounted() {

	}
}
</script>

<style>

</style>