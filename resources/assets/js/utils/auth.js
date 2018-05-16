export default function (Vue) {
	let authenticatedUser = {}
	Vue.auth = {
		setToken(token, expiration) {
			localStorage.setItem('authUser', token)
			localStorage.setItem('authUser', expiration)
		},
		getToken() {
			var auth = JSON.parse(localStorage.getItem('authUser'))
			if(!auth) {
				return null
			}
			if(Date.now()>parseInt(auth.expires_in)) {
				this.destroyToken();
				return null;
			}
			else{
				return auth.access_token 
			}
		},
		isAdmin() {
			var auth = JSON.parse(localStorage.getItem('authUser'))
			if(parseInt(auth.roles) === 1 || parseInt(auth.roles) === 2) {
				return true
			}
			return false
		},
		destroyToken() {
			localStorage.removeItem('authUser')
		},
		isAuthenticated() {
			if(this.getToken()) {
				return true
			} else {
				return false
			}
		}
	}
	Object.defineProperties(Vue.prototype, {
		$auth: {
			get() {
				return Vue.auth
			}
		}
	})
}
