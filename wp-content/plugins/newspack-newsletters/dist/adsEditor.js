(function(e, a) { for(var i in a) e[i] = a[i]; }(window, /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/ads-admin/editor/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/extends.js":
/*!********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/extends.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _extends() {\n  module.exports = _extends = Object.assign || function (target) {\n    for (var i = 1; i < arguments.length; i++) {\n      var source = arguments[i];\n\n      for (var key in source) {\n        if (Object.prototype.hasOwnProperty.call(source, key)) {\n          target[key] = source[key];\n        }\n      }\n    }\n\n    return target;\n  };\n\n  return _extends.apply(this, arguments);\n}\n\nmodule.exports = _extends;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/extends.js?");

/***/ }),

/***/ "./src/ads-admin/editor/index.js":
/*!***************************************!*\
  !*** ./src/ads-admin/editor/index.js ***!
  \***************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/date */ \"@wordpress/date\");\n/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_date__WEBPACK_IMPORTED_MODULE_8__);\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n\n\n\n\nvar AdEdit = function AdEdit(_ref) {\n  var expiryDate = _ref.expiryDate,\n      editPost = _ref.editPost;\n  var noticeProps;\n\n  if (expiryDate) {\n    var formattedExpiryDate = Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_8__[\"format\"])('M j Y', expiryDate);\n    var isExpiryInTheFuture = Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_8__[\"isInTheFuture\"])(expiryDate);\n    noticeProps = {\n      children: isExpiryInTheFuture ? \"\".concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('This ad will expire on ', 'newspack-newsletters'), \" \").concat(formattedExpiryDate, \".\") : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('The expiration date is set in the past. This ad will not be displayed.', 'newspack-newsletters'),\n      status: isExpiryInTheFuture ? 'info' : 'warning'\n    };\n  }\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__[\"PluginDocumentSettingPanel\"], {\n    name: \"newsletters-ads-settings-panel\",\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Expiry date', 'newspack-newsletters')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__[\"DatePicker\"], {\n    currentDate: expiryDate,\n    onChange: function onChange(expiry_date) {\n      return editPost({\n        meta: {\n          expiry_date: expiry_date\n        }\n      });\n    }\n  }), expiryDate ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"div\", {\n    style: {\n      textAlign: 'center'\n    }\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__[\"Button\"], {\n    isSecondary: true,\n    isLink: true,\n    isDestructive: true,\n    onClick: function onClick() {\n      return editPost({\n        meta: {\n          expiry_date: null\n        }\n      });\n    }\n  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Remove expiry date', 'newspack-newsletters'))) : null), noticeProps ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_5__[\"PluginPrePublishPanel\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__[\"Notice\"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({\n    isDismissible: false\n  }, noticeProps))) : null);\n};\n\nvar AdEditWithSelect = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__[\"compose\"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__[\"withSelect\"])(function (select) {\n  var _select = select('core/editor'),\n      getEditedPostAttribute = _select.getEditedPostAttribute;\n\n  var meta = getEditedPostAttribute('meta');\n  return {\n    expiryDate: meta.expiry_date\n  };\n}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__[\"withDispatch\"])(function (dispatch) {\n  var _dispatch = dispatch('core/editor'),\n      editPost = _dispatch.editPost;\n\n  return {\n    editPost: editPost\n  };\n})])(AdEdit);\nObject(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__[\"registerPlugin\"])('newspack-newsletters-sidebar', {\n  render: AdEditWithSelect,\n  icon: null\n});\n\n//# sourceURL=webpack:///./src/ads-admin/editor/index.js?");

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"components\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22components%22%5D%7D?");

/***/ }),

/***/ "@wordpress/compose":
/*!******************************************!*\
  !*** external {"this":["wp","compose"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"compose\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22compose%22%5D%7D?");

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"data\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22data%22%5D%7D?");

/***/ }),

/***/ "@wordpress/date":
/*!***************************************!*\
  !*** external {"this":["wp","date"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"date\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22date%22%5D%7D?");

/***/ }),

/***/ "@wordpress/edit-post":
/*!*******************************************!*\
  !*** external {"this":["wp","editPost"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"editPost\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22editPost%22%5D%7D?");

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

/***/ "@wordpress/plugins":
/*!******************************************!*\
  !*** external {"this":["wp","plugins"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"plugins\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22plugins%22%5D%7D?");

/***/ })

/******/ })));