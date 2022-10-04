/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./newspack-theme/js/src/post-summary/SummaryEditor.js":
/*!*************************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/SummaryEditor.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;\n\nconst decorateSummary = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__.compose)(_utils__WEBPACK_IMPORTED_MODULE_5__.connectWithSelect, (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withDispatch)(dispatch => ({\n  saveSummary: summary => {\n    dispatch('core/editor').editPost({\n      meta: {\n        [_utils__WEBPACK_IMPORTED_MODULE_5__.META_FIELD_SUMMARY]: summary\n      }\n    });\n  }\n})));\n\nconst SummaryEditor = _ref => {\n  let {\n    summary,\n    saveSummary\n  } = _ref;\n  const [value, setValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(summary);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    saveSummary(value);\n  }, [value]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {\n    label: __('Body:', 'newspack'),\n    value: value,\n    onChange: setValue,\n    style: {\n      width: '100%'\n    }\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (decorateSummary(SummaryEditor));\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-summary/SummaryEditor.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/SummaryTitleEditor.js":
/*!******************************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/SummaryTitleEditor.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;\n\nconst decorateTitle = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__.compose)(_utils__WEBPACK_IMPORTED_MODULE_5__.connectWithSelect, (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withDispatch)(dispatch => ({\n  saveSummaryTitle: summaryTitle => {\n    dispatch('core/editor').editPost({\n      meta: {\n        [_utils__WEBPACK_IMPORTED_MODULE_5__.META_FIELD_TITLE]: summaryTitle\n      }\n    });\n  }\n})));\n\nconst SummaryTitleEditor = _ref => {\n  let {\n    summaryTitle,\n    saveSummaryTitle\n  } = _ref;\n  const [value, setValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(summaryTitle);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    saveSummaryTitle(value);\n  }, [value]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: __('Title:', 'newspack'),\n    value: value,\n    onChange: setValue,\n    style: {\n      width: '100%'\n    }\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (decorateTitle(SummaryTitleEditor));\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-summary/SummaryTitleEditor.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/index.js":
/*!*****************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _SummaryEditor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SummaryEditor */ \"./newspack-theme/js/src/post-summary/SummaryEditor.js\");\n/* harmony import */ var _SummaryTitleEditor__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./SummaryTitleEditor */ \"./newspack-theme/js/src/post-summary/SummaryTitleEditor.js\");\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-summary/utils.js\");\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;\n\n\n\n/**\n * Component to be used as a panel in the Document tab of the Editor.\n *\n * https://developer.wordpress.org/block-editor/developers/slotfills/plugin-document-setting-panel/\n */\n\nconst NewspackSummaryPanel = () => {\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__.PluginDocumentSettingPanel, {\n    name: \"newspack-summary\",\n    title: __('Article Summary', 'newspack'),\n    className: \"newspack-summary\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('Write a summary that will be appended to the top of the article content.', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SummaryTitleEditor__WEBPACK_IMPORTED_MODULE_5__[\"default\"], null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SummaryEditor__WEBPACK_IMPORTED_MODULE_4__[\"default\"], null));\n};\n\n(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-document-setting-panel-newspack-summary', {\n  render: (0,_utils__WEBPACK_IMPORTED_MODULE_6__.connectWithSelect)(NewspackSummaryPanel),\n  icon: null\n});\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-summary/index.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-summary/utils.js":
/*!*****************************************************!*\
  !*** ./newspack-theme/js/src/post-summary/utils.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"META_FIELD_SUMMARY\": function() { return /* binding */ META_FIELD_SUMMARY; },\n/* harmony export */   \"META_FIELD_TITLE\": function() { return /* binding */ META_FIELD_TITLE; },\n/* harmony export */   \"connectWithSelect\": function() { return /* binding */ connectWithSelect; }\n/* harmony export */ });\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);\n/**\n * WordPress dependencies\n */\n\nconst META_FIELD_SUMMARY = 'newspack_article_summary';\nconst META_FIELD_TITLE = 'newspack_article_summary_title';\nconst connectWithSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.withSelect)(select => ({\n  summary: select('core/editor').getEditedPostAttribute('meta')[META_FIELD_SUMMARY],\n  summaryTitle: select('core/editor').getEditedPostAttribute('meta')[META_FIELD_TITLE],\n  mode: select('core/edit-post').getEditorMode()\n}));\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-summary/utils.js?");

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["plugins"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./newspack-theme/js/src/post-summary/index.js");
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;