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
				:items          = "catalogues"
				  v-model       = "editedItem.catalogueId"
				  label         = "Catalogue"
				  item-text     = "name"
				  item-value    = "id"
				  v-validate    = "'required'"
				:error-messages = "errors.collect('catalogue')"
				  data-vv-name  = "catalogue"
					/>

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
					
					<v-container grid-list-md fluid>
						<v-layout row wrap>
							<v-flex xs4  v-for="(size, i) in editedItem.sizes" :key="i">
								<v-text-field :label="`${size._name} price`" v-model.number="size.price" suffix="vnđ" required v-validate="{required:true, numeric:true, min:1, max:8}" :error-messages="errors.collect('size._name')" :data-vv-name="size._name"></v-text-field> 
							</v-flex>			
						</v-layout>
					</v-container>
					
					<v-radio-group v-model="editedItem.haveTopping" row mandatory label="Topping">
						<v-radio color="primary" label="Yes" :value="true" ></v-radio>
						<v-radio color="primary" label="No" :value="false" ></v-radio>
					</v-radio-group>		

					<v-text-field 
			                label         = "Description"
			                v-model       = "editedItem.description"
			              :error-messages = "errors.collect('description')"
			                v-validate    = "'max:254'"
			                data-vv-name  = "description"
					/>

					<v-text-field
			                label         = "Priority"
			                v-model       = "editedItem.priority"
			                v-validate    = "'required|numeric|max:2'"
			              :error-messages = "errors.collect('priority')"
			                data-vv-name  = "priority"
					/>

					<v-select
					                label         = "Status"
					              :items          = "status"
					                v-model       = "editedItem.statusId"
					              :error-messages = "errors.collect('status')"
					autocomplete
					v-validate   = "'required'"
					data-vv-name = "status"
					item-value   = "id"
					item-text    = "name"
					required
					/>
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
import {RepositoryFactory} from '@/services/Repository/index'
const ProductRepository = RepositoryFactory.get('products')
export default {
	data: function() {
		return {
			editedItem: {
				'name'       : '',
				'_name'      : null,
				'haveTopping': false,
				'sizes'      : [],
				'priority'   : 0,
				'statusId'   : 1,
				'description': null,
				'catalogueId': null,
			},
			defaultItem: {
				'name'       : '',
				'_name'      : null,
				'haveTopping': false,
				'sizes'      : [],
				'priority'   : 0,
				'statusId'   : 1,
				'description': null,
				'catalogueId': null,
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
			this.$store.commit('CLOSE_PRODUCT_DIALOG')
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
			const url    = `Product/Add`
			const params = {storeId: this.$route.params.storeId}
			ProductRepository.create(data, params).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateProduct', response.data.product)
					this.close()
					this.success(data.name+' product has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' product has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			ProductRepository.update(id, data).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateProduct', response.data.product)
					this.close()
					this.success(data.name+' product has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' product has already been taken.')
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
			catalogues : state => state.catalogue.catalogues,
			dialog     : state => state.product.dialog,
			editedIndex: state => state.product.editedIndex,
			item       : state => state.product.editedItem,
			status     : state => state.productStatus.status,
			sizes      : state => state.size.sizes,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Product': 'Edit Product'
		}
	},
	watch: {
		'dialog': async function(val) {
			if(val) {				
				if(this.editedIndex === -1) {
					var sizes = []
					await this.sizes.forEach(item => {
						sizes.push({id: item.id, 'name': item.name, '_name': item._name, 'price': 0})
					})
					this.editedItem.sizes = sizes
				} else {
					this.editedItem = JSON.parse(JSON.stringify(this.item))
				}
			}
		},
	}
}
</script>
