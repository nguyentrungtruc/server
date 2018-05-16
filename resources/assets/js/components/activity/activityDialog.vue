<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			
			<v-alert :type="alert.type" dismissible v-model="alert.show">
				{{alert.messages}}
			</v-alert>

			<v-card-text> 
				<v-form>
					<v-text-field 
					label="Days of week" 
					v-model="editedItem.daysofweek"
					:error-messages="errors.collect('day')"
					v-validate="'required|max:24'"
					data-vv-name="day"
					></v-text-field>

					<v-text-field 
					label="Number" 
					v-model="editedItem.number"
					:error-messages="errors.collect('number')"
					v-validate="'required|numeric'"
					data-vv-name="number"
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
				daysofweek: '',
				number: ''
			},
			defaultItem: {
				daysofweek: '',
				number: ''
			},
			disabled: true,
		}
	},
	methods: {
		//Close Dialog
		close: async function() {
			this.disabled = true
			this.editedItem = await Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_CLOSE_ACTIVITY')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Accept Update Activity
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Activity
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateActivity', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})

					}
				})
			} else {
				//Accept Add Role
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addActivity', vm.editedItem).then(response => {
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
			dialog: state      => state.activityStore.dialog,
			editedIndex: state => state.activityStore.editedIndex,
			item: state        => state.activityStore.editedItem,
			alert: state       => state.activityStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Activity' : 'Edit Activity'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.daysofweek': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}			
		},
		'editedItem.number': function(val, oldVal) {
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