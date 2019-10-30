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
					                                  label         = "Name"
					                                  v-model       = "editedItem.name"
					                                :error-messages = "errors.collect('name')"
					                                :counter        = "45"
					                                  v-validate    = "'required|max:45'"
					                                  data-vv-name  = "name"
					></v-text-field>

					<v-text-field 
					                                  label         = "English name"
					                                  v-model       = "editedItem._name"
					                                :counter        = "45"
					                                :error-messages = "errors.collect('English name')"
					                                  v-validate    = "'max:45'"
					                                  data-vv-name  = "English name"
					></v-text-field>

					<v-text-field
					                                label         = "Priority"
					                                v-model       = "editedItem.priority"
					                                v-validate    = "'required|numeric|max:2'"
					                              :error-messages = "errors.collect('priority')"
					                                data-vv-name  = "priority"
					/>

					<v-switch label="Ẩn/Hiện" v-model="editedItem.isShow" color="primary"></v-switch>					

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
const CatalogueRepository = RepositoryFactory.get('catalogues')
export default {
	data: function() {
		return {
			editedItem: {
				name    : '',
				_name   : '',
				priority: 0,
				isShow  : true,
			},
			defaultItem: {
				name    : '',
				_name   : '',
				priority: 0,
				isShow  : true,
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
			this.$store.commit('CLOSE_CATALOGUE_DIALOG')
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
			const params = {storeId: this.$route.params.storeId}
			CatalogueRepository.create(data, params).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateCatalogue', response.data.catalogue)
					this.close()
					this.success(data.name+' catalogue has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' catalogue has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			CatalogueRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateCatalogue', response.data.catalogue) 
					this.close()
					this.success(data.name+' catalogue has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' catalogue has already been taken.')
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
			dialog     : state => state.catalogue.dialog,
			editedIndex: state => state.catalogue.editedIndex,
			item       : state => state.catalogue.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Catalogue': 'Edit Catalogue'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
