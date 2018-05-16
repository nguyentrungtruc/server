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

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_COUNTRY')" class="mb-2">New Country</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.country_name }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.lang }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.country_slug }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.dialingcode }}</td>
			<td>{{ props.item.country_show }}</td>
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
				></gmap-map>
			</v-card>
		</template>
	</v-data-table>
</v-card>	
</template>

<script>
import Dialog from './countryDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Country List',
			search: '',
			headers: [
			{
				text: 'Country',
				align: 'left',
				value: 'country_name'
			},
			{ text: 'Language', value: 'lang', sortable: false },
			{ text: 'Slug', value: 'country_slug', sortable: false },
			{ text: 'Dialing Code', value: 'dialingcode', sortable: false },
			{ text: 'Show', value: 'country_show', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Country list',
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
						item.country_name+' country has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteCountry', item).then(response => {
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
		//Edit Role
		editItem (item) {
			this.$store.dispatch('editCountry', item)
		},
	},
	computed: {
		//Store Role
		...mapState({
			items: state => Array.from(state.countryStore.countries),
			loading: state => state.countryStore.loading,
			alert: state => state.countryStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		// this.$store.dispatch('getAuth').then(response => {
		// 	if (response.status == 200) {
			this.$store.dispatch('fetchCountry')
		// 	}
		// })
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