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

/***/ "./newspack-theme/js/src/post-meta-toggles.js":
/*!****************************************************!*\
  !*** ./newspack-theme/js/src/post-meta-toggles.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n\n\n\n\n\n\n\n\n\n\n/**\n * Hide updated date\n */\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__.__;\nconst PostStatusExtensions = _ref => {\n  let {\n    meta,\n    postType,\n    updateMetaValue\n  } = _ref;\n  if (!meta) {\n    return null;\n  }\n  const {\n    newspack_hide_page_title,\n    newspack_hide_updated_date,\n    newspack_show_updated_date,\n    newspack_show_share_buttons\n  } = meta;\n  const {\n    hide_date = [],\n    show_date = [],\n    hide_title = [],\n    show_share_buttons = []\n  } = window.newspack_post_meta_post_types;\n  const hideDate = 0 <= hide_date.indexOf(postType);\n  const showDate = 0 <= show_date.indexOf(postType);\n  const hideTitle = 0 <= hide_title.indexOf(postType);\n  const showShareButtons = 0 <= show_share_buttons.indexOf(postType);\n  if (!hideDate && !showDate && !hideTitle && !showShareButtons) {\n    return null;\n  }\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_4__.PluginPostStatusInfo, {\n    className: \"newspack__post-meta-toggles\"\n  }, hideDate && 'post' === postType && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"label\", {\n    htmlFor: \"hide_updated_date\"\n  }, __('Hide last updated date', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormToggle, {\n    checked: newspack_hide_updated_date,\n    onChange: () => updateMetaValue('newspack_hide_updated_date', !newspack_hide_updated_date),\n    id: \"hide_updated_date\"\n  })), showDate && 'post' === postType && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"label\", {\n    htmlFor: \"show_updated_date\"\n  }, __('Show last updated date', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormToggle, {\n    checked: newspack_show_updated_date,\n    onChange: () => updateMetaValue('newspack_show_updated_date', !newspack_show_updated_date),\n    id: \"show_updated_date\"\n  })), hideTitle && 'page' === postType && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"label\", {\n    htmlFor: \"hide_page_title\"\n  }, __('Hide page title', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormToggle, {\n    checked: newspack_hide_page_title,\n    onChange: () => updateMetaValue('newspack_hide_page_title', !newspack_hide_page_title),\n    id: \"hide_page_title\"\n  })), showShareButtons && 'page' === postType && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"label\", {\n    htmlFor: \"newspack_show_share_buttons\"\n  }, __('Show Jetpack share buttons', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.FormToggle, {\n    checked: newspack_show_share_buttons,\n    onChange: () => updateMetaValue('newspack_show_share_buttons', !newspack_show_share_buttons),\n    id: \"hide_page_title\"\n  })));\n};\n\n/**\n * Map state to props\n */\nconst mapStateToProps = select => {\n  const {\n    getCurrentPostType,\n    getEditedPostAttribute\n  } = select('core/editor');\n  return {\n    meta: getEditedPostAttribute('meta'),\n    postType: getCurrentPostType()\n  };\n};\nconst mapDispatchToProps = dispatch => {\n  const {\n    editPost\n  } = dispatch('core/editor');\n  return {\n    updateMetaValue: (key, value) => editPost({\n      meta: {\n        [key]: value\n      }\n    })\n  };\n};\n\n/**\n * Register plugins\n */\nconst postStatusSidebar = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__.compose)([(0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withSelect)(mapStateToProps), (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.withDispatch)(mapDispatchToProps)])(PostStatusExtensions);\n(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_3__.registerPlugin)('post-status-sidebar', {\n  render: postStatusSidebar\n});\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/post-meta-toggles.js?");

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
/******/ 	var __webpack_exports__ = __webpack_require__("./newspack-theme/js/src/post-meta-toggles.js");
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;