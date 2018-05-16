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
					label="Country" 
					v-model="editedItem.country_name"
					:counter="50"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:50'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="Language" 
					v-model="editedItem.lang"
					:counter="2"
					:error-messages="errors.collect('lang')"
					v-validate="'required|max:2'"
					data-vv-name="lang"
					></v-text-field>

					<v-text-field 
					label="Dialing code" 
					v-model="editedItem.dialingcode"
					:counter="5"
					:error-messages="errors.collect('dialingcode')"
					v-validate="'required|max:5'"
					data-vv-name="dialingcode"
					></v-text-field>

					<v-switch
					:label="`Show: ${editedItem.country_show.toString()}`"
					v-model="editedItem.country_show"
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
import {getLocation} from '@/helpers/apiGetLocation.js'
import axios from 'axios'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			editedItem: {
				country_name: '',
				lang:'',
				lat: '',
				lng: '',
				dialingcode: 0,
				country_show: true,
			},
			defaultItem: {
				country_name: '',
				lang:'',
				lat: '',
				lng: '',
				dialingcode: 0,
				country_show: true,
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
		//Accept Update Country
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Country
				vm.$validator.validateAll().then(async function(result){
					if(result) {			
						await getLocation(vm.editedItem.country_name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()		
						})
						vm.$store.dispatch('updateCountry', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Country
				vm.$validator.validateAll().then(async (result) => {
					if(result) {						
						await getLocation(vm.editedItem.country_name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()		
						})
						vm.$store.dispatch('addCountry', vm.editedItem).then(response => {
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
			dialog: state => state.countryStore.dialog,
			editedIndex: state => state.countryStore.editedIndex,
			item: state => state.countryStore.editedItem,
			alert: state => state.countryStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Country' : 'Edit Country'
		}
	},
	watch: {
		name(val) {

		},
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.country_name': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem.lang': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.dialingcode': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.country_show': function(val, oldVal) {

			if(val != oldVal) {
				this.disabled = false
			}	
			
			// if(this.editedIndex > -1 && oldVal != '') {
			// 	this.disabled = false
			// } else if(this.editedIndex == -1 && val != '') {
			// 	this.disabled = false
			// }
		}
	},
	mounted() {

	}
}
</script>

<style>

</style>