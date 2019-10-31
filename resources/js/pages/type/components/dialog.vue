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
						                  label         = "Type name"
						                  v-model       = "editedItem.name"
						                :error-messages = "errors.collect('name')"
						                  v-validate    = "'required|max:50'"
						                  data-vv-name  = "name"
						persistent-hint
					></v-text-field>

					<v-text-field 
						                  label         = "Code"
						                  v-model       = "editedItem.code"
						                :counter        = "5"
						                :error-messages = "errors.collect('code')"
						                  data-vv-name  = "code"
					></v-text-field>

					<v-text-field 
						                  label         = "Icon"
						                  v-model       = "editedItem.icon"
						                :counter        = "5"
						                :error-messages = "errors.collect('icon')"
						                  data-vv-name  = "icon"
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
const TypeRepository = RepositoryFactory.get('types')
export default {
	data: function() {
		return {
			editedItem: {
				name  : '',
				code  : '',
				icon  : '',
				isShow: true,
			},
			defaultItem: {
				name  : '',
				code  : '',
				icon  : '',
				isShow: true,
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
			this.$store.commit('CLOSE_TYPE_DIALOG')
		},
		//Accept Update
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
			TypeRepository.create(data).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateType', response.data.type)
					this.close()
					this.success(data.name+' type has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' type has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			const url  = `Type/${id}/Edit`
			TypeRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateType', response.data.type)
					this.close()
					this.success(data.name+' type has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' type has already been taken.')
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
			dialog     : state => state.type.dialog,
			editedIndex: state => state.type.editedIndex,
			item       : state => state.type.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Type': 'Edit Type'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
