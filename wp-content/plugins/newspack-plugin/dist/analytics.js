(function(e, a) { for(var i in a) e[i] = a[i]; }(window, /******/ (function(modules) { // webpackBootstrap
/******/ 	// install a JSONP callback for chunk loading
/******/ 	function webpackJsonpCallback(data) {
/******/ 		var chunkIds = data[0];
/******/ 		var moreModules = data[1];
/******/ 		var executeModules = data[2];
/******/
/******/ 		// add "moreModules" to the modules object,
/******/ 		// then flag all "chunkIds" as loaded and fire callback
/******/ 		var moduleId, chunkId, i = 0, resolves = [];
/******/ 		for(;i < chunkIds.length; i++) {
/******/ 			chunkId = chunkIds[i];
/******/ 			if(Object.prototype.hasOwnProperty.call(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 				resolves.push(installedChunks[chunkId][0]);
/******/ 			}
/******/ 			installedChunks[chunkId] = 0;
/******/ 		}
/******/ 		for(moduleId in moreModules) {
/******/ 			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
/******/ 				modules[moduleId] = moreModules[moduleId];
/******/ 			}
/******/ 		}
/******/ 		if(parentJsonpFunction) parentJsonpFunction(data);
/******/
/******/ 		while(resolves.length) {
/******/ 			resolves.shift()();
/******/ 		}
/******/
/******/ 		// add entry modules from loaded chunk to deferred list
/******/ 		deferredModules.push.apply(deferredModules, executeModules || []);
/******/
/******/ 		// run deferred modules when all chunks ready
/******/ 		return checkDeferredModules();
/******/ 	};
/******/ 	function checkDeferredModules() {
/******/ 		var result;
/******/ 		for(var i = 0; i < deferredModules.length; i++) {
/******/ 			var deferredModule = deferredModules[i];
/******/ 			var fulfilled = true;
/******/ 			for(var j = 1; j < deferredModule.length; j++) {
/******/ 				var depId = deferredModule[j];
/******/ 				if(installedChunks[depId] !== 0) fulfilled = false;
/******/ 			}
/******/ 			if(fulfilled) {
/******/ 				deferredModules.splice(i--, 1);
/******/ 				result = __webpack_require__(__webpack_require__.s = deferredModule[0]);
/******/ 			}
/******/ 		}
/******/
/******/ 		return result;
/******/ 	}
/******/
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// object to store loaded and loading chunks
/******/ 	// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 	// Promise = chunk loading, 0 = chunk loaded
/******/ 	var installedChunks = {
/******/ 		"analytics": 0
/******/ 	};
/******/
/******/ 	var deferredModules = [];
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
/******/ 	var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
/******/ 	jsonpArray.push = webpackJsonpCallback;
/******/ 	jsonpArray = jsonpArray.slice();
/******/ 	for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
/******/ 	var parentJsonpFunction = oldJsonpFunction;
/******/
/******/
/******/ 	// add entry module to deferred list
/******/ 	deferredModules.push(["./assets/wizards/analytics/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/components/src/consts.js":
/*!*****************************************!*\
  !*** ./assets/components/src/consts.js ***!
  \*****************************************/
/*! exports provided: NEWSPACK_SITE_URL, NEWSPACK_SUPPORT_URL */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"NEWSPACK_SITE_URL\", function() { return NEWSPACK_SITE_URL; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"NEWSPACK_SUPPORT_URL\", function() { return NEWSPACK_SUPPORT_URL; });\nvar NEWSPACK_SITE_URL = 'https://newspack.pub';\nvar NEWSPACK_SUPPORT_URL = \"\".concat(NEWSPACK_SITE_URL, \"/support\");\n\n//# sourceURL=webpack:///./assets/components/src/consts.js?");

/***/ }),

/***/ "./assets/wizards/analytics/index.js":
/*!*******************************************!*\
  !*** ./assets/wizards/analytics/index.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./views */ \"./assets/wizards/analytics/views/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/analytics/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_10__);\n\n\n\n\n\n/**\n * Analytics\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__[\"default\"].Switch;\nvar TABS = [{\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Plugins', 'newspack'),\n  path: '/',\n  exact: true\n}, {\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Custom Dimensions', 'newspack'),\n  path: '/custom-dimensions'\n}, {\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Custom Events', 'newspack'),\n  path: '/custom-events'\n}];\n\nvar AnalyticsWizard = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(AnalyticsWizard, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(AnalyticsWizard);\n\n  function AnalyticsWizard() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, AnalyticsWizard);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(AnalyticsWizard, [{\n    key: \"render\",\n\n    /**\n     * Render\n     */\n    value: function render() {\n      var _this$props = this.props,\n          pluginRequirements = _this$props.pluginRequirements,\n          wizardApiFetch = _this$props.wizardApiFetch,\n          isLoading = _this$props.isLoading;\n      var sharedProps = {\n        headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Analytics', 'newspack'),\n        subHeaderText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Track traffic and activity.', 'newspack'),\n        tabbedNavigation: TABS,\n        wizardApiFetch: wizardApiFetch,\n        isLoading: isLoading\n      };\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(HashRouter, {\n        hashType: \"slash\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Route, {\n        path: \"/custom-dimensions\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_9__[\"CustomDimensions\"], sharedProps);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Route, {\n        path: \"/custom-events\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_9__[\"CustomEvents\"], sharedProps);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Route, {\n        path: \"/\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_9__[\"Plugins\"], sharedProps);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Redirect, {\n        to: \"/\"\n      }))));\n    }\n  }]);\n\n  return AnalyticsWizard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"withWizard\"])(AnalyticsWizard, ['google-site-kit'])), document.getElementById('newspack-analytics-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/style.scss":
/*!*********************************************!*\
  !*** ./assets/wizards/analytics/style.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/analytics/style.scss?");

/***/ }),

/***/ "./assets/wizards/analytics/views/custom-dimensions/index.js":
/*!*******************************************************************!*\
  !*** ./assets/wizards/analytics/views/custom-dimensions/index.js ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\nvar SCOPES_OPTIONS = [{\n  value: 'HIT',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Hit', 'newspack')\n}, {\n  value: 'SESSION',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Session', 'newspack')\n}, {\n  value: 'USER',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('User', 'newspack')\n}, {\n  value: 'PRODUCT',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Product', 'newspack')\n}];\n/**\n * Analytics Custom Dimensions screen.\n */\n\nvar CustomDimensions = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(CustomDimensions, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5___default()(CustomDimensions);\n\n  function CustomDimensions() {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, CustomDimensions);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _super.call.apply(_super, [this].concat(args));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"state\", {\n      error: newspack_analytics_wizard_data.analyticsConnectionError,\n      customDimensions: newspack_analytics_wizard_data.customDimensions,\n      newDimensionName: '',\n      newDimensionScope: SCOPES_OPTIONS[0].value\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"handleAPIError\", function (_ref) {\n      var error = _ref.message;\n\n      _this.setState({\n        error: error\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"handleCustomDimensionCreation\", function () {\n      var wizardApiFetch = _this.props.wizardApiFetch;\n      var _this$state = _this.state,\n          customDimensions = _this$state.customDimensions,\n          newDimensionName = _this$state.newDimensionName,\n          newDimensionScope = _this$state.newDimensionScope;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/analytics/custom-dimensions',\n        method: 'POST',\n        data: {\n          name: newDimensionName,\n          scope: newDimensionScope\n        }\n      }).then(function (newCustomDimension) {\n        _this.setState({\n          customDimensions: [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(customDimensions), [newCustomDimension])\n        });\n      }).catch(_this.handleAPIError);\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"handleCustomDimensionSetting\", function (dimensionId) {\n      return function (role) {\n        var wizardApiFetch = _this.props.wizardApiFetch;\n        wizardApiFetch({\n          path: \"/newspack/v1/wizard/analytics/custom-dimensions/\".concat(dimensionId),\n          method: 'POST',\n          data: {\n            role: role\n          }\n        }).then(function (res) {\n          _this.setState({\n            customDimensions: res\n          });\n        }).catch(_this.handleAPIError);\n      };\n    });\n\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(CustomDimensions, [{\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var _this$state2 = this.state,\n          error = _this$state2.error,\n          customDimensions = _this$state2.customDimensions,\n          newDimensionName = _this$state2.newDimensionName,\n          newDimensionScope = _this$state2.newDimensionScope;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"div\", {\n        className: \"newspack__analytics-configuration\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])(\"Custom dimensions are used to collect and analyze data that Google Analytics doesn't automatically track.\", 'newspack')), error ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"Notice\"], {\n        noticeText: error,\n        isError: true,\n        rawHTML: true\n      }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"table\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"thead\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"tr\", null, [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Name', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('ID', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Role', 'newspack')].map(function (colName, i) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"th\", {\n          key: i\n        }, colName);\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"tbody\", null, customDimensions.map(function (dimension) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"tr\", {\n          key: dimension.id\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"strong\", null, dimension.name)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"code\", null, dimension.id)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"SelectControl\"], {\n          options: newspack_analytics_wizard_data.customDimensionsOptions,\n          value: dimension.role || '',\n          onChange: _this2.handleCustomDimensionSetting(dimension.id),\n          className: \"newspack__analytics-configuration__select\"\n        })));\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"p\", {\n        className: \"is-dark\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"strong\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Create a new custom dimension:', 'newspack'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"div\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"div\", {\n        className: \"newspack__analytics-configuration__form\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"TextControl\"], {\n        value: newDimensionName,\n        onChange: function onChange(val) {\n          return _this2.setState({\n            newDimensionName: val\n          });\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Name', 'newspack')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"SelectControl\"], {\n        value: newDimensionScope,\n        onChange: function onChange(val) {\n          return _this2.setState({\n            newDimensionScope: val\n          });\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Scope', 'newspack'),\n        options: SCOPES_OPTIONS\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"Button\"], {\n        onClick: this.handleCustomDimensionCreation,\n        disabled: newDimensionName.length === 0,\n        isPrimary: true\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_8__[\"__\"])('Create', 'newspack'))))));\n    }\n  }]);\n\n  return CustomDimensions;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"withWizardScreen\"])(CustomDimensions));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/custom-dimensions/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/custom-events/index.js":
/*!***************************************************************!*\
  !*** ./assets/wizards/analytics/views/custom-events/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _material_ui_icons_Done__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @material-ui/icons/Done */ \"./node_modules/@material-ui/icons/Done.js\");\n/* harmony import */ var _material_ui_icons_Done__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Done__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _material_ui_icons_Clear__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @material-ui/icons/Clear */ \"./node_modules/@material-ui/icons/Clear.js\");\n/* harmony import */ var _material_ui_icons_Clear__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Clear__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_consts__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ../../../../components/src/consts */ \"./assets/components/src/consts.js\");\n\n\n\n\n\n\n\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * External dependencies\n */\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Not implemented:\n * - visibility, ini-load: require the element to be AMP element,\n * - scroll: requires some more UI for scroll parameters, can be implemented later.\n */\n\nvar TRIGGER_OPTIONS = [{\n  value: 'click',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Click', 'newspack')\n}, {\n  value: 'submit',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Submit', 'newspack')\n}];\nvar NEW_EVENT_TEMPLATE = {\n  event_name: '',\n  event_category: '',\n  event_label: '',\n  on: TRIGGER_OPTIONS[0].value,\n  element: '',\n  amp_element: '',\n  non_interaction: true,\n  is_active: true\n};\n\nvar validateEvent = function validateEvent(event) {\n  return Boolean(event.event_name && event.event_category && event.on && event.element);\n};\n/**\n * Analytics Custom Events screen.\n */\n\n\nvar CustomEvents = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default()(CustomEvents, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6___default()(CustomEvents);\n\n  function CustomEvents() {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default()(this, CustomEvents);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _super.call.apply(_super, [this].concat(args));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"state\", {\n      error: newspack_analytics_wizard_data.analyticsConnectionError,\n      customEvents: newspack_analytics_wizard_data.customEvents,\n      editedEvent: NEW_EVENT_TEMPLATE,\n      editedEventId: null\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"handleAPIError\", function (_ref) {\n      var error = _ref.message;\n      return _this.setState({\n        error: error\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"updateCustomEvents\", function (updatedEvents) {\n      var wizardApiFetch = _this.props.wizardApiFetch;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/analytics/custom-events',\n        method: 'POST',\n        data: {\n          events: updatedEvents\n        }\n      }).then(function (_ref2) {\n        var events = _ref2.events;\n        return _this.setState({\n          customEvents: events,\n          editedEvent: NEW_EVENT_TEMPLATE,\n          editedEventId: null\n        });\n      }).catch(_this.handleAPIError);\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"handleCustomEventEdit\", function () {\n      var _this$state = _this.state,\n          customEvents = _this$state.customEvents,\n          editedEvent = _this$state.editedEvent,\n          editedEventId = _this$state.editedEventId;\n\n      if (editedEventId === 'new') {\n        _this.updateCustomEvents([].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(customEvents), [editedEvent]));\n      } else {\n        _this.updateCustomEvents(customEvents.map(function (event) {\n          if (event.id === editedEventId) {\n            return editedEvent;\n          }\n\n          return event;\n        }));\n      }\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"updateEditedEvent\", function (key) {\n      return function (value) {\n        return _this.setState(function (_ref3) {\n          var editedEvent = _ref3.editedEvent;\n          return {\n            editedEvent: _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()({}, editedEvent), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()({}, key, value))\n          };\n        });\n      };\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"setEditModal\", function (editedEventId) {\n      return function () {\n        var editedEvent = editedEventId !== null && Object(lodash__WEBPACK_IMPORTED_MODULE_9__[\"find\"])(_this.state.customEvents, ['id', editedEventId]);\n\n        _this.setState(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()({\n          editedEventId: editedEventId\n        }, editedEvent ? {\n          editedEvent: editedEvent\n        } : {\n          editedEvent: NEW_EVENT_TEMPLATE\n        }));\n      };\n    });\n\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default()(CustomEvents, [{\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var _this$state2 = this.state,\n          error = _this$state2.error,\n          customEvents = _this$state2.customEvents,\n          editedEvent = _this$state2.editedEvent,\n          editedEventId = _this$state2.editedEventId;\n      var isLoading = this.props.isLoading;\n      var isCreatingEvent = editedEventId === 'new';\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"div\", {\n        className: \"newspack__analytics-configuration\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Custom events are used to collect and analyze specific user interactions.', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Notice\"], {\n        rawHTML: true,\n        isInfo: true,\n        noticeText: \"\".concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('This is an advanced feature, read more about it on our', 'newspack'), \" <a target=\\\"_blank\\\" href=\\\"\").concat(_components_src_consts__WEBPACK_IMPORTED_MODULE_14__[\"NEWSPACK_SUPPORT_URL\"], \"/analytics\\\">\").concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('support page', 'newspack'), \"</a>.\")\n      }), error ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Notice\"], {\n        noticeText: error,\n        isError: true,\n        rawHTML: true\n      }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"table\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"thead\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"tr\", null, [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Active', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Action', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Category', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Label', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Trigger', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Edit', 'newspack')].map(function (colName, i) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"th\", {\n          key: i\n        }, colName);\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"tbody\", null, customEvents.map(function (event) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"tr\", {\n          key: event.id\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, event.is_active ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_material_ui_icons_Done__WEBPACK_IMPORTED_MODULE_10___default.a, null) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_material_ui_icons_Clear__WEBPACK_IMPORTED_MODULE_11___default.a, null)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, event.event_name), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, event.event_category), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, event.event_label), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"code\", null, event.on)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Button\"], {\n          isSmall: true,\n          isLink: true,\n          onClick: _this2.setEditModal(event.id)\n        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Edit', 'newspack'))));\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Button\"], {\n        onClick: this.setEditModal('new'),\n        isPrimary: true\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Create a new custom event', 'newspack')), editedEventId !== null && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Modal\"], {\n        title: isCreatingEvent ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('New custom event', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Editing custom event', 'newspack'),\n        onRequestClose: this.setEditModal(null)\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"div\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"TextControl\"], {\n        disabled: isLoading,\n        value: editedEvent.event_name,\n        onChange: this.updateEditedEvent('event_name'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Action', 'newspack'),\n        required: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"TextControl\"], {\n        disabled: isLoading,\n        value: editedEvent.event_category,\n        onChange: this.updateEditedEvent('event_category'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Category', 'newspack'),\n        required: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"TextControl\"], {\n        disabled: isLoading,\n        value: editedEvent.event_label,\n        onChange: this.updateEditedEvent('event_label'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Label', 'newspack')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"SelectControl\"], {\n        disabled: isLoading,\n        value: editedEvent.on,\n        onChange: this.updateEditedEvent('on'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Trigger', 'newspack'),\n        options: TRIGGER_OPTIONS,\n        required: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"TextControl\"], {\n        disabled: isLoading,\n        value: editedEvent.element,\n        onChange: this.updateEditedEvent('element'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Selector', 'newspack'),\n        className: \"newspack__analytics-configuration__code\",\n        required: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"TextControl\"], {\n        disabled: isLoading,\n        value: editedEvent.amp_element,\n        onChange: this.updateEditedEvent('amp_element'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('AMP Selector', 'newspack'),\n        className: \"newspack__analytics-configuration__code\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"CheckboxControl\"], {\n        disabled: isLoading,\n        checked: editedEvent.non_interaction,\n        onChange: this.updateEditedEvent('non_interaction'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Non-interaction event', 'newspack')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"CheckboxControl\"], {\n        disabled: isLoading,\n        checked: editedEvent.is_active,\n        onChange: this.updateEditedEvent('is_active'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Active', 'newspack')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Button\"], {\n        onClick: this.handleCustomEventEdit,\n        disabled: !validateEvent(editedEvent) || isLoading,\n        isPrimary: true\n      }, isCreatingEvent ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Create', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Update', 'newspack')), !isCreatingEvent && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"Button\"], {\n        disabled: isLoading,\n        onClick: function onClick() {\n          return _this2.updateCustomEvents(_this2.state.customEvents.filter(function (_ref4) {\n            var id = _ref4.id;\n            return editedEvent.id !== id;\n          }));\n        }\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Delete', 'newspack'))))));\n    }\n  }]);\n\n  return CustomEvents;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_13__[\"withWizardScreen\"])(CustomEvents));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/custom-events/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/index.js":
/*!*************************************************!*\
  !*** ./assets/wizards/analytics/views/index.js ***!
  \*************************************************/
/*! exports provided: Plugins, CustomDimensions, CustomEvents */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins */ \"./assets/wizards/analytics/views/plugins/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Plugins\", function() { return _plugins__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/* harmony import */ var _custom_dimensions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./custom-dimensions */ \"./assets/wizards/analytics/views/custom-dimensions/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"CustomDimensions\", function() { return _custom_dimensions__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; });\n\n/* harmony import */ var _custom_events__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./custom-events */ \"./assets/wizards/analytics/views/custom-events/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"CustomEvents\", function() { return _custom_events__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; });\n\n\n\n\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/plugins/index.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/analytics/views/plugins/index.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * Analytics Plugins View\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Analytics Plugins screen.\n */\n\nvar Plugins = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(Plugins, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(Plugins);\n\n  function Plugins() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, Plugins);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(Plugins, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ActionCard\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Google Analytics'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Configure and view site analytics'),\n        actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('View'),\n        handoff: \"google-site-kit\",\n        editLink: newspack_analytics_wizard_data.analyticsConnectionError ? undefined : 'admin.php?page=googlesitekit-module-analytics'\n      }));\n    }\n  }]);\n\n  return Plugins;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"withWizardScreen\"])(Plugins));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/plugins/index.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Clear.js":
/*!**************************************************!*\
  !*** ./node_modules/@material-ui/icons/Clear.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nvar _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ \"./node_modules/@babel/runtime/helpers/interopRequireWildcard.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar React = _interopRequireWildcard(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)( /*#__PURE__*/React.createElement(\"path\", {\n  d: \"M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z\"\n}), 'Clear');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Clear.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Done.js":
/*!*************************************************!*\
  !*** ./node_modules/@material-ui/icons/Done.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nvar _interopRequireWildcard = __webpack_require__(/*! @babel/runtime/helpers/interopRequireWildcard */ \"./node_modules/@babel/runtime/helpers/interopRequireWildcard.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar React = _interopRequireWildcard(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)( /*#__PURE__*/React.createElement(\"path\", {\n  d: \"M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z\"\n}), 'Done');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Done.js?");

/***/ }),

/***/ "@wordpress/api-fetch":
/*!*******************************************!*\
  !*** external {"this":["wp","apiFetch"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"apiFetch\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22apiFetch%22%5D%7D?");

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"components\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22components%22%5D%7D?");

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"element\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22element%22%5D%7D?");

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"i18n\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22i18n%22%5D%7D?");

/***/ }),

/***/ "@wordpress/primitives":
/*!*********************************************!*\
  !*** external {"this":["wp","primitives"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"primitives\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22primitives%22%5D%7D?");

/***/ }),

/***/ "@wordpress/url":
/*!**************************************!*\
  !*** external {"this":["wp","url"]} ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"url\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22url%22%5D%7D?");

/***/ }),

/***/ "lodash":
/*!**********************************!*\
  !*** external {"this":"lodash"} ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"lodash\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%22lodash%22%7D?");

/***/ }),

/***/ "react":
/*!*********************************!*\
  !*** external {"this":"React"} ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"React\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%22React%22%7D?");

/***/ }),

/***/ "react-dom":
/*!************************************!*\
  !*** external {"this":"ReactDOM"} ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"ReactDOM\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%22ReactDOM%22%7D?");

/***/ })

/******/ })));