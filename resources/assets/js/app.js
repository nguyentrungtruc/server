
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */



// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });

import '@/static-loader'
import Vue from 'vue';
import App from '@/App.vue';
import router from '@/router/router.js'
import store from '@/store/store.js'
import { http } from '@/api'
import filters from '@/filters'

Vue.config.productionTip = false

require('./bootstrap');

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
    );

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
    );

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
    );

Vue.use(filters)

new Vue({
	el: '#app',
	store,
	router,
	components: { App },
	template: '<App/>',
    created() {
        // http.init()
    }
})
