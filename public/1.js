webpackJsonp([1],{

/***/ 1194:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(19), __webpack_require__(98), __webpack_require__(18), __webpack_require__(13), __webpack_require__(542), __webpack_require__(6), __webpack_require__(541), __webpack_require__(3)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (typeof exports !== "undefined") {
		factory(exports, require('babel-runtime/regenerator'), require('babel-runtime/core-js/json/stringify'), require('babel-runtime/helpers/asyncToGenerator'), require('vee-validate'), require('vee-validate/dist/locale/vi'), require('@/config/config'), require('@/env.js'), require('vuex'));
	} else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, global.regenerator, global.stringify, global.asyncToGenerator, global.veeValidate, global.vi, global.config, global.env, global.vuex);
		global.Login = mod.exports;
	}
})(this, function (exports) {
	(function (global, factory) {
		if (true) {
			!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(19), __webpack_require__(98), __webpack_require__(18), __webpack_require__(13), __webpack_require__(542), __webpack_require__(6), __webpack_require__(541), __webpack_require__(3)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		} else if (typeof exports !== "undefined") {
			factory(exports);
		} else {
			var mod = {
				exports: {}
			};
			factory(mod.exports, global.regenerator, global.stringify, global.asyncToGenerator, global.veeValidate, global.vi, global.config, global.env, global.vuex);
			global.Login = mod.exports;
		}
	})(this, function (exports, _regenerator, _stringify, _asyncToGenerator2, _veeValidate, _vi, _config, _env, _vuex) {
		'use strict';

		Object.defineProperty(exports, "__esModule", {
			value: true
		});

		var _regenerator2 = _interopRequireDefault(_regenerator);

		var _stringify2 = _interopRequireDefault(_stringify);

		var _asyncToGenerator3 = _interopRequireDefault(_asyncToGenerator2);

		var _vi2 = _interopRequireDefault(_vi);

		function _interopRequireDefault(obj) {
			return obj && obj.__esModule ? obj : {
				default: obj
			};
		}

		exports.default = {
			data: function data() {
				return {
					locale: 'vi',
					drawer: null,
					email: null,
					password: null,
					loading: false
				};
			},
			props: {
				source: String
			},
			methods: {
				login: function () {
					var _ref = (0, _asyncToGenerator3.default)( /*#__PURE__*/_regenerator2.default.mark(function _callee2() {
						var _this = this;

						var vm;
						return _regenerator2.default.wrap(function _callee2$(_context2) {
							while (1) {
								switch (_context2.prev = _context2.next) {
									case 0:
										vm = this;
										_context2.next = 3;
										return !vm.loading;

									case 3:
										vm.loading = _context2.sent;
										_context2.next = 6;
										return vm.$validator.validateAll().then(function () {
											var _ref2 = (0, _asyncToGenerator3.default)( /*#__PURE__*/_regenerator2.default.mark(function _callee(result) {
												var data, authUser;
												return _regenerator2.default.wrap(function _callee$(_context) {
													while (1) {
														switch (_context.prev = _context.next) {
															case 0:
																if (result) {
																	data = { email: vm.email, password: vm.password };
																	authUser = {};

																	vm.axios.post('/api/DOFUU-AUTH/8420be8df65a43e246a0a6215c5ed977bb099cb8/login', data).then(function (response) {
																		console.log(response);
																		if (response.status == 200) {
																			console.log(response.data);
																			authUser.access_token = response.data.access_token;
																			authUser.expires_in = response.data.expires_in + Date.now();
																			window.localStorage.setItem('authUser', (0, _stringify2.default)(authUser));
																			vm.$store.dispatch('getAuth').then(function (response) {
																				if (response.status == 200) {
																					_this.$router.push({ name: 'Dashboard' });
																				}
																			});
																		}
																	});
																}

															case 1:
															case 'end':
																return _context.stop();
														}
													}
												}, _callee, _this);
											}));

											return function (_x) {
												return _ref2.apply(this, arguments);
											};
										}());

									case 6:
										vm.loading = !vm.loading;

									case 7:
									case 'end':
										return _context2.stop();
								}
							}
						}, _callee2, this);
					}));

					function login() {
						return _ref.apply(this, arguments);
					}

					return login;
				}()
			},
			computed: {},
			mounted: function mounted() {
				this.$validator.localize(this.locale, {
					messages: _vi2.default.messages
				});
			}
		};
	});
});

/***/ }),

/***/ 1195:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-app",
    { attrs: { id: "inspire" } },
    [
      _c(
        "v-content",
        [
          _c(
            "v-container",
            { attrs: { fluid: "", "fill-height": "", color: "grey" } },
            [
              _c(
                "v-layout",
                { attrs: { "justify-center": "" } },
                [
                  _c(
                    "v-flex",
                    { attrs: { xs12: "", sm8: "", md4: "" } },
                    [
                      _c(
                        "v-card",
                        { staticClass: "elevation-12" },
                        [
                          _vm.loading
                            ? _c("v-progress-linear", {
                                attrs: { indeterminate: true }
                              })
                            : _vm._e(),
                          _vm._v(" "),
                          _c(
                            "v-toolbar",
                            {
                              staticClass: "elevation-1",
                              attrs: { color: "white" }
                            },
                            [
                              _c("v-toolbar-title", [_vm._v("Đăng nhập")]),
                              _vm._v(" "),
                              _c("v-spacer")
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-card-text",
                            [
                              _c(
                                "v-form",
                                { attrs: { autocomplete: "off" } },
                                [
                                  _c("v-text-field", {
                                    directives: [
                                      {
                                        name: "validate",
                                        rawName: "v-validate",
                                        value: "required|email",
                                        expression: "'required|email'"
                                      }
                                    ],
                                    attrs: {
                                      "prepend-icon": "email",
                                      name: "email",
                                      label: "Email",
                                      type: "text",
                                      "error-messages": _vm.errors.collect(
                                        "email"
                                      )
                                    },
                                    model: {
                                      value: _vm.email,
                                      callback: function($$v) {
                                        _vm.email =
                                          typeof $$v === "string"
                                            ? $$v.trim()
                                            : $$v
                                      },
                                      expression: "email"
                                    }
                                  }),
                                  _vm._v(" "),
                                  _c("v-text-field", {
                                    directives: [
                                      {
                                        name: "validate",
                                        rawName: "v-validate",
                                        value: "required",
                                        expression: "'required'"
                                      }
                                    ],
                                    attrs: {
                                      "prepend-icon": "lock",
                                      name: "password",
                                      label: "Mật khẩu",
                                      id: "password",
                                      type: "password",
                                      "error-messages": _vm.errors.collect(
                                        "password"
                                      )
                                    },
                                    model: {
                                      value: _vm.password,
                                      callback: function($$v) {
                                        _vm.password =
                                          typeof $$v === "string"
                                            ? $$v.trim()
                                            : $$v
                                      },
                                      expression: "password"
                                    }
                                  })
                                ],
                                1
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-card-actions",
                            [
                              _c(
                                "v-btn",
                                {
                                  attrs: {
                                    block: "",
                                    color: "primary",
                                    loading: _vm.loading,
                                    disabled: _vm.loading
                                  },
                                  on: {
                                    click: function($event) {
                                      $event.preventDefault()
                                      _vm.login()
                                    }
                                  }
                                },
                                [_vm._v("Đăng nhập")]
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-c817b64a", module.exports)
  }
}

/***/ }),

/***/ 222:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(7)
/* script */
var __vue_script__ = __webpack_require__(1194)
/* template */
var __vue_template__ = __webpack_require__(1195)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\credential\\Login.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-c817b64a", Component.options)
  } else {
    hotAPI.reload("data-v-c817b64a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 541:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
  if (true) {
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else if (typeof exports !== "undefined") {
    factory(exports);
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports);
    global.env = mod.exports;
  }
})(this, function (exports) {
  (function (global, factory) {
    if (true) {
      !(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if (typeof exports !== "undefined") {
      factory(exports);
    } else {
      var mod = {
        exports: {}
      };
      factory(mod.exports);
      global.env = mod.exports;
    }
  })(this, function (exports) {
    'use strict';

    Object.defineProperty(exports, "__esModule", {
      value: true
    });
    var clientId = exports.clientId = 4;
    var clientSecret = exports.clientSecret = 'noSiDc4MfnJLUDtc5yLB3uUqnkNQph95BvNuq8s4';
  });
});

/***/ }),

/***/ 542:
/***/ (function(module, exports, __webpack_require__) {

!function(n,t){ true?module.exports=t():"function"==typeof define&&define.amd?define(t):(n.__vee_validate_locale__vi=n.__vee_validate_locale__vi||{},n.__vee_validate_locale__vi.js=t())}(this,function(){"use strict";var n,t={name:"vi",messages:{_default:function(n){return"Giá trị của "+n+" không đúng."},after:function(n,t){return n+" phải xuất hiện sau "+t[0]+"."},alpha_dash:function(n){return n+" có thể chứa các kí tự chữ (A-Z a-z), số (0-9), gạch ngang (-) và gạch dưới (_)."},alpha_num:function(n){return n+" chỉ có thể chứa các kí tự chữ và số."},alpha_spaces:function(n){return n+" chỉ có thế chứa các kí tự và khoảng trắng"},alpha:function(n){return n+" chỉ có thể chứa các kí tự chữ."},before:function(n,t){return n+" phải xuất hiện trước "+t[0]+"."},between:function(n,t){return n+" phải có giá trị nằm trong khoảng giữa "+t[0]+" và "+t[1]+"."},confirmed:function(n,t){return n+" khác với "+t[0]+"."},credit_card:function(n){return"Đã điền "+n+" không chính xác."},date_between:function(n,t){return n+" phải có giá trị nằm trong khoảng giữa  "+t[0]+" và "+t[1]+"."},date_format:function(n,t){return n+" phải có giá trị dưới định dạng "+t[0]+"."},decimal:function(n,t){void 0===t&&(t=[]);var i=t[0];return void 0===i&&(i="*"),n+" chỉ có thể chứa các kí tự số và dấu thập phân "+("*"===i?"":i)+"."},digits:function(n,t){return"Trường "+n+" chỉ có thể chứa các kí tự số và bắt buộc phải có độ dài là "+t[0]+"."},dimensions:function(n,t){return n+" phải có chiều rộng "+t[0]+" pixels và chiều cao "+t[1]+" pixels."},email:function(n){return n+" phải là một địa chỉ email hợp lệ."},ext:function(n){return n+" phải là một tệp."},image:function(n){return"Trường "+n+" phải là một ảnh."},in:function(n){return n+" phải là một giá trị."},ip:function(n){return n+" phải là một địa chỉ ip hợp lệ."},max:function(n,t){return n+" không thể có nhiều hơn "+t[0]+" kí tự."},max_value:function(n,t){return n+" phải nhỏ hơn hoặc bằng "+t[0]+"."},mimes:function(n){return n+" phải chứa kiểu tệp phù hợp."},min:function(n,t){return n+" phải chứa ít nhất "+t[0]+" kí tự."},min_value:function(n,t){return n+" phải lớn hơn hoặc bằng "+t[0]+"."},not_in:function(n){return n+" phải chứa một giá trị hợp lệ."},numeric:function(n){return n+" chỉ có thể có các kí tự số."},regex:function(n){return n+" có định dạng không đúng."},required:function(n){return n+" là bắt buộc."},size:function(n,t){var i,c,e,h=t[0];return n+" chỉ có thể chứa tệp nhỏ hơn "+(i=h,c=1024,e=0==(i=Number(i)*c)?0:Math.floor(Math.log(i)/Math.log(c)),1*(i/Math.pow(c,e)).toFixed(2)+" "+["Byte","KB","MB","GB","TB","PB","EB","ZB","YB"][e])+"."},url:function(n){return n+" không phải là một địa chỉ URL hợp lệ."}},attributes:{}};"undefined"!=typeof VeeValidate&&VeeValidate.Validator.localize(((n={})[t.name]=t,n));return t});

/***/ })

});