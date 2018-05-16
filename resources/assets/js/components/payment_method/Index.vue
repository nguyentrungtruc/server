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

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_PAYMENT')" class="mb-2">New Status</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td>{{ props.item.paymentName }}</td>
			<td>{{ props.item.description }}</td>
			<td :class="{'red--text': !props.item.show,'blue--text': props.item.show}">{{props.item.show}}</td>
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
import Dialog from './paymentDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Payment Method List',
			search: '',
			headers: [
			{
				text: 'Payment Methods',
				align: 'left',
				value: 'paymentName'
			},
			{ text: 'Description', value: 'description', sortable: false },
			{ text: 'Show', value: 'show', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Payment Method List',
				disabled: true
			}
			]
		}
	},
	methods: {
		//Delete Payment
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
						item.paymentName+' payment has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deletePayment', item)
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
		//Edit Payment
		editItem (item) {
			this.$store.dispatch('editPayment', item)
		},
	},
	computed: {
		//Payment Store
		...mapState({
			items: state   => Array.from(state.paymentStore.payments),
			loading: state => state.paymentStore.loading,
			alert: state   => state.paymentStore.alert
		}),
	},
	created: function() {
		this.$store.dispatch('fetchPayment')
	}
}
</script>

<style scoped>

</style>