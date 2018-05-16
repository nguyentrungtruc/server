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

		<v-btn color="primary" dark @click.native="$store.commit('DIALOG_COUPON')" class="mb-2">New Coupon</v-btn>

		<vue-dialog></vue-dialog>

		<v-data-table
		:headers="headers"
		:items="items"
		:search="search"
		hide-actions
		class="elevation-1"
		>
		<template slot="items" slot-scope="props">
			<td  style="cursor:pointer"><router-link :to="{name: 'CouponDetails', params: {coupon:props.item.id}}">{{ props.item.title}}</router-link></td>
			<td  style="cursor:pointer" class="text--blue">{{ props.item.coupon }}</td>
			<td  style="cursor:pointer">{{ props.item.notes }}</td>
			<td>{{ percent(props.item.discount_percent) }}</td>
			<td>{{ props.item.max_coupons }}</td>
			<td>{{ props.item.coupon_used }}</td>
			<td>{{ props.item.started_at }}</td>
			<td>{{ props.item.ended_at }}</td>
			<td>{{ props.item.status.coupon_status_name }}</td>
			<td>{{ props.item.actived}}</td>
			<td class="px-2">
				<v-btn icon class="mx-0" @click="editItem(props.item)">
					<v-icon color="teal">edit</v-icon>
				</v-btn>
				<v-btn icon class="mx-0" @click="deleteItem(props.item)">
					<v-icon color="pink">delete</v-icon>
				</v-btn>
			</td>
		</template>
	</v-data-table>
</v-card>	
</template>

<script>
import Dialog from './couponDialog'
import storeInformation from '@/mixins/storeInformation'
// import {percent} from '@/helpers/index'
import {mapState} from 'vuex'
export default {
	mixins: [storeInformation],
	components: {
		'vue-dialog': Dialog
	},
	data: function() {
		return {
			title: 'Coupon List',
			search: '',
			headers: [
			{
				text: 'Title',
				value: 'title'
			},
			{
				text: 'Coupon',
				align: 'left',
				value: 'coupon'
			},
			{ text: 'Notes', value: 'notes', sortable: false },
			{ text: 'Discount percent', value: 'discount_percent'},
			{ text: 'Max coupons', value: 'max_coupons'},
			{ text: 'Coupon used', value: 'coupon_used'},
			{ text: 'Start at', value: 'started_at'},
			{ text: 'End at', value: 'ended_at'},
			{ text: 'Status', value: 'status.coupon_status_name'},
			{ text: 'Actived', value: 'actived'},
			{ text: 'Actions', value: 'name'},
			],
			breadcrumbs: [
			{
				text: 'Dashboard',
				disabled: false
			},
			{
				text: 'Coupon list',
				disabled: true
			}
			]
		}
	},
	methods: {
		//Delete Coupon
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
						item.title+' coupon has been deleted.',
						'success'
						).then(() => {
							this.$store.dispatch('deleteCoupon', item).then(response => {
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
		//Edit Coupon
		editItem (item) {
			this.$store.dispatch('editCoupon', item)
		},
	},
	computed: {
		//Store Coupon
		...mapState({
			items: state   => Array.from(state.couponStore.coupons),
			loading: state => state.couponStore.loading,
			alert: state   => state.couponStore.alert
		}),
	},
	watch: {

	},
	created: function() {
		this.$store.dispatch('fetchCoupon')
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