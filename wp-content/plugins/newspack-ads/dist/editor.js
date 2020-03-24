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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _arrayWithHoles(arr) {\n  if (Array.isArray(arr)) return arr;\n}\n\nmodule.exports = _arrayWithHoles;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayWithHoles.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _arrayWithoutHoles(arr) {\n  if (Array.isArray(arr)) {\n    for (var i = 0, arr2 = new Array(arr.length); i < arr.length; i++) {\n      arr2[i] = arr[i];\n    }\n\n    return arr2;\n  }\n}\n\nmodule.exports = _arrayWithoutHoles;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/assertThisInitialized.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _assertThisInitialized(self) {\n  if (self === void 0) {\n    throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\");\n  }\n\n  return self;\n}\n\nmodule.exports = _assertThisInitialized;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/assertThisInitialized.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _classCallCheck(instance, Constructor) {\n  if (!(instance instanceof Constructor)) {\n    throw new TypeError(\"Cannot call a class as a function\");\n  }\n}\n\nmodule.exports = _classCallCheck;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/classCallCheck.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _defineProperties(target, props) {\n  for (var i = 0; i < props.length; i++) {\n    var descriptor = props[i];\n    descriptor.enumerable = descriptor.enumerable || false;\n    descriptor.configurable = true;\n    if (\"value\" in descriptor) descriptor.writable = true;\n    Object.defineProperty(target, descriptor.key, descriptor);\n  }\n}\n\nfunction _createClass(Constructor, protoProps, staticProps) {\n  if (protoProps) _defineProperties(Constructor.prototype, protoProps);\n  if (staticProps) _defineProperties(Constructor, staticProps);\n  return Constructor;\n}\n\nmodule.exports = _createClass;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/createClass.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _defineProperty(obj, key, value) {\n  if (key in obj) {\n    Object.defineProperty(obj, key, {\n      value: value,\n      enumerable: true,\n      configurable: true,\n      writable: true\n    });\n  } else {\n    obj[key] = value;\n  }\n\n  return obj;\n}\n\nmodule.exports = _defineProperty;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/defineProperty.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/getPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _getPrototypeOf(o) {\n  module.exports = _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {\n    return o.__proto__ || Object.getPrototypeOf(o);\n  };\n  return _getPrototypeOf(o);\n}\n\nmodule.exports = _getPrototypeOf;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/getPrototypeOf.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/inherits.js":
/*!*********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/inherits.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var setPrototypeOf = __webpack_require__(/*! ./setPrototypeOf */ \"./node_modules/@babel/runtime/helpers/setPrototypeOf.js\");\n\nfunction _inherits(subClass, superClass) {\n  if (typeof superClass !== \"function\" && superClass !== null) {\n    throw new TypeError(\"Super expression must either be null or a function\");\n  }\n\n  subClass.prototype = Object.create(superClass && superClass.prototype, {\n    constructor: {\n      value: subClass,\n      writable: true,\n      configurable: true\n    }\n  });\n  if (superClass) setPrototypeOf(subClass, superClass);\n}\n\nmodule.exports = _inherits;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/inherits.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _iterableToArray(iter) {\n  if (Symbol.iterator in Object(iter) || Object.prototype.toString.call(iter) === \"[object Arguments]\") return Array.from(iter);\n}\n\nmodule.exports = _iterableToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/iterableToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _iterableToArrayLimit(arr, i) {\n  var _arr = [];\n  var _n = true;\n  var _d = false;\n  var _e = undefined;\n\n  try {\n    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {\n      _arr.push(_s.value);\n\n      if (i && _arr.length === i) break;\n    }\n  } catch (err) {\n    _d = true;\n    _e = err;\n  } finally {\n    try {\n      if (!_n && _i[\"return\"] != null) _i[\"return\"]();\n    } finally {\n      if (_d) throw _e;\n    }\n  }\n\n  return _arr;\n}\n\nmodule.exports = _iterableToArrayLimit;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _nonIterableRest() {\n  throw new TypeError(\"Invalid attempt to destructure non-iterable instance\");\n}\n\nmodule.exports = _nonIterableRest;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/nonIterableRest.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _nonIterableSpread() {\n  throw new TypeError(\"Invalid attempt to spread non-iterable instance\");\n}\n\nmodule.exports = _nonIterableSpread;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/nonIterableSpread.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var _typeof = __webpack_require__(/*! ../helpers/typeof */ \"./node_modules/@babel/runtime/helpers/typeof.js\");\n\nvar assertThisInitialized = __webpack_require__(/*! ./assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n\nfunction _possibleConstructorReturn(self, call) {\n  if (call && (_typeof(call) === \"object\" || typeof call === \"function\")) {\n    return call;\n  }\n\n  return assertThisInitialized(self);\n}\n\nmodule.exports = _possibleConstructorReturn;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/setPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _setPrototypeOf(o, p) {\n  module.exports = _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {\n    o.__proto__ = p;\n    return o;\n  };\n\n  return _setPrototypeOf(o, p);\n}\n\nmodule.exports = _setPrototypeOf;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/setPrototypeOf.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ \"./node_modules/@babel/runtime/helpers/arrayWithHoles.js\");\n\nvar iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ \"./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js\");\n\nvar nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ \"./node_modules/@babel/runtime/helpers/nonIterableRest.js\");\n\nfunction _slicedToArray(arr, i) {\n  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || nonIterableRest();\n}\n\nmodule.exports = _slicedToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/slicedToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles */ \"./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js\");\n\nvar iterableToArray = __webpack_require__(/*! ./iterableToArray */ \"./node_modules/@babel/runtime/helpers/iterableToArray.js\");\n\nvar nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread */ \"./node_modules/@babel/runtime/helpers/nonIterableSpread.js\");\n\nfunction _toConsumableArray(arr) {\n  return arrayWithoutHoles(arr) || iterableToArray(arr) || nonIterableSpread();\n}\n\nmodule.exports = _toConsumableArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/toConsumableArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof2(obj) { if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") { _typeof2 = function _typeof2(obj) { return typeof obj; }; } else { _typeof2 = function _typeof2(obj) { return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj; }; } return _typeof2(obj); }\n\nfunction _typeof(obj) {\n  if (typeof Symbol === \"function\" && _typeof2(Symbol.iterator) === \"symbol\") {\n    module.exports = _typeof = function _typeof(obj) {\n      return _typeof2(obj);\n    };\n  } else {\n    module.exports = _typeof = function _typeof(obj) {\n      return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : _typeof2(obj);\n    };\n  }\n\n  return _typeof(obj);\n}\n\nmodule.exports = _typeof;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/typeof.js?");

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!\n  Copyright (c) 2017 Jed Watson.\n  Licensed under the MIT License (MIT), see\n  http://jedwatson.github.io/classnames\n*/\n/* global define */\n\n(function () {\n\t'use strict';\n\n\tvar hasOwn = {}.hasOwnProperty;\n\n\tfunction classNames () {\n\t\tvar classes = [];\n\n\t\tfor (var i = 0; i < arguments.length; i++) {\n\t\t\tvar arg = arguments[i];\n\t\t\tif (!arg) continue;\n\n\t\t\tvar argType = typeof arg;\n\n\t\t\tif (argType === 'string' || argType === 'number') {\n\t\t\t\tclasses.push(arg);\n\t\t\t} else if (Array.isArray(arg) && arg.length) {\n\t\t\t\tvar inner = classNames.apply(null, arg);\n\t\t\t\tif (inner) {\n\t\t\t\t\tclasses.push(inner);\n\t\t\t\t}\n\t\t\t} else if (argType === 'object') {\n\t\t\t\tfor (var key in arg) {\n\t\t\t\t\tif (hasOwn.call(arg, key) && arg[key]) {\n\t\t\t\t\t\tclasses.push(key);\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t}\n\t\t}\n\n\t\treturn classes.join(' ');\n\t}\n\n\tif ( true && module.exports) {\n\t\tclassNames.default = classNames;\n\t\tmodule.exports = classNames;\n\t} else if (true) {\n\t\t// register as 'classnames', consistent with npm package name\n\t\t!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {\n\t\t\treturn classNames;\n\t\t}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),\n\t\t\t\t__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));\n\t} else {}\n}());\n\n\n//# sourceURL=webpack:///./node_modules/classnames/index.js?");

/***/ }),

/***/ "./src/blocks/ad-unit/edit.js":
/*!************************************!*\
  !*** ./src/blocks/ad-unit/edit.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12__);\n\n\n\n\n\n\n\n\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\nvar Edit =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_7___default()(Edit, _Component);\n\n  /**\n   * Constructor.\n   */\n  function Edit() {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default()(this, Edit);\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_4___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_5___default()(Edit).apply(this, arguments));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"adUnitsForSelect\", function (adUnits) {\n      return [{\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Select an ad unit'),\n        value: null\n      }].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(Object.values(adUnits).map(function (adUnit) {\n        return {\n          label: adUnit.name,\n          value: adUnit.id\n        };\n      })));\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"activeAdDataForActiveAd\", function (activeAd) {\n      var adUnits = _this.state.adUnits;\n      var data = adUnits && adUnits.find(function (adUnit) {\n        return parseInt(adUnit.id) === parseInt(activeAd);\n      });\n      return _this.dimensionsFromAd(data);\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_8___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_6___default()(_this), \"dimensionsFromAd\", function (adData) {\n      var _ref = adData || {},\n          sizes = _ref.sizes;\n\n      var primarySize = sizes && sizes.length ? sizes[0] : [320, 240];\n\n      var _primarySize = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(primarySize, 2),\n          width = _primarySize[0],\n          height = _primarySize[1];\n\n      return {\n        width: width,\n        height: height\n      };\n    });\n\n    _this.state = {\n      adUnits: null\n    };\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default()(Edit, [{\n    key: \"componentDidMount\",\n    value: function componentDidMount() {\n      var _this2 = this;\n\n      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_12___default()({\n        path: '/newspack/v1/wizard/advertising'\n      }).then(function (response) {\n        return _this2.setState({\n          adUnits: response.ad_units,\n          initialUpdate: true\n        });\n      });\n    }\n  }, {\n    key: \"componentDidUpdate\",\n    value: function componentDidUpdate(prevProps) {\n      var _this$props = this.props,\n          attributes = _this$props.attributes,\n          noticeOperations = _this$props.noticeOperations;\n      var activeAd = attributes.activeAd;\n\n      if (activeAd !== prevProps.attributes.activeAd) {\n        var _this$activeAdDataFor = this.activeAdDataForActiveAd(activeAd),\n            code = _this$activeAdDataFor.code,\n            width = _this$activeAdDataFor.width,\n            height = _this$activeAdDataFor.height;\n\n        if (code && !width && !height) {\n          noticeOperations.createErrorNotice(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Invalid ad unit code. No dimensions available'));\n        } else {\n          noticeOperations.removeAllNotices();\n        }\n      }\n    }\n  }, {\n    key: \"render\",\n    value: function render() {\n      /**\n       * Constants\n       */\n      var _this$props2 = this.props,\n          attributes = _this$props2.attributes,\n          setAttributes = _this$props2.setAttributes,\n          noticeUI = _this$props2.noticeUI;\n      var activeAd = attributes.activeAd;\n      var adUnits = this.state.adUnits;\n\n      var _this$activeAdDataFor2 = this.activeAdDataForActiveAd(activeAd),\n          width = _this$activeAdDataFor2.width,\n          height = _this$activeAdDataFor2.height;\n\n      var adWidth = width ? {\n        width: \"\".concat(width, \"px\")\n      } : {};\n      var ratio = width && height ? {\n        padding: \"0 0 \".concat(height * 100 / width, \"%\")\n      } : {};\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Fragment\"], null, noticeUI, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"wp-block-newspack-ads-blocks-ad-unit\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"newspack-ads-ad-unit\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__[\"Placeholder\"], {\n        style: adWidth\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"newspack-ads-ad-unit__ratio\",\n        style: ratio\n      }), !adUnits && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__[\"Spinner\"], null), adUnits && !!adUnits.length && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__[\"SelectControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Ad Unit'),\n        value: activeAd,\n        options: this.adUnitsForSelect(adUnits),\n        onChange: function onChange(_activeAd) {\n          return setAttributes({\n            activeAd: _activeAd\n          });\n        }\n      }), adUnits && !adUnits.length && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"components-base-control\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(\"div\", {\n        className: \"components-base-control__field\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('No ad units have been created yet.'), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__[\"ExternalLink\"], {\n        href: \"/wp-admin/admin.php?page=newspack-google-ad-manager-wizard#/\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('You can create ad units in the Ads wizard'))))))));\n    }\n  }]);\n\n  return Edit;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_wordpress_components__WEBPACK_IMPORTED_MODULE_11__[\"withNotices\"])(Edit));\n\n//# sourceURL=webpack:///./src/blocks/ad-unit/edit.js?");

/***/ }),

/***/ "./src/blocks/ad-unit/editor.js":
/*!**************************************!*\
  !*** ./src/blocks/ad-unit/editor.js ***!
  \**************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var ___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! . */ \"./src/blocks/ad-unit/index.js\");\n/**\n * Internal dependencies\n */\n\n\nObject(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__[\"registerBlockType\"])(\"newspack-ads/\".concat(___WEBPACK_IMPORTED_MODULE_1__[\"name\"]), ___WEBPACK_IMPORTED_MODULE_1__[\"settings\"]);\n\n//# sourceURL=webpack:///./src/blocks/ad-unit/editor.js?");

/***/ }),

/***/ "./src/blocks/ad-unit/editor.scss":
/*!****************************************!*\
  !*** ./src/blocks/ad-unit/editor.scss ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/blocks/ad-unit/editor.scss?");

/***/ }),

/***/ "./src/blocks/ad-unit/index.js":
/*!*************************************!*\
  !*** ./src/blocks/ad-unit/index.js ***!
  \*************************************/
/*! exports provided: name, title, icon, settings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"name\", function() { return name; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"title\", function() { return title; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"icon\", function() { return icon; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"settings\", function() { return settings; });\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./edit */ \"./src/blocks/ad-unit/edit.js\");\n/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ \"./src/blocks/ad-unit/editor.scss\");\n/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_editor_scss__WEBPACK_IMPORTED_MODULE_5__);\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Style dependencies - will load in editor\n */\n\n\nvar name = 'ad-unit';\nvar title = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Ad Unit');\n/* From https://material.io/tools/icons */\n\nvar icon = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"SVG\"], {\n  xmlns: \"http://www.w3.org/2000/svg\",\n  width: \"24\",\n  height: \"24\",\n  viewBox: \"0 0 24 24\"\n}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"Path\"], {\n  d: \"M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 9H3V5h9v7z\"\n}));\nvar settings = {\n  title: title,\n  icon: icon,\n  category: Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__[\"getCategories\"])().some(function (_ref) {\n    var slug = _ref.slug;\n    return slug === 'newspack';\n  }) ? 'newspack' : 'common',\n  keywords: [Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('ad'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('advert'), Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('ads')],\n  description: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('A block for displaying ad inventory.', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"ExternalLink\"], {\n    href: \"/wp-admin/admin.php?page=newspack-advertising-wizard#/google_ad_manager\"\n  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Manage ad units', 'newspack'))),\n  attributes: {\n    activeAd: {\n      type: 'string'\n    }\n  },\n  supports: {\n    html: false,\n    align: ['left', 'center', 'right']\n  },\n  edit: _edit__WEBPACK_IMPORTED_MODULE_4__[\"default\"],\n  save: function save() {\n    return null;\n  } // to use view.php\n\n};\n\n//# sourceURL=webpack:///./src/blocks/ad-unit/index.js?");

/***/ }),

/***/ "./src/setup/category.js":
/*!*******************************!*\
  !*** ./src/setup/category.js ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _newspack_logo__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./newspack-logo */ \"./src/setup/newspack-logo.js\");\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * Internal dependencies\n */\n\n\nObject(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__[\"setCategories\"])([].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_2__[\"getCategories\"])().filter(function (_ref) {\n  var slug = _ref.slug;\n  return slug !== 'newspack';\n})), [{\n  slug: 'newspack',\n  title: 'Newspack',\n  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_newspack_logo__WEBPACK_IMPORTED_MODULE_3__[\"default\"], null)\n}]));\n\n//# sourceURL=webpack:///./src/setup/category.js?");

/***/ }),

/***/ "./src/setup/editor.js":
/*!*****************************!*\
  !*** ./src/setup/editor.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _public_path__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./public-path */ \"./src/setup/public-path.js\");\n/* harmony import */ var _public_path__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_public_path__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _category__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./category */ \"./src/setup/category.js\");\n/**\n * Internal dependencies\n */\n\n\n\n//# sourceURL=webpack:///./src/setup/editor.js?");

/***/ }),

/***/ "./src/setup/newspack-logo.js":
/*!************************************!*\
  !*** ./src/setup/newspack-logo.js ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ \"./node_modules/classnames/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);\n\n\n/**\n * External dependencies\n */\n\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (function (_ref) {\n  var _ref$size = _ref.size,\n      size = _ref$size === void 0 ? 24 : _ref$size,\n      className = _ref.className;\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"SVG\"], {\n    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('jetpack-logo', className),\n    width: size,\n    height: size,\n    viewBox: \"0 0 32 32\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"G\"], {\n    stroke: \"none\",\n    strokeWidth: \"1\",\n    fill: \"none\",\n    fillRule: \"evenodd\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"G\"], {\n    fill: \"#2A7DE1\",\n    fillRule: \"nonzero\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"Path\"], {\n    d: \"M16,0 C24.8365823,0 32,7.16345853 32,16.0000911 C32,24.8367237 24.8365823,32 16,32 C7.16341774,32 0,24.8367237 0,16.0000911 C0,7.16345853 7.16341774,0 16,0 Z M9.00970874,8.85436893 L9.00970874,13.6011058 L9.076,13.669 L9.00970874,13.6699029 L9.00970874,23.0938511 L12.4789644,23.0938511 L12.478,17.134 L18.3297326,23.0938511 L22.9902913,23.0938511 L9.00970874,8.85436893 Z M22.9902913,15.4822006 L20.1423948,15.4822006 L21.2545041,16.6213592 L22.9902913,16.6213592 L22.9902913,15.4822006 Z M22.9902913,12.1682848 L16.9320388,12.1682848 L18.0321512,13.3074434 L22.9902913,13.3074434 L22.9902913,12.1682848 Z M22.9902913,8.85436893 L13.6699029,8.85436893 L14.7723242,9.99352751 L22.9902913,9.99352751 L22.9902913,8.85436893 Z\",\n    id: \"Combined-Shape-Copy\",\n    fill: \"#2A7DE1\"\n  }))));\n});\n\n//# sourceURL=webpack:///./src/setup/newspack-logo.js?");

/***/ }),

/***/ "./src/setup/public-path.js":
/*!**********************************!*\
  !*** ./src/setup/public-path.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("/* exported __webpack_public_path__ */\n\n/* global __webpack_public_path__ */\n\n/**\n * Dynamically set WebPack's publicPath so that split assets can be found.\n *\n * @see https://webpack.js.org/guides/public-path/#on-the-fly\n */\nif (typeof window === 'object' && window.Jetpack_Block_Assets_Base_Url) {\n  // eslint-disable-next-line no-global-assign\n  __webpack_require__.p = window.Jetpack_Block_Assets_Base_Url;\n}\n\n//# sourceURL=webpack:///./src/setup/public-path.js?");

/***/ }),

/***/ 0:
/*!***************************************************************!*\
  !*** multi ./src/setup/editor ./src/blocks/ad-unit/editor.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("__webpack_require__(/*! /home/circleci/project/src/setup/editor */\"./src/setup/editor.js\");\nmodule.exports = __webpack_require__(/*! /home/circleci/project/src/blocks/ad-unit/editor.js */\"./src/blocks/ad-unit/editor.js\");\n\n\n//# sourceURL=webpack:///multi_./src/setup/editor_./src/blocks/ad-unit/editor.js?");

/***/ }),

/***/ "@wordpress/api-fetch":
/*!*******************************************!*\
  !*** external {"this":["wp","apiFetch"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"apiFetch\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22apiFetch%22%5D%7D?");

/***/ }),

/***/ "@wordpress/blocks":
/*!*****************************************!*\
  !*** external {"this":["wp","blocks"]} ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"blocks\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22blocks%22%5D%7D?");

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

/***/ })

/******/ })));