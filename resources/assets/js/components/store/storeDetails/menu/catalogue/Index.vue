<template>
	<v-card>
		<v-toolbar color="teal" dark flat>
			<v-toolbar-title>Catalogue</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-tooltip top>
				<v-btn icon @click.native="$store.commit('DIALOG_CATALOGUE')" slot="activator">
					<v-icon>playlist_add</v-icon>
				</v-btn>
				<span>Add Catalogue</span>
			</v-tooltip>
			
		</v-toolbar>
		<v-list>
			<v-list>
				<v-list-tile v-for="item in items" :key="item.id" @click="">
					<v-list-tile-content>
						<v-list-tile-title>{{ item.catalogue }}</v-list-tile-title>
						 <v-list-tile-sub-title v-if="item._catalogue">{{item._catalogue}}</v-list-tile-sub-title>
					</v-list-tile-content>
					<v-list-tile-action>
						<v-tooltip top>
							<v-btn icon ripple @click="editItem(item)" slot="activator">
								<v-icon color="teal">edit</v-icon>
							</v-btn>
							<span>Edit Catalogue</span>
						</v-tooltip>
						
					</v-list-tile-action>
					<v-list-tile-action>
						<v-tooltip top>
							<v-btn icon ripple @click="deleteItem(item)" slot="activator">
								<v-icon color="pink">delete</v-icon>
							</v-btn>
							<span>Edit Catalogue</span>
						</v-tooltip>
					</v-list-tile-action>
				</v-list-tile>
			</v-list>
		</v-list>
		<vue-dialog></vue-dialog>
	</v-card>
</template>

<script>
import Dialog from './catalogueDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			
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
						item.catalogue+' catalogue has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteCatalogue', item).then(response => {
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
			this.$store.dispatch('editCatalogue', item)
		}
	},
	computed: {
		...mapState({
			items: state => Array.from(state.catalogueStore.catalogues),
			loading: state => state.catalogueStore.loading,
			alert: state => state.catalogueStore.alert
		})
	},
	watch: {
		
	},
	created() {
		this.$store.dispatch('fetchCatalogue', this.$route.params.sid)
	}
}
</script>

<style>

</style>