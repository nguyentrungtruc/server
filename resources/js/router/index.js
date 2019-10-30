import Vue from 'vue'
import Router from 'vue-router'
import auth from '@/utils/auth.js'
Vue.use(Router)
Vue.use(auth)
import Main from '@/layout/default'
import Credential from '@/layout/credential'
import Dashboard from '@/pages/dashboard/Index'
import Activity from '@/pages/activity/Index'
import City from '@/pages/city/Index'
import Country from '@/pages/country/Index'
import CouponStatus from '@/pages/status/coupon/Index'
import District from '@/pages/district/Index'
import Order from  '@/pages/order/Index'
import OrderStatus from '@/pages/status/order/Index'
import ProductStatus from '@/pages/status/product/Index'
import RatingType from '@/pages/type/rating/Index'
import Role from '@/pages/role/Index'
import Size from '@/pages/size/Index'
import Store from '@/pages/store/Index'
import StoreDetail from '@/pages/store/details/Index'
import About from '@/pages/store/details/about/Index'
import Menu from '@/pages/store/details/menu/Index'
import General from '@/pages/store/details/menu/general/Index'
import Catalogue from '@/pages/store/details/menu/catalogues/Index'
import Product from '@/pages/store/details/menu/products/Index'
import Topping from '@/pages/store/details/menu/toppings/Index'
import StoreStatus from '@/pages/status/store/Index'
import Type from '@/pages/type/store/Index'
import User from '@/pages/user/Index'
import Admin from '@/pages/user/admin/Index'
import Customer from '@/pages/user/customer/Index'
import Employee from '@/pages/user/employee/Index'
import Partner from '@/pages/user/partner/Index'
import Shipper from '@/pages/user/shipper/Index'
import UserDetail from '@/pages/user/detail/Index'
const routes = [
    {
        path     : '/',
        component: Credential,
        name     : 'Home',
        meta     : {
            forAuth: false
        }
    },
    {
        path     : '/',
        component: Main,
        children : [
            {
                path     : '/activities',
                component: Activity,
                name     : 'Activity',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/districts',
                component: District,
                name     : 'District',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/cities',
                component: City,
                name     : 'City',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/countries',
                component: Country,
                name     : 'Country',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/roles',
                component: Role,
                name     : 'Role',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/stores/details/:storeId',
                component: StoreDetail,
                redirect : {name: 'About'},
                children : [
                    { path: 'about', component: About, name: 'About', meta: {forAuth: true}},
                    { path: 'menu', component: Menu, name: 'Menu', redirect: {name: 'General'}, meta: {forAuth: true}, children: [
                        { path: 'generals', component: General, name: 'General', meta: {forAuth: true}},
                        { path: 'catalogues', component: Catalogue, name: 'Catalogue', meta: {forAuth: true}},
                        { path: 'toppings', component: Topping, name: 'Topping', meta: {forAuth: true}},
                        { path: 'products', component: Product, name: 'Product', meta: {forAuth: true}}
                    ]},
                ],
                meta: {forAuth: true}
            },
            {
                path     : '/stores',
                component: Store,
                name     : 'Store',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/sizes',
                component: Size,
                name     : 'Size',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/order-status',
                component: OrderStatus,
                name     : 'OrderStatus',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/orders',
                component: Order,
                name     : 'Order',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/coupon-status',
                component: CouponStatus,
                name     : 'CouponStatus',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/product-status',
                component: ProductStatus,
                name     : 'ProductStatus',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/store-status',
                component: StoreStatus,
                name     : 'StoreStatus',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/rating-types',
                component: RatingType,
                name     : 'RatingType',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/store-types',
                component: Type,
                name     : 'Type',
                meta     : {
                    forAuth: true
                }
            },
            {
                path     : '/users/:userId/details',
                component: UserDetail,
                name     : 'UserDetail',
                meta     : {forAuth: true}
            },
            { 
                path     : '/users',
                component: User,
                name     : 'User',
                meta     : {auth:true},
                children : [
                    { path: 'admins', component: Admin, name: 'Admin', meta: {forAuth: true}},
                    { path: 'customers', component: Customer, name: 'Customer', meta: {forAuth: true}},
                    { path: 'employees', component: Employee, name: 'Employee', meta: {forAuth: true}},
                    { path: 'partner', component: Partner, name: 'Partner', meta: {forAuth: true}},
                    { path: 'shippers', component: Shipper, name: 'Shipper', meta: {forAuth: true}},
                ]
    
            },
            {
                path     : '/dashboard',
                component: Dashboard,
                name     : 'Dashboard',
                meta     : {
                    forAuth: true
                }
            }
        ],
        meta     : {
            forAuth: true
        }
    },        
]
const router = new Router({
    mode: 'history',
    routes
});

router.beforeEach(function(to, from, next) {
    if(to.meta.forAuth) {
        if(Vue.auth.isAuthenticated()) {
            next()
        } else {
            next({name: 'Home'})
        }
    } else {
        if(Vue.auth.isAuthenticated()) {
            next({name: 'Dashboard'})
        } else {
            next()
        }
    }
})

export default router