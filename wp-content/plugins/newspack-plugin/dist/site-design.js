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
/******/ 		"site-design": 0
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
/******/ 	deferredModules.push(["./assets/wizards/site-design/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/site-design/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/site-design/index.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/objectSpread2 */ \"./node_modules/@babel/runtime/helpers/objectSpread2.js\");\n/* harmony import */ var _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./views */ \"./assets/wizards/site-design/views/index.js\");\n\n\n\n\n\n\n\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_11__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_11__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_11__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_11__[\"default\"].Switch;\n/**\n * Site Design Wizard.\n */\n\nvar SiteDesignWizard = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(SiteDesignWizard, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_5___default()(SiteDesignWizard);\n\n  function SiteDesignWizard() {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, SiteDesignWizard);\n\n    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {\n      args[_key] = arguments[_key];\n    }\n\n    _this = _super.call.apply(_super, [this].concat(args));\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"componentDidMount\", function () {\n      _this.retrieveTheme();\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"retrieveTheme\", function () {\n      var _this$props = _this.props,\n          setError = _this$props.setError,\n          wizardApiFetch = _this$props.wizardApiFetch;\n      var params = {\n        path: '/newspack/v1/wizard/newspack-setup-wizard/theme',\n        method: 'GET'\n      };\n      wizardApiFetch(params).then(function (response) {\n        var theme = response.theme,\n            themeMods = response.theme_mods;\n\n        _this.setState({\n          theme: theme,\n          themeMods: themeMods\n        });\n      }).catch(function (error) {\n        console.log('[Theme Fetch Error]', error);\n        setError({\n          error: error\n        });\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"updateTheme\", function (newTheme) {\n      var _this$props2 = _this.props,\n          setError = _this$props2.setError,\n          wizardApiFetch = _this$props2.wizardApiFetch;\n      var params = {\n        path: '/newspack/v1/wizard/newspack-setup-wizard/theme/' + newTheme,\n        method: 'POST',\n        quiet: true\n      };\n      wizardApiFetch(params).then(function (response) {\n        var theme = response.theme,\n            themeMods = response.theme_mods;\n\n        _this.setState({\n          theme: theme,\n          themeMods: themeMods\n        });\n      }).catch(function (error) {\n        console.log('[Theme Update Error]', error);\n        setError({\n          error: error\n        });\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"setThemeMods\", function (themeModUpdates) {\n      return _this.setState({\n        themeMods: _babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()(_babel_runtime_helpers_objectSpread2__WEBPACK_IMPORTED_MODULE_0___default()({}, _this.state.themeMods), themeModUpdates)\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"updateThemeMods\", function () {\n      var _this$props3 = _this.props,\n          setError = _this$props3.setError,\n          wizardApiFetch = _this$props3.wizardApiFetch;\n      var themeMods = _this.state.themeMods;\n      var params = {\n        path: '/newspack/v1/wizard/newspack-setup-wizard/theme-mods/',\n        method: 'POST',\n        data: {\n          theme_mods: themeMods\n        },\n        quiet: true\n      };\n      wizardApiFetch(params).then(function (response) {\n        var theme = response.theme,\n            theme_mods = response.theme_mods;\n\n        _this.setState({\n          theme: theme,\n          themeMods: theme_mods\n        });\n      }).catch(function (error) {\n        console.log('[Theme Update Error]', error);\n        setError({\n          error: error\n        });\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_6___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this), \"state\", {\n      theme: null\n    });\n\n    return _this;\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(SiteDesignWizard, [{\n    key: \"render\",\n\n    /**\n     * Render\n     */\n    value: function render() {\n      var _this2 = this;\n\n      var pluginRequirements = this.props.pluginRequirements;\n      var tabbedNavigation = [{\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Theme'),\n        path: '/',\n        exact: true\n      }, {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Settings'),\n        path: '/settings',\n        exact: true\n      }];\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(HashRouter, {\n        hashType: \"slash\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(Route, {\n        path: \"/\",\n        exact: true,\n        render: function render() {\n          var theme = _this2.state.theme;\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_12__[\"ThemeSelection\"], {\n            headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Site Design', 'newspack'),\n            subHeaderText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Choose a Newspack theme', 'newspack'),\n            tabbedNavigation: tabbedNavigation,\n            buttonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Configure', 'newspack'),\n            buttonAction: \"#/settings\",\n            updateTheme: _this2.updateTheme,\n            theme: theme\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(Route, {\n        path: \"/settings\",\n        exact: true,\n        render: function render() {\n          var themeMods = _this2.state.themeMods;\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_12__[\"ThemeMods\"], {\n            headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Site Design', 'newspack'),\n            subHeaderText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Configure your Newspack theme', 'newspack'),\n            tabbedNavigation: tabbedNavigation,\n            themeMods: themeMods,\n            setThemeMods: _this2.setThemeMods,\n            buttonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Save', 'newspack'),\n            buttonAction: _this2.updateThemeMods,\n            secondaryButtonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_9__[\"__\"])('Advanced settings', 'newspack'),\n            secondaryButtonAction: \"/wp-admin/customize.php\"\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(Redirect, {\n        to: \"/\"\n      }))));\n    }\n  }]);\n\n  return SiteDesignWizard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_10__[\"withWizard\"])(SiteDesignWizard)), document.getElementById('newspack-site-design-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/site-design/index.js?");

/***/ }),

/***/ "./assets/wizards/site-design/views/index.js":
/*!***************************************************!*\
  !*** ./assets/wizards/site-design/views/index.js ***!
  \***************************************************/
/*! exports provided: ThemeSelection, ThemeMods */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _theme_selection__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./theme-selection */ \"./assets/wizards/site-design/views/theme-selection/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"ThemeSelection\", function() { return _theme_selection__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/* harmony import */ var _theme_mods__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./theme-mods */ \"./assets/wizards/site-design/views/theme-mods/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"ThemeMods\", function() { return _theme_mods__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; });\n\n\n\n\n//# sourceURL=webpack:///./assets/wizards/site-design/views/index.js?");

/***/ }),

/***/ "./assets/wizards/site-design/views/theme-mods/index.js":
/*!**************************************************************!*\
  !*** ./assets/wizards/site-design/views/theme-mods/index.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/site-design/views/theme-mods/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_7__);\n\n\n\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Theme Selection Screen.\n */\n\nvar ThemeMods = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(ThemeMods, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(ThemeMods);\n\n  function ThemeMods() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, ThemeMods);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(ThemeMods, [{\n    key: \"render\",\n\n    /**\n     * Render.\n     */\n    value: function render() {\n      var _this$props = this.props,\n          themeMods = _this$props.themeMods,\n          setThemeMods = _this$props.setThemeMods;\n      var headerSolidBackground = themeMods.header_solid_background,\n          headerSimplified = themeMods.header_simplified,\n          headerCenterLogo = themeMods.header_center_logo,\n          _themeMods$show_autho = themeMods.show_author_bio,\n          authorBio = _themeMods$show_autho === void 0 ? true : _themeMods$show_autho,\n          authorEmail = themeMods.show_author_email,\n          _themeMods$author_bio = themeMods.author_bio_length,\n          authorBioLength = _themeMods$author_bio === void 0 ? 200 : _themeMods$author_bio,\n          featuredImageDefault = themeMods.featured_image_default;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Header', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ToggleControl\"], {\n        isDark: true,\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Solid background', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Use the primary color as the header background.', 'newspack'),\n        checked: headerSolidBackground,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            header_solid_background: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ToggleControl\"], {\n        isDark: true,\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Center logo', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Center the logo in the header.', 'newspack'),\n        checked: headerCenterLogo,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            header_center_logo: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ToggleControl\"], {\n        isDark: true,\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Short header', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Display the header as a shorter, simpler version', 'newspack'),\n        checked: headerSimplified,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            header_simplified: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"hr\", null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Author bio', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ToggleGroup\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Author bio', 'newspack'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Display an author bio under individual posts.', 'newspack'),\n        checked: authorBio,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            show_author_bio: value\n          });\n        }\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ToggleControl\"], {\n        isDark: true,\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Author email', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Display the author email with bio on individual posts.', 'newspack'),\n        checked: authorEmail,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            show_author_email: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"TextControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Length', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Truncates the author bio on single posts to this approximate character length, but without breaking a word. The full bio appears on the author archive page.', 'newspack'),\n        type: \"number\",\n        value: authorBioLength,\n        onChange: function onChange(value) {\n          return setThemeMods({\n            author_bio_length: value\n          });\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"hr\", null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Featured Image', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"RadioControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Default position', 'newspack'),\n        selected: featuredImageDefault,\n        options: [{\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Large', 'newspack'),\n          value: 'large'\n        }, {\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Small', 'newspack'),\n          value: 'small'\n        }, {\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Behind article title', 'newspack'),\n          value: 'behind'\n        }, {\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Beside article title', 'newspack'),\n          value: 'beside'\n        }, {\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Hidden', 'newspack'),\n          value: 'hidden'\n        }],\n        onChange: function onChange(value) {\n          return setThemeMods({\n            featured_image_default: value\n          });\n        }\n      }));\n    }\n  }]);\n\n  return ThemeMods;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\nThemeMods.defaultProps = {\n  themeMods: {},\n  setThemeMods: function setThemeMods() {\n    return null;\n  }\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"withWizardScreen\"])(ThemeMods));\n\n//# sourceURL=webpack:///./assets/wizards/site-design/views/theme-mods/index.js?");

/***/ }),

/***/ "./assets/wizards/site-design/views/theme-mods/style.scss":
/*!****************************************************************!*\
  !*** ./assets/wizards/site-design/views/theme-mods/style.scss ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/site-design/views/theme-mods/style.scss?");

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