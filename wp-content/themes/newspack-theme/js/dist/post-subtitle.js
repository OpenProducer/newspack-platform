/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./newspack-theme/js/src/post-subtitle/SubtitleEditor.js":
/*!***************************************************************!*\
  !*** ./newspack-theme/js/src/post-subtitle/SubtitleEditor.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-subtitle/utils.js\");\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\nconst decorate = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_1__.compose)(_utils__WEBPACK_IMPORTED_MODULE_4__.connectWithSelect, (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withDispatch)(dispatch => ({\n  saveSubtitle: subtitle => {\n    dispatch('core/editor').editPost({\n      meta: {\n        [_utils__WEBPACK_IMPORTED_MODULE_4__.META_FIELD_NAME]: subtitle\n      }\n    });\n  }\n})));\nconst SubtitleEditor = ({\n  subtitle,\n  saveSubtitle\n}) => {\n  const [value, setValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(subtitle);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    saveSubtitle(value);\n  }, [value]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextareaControl, {\n    value: value,\n    onChange: setValue,\n    style: {\n      marginTop: '10px',\n      width: '100%'\n    }\n  });\n};\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (decorate(SubtitleEditor));\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-subtitle/SubtitleEditor.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-subtitle/index.js":
/*!******************************************************!*\
  !*** ./newspack-theme/js/src/post-subtitle/index.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _SubtitleEditor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SubtitleEditor */ \"./newspack-theme/js/src/post-subtitle/SubtitleEditor.js\");\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./utils */ \"./newspack-theme/js/src/post-subtitle/utils.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n\n/**\n * Internal dependencies\n */\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;\n\n\n\n/**\n * Component to be used as a panel in the Document tab of the Editor.\n *\n * https://developer.wordpress.org/block-editor/developers/slotfills/plugin-document-setting-panel/\n */\nconst NewspackSubtitlePanel = ({\n  subtitle,\n  mode\n}) => {\n  // Update the DOM when subtitle value changes or editor mode is switched\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    (0,_utils__WEBPACK_IMPORTED_MODULE_5__.appendSubtitleToTitleDOMElement)(subtitle, mode === 'text');\n  }, [subtitle, mode]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_2__.PluginDocumentSettingPanel, {\n    name: \"newspack-subtitle\",\n    title: __('Article Subtitle', 'newspack'),\n    className: \"newspack-subtitle\"\n  }, __('Set a Subtitle for the Article', 'newspack'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_SubtitleEditor__WEBPACK_IMPORTED_MODULE_4__[\"default\"], null));\n};\n(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__.registerPlugin)('plugin-document-setting-panel-newspack-subtitle', {\n  render: (0,_utils__WEBPACK_IMPORTED_MODULE_5__.connectWithSelect)(NewspackSubtitlePanel),\n  icon: null\n});\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-subtitle/index.js?");

/***/ }),

/***/ "./newspack-theme/js/src/post-subtitle/utils.js":
/*!******************************************************!*\
  !*** ./newspack-theme/js/src/post-subtitle/utils.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   META_FIELD_NAME: () => (/* binding */ META_FIELD_NAME),\n/* harmony export */   appendSubtitleToTitleDOMElement: () => (/* binding */ appendSubtitleToTitleDOMElement),\n/* harmony export */   connectWithSelect: () => (/* binding */ connectWithSelect)\n/* harmony export */ });\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_0__);\n/**\n * WordPress dependencies\n */\n\nconst SUBTITLE_ID = 'newspack-post-subtitle-element';\nconst META_FIELD_NAME = 'newspack_post_subtitle';\n\n/**\n * Appends subtitle to DOM, below the Title in the Editor.\n *\n * @param {string} subtitle Subtitle text\n */\nconst appendSubtitleToTitleDOMElement = (subtitle, isInCodeEditor) => {\n  let titleEl = document.querySelector('.editor-post-title__block'); // Legacy selector\n  if (!titleEl) {\n    titleEl = document.querySelector('.edit-post-visual-editor__post-title-wrapper');\n  }\n  if (titleEl && typeof subtitle === 'string') {\n    let subtitleEl = document.getElementById(SUBTITLE_ID);\n    if (!subtitleEl) {\n      subtitleEl = document.createElement('div');\n      subtitleEl.id = SUBTITLE_ID;\n      // special style for the code (raw text) editor\n      if (isInCodeEditor) {\n        subtitleEl.style.paddingLeft = '14px';\n        subtitleEl.style.marginBottom = '4px';\n      }\n      titleEl.appendChild(subtitleEl);\n    }\n    subtitleEl.innerHTML = subtitle;\n  }\n};\nconst connectWithSelect = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_0__.withSelect)(select => ({\n  subtitle: select('core/editor').getEditedPostAttribute('meta')[META_FIELD_NAME],\n  mode: select('core/edit-post').getEditorMode()\n}));\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-subtitle/utils.js?");

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ ((module) => {

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
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./newspack-theme/js/src/post-subtitle/index.js");
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;