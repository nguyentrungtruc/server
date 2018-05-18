<template>
	<v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false">
		<v-card color="grey lighten-2">
			<v-toolbar dark color="primary">
				<v-btn icon @click.native="close" dark>
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title><span class="headline">{{ formTitle }}</span></v-toolbar-title>
				<v-spacer></v-spacer>
				<v-toolbar-items>
					<v-btn dark flat @click.native="save" :disabled="disabled">Save</v-btn>
				</v-toolbar-items>
			</v-toolbar>
			<v-card-text>
				<v-container fluid grid-list-md>
					<v-layout row wrap :class="{'justify-center': editedIndex > -1}">
						<v-flex d-flex xs12 sm6 md6 v-if="editedIndex===-1">
							<v-card>
								<v-card-title primary class="title">User Information</v-card-title>
								<v-card-text>
									<v-form>										
										<v-subheader>
											Information
										</v-subheader>
										<v-container>		
											<v-text-field
											prepend-icon="person" 
											label="Name" 
											v-model="editedItem.user.name"
											v-validate="'required'"
											:error-messages="errors.collect('name')"
											data-vv-name="name"
											data-vv-scope="user"
											></v-text-field>

											<v-text-field
											prepend-icon="email" 
											label="Email" 
											v-model="editedItem.user.email"
											v-validate="'required|email'"
											:error-messages="errors.collect('email')"
											data-vv-name="email"
											data-vv-scope="user"></v-text-field>

											<v-text-field
											prepend-icon="lock" 
											label="Password" 
											v-model="editedItem.user.password" 
											type="password"
											v-validate="'required|min:8|max:36'"
											:error-messages="errors.collect('password')"
											data-vv-name="password"
											data-vv-scope="user"></v-text-field>

											<v-text-field 
											prepend-icon="lock" 
											label="Confirm password" 
											v-model="editedItem.user.confirm_password" 
											type="password"
											v-validate="{required:true, is:editedItem.user.password}"
											data-vv-name="confirmPassword"
											:error-messages="errors.collect('confirmPassword')"
											data-vv-scope="user"></v-text-field>

											<v-radio-group v-model="editedItem.user.gender" row v-validate="'required|numeric'"
											:error-messages="errors.collect('gender')"
											data-vv-name="gender" 
											data-vv-scope="user">
											<v-radio label="Male" :value='1' color="primary" ></v-radio>
											<v-radio label="Female" :value='0' color="primary"></v-radio>	</v-radio-group>

											<v-menu
											ref="menu"
											lazy
											:close-on-content-click="false"
											v-model="menu"
											transition="scale-transition"
											offset-y
											full-width
											:nudge-right="40"
											min-width="290px"
											>
											<v-text-field
											slot="activator"
											label="Birthday date"
											v-model="editedItem.user.birthday"
											prepend-icon="event"
											v-validate="'required'"
											:error-messages="errors.collect('birthday')"
											data-vv-name="birthday"
											data-vv-scope="user"
											readonly
											></v-text-field>
											<v-date-picker
											ref="picker"
											v-model="editedItem.user.birthday"
											min="1950-01-01"
											:max="new Date().toISOString().substr(0, 10)"
											></v-date-picker>
										</v-menu>

									</v-container>

									<v-divider></v-divider>

									<v-subheader>Contact</v-subheader>
									<v-container>
										<v-text-field 
										prepend-icon="phone"
										label="Phone" 
										v-model="editedItem.user.phone"
										v-validate="'required|numeric|min:10|max:12'"
										:error-messages="errors.collect('phone')"
										data-vv-name="phone"
										data-vv-scope="user"></v-text-field>
										<v-text-field
										prepend-icon="place" 
										label="Address" 
										v-model="editedItem.user.address"
										v-validate="'required|max:255'"
										:error-messages="errors.collect('address')"
										data-vv-name="address"
										data-vv-scope="user"></v-text-field>
									</v-container>

									<v-divider></v-divider>

									<v-subheader>Setting</v-subheader>

									<v-container>
										<v-switch label="Actived" v-model="editedItem.user.actived" color="primary"></v-switch>
									</v-container>
								</v-form>
							</v-card-text>
						</v-card>
					</v-flex>
					<v-flex d-flex xs12 sm6 md6 >
						<v-card>
							<v-card-title primary class="title">Store Information</v-card-title>
							<v-card-text>
								<v-subheader>Avatar</v-subheader>
								<v-layout justify-center >
									<v-flex xs8 sm8 md6>
										<vue-image :image="editedItem.store.avatar" @IMAGE="getImage"></vue-image>		
									</v-flex>
								</v-layout>
								<v-form>
									<v-subheader>Store Information</v-subheader>
									<v-container>
										<v-layout> 
											<v-flex xs12 class="align-center">

											</v-flex>
										</v-layout>

										<v-select
										:items="types"
										v-model="editedItem.store.type_id"
										label="Type"
										item-text="type_name"
										item-value="id"
										prepend-icon="store"
										v-validate="'required'"
										:error-messages="errors.collect('type')"
										data-vv-name="type"
										data-vv-scope="store"
										></v-select>

										<v-text-field
										prepend-icon="text_format" 
										label="Name" 
										v-model="editedItem.store.name"
										v-validate="'required'"
										:error-messages="errors.collect('storeName')"
										data-vv-name="storeName"
										data-vv-scope="store"></v-text-field>

										<v-text-field
										prepend-icon="phone" 
										label="Phone number" 
										v-model="editedItem.store.phone"v-validate="'required|numeric|min:10|max:12'"
										:error-messages="errors.collect('storePhone')"
										data-vv-name="storePhone"
										data-vv-scope="store"></v-text-field>

										<v-text-field
										prepend-icon="access_time" 
										label="Prepare time" 
										v-model="editedItem.store.preparetime"
										suffix="minutes"
										v-validate="'required|numeric|max:2'"
										:error-messages="errors.collect('prepareTime')"
										data-vv-name="prepareTime"
										data-vv-scope="store"></v-text-field>

										<v-select
										:items="cities"
										v-model="editedItem.store.city_id"
										label="City"
										item-text="name"
										item-value="id"
										prepend-icon="map"
										@change="changeCity"
										v-validate="'required'"
										:error-messages="errors.collect('city')"
										data-vv-name="city"
										data-vv-scope="store"
										></v-select>

										<v-select
										v-if="editedItem.store.city_id != null"
										:items="districts"
										v-model="editedItem.store.district_id"
										label="District"
										item-text="district_name"
										item-value="id"
										prepend-icon="streetview"
										v-validate="'required'"
										:error-messages="errors.collect('district')"
										data-vv-name="district"
										data-vv-scope="store"
										></v-select>

										<v-text-field
										prepend-icon="place" 
										label="Store address" 
										v-model="editedItem.store.address"
										v-validate="'required|max:255'"
										:error-messages="errors.collect('storeAddress')"
										data-vv-name="storeAddress"
										data-vv-scope="store"></v-text-field>
									</v-container>

									<v-divider></v-divider>

									<v-subheader>Setting</v-subheader>

									<v-container>

										<v-switch label="Showed" v-model="editedItem.store.show" color="primary"></v-switch>

										<v-checkbox
										label="Verified merchant"
										color="primary"
										v-model="editedItem.store.verified"
										></v-checkbox>
									</v-container>
								</v-form>
							</v-card-text>
						</v-card>
					</v-flex>
				</v-layout>
			</v-container>
		</v-card-text>
	</v-card>
</v-dialog>
</template>

<script>
import {mapState} from 'vuex'
import {getLocation, activity} from '@/helpers/index.js'
import UploadImage from '@/components/image/uploadImage'
export default {
	components: {
		'vue-image': UploadImage
	},
	data() {
		return {
			editedItem: {
				user: {
					name: '',
					email: '',
					password:'',
					confirm_password:'',
					gender: 1,
					birthday: null,
					phone:'',
					address: '',
					lat:'',
					lng:'',
					actived: false,
					role_id: 4
				},
				store: {
					type_id: null,
					name: '',
					phone: '',
					preparetime: '',
					city_id: null,
					district_id: null,
					address: '',
					lat: '',
					lng: '',
					show: false,
					verified: false,
					avatar: null,
				}
			},
			defaultItem: {
				user: {
					name: '',
					email: '',
					password:'',
					confirm_password:'',
					gender: 1,
					birthday: null,
					phone:'',
					address: '',
					lat:'',
					lng:'',
					actived: false,
					role_id: 4	
				},
				store: {
					type_id: null,
					name: '',
					phone: '',
					preparetime: '',
					city_id: null,
					district_id: null,
					address: '',
					lat: '',
					lng: '',
					show: false,
					verified: false,
					avatar: null,				
				}
			},
			disabled: true,
			menu:false,
			flag_store: false,
			imageUrl:null
		}
	},
	methods: {
//Close Dialog
close: async function() {
	this.editedItem = await Object.assign({}, this.defaultItem)
	await this.errors.clear()
	this.disabled = true
	this.$store.commit('DIALOG_STORE_CLOSE')

},
//Accept Update Store
save: async function(request) {
	var vm = this
	if (vm.editedIndex > -1) {
//Accept Edit Store
vm.$validator.validateAll('store').then(async function(result){
	if(result) {	
		await getLocation(vm.editedItem.store.address).then(response => {
			vm.editedItem.store.lat = response[0].geometry.location.lat()
			vm.editedItem.store.lng = response[0].geometry.location.lng()	
		})	
		vm.$store.dispatch('updateStore', vm.editedItem.store).then(response => {
			if(response.status == 200) {
				vm.close()
			}
		}).catch(errors => {
			if(errors.response.status == 422) {

			}
		})	
	}
})
} else {
//Accept Add User
vm.$validator.validateAll('user').then(async (result) => {
	if(result) {						
		vm.$validator.validateAll('store').then(async(resultStore) => {
			if(resultStore) {
				await getLocation(vm.editedItem.user.address).then(response => {
					vm.editedItem.user.lat = response[0].geometry.location.lat()
					vm.editedItem.user.lng = response[0].geometry.location.lng()	
				})			
				await getLocation(vm.editedItem.store.address).then(response => {
					vm.editedItem.store.lat = response[0].geometry.location.lat()
					vm.editedItem.store.lng = response[0].geometry.location.lng()	
				})	
				vm.$store.dispatch('addStore', vm.editedItem).then(response => {
					if(response.status == 201) {
						vm.close()
					}
				}).catch(errors => {
					if(errors.response.status == 422) {

					}
				})	
			}
		})		
	}
})				
}
},
//Change city get district
changeCity: function(id) {
	this.districts = this.$store.getters.getDistrictByCityId(id)
},
//Get image 
getImage(value) {
	this.editedItem.store.avatar = value
}
},
computed: {
	...mapState({
		dialog: state      => state.storeStore.dialog,
		editedIndex: state => state.storeStore.editedIndex,
		item: state        => state.storeStore.editedItem,
		alert: state       => state.storeStore.alert,
		roles: state       => Array.from(state.roleStore.roles),
		cities: state      => Array.from(state.cityStore.cities),
		types: state       => Array.from(state.typeStore.types),
		activities: state  => Array.from(state.activityStore.activities)
	}),
	formTitle () {
		return this.editedIndex === -1 ? 'New Store' : 'Edit Store'
	}
},
watch: {
	menu (val) {
		val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
	},
	item (val) {
		Object.assign(this.editedItem.store, val)	
	},
	'editedItem.store.avatar': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != null) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != null) {
			this.disabled = false
		}
	}, 
	'editedItem.store.type_id': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != null) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != null) {
			this.disabled = false
		}
	},
	'editedItem.store.name': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	},
	'editedItem.store.phone': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 
	'editedItem.store.preparetime': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 
	'editedItem.store.city_id': function(val, oldVal) {
		this.districts = this.$store.getters.getDistrictByCityId(val)
		if(this.editedIndex > -1 && oldVal != null) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != null) {
			this.disabled = false
		}
	},
	'editedItem.store.district_id': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != null) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != null) {
			this.disabled = false
		}
	}, 
	'editedItem.store.address': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 
	'editedItem.store.show': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != false) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != false) {
			this.disabled = false
		}
	},
	'editedItem.user.name': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	},
	'editedItem.user.email': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 	
	'editedItem.user.password': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 
	'editedItem.user.confirm_password': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	}, 
	'editedItem.user.gender': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != 1) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != 1) {
			this.disabled = false
		}
	},
	'editedItem.user.birthday': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != null) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != null) {
			this.disabled = false
		}
	},
	'editedItem.user.phone': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	},
	'editedItem.user.address': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != '') {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != '') {
			this.disabled = false
		}
	},
	'editedItem.user.actived': function(val, oldVal) {
		if(this.editedIndex > -1 && oldVal != false) {
			this.disabled = false
		} else if(this.editedIndex == -1 && val != false) {
			this.disabled = false
		}
	}
},
created: function() {
	this.$store.dispatch('fetchType').then(response => {
		if(response.status == 200) {
			this.$store.dispatch('fetchCity')
			this.$store.dispatch('fetchDistrict')
		}
	})
}
}
</script>

<style>

</style>