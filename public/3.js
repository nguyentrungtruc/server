webpackJsonp([3],{

/***/ 931:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(91), __webpack_require__(106), __webpack_require__(11)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (typeof exports !== "undefined") {
		factory(exports, require('babel-runtime/core-js/promise'), require('@/config/config.js'), require('axios'));
	} else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, global.promise, global.config, global.axios);
		global.authStore = mod.exports;
	}
})(this, function (exports) {
	(function (global, factory) {
		if (true) {
			!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(91), __webpack_require__(106), __webpack_require__(11)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		} else if (typeof exports !== "undefined") {
			factory(exports);
		} else {
			var mod = {
				exports: {}
			};
			factory(mod.exports, global.promise, global.config, global.axios);
			global.authStore = mod.exports;
		}
	})(this, function (exports, _promise, _config, _axios) {
		'use strict';

		Object.defineProperty(exports, "__esModule", {
			value: true
		});

		var _promise2 = _interopRequireDefault(_promise);

		var _axios2 = _interopRequireDefault(_axios);

		function _interopRequireDefault(obj) {
			return obj && obj.__esModule ? obj : {
				default: obj
			};
		}

		var state = {
			isAuth: !!localStorage.getItem('authUser'),
			authUser: Object,
			loading: false
		};

		var mutations = {
			SET_AUTH_USER: function SET_AUTH_USER(state, payload) {
				state.authUser = payload;
				state.isAuth = true;
			},
			CLEAR_AUTH_USER: function CLEAR_AUTH_USER(state) {
				state.authUser = null;
				state.isAuth = false;
				window.localStorage.removeItem('authUser');
			},
			LOADING_AUTH: function LOADING_AUTH(state) {
				state.loading = !state.loading;
			}
		};

		var actions = {
			getAuth: function getAuth(_ref) {
				var commit = _ref.commit,
				    dispatch = _ref.dispatch;
				return new _promise2.default(function (resolve, reject) {
					var vm = undefined;
					_axios2.default.get(_config.adminUrl, { headers: (0, _config.getHeader)() }).then(function (response) {
						if (response.status === 200) {
							commit('SET_AUTH_USER', response.data);
						}
						resolve(response);
					}).catch(function (errors) {
						reject(errors);
					});
				});
			},
			clearAuth: function clearAuth(_ref2) {
				var commit = _ref2.commit;
				return new _promise2.default(function (resolve, reject) {
					commit('CLEAR_AUTH_USER');
					// axios.get(logoutUrl, {headers: getHeader()}).then(response => {
					// 	if(response.status === 200) {
					// 		commit('CLEAR_AUTH_USER')
					// 	}
					// })
				});
			}
		};

		var getters = {};

		exports.default = {
			state: state, mutations: mutations, actions: actions, getters: getters
		};
	});
});

/***/ })

});