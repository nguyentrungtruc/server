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
					            label         = "Activity name"
					            v-model       = "editedItem.daysofweek"
					          :error-messages = "errors.collect('daysofweek')"
					          :counter        = "25"
					            v-validate    = "'required|max:25'"
					            data-vv-name  = "daysofweek"
					></v-text-field>

					<v-text-field 
					                  label          = "Number"
					                  v-model.number = "editedItem.number"
					                :counter         = "2"
					                :error-messages  = "errors.collect('number')"
					                  v-validate     = "'required|numeric|max:2'"
					                  data-vv-name   = "number"
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
import {Geocoder} from '@/utils/maps'
export default {
	data: function() {
		return {
			editedItem: {
				daysofweek: '',
				number    : ''
			},
			defaultItem: {
				daysofweek: '',
				number    : ''
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
			this.$store.commit('CLOSE_ACTIVITY_DIALOG')
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
			const url = `Activity/Add`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 201) {
					this.$store.dispatch('updateActivity', response.data.activity)
					this.close()
					this.success(data.name+' activity has been added.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' activity has already been taken.')
				}
			}).finally(() => {this.progress = false})
		},
		//ACTION UPDATE
		update(data) {
			const {id} = data
			const url  = `Activity/${id}/Edit`
			this.axios.post(url, data, {withCredentials: true}).then(response => {
				if(response.status === 200) {
					this.$store.dispatch('updateActivity', response.data.activity)
					this.close()
					this.success(data.name+' activity has been edited.')
				}
			}).catch(error => {
				if(error.response.status === 422) {
					this.fail(data.name+' activity has already been taken.')
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
			dialog     : state => state.activity.dialog,
			editedIndex: state => state.activity.editedIndex,
			item       : state => state.activity.editedItem,
			alert      : state => state.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Activity': 'Edit Activity'
		}
	},
	watch: {
		item (val) {
			this.editedItem = {...val}
		},
	}
}
</script>
