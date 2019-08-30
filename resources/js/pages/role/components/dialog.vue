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
					      label         = "Role name"
					      v-model       = "editedItem.name"
					    :counter        = "25"
					    :error-messages = "errors.collect('name')"
					      v-validate    = "'required|max:25'"
					      data-vv-name  = "name"
					></v-text-field>

					<v-text-field 
					      label         = "Description"
					      v-model       = "editedItem.description"
					    :counter        = "255"
					    :error-messages = "errors.collect('description')"
					      v-validate    = "'required|max:255'"
					      data-vv-name  = "description"
					></v-text-field>
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
export default {
	data: function() {
		return {
			editedItem: {
				name       : '',
				description: ''
			},
			defaultItem: {
				name       : '',
				description: ''
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
			this.$store.commit('CLOSE_ROLE_DIALOG')
		},
		//Accept Update Role
		save: function(request) {
			var   vm   = this
			const data = {...this.editedItem}
			vm.$validator.validateAll().then((result) => {
				if(result) {
					if(!vm.progress) {
						vm.progress = true
						if(vm.editedIndex > -1) {
							vm.update(data)
						} else {
							vm.add(data)
						}
					}
				}
			})
		},
		//ACTION ADD NEW ROLE
		add(data) {
			const url = `Role/Add`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateRole', response.data.role)
					this.close()
					this.success(data.name+' Role has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' Role has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE ROLE
		update(data) {
			const {id} = data
			const url  = `Role/${id}/Edit`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateRole', response.data.role)
					this.close()
					this.success(data.name+' role has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' role has already been taken.')
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
			dialog     : state => state.role.dialog,
			editedIndex: state => state.role.editedIndex,
			item       : state => state.role.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Role': 'Edit Role'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
