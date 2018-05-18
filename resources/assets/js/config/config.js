import Vue from 'vue'
import Auth from '@/utils/auth'
Vue.use(Auth)
export const getHeader = function(){
	const tokenData = Vue.auth.getToken()
	const headers = {
		'Accept': 'application/json',
		'Authorization':'Bearer ' +tokenData
	}
	return headers
}

// export const apiDomain = 'http://167.99.74.115'
export const loginUrl  = apiDomain + '/oauth/token'
export const logoutUrl = apiDomain + '/api/logout'
export const adminUrl = apiDomain+'/api/dofuuAdmin'

//API Delivery
export const activeDelivery = apiDomain+'/api/Dofuu-Delivery/active'
export const deliveryUrl = apiDomain+'/api/Dofuu-Delivery'
export const rangeUrl = apiDomain+ '/api/dofuuRanges'
export const couponUrl = apiDomain+ '/api/dofuuCoupons'
export const couponStatusUrl = apiDomain+ '/api/dofuuCouponStatus'
export const productUrl = apiDomain+ '/api/dofuuProducts'
export const catalogueUrl = apiDomain+ '/api/dofuuCatalogues'
export const productStatusUrl = apiDomain+ '/api/dofuuProductStatus'
export const storeUrl = apiDomain+ '/api/dofuuStores'
export const activityUrl = apiDomain+ '/api/dofuuActivities'
export const storeStatusUrl = apiDomain+ '/api/dofuuStoreStatus'
export const typeUrl = apiDomain+ '/api/dofuuTypes'
export const districtUrl = apiDomain+ '/api/dofuuDistricts'
//API City
export const activeDeliveryUrl = apiDomain+ '/api/Dofuu-Cities/ActiveDelivery' 
export const citiesDoesntHaveDeliveryUrl = apiDomain+ '/api/Dofuu-CitiesDoesntHaveDelivery'
export const cityWithStoreUrl = apiDomain+ '/api/dofuuCityWithStores'
export const cityUrl = apiDomain+ '/api/dofuuCities'
//API Country
export const countryUrl = apiDomain+ '/api/dofuuCountries'
export const userUrl = apiDomain+ '/api/dofuuUsers'
export const roleUrl = apiDomain+ '/api/dofuuRoles'
