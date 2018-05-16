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
				<div class="headline">{{title}}</div>
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

		<v-btn color="primary" dark  class="mb-2" @click.native="$store.commit('DIALOG_RANGE')">New Range</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td>{{ props.item.from }}</td>
			<td>{{ props.item.to }}</td>
			<td>{{ props.item.description }}</td>
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
import Dialog from './rangeDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Range Lists',
			search: '',
			headers: [
			{ text: 'Range From(Km)', value: 'from'},
			{ text: 'Range To(Km)', value: 'to'},
			{ text: 'Description', value: 'description', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Range List',
				disabled: true
			}
			]
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
				confirmButtonClass: 'btn error',
				cancelButtonClass: 'btn',
				buttonsStyling: false,
				reverseButtons: true
			}).then((result) => {
				if (result.value) {
					vm.$swal(
						'Deleted!',
						'the range has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteRange', item).then(response => {
								if(response.status == 204){

								}
							})
						})
					} else{
						vm.$swal('Cancelled', '', 'error')
					}
				})
			
		},
		//Edit Range
		editItem (item) {
			this.$store.dispatch('editRange', item)
		},
	},
	computed: {
		//Range Store
		...mapState({
			items: state => Array.from(state.rangeStore.ranges),
			loading: state => state.rangeStore.loading,
			alert: state => state.rangeStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchRange')
	}
}
</script>

<style scoped>

</style>