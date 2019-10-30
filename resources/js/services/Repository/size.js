import Repository from './repository'

const resource = '/Size';

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

    update(id, payload, params = null) {
        return Repository.post(`${resource}/${id}/Edit`, payload, {params, withCredentials: true});
    },

    delete(id, params = null) {
        return Repository.post(`${resource}/${id}/Remove`, {params, withCredentials: true});
    }    

}