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
/******/ 		"dashboard": 0
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
/******/ 	deferredModules.push(["./assets/wizards/dashboard/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/dashboard/index.js":
/*!*******************************************!*\
  !*** ./assets/wizards/dashboard/index.js ***!
  \*******************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _views_dashboardCard__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./views/dashboardCard */ \"./assets/wizards/dashboard/views/dashboardCard.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/dashboard/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_9__);\n\n\n\n\n\n\n\n/* global newspack_dashboard */\n\n/**\n * WordPress dependencies.\n */\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\n/**\n * Newspack Dashboard.\n */\n\nvar Dashboard = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_3___default()(Dashboard, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4___default()(Dashboard);\n\n  function Dashboard() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, Dashboard);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(Dashboard, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      var items = this.props.items;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard__header\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard__header__inner\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"NewspackLogo\"], {\n        centered: true,\n        height: 72\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(\"div\", {\n        className: \"mw6 mr-auto ml-auto\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"GlobalNotices\"], null)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard newspack-wizard__content\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"Grid\"], {\n        columns: 3,\n        gutter: 32\n      }, items.map(function (card) {\n        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_views_dashboardCard__WEBPACK_IMPORTED_MODULE_8__[\"default\"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, card, {\n          key: card.slug\n        }));\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"Footer\"], null));\n    }\n  }]);\n\n  return Dashboard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_5__[\"createElement\"])(Dashboard, {\n  items: newspack_dashboard\n}), document.getElementById('newspack'));\n\n//# sourceURL=webpack:///./assets/wizards/dashboard/index.js?");

/***/ }),

/***/ "./assets/wizards/dashboard/style.scss":
/*!*********************************************!*\
  !*** ./assets/wizards/dashboard/style.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/dashboard/style.scss?");

/***/ }),

/***/ "./assets/wizards/dashboard/views/dashboardCard.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/dashboard/views/dashboardCard.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/index.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ \"./node_modules/classnames/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);\n\n\n\n\n\n\n/**\n * Dashboard Card\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n/**\n * Internal dependencies.\n */\n\n\n/**\n * External dependencies.\n */\n\n\n\nvar DashboardCard = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(DashboardCard, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(DashboardCard);\n\n  function DashboardCard() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, DashboardCard);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(DashboardCard, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      var _this$props = this.props,\n          name = _this$props.name,\n          description = _this$props.description,\n          slug = _this$props.slug,\n          url = _this$props.url;\n      var classes = classnames__WEBPACK_IMPORTED_MODULE_7___default()('newspack-dashboard-card', slug);\n      var iconMap = {\n        'site-design': Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"typography\"]\n        }),\n        'reader-revenue': Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"payment\"]\n        }),\n        advertising: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"stretchWide\"]\n        }),\n        syndication: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"rss\"]\n        }),\n        analytics: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"chartLine\"]\n        }),\n        seo: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"search\"]\n        }),\n        'health-check': Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"lifesaver\"]\n        }),\n        engagement: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"postComments\"]\n        }),\n        popups: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"megaphone\"]\n        }),\n        support: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"help\"]\n        }),\n        updates: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n          icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"reusableBlock\"]\n        })\n      };\n      var contents = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-dashboard-card__contents\"\n      }, iconMap[slug] || Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"Icon\"], {\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_5__[\"plugins\"]\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-dashboard-card__header\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, name), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, description)));\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"Card\"], {\n        className: classes\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n        href: url\n      }, contents));\n    }\n  }]);\n\n  return DashboardCard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (DashboardCard);\n\n//# sourceURL=webpack:///./assets/wizards/dashboard/views/dashboardCard.js?");

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

/***/ "@wordpress/keycodes":
/*!*******************************************!*\
  !*** external {"this":["wp","keycodes"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"keycodes\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22keycodes%22%5D%7D?");

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