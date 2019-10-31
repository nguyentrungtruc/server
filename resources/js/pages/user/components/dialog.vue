<template>
	<v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
		<v-card>
			<v-toolbar>
				<v-btn icon @click.native="close" small>
					<v-icon>close</v-icon>
				</v-btn>
				<v-toolbar-title><span class="headline">{{ formTitle }}</span></v-toolbar-title>
			</v-toolbar>
			<d-alert :index="1"/>
			<v-card-text> 
				<v-form>
				<v-subheader>Information</v-subheader>
				<v-container>
					<v-select
					:items          = "roles"
					  v-model       = "editedItem.roleId"
					  label         = "Role"
					  item-text     = "name"
					  item-value    = "id"
					  prepend-icon  = "category"
					  v-validate    = "'required'"
					:error-messages = "errors.collect('role')"
					  data-vv-name  = "role"
					/>

					<v-text-field
					          prepend-icon  = "person"
					          label         = "Name"
					          v-model       = "editedItem.name"
					          v-validate    = "'required'"
					        :error-messages = "errors.collect('name')"
					          data-vv-name  = "name"
					/>

					<v-text-field
					          prepend-icon  = "email"
					          label         = "Email"
					          v-model       = "editedItem.email"
					          v-validate    = "'required|email'"
					        :error-messages = "errors.collect('email')"
					          data-vv-name  = "email"
					/>

					<v-text-field
					:append-icon    = "showPassword ? 'visibility' : 'visibility_off'"
					  autocomplete  = "new-password"
					  prepend-icon  = "lock"
					  label         = "Password"
					  v-model       = "editedItem.password"
					:type           = "showPassword ? 'text' : 'password'"
					  v-validate    = "{required:requiredPassword, min:8, max:36}"
					:error-messages = "errors.collect('password')"
					  data-vv-name  = "password"
					  
					  @click:append = "showPassword = !showPassword"
					/>

					<v-text-field 
					          prepend-icon  = "lock"
					          label         = "Confirm password"
					          v-model       = "editedItem.confirmPassword"
					        :type           = "showPassword ? 'text' : 'password'"
					          v-validate    = "{required:requiredPassword, is:editedItem.password}"
					          data-vv-name  = "confirm password"
					        :error-messages = "errors.collect('confirm password')"
					/>

					<v-radio-group v-model="editedItem.gender" row>
						<template v-slot:label>
							<v-icon>wc</v-icon>
						</template>
						<v-radio label="Male" :value="true"></v-radio>
						<v-radio label="Female" :value="false"></v-radio>
					</v-radio-group>
					
					<v-menu
						          ref                   = "menu"
						          v-model               = "menu"
						        :close-on-content-click = "false"
						          transition            = "scale-transition"
						offset-y
						full-width
						min-width = "290px"
					>
						<template v-slot:activator="{ on }">
							<v-text-field
						v-model      = "editedItem.birthday"
						label        = "Birthday date"
						prepend-icon = "event"
						readonly
						          v-on          = "on"
						          v-validate    = "'required'"
						        :error-messages = "errors.collect('birthday')"
						          data-vv-name  = "birthday"
							/>
						</template>
						<v-date-picker
						          ref     = "picker"
						          v-model = "editedItem.birthday"
						        :max      = "new Date().toISOString().substr(0, 10)"
						          min     = "1950-01-01"
						          @change = "changeDate"
						></v-date-picker>
					</v-menu>
				</v-container>

				<v-divider></v-divider>
				<v-subheader>Contacts</v-subheader>
				<v-container>				

					<v-text-field
					          mask          = "(####) ### - ###"
					          prepend-icon  = "phone"
					          label         = "Phone"
					          v-model.trim  = "editedItem.phone"
					          v-validate    = "'numeric|min:10|max:11'"
					        :error-messages = "errors.collect('phone')"
					          data-vv-name  = "phone"
					/>

					<v-text-field
						          prepend-icon  = "place"
						          label         = "Address"
						          v-model       = "editedItem.address"
						          v-validate    = "'max:255'"
						        :error-messages = "errors.collect('address')"
						          data-vv-name  = "address"
					/>
				</v-container>
				
				<v-divider></v-divider>

				<v-subheader>Setup</v-subheader>

				<v-container>					
					<v-switch :label="activeStatus" v-model="editedItem.isActive" color="primary"></v-switch>
					<v-switch :label="banStatus" v-model="editedItem.isBan" color="primary"></v-switch>
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
import {mapState} from 'vuex'
import {Alert} from '@/components'
import {Geocoder} from '@/utils/maps'
import {RepositoryFactory} from '@/services/Repository/index'
const UserRepository = RepositoryFactory.get('users')
export default {
	data: function() {
		return {
			editedItem: {
				name           : '',
				email          : '',
				password       : '',
				confirmPassword: '',
				gender         : false,
				birthday       : '2018-05-02',
				phone          : '',
				address        : '',
				lat            : '',
				lng            : '',
				isActive       : true,
				isBan          : false,
				roleId         : 5
			},
			defaultItem: {
				name           : '',
				email          : '',
				password       : '',
				confirmPassword: '',
				gender         : false,
				birthday       : '2018-05-02',
				phone          : '',
				address        : '',
				lat            : '',
				lng            : '',
				isActive       : true,
				isBan          : false,
				roleId         : 5
			},
			menu        : false,
			showPassword: false,
			districts   : [],
			progress    : false
		}
	},
	components: {
		'd-alert': Alert
	},
	methods: {
		changeDate(date) {
			this.$refs.menu.save(date)
		},
		// //GET POSITION
		getPosition(address) {
			var vm = this
			return new Promise((resolve, reject) => {	
				Geocoder.getPredictions(address).then(response => {
					Object.assign(vm.editedItem, {lat: response[0].geometry.location.lat(), lng: response[0].geometry.location.lng() })
					resolve(response)
				})
			})
		},
		//CLOSE DIALOG
		close: async function() {
			this.editedItem = await {...this.defaultItem}
			this.$store.dispatch('offAlert')
			this.$validator.reset()
			this.$store.commit('CLOSE_USER_DIALOG')
		},
		//ACCEPT
		save:  function(request) {
			var vm = this
			vm.$validator.validateAll().then(async (result) => {
				if(result) {
					if(!vm.progress) {
						vm.progress = true
						await vm.getPosition(vm.editedItem.address)
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
		//ACTION ADD NEW 
		add(data) {
			UserRepository.create(data).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateUser', response.data.user)
					this.close()
					this.success(data.email+' user has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail('Number phone or email has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			UserRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateUser', response.data.user) && this.close() && this.success(data.email+' user has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail('Number phone or email has already been taken.')
				}
			}).finally(() => {this.progress = false})
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
			roles      : state => state.role.roles,
			dialog     : state => state.user.dialog,
			editedIndex: state => state.user.editedIndex,
			item       : state => state.user.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New User': 'Edit User'
		},
		activeStatus() {
			return this.editedItem.isActive ? 'Actived': 'Inactive'
		},
		banStatus() {
			return this.editedItem.isBan ? 'Banned': 'Unban'
		},
		requiredPassword() {
			return this.editedIndex === -1
		}
	},
	watch: {
		menu (val) {
			val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
		},
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>

<style>
#map {
	width : 100%;
	height: 250px;
}
</style>