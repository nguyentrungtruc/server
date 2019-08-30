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
					          label          = "Step"
					          v-model.number = "editedItem.step"
					        :counter         = "2"
					        :error-messages  = "errors.collect('step')"
					          v-validate     = "'numeric|max:2'"
					          data-vv-name   = "step"
					></v-text-field>
					<v-text-field 
					              label         = "Status name"
					              v-model       = "editedItem.name"
					            :error-messages = "errors.collect('name')"
					            :counter        = "25"
					              v-validate    = "'required|max:25'"
					              data-vv-name  = "name"
					></v-text-field>

					<v-text-field 
					              label         = "Description"
					              v-model       = "editedItem.description"
					            :counter        = "255"
					            :error-messages = "errors.collect('description')"
					              v-validate    = "'max:255'"
					              data-vv-name  = "description"
					></v-text-field>

					<v-select
					        label      = "Color"
					      :items       = "colors"
					        v-model    = "editedItem.color"
					        item-text  = "name"
					        item-value = "color"
					>
						<template slot="selection" slot-scope="data">
							<v-chip outlined :color="data.item.color">
								<v-icon :color="data.item.color">lens</v-icon> {{data.item.name}}
							</v-chip>
						</template>
						<template v-slot:item="{item}">		
							<v-list-item-icon>
								<v-icon :color="item.color">lens</v-icon>
							</v-list-item-icon>
							<v-list-item-content>
								{{item.name}}
							</v-list-item-content>
						</template>
					</v-select>

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
				name       : '',
				description: '',
				step       : 0,
				color      : ''
			},
			defaultItem: {
				name       : '',
				step       : 0,
				description: '',
				color      : ''
			},
			colors: [
			{
				name : 'red',
				color: 'red accent-4'
			},
			{
				name : 'green',
				color: 'green accent-4'
			},
			{
				name : 'grey',
				color: 'grey'
			},
			{
				name : 'yellow',
				color: 'yellow accent-4'
			}
			],
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
			this.$store.commit('CLOSE_ORDER_STATUS_DIALOG')
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
			const url = `OrderStatus/Add`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateOrderStatus', response.data.status)
					this.close()
					this.success(data.name+' status has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' status has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			const url  = `OrderStatus/${id}/Edit`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateOrderStatus', response.data.status)
					this.close()
					this.success(data.name+' status has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' status has already been taken.')
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
			dialog     : state => state.orderStatus.dialog,
			editedIndex: state => state.orderStatus.editedIndex,
			item       : state => state.orderStatus.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Status': 'Edit Status'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
