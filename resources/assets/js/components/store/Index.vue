<template>
	<v-card>
		<!-- Bread Crumbs -->
		<v-breadcrumbs>
			<v-icon slot="divider">chevron_right</v-icon>
			<v-breadcrumbs-item
			v-for="item in breadcrumbs"
			:key="item.text"
			:disabled="item.disabled"
			>{{ item.text }}</v-breadcrumbs-item>
		</v-breadcrumbs>
		<v-divider></v-divider>
		<v-card flat>
			<v-card-text>
				<v-card flat>
					<v-card-text><v-card-title primary-title>
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
					<!-- Alert -->
					<v-alert :type="alert.type" dismissible v-model="alert.alert">
						{{alert.messages}}
					</v-alert>
					
					<v-btn color="primary" dark @click.native="$store.commit('DIALOG_STORE')" class="mb-2">New Store</v-btn>

					<vue-dialog></vue-dialog>
					
					<v-data-table
					:headers="headers"
					:items="items"
					:search="search"
					class="elevation-1"
					:rows-per-page-items="rowsPerPageItems"
					:pagination.sync="pagination"
					>
					<template slot="items" slot-scope="props">
						
						<td @click="expanded(props)">{{ props.item.id }}</td>
						<td @click="expanded(props)">
							<v-avatar size="120" tile>
								<img :src="image(props.item.avatar)" alt="avatar">
							</v-avatar>
						</td>
						<td @click="expanded(props)">{{ props.item.name }}</td>
						<td @click="expanded(props)">{{ props.item.city_name }}</td>
						<td @click="expanded(props)">{{ props.item.district_name }}</td>
						<td><a @click.stop.prevent="showMap(props.item)">{{ props.item.address }}</a></td>
						<td @click="expanded(props)">{{ props.item.phone }}</td>
						<td class="text-xs-center">
							<v-btn v-if="props.item.name!=null" icon class="mx-0" :to="{name: 'StoreDetails', params: {sid: props.item.id}}">
								<v-icon color="red">store</v-icon>
							</v-btn>
							<v-btn v-else icon disabled>
								<v-icon color="secondary">store</v-icon>
							</v-btn>
						</td>
						<td class="text-xs-center">
							<v-btn icon class="mx-0" @click="editItem(props.item)">
								<v-icon color="teal">edit</v-icon>
							</v-btn>
						</td>
					</template>

					<template slot="expand" slot-scope="props">
						<v-container fluid grid-list-md class="grey lighten-3">
							<v-layout row wrap>
								<v-flex d-flex xs12 sm6 md6>
									<v-card>
										<v-toolbar card>
											<v-toolbar-title>
												Informations
											</v-toolbar-title>
										</v-toolbar>
										<v-card-text>
											<v-list dense>
												<v-list-tile>
													<v-list-tile-action>
														<strong>UID:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.user.id }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>
												<v-list-tile>
													<v-list-tile-action>
														<strong>Owner:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.user.name }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>

												<v-list-tile>
													<v-list-tile-action>
														<strong>Birthday:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.user.birthday }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>
												<v-list-tile>
													<v-list-tile-action>
														<strong>Gender:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ gender(props.item.user.gender) }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>								
											</v-list>
										</v-card-text>
									</v-card>
								</v-flex>
								<v-flex d-flex xs12 sm6 md6>
									<v-layout row wrap>
										<v-flex d-flex xs12 sm6 md12>
											<v-card>
												<v-toolbar card color="indigo" dark>
													<v-toolbar-title>Contacts</v-toolbar-title>
												</v-toolbar>
												<v-card-text>
													<v-list dense>
														<v-list-tile>
															<v-list-tile-action>
																<strong>Email:</strong>
															</v-list-tile-action>
															<v-list-tile-content>
																<v-list-tile-title>
																	{{ props.item.user.email}}
																</v-list-tile-title>
															</v-list-tile-content>
														</v-list-tile>
														<v-list-tile>
															<v-list-tile-action>
																<strong>Phone: </strong>
															</v-list-tile-action>
															<v-list-tile-content>
																<v-list-tile-title>
																	{{ props.item.user.phone }}
																</v-list-tile-title>
															</v-list-tile-content>
														</v-list-tile>
														<v-list-tile>
															<v-list-tile-action>
																<strong>Address:</strong>
															</v-list-tile-action>
															<v-list-tile-content>
																<v-list-tile-title>
																	<a @click.stop.prevent="showMap(props.item)">			
																		{{ props.item.user.address }}
																	</a>
																</v-list-tile-title>
															</v-list-tile-content>
														</v-list-tile>						
													</v-list>									
												</v-card-text>
											</v-card>
										</v-flex>
										<v-flex d-flex>
											<v-layout row wrap>
												<v-flex
												d-flex
												xs12
												>
												<v-card>
													<v-toolbar card color="red" dark>
														<v-toolbar-title>
															Settings
														</v-toolbar-title>
													</v-toolbar>
													<v-card-text>
														<v-list dense>
															<v-list-tile>
																<v-list-tile-action>
																	<strong>Actived:</strong>
																</v-list-tile-action>
																<v-list-tile-content>
																	<v-list-tile-title>
																		{{ props.item.user.actived}}
																	</v-list-tile-title>
																</v-list-tile-content>
															</v-list-tile>
															<v-list-tile>
																<v-list-tile-action>
																	<strong>Banned: </strong>
																</v-list-tile-action>
																<v-list-tile-content>
																	<v-list-tile-title>
																		{{ props.item.user.banned }}
																	</v-list-tile-title>
																</v-list-tile-content>
															</v-list-tile>
														</v-list>		
													</v-card-text>
												</v-card>
											</v-flex>
										</v-layout>
									</v-flex>
								</v-layout>
							</v-flex>
						</v-layout>
					</v-container>
				</template>

				<template slot="no-results">
					<tr>
						<td :colspan="headers.length">
							<v-alert :value="true" color="error" icon="warning">
								Your search for "{{ search }}" found no results.
							</v-alert>
						</td>
					</tr>			
				</template>
			</v-data-table></v-card-text>
		</v-card>
	</v-card-text>
	<v-dialog v-model="mapDialog" max-width="800" v-if="mapDialog">
		<v-card>
			<gmap-map
			:center="location"
			:zoom="15"
			map-type-id="terrain"
			style="height: 500px"
			><gmap-marker :position="location">
			</gmap-marker></gmap-map>
		</v-card>
	</v-dialog>
</v-card>
</v-card>
</template>

<script>
import image from '@/mixins/imageUrl'
import Dialog from './storeDialog'
import MapDialog from '@/components/dialog/mapDialog'
import {mapState} from 'vuex'
export default {
	mixins: [image],
	components: {
		'vue-dialog': Dialog,
		'vue-map-dialog': MapDialog
	},
	data: function() {
		return {
			title: 'Store List',
			search: '',
			headers: [
			{
				text: 'SID',
				align: 'left',
				value: 'id'
			},
			{ text: 'Store avatar', value: 'store_avatar'},
			{ text: 'Store name', value: 'store_name'},
			{ text: 'City', value:'district.city.city_name'},
			{ text: 'District', value:'district.district_name'},
			{ text: 'Address', value: 'store_address', sortable: false},
			{ text: 'Store phone', value: 'store_phone'},
			{ text: 'Store', value: 'user.have_store', sortable: false},
			{ text: 'Action', sortable:false}
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Store list',
				disabled: true
			}
			],
			rowsPerPageItems: [25, 50, 100, {"text": "All", "value": -1}],
			pagination: {
				sortBy: 'name'
			},
			mapDialog: false
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
						item.store_name+' store has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteStore', item).then(response => {
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
			this.$store.dispatch('editStore', item)
		},
		//Expanded Store
		expanded(request) {
			request.expanded = !request.expanded
		},
		//Show google map
		showMap(request) {
			this.mapDialog = true
			this.location = {
				lat: request.lat,
				lng: request.lng
			}
		},
		//Convert value int to string for gender
		gender: function(value) {
			const gender = parseInt(value)
			if(parseInt(gender)==1) {
				return 'Male'
			} else {
				return 'Female'
			}
		}
	},
	computed: {
		...mapState({
			items: state => Array.from(state.storeStore.stores),
			loading: state => state.storeStore.loading,
			alert: state => state.storeStore.alert,
			dialog: state => state.storeStore.dialog,
		}),
		formTitle() {
			return this.editedIndex === -1 ? 'New User' : 'Edit User'
		}
	},
	watch: {
		
	},
	created() {
		this.$store.dispatch('fetchStore').then(response => {
			if(response.status == 200) {

			}
		})
	}

}
</script>

<style>

</style>