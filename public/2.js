webpackJsonp([2],{

/***/ 1163:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(1164)
}
var normalizeComponent = __webpack_require__(6)
/* script */
var __vue_script__ = __webpack_require__(1166)
/* template */
var __vue_template__ = __webpack_require__(1167)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-4ec0590a"
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
Component.options.__file = "resources\\assets\\js\\components\\store\\storeDetails\\Activity\\activityDialog.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4ec0590a", Component.options)
  } else {
    hotAPI.reload("data-v-4ec0590a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 1164:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(1165);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(9)("7d7e5395", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../../../node_modules/css-loader/index.js!../../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-4ec0590a\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./activityDialog.vue", function() {
     var newContent = require("!!../../../../../../../node_modules/css-loader/index.js!../../../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-4ec0590a\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./activityDialog.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 1165:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(8)(false);
// imports


// module
exports.push([module.i, "\n.google-map[data-v-4ec0590a] {\r\n\twidth: 800px;\r\n\theight: 600px;\r\n\tmargin: 0 auto;\r\n\tbackground: gray;\n}\r\n", ""]);

// exports


/***/ }),

/***/ 1166:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;(function (global, factory) {
	if (true) {
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(23), __webpack_require__(22), __webpack_require__(4), !(function webpackMissingModule() { var e = new Error("Cannot find module \"./districtDialog\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()), __webpack_require__(3)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else if (typeof exports !== "undefined") {
		factory(exports, require('babel-runtime/regenerator'), require('babel-runtime/helpers/asyncToGenerator'), require('babel-runtime/helpers/extends'), require('./districtDialog'), require('vuex'));
	} else {
		var mod = {
			exports: {}
		};
		factory(mod.exports, global.regenerator, global.asyncToGenerator, global._extends, global.districtDialog, global.vuex);
		global.activityDialog = mod.exports;
	}
})(this, function (exports) {
	(function (global, factory) {
		if (true) {
			!(__WEBPACK_AMD_DEFINE_ARRAY__ = [exports, __webpack_require__(23), __webpack_require__(22), __webpack_require__(4), !(function webpackMissingModule() { var e = new Error("Cannot find module \"./districtDialog\""); e.code = 'MODULE_NOT_FOUND'; throw e; }()), __webpack_require__(3)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		} else if (typeof exports !== "undefined") {
			factory(exports);
		} else {
			var mod = {
				exports: {}
			};
			factory(mod.exports, global.regenerator, global.asyncToGenerator, global._extends, global.districtDialog, global.vuex);
			global.activityDialog = mod.exports;
		}
	})(this, function (exports, _regenerator, _asyncToGenerator2, _extends2, _districtDialog, _vuex) {
		'use strict';

		Object.defineProperty(exports, "__esModule", {
			value: true
		});

		var _regenerator2 = _interopRequireDefault(_regenerator);

		var _asyncToGenerator3 = _interopRequireDefault(_asyncToGenerator2);

		var _extends3 = _interopRequireDefault(_extends2);

		var _districtDialog2 = _interopRequireDefault(_districtDialog);

		function _interopRequireDefault(obj) {
			return obj && obj.__esModule ? obj : {
				default: obj
			};
		}

		exports.default = {
			components: {
				'vue-dialog': _districtDialog2.default
			},
			data: function data() {
				return {
					title: 'Activity Time',
					items: []
				};
			},
			methods: {
				saveTime: function saveTime(ref, time, index) {
					this.$refs[ref][index].save(time);
				},
				save: function save() {
					var data = [];
					this.items.forEach(function (item) {
						if (item.id != null) {
							var timeTemp = [];
							item.times.forEach(function (time) {
								timeTemp.push({ from: time.from, to: time.to });
							});
							data.push({ activity_id: item.id, times: timeTemp });
						}
					});
					if (data.length > 0) {
						var payload = { data: data };
						axios.post('/api/DofuuActivityTime/' + this.store.id, payload, { headers: getHeader() }).then(function (response) {
							console.log(response);
						}).catch(function (error) {});
					}
				}
			},
			computed: (0, _extends3.default)({}, (0, _vuex.mapState)({
				dialog: function dialog(state) {
					return state.activityStore.dialog;
				}
			})),
			watch: {
				'activities': function activities(val) {
					var array = new Array();
					val.forEach(function (item) {
						array.push({ id: null, times: [{ from: '08:00', to: '22:00', fromModal: false, toModal: false }] });
					});
					console.log(array);
					this.items = array;
				}
			},
			mounted: function () {
				var _ref = (0, _asyncToGenerator3.default)( /*#__PURE__*/_regenerator2.default.mark(function _callee() {
					return _regenerator2.default.wrap(function _callee$(_context) {
						while (1) {
							switch (_context.prev = _context.next) {
								case 0:
									console.log(this.dialog);
									if (this.dialog) {
										this.$store.dispatch('fetchActivity');
									}

								case 2:
								case 'end':
									return _context.stop();
							}
						}
					}, _callee, this);
				}));

				function mounted() {
					return _ref.apply(this, arguments);
				}

				return mounted;
			}()
		};
	});
});

/***/ }),

/***/ 1167:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-dialog",
    {
      attrs: { persistent: "", "max-width": "100%" },
      model: {
        value: _vm.activityDialog,
        callback: function($$v) {
          _vm.activityDialog = $$v
        },
        expression: "activityDialog"
      }
    },
    [
      _c(
        "v-card",
        [
          _c("v-card-title", { staticClass: "headline" }, [
            _vm._v("Activity time")
          ]),
          _vm._v(" "),
          _c(
            "v-card-text",
            [
              _vm._v("\n\t\t\t" + _vm._s(_vm.items) + "\n\t\t\t"),
              _vm._l(_vm.activities, function(item, index) {
                return _c(
                  "v-layout",
                  { key: item.id, attrs: { row: "", wrap: "" } },
                  [
                    _c(
                      "v-flex",
                      { attrs: { xs12: "", sm4: "", md4: "" } },
                      [
                        _c("v-checkbox", {
                          staticClass: "pt-3",
                          attrs: {
                            "hide-details": "",
                            label: item.daysOfWeek,
                            value: item.id
                          },
                          model: {
                            value: _vm.items[index].id,
                            callback: function($$v) {
                              _vm.$set(_vm.items[index], "id", $$v)
                            },
                            expression: "items[index].id"
                          }
                        })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _vm._l(_vm.items[index].times, function(time, i) {
                      return _c(
                        "v-flex",
                        { key: i, attrs: { xs8: "" } },
                        [
                          _c(
                            "v-layout",
                            { attrs: { row: "", wrap: "" } },
                            [
                              _c(
                                "v-flex",
                                { attrs: { xs6: "" } },
                                [
                                  _c(
                                    "v-dialog",
                                    {
                                      ref: "from",
                                      refInFor: true,
                                      attrs: {
                                        persistent: "",
                                        lazy: "",
                                        "full-width": "",
                                        width: "290px",
                                        "return-value": time.from
                                      },
                                      on: {
                                        "update:returnValue": function($event) {
                                          _vm.$set(time, "from", $event)
                                        }
                                      },
                                      model: {
                                        value: time.fromModal,
                                        callback: function($$v) {
                                          _vm.$set(time, "fromModal", $$v)
                                        },
                                        expression: "time.fromModal"
                                      }
                                    },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          slot: "activator",
                                          "hide-details": "",
                                          label: "Start time",
                                          "single-line": "",
                                          "prepend-icon": "access_time",
                                          readonly: ""
                                        },
                                        slot: "activator",
                                        model: {
                                          value: time.from,
                                          callback: function($$v) {
                                            _vm.$set(time, "from", $$v)
                                          },
                                          expression: "time.from"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c(
                                        "v-time-picker",
                                        {
                                          attrs: {
                                            actions: "",
                                            format: "24hr"
                                          },
                                          model: {
                                            value: time.from,
                                            callback: function($$v) {
                                              _vm.$set(time, "from", $$v)
                                            },
                                            expression: "time.from"
                                          }
                                        },
                                        [
                                          _c("v-spacer"),
                                          _vm._v(" "),
                                          _c(
                                            "v-btn",
                                            {
                                              attrs: {
                                                flat: "",
                                                color: "primary"
                                              },
                                              on: {
                                                click: function($event) {
                                                  time.fromModal = false
                                                }
                                              }
                                            },
                                            [_vm._v("Cancel")]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-btn",
                                            {
                                              attrs: {
                                                flat: "",
                                                color: "primary"
                                              },
                                              on: {
                                                click: function($event) {
                                                  _vm.saveTime(
                                                    "from",
                                                    time.from,
                                                    index
                                                  )
                                                }
                                              }
                                            },
                                            [_vm._v("OK")]
                                          )
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "v-flex",
                                { attrs: { xs6: "" } },
                                [
                                  _c(
                                    "v-dialog",
                                    {
                                      ref: "to",
                                      refInFor: true,
                                      attrs: {
                                        persistent: "",
                                        lazy: "",
                                        "full-width": "",
                                        width: "290px",
                                        "return-value": time.to
                                      },
                                      on: {
                                        "update:returnValue": function($event) {
                                          _vm.$set(time, "to", $event)
                                        }
                                      },
                                      model: {
                                        value: time.toModal,
                                        callback: function($$v) {
                                          _vm.$set(time, "toModal", $$v)
                                        },
                                        expression: "time.toModal"
                                      }
                                    },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          slot: "activator",
                                          "hide-details": "",
                                          label: "End time",
                                          "prepend-icon": "access_time",
                                          readonly: ""
                                        },
                                        slot: "activator",
                                        model: {
                                          value: time.to,
                                          callback: function($$v) {
                                            _vm.$set(time, "to", $$v)
                                          },
                                          expression: "time.to"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c(
                                        "v-time-picker",
                                        {
                                          attrs: {
                                            actions: "",
                                            format: "24hr"
                                          },
                                          model: {
                                            value: time.to,
                                            callback: function($$v) {
                                              _vm.$set(time, "to", $$v)
                                            },
                                            expression: "time.to"
                                          }
                                        },
                                        [
                                          _c("v-spacer"),
                                          _vm._v(" "),
                                          _c(
                                            "v-btn",
                                            {
                                              attrs: {
                                                flat: "",
                                                color: "primary"
                                              },
                                              on: {
                                                click: function($event) {
                                                  time.toModal = false
                                                }
                                              }
                                            },
                                            [_vm._v("Cancel")]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-btn",
                                            {
                                              attrs: {
                                                flat: "",
                                                color: "primary"
                                              },
                                              on: {
                                                click: function($event) {
                                                  _vm.saveTime(
                                                    "to",
                                                    time.to,
                                                    index
                                                  )
                                                }
                                              }
                                            },
                                            [_vm._v("OK")]
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
                    })
                  ],
                  2
                )
              })
            ],
            2
          ),
          _vm._v(" "),
          _c(
            "v-card-actions",
            [
              _c("v-spacer"),
              _vm._v(" "),
              _c(
                "v-btn",
                {
                  attrs: { color: "green darken-1", flat: "" },
                  nativeOn: {
                    click: function($event) {
                      _vm.$store.commit("DIALOG_CLOSE_ACTIVITY")
                    }
                  }
                },
                [_vm._v("Cancel")]
              ),
              _vm._v(" "),
              _c(
                "v-btn",
                {
                  attrs: { color: "green darken-1", flat: "" },
                  nativeOn: {
                    click: function($event) {
                      _vm.save($event)
                    }
                  }
                },
                [_vm._v("Save")]
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
    require("vue-hot-reload-api")      .rerender("data-v-4ec0590a", module.exports)
  }
}

/***/ })

});