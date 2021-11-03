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
/******/ 	return __webpack_require__(__webpack_require__.s = "./newspack-theme/js/src/post-summary/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./newspack-theme/js/src/post-summary/SummaryEditor.js":
/*!*************************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/SummaryEditor.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\nvar decorateSummary = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__[\"compose\"])(_utils__WEBPACK_IMPORTED_MODULE_7__[\"connectWithSelect\"], Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__[\"withDispatch\"])(function (dispatch) {\n  return {\n    saveSummary: function saveSummary(summary) {\n      dispatch('core/editor').editPost({\n        meta: _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, _utils__WEBPACK_IMPORTED_MODULE_7__[\"META_FIELD_SUMMARY\"], summary)\n      });\n    }\n  };\n}));\n\nvar SummaryEditor = function SummaryEditor(_ref) {\n  var summary = _ref.summary,\n      saveSummary = _ref.saveSummary;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"useState\"])(summary),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      value = _useState2[0],\n      setValue = _useState2[1];\n\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"useEffect\"])(function () {\n    saveSummary(value);\n  }, [value]);\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__[\"TextareaControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Body:', 'newspack'),\n    value: value,\n    onChange: setValue,\n    style: {\n      width: '100%'\n    }\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (decorateSummary(SummaryEditor));\n\n//# sourceURL=webpack:///./newspack-theme/js/src/post-summary/SummaryEditor.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/SummaryTitleEditor.js":
/*!******************************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/SummaryTitleEditor.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\nvar decorateTitle = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__[\"compose\"])(_utils__WEBPACK_IMPORTED_MODULE_7__[\"connectWithSelect\"], Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__[\"withDispatch\"])(function (dispatch) {\n  return {\n    saveSummaryTitle: function saveSummaryTitle(summaryTitle) {\n      dispatch('core/editor').editPost({\n        meta: _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, _utils__WEBPACK_IMPORTED_MODULE_7__[\"META_FIELD_TITLE\"], summaryTitle)\n      });\n    }\n  };\n}));\n\nvar SummaryTitleEditor = function SummaryTitleEditor(_ref) {\n  var summaryTitle = _ref.summaryTitle,\n      saveSummaryTitle = _ref.saveSummaryTitle;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"useState\"])(summaryTitle),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      value = _useState2[0],\n      setValue = _useState2[1];\n\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"useEffect\"])(function () {\n    saveSummaryTitle(value);\n  }, [value]);\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__[\"TextControl\"], {\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Title:', 'newspack'),\n    value: value,\n    onChange: setValue,\n    style: {\n      width: '100%'\n    }\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (decorateTitle(SummaryTitleEditor));\n\n//# sourceURL=webpack:///./newspack-theme/js/src/post-summary/SummaryTitleEditor.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/index.js":
/*!*****************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/index.js ***!
  \*****************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _SummaryEditor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SummaryEditor */ \"./newspack-theme/js/src/post-summary/SummaryEditor.js\");\n/* harmony import */ var _SummaryTitleEditor__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./SummaryTitleEditor */ \"./newspack-theme/js/src/post-summary/SummaryTitleEditor.js\");\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\n\n/**\n * Component to be used as a panel in the Document tab of the Editor.\n *\n * https://developer.wordpress.org/block-editor/developers/slotfills/plugin-document-setting-panel/\n */\n\nvar NewspackSummaryPanel = function NewspackSummaryPanel() {\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__[\"PluginDocumentSettingPanel\"], {\n    name: \"newspack-summary\",\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Article Summary', 'newspack'),\n    className: \"newspack-summary\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Write a summary that will be appended to the top of the article content.', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_SummaryTitleEditor__WEBPACK_IMPORTED_MODULE_5__[\"default\"], null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_SummaryEditor__WEBPACK_IMPORTED_MODULE_4__[\"default\"], null));\n};\n\nObject(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__[\"registerPlugin\"])('plugin-document-setting-panel-newspack-summary', {\n  render: Object(_utils__WEBPACK_IMPORTED_MODULE_6__[\"connectWithSelect\"])(NewspackSummaryPanel),\n  icon: null\n});\n\n//# sourceURL=webpack:///./newspack-theme/js/src/post-summary/index.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/utils.js":
/*!*****************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/utils.js ***!
  \*****************************************************/
/*! exports provided: META_FIELD_SUMMARY, META_FIELD_TITLE, connectWithSelect */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"META_FIELD_SUMMARY\", function() { return META_FIELD_SUMMARY; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"META_FIELD_TITLE\", function() { return META_FIELD_TITLE; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"connectWithSelect\", function() { return connectWithSelect; });\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);\n/**\n * WordPress dependencies\n */\n\nvar META_FIELD_SUMMARY = 'newspack_article_summary';\nvar META_FIELD_TITLE = 'newspack_article_summary_title';\nvar connectWithSelect = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__[\"withSelect\"])(function (select) {\n  return {\n    summary: select('core/editor').getEditedPostAttribute('meta')[META_FIELD_SUMMARY],\n    summaryTitle: select('core/editor').getEditedPostAttribute('meta')[META_FIELD_TITLE],\n    mode: select('core/edit-post').getEditorMode()\n  };\n});\n\n//# sourceURL=webpack:///./newspack-theme/js/src/post-summary/utils.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _arrayLikeToArray(arr, len) {\n  if (len == null || len > arr.length) len = arr.length;\n\n  for (var i = 0, arr2 = new Array(len); i < len; i++) {\n    arr2[i] = arr[i];\n  }\n\n  return arr2;\n}\n\nmodule.exports = _arrayLikeToArray;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayLikeToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _arrayWithHoles(arr) {\n  if (Array.isArray(arr)) return arr;\n}\n\nmodule.exports = _arrayWithHoles;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/arrayWithHoles.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _defineProperty(obj, key, value) {\n  if (key in obj) {\n    Object.defineProperty(obj, key, {\n      value: value,\n      enumerable: true,\n      configurable: true,\n      writable: true\n    });\n  } else {\n    obj[key] = value;\n  }\n\n  return obj;\n}\n\nmodule.exports = _defineProperty;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/defineProperty.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _iterableToArrayLimit(arr, i) {\n  var _i = arr && (typeof Symbol !== \"undefined\" && arr[Symbol.iterator] || arr[\"@@iterator\"]);\n\n  if (_i == null) return;\n  var _arr = [];\n  var _n = true;\n  var _d = false;\n\n  var _s, _e;\n\n  try {\n    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {\n      _arr.push(_s.value);\n\n      if (i && _arr.length === i) break;\n    }\n  } catch (err) {\n    _d = true;\n    _e = err;\n  } finally {\n    try {\n      if (!_n && _i[\"return\"] != null) _i[\"return\"]();\n    } finally {\n      if (_d) throw _e;\n    }\n  }\n\n  return _arr;\n}\n\nmodule.exports = _iterableToArrayLimit;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _nonIterableRest() {\n  throw new TypeError(\"Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.\");\n}\n\nmodule.exports = _nonIterableRest;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/nonIterableRest.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles.js */ \"./node_modules/@babel/runtime/helpers/arrayWithHoles.js\");\n\nvar iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit.js */ \"./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js\");\n\nvar unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray.js */ \"./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js\");\n\nvar nonIterableRest = __webpack_require__(/*! ./nonIterableRest.js */ \"./node_modules/@babel/runtime/helpers/nonIterableRest.js\");\n\nfunction _slicedToArray(arr, i) {\n  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || unsupportedIterableToArray(arr, i) || nonIterableRest();\n}\n\nmodule.exports = _slicedToArray;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/slicedToArray.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ \"./node_modules/@babel/runtime/helpers/arrayLikeToArray.js\");\n\nfunction _unsupportedIterableToArray(o, minLen) {\n  if (!o) return;\n  if (typeof o === \"string\") return arrayLikeToArray(o, minLen);\n  var n = Object.prototype.toString.call(o).slice(8, -1);\n  if (n === \"Object\" && o.constructor) n = o.constructor.name;\n  if (n === \"Map\" || n === \"Set\") return Array.from(o);\n  if (n === \"Arguments\" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);\n}\n\nmodule.exports = _unsupportedIterableToArray;\nmodule.exports[\"default\"] = module.exports, module.exports.__esModule = true;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js?");

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"components\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22components%22%5D?");

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"compose\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22compose%22%5D?");

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"data\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22data%22%5D?");

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"editPost\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22editPost%22%5D?");

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"element\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22element%22%5D?");

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"i18n\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22i18n%22%5D?");

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"plugins\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22plugins%22%5D?");

/***/ })

/******/ })));