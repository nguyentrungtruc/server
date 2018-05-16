webpackJsonp([0],{

/***/ 929:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(91), __webpack_require__(13), __webpack_require__(106), __webpack_require__(11)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (typeof exports !== "undefined") {
		factory(exports, require('babel-runtime/core-js/promise'), require('babel-runtime/core-js/object/assign'), require('@/config/config.js'), require('axios'));
	} else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, global.promise, global.assign, global.config, global.axios);
		global.productStatusStore = mod.exports;
	}
})(this, function (exports) {
	(function (global, factory) {
		if (true) {
			!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(91), __webpack_require__(13), __webpack_require__(106), __webpack_require__(11)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		} else if (typeof exports !== "undefined") {
			factory(exports);
		} else {
			var mod = {
				exports: {}
			};
			factory(mod.exports, global.promise, global.assign, global.config, global.axios);
			global.productStatusStore = mod.exports;
		}
	})(this, function (exports, _promise, _assign, _config, _axios) {
		'use strict';

		Object.defineProperty(exports, "__esModule", {
			value: true
		});

		var _promise2 = _interopRequireDefault(_promise);

		var _assign2 = _interopRequireDefault(_assign);

		var _axios2 = _interopRequireDefault(_axios);

		function _interopRequireDefault(obj) {
			return obj && obj.__esModule ? obj : {
				default: obj
			};
		}

		var state = {
			productStatus: {},
			loading: false,
			dialog: false,
			editedIndex: -1,
			editedItem: {},
			alert: {
				alert: false,
				messages: '',
				type: 'success'
			}
		};

		var mutations = {
			FETCH_PRODUCT_STATUS: function FETCH_PRODUCT_STATUS(state, payload) {
				state.productStatus = payload;
			},
			LOADING_PRODUCT_STATUS: function LOADING_PRODUCT_STATUS(state) {
				state.loading = !state.loading;
			},
			DIALOG_PRODUCT_STATUS: function DIALOG_PRODUCT_STATUS(state) {
				state.dialog = !state.dialog;
			},
			DIALOG_CLOSE: function DIALOG_CLOSE(state) {
				state.dialog = false;
				state.editedIndex = -1;
			},
			EDIT_PRODUCT_STATUS: function EDIT_PRODUCT_STATUS(state, payload) {
				state.editedIndex = state.productStatus.indexOf(payload);
				state.editedItem = (0, _assign2.default)({}, payload);
				state.dialog = true;
			},
			UPDATE_PRODUCT_STATUS: function UPDATE_PRODUCT_STATUS(state, payload) {
				if (state.editedIndex > -1) {
					(0, _assign2.default)(state.productStatus[state.editedIndex], payload);
				} else {
					state.productStatus.push(payload);
				}
			},
			REMOVE_PRODUCT_STATUS: function REMOVE_PRODUCT_STATUS(state, payload) {
				var productStatus = state.productStatus;
				productStatus.splice(productStatus.indexOf(payload), 1);
			},
			ALERT_PRODUCT_STATUS: function ALERT_PRODUCT_STATUS(state, payload) {
				state.alert = (0, _assign2.default)(state.alert, payload);
				setTimeout(function () {
					state.alert = {
						alert: false,
						messages: '',
						type: 'success'
					};
				}, 5000);
			}
		};

		var actions = {
			fetchProductStatus: function fetchProductStatus(_ref, payload) {
				var commit = _ref.commit,
				    state = _ref.state;
				return new _promise2.default(function (resolve, reject) {
					_axios2.default.get(productStatusUrl, { headers: (0, _config.getHeader)() }).then(function (response) {
						if (response.status === 200) {
							commit('FETCH_PRODUCT_STATUS', response.data);
						}
						resolve(response);
					}).catch(function (errors) {
						reject(errors);
					});
				});
			},
			addProductStatus: function addProductStatus(_ref2, payload) {
				var commit = _ref2.commit,
				    state = _ref2.state;
				return new _promise2.default(function (resolve, reject) {
					_axios2.default.post(productStatusUrl, payload, { headers: (0, _config.getHeader)() }).then(function (response) {
						if (response.status === 200) {
							commit('UPDATE_PRODUCT_STATUS', response.data);
							commit('ALERT_PRODUCT_STATUS', { alert: true, messages: payload.product_status_name + ' status has been added.', type: 'success' });
						}
						resolve(response);
					}).catch(function (errors) {
						reject(errors);
						if (errors.response.status === 422) {
							commit('ALERT_PRODUCT_STATUS', { alert: true, messages: payload.product_status_name + ' status has already been taken.', type: 'error' });
						}
					});
				});
			},
			editProductStatus: function editProductStatus(_ref3, payload) {
				var commit = _ref3.commit;
				return new _promise2.default(function (resolve, reject) {
					commit('EDIT_PRODUCT_STATUS', payload);
				});
			},
			updateProductStatus: function updateProductStatus(_ref4, payload) {
				var commit = _ref4.commit;
				return new _promise2.default(function (resolve, reject) {
					var vm = undefined;
					_axios2.default.put(productStatusUrl + '/' + payload.id, payload, { headers: (0, _config.getHeader)() }).then(function (response) {
						if (response.status == 201) {
							commit('UPDATE_PRODUCT_STATUS', response.data);
							commit('ALERT_PRODUCT_STATUS', { alert: true, messages: payload.product_status_name + ' status has been edited.', type: 'success' });
						}
						resolve(response);
					}).catch(function (errors) {
						if (errors.response.status == 422) {
							commit('ALERT_PRODUCT_STATUS', { alert: true, messages: payload.product_status_name + ' status has already been taken.', type: 'error' });
						}
						reject(errors);
					});
				});
			},
			deleteProductStatus: function deleteProductStatus(_ref5, payload) {
				var commit = _ref5.commit;
				return new _promise2.default(function (resolve, reject) {
					var vm = undefined;
					_axios2.default.delete(productStatusUrl + '/' + payload.id, { headers: (0, _config.getHeader)() }).then(function (response) {
						if (response.status == 204) {
							commit('REMOVE_PRODUCT_STATUS', payload);
							commit('ALERT_PRODUCT_STATUS', { alert: true, messages: payload.product_status_name + ' status has been deleted.', type: 'success' });
						}
						resolve(response);
					}).catch(function (errors) {
						reject(errors);
					});
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