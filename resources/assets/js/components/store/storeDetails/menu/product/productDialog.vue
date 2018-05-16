<template>
	<v-dialog v-model="dialog" persistent  max-width="500px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ formTitle }}</span>
			</v-card-title>
			
			<v-alert :type="alert.type" dismissible v-model="alert.alert">
				{{alert.messages}}
			</v-alert>

			<v-card-text>
				<v-form>
					<v-layout justify-center >
						<vue-image :image="editedItem.image" @IMAGE="getImage"></vue-image>
					</v-layout>
					<v-select
					label="Catalogue"
					:items="catalogues"
					v-model="editedItem.catalogue_id"
					:error-messages="errors.collect('catalogue')"
					autocomplete
					v-validate="'required'"
					data-vv-name="catalogue"
					item-value="id"
					item-text="catalogue"
					></v-select>

					<v-text-field 
					label="Product name" 
					v-model="editedItem.name"
					:error-messages="errors.collect('name')"
					v-validate="'required|max:254'"
					data-vv-name="name"
					></v-text-field>

					<v-text-field 
					label="English name" 
					v-model="editedItem._name"
					:error-messages="errors.collect('English name')"
					v-validate="'max:254'"
					data-vv-name="English name"
					></v-text-field>

					<v-text-field 
					label="Price" 
					v-model="editedItem.price"
					:error-messages="errors.collect('price')"
					v-validate="'required|max:10|numeric'"
					data-vv-name="price"
					suffix="VNĐ"
					></v-text-field>

					<v-text-field 
					label="Description" 
					v-model="editedItem.description"
					:error-messages="errors.collect('description')"
					v-validate="'max:254'"
					data-vv-name="description"
					></v-text-field>

					<v-select
					label="Status"
					:items="status"
					v-model="editedItem.status_id"
					:error-messages="errors.collect('status')"
					autocomplete
					v-validate="'required'"
					data-vv-name="status"
					item-value="id"
					item-text="product_status_name"
					></v-select>

				</v-form>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="blue darken-1" flat @click.native="close">Cancel</v-btn>
				<v-btn color="blue darken-1" flat @click.native="save" :disabled="disabled" >Save</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import {ErrorBag, Validator} from 'vee-validate'
import axios from 'axios'
import {mapState} from 'vuex'
import UploadImage from '@/components/image/uploadImage'
export default {
	components: {
		'vue-image':UploadImage
	},
	data: function() {
		return {
			editedItem: {
				name: '',
				_name: '',
				price: 0,
				description: '',
				image: null,
				catalogue_id:null,
				status_id: 1
			},
			defaultItem: {
				name: '',
				_name: '',
				price: 0,
				description: '',
				image: null,
				catalogue_id: null,
				status_id: 1
			},
			disabled: true,
		}
	},
	methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_PRODUCT_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Update Status
		save: function(request) {
			var vm = this
			Object.assign(this.editedItem, {sid: this.$route.params.sid})
			if (vm.editedIndex > -1) {
				//Accept Edit Status
				vm.$validator.validateAll().then(function(result){
					if(result) {			
						vm.$store.dispatch('updateProduct', vm.editedItem).then(response => {
							if(response.status == 200) {
								vm.close()
							}
						})			
					}
				})
			} else {
				//Accept Add Status
				vm.$validator.validateAll().then((result) => {
					if(result) {						
						vm.$store.dispatch('addProduct', vm.editedItem).then(response => {
							if(response.status == 201) {
								vm.close()
							}
						})
					}
				})				
			}
		},
		getImage(value) {
			this.disabled = false
			this.editedItem.image = value
		}
	},
	computed: {
		...mapState({
			status: state => Array.from(state.productStatusStore.productStatus),
			dialog: state => state.productStore.dialog,
			editedIndex: state => state.productStore.editedIndex,
			item: state => state.productStore.editedItem,
			alert: state => state.productStore.alert,
			catalogues: state => Array.from(state.catalogueStore.catalogues)
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Product' : 'Edit Product'
		}
	},
	watch: {
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
		'editedItem._name': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
			
		},
		'editedItem.price': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != 0) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != 0) {
				this.disabled = false
			}
		},
		'editedItem.description': function(val, oldVal) {
			console.log(val, oldVal)
			if(this.editedIndex > -1 && oldVal != '') {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != '') {
				this.disabled = false
			}
		},
		'editedItem.image': function(val, oldVal) {
			if(this.editedIndex >-1 && oldVal != null && val != null) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != null) {
				this.disabled = false
			}
		},
		'editedItem.catalogue_id': function(val, oldVal) {
			if(this.editedIndex > -1 && oldVal != null) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != null) {
				this.disabled = false
			}
		},
		'editedItem.status_id': function(val, oldVal) {
			console.log(val)
			console.log(oldVal)
			if(this.editedIndex > -1 && oldVal != val) {
				this.disabled = false
			} else if(this.editedIndex == -1 && val != 1) {
				this.disabled = false
			}
		}
	},
	mounted() {

	},
	created() {
		this.$store.dispatch('fetchProductStatus')
	}
}
</script>

<style>

</style>