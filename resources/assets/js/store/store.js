import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
Vue.config.debug = true;
// const store = new Vuex.Store()
import notificationStore from './notificationStore'
import paymentStore from './paymentStore'
import orderStatusStore from './orderStatusStore'
import productStore from './productStore'
import catalogueStore from './catalogueStore'
import productStatusStore from './productStatusStore'
import deliveryStore from './deliveryStore'
import rangeStore from './rangeStore'
import couponStore from './couponStore'
import couponStatusStore from './couponStatusStore'
import storeStore from './storeStore'
import storeStatusStore from './storeStatusStore'
import activityStore from './activityStore'
import typeStore from './typeStore'
import districtStore from './districtStore'
import cityStore from './cityStore'
import countryStore from './countryStore'
import userStore from './userStore'
import roleStore from './roleStore'
import authStore from './authStore'

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
	modules: {
		authStore, roleStore, userStore, 
		countryStore, cityStore, districtStore,
		typeStore,
		activityStore,
		storeStatusStore, storeStore,
		couponStatusStore, couponStore,
		rangeStore, deliveryStore,
		productStatusStore,
		catalogueStore,
		productStore,
		orderStatusStore,
		paymentStore,
		notificationStore
	},
	strict: debug
});