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
				{{editedItem}}
				<v-form>
					<v-select
					label="City"
					:items="cities"
					v-model="editedItem.city_id"
					autocomplete
					:error-messages="errors.collect('city')"
					v-validate="'required'"
					data-vv-name="city"
					item-text="city_name"
					item-value="id"
					></v-select>

					<v-text-field 
					label="District name" 
					v-model="editedItem.district_name"
					:counter="50"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:50'"
					data-vv-name="name"
					></v-text-field>

					<v-switch
					:label="`Show: ${editedItem.district_show.toString()}`"
					v-model="editedItem.district_show"
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
				district_name: '',
				lat: '',
				lng: '',
				district_show: true,
				city_id: 0,
			},
			defaultItem: {
				district_name: '',
				lat: '',
				lng: '',
				district_show: true,
				city_id: 0,
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
		//Update District
		save: function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit District
				vm.$validator.validateAll().then(async function(result){
					if(result) {			
						await getLocation(vm.editedItem.district_name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()
						})
						vm.$store.dispatch('updateDistrict', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add District
				vm.$validator.validateAll().then(async (result) => {
					if(result) {						
						await getLocation(vm.editedItem.district_name).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()
						})
						vm.$store.dispatch('addDistrict', vm.editedItem).then(response => {
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
			cities: state => Array.from(state.cityStore.cities),
			dialog: state => state.districtStore.dialog,
			editedIndex: state => state.districtStore.editedIndex,
			item: state => state.districtStore.editedItem,
			alert: state => state.districtStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New District' : 'Edit District'
		}
	},
	watch: {
		item (val) {
			this.editedItem = Object.assign(this.editedItem, val)
		},
		'editedItem.district_name': function(val, oldVal) {

			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.district_show': function(val, oldVal) {
			if(val != oldVal) {
				this.disabled = false
			}	
		},
		'editedItem.city_id': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != 0) {
				this.disabled = false
			} else if(this.editedIndex == -1 && oldVal == 0) {
				this.disabled = false
			}
		}
	},
	created() {
		this.$store.dispatch('fetchCity').then(response => {
			if(response.status === 200) {
				this.$store.dispatch('fetchDistrict')
			}
		})
	},
	mounted() {

	}
}
</script>

<style>

</style>