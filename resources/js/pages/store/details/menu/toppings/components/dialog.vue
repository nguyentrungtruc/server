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
					v-model = "editedItem.price"
					label   = "Price"
					persistent-hint 
					    hint          = "Price for topping"
					    suffix        = "vnđ" v-validate = "'required|max:10|numeric'"
					    data-vv-name  = "price"
					  :error-messages = "errors.collect('price')"
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
import axios from 'axios'
import {mapState} from 'vuex'
import {Alert} from '@/components'
export default {
	data: function() {
		return {
			editedItem: {
				name  : '',
				_name : '',
				price : 0,
				isShow: true,
			},
			defaultItem: {
				name  : '',
				_name : '',
				price : 0,
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
			this.$store.commit('CLOSE_TOPPING_DIALOG')
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
			const url    = `Topping/Add`
			const params = {storeId: this.$route.params.storeId}
			this.axios.post(url, data, {params, withCredentials: true}).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateTopping', response.data.topping)
					this.close()
					this.success(data.name+' topping has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' topping has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			const url  = `Topping/${id}/Edit`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateTopping', response.data.topping)
					this.close()
					this.success(data.name+' topping has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' topping has already been taken.')
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
			dialog     : state => state.topping.dialog,
			editedIndex: state => state.topping.editedIndex,
			item       : state => state.topping.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Topping': 'Edit Topping'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
