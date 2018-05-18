import Vue from 'vue'
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
	load: {
		key: 'AIzaSyANKviRikWWh8f9fbGuzh68I2Gl4T8yEEE',
		libraries: 'places',
		v: '3.32',
		language:'vi',
		region: 'VN',
	}
	// libraries: 'places', // This is required if you use the Autocomplete plugin
    // OR: libraries: 'places,drawing'
    // OR: libraries: 'places,drawing,visualization'
    // (as you require)

})