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

export const apiDomain = 'https://admin.dofuu.com'
//domain xyz
// export const imageURL  = 'https://www.dofuu.xyz'
//domain com
export const imageURL  = 'https://www.dofuu.com'

export const loginUrl  = apiDomain + '/oauth/token'
export const logoutUrl = apiDomain + '/api/logout'
export const adminUrl = apiDomain+'/api/dofuuAdmin'

//API Delivery
export const activeDelivery = apiDomain+'/api/Dofuu-Delivery/active'
export const rangeUrl = apiDomain+ '/api/dofuuRanges'

export const productUrl = apiDomain+ '/api/dofuuProducts'
export const productStatusUrl = apiDomain+ '/api/dofuuProductStatus'
export const storeUrl = apiDomain+ '/api/dofuuStores'
export const storeStatusUrl = apiDomain+ '/api/dofuuStoreStatus'
export const typeUrl = apiDomain+ '/api/dofuuTypes'

//API City
export const activeDeliveryUrl = apiDomain+ '/api/Dofuu-Cities/ActiveDelivery' 
export const citiesDoesntHaveDeliveryUrl = apiDomain+ '/api/Dofuu-CitiesDoesntHaveDelivery'
export const cityWithStoreUrl = apiDomain+ '/api/dofuuCityWithStores'

//API Country
export const userUrl = apiDomain+ '/api/dofuuUsers'
export const roleUrl = apiDomain+ '/api/dofuuRoles'
