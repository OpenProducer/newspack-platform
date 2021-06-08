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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/editor/blocks/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _arrayLikeToArray(arr, len) {\n  if (len == null || len > arr.length) len = arr.length;\n\n  for (var i = 0, arr2 = new Array(len); i < len; i++) {\n    arr2[i] = arr[i];\n  }\n\n  return arr2;\n}\n\nmodule.exports = _arrayLikeToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayLikeToArray.js?");

/***/ }),

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
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ \"./node_modules/@babel/runtime/helpers/arrayLikeToArray.js\");\n\nfunction _arrayWithoutHoles(arr) {\n  if (Array.isArray(arr)) return arrayLikeToArray(arr);\n}\n\nmodule.exports = _arrayWithoutHoles;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js?");

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

/***/ "./node_modules/@babel/runtime/helpers/createSuper.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createSuper.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var getPrototypeOf = __webpack_require__(/*! ./getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n\nvar isNativeReflectConstruct = __webpack_require__(/*! ./isNativeReflectConstruct */ \"./node_modules/@babel/runtime/helpers/isNativeReflectConstruct.js\");\n\nvar possibleConstructorReturn = __webpack_require__(/*! ./possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n\nfunction _createSuper(Derived) {\n  return function () {\n    var Super = getPrototypeOf(Derived),\n        result;\n\n    if (isNativeReflectConstruct()) {\n      var NewTarget = getPrototypeOf(this).constructor;\n      result = Reflect.construct(Super, arguments, NewTarget);\n    } else {\n      result = Super.apply(this, arguments);\n    }\n\n    return possibleConstructorReturn(this, result);\n  };\n}\n\nmodule.exports = _createSuper;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/createSuper.js?");

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

/***/ "./node_modules/@babel/runtime/helpers/isNativeReflectConstruct.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/isNativeReflectConstruct.js ***!
  \*************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _isNativeReflectConstruct() {\n  if (typeof Reflect === \"undefined\" || !Reflect.construct) return false;\n  if (Reflect.construct.sham) return false;\n  if (typeof Proxy === \"function\") return true;\n\n  try {\n    Date.prototype.toString.call(Reflect.construct(Date, [], function () {}));\n    return true;\n  } catch (e) {\n    return false;\n  }\n}\n\nmodule.exports = _isNativeReflectConstruct;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/isNativeReflectConstruct.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _iterableToArray(iter) {\n  if (typeof Symbol !== \"undefined\" && Symbol.iterator in Object(iter)) return Array.from(iter);\n}\n\nmodule.exports = _iterableToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/iterableToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _iterableToArrayLimit(arr, i) {\n  if (typeof Symbol === \"undefined\" || !(Symbol.iterator in Object(arr))) return;\n  var _arr = [];\n  var _n = true;\n  var _d = false;\n  var _e = undefined;\n\n  try {\n    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {\n      _arr.push(_s.value);\n\n      if (i && _arr.length === i) break;\n    }\n  } catch (err) {\n    _d = true;\n    _e = err;\n  } finally {\n    try {\n      if (!_n && _i[\"return\"] != null) _i[\"return\"]();\n    } finally {\n      if (_d) throw _e;\n    }\n  }\n\n  return _arr;\n}\n\nmodule.exports = _iterableToArrayLimit;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _nonIterableRest() {\n  throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\nmodule.exports = _nonIterableRest;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/nonIterableRest.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _nonIterableSpread() {\n  throw new TypeError(\"Invalid attempt to spread non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\nmodule.exports = _nonIterableSpread;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/nonIterableSpread.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/objectSpread2.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/objectSpread2.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var defineProperty = __webpack_require__(/*! ./defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n\nfunction ownKeys(object, enumerableOnly) {\n  var keys = Object.keys(object);\n\n  if (Object.getOwnPropertySymbols) {\n    var symbols = Object.getOwnPropertySymbols(object);\n    if (enumerableOnly) symbols = symbols.filter(function (sym) {\n      return Object.getOwnPropertyDescriptor(object, sym).enumerable;\n    });\n    keys.push.apply(keys, symbols);\n  }\n\n  return keys;\n}\n\nfunction _objectSpread2(target) {\n  for (var i = 1; i < arguments.length; i++) {\n    var source = arguments[i] != null ? arguments[i] : {};\n\n    if (i % 2) {\n      ownKeys(Object(source), true).forEach(function (key) {\n        defineProperty(target, key, source[key]);\n      });\n    } else if (Object.getOwnPropertyDescriptors) {\n      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));\n    } else {\n      ownKeys(Object(source)).forEach(function (key) {\n        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));\n      });\n    }\n  }\n\n  return target;\n}\n\nmodule.exports = _objectSpread2;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/objectSpread2.js?");

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

eval("var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ \"./node_modules/@babel/runtime/helpers/arrayWithHoles.js\");\n\nvar iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ \"./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js\");\n\nvar unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ \"./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js\");\n\nvar nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ \"./node_modules/@babel/runtime/helpers/nonIterableRest.js\");\n\nfunction _slicedToArray(arr, i) {\n  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();\n}\n\nmodule.exports = _slicedToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/slicedToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles */ \"./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js\");\n\nvar iterableToArray = __webpack_require__(/*! ./iterableToArray */ \"./node_modules/@babel/runtime/helpers/iterableToArray.js\");\n\nvar unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray */ \"./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js\");\n\nvar nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread */ \"./node_modules/@babel/runtime/helpers/nonIterableSpread.js\");\n\nfunction _toConsumableArray(arr) {\n  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();\n}\n\nmodule.exports = _toConsumableArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/toConsumableArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _typeof(obj) {\n  \"@babel/helpers - typeof\";\n\n  if (typeof Symbol === \"function\" && typeof Symbol.iterator === \"symbol\") {\n    module.exports = _typeof = function _typeof(obj) {\n      return typeof obj;\n    };\n  } else {\n    module.exports = _typeof = function _typeof(obj) {\n      return obj && typeof Symbol === \"function\" && obj.constructor === Symbol && obj !== Symbol.prototype ? \"symbol\" : typeof obj;\n    };\n  }\n\n  return _typeof(obj);\n}\n\nmodule.exports = _typeof;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/typeof.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray */ \"./node_modules/@babel/runtime/helpers/arrayLikeToArray.js\");\n\nfunction _unsupportedIterableToArray(o, minLen) {\n  if (!o) return;\n  if (typeof o === \"string\") return arrayLikeToArray(o, minLen);\n  var n = Object.prototype.toString.call(o).slice(8, -1);\n  if (n === \"Object\" && o.constructor) n = o.constructor.name;\n  if (n === \"Map\" || n === \"Set\") return Array.from(n);\n  if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);\n}\n\nmodule.exports = _unsupportedIterableToArray;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js?");

/***/ }),

/***/ "./src/components/autocomplete-tokenfield/autocomplete-tokenfield.scss":
/*!*****************************************************************************!*\
  !*** ./src/components/autocomplete-tokenfield/autocomplete-tokenfield.scss ***!
  \*****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/components/autocomplete-tokenfield/autocomplete-tokenfield.scss?");

/***/ }),

/***/ "./src/components/autocomplete-tokenfield/index.js":
/*!*********************************************************!*\
  !*** ./src/components/autocomplete-tokenfield/index.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _autocomplete_tokenfield_scss__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./autocomplete-tokenfield.scss */ \"./src/components/autocomplete-tokenfield/autocomplete-tokenfield.scss\");\n/* harmony import */ var _autocomplete_tokenfield_scss__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_autocomplete_tokenfield_scss__WEBPACK_IMPORTED_MODULE_10__);\n\n\n\n\n\n\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * An multi-selecting, api-driven autocomplete input suitable for use in block attributes.\n */\n\nvar AutocompleteTokenField = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default()(AutocompleteTokenField, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_4___default()(AutocompleteTokenField);\n\n  function AutocompleteTokenField(props) {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, AutocompleteTokenField);\n\n    _this = _super.call(this, props);\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"isFetchingInfoOnLoad\", function () {\n      var _this$props = _this.props,\n          tokens = _this$props.tokens,\n          fetchSavedInfo = _this$props.fetchSavedInfo;\n      return Boolean(tokens.length && fetchSavedInfo);\n    });\n\n    _this.state = {\n      suggestions: [],\n      validValues: {},\n      loading: _this.isFetchingInfoOnLoad()\n    };\n    _this.debouncedUpdateSuggestions = Object(lodash__WEBPACK_IMPORTED_MODULE_8__[\"debounce\"])(_this.updateSuggestions, 500);\n    return _this;\n  }\n  /**\n   * If the component has tokens passed in props, it should fetch info after it mounts.\n   */\n\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(AutocompleteTokenField, [{\n    key: \"componentDidMount\",\n\n    /**\n     * When the component loads, fetch information about the tokens so we can populate\n     * the tokens with the correct labels.\n     */\n    value: function componentDidMount() {\n      var _this2 = this;\n\n      if (this.isFetchingInfoOnLoad()) {\n        var _this$props2 = this.props,\n            tokens = _this$props2.tokens,\n            fetchSavedInfo = _this$props2.fetchSavedInfo;\n        fetchSavedInfo(tokens).then(function (results) {\n          var validValues = _this2.state.validValues;\n          results.forEach(function (suggestion) {\n            validValues[suggestion.value] = suggestion.label;\n          });\n\n          _this2.setState({\n            validValues: validValues,\n            loading: false\n          });\n        });\n      }\n    }\n    /**\n     * Clean up any unfinished autocomplete api call requests.\n     */\n\n  }, {\n    key: \"componentWillUnmount\",\n    value: function componentWillUnmount() {\n      delete this.suggestionsRequest;\n      this.debouncedUpdateSuggestions.cancel();\n    }\n    /**\n     * Get a list of labels for input values.\n     *\n     * @param {Array} values Array of values (ids, etc.).\n     * @return {Array} array of valid labels corresponding to the values.\n     */\n\n  }, {\n    key: \"getLabelsForValues\",\n    value: function getLabelsForValues(values) {\n      var validValues = this.state.validValues;\n      return values.reduce(function (accumulator, value) {\n        return validValues[value] ? [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(accumulator), [validValues[value]]) : accumulator;\n      }, []);\n    }\n    /**\n     * Get a list of values for input labels.\n     *\n     * @param {Array} labels Array of labels from the tokens.\n     * @return {Array} Array of valid values corresponding to the labels.\n     */\n\n  }, {\n    key: \"getValuesForLabels\",\n    value: function getValuesForLabels(labels) {\n      var validValues = this.state.validValues;\n      return labels.map(function (label) {\n        return Object.keys(validValues).find(function (key) {\n          return validValues[key] === label;\n        });\n      });\n    }\n    /**\n     * Refresh the autocomplete dropdown.\n     *\n     * @param {string} input Input to fetch suggestions for\n     */\n\n  }, {\n    key: \"updateSuggestions\",\n    value: function updateSuggestions(input) {\n      var _this3 = this;\n\n      var fetchSuggestions = this.props.fetchSuggestions;\n\n      if (!fetchSuggestions) {\n        return;\n      }\n\n      this.setState({\n        loading: true\n      }, function () {\n        var request = fetchSuggestions(input);\n        request.then(function (suggestions) {\n          // A fetch Promise doesn't have an abort option. It's mimicked by\n          // comparing the request reference in on the instance, which is\n          // reset or deleted on subsequent requests or unmounting.\n          if (_this3.suggestionsRequest !== request) {\n            return;\n          }\n\n          var validValues = _this3.state.validValues;\n          var currentSuggestions = [];\n          suggestions.forEach(function (suggestion) {\n            currentSuggestions.push(suggestion.label);\n            validValues[suggestion.value] = suggestion.label;\n          });\n\n          _this3.setState({\n            suggestions: currentSuggestions,\n            validValues: validValues,\n            loading: false\n          });\n        })[\"catch\"](function () {\n          if (_this3.suggestionsRequest === request) {\n            _this3.setState({\n              loading: false\n            });\n          }\n        });\n        _this3.suggestionsRequest = request;\n      });\n    }\n    /**\n     * When a token is selected, we need to convert the string label into a recognized value suitable for saving as an attribute.\n     *\n     * @param {Array} tokenStrings An array of token label strings.\n     */\n\n  }, {\n    key: \"handleOnChange\",\n    value: function handleOnChange(tokenStrings) {\n      var onChange = this.props.onChange;\n      onChange(this.getValuesForLabels(tokenStrings));\n    }\n    /**\n     * To populate the tokens, we need to convert the values into a human-readable label.\n     *\n     * @return {Array} An array of token label strings.\n     */\n\n  }, {\n    key: \"getTokens\",\n    value: function getTokens() {\n      var tokens = this.props.tokens;\n      return this.getLabelsForValues(tokens);\n    }\n    /**\n     * Render.\n     */\n\n  }, {\n    key: \"render\",\n    value: function render() {\n      var _this4 = this;\n\n      var _this$props3 = this.props,\n          help = _this$props3.help,\n          _this$props3$label = _this$props3.label,\n          label = _this$props3$label === void 0 ? '' : _this$props3$label;\n      var _this$state = this.state,\n          suggestions = _this$state.suggestions,\n          loading = _this$state.loading;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"div\", {\n        className: \"autocomplete-tokenfield\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"FormTokenField\"], {\n        value: this.getTokens(),\n        suggestions: suggestions,\n        onChange: function onChange(tokens) {\n          return _this4.handleOnChange(tokens);\n        },\n        onInputChange: function onInputChange(input) {\n          return _this4.debouncedUpdateSuggestions(input);\n        },\n        label: label\n      }), loading && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Spinner\"], null), help && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"createElement\"])(\"p\", {\n        className: \"autocomplete-tokenfield__help\"\n      }, help));\n    }\n  }]);\n\n  return AutocompleteTokenField;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (AutocompleteTokenField);\n\n//# sourceURL=webpack:///./src/components/autocomplete-tokenfield/index.js?");

/***/ }),

/***/ "./src/editor/blocks/index.js":
/*!************************************!*\
  !*** ./src/editor/blocks/index.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _posts_inserter__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./posts-inserter */ \"./src/editor/blocks/posts-inserter/index.js\");\n/**\n * Internal dependencies\n */\n\nObject(_posts_inserter__WEBPACK_IMPORTED_MODULE_0__[\"default\"])();\n\n//# sourceURL=webpack:///./src/editor/blocks/index.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/block.json":
/*!*****************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/block.json ***!
  \*****************************************************/
/*! exports provided: name, category, supports, attributes, default */
/***/ (function(module) {

eval("module.exports = JSON.parse(\"{\\\"name\\\":\\\"newspack-newsletters/posts-inserter\\\",\\\"category\\\":\\\"widgets\\\",\\\"supports\\\":[\\\"align\\\"],\\\"attributes\\\":{\\\"areBlocksInserted\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":false},\\\"postsToShow\\\":{\\\"type\\\":\\\"number\\\",\\\"default\\\":3},\\\"displayPostExcerpt\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":true},\\\"excerptLength\\\":{\\\"type\\\":\\\"number\\\",\\\"default\\\":15},\\\"displayPostDate\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":false},\\\"displayFeaturedImage\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":true},\\\"displayContinueReading\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":false},\\\"innerBlocksToInsert\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]},\\\"featuredImageAlignment\\\":{\\\"type\\\":\\\"string\\\",\\\"default\\\":\\\"left\\\"},\\\"isDisplayingSpecificPosts\\\":{\\\"type\\\":\\\"boolean\\\",\\\"default\\\":false},\\\"specificPosts\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]},\\\"textFontSize\\\":{\\\"type\\\":\\\"number\\\",\\\"default\\\":16},\\\"headingFontSize\\\":{\\\"type\\\":\\\"number\\\",\\\"default\\\":25},\\\"textColor\\\":{\\\"type\\\":\\\"string\\\",\\\"default\\\":\\\"#000\\\"},\\\"headingColor\\\":{\\\"type\\\":\\\"string\\\",\\\"default\\\":\\\"#000\\\"},\\\"tags\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]},\\\"tagExclusions\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]},\\\"categories\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]},\\\"categoryExclusions\\\":{\\\"type\\\":\\\"array\\\",\\\"default\\\":[]}}}\");\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/block.json?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/consts.js":
/*!****************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/consts.js ***!
  \****************************************************/
/*! exports provided: POSTS_INSERTER_BLOCK_NAME, POSTS_INSERTER_STORE_NAME */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"POSTS_INSERTER_BLOCK_NAME\", function() { return POSTS_INSERTER_BLOCK_NAME; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"POSTS_INSERTER_STORE_NAME\", function() { return POSTS_INSERTER_STORE_NAME; });\nvar POSTS_INSERTER_BLOCK_NAME = 'newspack-newsletters/posts-inserter';\nvar POSTS_INSERTER_STORE_NAME = 'newspack-newsletters/posts-inserter-block';\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/consts.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/deduplication.js":
/*!***********************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/deduplication.js ***!
  \***********************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _consts__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./consts */ \"./src/editor/blocks/posts-inserter/consts.js\");\n\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\nvar DEFAULT_STATE = {\n  postIdsByBlocks: {},\n  existingBlockIdsInOrder: [],\n  insertedPostIds: []\n};\nvar actions = {\n  setHandledPostsIds: function setHandledPostsIds(ids, props) {\n    return {\n      type: 'SET_HANDLED_POST_IDS',\n      handledPostIds: ids,\n      props: props\n    };\n  },\n\n  /**\n   * After insertion, save the inserted post ids.\n   *\n   * @param {Array} insertedPostIds post ids\n   */\n  setInsertedPostsIds: function setInsertedPostsIds(insertedPostIds) {\n    return {\n      type: 'SET_INSERTED_POST_IDS',\n      insertedPostIds: insertedPostIds\n    };\n  },\n  removeBlock: function removeBlock(clientId) {\n    return {\n      type: 'REMOVE_BLOCK',\n      clientId: clientId\n    };\n  }\n};\n\nvar getAllPostsInserterBlocksIds = function getAllPostsInserterBlocksIds(blocks) {\n  return Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"flatMap\"])(blocks, function (block) {\n    return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(block.name === _consts__WEBPACK_IMPORTED_MODULE_5__[\"POSTS_INSERTER_BLOCK_NAME\"] ? [block.clientId] : []), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(getAllPostsInserterBlocksIds(block.innerBlocks)));\n  });\n};\n\nObject(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__[\"registerStore\"])(_consts__WEBPACK_IMPORTED_MODULE_5__[\"POSTS_INSERTER_STORE_NAME\"], {\n  reducer: function reducer() {\n    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : DEFAULT_STATE;\n    var action = arguments.length > 1 ? arguments[1] : undefined;\n\n    switch (action.type) {\n      case 'SET_HANDLED_POST_IDS':\n        var _action$props = action.props,\n            clientId = _action$props.clientId,\n            existingBlocks = _action$props.existingBlocks;\n        var existingBlockIdsInOrder = getAllPostsInserterBlocksIds(existingBlocks);\n        return _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default()({}, state, {\n          existingBlockIdsInOrder: existingBlockIdsInOrder,\n          postIdsByBlocks: Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"pick\"])(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default()({}, state.postIdsByBlocks, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, clientId, action.handledPostIds)), existingBlockIdsInOrder)\n        });\n\n      case 'SET_INSERTED_POST_IDS':\n        return _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default()({}, state, {\n          insertedPostIds: Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"uniq\"])([].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(state.insertedPostIds), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(action.insertedPostIds)))\n        });\n\n      case 'REMOVE_BLOCK':\n        return _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default()({}, state, {\n          existingBlockIdsInOrder: Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"without\"])(state.existingBlockIdsInOrder, action.clientId),\n          postIdsByBlocks: Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"omit\"])(state.postIdsByBlocks, [action.clientId])\n        });\n    }\n\n    return state;\n  },\n  actions: actions,\n  selectors: {\n    getHandledPostIds: function getHandledPostIds(_ref, blockClientId) {\n      var postIdsByBlocks = _ref.postIdsByBlocks,\n          existingBlockIdsInOrder = _ref.existingBlockIdsInOrder,\n          insertedPostIds = _ref.insertedPostIds;\n      var blockIndex = existingBlockIdsInOrder.indexOf(blockClientId);\n      var blocksBeforeIds = Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"slice\"])(existingBlockIdsInOrder, 0, blockIndex);\n      return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"uniq\"])(Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"flatten\"])(Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"values\"])(Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"pick\"])(postIdsByBlocks, blocksBeforeIds))))), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(insertedPostIds));\n    }\n  }\n});\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/deduplication.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/icon.js":
/*!**************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/icon.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n\n\n/* From https://material.io/tools/icons */\n\nvar icon = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"SVG\"], {\n  xmlns: \"http://www.w3.org/2000/svg\",\n  width: \"24\",\n  height: \"24\",\n  viewBox: \"0 0 24 24\"\n}, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"Path\"], {\n  d: \"M0 0h24v24H0z\",\n  fill: \"none\"\n}), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__[\"Path\"], {\n  d: \"M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12zM10 9h8v2h-8zm0 3h4v2h-4zm0-6h8v2h-8z\"\n}));\n/* harmony default export */ __webpack_exports__[\"default\"] = (icon);\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/icon.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/index.js":
/*!***************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/index.js ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/block-editor */ \"@wordpress/block-editor\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./style.scss */ \"./src/editor/blocks/posts-inserter/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _deduplication__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./deduplication */ \"./src/editor/blocks/posts-inserter/deduplication.js\");\n/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./block.json */ \"./src/editor/blocks/posts-inserter/block.json\");\nvar _block_json__WEBPACK_IMPORTED_MODULE_13___namespace = /*#__PURE__*/__webpack_require__.t(/*! ./block.json */ \"./src/editor/blocks/posts-inserter/block.json\", 1);\n/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./icon */ \"./src/editor/blocks/posts-inserter/icon.js\");\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./utils */ \"./src/editor/blocks/posts-inserter/utils.js\");\n/* harmony import */ var _query_controls__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./query-controls */ \"./src/editor/blocks/posts-inserter/query-controls.js\");\n/* harmony import */ var _consts__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./consts */ \"./src/editor/blocks/posts-inserter/consts.js\");\n\n\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\n\n\nvar PostsInserterBlock = function PostsInserterBlock(_ref) {\n  var setAttributes = _ref.setAttributes,\n      attributes = _ref.attributes,\n      postList = _ref.postList,\n      replaceBlocks = _ref.replaceBlocks,\n      setHandledPostsIds = _ref.setHandledPostsIds,\n      setInsertedPostsIds = _ref.setInsertedPostsIds,\n      removeBlock = _ref.removeBlock,\n      blockEditorSettings = _ref.blockEditorSettings;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useState\"])(!attributes.displayFeaturedImage),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState, 2),\n      isReady = _useState2[0],\n      setIsReady = _useState2[1];\n\n  var stringifiedPostList = JSON.stringify(postList); // Stringify added to minimize flicker.\n\n  var templateBlocks = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useMemo\"])(function () {\n    return Object(_utils__WEBPACK_IMPORTED_MODULE_15__[\"getTemplateBlocks\"])(postList, attributes);\n  }, [stringifiedPostList, attributes]);\n  var stringifiedTemplateBlocks = JSON.stringify(templateBlocks);\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useEffect\"])(function () {\n    var isDisplayingSpecificPosts = attributes.isDisplayingSpecificPosts,\n        specificPosts = attributes.specificPosts; // No spinner if we're not dealing with images.\n\n    if (!attributes.displayFeaturedImage) {\n      return setIsReady(true);\n    } // No spinner if we're in the middle of selecting a specific post.\n\n\n    if (isDisplayingSpecificPosts && 0 === specificPosts.length) {\n      return setIsReady(true);\n    } // Reset ready state.\n\n\n    setIsReady(false); // If we have a post to show, check for featured image blocks.\n\n    if (0 < postList.length) {\n      // Find all the featured images.\n      var images = [];\n      postList.map(function (post) {\n        return post.featured_media && images.push(post.featured_media);\n      }); // If no posts have featured media, skip loading state.\n\n      if (0 === images.length) {\n        return setIsReady(true);\n      } // Wait for image blocks to be added to the BlockPreview.\n\n\n      var imageBlocks = stringifiedTemplateBlocks.match(/\\\"name\\\":\\\"core\\/image\\\"/g) || []; // Preview is ready once all image blocks are accounted for.\n\n      if (imageBlocks.length === images.length) {\n        setIsReady(true);\n      }\n    }\n  }, [stringifiedPostList, stringifiedTemplateBlocks]);\n  var innerBlocksToInsert = templateBlocks.map(_utils__WEBPACK_IMPORTED_MODULE_15__[\"convertBlockSerializationFormat\"]);\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useEffect\"])(function () {\n    setAttributes({\n      innerBlocksToInsert: innerBlocksToInsert\n    });\n  }, [JSON.stringify(innerBlocksToInsert)]);\n  var handledPostIds = postList.map(function (post) {\n    return post.id;\n  });\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useEffect\"])(function () {\n    if (attributes.areBlocksInserted) {\n      replaceBlocks(templateBlocks);\n      setInsertedPostsIds(handledPostIds);\n    }\n  }, [attributes.areBlocksInserted]);\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useEffect\"])(function () {\n    if (!attributes.preventDeduplication) {\n      setHandledPostsIds(handledPostIds);\n      return removeBlock;\n    }\n  }, [handledPostIds.join()]);\n  var blockControlsImages = [{\n    icon: 'align-none',\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Show image on top', 'newspack-newsletters'),\n    isActive: attributes.featuredImageAlignment === 'top',\n    onClick: function onClick() {\n      return setAttributes({\n        featuredImageAlignment: 'top'\n      });\n    }\n  }, {\n    icon: 'align-pull-left',\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Show image on left', 'newspack-newsletters'),\n    isActive: attributes.featuredImageAlignment === 'left',\n    onClick: function onClick() {\n      return setAttributes({\n        featuredImageAlignment: 'left'\n      });\n    }\n  }, {\n    icon: 'align-pull-right',\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Show image on right', 'newspack-newsletters'),\n    isActive: attributes.featuredImageAlignment === 'right',\n    onClick: function onClick() {\n      return setAttributes({\n        featuredImageAlignment: 'right'\n      });\n    }\n  }];\n  return attributes.areBlocksInserted ? null : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__[\"InspectorControls\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"PanelBody\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Post content settings', 'newspack-newsletters')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Post excerpt', 'newspack-newsletters'),\n    checked: attributes.displayPostExcerpt,\n    onChange: function onChange(value) {\n      return setAttributes({\n        displayPostExcerpt: value\n      });\n    }\n  }), attributes.displayPostExcerpt && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"RangeControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Max number of words in excerpt', 'newspack-newsletters'),\n    value: attributes.excerptLength,\n    onChange: function onChange(value) {\n      return setAttributes({\n        excerptLength: value\n      });\n    },\n    min: 10,\n    max: 100\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Date', 'newspack-newsletters'),\n    checked: attributes.displayPostDate,\n    onChange: function onChange(value) {\n      return setAttributes({\n        displayPostDate: value\n      });\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Featured image', 'newspack-newsletters'),\n    checked: attributes.displayFeaturedImage,\n    onChange: function onChange(value) {\n      return setAttributes({\n        displayFeaturedImage: value\n      });\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])(\"Author's name\", 'newspack-newsletters'),\n    checked: attributes.displayAuthor,\n    onChange: function onChange(value) {\n      return setAttributes({\n        displayAuthor: value\n      });\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('\"Continue reading…\" link', 'newspack-newsletters'),\n    checked: attributes.displayContinueReading,\n    onChange: function onChange(value) {\n      return setAttributes({\n        displayContinueReading: value\n      });\n    }\n  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"PanelBody\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Sorting and filtering', 'newspack-newsletters')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_query_controls__WEBPACK_IMPORTED_MODULE_16__[\"default\"], {\n    attributes: attributes,\n    setAttributes: setAttributes\n  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"PanelBody\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Text style', 'newspack-newsletters')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"FontSizePicker\"], {\n    fontSizes: blockEditorSettings.fontSizes,\n    value: attributes.textFontSize,\n    fallbackFontSize: 16,\n    onChange: function onChange(value) {\n      return setAttributes({\n        textFontSize: isNaN(value) ? null : value\n      });\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ColorPicker\"], {\n    color: attributes.textColor,\n    onChangeComplete: function onChangeComplete(value) {\n      return setAttributes({\n        textColor: value.hex\n      });\n    },\n    disableAlpha: true\n  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"PanelBody\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Heading style', 'newspack-newsletters')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"FontSizePicker\"], {\n    fontSizes: blockEditorSettings.fontSizes,\n    value: attributes.headingFontSize,\n    fallbackFontSize: 25,\n    onChange: function onChange(value) {\n      return setAttributes({\n        headingFontSize: isNaN(value) ? null : value\n      });\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"ColorPicker\"], {\n    color: attributes.headingColor,\n    onChangeComplete: function onChangeComplete(value) {\n      return setAttributes({\n        headingColor: value.hex\n      });\n    },\n    disableAlpha: true\n  }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__[\"BlockControls\"], null, attributes.displayFeaturedImage && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Toolbar\"], {\n    controls: blockControlsImages\n  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", {\n    className: \"newspack-posts-inserter\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", {\n    className: \"newspack-posts-inserter__header\"\n  }, _icon__WEBPACK_IMPORTED_MODULE_14__[\"default\"], Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"span\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Posts Inserter', 'newspack-newsletters'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", {\n    className: \"newspack-posts-inserter__preview\"\n  }, isReady ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__[\"BlockPreview\"], {\n    blocks: templateBlocks,\n    viewportWidth: 558\n  }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Spinner\"], null)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", {\n    className: \"newspack-posts-inserter__footer\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Button\"], {\n    isPrimary: true,\n    onClick: function onClick() {\n      return setAttributes({\n        areBlocksInserted: true\n      });\n    }\n  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Insert posts', 'newspack-newsletters')))));\n};\n\nvar PostsInserterBlockWithSelect = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_8__[\"compose\"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_7__[\"withSelect\"])(function (select, props) {\n  var _props$attributes = props.attributes,\n      postsToShow = _props$attributes.postsToShow,\n      order = _props$attributes.order,\n      orderBy = _props$attributes.orderBy,\n      categories = _props$attributes.categories,\n      isDisplayingSpecificPosts = _props$attributes.isDisplayingSpecificPosts,\n      specificPosts = _props$attributes.specificPosts,\n      preventDeduplication = _props$attributes.preventDeduplication,\n      tags = _props$attributes.tags,\n      tagExclusions = _props$attributes.tagExclusions,\n      categoryExclusions = _props$attributes.categoryExclusions,\n      excerptLength = _props$attributes.excerptLength;\n\n  var _select = select('core'),\n      getEntityRecords = _select.getEntityRecords,\n      getMedia = _select.getMedia;\n\n  var _select2 = select('core/block-editor'),\n      getSelectedBlock = _select2.getSelectedBlock,\n      getBlocks = _select2.getBlocks,\n      getSettings = _select2.getSettings;\n\n  var catIds = categories && categories.length > 0 ? categories.map(function (cat) {\n    return cat.id;\n  }) : [];\n\n  var _select3 = select(_consts__WEBPACK_IMPORTED_MODULE_17__[\"POSTS_INSERTER_STORE_NAME\"]),\n      getHandledPostIds = _select3.getHandledPostIds;\n\n  var exclude = getHandledPostIds(props.clientId);\n  var posts = [];\n  var isHandlingSpecificPosts = isDisplayingSpecificPosts && specificPosts.length > 0;\n\n  if (!isDisplayingSpecificPosts || isHandlingSpecificPosts) {\n    var postListQuery = isDisplayingSpecificPosts ? {\n      include: specificPosts.map(function (post) {\n        return post.id;\n      })\n    } : Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"pickBy\"])({\n      categories: catIds,\n      tags: tags,\n      order: order,\n      orderby: orderBy,\n      per_page: postsToShow,\n      exclude: preventDeduplication ? [] : exclude,\n      categories_exclude: categoryExclusions,\n      tags_exclude: tagExclusions,\n      excerpt_length: excerptLength\n    }, function (value) {\n      return !Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"isUndefined\"])(value);\n    });\n    posts = getEntityRecords('postType', 'post', postListQuery) || [];\n  } // Order posts in the order as they appear in the input\n\n\n  if (isHandlingSpecificPosts) {\n    posts = specificPosts.reduce(function (all, _ref2) {\n      var id = _ref2.id;\n      var found = Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"find\"])(posts, ['id', id]);\n      return found ? [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_1___default()(all), [found]) : all;\n    }, []);\n  }\n\n  return {\n    // Not used by the component, but needed in deduplication.\n    existingBlocks: getBlocks(),\n    blockEditorSettings: getSettings(),\n    selectedBlock: getSelectedBlock(),\n    postList: posts.map(function (post) {\n      if (post.featured_media) {\n        var image = getMedia(post.featured_media);\n        var fallbackImageURL = Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"get\"])(image, 'source_url', null);\n        var featuredImageMediumURL = Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"get\"])(image, ['media_details', 'sizes', 'medium', 'source_url'], null) || fallbackImageURL;\n        var featuredImageLargeURL = Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"get\"])(image, ['media_details', 'sizes', 'large', 'source_url'], null) || fallbackImageURL;\n        return _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()({}, post, {\n          featuredImageMediumURL: featuredImageMediumURL,\n          featuredImageLargeURL: featuredImageLargeURL\n        });\n      }\n\n      return post;\n    })\n  };\n}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_7__[\"withDispatch\"])(function (dispatch, props) {\n  var _dispatch = dispatch('core/block-editor'),\n      _replaceBlocks = _dispatch.replaceBlocks;\n\n  var _dispatch2 = dispatch(_consts__WEBPACK_IMPORTED_MODULE_17__[\"POSTS_INSERTER_STORE_NAME\"]),\n      _setHandledPostsIds = _dispatch2.setHandledPostsIds,\n      setInsertedPostsIds = _dispatch2.setInsertedPostsIds,\n      _removeBlock = _dispatch2.removeBlock;\n\n  return {\n    replaceBlocks: function replaceBlocks(blocks) {\n      _replaceBlocks(props.selectedBlock.clientId, blocks);\n    },\n    setHandledPostsIds: function setHandledPostsIds(ids) {\n      return _setHandledPostsIds(ids, props);\n    },\n    setInsertedPostsIds: setInsertedPostsIds,\n    removeBlock: function removeBlock() {\n      return _removeBlock(props.clientId);\n    }\n  };\n})])(PostsInserterBlock);\n/* harmony default export */ __webpack_exports__[\"default\"] = (function () {\n  Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__[\"registerBlockType\"])(_consts__WEBPACK_IMPORTED_MODULE_17__[\"POSTS_INSERTER_BLOCK_NAME\"], _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()({}, _block_json__WEBPACK_IMPORTED_MODULE_13__, {\n    title: 'Posts Inserter',\n    icon: _icon__WEBPACK_IMPORTED_MODULE_14__[\"default\"],\n    edit: PostsInserterBlockWithSelect,\n    save: function save() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_10__[\"InnerBlocks\"].Content, null);\n    }\n  }));\n});\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/index.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/query-controls.js":
/*!************************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/query-controls.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/url */ \"@wordpress/url\");\n/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/html-entities */ \"@wordpress/html-entities\");\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _components_autocomplete_tokenfield__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../../components/autocomplete-tokenfield */ \"./src/components/autocomplete-tokenfield/index.js\");\n\n\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\nvar fetchPostSuggestions = function fetchPostSuggestions(search) {\n  return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n    path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])('/wp/v2/search', {\n      search: search,\n      per_page: 20,\n      _fields: 'id,title',\n      subtype: 'post'\n    })\n  }).then(function (posts) {\n    return posts.map(function (post) {\n      return {\n        id: post.id,\n        title: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__[\"decodeEntities\"])(post.title) || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('(no title)', 'newspack-newsletters')\n      };\n    });\n  });\n};\n\nvar SEPARATOR = '--';\n\nvar encodePosts = function encodePosts(posts) {\n  return posts.map(function (post) {\n    return [post.id, post.title].join(SEPARATOR);\n  });\n};\n\nvar decodePost = function decodePost(encodedPost) {\n  var match = encodedPost.match(new RegExp(\"^([\\\\d]*)\".concat(SEPARATOR, \"(.*)\")));\n\n  if (match) {\n    return [match[1], match[2]];\n  }\n\n  return encodedPost;\n}; // NOTE: Mostly copied from Gutenberg's Posts Inserter block.\n// https://github.com/WordPress/gutenberg/blob/master/packages/block-library/src/posts-inserter/edit.js\n\n\nvar QueryControlsSettings = function QueryControlsSettings(_ref) {\n  var attributes = _ref.attributes,\n      setAttributes = _ref.setAttributes;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useState\"])([]),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState, 2),\n      categoriesList = _useState2[0],\n      setCategoriesList = _useState2[1];\n\n  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useState\"])(false),\n      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState3, 2),\n      showAdvancedFilters = _useState4[0],\n      setShowAdvancedFilters = _useState4[1];\n\n  var categoryExclusions = attributes.categoryExclusions,\n      tags = attributes.tags,\n      tagExclusions = attributes.tagExclusions;\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useEffect\"])(function () {\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n      path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])(\"/wp/v2/categories\", {\n        per_page: -1\n      })\n    }).then(setCategoriesList);\n  }, []);\n  var categorySuggestions = categoriesList.reduce(function (accumulator, category) {\n    return _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_1___default()({}, accumulator, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, category.name, category));\n  }, {});\n\n  var selectCategories = function selectCategories(tokens) {\n    var hasNoSuggestion = tokens.some(function (token) {\n      return typeof token === 'string' && !categorySuggestions[token];\n    });\n\n    if (hasNoSuggestion) {\n      return;\n    } // Categories that are already will be objects, while new additions will be strings (the name).\n    // allCategories nomalizes the array so that they are all objects.\n\n\n    var allCategories = tokens.map(function (token) {\n      return typeof token === 'string' ? categorySuggestions[token] : token;\n    }); // We do nothing if the category is not selected\n    // from suggestions.\n\n    if (Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"includes\"])(allCategories, null)) {\n      return false;\n    }\n\n    setAttributes({\n      categories: allCategories\n    });\n  };\n\n  var selectTags = function selectTags(tokens) {\n    var validTags = tokens.filter(function (token) {\n      return !!token;\n    });\n    setAttributes({\n      tags: validTags\n    });\n  };\n\n  var selectExcludedTags = function selectExcludedTags(tokens) {\n    var validTags = tokens.filter(function (token) {\n      return !!token;\n    });\n    setAttributes({\n      tagExclusions: validTags\n    });\n  };\n\n  var selectExcludedCategories = function selectExcludedCategories(tokens) {\n    var validCats = tokens.filter(function (token) {\n      return !!token;\n    });\n    setAttributes({\n      categoryExclusions: validCats\n    });\n  };\n\n  var _useState5 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useState\"])(false),\n      _useState6 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState5, 2),\n      isFetchingPosts = _useState6[0],\n      setIsFetchingPosts = _useState6[1];\n\n  var _useState7 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"useState\"])([]),\n      _useState8 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_useState7, 2),\n      foundPosts = _useState8[0],\n      setFoundPosts = _useState8[1];\n\n  var handleSpecificPostsInput = function handleSpecificPostsInput(search) {\n    if (isFetchingPosts || search.length === 0) {\n      return;\n    }\n\n    setIsFetchingPosts(true);\n    fetchPostSuggestions(search).then(function (posts) {\n      setIsFetchingPosts(false);\n      setFoundPosts(posts);\n    });\n  };\n\n  var handleSpecificPostsSelection = function handleSpecificPostsSelection(postTitles) {\n    setAttributes({\n      specificPosts: postTitles.map(function (encodedTitle) {\n        var _decodePost = decodePost(encodedTitle),\n            _decodePost2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_decodePost, 2),\n            id = _decodePost2[0],\n            title = _decodePost2[1];\n\n        return {\n          id: parseInt(id),\n          title: title\n        };\n      })\n    });\n  };\n\n  var fetchCategorySuggestions = function fetchCategorySuggestions(search) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n      path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])('/wp/v2/categories', {\n        search: search,\n        per_page: 20,\n        _fields: 'id,name',\n        orderby: 'count',\n        order: 'desc'\n      })\n    }).then(function (categories) {\n      return categories.map(function (category) {\n        return {\n          value: category.id,\n          label: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__[\"decodeEntities\"])(category.name) || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('(no title)', 'newspack-newsletters')\n        };\n      });\n    });\n  };\n\n  var fetchSavedCategories = function fetchSavedCategories(categoryIDs) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n      path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])('/wp/v2/categories', {\n        per_page: 100,\n        _fields: 'id,name',\n        include: categoryIDs.join(',')\n      })\n    }).then(function (categories) {\n      return categories.map(function (category) {\n        return {\n          value: category.id,\n          label: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__[\"decodeEntities\"])(category.name) || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('(no title)', 'newspack-newsletters')\n        };\n      });\n    });\n  };\n\n  var fetchTagSuggestions = function fetchTagSuggestions(search) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n      path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])('/wp/v2/tags', {\n        search: search,\n        per_page: 20,\n        _fields: 'id,name',\n        orderby: 'count',\n        order: 'desc'\n      })\n    }).then(function (fetchedTags) {\n      return fetchedTags.map(function (tag) {\n        return {\n          value: tag.id,\n          label: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__[\"decodeEntities\"])(tag.name) || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('(no title)', 'newspack-newsletters')\n        };\n      });\n    });\n  };\n\n  var fetchSavedTags = function fetchSavedTags(tagIDs) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_8___default()({\n      path: Object(_wordpress_url__WEBPACK_IMPORTED_MODULE_7__[\"addQueryArgs\"])('/wp/v2/tags', {\n        per_page: 100,\n        _fields: 'id,name',\n        include: tagIDs.join(',')\n      })\n    }).then(function (fetchedTags) {\n      return fetchedTags.map(function (tag) {\n        return {\n          value: tag.id,\n          label: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_9__[\"decodeEntities\"])(tag.name) || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('(no title)', 'newspack-newsletters')\n        };\n      });\n    });\n  };\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", {\n    className: \"newspack-newsletters-query-controls\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"ToggleControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Display specific posts', 'newspack-newsletters'),\n    checked: attributes.isDisplayingSpecificPosts,\n    onChange: function onChange(value) {\n      return setAttributes({\n        isDisplayingSpecificPosts: value\n      });\n    }\n  }), attributes.isDisplayingSpecificPosts ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"FormTokenField\"], {\n    label: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"div\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Add posts', 'newspack-newsletters'), isFetchingPosts && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"Spinner\"], null)),\n    onChange: handleSpecificPostsSelection,\n    value: encodePosts(attributes.specificPosts),\n    suggestions: encodePosts(foundPosts),\n    displayTransform: function displayTransform(string) {\n      var _decodePost3 = decodePost(string),\n          _decodePost4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_decodePost3, 2),\n          id = _decodePost4[0],\n          title = _decodePost4[1];\n\n      return title || id;\n    },\n    onInputChange: Object(lodash__WEBPACK_IMPORTED_MODULE_4__[\"debounce\"])(handleSpecificPostsInput, 400)\n  }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"QueryControls\"], {\n    numberOfItems: attributes.postsToShow,\n    onNumberOfItemsChange: function onNumberOfItemsChange(value) {\n      return setAttributes({\n        postsToShow: value\n      });\n    },\n    categorySuggestions: categorySuggestions,\n    onCategoryChange: selectCategories,\n    selectedCategories: attributes.categories,\n    minItems: 1,\n    maxItems: 20\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(\"p\", {\n    key: \"toggle-advanced-filters\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"Button\"], {\n    isLink: true,\n    onClick: function onClick() {\n      return setShowAdvancedFilters(!showAdvancedFilters);\n    }\n  }, showAdvancedFilters ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Hide Advanced Filters', 'newspack-newsletters') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Show Advanced Filters', 'newspack-newsletters'))), showAdvancedFilters && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_components_autocomplete_tokenfield__WEBPACK_IMPORTED_MODULE_10__[\"default\"], {\n    key: \"tags\",\n    tokens: tags,\n    onChange: selectTags,\n    fetchSuggestions: fetchTagSuggestions,\n    fetchSavedInfo: fetchSavedTags,\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Tags', 'newspack-newsletters')\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_components_autocomplete_tokenfield__WEBPACK_IMPORTED_MODULE_10__[\"default\"], {\n    key: \"category-exclusion\",\n    tokens: categoryExclusions,\n    onChange: selectExcludedCategories,\n    fetchSuggestions: fetchCategorySuggestions,\n    fetchSavedInfo: fetchSavedCategories,\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Excluded Categories', 'newspack-newsletters')\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_components_autocomplete_tokenfield__WEBPACK_IMPORTED_MODULE_10__[\"default\"], {\n    key: \"tag-exclusion\",\n    tokens: tagExclusions,\n    onChange: selectExcludedTags,\n    fetchSuggestions: fetchTagSuggestions,\n    fetchSavedInfo: fetchSavedTags,\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Excluded Tags', 'newspack-newsletters')\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_6__[\"SelectControl\"], {\n    key: \"query-controls-order-select\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Order by'),\n    value: \"\".concat(attributes.orderBy, \"/\").concat(attributes.order),\n    options: [{\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Newest to oldest'),\n      value: 'date/desc'\n    }, {\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Oldest to newest'),\n      value: 'date/asc'\n    }, {\n      /* translators: label for ordering posts by title in ascending order */\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('A → Z'),\n      value: 'title/asc'\n    }, {\n      /* translators: label for ordering posts by title in descending order */\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Z → A'),\n      value: 'title/desc'\n    }],\n    onChange: function onChange(value) {\n      var _value$split = value.split('/'),\n          _value$split2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_2___default()(_value$split, 2),\n          newOrderBy = _value$split2[0],\n          newOrder = _value$split2[1];\n\n      if (newOrder !== attributes.order) {\n        setAttributes({\n          order: newOrder\n        });\n      }\n\n      if (newOrderBy !== attributes.orderBy) {\n        setAttributes({\n          orderBy: newOrderBy\n        });\n      }\n    }\n  }))));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (QueryControlsSettings);\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/query-controls.js?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/style.scss":
/*!*****************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/style.scss ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/style.scss?");

/***/ }),

/***/ "./src/editor/blocks/posts-inserter/utils.js":
/*!***************************************************!*\
  !*** ./src/editor/blocks/posts-inserter/utils.js ***!
  \***************************************************/
/*! exports provided: getTemplateBlocks, convertBlockSerializationFormat, setPreventDeduplicationForPostsInserter */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"getTemplateBlocks\", function() { return getTemplateBlocks; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"convertBlockSerializationFormat\", function() { return convertBlockSerializationFormat; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"setPreventDeduplicationForPostsInserter\", function() { return setPreventDeduplicationForPostsInserter; });\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/date */ \"@wordpress/date\");\n/* harmony import */ var _wordpress_date__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_date__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _consts__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./consts */ \"./src/editor/blocks/posts-inserter/consts.js\");\n\n\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\nvar assignFontSize = function assignFontSize(fontSize, attributes) {\n  if (typeof fontSize === 'number') {\n    attributes.style = _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_2___default()({}, attributes.style || {}, {\n      typography: {\n        fontSize: fontSize\n      }\n    });\n  } else if (typeof fontSize === 'string') {\n    attributes.fontSize = fontSize;\n  }\n\n  return attributes;\n};\n\nvar getHeadingBlockTemplate = function getHeadingBlockTemplate(post, _ref) {\n  var headingFontSize = _ref.headingFontSize,\n      headingColor = _ref.headingColor;\n  return ['core/heading', assignFontSize(headingFontSize, {\n    style: {\n      color: {\n        text: headingColor\n      }\n    },\n    content: \"<a href=\\\"\".concat(post.link, \"\\\">\").concat(post.title.rendered, \"</a>\"),\n    level: 3\n  })];\n};\n\nvar getDateBlockTemplate = function getDateBlockTemplate(post, _ref2) {\n  var textFontSize = _ref2.textFontSize,\n      textColor = _ref2.textColor;\n\n  var dateFormat = Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_6__[\"__experimentalGetSettings\"])().formats.date;\n\n  return ['core/paragraph', assignFontSize(textFontSize, {\n    content: Object(_wordpress_date__WEBPACK_IMPORTED_MODULE_6__[\"dateI18n\"])(dateFormat, post.date_gmt),\n    fontSize: 'normal',\n    style: {\n      color: {\n        text: textColor\n      }\n    }\n  })];\n};\n\nvar getExcerptBlockTemplate = function getExcerptBlockTemplate(post, _ref3) {\n  var excerptLength = _ref3.excerptLength,\n      textFontSize = _ref3.textFontSize,\n      textColor = _ref3.textColor;\n  var excerpt = post.excerpt.rendered;\n  var excerptElement = document.createElement('div');\n  excerptElement.innerHTML = excerpt;\n  excerpt = excerptElement.textContent || excerptElement.innerText || '';\n  var needsEllipsis = excerptLength < excerpt.trim().split(' ').length;\n  var postExcerpt = needsEllipsis ? \"\".concat(excerpt.split(' ', excerptLength).join(' '), \" [\\u2026]\") : excerpt;\n  var attributes = {\n    content: postExcerpt.trim(),\n    style: {\n      color: {\n        text: textColor\n      }\n    }\n  };\n  return ['core/paragraph', assignFontSize(textFontSize, attributes)];\n};\n\nvar getContinueReadingLinkBlockTemplate = function getContinueReadingLinkBlockTemplate(post, _ref4) {\n  var textFontSize = _ref4.textFontSize,\n      textColor = _ref4.textColor;\n  var attributes = {\n    content: \"<a href=\\\"\".concat(post.link, \"\\\">\").concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__[\"__\"])('Continue reading…', 'newspack'), \"</a>\"),\n    style: {\n      color: {\n        text: textColor\n      }\n    }\n  };\n  return ['core/paragraph', assignFontSize(textFontSize, attributes)];\n};\n\nvar getAuthorBlockTemplate = function getAuthorBlockTemplate(post, _ref5) {\n  var textFontSize = _ref5.textFontSize,\n      textColor = _ref5.textColor;\n  var newspack_author_info = post.newspack_author_info;\n\n  if (Array.isArray(newspack_author_info) && newspack_author_info.length) {\n    var authorLinks = newspack_author_info.reduce(function (acc, author, index) {\n      var author_link = author.author_link,\n          display_name = author.display_name;\n\n      if (author_link && display_name) {\n        var comma = newspack_author_info.length > 2 && index < newspack_author_info.length - 1 ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__[\"_x\"])(',', 'comma separator for multiple authors', 'newspack-newsletters') : '';\n        var and = newspack_author_info.length > 1 && index === newspack_author_info.length - 1 ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__[\"__\"])('and ', 'newspack-newsletters') : '';\n        acc.push(\"\".concat(and, \"<a href=\\\"\").concat(author_link, \"\\\">\").concat(display_name, \"</a>\").concat(comma));\n      }\n\n      return acc;\n    }, []);\n    return ['core/heading', assignFontSize(textFontSize, {\n      content: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__[\"__\"])('By ', 'newspack-newsletters') + authorLinks.join(' '),\n      fontSize: 'normal',\n      level: 6,\n      style: {\n        color: {\n          text: textColor\n        }\n      }\n    })];\n  }\n\n  return null;\n};\n\nvar createBlockTemplatesForSinglePost = function createBlockTemplatesForSinglePost(post, attributes) {\n  var postContentBlocks = [getHeadingBlockTemplate(post, attributes)];\n\n  if (attributes.displayAuthor) {\n    var author = getAuthorBlockTemplate(post, attributes);\n\n    if (author) {\n      postContentBlocks.push(author);\n    }\n  }\n\n  if (attributes.displayPostDate && post.date_gmt) {\n    postContentBlocks.push(getDateBlockTemplate(post, attributes));\n  }\n\n  if (attributes.displayPostExcerpt) {\n    postContentBlocks.push(getExcerptBlockTemplate(post, attributes));\n  }\n\n  if (attributes.displayContinueReading) {\n    postContentBlocks.push(getContinueReadingLinkBlockTemplate(post, attributes));\n  }\n\n  var hasFeaturedImage = post.featuredImageLargeURL || post.featuredImageMediumURL;\n\n  if (attributes.displayFeaturedImage && hasFeaturedImage) {\n    var featuredImageId = post.featured_media;\n\n    var getImageBlock = function getImageBlock() {\n      var alignCenter = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;\n      return ['core/image', _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_2___default()({\n        id: featuredImageId,\n        url: alignCenter ? post.featuredImageLargeURL : post.featuredImageMediumURL,\n        href: post.link\n      }, alignCenter ? {\n        align: 'center'\n      } : {})];\n    };\n\n    var imageColumnBlock = ['core/column', {}, [getImageBlock()]];\n    var postContentColumnBlock = ['core/column', {}, postContentBlocks];\n\n    switch (attributes.featuredImageAlignment) {\n      case 'left':\n        return [['core/columns', {}, [imageColumnBlock, postContentColumnBlock]]];\n\n      case 'right':\n        return [['core/columns', {}, [postContentColumnBlock, imageColumnBlock]]];\n\n      case 'top':\n        return [getImageBlock(true)].concat(postContentBlocks);\n    }\n  }\n\n  return postContentBlocks;\n};\n\nvar createBlockFromTemplate = function createBlockFromTemplate(_ref6) {\n  var _ref7 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_1___default()(_ref6, 3),\n      name = _ref7[0],\n      blockAttributes = _ref7[1],\n      _ref7$ = _ref7[2],\n      innerBlocks = _ref7$ === void 0 ? [] : _ref7$;\n\n  return Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__[\"createBlock\"])(name, blockAttributes, innerBlocks.map(createBlockFromTemplate));\n};\n\nvar createBlockTemplatesForPosts = function createBlockTemplatesForPosts(posts, attributes) {\n  return posts.reduce(function (blocks, post) {\n    return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(blocks), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(createBlockTemplatesForSinglePost(post, attributes)));\n  }, []);\n};\n\nvar getTemplateBlocks = function getTemplateBlocks(postList, attributes) {\n  return createBlockTemplatesForPosts(postList, attributes).map(createBlockFromTemplate);\n};\n/**\n * Converts a block object to a shape processable by the backend,\n * which contains block's HTML.\n *\n * @param {Object} block block, as understood by the block editor\n * @return {Object} block with innerHTML, processable by the backend\n */\n\nvar convertBlockSerializationFormat = function convertBlockSerializationFormat(block) {\n  return {\n    attrs: Object(lodash__WEBPACK_IMPORTED_MODULE_3__[\"omit\"])(block.attributes, 'content'),\n    blockName: block.name,\n    innerHTML: Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_5__[\"getBlockContent\"])(block),\n    innerBlocks: block.innerBlocks.map(convertBlockSerializationFormat)\n  };\n}; // In some cases, the Posts Inserter block should not handle deduplication.\n// Previews might be displayed next to each other or next to a post, which results in multiple block lists.\n// The deduplication store relies on the assumption that a post has a single blocks list, which\n// is not true when there are block previews used.\n\nvar setPreventDeduplicationForPostsInserter = function setPreventDeduplicationForPostsInserter(blocks) {\n  return blocks.map(function (block) {\n    if (block.name === _consts__WEBPACK_IMPORTED_MODULE_7__[\"POSTS_INSERTER_BLOCK_NAME\"]) {\n      block.attributes.preventDeduplication = true;\n    }\n\n    if (block.innerBlocks) {\n      block.innerBlocks = setPreventDeduplicationForPostsInserter(block.innerBlocks);\n    }\n\n    return block;\n  });\n};\n\n//# sourceURL=webpack:///./src/editor/blocks/posts-inserter/utils.js?");

/***/ }),

/***/ "@wordpress/api-fetch":
/*!*******************************************!*\
  !*** external {"this":["wp","apiFetch"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"apiFetch\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22apiFetch%22%5D%7D?");

/***/ }),

/***/ "@wordpress/block-editor":
/*!**********************************************!*\
  !*** external {"this":["wp","blockEditor"]} ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"blockEditor\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22blockEditor%22%5D%7D?");

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

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"element\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22element%22%5D%7D?");

/***/ }),

/***/ "@wordpress/html-entities":
/*!***********************************************!*\
  !*** external {"this":["wp","htmlEntities"]} ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"htmlEntities\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22htmlEntities%22%5D%7D?");

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

/***/ })

/******/ })));