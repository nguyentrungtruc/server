import Vue from 'vue'
import Vuex from 'vuex'

import state from './state'
import getters from './getters'
import mutations from './mutations'
import actions from './actions'
//MODULES
import activity from './modules/activity'
import alert from './modules/alert'
import auth from './modules/auth'
import catalogue from './modules/catalogue'
import city from './modules/city'
import country from './modules/country'
import couponStatus from './modules/couponStatus'
import district from './modules/district'
import orderStatus from './modules/orderStatus'
import product from './modules/product'
import productStatus from './modules/productStatus'
import ratingType from './modules/ratingType'
import role from './modules/role'
import size from './modules/size'
import store from './modules/store'
import storeStatus from './modules/storeStatus'
import topping from './modules/topping'
import type from './modules/type'
import user from './modules/user'
Vue.use(Vuex)
const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions,
    modules: {
        activity,
        alert,
        auth,
        catalogue,
        city,
        country,
        couponStatus,
        district,
        orderStatus,
        product,
        productStatus,
        ratingType,
        role,
        size,
        store,
        storeStatus,
        topping,
        type,
        user
    },
    strict: debug
})