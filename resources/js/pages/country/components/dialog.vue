<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			<d-alert :index="1"/>
			<v-card-text> 
				<v-form>
					<v-text-field 
				                label         = "Country name"
				                v-model       = "editedItem.name"
				              :error-messages = "errors.collect('name')"
				                v-validate    = "'required|max:50'"
				                data-vv-name  = "name"
					persistent-hint
					></v-text-field>

					<v-text-field 
					                label         = "Language"
					                v-model       = "editedItem.lang"
					              :counter        = "2"
					              :error-messages = "errors.collect('lang')"
					                v-validate    = "'required|max:2'"
					                data-vv-name  = "lang"
					></v-text-field>

					<v-text-field 
						                label         = "Dialing code"
						                v-model       = "editedItem.dialingCode"
						              :counter        = "5"
						              :error-messages = "errors.collect('dialingcode')"
						                v-validate    = "'required|max:5'"
						                data-vv-name  = "dialingcode"
					></v-text-field>

					<v-switch
					:label    = "`Show: ${editedItem.isShow.toString()}`"
					  v-model = "editedItem.isShow"
					  color   = "primary">
						<template v-slot:label>
							<div>Show: <v-icon>{{editedItem.isShow ? 'visibility' : 'visibility_off'}}</v-icon></div>
						</template>  
					</v-switch>
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
const CountryRepository = RepositoryFactory.get('countries')
export default {
	data: function() {
		return {
			editedItem: {
				name       : '',
				lang       : '',
				lat        : '',
				lng        : '',
				dialingCode: 0,
				isShow     : true,
			},
			defaultItem: {
				name       : '',
				lang       : '',
				lat        : '',
				lng        : '',
				dialingCode: 0,
				isShow     : true,
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
			this.$store.dispatch('offAlert')
			this.$validator.reset()	
			this.$store.commit('CLOSE_COUNTRY_DIALOG')
		},
		//Accept Update Country
		save:  function(request) {
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
		//ACTION ADD NEW COUNTRY
		add(data) {
			CountryRepository.create(data).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateCountry', response.data.country)
					this.close()
					this.success(data.name+' country has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' country has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE COUNTRY
		update(data) {
			const {id} = data
			CountryRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateCountry', response.data.country)
					this.close()
					this.success(data.name+' country has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' country has already been taken.')
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
			dialog     : state => state.country.dialog,
			editedIndex: state => state.country.editedIndex,
			item       : state => state.country.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Country': 'Edit Country'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
