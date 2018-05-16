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

		<v-alert :type="alert.type" dismissible v-model="alert.show">
			{{alert.message}}
		</v-alert>

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_ORDER_STATUS')" class="mb-2">New Status</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td>{{props.item.numberOrder}}</td>
			<td>{{ props.item.name }}</td>
			<td>{{ props.item.description }}</td>
			<td><v-icon :color="props.item.color">lens</v-icon></td>
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
import Dialog from './statusDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Order Status List',
			search: '',
			headers: [
			{
				text: 'No.',
				value: 'numberOrder'
			},
			{
				text: 'Status',
				align: 'left',
				value: 'order_status_name'
			},
			{ text: 'Description', value: 'order_status_description', sortable: false },
			{ text: 'Color', value: 'color', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Order status list',
				disabled: true
			}
			]
		}
	},
	methods: {
		//Delete Status
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
						item.order_status_name+' status has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteOrderStatus', item)
						})
					} else{
						vm.$swal(
							'Cancelled',
							'',
							'error'
							)
					}
				})
			
		},
		//Edit Store Status
		editItem (item) {
			this.$store.dispatch('editOrderStatus', item)
		},
	},
	computed: {
		//Store Status Store
		...mapState({
			items: state   => Array.from(state.orderStatusStore.orderStatus),
			loading: state => state.orderStatusStore.loading,
			alert: state   => state.orderStatusStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchOrderStatus')
	}
}
</script>

<style scoped>

</style>