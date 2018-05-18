import Vue from 'vue'
import axios from 'axios'
import Router from 'vue-router'
import Vuex from 'vuex'

import store from '@/store/store'
import auth from '@/utils/auth.js'

Vue.use(Vuex)
Vue.use(auth)

const Login = () => import('@/components/credential/Login')
// const Role = () => import('@/components/role/Index')

import Test from '@/components/test/test'
import Notification from '@/components/notification/Index'
import OrderDetails from '@/components/order/orderDetails'
import Order from '@/components/order/Index'
import PaymentMethod from '@/components/payment_method/Index'
import OrderStatus from '@/components/order_status/Index'
import ProductStatus from '@/components/product_status/Index'
import Menu from '@/components/store/storeDetails/menu/Index'
import StoreDetails from '@/components/store/storeDetails/Index'
import Service from '@/components/service/Index'
import Delivery from '@/components/delivery/Index'
import Range from '@/components/range/Index'
import CouponDetails from '@/components/coupon/couponDetails'
import Coupon from '@/components/coupon/Index'
import CouponStatus from '@/components/coupon_status/Index'
import Store from '@/components/store/Index'
import Activity from '@/components/activity/Index'
import StoreStatus from '@/components/store_status/Index'
import Type from '@/components/type/Index'
import District from '@/components/district/Index'
import City from '@/components/city/Index'
import Country from '@/components/country/Index'
import User from '@/components/user/Index'
import Passport from '@/components/passport/Index'
import Role from '@/components/role/Index'
import Dashboard from '@/components/dashboard/Index'

Vue.use(Router)


const router = new Router({
	mode: 'history',
	routes: [
	{
		path: '/dofuu-admin-panel/test',
		component: Test,
		name: 'Test',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/notifications',
		component: Notification,
		name: 'Notification',
		meta: {
			forAuth:true
		}
	},
	{
		path: '/dofuu-admin-panel/orders/:oid/details',
		component: OrderDetails,
		name: 'OrderDetails',
		meta: {
			forAuth:true
		}
	},
	{
		path: '/dofuu-admin-panel/orders',
		component: Order,
		name: 'Order',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/payment_method',
		component: PaymentMethod,
		name: 'PaymentMethod',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/order_status',
		component: OrderStatus,
		name: 'OrderStatus',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/product_status',
		component: ProductStatus,
		name: 'ProductStatus',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/stores/:sid',
		component: StoreDetails,
		name: 'StoreDetails',
		children: [
		{
			path:'/dofuu-admin-panel/store/:sid/menu',
			component: Menu,
			name: 'Menu',
			meta: {
				forAuth: true
			}
		}
		],
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/service',
		component: Service,
		name: 'Service',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/deliveries',
		component: Delivery,
		name: 'Delivery',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/ranges',
		component: Range,
		name: 'Range',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/coupons/:coupon',
		component: CouponDetails,
		name: 'CouponDetails',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/coupons',
		component: Coupon,
		name: 'Coupon',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/coupon_status',
		component: CouponStatus,
		name: 'CouponStatus',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/stores',
		component: Store,
		name: 'Store',
		meta: {
			forAuth: true
		}
	},		
	{
		path: '/dofuu-admin-panel/activities',
		component: Activity,
		name: 'Activity',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/store_status',
		component: StoreStatus,
		name: 'StoreStatus',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/types',
		component: Type,
		name: 'Type',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/districts',
		component: District,
		name: 'District',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/cities',
		component: City,
		name: 'City',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/countries',
		component: Country,
		name: 'Country',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/users',
		component: User,
		name: 'User',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/passport',
		component: Passport,
		name: 'Passport',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/roles',
		component: Role,
		name: 'Role',
		meta: {
			forAuth: true
		}
	},
	{
		path: '/dofuu-admin-panel/dashboard',
		component: Dashboard,
		name: 'Dashboard',
		meta: {
			forAuth:true
		}
	}
	]
})

router.beforeEach(function(to, from, next) {
	
	if(to.matched.some(record => record.meta.forAuth)) {
		store.dispatch('getAuth')
		if(!Vue.auth.isAuthenticated()) {
			next({path: '/'})
		} else {
			next()
		}
	} else {
		next({path: '/'})
	}
})

export default router