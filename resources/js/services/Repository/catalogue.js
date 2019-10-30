import Repository from './repository'

const resource = '/Catalogue';

export default {
    get(params = null) {
        return Repository.get(`${resource}/Fetch`, {params, withCredentials: true});
    },

    show(id, params = null) {
        return Repository.get(`${resource}/${id}/Show`, {params, withCredentials: true});
    },

    create(payload, params = null) {
        return Repository.post(`${resource}/Add`, payload, {params, withCredentials: true});
    },

    update(id, payload) {
        return Repository.post(`${resource}/${id}/Edit`, payload, {withCredentials: true});
    },

    delete(id) {
        return Repository.post(`${resource}/${id}/Remove`, {withCredentials: true});
    }
    

}