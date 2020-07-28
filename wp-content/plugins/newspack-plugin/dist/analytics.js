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
/******/ 			if(installedChunks[chunkId]) {
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

/***/ "./assets/wizards/analytics/index.js":
/*!*******************************************!*\
  !*** ./assets/wizards/analytics/index.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _material_ui_icons_TrendingUp__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @material-ui/icons/TrendingUp */ \"./node_modules/@material-ui/icons/TrendingUp.js\");\n/* harmony import */ var _material_ui_icons_TrendingUp__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_TrendingUp__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./views */ \"./assets/wizards/analytics/views/index.js\");\n\n\n\n\n\n\n/**\n * Analytics\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Material UI dependencies.\n */\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_10__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_10__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_10__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_10__[\"default\"].Switch;\nvar TABS = [{\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Configuration', 'newspack'),\n  path: '/',\n  exact: true\n}, {\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Plugins', 'newspack'),\n  path: '/plugins'\n}];\n\nvar AnalyticsWizard =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(AnalyticsWizard, _Component);\n\n  function AnalyticsWizard() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, AnalyticsWizard);\n\n    return _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default()(AnalyticsWizard).apply(this, arguments));\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(AnalyticsWizard, [{\n    key: \"render\",\n\n    /**\n     * Render\n     */\n    value: function render() {\n      var _this$props = this.props,\n          pluginRequirements = _this$props.pluginRequirements,\n          wizardApiFetch = _this$props.wizardApiFetch;\n      var sharedProps = {\n        headerIcon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_TrendingUp__WEBPACK_IMPORTED_MODULE_8___default.a, null),\n        headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Analytics', 'newspack'),\n        subHeaderText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Track traffic and activity', 'newspack'),\n        tabbedNavigation: TABS,\n        wizardApiFetch: wizardApiFetch\n      };\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(HashRouter, {\n        hashType: \"slash\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(Route, {\n        path: \"/plugins\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_11__[\"Plugins\"], sharedProps);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(Route, {\n        path: \"/\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_11__[\"Configuration\"], sharedProps);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(Redirect, {\n        to: \"/\"\n      }))));\n    }\n  }]);\n\n  return AnalyticsWizard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_9__[\"withWizard\"])(AnalyticsWizard, ['google-site-kit'])), document.getElementById('newspack-analytics-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/configuration/index.js":
/*!***************************************************************!*\
  !*** ./assets/wizards/analytics/views/configuration/index.js ***!
  \***************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread */ \"./node_modules/@babel/runtime/helpers/objectSpread.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/analytics/views/configuration/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_12__);\n\n\n\n\n\n\n\n\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n\nvar SCOPES_OPTIONS = [{\n  value: 'HIT',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Hit', 'newspack')\n}, {\n  value: 'SESSION',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Session', 'newspack')\n}, {\n  value: 'USER',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('User', 'newspack')\n}, {\n  value: 'PRODUCT',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Product', 'newspack')\n}];\n/**\n * Analytics Configuration screen.\n */\n\nvar Configuration =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7___default()(Configuration, _Component);\n\n  function Configuration() {\n    var _getPrototypeOf2;\n\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default()(this, Configuration);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4___default()(this, (_getPrototypeOf2 = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5___default()(Configuration)).call.apply(_getPrototypeOf2, [this].concat(args)));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"state\", {\n      error: newspack_analytics_wizard_data.analyticsConnectionError,\n      customDimensions: newspack_analytics_wizard_data.customDimensions,\n      newDimensionName: '',\n      newDimensionScope: SCOPES_OPTIONS[0].value\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"handleAPIError\", function (_ref) {\n      var error = _ref.message;\n\n      _this.setState({\n        error: error\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"handleCustomDimensionCreation\", function () {\n      var wizardApiFetch = _this.props.wizardApiFetch;\n      var _this$state = _this.state,\n          customDimensions = _this$state.customDimensions,\n          newDimensionName = _this$state.newDimensionName,\n          newDimensionScope = _this$state.newDimensionScope;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/analytics/custom-dimensions',\n        method: 'POST',\n        data: {\n          name: newDimensionName,\n          scope: newDimensionScope\n        }\n      }).then(function (newCustomDimension) {\n        _this.setState({\n          customDimensions: [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(customDimensions), [newCustomDimension])\n        });\n      }).catch(_this.handleAPIError);\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"handleCategoryDimensionSetting\", function (dimensionId) {\n      var wizardApiFetch = _this.props.wizardApiFetch;\n      var customDimensions = _this.state.customDimensions;\n      wizardApiFetch({\n        path: \"/newspack/v1/wizard/analytics/category-dimension/\".concat(dimensionId),\n        method: 'POST'\n      }).then(function (_ref2) {\n        var id = _ref2.id;\n\n        _this.setState({\n          customDimensions: customDimensions.map(function (dimension) {\n            return _babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_0___default()({}, dimension, {\n              is_category_dimension: dimension.id === id\n            });\n          })\n        });\n      }).catch(_this.handleAPIError);\n    });\n\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default()(Configuration, [{\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var _this$state2 = this.state,\n          error = _this$state2.error,\n          customDimensions = _this$state2.customDimensions,\n          newDimensionName = _this$state2.newDimensionName,\n          newDimensionScope = _this$state2.newDimensionScope;\n      var hasCustomDimensions = !error && customDimensions.length !== 0;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"newspack__analytics-configuration newspack-card newspack-card__no-background\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Custom dimensions', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])(\"Custom dimensions are used to collect and analyze data that Google Analytics doesn't automatically track.\", 'newspack')), hasCustomDimensions && customDimensions.filter(function (dimension) {\n        return dimension.is_category_dimension;\n      }).length === 0 && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Please set a category dimension. Otherwise, the categories will not be reported to GA.', 'newspack'),\n        isWarning: true\n      }), error ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"Notice\"], {\n        noticeText: error,\n        isError: true\n      }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"table\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"thead\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"tr\", null, [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Name', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('ID', 'newspack'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Category dimension', 'newspack')].map(function (colName, i) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"th\", {\n          key: i\n        }, colName);\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"tbody\", null, customDimensions.map(function (dimension) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"tr\", {\n          key: dimension.id\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"strong\", null, dimension.name)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"code\", null, dimension.id)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"td\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"CheckboxControl\"], {\n          onChange: function onChange() {\n            return _this2.handleCategoryDimensionSetting(dimension.id);\n          },\n          checked: dimension.is_category_dimension\n        })));\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"p\", {\n        className: \"is-dark\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"strong\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Create a new custom dimension:', 'newspack'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"newspack__analytics-configuration__form\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"TextControl\"], {\n        value: newDimensionName,\n        onChange: function onChange(val) {\n          return _this2.setState({\n            newDimensionName: val\n          });\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Name', 'newspack')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"SelectControl\"], {\n        value: newDimensionScope,\n        onChange: function onChange(val) {\n          return _this2.setState({\n            newDimensionScope: val\n          });\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Scope', 'newspack'),\n        options: SCOPES_OPTIONS\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"Button\"], {\n        onClick: this.handleCustomDimensionCreation,\n        disabled: newDimensionName.length === 0,\n        isPrimary: true\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Create', 'newspack'))))));\n    }\n  }]);\n\n  return Configuration;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"withWizardScreen\"])(Configuration));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/configuration/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/configuration/style.scss":
/*!*****************************************************************!*\
  !*** ./assets/wizards/analytics/views/configuration/style.scss ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/configuration/style.scss?");

/***/ }),

/***/ "./assets/wizards/analytics/views/index.js":
/*!*************************************************!*\
  !*** ./assets/wizards/analytics/views/index.js ***!
  \*************************************************/
/*! exports provided: Plugins, Configuration */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins */ \"./assets/wizards/analytics/views/plugins/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Plugins\", function() { return _plugins__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/* harmony import */ var _configuration__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./configuration */ \"./assets/wizards/analytics/views/configuration/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Configuration\", function() { return _configuration__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; });\n\n\n\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/plugins/index.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/analytics/views/plugins/index.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * Analytics Plugins View\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Analytics Plugins screen.\n */\n\nvar Plugins =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(Plugins, _Component);\n\n  function Plugins() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, Plugins);\n\n    return _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default()(Plugins).apply(this, arguments));\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(Plugins, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"ActionCard\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Google Analytics'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Configure and view site analytics'),\n        actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('View'),\n        handoff: \"google-site-kit\",\n        editLink: newspack_analytics_wizard_data.analyticsConnectionError ? undefined : 'admin.php?page=googlesitekit-module-analytics'\n      }));\n    }\n  }]);\n\n  return Plugins;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"withWizardScreen\"])(Plugins));\n\n//# sourceURL=webpack:///./assets/wizards/analytics/views/plugins/index.js?");

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

/***/ })

/******/ })));