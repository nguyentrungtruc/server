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
					<v-select
					label="Country"
					:items="countries"
					v-model="editedItem.country_id"
					autocomplete
					:error-messages="errors.collect('country')"
					v-validate="'required'"
					data-vv-name="country"
					item-text="country_name"
					item-value="id"
					></v-select>

					<v-text-field 
					label="City name" 
					v-model="editedItem.name"
					:counter="50"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:50'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="Zip code" 
					v-model="editedItem.zipcode"
					:counter="10"
					:error-messages="errors.collect('zipcode')"
					v-validate="'required|max:10'"
					data-vv-name="zipcode"
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
import {getLocation} from '@/helpers/apiGetLocation'
import {mapState} from 'vuex'
export default {
	data: function() {
		return {
			editedItem: {
				name: '',
				zipcode:'',
				lat: '',
				lng: '',
				show: true,
				country_id: 0,
			},
			defaultItem: {
				name: '',
				zipcode:'',
				lat: '',
				lng: '',
				show: true,
				country_id: 0,
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
				//Accept Edit City
				vm.$validator.validateAll().then(async function(result){
					if(result) {			
						await getLocation(vm.editedItem.name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()
						})
						vm.$store.dispatch('updateCity', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add City
				vm.$validator.validateAll().then(async (result) => {
					if(result) {						
						await getLocation(vm.editedItem.name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()
						})
						vm.$store.dispatch('addCity', vm.editedItem).then(response => {
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
			countries: state   => Array.from(state.countryStore.countries),
			dialog: state      => state.cityStore.dialog,
			editedIndex: state => state.cityStore.editedIndex,
			item: state        => state.cityStore.editedItem,
			alert: state       => state.cityStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New City' : 'Edit City'
		}
	},
	watch: {
		name(val) {

		},
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
		'editedItem.zipcode': function(val, oldVal) {
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
		},
		'editedItem.country_id': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != 0) {
				this.disabled = false
			} else if(this.editedIndex == -1 && oldVal == 0) {
				this.disabled = false
			}
		}
	},
	created() {
		this.$store.dispatch('fetchCountry').then(response => {
			if(response.status === 200) {
				this.$store.dispatch('fetchCity')
			}
		})
	},
	mounted() {

	}
}
</script>

<style>

</style>