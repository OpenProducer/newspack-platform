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

/***/ "./assets/wizards/connections/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/connections/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./views */ \"./assets/wizards/connections/views/index.js\");\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"];\nconst MainScreen = (0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(_views__WEBPACK_IMPORTED_MODULE_5__.Main);\n\nconst ConnectionsWizard = _ref => {\n  let {\n    pluginRequirements,\n    wizardApiFetch,\n    startLoading,\n    doneLoading\n  } = _ref;\n  const wizardScreenProps = {\n    headerText: __('Connections', 'newspack'),\n    subHeaderText: __('Connections to third-party services', 'newspack'),\n    wizardApiFetch,\n    startLoading,\n    doneLoading\n  };\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(HashRouter, {\n    hashType: \"slash\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Switch, null, pluginRequirements, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Route, {\n    exact: true,\n    path: \"/\",\n    render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(MainScreen, wizardScreenProps)\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Redirect, {\n    to: \"/\"\n  })));\n};\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizard)(ConnectionsWizard)), document.getElementById('newspack-connections-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/index.js":
/*!***************************************************!*\
  !*** ./assets/wizards/connections/views/index.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Main\": function() { return /* reexport safe */ _main__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _main__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main */ \"./assets/wizards/connections/views/main/index.js\");\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/fivetran.js":
/*!***********************************************************!*\
  !*** ./assets/wizards/connections/views/main/fivetran.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"handleFivetranRedirect\": function() { return /* binding */ handleFivetranRedirect; }\n/* harmony export */ });\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_5__);\n\n\n/* global newspack_connections_data */\n\n/**\n * WordPress dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * External dependencies\n */\n\n\n\nconst handleFivetranRedirect = (response, _ref) => {\n  let {\n    wizardApiFetch,\n    startLoading,\n    doneLoading\n  } = _ref;\n  const params = (0,qs__WEBPACK_IMPORTED_MODULE_4__.parse)(window.location.search.replace(/^\\?/, '')); // 'id' param will be appended by the redirect from a Fivetran connect card.\n\n  if (params.id) {\n    startLoading();\n    const newConnector = (0,lodash__WEBPACK_IMPORTED_MODULE_5__.find)((0,lodash__WEBPACK_IMPORTED_MODULE_5__.values)(response.fivetran), ['id', params.id]);\n\n    const removeIdParamFromURL = () => {\n      // Remove the 'id' param.\n      params.id = undefined;\n      window.location.search = (0,qs__WEBPACK_IMPORTED_MODULE_4__.stringify)(params);\n    };\n\n    if (newConnector) {\n      if (newConnector.sync_state === 'paused') {\n        wizardApiFetch({\n          path: '/newspack/v1/oauth/fivetran?connector_id=' + newConnector.id,\n          method: 'POST',\n          data: {\n            paused: false\n          }\n        }).then(removeIdParamFromURL);\n      } else {\n        removeIdParamFromURL();\n      }\n    }\n\n    doneLoading();\n  }\n};\nconst CONNECTORS = [{\n  service: 'google_analytics',\n  label: __('Google Analytics', 'newspack')\n}, {\n  service: 'mailchimp',\n  label: __('Mailchimp', 'newspack')\n}, {\n  service: 'stripe',\n  label: __('Stripe', 'newspack')\n}, {\n  service: 'double_click_publishers',\n  label: __('Google Ad Manager', 'newspack')\n}, {\n  service: 'facebook_pages',\n  label: __('Facebook Pages', 'newspack')\n}];\n\nconst FivetranConnection = _ref2 => {\n  let {\n    wpComStatus,\n    setError\n  } = _ref2;\n  const [connections, setConnections] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)();\n  const [inFlight, setInFlight] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);\n  const hasFetched = connections !== undefined;\n  const canBeConnected = wpComStatus === true;\n  const canUseFivetran = newspack_connections_data.can_connect_fivetran;\n\n  const handleError = err => {\n    if (err.message) {\n      setError(err.message);\n    }\n\n    setInFlight(false);\n  };\n\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (!canUseFivetran || !canBeConnected) {\n      return;\n    }\n\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: '/newspack/v1/oauth/fivetran'\n    }).then(res => {\n      setConnections(res);\n      setInFlight(false);\n    }).catch(handleError);\n  }, [canUseFivetran, canBeConnected]);\n\n  if (!canUseFivetran) {\n    return null;\n  }\n\n  const createConnection = _ref3 => {\n    let {\n      service\n    } = _ref3;\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: `/newspack/v1/oauth/fivetran/${service}`,\n      method: 'POST',\n      data: {\n        service\n      }\n    }).then(_ref4 => {\n      let {\n        url\n      } = _ref4;\n      return window.location = url;\n    }).catch(handleError);\n  };\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h1\", null, __('Fivetran', 'newspack')), wpComStatus === false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Notice, {\n    isWarning: true\n  }, __('Connect your WordPress.com account first.', 'newspack')), CONNECTORS.map(item => {\n    const setupState = (0,lodash__WEBPACK_IMPORTED_MODULE_5__.get)(connections, [item.service, 'setup_state']);\n    const syncState = (0,lodash__WEBPACK_IMPORTED_MODULE_5__.get)(connections, [item.service, 'sync_state']);\n    const status = {\n      // eslint-disable-next-line no-nested-ternary\n      label: setupState ? `${setupState}, ${syncState}` : hasFetched ? __('Not connected', 'newspack') : '-',\n      isConnected: setupState === 'connected'\n    };\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n      key: item.service\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      title: item.label,\n      description: `${__('Status:', 'newspack')} ${status.label}`,\n      actionText: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Button, {\n        disabled: inFlight || !hasFetched || !canBeConnected,\n        onClick: () => createConnection(item),\n        isLink: true\n      }, status.isConnected ? __('Re-connect', 'newspack') : __('Connect', 'newspack')),\n      checkbox: status.isConnected ? 'checked' : 'unchecked',\n      isMedium: true\n    }));\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (FivetranConnection);\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/main/fivetran.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/google.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/connections/views/main/google.js ***!
  \*********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"handleGoogleRedirect\": function() { return /* binding */ handleGoogleRedirect; }\n/* harmony export */ });\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/* global newspack_connections_data */\n\n/**\n * External dependencies.\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n/**\n * Internal dependencies.\n */\n\n\n\nconst getURLParams = () => {\n  return qs__WEBPACK_IMPORTED_MODULE_1___default().parse(window.location.search.replace(/^\\?/, ''));\n};\n\nconst handleGoogleRedirect = _ref => {\n  let {\n    setError\n  } = _ref;\n  const params = getURLParams();\n\n  if (params.access_token) {\n    return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/oauth/google/finish',\n      method: 'POST',\n      data: {\n        access_token: params.access_token,\n        refresh_token: params.refresh_token,\n        csrf_token: params.csrf_token,\n        expires_at: params.expires_at\n      }\n    }).then(() => {\n      params.access_token = undefined;\n      params.refresh_token = undefined;\n      params.csrf_token = undefined;\n      params.expires_at = undefined;\n      window.location.search = qs__WEBPACK_IMPORTED_MODULE_1___default().stringify(params);\n    }).catch(e => {\n      setError(e.message || __('Something went wrong during authentication with Google.', 'newspack'));\n    });\n  }\n\n  return Promise.resolve();\n};\n\nconst GoogleOAuth = _ref2 => {\n  let {\n    setError,\n    canBeConnected\n  } = _ref2;\n  const [authState, setAuthState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});\n  const userBasicInfo = authState.user_basic_info;\n  const canUseOauth = newspack_connections_data.can_connect_google;\n  const [inFlight, setInFlight] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);\n\n  const handleError = res => setError(res.message || __('Something went wrong.', 'newspack'));\n\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    const params = getURLParams();\n\n    if (canUseOauth && !params.access_token) {\n      setInFlight(true);\n      _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n        path: '/newspack/v1/oauth/google'\n      }).then(setAuthState).catch(handleError).finally(() => setInFlight(false));\n    }\n  }, []);\n\n  if (!canUseOauth) {\n    return null;\n  }\n\n  const isConnected = Boolean(userBasicInfo && userBasicInfo.email); // Redirect user to Google auth screen.\n\n  const goToAuthPage = () => {\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/oauth/google/start'\n    }).then(url => window.location = url).catch(handleError);\n  }; // Redirect user to Google auth screen.\n\n\n  const disconnect = () => {\n    setInFlight(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/oauth/google/revoke',\n      method: 'DELETE'\n    }).then(() => {\n      setAuthState({});\n      setInFlight(false);\n    }).catch(handleError);\n  };\n\n  const getDescription = () => {\n    if (inFlight) {\n      return __('Loading…', 'newspack');\n    }\n\n    if (isConnected) {\n      // Translators: user connection status message.\n      return (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.sprintf)(__('Connected as %s', 'newspack'), userBasicInfo.email);\n    }\n\n    if (!canBeConnected) {\n      return __('First connect to WordPress.com', 'newspack');\n    }\n\n    return __('Not connected', 'newspack');\n  };\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ActionCard, {\n    title: __('Google', 'newspack'),\n    description: getDescription(),\n    checkbox: isConnected ? 'checked' : 'unchecked',\n    actionText: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Button, {\n      isLink: true,\n      isDestructive: isConnected,\n      onClick: isConnected ? disconnect : goToAuthPage,\n      disabled: inFlight || !isConnected && !canBeConnected\n    }, isConnected ? __('Disconnect', 'newspack') : __('Connect', 'newspack')),\n    isMedium: true\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (GoogleOAuth);\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/main/google.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/index.js":
/*!********************************************************!*\
  !*** ./assets/wizards/connections/views/main/index.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _wpcom__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./wpcom */ \"./assets/wizards/connections/views/main/wpcom.js\");\n/* harmony import */ var _google__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./google */ \"./assets/wizards/connections/views/main/google.js\");\n/* harmony import */ var _mailchimp__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./mailchimp */ \"./assets/wizards/connections/views/main/mailchimp.js\");\n/* harmony import */ var _fivetran__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./fivetran */ \"./assets/wizards/connections/views/main/fivetran.js\");\n\n\n/**\n * WordPress dependencies.\n */\n\n/**\n * Internal dependencies\n */\n\n\n\n\n\n\n\nconst Main = () => {\n  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)();\n  const [isResolvingAuth, setIsResolvingAuth] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);\n  const [isWPCOMConnected, setIsWPCOMConnected] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)();\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    (0,_google__WEBPACK_IMPORTED_MODULE_3__.handleGoogleRedirect)({\n      setError\n    }).finally(() => {\n      setIsResolvingAuth(false);\n    });\n  }, []);\n\n  if (isResolvingAuth) {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_1__.Waiting, {\n      isCenter: true\n    });\n  }\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, error && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_1__.Notice, {\n    isError: true,\n    noticeText: error\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wpcom__WEBPACK_IMPORTED_MODULE_2__[\"default\"], {\n    onStatusChange: setIsWPCOMConnected\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_google__WEBPACK_IMPORTED_MODULE_3__[\"default\"], {\n    setError: setError,\n    canBeConnected: isWPCOMConnected === true\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_mailchimp__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n    setError: setError\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_fivetran__WEBPACK_IMPORTED_MODULE_5__[\"default\"], {\n    setError: setError,\n    wpComStatus: isWPCOMConnected\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Main);\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/main/index.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/mailchimp.js":
/*!************************************************************!*\
  !*** ./assets/wizards/connections/views/main/mailchimp.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/keycodes */ \"@wordpress/keycodes\");\n/* harmony import */ var _wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * WordPress dependencies.\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nconst Mailchimp = _ref => {\n  let {\n    setError\n  } = _ref;\n  const [authState, setAuthState] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});\n  const [isModalOpen, setisModalOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);\n  const [apiKey, setAPIKey] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)();\n  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);\n  const modalTextRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)(null);\n  const isConnected = Boolean(authState && authState.username);\n\n  const handleError = res => setError(res.message || __('Something went wrong.', 'newspack'));\n\n  const openModal = () => setisModalOpen(true);\n\n  const closeModal = () => {\n    setisModalOpen(false);\n    setAPIKey();\n  }; // Check the Mailchimp connectivity status.\n\n\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    setIsLoading(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: '/newspack/v1/oauth/mailchimp'\n    }).then(res => {\n      setAuthState(res);\n    }).catch(handleError).finally(() => setIsLoading(false));\n  }, []);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (isModalOpen) {\n      modalTextRef.current.querySelector('input').focus();\n    }\n  }, [isModalOpen]);\n\n  const submitAPIKey = () => {\n    setError();\n    setIsLoading(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: '/newspack/v1/oauth/mailchimp',\n      method: 'POST',\n      data: {\n        api_key: apiKey\n      }\n    }).then(res => {\n      setAuthState(res);\n    }).catch(e => {\n      setError(e.message || __('Something went wrong during verification of your Mailchimp API key.', 'newspack'));\n    }).finally(() => {\n      setIsLoading(false);\n      closeModal();\n    });\n  };\n\n  const disconnect = () => {\n    setIsLoading(true);\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: '/newspack/v1/oauth/mailchimp',\n      method: 'DELETE'\n    }).then(() => {\n      setAuthState({});\n      setIsLoading(false);\n    }).catch(handleError);\n  };\n\n  const getDescription = () => {\n    if (isLoading) {\n      return __('Loading…', 'newspack');\n    }\n\n    if (isConnected) {\n      // Translators: user connection status message.\n      return (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.sprintf)(__('Connected as %s', 'newspack'), authState.username);\n    }\n\n    return __('Not connected', 'newspack');\n  };\n\n  const getModalButtonText = () => {\n    if (isLoading) {\n      return __('Connecting…', 'newspack');\n    }\n\n    if (isConnected) {\n      return __('Connected', 'newspack');\n    }\n\n    return __('Connect', 'newspack');\n  };\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.ActionCard, {\n    title: \"Mailchimp\",\n    description: getDescription(),\n    checkbox: isConnected ? 'checked' : 'unchecked',\n    actionText: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n      isLink: true,\n      isDestructive: isConnected,\n      onClick: isConnected ? disconnect : openModal,\n      disabled: isLoading\n    }, isConnected ? __('Disconnect', 'newspack') : __('Connect', 'newspack')),\n    isMedium: true\n  }), isModalOpen && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Modal, {\n    title: __('Add Mailchimp API Key', 'newspack'),\n    onRequestClose: closeModal\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    ref: modalTextRef\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Grid, {\n    columns: 1,\n    gutter: \"0\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n    placeholder: \"123457103961b1f4dc0b2b2fd59c137b-us1\",\n    label: __('Mailchimp API Key', 'newspack'),\n    hideLabelFromVision: true,\n    value: apiKey,\n    onChange: setAPIKey,\n    onKeyDown: event => {\n      if (_wordpress_keycodes__WEBPACK_IMPORTED_MODULE_3__.ENTER === event.keyCode && '' !== apiKey) {\n        event.preventDefault();\n        submitAPIKey();\n      }\n    }\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Card, {\n    noBorder: true\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ExternalLink, {\n    href: \"https://us1.admin.mailchimp.com/account/api/\"\n  }, __('Generate Mailchimp API key', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"span\", {\n    className: \"sep sep__inline\"\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ExternalLink, {\n    href: \"https://mailchimp.com/help/about-api-keys/\"\n  }, __('About Mailchimp API keys', 'newspack'))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Card, {\n    buttonsCard: true,\n    noBorder: true,\n    className: \"justify-end\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n    isSecondary: true,\n    onClick: closeModal\n  }, __('Cancel', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n    isPrimary: true,\n    disabled: !apiKey,\n    onClick: submitAPIKey\n  }, getModalButtonText()))));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Mailchimp);\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/main/mailchimp.js?");

/***/ }),

/***/ "./assets/wizards/connections/views/main/wpcom.js":
/*!********************************************************!*\
  !*** ./assets/wizards/connections/views/main/wpcom.js ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _support_components_withWPCOMAuth__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../support/components/withWPCOMAuth */ \"./assets/wizards/support/components/withWPCOMAuth.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/* global newspack_connections_data */\n\n/**\n * WordPress dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nconst WPCOMAuth = _ref => {\n  let {\n    onStatusChange,\n    shouldAuthenticate,\n    isInFlight,\n    disconnectURL,\n    authURL\n  } = _ref;\n  const canUseWPCOM = newspack_connections_data.can_connect_wpcom;\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (!isInFlight) {\n      onStatusChange(shouldAuthenticate === false);\n    }\n  }, [shouldAuthenticate, isInFlight]);\n\n  if (!canUseWPCOM) {\n    return null;\n  }\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n    title: __('WordPress.com', 'newspack'),\n    description: // eslint-disable-next-line no-nested-ternary\n    isInFlight ? __('Loading…', 'newspack') : shouldAuthenticate ? __('Not connected', 'newspack') : __('Connected', 'newspack'),\n    checkbox: shouldAuthenticate ? 'unchecked' : 'checked',\n    actionText: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Button, {\n      isLink: true,\n      isDestructive: !isInFlight && !shouldAuthenticate,\n      href: shouldAuthenticate ? authURL : disconnectURL,\n      disabled: isInFlight\n    }, shouldAuthenticate ? __('Connect', 'newspack') : __('Disconnect', 'newspack')),\n    isMedium: true\n  });\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_support_components_withWPCOMAuth__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(null, WPCOMAuth));\n\n//# sourceURL=webpack://newspack/./assets/wizards/connections/views/main/wpcom.js?");

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
/******/ 			"connections": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/connections/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;