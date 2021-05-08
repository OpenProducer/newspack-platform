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
/******/ 		"engagement": 0
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
/******/ 	deferredModules.push(["./assets/wizards/engagement/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/engagement/index.js":
/*!********************************************!*\
  !*** ./assets/wizards/engagement/index.js ***!
  \********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/asyncToGenerator */ \"./node_modules/@babel/runtime/helpers/asyncToGenerator.js\");\n/* harmony import */ var _babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ \"./node_modules/@babel/runtime/helpers/assertThisInitialized.js\");\n/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_8__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./views */ \"./assets/wizards/engagement/views/index.js\");\n\n\n\n\n\n\n\n\n\n/**\n * Engagement\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_12__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_12__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_12__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_12__[\"default\"].Switch;\n\nvar EngagementWizard = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default()(EngagementWizard, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_6___default()(EngagementWizard);\n\n  function EngagementWizard(props) {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_2___default()(this, EngagementWizard);\n\n    _this = _super.call(this, props);\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"onWizardReady\", function () {\n      var _this$props = _this.props,\n          setError = _this$props.setError,\n          wizardApiFetch = _this$props.wizardApiFetch;\n      return wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-engagement-wizard/related-content'\n      }).then(function (data) {\n        return _this.setState(data);\n      }).catch(function (error) {\n        return setError(error);\n      });\n    });\n\n    _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_7___default()(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this), \"updatedRelatedContentSettings\", /*#__PURE__*/_babel_runtime_helpers_asyncToGenerator__WEBPACK_IMPORTED_MODULE_1___default()( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {\n      var wizardApiFetch, relatedPostsMaxAge;\n      return regeneratorRuntime.wrap(function _callee$(_context) {\n        while (1) {\n          switch (_context.prev = _context.next) {\n            case 0:\n              wizardApiFetch = _this.props.wizardApiFetch;\n              relatedPostsMaxAge = _this.state.relatedPostsMaxAge;\n              _context.prev = 2;\n              _context.next = 5;\n              return wizardApiFetch({\n                path: '/newspack/v1/wizard/newspack-engagement-wizard/related-posts-max-age',\n                method: 'POST',\n                data: {\n                  relatedPostsMaxAge: relatedPostsMaxAge\n                }\n              });\n\n            case 5:\n              _this.setState({\n                relatedPostsError: null,\n                relatedPostsUpdated: false\n              });\n\n              _context.next = 11;\n              break;\n\n            case 8:\n              _context.prev = 8;\n              _context.t0 = _context[\"catch\"](2);\n\n              _this.setState({\n                relatedPostsError: _context.t0.message || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('There was an error saving settings. Please try again.', 'newspack')\n              });\n\n            case 11:\n            case \"end\":\n              return _context.stop();\n          }\n        }\n      }, _callee, null, [[2, 8]]);\n    })));\n\n    _this.state = {\n      relatedPostsEnabled: false,\n      relatedPostsMaxAge: 0,\n      relatedPostsUpdated: false,\n      relatedPostsError: null\n    };\n    return _this;\n  }\n  /**\n   * Figure out whether to use the WooCommerce or Jetpack Mailchimp wizards and get appropriate settings.\n   */\n\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_3___default()(EngagementWizard, [{\n    key: \"render\",\n    value:\n    /**\n     * Render\n     */\n    function render() {\n      var _this2 = this;\n\n      var pluginRequirements = this.props.pluginRequirements;\n      var _this$state = this.state,\n          relatedPostsEnabled = _this$state.relatedPostsEnabled,\n          relatedPostsError = _this$state.relatedPostsError,\n          relatedPostsMaxAge = _this$state.relatedPostsMaxAge,\n          relatedPostsUpdated = _this$state.relatedPostsUpdated;\n      var tabbed_navigation = [{\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Newsletters'),\n        path: '/newsletters',\n        exact: true\n      }, {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Social'),\n        path: '/social',\n        exact: true\n      }, {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Commenting'),\n        path: '/commenting'\n      }, {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Recirculation'),\n        path: '/recirculation'\n      }, {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('UGC'),\n        path: '/user-generated-content'\n      }];\n\n      var subheader = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Newsletters, social, commenting, recirculation, user-generated content');\n\n      var props = {\n        headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Engagement', 'newspack'),\n        subHeaderText: subheader,\n        tabbedNavigation: tabbed_navigation\n      };\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(HashRouter, {\n        hashType: \"slash\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Route, {\n        path: \"/newsletters\",\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_13__[\"Newsletters\"], props);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Route, {\n        path: \"/social\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_13__[\"Social\"], props);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Route, {\n        path: \"/commenting\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_13__[\"Commenting\"], props);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Route, {\n        path: \"/recirculation\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_13__[\"RelatedContent\"], _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, props, {\n            relatedPostsEnabled: relatedPostsEnabled,\n            relatedPostsError: relatedPostsError,\n            buttonAction: function buttonAction() {\n              return _this2.updatedRelatedContentSettings();\n            },\n            buttonText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_10__[\"__\"])('Save', 'newspack'),\n            buttonDisabled: !relatedPostsEnabled || !relatedPostsUpdated,\n            relatedPostsMaxAge: relatedPostsMaxAge,\n            onChange: function onChange(value) {\n              _this2.setState({\n                relatedPostsMaxAge: value,\n                relatedPostsUpdated: true\n              });\n            }\n          }));\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Route, {\n        path: \"/user-generated-content\",\n        exact: true,\n        render: function render() {\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(_views__WEBPACK_IMPORTED_MODULE_13__[\"UGC\"], props);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Redirect, {\n        to: \"/newsletters\"\n      }))));\n    }\n  }]);\n\n  return EngagementWizard;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_9__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_11__[\"withWizard\"])(EngagementWizard, ['jetpack'])), document.getElementById('newspack-engagement-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/engagement/index.js?");

/***/ }),

/***/ "./assets/wizards/engagement/views/commenting/index.js":
/*!*************************************************************!*\
  !*** ./assets/wizards/engagement/views/commenting/index.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n\nvar Commenting = function Commenting() {\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(false),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      disqusActive = _useState2[0],\n      setDisqusActive = _useState2[1];\n\n  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(false),\n      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState3, 2),\n      coralActive = _useState4[0],\n      setCoralActive = _useState4[1];\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('WordPress comments', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"ActionCard\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('WordPress Commenting'),\n    description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Native WordPress commenting system.'),\n    actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Configure'),\n    handoff: \"wordpress-settings-discussion\"\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Disqus', 'newspack')), disqusActive ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"ActionCard\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Disqus', 'newspack'),\n    description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Disqus commenting system.'),\n    actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Configure'),\n    handoff: \"disqus-comment-system\"\n  }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"PluginInstaller\"], {\n    withoutFooterButton: true,\n    plugins: ['disqus-comment-system', 'newspack-disqus-amp'],\n    onStatus: function onStatus(_ref) {\n      var complete = _ref.complete;\n      return setDisqusActive(complete);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('The Coral Project', 'newspack')), coralActive ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"ActionCard\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('The Coral Project', 'newspack'),\n    description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Coral Project  commenting system.'),\n    actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Configure'),\n    handoff: \"talk-wp-plugin\"\n  }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"PluginInstaller\"], {\n    withoutFooterButton: true,\n    plugins: ['talk-wp-plugin'],\n    onStatus: function onStatus(_ref2) {\n      var complete = _ref2.complete;\n      return setCoralActive(complete);\n    }\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"withWizardScreen\"])(Commenting));\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/commenting/index.js?");

/***/ }),

/***/ "./assets/wizards/engagement/views/index.js":
/*!**************************************************!*\
  !*** ./assets/wizards/engagement/views/index.js ***!
  \**************************************************/
/*! exports provided: Newsletters, Social, RelatedContent, UGC, Commenting */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _newsletters__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./newsletters */ \"./assets/wizards/engagement/views/newsletters/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Newsletters\", function() { return _newsletters__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n/* harmony import */ var _social__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./social */ \"./assets/wizards/engagement/views/social/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Social\", function() { return _social__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; });\n\n/* harmony import */ var _related_content__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./related-content */ \"./assets/wizards/engagement/views/related-content/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"RelatedContent\", function() { return _related_content__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; });\n\n/* harmony import */ var _ugc__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ugc */ \"./assets/wizards/engagement/views/ugc/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"UGC\", function() { return _ugc__WEBPACK_IMPORTED_MODULE_3__[\"default\"]; });\n\n/* harmony import */ var _commenting__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./commenting */ \"./assets/wizards/engagement/views/commenting/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Commenting\", function() { return _commenting__WEBPACK_IMPORTED_MODULE_4__[\"default\"]; });\n\n\n\n\n\n\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/index.js?");

/***/ }),

/***/ "./assets/wizards/engagement/views/related-content/index.js":
/*!******************************************************************!*\
  !*** ./assets/wizards/engagement/views/related-content/index.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/engagement/views/related-content/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_8__);\n\n\n\n\n\n\n/**\n * Related content screen.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Related Content Screen\n */\n\nvar RelatedContent = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(RelatedContent, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(RelatedContent);\n\n  function RelatedContent() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, RelatedContent);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(RelatedContent, [{\n    key: \"render\",\n    value:\n    /**\n     * Render.\n     */\n    function render() {\n      var _this$props = this.props,\n          _onChange = _this$props.onChange,\n          relatedPostsEnabled = _this$props.relatedPostsEnabled,\n          relatedPostsError = _this$props.relatedPostsError,\n          relatedPostsMaxAge = _this$props.relatedPostsMaxAge;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-salesforce-wizard\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Recirculation Settings', 'newspack')), relatedPostsError && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"Notice\"], {\n        noticeText: relatedPostsError,\n        isError: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('These extra settings apply to Related Posts shown by Jetpack.', 'newspack'), ' ', Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__[\"ExternalLink\"], {\n        href: \"/wp-admin/admin.php?page=jetpack#/traffic\",\n        key: \"jetpack-link\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Configure Related Posts in Jetpack', 'newspack'))), relatedPostsEnabled ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"TextControl\"], {\n        className: \"newspack-related-content__age-input\",\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('If set, posts will be shown as related content only if published within the past number of months. If 0, any published post can be shown, regardless of publish date.', 'newspack'),\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Maximum age of related content, in months', 'newspack'),\n        onChange: function onChange(value) {\n          return _onChange(value);\n        },\n        placeholder: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Maximum age of related content, in months'),\n        type: \"number\",\n        value: relatedPostsMaxAge || 0\n      }) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"ActionCard\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Jetpack Related Posts'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Enable Related Posts via Jetpack.'),\n        actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Configure'),\n        handoff: \"jetpack\",\n        editLink: \"admin.php?page=jetpack#/traffic\"\n      })));\n    }\n  }]);\n\n  return RelatedContent;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_7__[\"withWizardScreen\"])(RelatedContent));\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/related-content/index.js?");

/***/ }),

/***/ "./assets/wizards/engagement/views/related-content/style.scss":
/*!********************************************************************!*\
  !*** ./assets/wizards/engagement/views/related-content/style.scss ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/related-content/style.scss?");

/***/ }),

/***/ "./assets/wizards/engagement/views/social/index.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/engagement/views/social/index.js ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n/**\n * Social screen.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Social Screen\n */\n\nvar Social = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(Social, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(Social);\n\n  function Social() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, Social);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(Social, [{\n    key: \"render\",\n    value:\n    /**\n     * Render.\n     */\n    function render() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"ActionCard\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Jetpack Publicize'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Publicize makes it easy to share your site’s posts on several social media networks automatically when you publish a new post.'),\n        actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Configure'),\n        handoff: \"jetpack\",\n        editLink: \"admin.php?page=jetpack#/sharing\"\n      });\n    }\n  }]);\n\n  return Social;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"withWizardScreen\"])(Social));\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/social/index.js?");

/***/ }),

/***/ "./assets/wizards/engagement/views/ugc/index.js":
/*!******************************************************!*\
  !*** ./assets/wizards/engagement/views/ugc/index.js ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n/**\n * UGC screen.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * UGC Screen\n */\n\nvar UGC = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(UGC, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(UGC);\n\n  function UGC() {\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, UGC);\n\n    return _super.apply(this, arguments);\n  }\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(UGC, [{\n    key: \"render\",\n    value:\n    /**\n     * Render.\n     */\n    function render() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('Coming Soon')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__[\"__\"])('User Generated Content features TK.')));\n    }\n  }]);\n\n  return UGC;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_components_src__WEBPACK_IMPORTED_MODULE_6__[\"withWizardScreen\"])(UGC));\n\n//# sourceURL=webpack:///./assets/wizards/engagement/views/ugc/index.js?");

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"apiFetch\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22apiFetch%22%5D?");

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"components\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22components%22%5D?");

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

/***/ "@wordpress/keycodes":
/*!**********************************!*\
  !*** external ["wp","keycodes"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"keycodes\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22keycodes%22%5D?");

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"primitives\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22primitives%22%5D?");

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"url\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22url%22%5D?");

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"lodash\"]; }());\n\n//# sourceURL=webpack:///external_%22lodash%22?");

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"React\"]; }());\n\n//# sourceURL=webpack:///external_%22React%22?");

/***/ }),

/***/ "react-dom":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"ReactDOM\"]; }());\n\n//# sourceURL=webpack:///external_%22ReactDOM%22?");

/***/ })

/******/ })));