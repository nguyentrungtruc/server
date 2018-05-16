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

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_ACTIVITY')" class="mb-2">New Activity</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.daysOfWeek }}</td>
			<td  @click="props.expanded = !props.expanded" style="cursor:pointer">{{ props.item.number }}</td>
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
import Dialog from './activityDialog'
import {mapState} from 'vuex'
export default {
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Activity List',
			search: '',
			headers: [
			{
				text: 'Days of week',
				align: 'left',
				value: 'daysOfWeek'
			},
			{ text: 'Number', value: 'number', sortable: false },
			{ text: 'Actions', value: 'name', sortable: false },
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Activity list',
				disabled: true
			}
			]
		}
	},
	methods: {
		//Delete Activity
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
						item.daysOfWeek+' activity has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteActivity', item).then(response => {
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
		//Edit Activity
		editItem (item) {
			this.$store.dispatch('editActivity', item)
		},
	},
	computed: {
		//Store Activity
		...mapState({
			items: state   => Array.from(state.activityStore.activities),
			loading: state => state.activityStore.loading,
			alert: state   => state.activityStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchActivity')
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