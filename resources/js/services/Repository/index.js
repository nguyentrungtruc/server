import catalogue from './catalogue'
import city from './city'
import country from './country'
import couponStatus from './couponStatus'
import district from './district'
import orderStatus from './orderStatus'
import product from './product'
import productStatus from './productStatus'
import ratingType from './ratingType'
import role from './role'
import size from './size'
import store from './store'
import storeStatus from './storeStatus'
import topping from './topping'
import type from './type'
import user from './user'
const repositories = {
    catalogues   : catalogue,
    cities       : city,
    countries    : country,
    couponStatus : couponStatus,
    districts    : district,
    orderStatus  : orderStatus,
    products     : product,
    productStatus: productStatus,
    ratingType   : ratingType,
    roles        : role,
    sizes        : size,
    stores       : store,
    storeStatus  : storeStatus,
    toppings     : topping,
    types        : type,
    users        : user
}

export const RepositoryFactory = {
    get: name => repositories[name]
}