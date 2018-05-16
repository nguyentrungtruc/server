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

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_CITY')" class="mb-2">New City</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.country.country_name }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.name }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.zipcode }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.slug }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.show }}</td>
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
		<template slot="expand" slot-scope="props">
			<v-card flat>
				<gmap-map
				:center="{lat:props.item.lat, lng:props.item.lng}"
				:zoom="7"
				style="width: 100%; height: 300px"
				>
				<gmap-marker :position="{lat:props.item.lat, lng:props.item.lng}"></gmap-marker>
			</gmap-map>
		</v-card>
	</template>
</v-data-table>
</v-card>	
</template>

<script>
import Dialog from './cityDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'City List',
			search: '',
			headers: [
			{
				text: 'Country',
				value: 'country_name',
				sortable: false
			},
			{
				text: 'City',
				align: 'left',
				value: 'name'
			},
			{ text: 'Zip code', value: 'zipcode'},
			{ text: 'Slug', value: 'slug', sortable: false },
			{ text: 'Show', value: 'show', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'City list',
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
						item.name+' city has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteCity', item).then(response => {
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
		//Edit City
		editItem (item) {
			this.$store.dispatch('editCity', item)
		},
	},
	computed: {
		//Store City
		...mapState({
			items: state => Array.from(state.cityStore.cities),
			loading: state => state.cityStore.loading,
			alert: state => state.cityStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchCity')
	}
}
</script>

<style scoped>
.google-map {
	width: 800px;
	height: 600px;
	margin: 0 auto;
	background: gray;
}
</style>