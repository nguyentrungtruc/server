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
				<v-form @keyup.enter="save">
					<v-text-field 
					label="Type name" 
					v-model="editedItem.name"
					:counter="50"
					:error-messages="errors.collect('name')"
					v-validate="'max:50'"
					data-vv-name="name"					
					></v-text-field>					
				</v-form>
			</v-card-text>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="blue darken-1" flat @click.native="close">Cancel</v-btn>
				<v-btn color="blue darken-1" class="white--text" @click.native="save" :loading="progress" >Save</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
	import {ErrorBag, Validator} from 'vee-validate'
	import { getHeader } from '@/config/config.js'
	import axios from 'axios'
	import {mapState} from 'vuex'
	export default {
		data: function() {
			return {
				editedItem: {
					name: ''
				},
				defaultItem: {
					name: ''
				},
				disabled: true,
				progress: false,
			}
		},
		methods: {
		//Close Dialog
		close: function() {
			this.disabled = true
			this.editedItem = Object.assign({}, this.defaultItem)	
			this.$store.commit('DIALOG_RATING_TYPE_CLOSE')
			setTimeout(() => {
				this.errors.clear()		
			},300)
		},
		//Save Action
		save: function(request) {
			var vm = this
			vm.$validator.validateAll().then(result => {
				if(result) {
					if(!vm.progress) {
						if (vm.editedIndex > -1) {
							vm.update()
						}
						else {
							vm.add()
						}
					}
				}
			})
		},
		// UPDATE
		update: function() {
			var vm = this 
			var data = Object.assign({}, vm.editedItem)
			vm.process()
			setTimeout(() => {
				axios.put('/api/Dofuu-Rating-Type/'+data.id, data, { headers: getHeader() }).then(response => {
					if(response.status == 200) {
						vm.$store.commit('UPDATE_RATING_TYPE', response.data.ratingType)
						vm.$store.commit('ALERT_RATING_TYPE', { alert:true, messages: response.data.message, type: response.data.type })
						vm.close()
					}
				}).catch(errors => {
					if(errors.response.status == 422) {
						commit('ALERT_RANGE', {alert:true, messages:'the range has already been taken.', type: 'error'})
					}
				}).finally(() => {
					vm.process()
				})
			})			
		},
		//ADD 
		add: function() {
			var vm   = this 
			var data = Object.assign({}, vm.editedItem)
			vm.process()
			setTimeout(() => {
				return axios.post('/api/Dofuu-Rating-Type', data, { headers: getHeader() }).then(response => {
					if(response.status === 201) {
						vm.$store.commit('UPDATE_RATING_TYPE', response.data.ratingType)
						vm.$store.commit('ALERT_RATING_TYPE', { alert:true, messages: response.data.message, type: response.data.type })
						vm.close()
					}
				}).catch(errors => {
					if(errors.response.status === 422) {
						vm.$store.commit('ALERT_RATING_TYPE', { alert:true, messages: 'the rating type has already been taken.', type: 'error' })
					}
				}).finally(() => {
					vm.process()
				})
			}, 100)
		},
		//PROCESS
		process: function() {
			var vm = this
			vm.progress = !vm.progress
		}
	},
	computed: {
		...mapState({
			dialog: state      => state.ratingTypeStore.dialog,
			editedIndex: state => state.ratingTypeStore.editedIndex,
			item: state        => state.ratingTypeStore.editedItem,
			alert: state       => state.ratingTypeStore.alert
		}),
		formTitle () {
			return this.editedIndex === -1 ? 'New Rating Type' : 'Edit Rating Type'
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
		}
	},
	mounted() {

	}
}
</script>

<style>

</style>