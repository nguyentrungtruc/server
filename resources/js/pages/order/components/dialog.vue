<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			<d-alert :index="1"/>
			<v-card-text> 
				<v-form>
					<v-subheader>Thông tin cửa hàng</v-subheader>
				<v-container>
					<v-select
					:items          = "types"
					  v-model       = "editedItem.typeId"
					  label         = "Type of store"
					  item-text     = "name"
					  item-value    = "id"
					  prepend-icon  = "category"
					  v-validate    = "'required'"
					:error-messages = "errors.collect('type')"
					  data-vv-name  = "type"
					/>

					<v-text-field
					                        prepend-icon  = "store"
					                        label         = "Store name"
					                        v-model       = "editedItem.name"
					                        v-validate    = "'required'"
					                      :error-messages = "errors.collect('name')"
					                        data-vv-name  = "name"
					/>

					<v-text-field
					                        mask          = "(####) ### - ###"
					                        prepend-icon  = "phone"
					                        label         = "Phone"
					                        v-model       = "editedItem.phone"
					                        v-validate    = "'numeric|min:10|max:11'"
					                      :error-messages = "errors.collect('phone')"
					                        data-vv-name  = "phone"
					/>

					<v-text-field
					                        prepend-icon  = "access_time"
					                        label         = "Prepare time"
					                        v-model       = "editedItem.prepareTime"
					                        suffix        = "minutes"
					                        v-validate    = "'required|numeric|max:2'"
					                      :error-messages = "errors.collect('prepare time')"
					                        data-vv-name  = "prepare time"
					/>

					<v-select
					:items          = "cities"
					  v-model       = "editedItem.cityId"
					  label         = "Cities"
					  item-text     = "name"
					  item-value    = "id"
					  prepend-icon  = "map"
					  @change       = "changeCity"
					  v-validate    = "'required'"
					:error-messages = "errors.collect('city')"
					  data-vv-name  = "city"
					/>

					<v-select
					            v-if          = "editedItem.cityId != null"
					          :items          = "districts"
					            v-model       = "editedItem.districtId"
					            label         = "District"
					            item-text     = "name"
					            item-value    = "id"
					            prepend-icon  = "streetview"
					            v-validate    = "'required'"
					          :error-messages = "errors.collect('district')"
					            data-vv-name  = "district"
					/>

					<v-text-field
					  id            = "auto-complete"
					  ref           = "autocomplete"
					  prepend-icon  = "place"
					  label         = "Địa chỉ cửa hàng"
					  v-model       = "editedItem.address"
					  v-validate    = "'required|max:255'"
					:error-messages = "errors.collect('address')"
					  @focus        = "autocomplete"
					  data-vv-name  = "address"
					/>
					<GmapMap v-if="dialog" :center="{lat:editedItem.lat, lng:editedItem.lng}" :zoom="15" style="width: 100%; height: 300px">
						<GmapMarker	:position="{lat:editedItem.lat, lng:editedItem.lng}"
						:clickable="true" :draggable="true" @dragend="updateCenter"/>
					</GmapMap>
				</v-container>

				<v-divider></v-divider>

				<v-subheader>Cài đặt</v-subheader>

				<v-container>
					<v-flex xs12>
						<v-text-field
						                        prepend-icon  = "stars"
						                        label         = "Priority"
						                        v-model       = "editedItem.priority"
						                        v-validate    = "'required|numeric|max:2'"
						                      :error-messages = "errors.collect('priority')"
						                        data-vv-name  = "priority"
						/>
					</v-flex>		

					<v-flex xs12>
						<v-switch label="Ẩn/Hiện" v-model="editedItem.isShow" color="primary"></v-switch>
					</v-flex>
					
					<v-flex xs12>
						<v-checkbox
						label   = "Xác nhận hợp tác"
						color   = "primary"
						v-model = "editedItem.isVerify"
						/>
					</v-flex>	

					<v-flex xs12>
						<v-text-field
						                        prepend-icon  = "attach_money"
						                        label         = "Discount"
						                        v-model       = "editedItem.discount"
						                        v-validate    = "{required: editedItem.isVerify, numeric:true, max: 2}"
						                      :error-messages = "errors.collect('discount')"
						                        data-vv-name  = "discount"
						                        suffix        = "%"
						/>
					</v-flex>								

					<v-select
					:items          = "status"
					  v-model       = "editedItem.statusId"
					  label         = "Status"
					  item-text     = "name"
					  item-value    = "id"
					  prepend-icon  = "lens"
					  v-validate    = "'required'"
					:error-messages = "errors.collect('status')"
					  data-vv-name  = "status"
					/>
				</v-container>
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
import axios from 'axios'
import {mapState} from 'vuex'
import {Alert} from '@/components'
import {Geocoder} from '@/utils/maps'
export default {
	data: function() {
		return {
			editedItem: {
				typeId     : null,
				name       : '',
				phone      : '',
				prepareTime: 20,
				cityId     : null,
				districtId : null,
				address    : '',
				lat        : 10.0452,
				lng        : 105.7469,
				discount   : 0,
				priority   : 0,
				isShow     : false,
				isVerify   : false,
				statusId   : 1
			},
			defaultItem: {
				typeId     : null,
				name       : '',
				phone      : '',
				prepareTime: 20,
				cityId     : null,
				districtId : null,
				address    : '',
				lat        : 10.0452,
				lng        : 105.7469,
				discount   : 0,
				priority   : 0,
				isShow     : false,
				isVerify   : false,
				statusId   : 1
			},
			districts: [],
			progress : false
		}
	},
	components: {
		'd-alert': Alert
	},
	methods: {
		//AUTO COMPLETE
		autocomplete() {
			var   vm           = this
			const radius       = 50
			var   input        = document.getElementById('auto-complete')
			var   autocomplete = new google.maps.places.Autocomplete(input)
			autocomplete.addListener('place_changed', function() {
				var place = autocomplete.getPlace()
				if(!place.geometry) {
					vm.getPosition(vm.editedItem.address)
				} else {
					vm.setPlace(place)
				}					
			})
		},
		//SET PLACE
		setPlace(result) {
			var place = result
			if(place) {
				this.editedItem.address = place.formatted_address
				this.editedItem.lat     = place.geometry.location.lat()
				this.editedItem.lng     = place.geometry.location.lng()
			}
		},
		//GET POSITION
		getPosition(address) {
			var vm = this
			return new Promise((resolve, reject) => {	
				Geocoder.getPredictions(address).then(response => {
					Object.assign(vm.editedItem, {address: response[0].formatted_address.slice(0, -10), lat: response[0].geometry.location.lat(), lng: response[0].geometry.location.lng() })
					resolve(response)
				})
			})
		},
		//UPDATE CENTER
		updateCenter(center) {
			this.editedItem.lat = center.latLng.lat()
			this.editedItem.lng = center.latLng.lng()
		},
		//CLOSE DIALOG
		close: async function() {
			this.editedItem = await {...this.defaultItem}
			this.$store.dispatch('offAlert')
			this.$validator.reset()
			this.$store.commit('CLOSE_STORE_DIALOG')
		},
		//ACCEPT
		save:  function(request) {
			var vm = this
			vm.$validator.validateAll().then(async (result) => {
				if(result) {
					if(!vm.progress) {
						                                          vm.progress = true
						                                    const data        = {...this.editedItem}
						if(vm.editedIndex > -1) {
							vm.update(data)
						} else {
							vm.add(data)
						}
					}
				}
			})
		},
		//ACTION ADD NEW 
		add(data) {
			const url = `Store/Add`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateStore', response.data.store)
					this.close()
					this.success(data.name+' store has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' store has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			const url  = `Store/${id}/Edit`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateStore', response.data.store)
					this.close()
					this.success(data.name+' store has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' store has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//CHANGE CITY GET DISTRICT
		changeCity: function(id) {
			const url = `District/${id}/GetByCity`
			this.axios.get(url, null, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.districts = response.data.districts
				}
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
			cities     : state => state.city.cities,
			types      : state => state.type.types,
			status     : state => state.storeStatus.status,
			dialog     : state => state.store.dialog,
			editedIndex: state => state.store.editedIndex,
			item       : state => state.store.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Store': 'Edit Store'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
		dialog(val) {
			const {cityId} = this.editedItem
			if(val && this.editedIndex > -1) {
				this.changeCity(cityId)
			}
		}
	}
}
</script>

<style>
#map {
	width : 100%;
	height: 250px;
}
</style>