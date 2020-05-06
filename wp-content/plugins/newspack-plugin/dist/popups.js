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
/******/ 		"popups": 0
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
/******/ 	deferredModules.push(["./assets/wizards/popups/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/popups/components/popup-action-card/index.js":
/*!*********************************************************************!*\
  !*** ./assets/wizards/popups/components/popup-action-card/index.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/html-entities */ \"@wordpress/html-entities\");\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _material_ui_icons_FilterList__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @material-ui/icons/FilterList */ \"./node_modules/@material-ui/icons/FilterList.js\");\n/* harmony import */ var _material_ui_icons_FilterList__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_FilterList__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _material_ui_icons_MoreVert__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @material-ui/icons/MoreVert */ \"./node_modules/@material-ui/icons/MoreVert.js\");\n/* harmony import */ var _material_ui_icons_MoreVert__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_MoreVert__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _popup_popover__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../popup-popover */ \"./assets/wizards/popups/components/popup-popover/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/popups/components/popup-action-card/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_14__);\n\n\n\n\n\n\n\n\n/**\n * Popup Action Card\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n\n/**\n * Material UI dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\n\nvar PopupActionCard =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(PopupActionCard, _Component);\n\n  function PopupActionCard() {\n    var _getPrototypeOf2;\n\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, PopupActionCard);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1___default()(this, (_getPrototypeOf2 = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2___default()(PopupActionCard)).call.apply(_getPrototypeOf2, [this].concat(args)));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"state\", {\n      categoriesVisibility: false,\n      popoverVisibility: false\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"render\", function () {\n      var _this$state = _this.state,\n          categoriesVisibility = _this$state.categoriesVisibility,\n          popoverVisibility = _this$state.popoverVisibility;\n      var _this$props = _this.props,\n          className = _this$props.className,\n          description = _this$props.description,\n          deletePopup = _this$props.deletePopup,\n          popup = _this$props.popup,\n          previewPopup = _this$props.previewPopup,\n          setCategoriesForPopup = _this$props.setCategoriesForPopup,\n          setSitewideDefaultPopup = _this$props.setSitewideDefaultPopup,\n          publishPopup = _this$props.publishPopup,\n          updatePopup = _this$props.updatePopup;\n      var id = popup.id,\n          categories = popup.categories,\n          title = popup.title,\n          sitewideDefault = popup.sitewide_default,\n          status = popup.status;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_12__[\"ActionCard\"], {\n        className: className,\n        title: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__[\"decodeEntities\"])(title),\n        key: id,\n        description: description,\n        actionText: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"Fragment\"], null, !sitewideDefault && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Tooltip\"], {\n          text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Category filtering', 'newspack')\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_12__[\"Button\"], {\n          className: \"icon-only\",\n          onClick: function onClick() {\n            return _this.setState({\n              categoriesVisibility: !categoriesVisibility\n            });\n          }\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_FilterList__WEBPACK_IMPORTED_MODULE_10___default.a, null))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"Tooltip\"], {\n          text: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('More options', 'newspack')\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_12__[\"Button\"], {\n          className: \"icon-only\",\n          onClick: function onClick() {\n            return _this.setState({\n              popoverVisibility: !popoverVisibility\n            });\n          }\n        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_MoreVert__WEBPACK_IMPORTED_MODULE_11___default.a, null))), popoverVisibility && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_popup_popover__WEBPACK_IMPORTED_MODULE_13__[\"default\"], {\n          deletePopup: deletePopup,\n          onFocusOutside: function onFocusOutside() {\n            return _this.setState({\n              popoverVisibility: false\n            });\n          },\n          popup: popup,\n          setSitewideDefaultPopup: setSitewideDefaultPopup,\n          updatePopup: updatePopup,\n          previewPopup: previewPopup,\n          publishPopup: 'publish' !== status ? publishPopup : null\n        }))\n      }, categoriesVisibility && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_12__[\"CategoryAutocomplete\"], {\n        value: categories || [],\n        onChange: function onChange(tokens) {\n          return setCategoriesForPopup(id, tokens);\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Category filtering', 'newspack '),\n        disabled: sitewideDefault\n      }));\n    });\n\n    return _this;\n  }\n\n  return PopupActionCard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"Component\"]);\n\nPopupActionCard.defaultProps = {\n  popup: {},\n  deletePopup: function deletePopup() {\n    return null;\n  },\n  setCategoriesForPopup: function setCategoriesForPopup() {\n    return null;\n  },\n  setSitewideDefaultPopup: function setSitewideDefaultPopup() {\n    return null;\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (PopupActionCard);\n\n//# sourceURL=webpack:///./assets/wizards/popups/components/popup-action-card/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/components/popup-action-card/style.scss":
/*!***********************************************************************!*\
  !*** ./assets/wizards/popups/components/popup-action-card/style.scss ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/popups/components/popup-action-card/style.scss?");

/***/ }),

/***/ "./assets/wizards/popups/components/popup-popover/index.js":
/*!*****************************************************************!*\
  !*** ./assets/wizards/popups/components/popup-popover/index.js ***!
  \*****************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/html-entities */ \"@wordpress/html-entities\");\n/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/keycodes */ \"@wordpress/keycodes\");\n/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_keycodes__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _material_ui_icons_Edit__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @material-ui/icons/Edit */ \"./node_modules/@material-ui/icons/Edit.js\");\n/* harmony import */ var _material_ui_icons_Edit__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Edit__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _material_ui_icons_Delete__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @material-ui/icons/Delete */ \"./node_modules/@material-ui/icons/Delete.js\");\n/* harmony import */ var _material_ui_icons_Delete__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Delete__WEBPACK_IMPORTED_MODULE_12__);\n/* harmony import */ var _material_ui_icons_Visibility__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @material-ui/icons/Visibility */ \"./node_modules/@material-ui/icons/Visibility.js\");\n/* harmony import */ var _material_ui_icons_Visibility__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Visibility__WEBPACK_IMPORTED_MODULE_13__);\n/* harmony import */ var _material_ui_icons_Today__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @material-ui/icons/Today */ \"./node_modules/@material-ui/icons/Today.js\");\n/* harmony import */ var _material_ui_icons_Today__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Today__WEBPACK_IMPORTED_MODULE_14__);\n/* harmony import */ var _material_ui_icons_Publish__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! @material-ui/icons/Publish */ \"./node_modules/@material-ui/icons/Publish.js\");\n/* harmony import */ var _material_ui_icons_Publish__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Publish__WEBPACK_IMPORTED_MODULE_15__);\n/* harmony import */ var _material_ui_icons_BugReport__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! @material-ui/icons/BugReport */ \"./node_modules/@material-ui/icons/BugReport.js\");\n/* harmony import */ var _material_ui_icons_BugReport__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_BugReport__WEBPACK_IMPORTED_MODULE_16__);\n/* harmony import */ var _material_ui_icons_Public__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! @material-ui/icons/Public */ \"./node_modules/@material-ui/icons/Public.js\");\n/* harmony import */ var _material_ui_icons_Public__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_Public__WEBPACK_IMPORTED_MODULE_17__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/popups/components/popup-popover/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_19___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_19__);\n\n\n\n\n\n\n\n\n/**\n * Popup Action Card\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n\n\n/**\n * Material UI dependencies.\n */\n\n\n\n\n\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nvar frequencyMap = {\n  never: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Never', 'newspack'),\n  once: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Once', 'newspack'),\n  daily: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Once a day', 'newspack'),\n  always: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Every page', 'newspack')\n};\n\nvar frequenciesForPopup = function frequenciesForPopup(_ref) {\n  var options = _ref.options;\n  var placement = options.placement;\n  return Object.keys(frequencyMap).filter(function (key) {\n    return !('always' === key && 'inline' !== placement);\n  }).map(function (key) {\n    return {\n      label: frequencyMap[key],\n      value: key\n    };\n  });\n};\n\nvar PopupPopover =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(PopupPopover, _Component);\n\n  function PopupPopover() {\n    var _getPrototypeOf2;\n\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, PopupPopover);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_1___default()(this, (_getPrototypeOf2 = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_2___default()(PopupPopover)).call.apply(_getPrototypeOf2, [this].concat(args)));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"render\", function () {\n      var _this$props = _this.props,\n          deletePopup = _this$props.deletePopup,\n          popup = _this$props.popup,\n          previewPopup = _this$props.previewPopup,\n          setSitewideDefaultPopup = _this$props.setSitewideDefaultPopup,\n          onFocusOutside = _this$props.onFocusOutside,\n          publishPopup = _this$props.publishPopup,\n          updatePopup = _this$props.updatePopup;\n      var id = popup.id,\n          sitewideDefault = popup.sitewide_default,\n          editLink = popup.edit_link,\n          options = popup.options;\n      var frequency = options.frequency,\n          placement = options.placement;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_18__[\"Popover\"], {\n        position: \"bottom left\",\n        onFocusOutside: onFocusOutside,\n        onKeyDown: function onKeyDown(event) {\n          return _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_10__[\"ESCAPE\"] === event.keyCode && onFocusOutside();\n        }\n      }, 'inline' !== placement && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        onClick: function onClick() {\n          setSitewideDefaultPopup(id, !sitewideDefault);\n          onFocusOutside();\n        },\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Public__WEBPACK_IMPORTED_MODULE_17___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Sitewide default', 'newspack'), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_18__[\"ToggleControl\"], {\n        className: \"newspack-popup-action-card-popover-control\",\n        checked: sitewideDefault,\n        onChange: function onChange() {\n          return null;\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        onClick: function onClick() {\n          updatePopup(id, {\n            frequency: 'test' === frequency ? 'daily' : 'test'\n          });\n          onFocusOutside();\n        },\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_BugReport__WEBPACK_IMPORTED_MODULE_16___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Test mode', 'newspack'), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_18__[\"ToggleControl\"], {\n        className: \"newspack-popup-action-card-popover-control\",\n        checked: 'test' === frequency,\n        onChange: function onChange() {\n          return null;\n        }\n      })), 'test' !== frequency && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Today__WEBPACK_IMPORTED_MODULE_14___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_18__[\"SelectControl\"], {\n        onChange: function onChange(value) {\n          updatePopup(id, {\n            frequency: value\n          });\n          onFocusOutside();\n        },\n        options: frequenciesForPopup(popup),\n        value: frequency\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        onClick: function onClick() {\n          onFocusOutside();\n          previewPopup(popup);\n        },\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Visibility__WEBPACK_IMPORTED_MODULE_13___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Preview', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        href: Object(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_8__[\"decodeEntities\"])(editLink),\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Edit__WEBPACK_IMPORTED_MODULE_11___default.a, null),\n        className: \"newspack-button\",\n        isLink: true\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Edit', 'newspack')), publishPopup && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        onClick: function onClick() {\n          return publishPopup(id);\n        },\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Publish__WEBPACK_IMPORTED_MODULE_15___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Publish', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_9__[\"MenuItem\"], {\n        onClick: function onClick() {\n          return deletePopup(id);\n        },\n        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"createElement\"])(_material_ui_icons_Delete__WEBPACK_IMPORTED_MODULE_12___default.a, null),\n        className: \"newspack-button\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_7__[\"__\"])('Delete', 'newspack')));\n    });\n\n    return _this;\n  }\n\n  return PopupPopover;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (PopupPopover);\n\n//# sourceURL=webpack:///./assets/wizards/popups/components/popup-popover/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/components/popup-popover/style.scss":
/*!*******************************************************************!*\
  !*** ./assets/wizards/popups/components/popup-popover/style.scss ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/popups/components/popup-popover/style.scss?");

/***/ }),

/***/ "./assets/wizards/popups/index.js":
/*!****************************************!*\
  !*** ./assets/wizards/popups/index.js ***!
  \****************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread */ \"./node_modules/@babel/runtime/helpers/objectSpread.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__);\n/* harmony import */ var _material_ui_icons_NewReleases__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @material-ui/icons/NewReleases */ \"./node_modules/@material-ui/icons/NewReleases.js\");\n/* harmony import */ var _material_ui_icons_NewReleases__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_material_ui_icons_NewReleases__WEBPACK_IMPORTED_MODULE_13__);\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_14__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./views */ \"./assets/wizards/popups/views/index.js\");\n\n\n\n\n\n\n\n\n\n\n\n/**\n * Pop-ups Wizard\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * External dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_16__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_16__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_16__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_16__[\"default\"].Switch;\n\nvar headerText = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Campaigns', 'newspack');\n\nvar subHeaderText = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Reach your readers with configurable campaigns.', 'newspack');\n\nvar tabbedNavigation = [{\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Overlay', 'newpack'),\n  path: '/overlay',\n  exact: true\n}, {\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Inline', 'newpack'),\n  path: '/inline',\n  exact: true\n}];\n\nvar PopupsWizard =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_8___default()(PopupsWizard, _Component);\n\n  function PopupsWizard(props) {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_3___default()(this, PopupsWizard);\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6___default()(PopupsWizard).call(this, props));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"onWizardReady\", function () {\n      _this.getPopups();\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"getPopups\", function () {\n      var _this$props = _this.props,\n          setError = _this$props.setError,\n          wizardApiFetch = _this$props.wizardApiFetch;\n      return wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-popups-wizard/'\n      }).then(function (_ref) {\n        var popups = _ref.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"setSitewideDefaultPopup\", function (popupId, state) {\n      var _this$props2 = _this.props,\n          setError = _this$props2.setError,\n          wizardApiFetch = _this$props2.wizardApiFetch;\n      return wizardApiFetch({\n        path: \"/newspack/v1/wizard/newspack-popups-wizard/sitewide-popup/\".concat(popupId),\n        method: state ? 'POST' : 'DELETE'\n      }).then(function (_ref2) {\n        var popups = _ref2.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"setCategoriesForPopup\", function (popupId, categories) {\n      var _this$props3 = _this.props,\n          setError = _this$props3.setError,\n          wizardApiFetch = _this$props3.wizardApiFetch;\n      return wizardApiFetch({\n        path: \"/newspack/v1/wizard/newspack-popups-wizard/popup-categories/\".concat(popupId),\n        method: 'POST',\n        data: {\n          categories: categories\n        }\n      }).then(function (_ref3) {\n        var popups = _ref3.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"updatePopup\", function (popupId, options) {\n      var _this$props4 = _this.props,\n          setError = _this$props4.setError,\n          wizardApiFetch = _this$props4.wizardApiFetch;\n      return wizardApiFetch({\n        path: \"/newspack/v1/wizard/newspack-popups-wizard/\".concat(popupId),\n        method: 'POST',\n        data: {\n          options: options\n        }\n      }).then(function (_ref4) {\n        var popups = _ref4.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"deletePopup\", function (popupId) {\n      var _this$props5 = _this.props,\n          setError = _this$props5.setError,\n          wizardApiFetch = _this$props5.wizardApiFetch;\n      return wizardApiFetch({\n        path: \"/newspack/v1/wizard/newspack-popups-wizard/\".concat(popupId),\n        method: 'DELETE'\n      }).then(function (_ref5) {\n        var popups = _ref5.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"publishPopup\", function (popupId) {\n      var _this$props6 = _this.props,\n          setError = _this$props6.setError,\n          wizardApiFetch = _this$props6.wizardApiFetch;\n      return wizardApiFetch({\n        path: \"/newspack/v1/wizard/newspack-popups-wizard/\".concat(popupId, \"/publish\"),\n        method: 'POST'\n      }).then(function (_ref6) {\n        var popups = _ref6.popups;\n        return _this.setState({\n          popups: _this.sortPopups(popups)\n        });\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"sortPopups\", function (popups) {\n      var overlay = _this.sortPopupGroup(popups.filter(function (_ref7) {\n        var options = _ref7.options;\n        return 'inline' !== options.placement;\n      }));\n\n      var inline = _this.sortPopupGroup(popups.filter(function (_ref8) {\n        var options = _ref8.options;\n        return 'inline' === options.placement;\n      }));\n\n      return {\n        overlay: overlay,\n        inline: inline\n      };\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"sortPopupGroup\", function (popups) {\n      var test = popups.filter(function (_ref9) {\n        var options = _ref9.options,\n            status = _ref9.status;\n        return 'publish' === status && 'test' === options.frequency;\n      });\n      var draft = popups.filter(function (_ref10) {\n        var status = _ref10.status;\n        return 'draft' === status;\n      });\n      var active = popups.filter(function (_ref11) {\n        var categories = _ref11.categories,\n            options = _ref11.options,\n            sitewideDefault = _ref11.sitewide_default,\n            status = _ref11.status;\n        return 'inline' === options.placement ? 'test' !== options.frequency && 'never' !== options.frequency && 'publish' === status : 'test' !== options.frequency && (sitewideDefault || categories.length) && 'publish' === status;\n      });\n      var activeWithSitewideDefaultFirst = [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(active.filter(function (_ref12) {\n        var sitewideDefault = _ref12.sitewide_default;\n        return sitewideDefault;\n      })), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_2___default()(active.filter(function (_ref13) {\n        var sitewideDefault = _ref13.sitewide_default;\n        return !sitewideDefault;\n      })));\n      var inactive = popups.filter(function (_ref14) {\n        var categories = _ref14.categories,\n            options = _ref14.options,\n            sitewideDefault = _ref14.sitewide_default,\n            status = _ref14.status;\n        return 'inline' === options.placement ? 'never' === options.frequency && 'publish' === status : 'test' !== options.frequency && !sitewideDefault && !categories.length && 'publish' === status;\n      });\n      return {\n        draft: draft,\n        test: test,\n        active: activeWithSitewideDefaultFirst,\n        inactive: inactive\n      };\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_9___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_7___default()(_this), \"previewUrlForPopup\", function (_ref15) {\n      var options = _ref15.options,\n          id = _ref15.id;\n      var placement = options.placement,\n          triggerType = options.trigger_type;\n      var previewURL = 'inline' === placement || 'scroll' === triggerType ? window && window.newspack_popups_wizard_data && window.newspack_popups_wizard_data.preview_post : '/';\n      return \"\".concat(previewURL, \"?\").concat(Object(qs__WEBPACK_IMPORTED_MODULE_14__[\"stringify\"])(_babel_runtime_helpers_objectSpread__WEBPACK_IMPORTED_MODULE_1___default()({}, options, {\n        newspack_popups_preview_id: id\n      })));\n    });\n\n    _this.state = {\n      popups: {\n        inline: [],\n        overlay: []\n      },\n      previewUrl: null\n    };\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_4___default()(PopupsWizard, [{\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var pluginRequirements = this.props.pluginRequirements;\n      var _this$state = this.state,\n          popups = _this$state.popups,\n          previewUrl = _this$state.previewUrl;\n      var inline = popups.inline,\n          overlay = popups.overlay;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_15__[\"WebPreview\"], {\n        url: previewUrl,\n        renderButton: function renderButton(_ref16) {\n          var showPreview = _ref16.showPreview;\n          var sharedProps = {\n            headerIcon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(_material_ui_icons_NewReleases__WEBPACK_IMPORTED_MODULE_13___default.a, null),\n            headerText: headerText,\n            subHeaderText: subHeaderText,\n            tabbedNavigation: tabbedNavigation,\n            setSitewideDefaultPopup: _this2.setSitewideDefaultPopup,\n            setCategoriesForPopup: _this2.setCategoriesForPopup,\n            updatePopup: _this2.updatePopup,\n            deletePopup: _this2.deletePopup,\n            previewPopup: function previewPopup(popup) {\n              return _this2.setState({\n                previewUrl: _this2.previewUrlForPopup(popup)\n              }, function () {\n                return showPreview();\n              });\n            },\n            publishPopup: _this2.publishPopup\n          };\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(HashRouter, {\n            hashType: \"slash\"\n          }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(Route, {\n            path: \"/overlay\",\n            render: function render() {\n              return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_17__[\"PopupGroup\"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, sharedProps, {\n                items: overlay,\n                buttonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Add new Overlay Campaign', 'newspack'),\n                buttonAction: \"/wp-admin/post-new.php?post_type=newspack_popups_cpt\",\n                emptyMessage: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('No Overlay Campaigns have been created yet.', 'newspack')\n              }));\n            }\n          }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(Route, {\n            path: \"/inline\",\n            render: function render() {\n              return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_17__[\"PopupGroup\"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, sharedProps, {\n                items: inline,\n                buttonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('Add new Inline Campaign', 'newspack'),\n                buttonAction: \"/wp-admin/post-new.php?post_type=newspack_popups_cpt&placement=inline\",\n                emptyMessage: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__[\"__\"])('No Inline Campaigns have been created yet.', 'newspack')\n              }));\n            }\n          }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(Redirect, {\n            to: \"/overlay\"\n          })));\n        }\n      });\n    }\n  }]);\n\n  return PopupsWizard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_11__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_15__[\"withWizard\"])(PopupsWizard, ['jetpack', 'newspack-popups'])), document.getElementById('newspack-popups-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/popups/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/views/analytics/index.js":
/*!********************************************************!*\
  !*** ./assets/wizards/popups/views/analytics/index.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Pop-ups wizard, Analytics screen.\n */\n\n/**\n * Internal dependencies\n */\n\n/**\n * Analytics Screen\n */\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_1__[\"withWizardScreen\"])(function () {\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(\"p\", null, \"TK\");\n}));\n\n//# sourceURL=webpack:///./assets/wizards/popups/views/analytics/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/views/index.js":
/*!**********************************************!*\
  !*** ./assets/wizards/popups/views/index.js ***!
  \**********************************************/
/*! exports provided: PopupGroup, Analytics */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _popup_group__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./popup-group */ \"./assets/wizards/popups/views/popup-group/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"PopupGroup\", function() { return _popup_group__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/* harmony import */ var _analytics__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./analytics */ \"./assets/wizards/popups/views/analytics/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Analytics\", function() { return _analytics__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; });\n\n\n\n\n//# sourceURL=webpack:///./assets/wizards/popups/views/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/views/popup-group/index.js":
/*!**********************************************************!*\
  !*** ./assets/wizards/popups/views/popup-group/index.js ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ \"./node_modules/@babel/runtime/helpers/toConsumableArray.js\");\n/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ \"./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js\");\n/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ \"./node_modules/@babel/runtime/helpers/getPrototypeOf.js\");\n/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_popup_action_card__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../components/popup-action-card */ \"./assets/wizards/popups/components/popup-action-card/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/popups/views/popup-group/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_12__);\n\n\n\n\n\n\n\n\n\n\n/**\n * Pop-ups wizard screen.\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n\n\n/**\n * Popup group screen\n */\n\nvar PopupGroup =\n/*#__PURE__*/\nfunction (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_6___default()(PopupGroup, _Component);\n\n  function PopupGroup(props) {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, PopupGroup);\n\n    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4___default()(PopupGroup).call(this, props));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_5___default()(_this), \"descriptionForPopup\", function (_ref) {\n      var categories = _ref.categories,\n          sitewideDefault = _ref.sitewide_default;\n\n      if (sitewideDefault) {\n        return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Sitewide default', 'newspack');\n      }\n\n      if (categories.length > 0) {\n        return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Categories: ', 'newspack') + categories.map(function (category) {\n          return category.name;\n        }).join(', ');\n      }\n\n      return null;\n    });\n\n    _this.state = {\n      filter: 'all'\n    };\n    return _this;\n  }\n  /**\n   * Construct the appropriate description for a single Pop-up based on categories and sitewide default status.\n   *\n   * @param {Object} popup object.\n   */\n\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(PopupGroup, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      var _this2 = this;\n\n      var filter = this.state.filter;\n      var _this$props = this.props,\n          deletePopup = _this$props.deletePopup,\n          emptyMessage = _this$props.emptyMessage,\n          _this$props$items = _this$props.items,\n          items = _this$props$items === void 0 ? {} : _this$props$items,\n          previewPopup = _this$props.previewPopup,\n          setCategoriesForPopup = _this$props.setCategoriesForPopup,\n          setSitewideDefaultPopup = _this$props.setSitewideDefaultPopup,\n          publishPopup = _this$props.publishPopup,\n          updatePopup = _this$props.updatePopup;\n      var _items$active = items.active,\n          active = _items$active === void 0 ? [] : _items$active,\n          _items$draft = items.draft,\n          draft = _items$draft === void 0 ? [] : _items$draft,\n          _items$test = items.test,\n          test = _items$test === void 0 ? [] : _items$test,\n          _items$inactive = items.inactive,\n          inactive = _items$inactive === void 0 ? [] : _items$inactive;\n      var sections = [];\n      var filterOptions = [];\n\n      if (active.length > 0) {\n        var label = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Active', 'newspack');\n\n        if (filter === 'all' || filter === 'active') {\n          sections.push(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], {\n            key: \"active\"\n          }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"h2\", {\n            className: \"newspack-popups-wizard__group-type\"\n          }, label, ' ', Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"span\", {\n            className: \"newspack-popups-wizard__group-count\"\n          }, active.length)), active.map(function (popup) {\n            return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_popup_action_card__WEBPACK_IMPORTED_MODULE_11__[\"default\"], {\n              className: popup.sitewide_default ? 'newspack-card__is-primary' : 'newspack-card__is-supported',\n              deletePopup: deletePopup,\n              description: _this2.descriptionForPopup(popup),\n              key: popup.id,\n              popup: popup,\n              previewPopup: previewPopup,\n              setCategoriesForPopup: setCategoriesForPopup,\n              setSitewideDefaultPopup: setSitewideDefaultPopup,\n              updatePopup: updatePopup\n            });\n          })));\n        }\n\n        filterOptions.push({\n          label: label,\n          value: 'active'\n        });\n      }\n\n      if (test.length > 0) {\n        var _label = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Test mode', 'newspack');\n\n        if (filter === 'all' || filter === 'test') {\n          sections.push(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], {\n            key: \"test\"\n          }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"h2\", {\n            className: \"newspack-popups-wizard__group-type\"\n          }, _label, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"span\", {\n            className: \"newspack-popups-wizard__group-count\"\n          }, test.length)), test.map(function (popup) {\n            return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_popup_action_card__WEBPACK_IMPORTED_MODULE_11__[\"default\"], {\n              className: \"newspack-card__is-secondary\",\n              deletePopup: deletePopup,\n              description: _this2.descriptionForPopup(popup),\n              key: popup.id,\n              popup: popup,\n              previewPopup: previewPopup,\n              setCategoriesForPopup: setCategoriesForPopup,\n              setSitewideDefaultPopup: setSitewideDefaultPopup,\n              updatePopup: updatePopup\n            });\n          })));\n        }\n\n        filterOptions.push({\n          label: _label,\n          value: 'test'\n        });\n      }\n\n      if (inactive.length > 0) {\n        var _label2 = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Inactive', 'newspack');\n\n        if (filter === 'all' || filter === 'inactive') {\n          sections.push(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], {\n            key: \"inactive\"\n          }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"h2\", {\n            className: \"newspack-popups-wizard__group-type\"\n          }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Inactive', 'newspack'), ' ', Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"span\", {\n            className: \"newspack-popups-wizard__group-count\"\n          }, inactive.length)), inactive.map(function (popup) {\n            return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_popup_action_card__WEBPACK_IMPORTED_MODULE_11__[\"default\"], {\n              className: \"newspack-card__is-disabled\",\n              deletePopup: deletePopup,\n              description: _this2.descriptionForPopup(popup),\n              key: popup.id,\n              popup: popup,\n              previewPopup: previewPopup,\n              setCategoriesForPopup: function setCategoriesForPopup() {\n                return null;\n              },\n              setSitewideDefaultPopup: setSitewideDefaultPopup,\n              updatePopup: updatePopup\n            });\n          })));\n        }\n\n        filterOptions.push({\n          label: _label2,\n          value: 'inactive'\n        });\n      }\n\n      if (draft.length > 0) {\n        var _label3 = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Draft', 'newspack');\n\n        if (filter === 'all' || filter === 'draft') {\n          sections.push(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], {\n            key: \"inactive\"\n          }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"h2\", {\n            className: \"newspack-popups-wizard__group-type\"\n          }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Draft', 'newspack'), ' ', Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"span\", {\n            className: \"newspack-popups-wizard__group-count\"\n          }, draft.length)), draft.map(function (popup) {\n            return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_popup_action_card__WEBPACK_IMPORTED_MODULE_11__[\"default\"], {\n              className: \"newspack-card__is-disabled\",\n              deletePopup: deletePopup,\n              description: _this2.descriptionForPopup(popup),\n              key: popup.id,\n              popup: popup,\n              previewPopup: previewPopup,\n              publishPopup: publishPopup,\n              setCategoriesForPopup: function setCategoriesForPopup() {\n                return null;\n              },\n              setSitewideDefaultPopup: setSitewideDefaultPopup,\n              updatePopup: updatePopup\n            });\n          })));\n        }\n\n        filterOptions.push({\n          label: _label3,\n          value: 'draft'\n        });\n      }\n\n      return sections.length > 0 ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], null, filterOptions.length > 0 && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_10__[\"SelectControl\"], {\n        options: [{\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('All', 'newspack'),\n          value: 'all'\n        }].concat(filterOptions),\n        value: filter,\n        onChange: function onChange(value) {\n          return _this2.setState({\n            filter: value\n          });\n        },\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Filter:', 'newspack'),\n        className: \"newspack-popups-wizard__group-select\"\n      }), sections.reduce(function (acc, item, index) {\n        return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(acc), [item, index < sections.length - 1 && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"hr\", {\n          key: index\n        })]);\n      }, [])) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(\"p\", null, emptyMessage);\n    }\n  }]);\n\n  return PopupGroup;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_10__[\"withWizardScreen\"])(PopupGroup));\n\n//# sourceURL=webpack:///./assets/wizards/popups/views/popup-group/index.js?");

/***/ }),

/***/ "./assets/wizards/popups/views/popup-group/style.scss":
/*!************************************************************!*\
  !*** ./assets/wizards/popups/views/popup-group/style.scss ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/popups/views/popup-group/style.scss?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/BugReport.js":
/*!******************************************************!*\
  !*** ./node_modules/@material-ui/icons/BugReport.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M20 8h-2.81c-.45-.78-1.07-1.45-1.82-1.96L17 4.41 15.59 3l-2.17 2.17C12.96 5.06 12.49 5 12 5c-.49 0-.96.06-1.41.17L8.41 3 7 4.41l1.62 1.63C7.88 6.55 7.26 7.22 6.81 8H4v2h2.09c-.05.33-.09.66-.09 1v1H4v2h2v1c0 .34.04.67.09 1H4v2h2.81c1.04 1.79 2.97 3 5.19 3s4.15-1.21 5.19-3H20v-2h-2.09c.05-.33.09-.66.09-1v-1h2v-2h-2v-1c0-.34-.04-.67-.09-1H20V8zm-6 8h-4v-2h4v2zm0-4h-4v-2h4v2z\"\n}), 'BugReport');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/BugReport.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/FilterList.js":
/*!*******************************************************!*\
  !*** ./node_modules/@material-ui/icons/FilterList.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z\"\n}), 'FilterList');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/FilterList.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/MoreVert.js":
/*!*****************************************************!*\
  !*** ./node_modules/@material-ui/icons/MoreVert.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z\"\n}), 'MoreVert');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/MoreVert.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Public.js":
/*!***************************************************!*\
  !*** ./node_modules/@material-ui/icons/Public.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z\"\n}), 'Public');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Public.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Publish.js":
/*!****************************************************!*\
  !*** ./node_modules/@material-ui/icons/Publish.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M5 4v2h14V4H5zm0 10h4v6h6v-6h4l-7-7-7 7z\"\n}), 'Publish');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Publish.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Today.js":
/*!**************************************************!*\
  !*** ./node_modules/@material-ui/icons/Today.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z\"\n}), 'Today');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Today.js?");

/***/ }),

/***/ "./node_modules/@material-ui/icons/Visibility.js":
/*!*******************************************************!*\
  !*** ./node_modules/@material-ui/icons/Visibility.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nvar _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ \"./node_modules/@babel/runtime/helpers/interopRequireDefault.js\");\n\nObject.defineProperty(exports, \"__esModule\", {\n  value: true\n});\nexports.default = void 0;\n\nvar _react = _interopRequireDefault(__webpack_require__(/*! react */ \"react\"));\n\nvar _createSvgIcon = _interopRequireDefault(__webpack_require__(/*! ./utils/createSvgIcon */ \"./node_modules/@material-ui/icons/utils/createSvgIcon.js\"));\n\nvar _default = (0, _createSvgIcon.default)(_react.default.createElement(\"path\", {\n  d: \"M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z\"\n}), 'Visibility');\n\nexports.default = _default;\n\n//# sourceURL=webpack:///./node_modules/@material-ui/icons/Visibility.js?");

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

/***/ "@wordpress/keycodes":
/*!*******************************************!*\
  !*** external {"this":["wp","keycodes"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"keycodes\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22keycodes%22%5D%7D?");

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