import Repository from './repository'

const resource = '/Store';

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

    updateAvatar(id, payload, params = null) {
        return Repository.post(`${resource}/${id}/Avatar/Update`, payload, {params, withCredentials: true});
    },

    updateActivity(id, payload, params = null) {
        return Repository.post(`${resource}/${id}/Activity/Update`, payload, {params, withCredentials: true});
    },

    delete(id, params = null) {
        return Repository.post(`${resource}/${id}/Remove`, {params, withCredentials: true});
    }    

}