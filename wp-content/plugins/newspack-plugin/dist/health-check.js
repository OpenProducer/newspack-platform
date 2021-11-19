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

/***/ "./assets/wizards/health-check/index.js":
/*!**********************************************!*\
  !*** ./assets/wizards/health-check/index.js ***!
  \**********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./views */ \"./assets/wizards/health-check/views/index.js\");\n\n\n/**\n * Health Check\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__[\"default\"];\n\nclass HealthCheckWizard extends _wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Component {\n  constructor(props) {\n    super(props);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"onWizardReady\", () => {\n      this.fetchHealthData();\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"fetchHealthData\", () => {\n      const {\n        wizardApiFetch,\n        setError\n      } = this.props;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-health-check-wizard/'\n      }).then(healthCheckData => this.setState({\n        healthCheckData,\n        hasData: true\n      })).catch(error => {\n        setError(error);\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"deactivateAllPlugins\", () => {\n      const {\n        wizardApiFetch,\n        setError\n      } = this.props;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-health-check-wizard/unsupported_plugins',\n        method: 'delete'\n      }).then(healthCheckData => this.setState({\n        healthCheckData\n      })).catch(error => {\n        setError(error);\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"repairConfiguration\", configuration => {\n      const {\n        wizardApiFetch,\n        setError\n      } = this.props;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-health-check-wizard/repair/' + configuration\n      }).then(healthCheckData => this.setState({\n        healthCheckData\n      })).catch(error => {\n        setError(error);\n      });\n    });\n\n    this.state = {\n      hasData: false,\n      healthCheckData: {\n        unsupported_plugins: {},\n        missing_plugins: {}\n      }\n    };\n  }\n\n  /**\n   * Render\n   */\n  render() {\n    const {\n      hasData,\n      healthCheckData\n    } = this.state;\n    const {\n      unsupported_plugins: unsupportedPlugins,\n      missing_plugins: missingPlugins,\n      configuration_status: configurationStatus\n    } = healthCheckData;\n    const tabs = [{\n      label: __('Plugins', 'newspack'),\n      path: '/',\n      exact: true\n    }, {\n      label: __('Configuration'),\n      path: '/configuration'\n    }];\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(HashRouter, {\n      hashType: \"slash\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Switch, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Plugins, {\n        headerText: __('Health Check', 'newspack'),\n        subHeaderText: __('Verify and correct site health issues', 'newspack'),\n        deactivateAllPlugins: this.deactivateAllPlugins,\n        tabbedNavigation: tabs,\n        missingPlugins: Object.keys(missingPlugins),\n        unsupportedPlugins: Object.keys(unsupportedPlugins).map(value => ({ ...unsupportedPlugins[value],\n          Slug: value\n        }))\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/configuration\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Configuration, {\n        hasData: hasData,\n        headerText: __('Health Check', 'newspack'),\n        subHeaderText: __('Verify and correct site health issues', 'newspack'),\n        tabbedNavigation: tabs,\n        configurationStatus: configurationStatus,\n        missingPlugins: Object.keys(missingPlugins),\n        repairConfiguration: this.repairConfiguration\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Redirect, {\n      to: \"/\"\n    }))));\n  }\n\n}\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizard)(HealthCheckWizard)), document.getElementById('newspack-health-check-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/health-check/index.js?");

/***/ }),

/***/ "./assets/wizards/health-check/views/configuration/index.js":
/*!******************************************************************!*\
  !*** ./assets/wizards/health-check/views/configuration/index.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Notify about site misconfigurations.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * SEO Intro screen.\n */\n\nclass Configuration extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      configurationStatus,\n      missingPlugins,\n      hasData,\n      repairConfiguration\n    } = this.props;\n    const {\n      amp,\n      jetpack,\n      sitekit\n    } = configurationStatus || {};\n    const isAMPMissing = missingPlugins.indexOf('amp') !== -1;\n    return hasData && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ActionCard, {\n      className: amp ? 'newspack-card__is-supported' : 'newspack-card__is-unsupported',\n      title: __('AMP', 'newspack'),\n      description: // eslint-disable-next-line no-nested-ternary\n      isAMPMissing ? __('AMP plugin is not active.', 'newspack') : amp ? __('AMP plugin is in standard mode.', 'newspack') : __('AMP plugin is not in standard mode. ', 'newspack'),\n      actionText: !isAMPMissing && !amp && __('Repair', 'newspack'),\n      onClick: () => repairConfiguration('amp')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ActionCard, {\n      className: jetpack ? 'newspack-card__is-supported' : 'newspack-card__is-unsupported',\n      title: __('Jetpack', 'newspack'),\n      description: jetpack ? __('Jetpack is connected.', 'newspack') : __('Jetpack is not connected. ', 'newspack'),\n      actionText: !jetpack && __('Connect', 'newspack'),\n      handoff: \"jetpack\"\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ActionCard, {\n      className: sitekit ? 'newspack-card__is-supported' : 'newspack-card__is-unsupported',\n      title: __('Google Site Kit', 'newspack'),\n      description: sitekit ? __('Site Kit is connected.', 'newspack') : __('Site Kit is not connected. ', 'newspack'),\n      actionText: !sitekit && __('Connect', 'newspack'),\n      handoff: \"google-site-kit\"\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(Configuration));\n\n//# sourceURL=webpack://newspack/./assets/wizards/health-check/views/configuration/index.js?");

/***/ }),

/***/ "./assets/wizards/health-check/views/index.js":
/*!****************************************************!*\
  !*** ./assets/wizards/health-check/views/index.js ***!
  \****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Plugins\": function() { return /* reexport safe */ _plugins__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"Configuration\": function() { return /* reexport safe */ _configuration__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins */ \"./assets/wizards/health-check/views/plugins/index.js\");\n/* harmony import */ var _configuration__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./configuration */ \"./assets/wizards/health-check/views/configuration/index.js\");\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/health-check/views/index.js?");

/***/ }),

/***/ "./assets/wizards/health-check/views/plugins/index.js":
/*!************************************************************!*\
  !*** ./assets/wizards/health-check/views/plugins/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Remove unsupported plugins.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * SEO Intro screen.\n */\n\nclass Plugins extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      unsupportedPlugins,\n      missingPlugins,\n      deactivateAllPlugins\n    } = this.props;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, missingPlugins.length ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Notice, {\n      noticeText: __('These plugins shoud be active:'),\n      isWarning: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.PluginInstaller, {\n      plugins: missingPlugins\n    })) : null, unsupportedPlugins.length ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Notice, {\n      noticeText: __('Newspack does not support these plugins:'),\n      isError: true\n    }), unsupportedPlugins.map(unsupportedPlugin => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ActionCard, {\n      title: unsupportedPlugin.Name,\n      key: unsupportedPlugin.Slug,\n      description: unsupportedPlugin.Description,\n      className: \"newspack-card__is-unsupported\"\n    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n      className: \"newspack-buttons-card\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n      isPrimary: true,\n      onClick: deactivateAllPlugins\n    }, __('Deactivate All')))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Notice, {\n      noticeText: __('No unsupported plugins found.'),\n      isSuccess: true\n    })));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(Plugins));\n\n//# sourceURL=webpack://newspack/./assets/wizards/health-check/views/plugins/index.js?");

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ (function(module) {

module.exports = window["React"];

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["lodash"];

/***/ }),

/***/ "moment":
/*!*************************!*\
  !*** external "moment" ***!
  \*************************/
/***/ (function(module) {

module.exports = window["moment"];

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/html-entities":
/*!**************************************!*\
  !*** external ["wp","htmlEntities"] ***!
  \**************************************/
/***/ (function(module) {

module.exports = window["wp"]["htmlEntities"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/keycodes":
/*!**********************************!*\
  !*** external ["wp","keycodes"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["keycodes"];

/***/ }),

/***/ "@wordpress/primitives":
/*!************************************!*\
  !*** external ["wp","primitives"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["primitives"];

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/***/ (function(module) {

module.exports = window["wp"]["url"];

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
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
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
/******/ 	/* webpack/runtime/global */
/******/ 	!function() {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
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
/******/ 	/* webpack/runtime/publicPath */
/******/ 	!function() {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript)
/******/ 				scriptUrl = document.currentScript.src
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) scriptUrl = scripts[scripts.length - 1].src
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl;
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"health-check": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkwebpack"] = self["webpackChunkwebpack"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/health-check/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;