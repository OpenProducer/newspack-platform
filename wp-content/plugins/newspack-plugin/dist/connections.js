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
/******/ 		"connections": 0
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
/******/ 	deferredModules.push(["./assets/wizards/connections/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/connections/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/connections/index.js ***!
  \*********************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./views */ \"./assets/wizards/connections/views/index.js\");\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\n\nvar HashRouter = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"].HashRouter,\n    Redirect = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"].Redirect,\n    Route = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"].Route,\n    Switch = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"].Switch;\nvar MainScreen = Object(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"withWizardScreen\"])(_views__WEBPACK_IMPORTED_MODULE_5__[\"Main\"]);\n\nvar ConnectionsWizard = function ConnectionsWizard(_ref) {\n  var pluginRequirements = _ref.pluginRequirements,\n      wizardApiFetch = _ref.wizardApiFetch,\n      startLoading = _ref.startLoading,\n      doneLoading = _ref.doneLoading;\n  var wizardScreenProps = {\n    headerText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Connections', 'newspack'),\n    subHeaderText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Connections to third-party services', 'newspack'),\n    wizardApiFetch: wizardApiFetch,\n    startLoading: startLoading,\n    doneLoading: doneLoading\n  };\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(HashRouter, {\n    hashType: \"slash\"\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(Switch, null, pluginRequirements, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(Route, {\n    exact: true,\n    path: \"/\",\n    render: function render() {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(MainScreen, wizardScreenProps);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(Redirect, {\n    to: \"/\"\n  })));\n};\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(Object(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"withWizard\"])(ConnectionsWizard)), document.getElementById('newspack-connections-wizard'));\n\n//# sourceURL=webpack:///./assets/wizards/connections/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/index.js":
/*!***************************************************!*\
  !*** ./assets/wizards/connections/views/index.js ***!
  \***************************************************/
/*! exports provided: Main */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _main__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main */ \"./assets/wizards/connections/views/main/index.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"Main\", function() { return _main__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; });\n\n\n\n//# sourceURL=webpack:///./assets/wizards/connections/views/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/fivetran.js":
/*!***********************************************************!*\
  !*** ./assets/wizards/connections/views/main/fivetran.js ***!
  \***********************************************************/
/*! exports provided: handleFivetranRedirect, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"handleFivetranRedirect\", function() { return handleFivetranRedirect; });\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_6__);\n\n\n\n/* global newspack_connections_data */\n\n/**\n * WordPress dependencies\n */\n\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * External dependencies\n */\n\n\n\nvar handleFivetranRedirect = function handleFivetranRedirect(response, _ref) {\n  var wizardApiFetch = _ref.wizardApiFetch,\n      startLoading = _ref.startLoading,\n      doneLoading = _ref.doneLoading;\n  var params = Object(qs__WEBPACK_IMPORTED_MODULE_5__[\"parse\"])(window.location.search.replace(/^\\?/, '')); // 'id' param will be appended by the redirect from a Fivetran connect card.\n\n  if (params.id) {\n    startLoading();\n    var newConnector = Object(lodash__WEBPACK_IMPORTED_MODULE_6__[\"find\"])(Object(lodash__WEBPACK_IMPORTED_MODULE_6__[\"values\"])(response.fivetran), ['id', params.id]);\n\n    var removeIdParamFromURL = function removeIdParamFromURL() {\n      // Remove the 'id' param.\n      params.id = undefined;\n      window.location.search = Object(qs__WEBPACK_IMPORTED_MODULE_5__[\"stringify\"])(params);\n    };\n\n    if (newConnector) {\n      if (newConnector.sync_state === 'paused') {\n        wizardApiFetch({\n          path: '/newspack/v1/oauth/fivetran?connector_id=' + newConnector.id,\n          method: 'POST',\n          data: {\n            paused: false\n          }\n        }).then(removeIdParamFromURL);\n      } else {\n        removeIdParamFromURL();\n      }\n    }\n\n    doneLoading();\n  }\n};\nvar CONNECTORS = [{\n  service: 'google_analytics',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Google Analytics', 'newspack')\n}, {\n  service: 'mailchimp',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Mailchimp', 'newspack')\n}, {\n  service: 'stripe',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Stripe', 'newspack')\n}, {\n  service: 'double_click_publishers',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Google Ad Manager', 'newspack')\n}, {\n  service: 'facebook_pages',\n  label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Facebook Pages', 'newspack')\n}];\n\nvar FivetranConnection = function FivetranConnection(_ref2) {\n  var wpComStatus = _ref2.wpComStatus,\n      setError = _ref2.setError;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      connections = _useState2[0],\n      setConnections = _useState2[1];\n\n  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(false),\n      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState3, 2),\n      inFlight = _useState4[0],\n      setInFlight = _useState4[1];\n\n  var hasFetched = connections !== undefined;\n  var canBeConnected = wpComStatus === true;\n  var canUseFivetran = newspack_connections_data.can_connect_fivetran;\n\n  var handleError = function handleError(err) {\n    if (err.message) {\n      setError(err.message);\n    }\n\n    setInFlight(false);\n  };\n\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useEffect\"])(function () {\n    if (!canUseFivetran || !canBeConnected) {\n      return;\n    }\n\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/oauth/fivetran'\n    }).then(function (res) {\n      setConnections(res);\n      setInFlight(false);\n    }).catch(handleError);\n  }, [canUseFivetran, canBeConnected]);\n\n  if (!canUseFivetran) {\n    return null;\n  }\n\n  var createConnection = function createConnection(_ref3) {\n    var service = _ref3.service;\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: \"/newspack/v1/oauth/fivetran/\".concat(service),\n      method: 'POST',\n      data: {\n        service: service\n      }\n    }).then(function (_ref4) {\n      var url = _ref4.url;\n      return window.location = url;\n    }).catch(handleError);\n  };\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"div\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"h1\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Fivetran', 'newspack')), wpComStatus === false && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_4__[\"Notice\"], {\n    isWarning: true\n  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Connect your WordPress.com account first.', 'newspack')), CONNECTORS.map(function (item) {\n    var setupState = Object(lodash__WEBPACK_IMPORTED_MODULE_6__[\"get\"])(connections, [item.service, 'setup_state']);\n    var syncState = Object(lodash__WEBPACK_IMPORTED_MODULE_6__[\"get\"])(connections, [item.service, 'sync_state']);\n    var status = {\n      // eslint-disable-next-line no-nested-ternary\n      label: setupState ? \"\".concat(setupState, \", \").concat(syncState) : hasFetched ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Not connected', 'newspack') : '-',\n      isConnected: setupState === 'connected'\n    };\n    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"div\", {\n      key: item.service\n    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_4__[\"ActionCard\"], {\n      title: item.label,\n      description: \"\".concat(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Status:', 'newspack'), \" \").concat(status.label),\n      actionText: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_4__[\"Button\"], {\n        disabled: inFlight || !hasFetched || !canBeConnected,\n        onClick: function onClick() {\n          return createConnection(item);\n        },\n        isLink: true\n      }, status.isConnected ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Re-connect', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Connect', 'newspack')),\n      checkbox: status.isConnected ? 'checked' : 'unchecked',\n      isMedium: true\n    }));\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (FivetranConnection);\n\n//# sourceURL=webpack:///./assets/wizards/connections/views/main/fivetran.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/google.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/connections/views/main/google.js ***!
  \*********************************************************/
/*! exports provided: handleGoogleRedirect, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"handleGoogleRedirect\", function() { return handleGoogleRedirect; });\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n/* global newspack_connections_data */\n\n/**\n * External dependencies.\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nvar getURLParams = function getURLParams() {\n  return qs__WEBPACK_IMPORTED_MODULE_2___default.a.parse(window.location.search.replace(/^\\?/, ''));\n};\n\nvar handleGoogleRedirect = function handleGoogleRedirect(_ref) {\n  var setError = _ref.setError;\n  var params = getURLParams();\n\n  if (params.access_token) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({\n      path: '/newspack/v1/oauth/google/finish',\n      method: 'POST',\n      data: {\n        access_token: params.access_token,\n        refresh_token: params.refresh_token,\n        csrf_token: params.csrf_token,\n        expires_at: params.expires_at\n      }\n    }).then(function () {\n      params.access_token = undefined;\n      params.refresh_token = undefined;\n      params.csrf_token = undefined;\n      params.expires_at = undefined;\n      window.location.search = qs__WEBPACK_IMPORTED_MODULE_2___default.a.stringify(params);\n    }).catch(function (e) {\n      setError(e.message || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Something went wrong during authentication with Google.', 'newspack'));\n    });\n  }\n\n  return Promise.resolve();\n};\n\nvar GoogleOAuth = function GoogleOAuth(_ref2) {\n  var setError = _ref2.setError,\n      canBeConnected = _ref2.canBeConnected;\n\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])({}),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      authState = _useState2[0],\n      setAuthState = _useState2[1];\n\n  var userBasicInfo = authState.user_basic_info;\n  var isConnected = Boolean(userBasicInfo && userBasicInfo.email);\n  var canUseOauth = newspack_connections_data.can_connect_google;\n\n  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(false),\n      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState3, 2),\n      inFlight = _useState4[0],\n      setInFlight = _useState4[1];\n\n  var handleError = function handleError(res) {\n    return setError(res.message || Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Something went wrong.', 'newspack'));\n  };\n\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useEffect\"])(function () {\n    var params = getURLParams();\n\n    if (canUseOauth && !params.access_token) {\n      setInFlight(true);\n      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({\n        path: '/newspack/v1/oauth/google'\n      }).then(function (res) {\n        setAuthState(res);\n        setInFlight(false);\n      }).catch(handleError);\n    }\n  }, []);\n\n  if (!canUseOauth) {\n    return null;\n  } // Redirect user to Google auth screen.\n\n\n  var goToAuthPage = function goToAuthPage() {\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({\n      path: '/newspack/v1/oauth/google/start'\n    }).then(function (url) {\n      return window.location = url;\n    }).catch(handleError);\n  }; // Redirect user to Google auth screen.\n\n\n  var disconnect = function disconnect() {\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({\n      path: '/newspack/v1/oauth/google/revoke',\n      method: 'DELETE'\n    }).then(function () {\n      setAuthState({});\n      setInFlight(false);\n    }).catch(handleError);\n  };\n\n  var getDescription = function getDescription() {\n    if (inFlight) {\n      return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Loading…', 'newspack');\n    }\n\n    if (isConnected) {\n      return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"sprintf\"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Connected as %s', 'newspack'), userBasicInfo.email);\n    }\n\n    if (!canBeConnected) {\n      return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('First connect to WordPress.com', 'newspack');\n    }\n\n    return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Not connected', 'newspack');\n  };\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_5__[\"ActionCard\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Google', 'newspack'),\n    description: getDescription(),\n    checkbox: isConnected ? 'checked' : 'unchecked',\n    actionText: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_5__[\"Button\"], {\n      isLink: true,\n      isDestructive: isConnected,\n      onClick: isConnected ? disconnect : goToAuthPage,\n      disabled: inFlight || !isConnected && !canBeConnected\n    }, isConnected ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Disconnect', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__[\"__\"])('Connect', 'newspack')),\n    isMedium: true\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (GoogleOAuth);\n\n//# sourceURL=webpack:///./assets/wizards/connections/views/main/google.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/index.js":
/*!********************************************************!*\
  !*** ./assets/wizards/connections/views/main/index.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ \"./node_modules/@babel/runtime/helpers/slicedToArray.js\");\n/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _wpcom__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./wpcom */ \"./assets/wizards/connections/views/main/wpcom.js\");\n/* harmony import */ var _google__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./google */ \"./assets/wizards/connections/views/main/google.js\");\n/* harmony import */ var _fivetran__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./fivetran */ \"./assets/wizards/connections/views/main/fivetran.js\");\n\n\n\n/**\n * WordPress dependencies.\n */\n\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\nvar Main = function Main() {\n  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(),\n      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),\n      error = _useState2[0],\n      setError = _useState2[1];\n\n  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(true),\n      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState3, 2),\n      isResolvingAuth = _useState4[0],\n      setIsResolvingAuth = _useState4[1];\n\n  var _useState5 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useState\"])(),\n      _useState6 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState5, 2),\n      isWPCOMConnected = _useState6[0],\n      setIsWPCOMConnected = _useState6[1];\n\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"useEffect\"])(function () {\n    Object(_google__WEBPACK_IMPORTED_MODULE_4__[\"handleGoogleRedirect\"])({\n      setError: setError\n    }).finally(function () {\n      setIsResolvingAuth(false);\n    });\n  }, []);\n\n  if (isResolvingAuth) {\n    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_2__[\"Waiting\"], {\n      isCenter: true,\n      size: 42\n    });\n  }\n\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"Fragment\"], null, error && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_2__[\"Notice\"], {\n    isError: true,\n    noticeText: error\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wpcom__WEBPACK_IMPORTED_MODULE_3__[\"default\"], {\n    onStatusChange: setIsWPCOMConnected\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_google__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n    setError: setError,\n    canBeConnected: isWPCOMConnected === true\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_fivetran__WEBPACK_IMPORTED_MODULE_5__[\"default\"], {\n    setError: setError,\n    wpComStatus: isWPCOMConnected\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Main);\n\n//# sourceURL=webpack:///./assets/wizards/connections/views/main/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/wpcom.js":
/*!********************************************************!*\
  !*** ./assets/wizards/connections/views/main/wpcom.js ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _support_components_withWPCOMAuth__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../support/components/withWPCOMAuth */ \"./assets/wizards/support/components/withWPCOMAuth.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nvar WPCOMAuth = function WPCOMAuth(_ref) {\n  var onStatusChange = _ref.onStatusChange,\n      shouldAuthenticate = _ref.shouldAuthenticate,\n      isInFlight = _ref.isInFlight,\n      disconnectURL = _ref.disconnectURL,\n      authURL = _ref.authURL;\n  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"useEffect\"])(function () {\n    if (!isInFlight) {\n      onStatusChange(shouldAuthenticate === false);\n    }\n  }, [shouldAuthenticate, isInFlight]);\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"ActionCard\"], {\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('WordPress.com', 'newspack'),\n    description: // eslint-disable-next-line no-nested-ternary\n    isInFlight ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('Loading…', 'newspack') : shouldAuthenticate ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('Not connected', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('Connected', 'newspack'),\n    checkbox: shouldAuthenticate ? 'unchecked' : 'checked',\n    actionText: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_3__[\"Button\"], {\n      isLink: true,\n      isDestructive: !isInFlight && !shouldAuthenticate,\n      href: shouldAuthenticate ? authURL : disconnectURL,\n      disabled: isInFlight\n    }, shouldAuthenticate ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('Connect', 'newspack') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__[\"__\"])('Disconnect', 'newspack')),\n    isMedium: true\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Object(_support_components_withWPCOMAuth__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(null, WPCOMAuth));\n\n//# sourceURL=webpack:///./assets/wizards/connections/views/main/wpcom.js?");

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

/***/ "@wordpress/html-entities":
/*!**************************************!*\
  !*** external ["wp","htmlEntities"] ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"wp\"][\"htmlEntities\"]; }());\n\n//# sourceURL=webpack:///external_%5B%22wp%22,%22htmlEntities%22%5D?");

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

/***/ "moment":
/*!*************************!*\
  !*** external "moment" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"moment\"]; }());\n\n//# sourceURL=webpack:///external_%22moment%22?");

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"React\"]; }());\n\n//# sourceURL=webpack:///external_%22React%22?");

/***/ })

/******/ })));