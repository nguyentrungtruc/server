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
			{{mapDialog}}
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
					
					<v-btn color="primary" dark @click.native="$store.commit('DIALOG_USER')" class="mb-2">New User</v-btn>

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
						<td @click="expanded(props)">{{ props.item.name }}</td>
						<td @click="expanded(props)">{{ props.item.email }}</td>
						<td>{{ props.item.phone }}</td>
						<td>{{ props.item.role.name }}</td>
						<td class="text-xs-center"> <v-edit-dialog
							:return-value.sync="props.item.actived"
							large
							lazy
							persistent
							>
							<div>{{ props.item.actived }}</div>
							<div slot="input" class="mt-3 title">Actived</div>
							<v-switch slot="input" label="Actived" v-model="props.item.actived" color="primary"></v-switch>
						</v-edit-dialog></td>
						<td class="text-xs-center">{{ props.item.banned }}</td>
						<td class="text-xs-center">{{ props.item.have_store}}</td>
						<td class="justify-center layout px-0">
							<v-btn icon class="mx-0" @click="editItem(props.item)">
								<v-icon color="teal">edit</v-icon>
							</v-btn>
							<v-btn icon class="mx-0" @click="deleteItem(props.item)">
								<v-icon color="pink">delete</v-icon>
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
															{{ props.item.id }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>
												<v-list-tile>
													<v-list-tile-action>
														<strong>Name:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.name }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>

												<v-list-tile>
													<v-list-tile-action>
														<strong>Birthday:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.birthday }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>
												<v-list-tile>
													<v-list-tile-action>
														<strong>Gender:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ gender(props.item.gender) }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>								
												<v-list-tile>
													<v-list-tile-action>
														<strong>Role:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.role.name }}
														</v-list-tile-title>
													</v-list-tile-content>
												</v-list-tile>
												<v-list-tile>
													<v-list-tile-action>
														<strong>Store:</strong>
													</v-list-tile-action>
													<v-list-tile-content>
														<v-list-tile-title>
															{{ props.item.have_store }}
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
																	{{ props.item.email}}
																</v-list-tile-title>
															</v-list-tile-content>
														</v-list-tile>
														<v-list-tile>
															<v-list-tile-action>
																<strong>Phone: </strong>
															</v-list-tile-action>
															<v-list-tile-content>
																<v-list-tile-title>
																	{{ props.item.phone }}
																</v-list-tile-title>
															</v-list-tile-content>
														</v-list-tile>
														<v-list-tile>
															<v-list-tile-action>
																<strong>Address:</strong>
															</v-list-tile-action>
															<v-list-tile-content>
																<v-list-tile-title>
																	<a href="javascript:void(0)" @click.prevent="showMap(props.item)">			
																		{{ props.item.address }}
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
																		{{ props.item.actived}}
																	</v-list-tile-title>
																</v-list-tile-content>
															</v-list-tile>
															<v-list-tile>
																<v-list-tile-action>
																	<strong>Banned: </strong>
																</v-list-tile-action>
																<v-list-tile-content>
																	<v-list-tile-title>
																		{{ props.item.banned }}
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
						<td colspan="9">
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
import Dialog from './userDialog'
import MapDialog from '@/components/dialog/mapDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog,
		'vue-map-dialog': MapDialog
	},
	data: function() {
		return {
			title: 'User List',
			search: '',
			headers: [
			{
				text: 'UID',
				align: 'left',
				value: 'uid'
			},
			{ text: 'Name', value: 'name' },
			{ text: 'Email', value: 'email' },
			{ text: 'Phone', value: 'phone'},
			{ text: 'Role', value: 'role.name'},
			{ text: 'Actived', value: 'actived', sortable: false },
			{ text: 'Banned', value: 'banned', sortable: false },
			{ text: 'Store', value: 'have_store', sortable: false},
			{ text: 'Action', sortable:false}
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'User list',
				disabled: true
			}
			],
			rowsPerPageItems: [25, 50, 100, {"text": "All", "value": -1}],
			pagination: {
				sortBy: 'name'
			},
			mapDialog: false,
			location: {
				lat: '',
				lng: ''
			}
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
						item.email+' account has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteUser', item).then(response => {
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
		//Edit User
		editItem (item) {
			this.$store.dispatch('editUser', item)
		},
		//Expanded User
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
			roles: state => Array.from(state.roleStore.roles),
			items: state => Array.from(state.userStore.users),
			loading: state => state.userStore.loading,
			alert: state => state.userStore.alert
		}),
		formTitle() {
			return this.editedIndex === -1 ? 'New User' : 'Edit User'
		}
	},
	watch: {
		
	},
	created() {
		// this.$store.dispatch('getAuth').then(response => {
		// 	if(response.status === 200) {
			this.$store.dispatch('fetchUser').then(response => {
				if(response.status == 200) {
					
				}
			})
			this.$store.dispatch('fetchRole')
			// }
		// })
	}

}
</script>

<style>

</style>