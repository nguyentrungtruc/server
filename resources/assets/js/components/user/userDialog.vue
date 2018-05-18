<template>
	<v-dialog v-if="dialog" v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false" :lazy="true">
		<v-card>
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
				<v-form>
					<v-subheader>Permission</v-subheader>
					<v-container>
						<v-select
						:items="roles"
						v-model="editedItem.role_id"
						label="Role"
						item-text="name"
						item-value="id"
						prepend-icon="map"
						v-validate="'required'"
						:error-messages="errors.collect('role')"
						data-vv-name="role"
						data-vv-scope="user"
						></v-select>
					</v-container>

					<v-divider></v-divider>

					<v-subheader>
						Information
					</v-subheader>
					<v-container>		
						<v-text-field
						prepend-icon="person" 
						label="Name" 
						v-model="editedItem.name"
						v-validate="'required'"
						:error-messages="errors.collect('name')"
						data-vv-name="name"
						data-vv-scope="user"
						></v-text-field>

						<v-text-field
						prepend-icon="email" 
						label="Email" 
						v-model="editedItem.email"
						v-validate="'required|email'"
						:error-messages="errors.collect('email')"
						data-vv-name="email"
						data-vv-scope="user"></v-text-field>
						
						<div v-if="editedIndex > -1">
							<v-text-field
							prepend-icon="lock" 
							label="Password" 
							v-model="editedItem.password" 
							type="password"
							v-validate="'min:8|max:36'"
							:error-messages="errors.collect('password')"
							data-vv-name="password"
							data-vv-scope="user"></v-text-field>

							<v-text-field 
							prepend-icon="lock" 
							label="Confirm password" 
							v-model="editedItem.confirm_password" 
							type="password"
							v-validate="{is:editedItem.password}"
							data-vv-name="confirmPassword"
							:error-messages="errors.collect('confirmPassword')"
							data-vv-scope="user"></v-text-field>
						</div>

						<div v-if="editedIndex == -1">
							<v-text-field
							prepend-icon="lock" 
							label="Password" 
							v-model="editedItem.password" 
							type="password"
							v-validate="'required|min:8|max:36'"
							:error-messages="errors.collect('password')"
							data-vv-name="password"
							data-vv-scope="user"></v-text-field>

							<v-text-field 
							prepend-icon="lock" 
							label="Confirm password" 
							v-model="editedItem.confirm_password" 
							type="password"
							v-validate="{required:true, is:editedItem.password}"
							data-vv-name="confirmPassword"
							:error-messages="errors.collect('confirmPassword')"
							data-vv-scope="user"></v-text-field>
						</div>

						<v-radio-group 
						v-model="editedItem.gender" 
						row
						v-validate="'required|numeric'"
						:error-messages="errors.collect('gender')"
						data-vv-name="gender" 
						data-vv-scope="user">
						<v-radio label="Male" :value='1' color="primary" ></v-radio>
						<v-radio label="Female" :value='0' color="primary"></v-radio></v-radio-group>

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
						v-model="editedItem.birthday"
						prepend-icon="event"
						v-validate="'required'"
						:error-messages="errors.collect('birthday')"
						data-vv-name="birthday"
						data-vv-scope="user"
						readonly
						></v-text-field>
						<v-date-picker
						ref="picker"
						v-model="editedItem.birthday"
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
					v-model="editedItem.phone"
					v-validate="'required|numeric|min:10|max:12'"
					:error-messages="errors.collect('phone')"
					data-vv-name="phone"
					data-vv-scope="user"></v-text-field>

					<v-text-field
					prepend-icon="place" 
					label="Address" 
					v-model="editedItem.address"
					v-validate="'required|max:255'"
					:error-messages="errors.collect('address')"
					data-vv-name="address"
					data-vv-scope="user"></v-text-field>
				</v-container>

				<v-divider></v-divider>

				<v-subheader>Setting</v-subheader>

				<v-container>
					<v-switch label="Actived" v-model="editedItem.actived" color="primary"></v-switch>
				</v-container>
			</v-form>
		</v-card-text>
	</v-card>
</v-dialog>
</template>

<script>
import {mapState} from 'vuex'
import {getLocation} from '@/helpers/apiGetLocation.js'
export default {
	data() {
		return {
			editedItem: {
				name: '',
				email: '',
				password:'',
				confirm_password:'',
				gender: '',
				birthday: '',
				phone:'',
				address: '',
				lat:'',
				lng:'',
				actived: true,
				role_id: ''
			},
			defaultItem: {
				name: '',
				email: '',
				password:'',
				confirm_password:'',
				gender: '',
				birthday: '',
				phone:'',
				address: '',
				lat:'',
				lng:'',
				actived: true,
				role_id: ''	
			},
			disabled: true,
			menu:false,
			districts: []
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_USER_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Accept Update Role
		save: async function(request) {
			var vm = this
			if (vm.editedIndex > -1) {
				//Accept Edit Role
				vm.$validator.validateAll('user').then(async function(result){
					if(result) {			
						await getLocation(vm.editedItem.address).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()	
							if(response.status == 200) {
								vm.$store.dispatch('updateUser', vm.editedItem).then(response => {
									if(response.status == 201) {
										vm.close()
									}
								})
							}	
						})					
					}
				})
			} else {
				//Accept Add User
				vm.$validator.validateAll('user').then(async (result) => {
					if(result) {
						await getLocation(vm.editedItem.address).then(response => {
							vm.editedItem.lat = response[0].geometry.location.lat()
							vm.editedItem.lng = response[0].geometry.location.lng()	
							if(response.status == 200) {
								vm.$store.dispatch('addUser', vm.editedItem).then(response => {
									if(response.status == 200) {
										vm.close()
									}
								})
							}	
						})								
					}
				})				
			}
		},
		getLocation: (value) => new Promise((resolve, reject) => {
			axios.get(`http://maps.googleapis.com/maps/api/geocode/json?address="+${value}+"&sensor=false`).then(response => {
				if(response.status == 200) {
					resolve(response)
				}
			}).catch(errors => {
				reject(errors)
			})
		})
	},
	computed: {
		...mapState({
			dialog: state => state.userStore.dialog,
			editedIndex: state => state.userStore.editedIndex,
			item: state => state.userStore.editedItem,
			alert: state => state.userStore.alert,
			roles: state => Array.from(state.roleStore.roles)
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New User' : 'Edit User'
		}
	},
	watch: {
		menu (val) {
			val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
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
		'editedItem.email': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.password': function(val, oldVal) {
			if(this.editedIndex > -1 && val != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.gender': function(val, oldVal) {
			if(this.editedIndex > -1 && val != oldVal && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.birthday': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.phone': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.address': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.role_id': function(val, oldVal) {
			if(this.editedIndex >-1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.actived': function(val, oldVal) {
			console.log(val)
			console.log(oldVal)
			if(this.editedIndex > -1 && val != oldVal && oldVal != true) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		}
	}
}
</script>

<style>

</style>