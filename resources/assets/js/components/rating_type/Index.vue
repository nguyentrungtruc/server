<template>
	<v-card>
		<v-breadcrumbs>
			<v-icon slot="divider">chevron_right</v-icon>
			<v-breadcrumbs-item
			v-for="item in breadcrumbs"
			:key="item.text"
			:disabled="item.disabled"
			>{{ item.text }}</v-breadcrumbs-item>
		</v-breadcrumbs>

		<v-divider></v-divider>
		
		<v-card-title primary-title>
			<div>
				<div class="headline text-capitalize">{{title}}</div>
			</div>
			<v-spacer></v-spacer>
			<v-text-field
			append-icon="search"
			label="Search"
			single-line
			hide-details
			v-model="search"
			></v-text-field>
		</v-card-title>

		<v-alert :type="alert.type" dismissible v-model="alert.alert">
			{{alert.messages}}
		</v-alert>

		<v-btn color="primary" dark  class="mb-2" @click.native="$store.commit('DIALOG_RATING_TYPE')">New Rating Type</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td>{{ props.item.id }}</td>
			<td>{{ props.item.name }}</td>
			<td class="px-2">
				<v-btn icon class="mx-0" @click="editItem(props.item)">
					<v-icon color="teal">edit</v-icon>
				</v-btn>
				<v-btn icon class="mx-0" @click="deleteItem(props.item)">
					<v-icon color="pink">delete</v-icon>
				</v-btn>
			</td>
		</template>
		<template slot="no-results">
			<tr>
				<td colspan="6">
					<v-alert :value="true" color="error" icon="warning">
						Your search for "{{ search }}" found no results.
					</v-alert>
				</td>
			</tr>			
		</template>
	</v-data-table>
</v-card>	
</template>

<script>
	import Dialog from './dialog'
	import {mapState} from 'vuex'
	import { getHeader } from '@/config/config.js'
	export default {
		components: {
			'vue-dialog': Dialog
		},
		data: function() {
			return {
				title: 'Rating type lists',
				search: '',
				headers: [
				{ text: 'Id', value: 'name'},
				{ text: 'Name', value: 'name'},
				{ text: 'Actions', sortable: false },
				],
				breadcrumbs: [
				{
					text: 'Dashboard',
					disabled: false
				},
				{
					text: 'Rating type lists',
					disabled: true
				}
				],
				progress: false

			}
		},
		methods: {
		//Delete Role
		deleteItem (item) {
			var vm = this 
			vm.$swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel!',
				confirmButtonClass: 'v-btn primary',
				cancelButtonClass: 'v-btn',
				buttonsStyling: false,
				reverseButtons: true
			}).then((result) => {
				if (result.value) {
					vm.$swal(
						'Deleted!',
						'the rating type has been deleted.',
						'success'
						).then(() => {
							vm.delete(item)
						})
					} else{
						vm.$swal('Cancelled', '', 'error')
					}
				})
			
		},
		delete(item) {
			var vm = this
			vm.process()
			setTimeout(() => {

				axios.delete('/api/Dofuu-Rating-Type/'+item.id, {headers: getHeader()}).then(response => {
					if(response.status == 204) {
						vm.$store.commit('REMOVE_RATING_TYPE', item)
						vm.$store.commit('ALERT_RATING_TYPE', {alert:true, messages: 'the rating type has been deleted.', type: 'success'})
					}
				}).catch(errors => {
				}).finally(() => {
					vm.process()
				})

			}, 100)
			
		},
		//Edit 
		editItem (item) {
			this.$store.dispatch('editRatingType', item)
		},
		process: function() {
			this.progress = !this.progress
		}
	},
	computed: {
		//Store
		...mapState({
			items: state   => Array.from(state.ratingTypeStore.ratingTypes),
			loading: state => state.ratingTypeStore.loading,
			alert: state   => state.ratingTypeStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchRatingType')
	}
}
</script>

<style scoped>

</style>