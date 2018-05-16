<template>
	<v-card>
		<v-toolbar color="white" flat>
			<v-toolbar-title>Products</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-tooltip top>
				<v-btn icon @click.native="$store.commit('DIALOG_PRODUCT')"  slot="activator">
					<v-icon color="green" medium>add</v-icon>
				</v-btn>
				<span>Add Product</span>
			</v-tooltip>
			
		</v-toolbar>
		
		<vue-dialog></vue-dialog>
		
		<v-data-table
		:headers="headers"
		:items="items"
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td>{{ props.item.id }}</td>
			<td>
				<v-avatar size="120" tile>
					<img :src="image(props.item.image)" alt="avatar">
				</v-avatar>
			</td>
			<td>
				{{ props.item.name }}
				<v-subheader>{{props.item._name}}</v-subheader>
			</td>
			<td>{{ props.item.price }}</td>
			<td>{{ props.item.count }}</td>
			<td>{{ props.item.status.product_status_name }}</td>

			<td class="text-xs-center">
				<v-tooltip top>
					<v-btn icon class="mx-0" @click="editItem(props.item)" slot="activator">
						<v-icon color="teal">edit</v-icon>
					</v-btn>
					<span>Edit Product</span>
				</v-tooltip>
				<v-tooltip top>
					<v-btn icon class="mx-0" @click="deleteItem(props.item)" slot="activator">
						<v-icon color="pink">delete</v-icon>
					</v-btn>
					<span>Remove Product</span>
				</v-tooltip>				
			</td>
		</template>

				<!-- <template slot="no-results">
					<tr>
						<td :colspan="headers.length">
							<v-alert :value="true" color="error" icon="warning">
								Your search for "{{ search }}" found no results.
							</v-alert>
						</td>
					</tr>			
				</template> -->
			</v-data-table>
		<!-- 	<v-dialog v-if="catalogues.length == 0" v-model="dialog" max-width="290">
				<v-card>
					<v-card-title class="headline">Use Google's location service?</v-card-title>
					<v-card-text>Catalogue not found!!! Please add Catalogue before when add products</v-card-text>
				</v-card>
			</v-dialog> -->
		</v-card>
	</template>

	<script>
	import Dialog from './productDialog'
	import image from '@/mixins/imageUrl'
	import {mapState} from 'vuex'
	export default {
		mixins: [image],
		components: {
			'vue-dialog': Dialog
		},
		data: function() {
			return {
				headers: [
				{
					text: 'ID',
					align: 'left',
					value: 'id'
				},
				{ text: 'Image', value: 'image'},
				{ text: 'Name', value: 'name'},
				{ text: 'Price', value:'price'},
				{ text: 'Count', value:'count'},
				{ text: 'Status', value: 'status.name', sortable: false},
				{ text: 'Action', sortable:false}
				],
			}
		},
		methods: {
		//Delete User
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
						item.name+' product has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteProduct', item).then(response => {
								if(response.status == 204){

								}
							})
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
		//Edit Store
		editItem (item) {
			this.$store.dispatch('editProduct', item)
		}
	},
	computed: {
		...mapState({
			catalogues: state => state.catalogueStore.catalogues,
			dialog: state => state.productStore.dialog,
			items: state => Array.from(state.productStore.products),
			loading: state => state.productStore.loading,
			alert: state => state.productStore.alert
		})
	},
	watch: {
		
	},
	created() {
		this.$store.dispatch('fetchProduct', this.$route.params.sid)
	}
}
</script>

<style>

</style>