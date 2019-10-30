<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			<d-alert :index="1"/>
			<v-card-text> 
				<v-form>
					<v-select
					                  label         = "Country"
					                  v-model       = "editedItem.countryId"
					                :items          = "countries"
					                  item-text     = "name"
					                  item-value    = "id"
					                :error-messages = "errors.collect('country')"
					                  v-validate    = "'required'"
					                  data-vv-name  = "country"
					></v-select>
					
					<v-text-field 
					                  label         = "City name"
					                  v-model       = "editedItem.name"
					                :counter        = "50"
					                :error-messages = "errors.collect('name')"
					                  v-validate    = "'required|max:50'"
					                  data-vv-name  = "name"
					></v-text-field>

					<v-text-field 
					                  label         = "Zip code"
					                  v-model       = "editedItem.zipCode"
					                :counter        = "10"
					                :error-messages = "errors.collect('zipcode')"
					                  v-validate    = "'required|max:10'"
					                  data-vv-name  = "zipcode"
					></v-text-field>

					<v-switch
					:label    = "`Show: ${editedItem.isShow.toString()}`"
					  v-model = "editedItem.isShow"
					  color   = "primary"></v-switch>
				</v-form>
			</v-card-text>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn class="ma-2" small rounded text @click.native="close">Cancel</v-btn>
				<v-btn class="ma-2" small rounded text color="blue darken-1" @click.native="save" :loading="progress">Save</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import {ErrorBag, Validator} from 'vee-validate'
import {mapState} from 'vuex'
import {Alert} from '@/components'
import {Geocoder} from '@/utils/maps'
import {RepositoryFactory} from '@/services/Repository/index'
const CityRepository = RepositoryFactory.get('cities')
export default {
	data: function() {
		return {
			editedItem: {
				name   : '',
				lat    : '',
				lng    : '',
				zipCode: 0,
				isShow : true,
			},
			defaultItem: {
				name   : '',
				lat    : '',
				lng    : '',
				zipCode: 0,
				isShow : true,
			},
			progress: false
		}
	},
	components: {
		'd-alert': Alert
	},
	methods: {
		//Close Dialog
		close: async function() {
			this.editedItem = await {...this.defaultItem}
			this.$validator.reset()	
			this.$store.dispatch('offAlert')
			this.$store.commit('CLOSE_CITY_DIALOG')
		},
		//Accept Update City
		save: function(request) {
			var vm = this
			vm.$validator.validateAll().then(async (result) => {
				if(result) {
					if(!vm.progress) {
						vm.progress = true
						await vm.getPosition(vm.editedItem.name)
						const data = {...this.editedItem}
						if(vm.editedIndex > -1) {
							vm.update(data)
						} else {
							vm.add(data)
						}
					}
				}
			})
		},
		//ACTION ADD NEW CITY
		add(data) {
			CityRepository.create(data).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateCity', response.data.city)
					this.close()
					this.success(data.name+' city has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' city has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE CITY
		update(data) {
			const {id} = data
			CityRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateCity', response.data.city)
					this.close()
					this.success(data.name+' city has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' city has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//GET POSITION
		getPosition(address) {
			var vm = this
			return new Promise((resolve, reject) => {	
				Geocoder.getPredictions(address).then(response => {
					Object.assign(vm.editedItem, {lat: response[0].geometry.location.lat(), lng: response[0].geometry.location.lng() })
					resolve(true)
				})
			})
		},
		//ACTION ALERT WHEN SUCCESS
		success(message) {
			this.$store.dispatch('onAlert', {close: true, index: 0, message: message, routeName: this.$route.name, show: true, type: 'success'})
		},
		//ACTION ALERT WHEN FAIL
		fail(message) {
			this.$store.dispatch('onAlert', {close: false, index: 1, message: message, routeName: this.$route.name, show: true, type: 'error'})
		}
	},
	computed: {
		...mapState({
			countries  : state => state.country.countries,
			dialog     : state => state.city.dialog,
			editedIndex: state => state.city.editedIndex,
			item       : state => state.city.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New City': 'Edit City'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
