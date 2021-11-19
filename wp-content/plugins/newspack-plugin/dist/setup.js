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

/***/ "./assets/wizards/setup/style.scss":
/*!*****************************************!*\
  !*** ./assets/wizards/setup/style.scss ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/style.scss?");

/***/ }),

/***/ "./assets/wizards/setup/views/services/style.scss":
/*!********************************************************!*\
  !*** ./assets/wizards/setup/views/services/style.scss ***!
  \********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/services/style.scss?");

/***/ }),

/***/ "./assets/wizards/setup/index.js":
/*!***************************************!*\
  !*** ./assets/wizards/setup/index.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _views___WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./views/ */ \"./assets/wizards/setup/views/index.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/setup/style.scss\");\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n\n\nconst {\n  HashRouter,\n  Route\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__[\"default\"];\nconst ROUTES = [{\n  path: '/',\n  label: __('Welcome', 'newspack'),\n  render: _views___WEBPACK_IMPORTED_MODULE_3__.Welcome\n}, {\n  path: '/settings',\n  label: __('Settings', 'newspack'),\n  subHeaderText: __('Set up your site', 'newspack'),\n  render: _views___WEBPACK_IMPORTED_MODULE_3__.Settings\n}, {\n  path: '/integrations',\n  label: __('Integrations', 'newspack'),\n  subHeaderText: __('Configure core plugins', 'newspack'),\n  render: _views___WEBPACK_IMPORTED_MODULE_3__.Integrations,\n  canProceed: false\n}, {\n  path: '/services',\n  label: __('Services', 'newspack'),\n  subHeaderText: __('Activate extra features'),\n  render: _views___WEBPACK_IMPORTED_MODULE_3__.Services\n}, {\n  path: '/design',\n  label: __('Design', 'newspack'),\n  subHeaderText: __('Customize your site', 'newspack'),\n  render: _views___WEBPACK_IMPORTED_MODULE_3__.Design\n}];\n\nconst SetupWizard = _ref => {\n  let {\n    wizardApiFetch,\n    setError\n  } = _ref;\n  const [routes, setRoutes] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(ROUTES);\n\n  const finishSetup = () => {\n    const params = {\n      path: `/newspack/v1/wizard/newspack-setup-wizard/complete`,\n      method: 'POST',\n      quiet: true\n    };\n    wizardApiFetch(params).then(() => window.location = newspack_urls.dashboard).catch(setError);\n  };\n\n  const sharedProps = {\n    wizardApiFetch,\n    setError,\n    routes\n  };\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(HashRouter, {\n    hashType: \"slash\"\n  }, routes.map((route, index) => {\n    var _routes2;\n\n    const nextRoute = (_routes2 = routes[index + 1]) === null || _routes2 === void 0 ? void 0 : _routes2.path;\n    const buttonAction = nextRoute ? {\n      href: '#' + nextRoute\n    } : {};\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Route, {\n      key: index,\n      path: route.path,\n      exact: route.path === '/',\n      render: () => route.render({ ...sharedProps,\n        headerText: route.label,\n        subHeaderText: route.subHeaderText,\n        buttonText: nextRoute ? route.buttonText || __('Continue') : __('Finish'),\n        buttonAction,\n        buttonDisabled: route.canProceed === false,\n        onSave: nextRoute ? null : finishSetup,\n        updateRoute: update => {\n          setRoutes(_routes => _routes.map((r, i) => i === index ? { ...r,\n            ...update\n          } : r));\n        }\n      })\n    });\n  })));\n};\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizard)(SetupWizard, []), {\n  simpleFooter: true\n}), document.getElementById('newspack-setup-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/index.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/setup/views/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Welcome\": function() { return /* reexport safe */ _welcome__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"Settings\": function() { return /* reexport safe */ _settings__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; },\n/* harmony export */   \"Services\": function() { return /* reexport safe */ _services__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; },\n/* harmony export */   \"Integrations\": function() { return /* reexport safe */ _integrations__WEBPACK_IMPORTED_MODULE_3__[\"default\"]; },\n/* harmony export */   \"Design\": function() { return /* reexport safe */ _site_design_views_main__WEBPACK_IMPORTED_MODULE_4__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _welcome__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./welcome */ \"./assets/wizards/setup/views/welcome/index.js\");\n/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./settings */ \"./assets/wizards/setup/views/settings/index.js\");\n/* harmony import */ var _services__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./services */ \"./assets/wizards/setup/views/services/index.js\");\n/* harmony import */ var _integrations__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./integrations */ \"./assets/wizards/setup/views/integrations/index.js\");\n/* harmony import */ var _site_design_views_main__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../site-design/views/main */ \"./assets/wizards/site-design/views/main/index.js\");\n\n\n\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/index.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/integrations/index.js":
/*!**********************************************************!*\
  !*** ./assets/wizards/setup/views/integrations/index.js ***!
  \**********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../utils */ \"./assets/utils/index.js\");\n\n\n/**\n * WordPress dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n/**\n * Internal dependencies\n */\n\n\n\nconst INTEGRATIONS = {\n  jetpack: {\n    pluginSlug: 'jetpack',\n    editLink: 'admin.php?page=jetpack#/settings',\n    name: 'Jetpack',\n    description: __('The ideal plugin for security, performance, and more', 'newspack'),\n    fetchStatus: () => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: `/newspack/v1/plugins/jetpack`\n    }).then(result => ({\n      jetpack: {\n        status: result.Configured ? result.Status : 'inactive'\n      }\n    }))\n  },\n  'google-site-kit': {\n    pluginSlug: 'google-site-kit',\n    editLink: 'admin.php?page=googlesitekit-splash',\n    name: __('Site Kit by Google', 'newspack'),\n    description: __('Deploy, manage, and get insights from critical Google tools', 'newspack'),\n    fetchStatus: () => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n      path: '/newspack/v1/plugins/google-site-kit'\n    }).then(result => ({\n      'google-site-kit': {\n        status: result.Configured ? result.Status : 'inactive'\n      }\n    }))\n  },\n  mailchimp: {\n    name: 'Mailchimp',\n    description: __('Allow users to sign up to your mailing list', 'newspack'),\n    fetchStatus: () => (0,_utils__WEBPACK_IMPORTED_MODULE_4__.fetchJetpackMailchimpStatus)().then(mailchimp => ({\n      mailchimp\n    })).catch(mailchimp => ({\n      mailchimp\n    })),\n    isOptional: true\n  }\n};\n\nconst intergationConnectButton = integration => {\n  var _integration$error;\n\n  if (integration.pluginSlug) {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Handoff, {\n      plugin: integration.pluginSlug,\n      editLink: integration.editLink,\n      compact: true,\n      isLink: true\n    }, __('Connect', 'newspack'));\n  }\n\n  if (integration.url) {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Button, {\n      isLink: true,\n      href: integration.url,\n      target: \"_blank\"\n    }, __('Connect', 'newspack'));\n  }\n\n  if (!((_integration$error = integration.error) !== null && _integration$error !== void 0 && _integration$error.code) === 'unavailable_site_id') {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"span\", {\n      className: \"i o-80\"\n    }, __('Connect Jetpack in order to configure Mailchimp.'));\n  }\n};\n\nconst Integrations = _ref => {\n  let {\n    setError,\n    updateRoute\n  } = _ref;\n  const [integrations, setIntegrations] = _components_src__WEBPACK_IMPORTED_MODULE_3__.hooks.useObjectState(INTEGRATIONS);\n  const integrationsArray = Object.values(integrations);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    integrationsArray.forEach(async integration => {\n      const update = await integration.fetchStatus().catch(setError);\n      setIntegrations(update);\n    });\n  }, []);\n  const canProceed = integrationsArray.filter(integration => integration.status !== 'active' && !integration.isOptional).length === 0;\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    updateRoute({\n      canProceed\n    });\n  }, [canProceed]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, integrationsArray.map(integration => {\n    const isInactive = integration.status === 'inactive';\n    const isLoading = !integration.status;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      key: integration.name,\n      title: integration.name,\n      description: integration.description,\n      actionText: isInactive ? intergationConnectButton(integration) : null,\n      checkbox: isInactive || isLoading ? 'unchecked' : 'checked',\n      isMedium: true\n    });\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(Integrations));\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/integrations/index.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/services/ReaderRevenue.js":
/*!**************************************************************!*\
  !*** ./assets/wizards/setup/views/services/ReaderRevenue.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! classnames */ \"./node_modules/classnames/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _readerRevenue_views_donation__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../readerRevenue/views/donation */ \"./assets/wizards/readerRevenue/views/donation/index.js\");\n/* harmony import */ var _readerRevenue_views_stripe_setup__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../readerRevenue/views/stripe-setup */ \"./assets/wizards/readerRevenue/views/stripe-setup/index.js\");\n\n\n/**\n * External dependencies\n */\n\n\n/**\n * WordPress dependencies.\n */\n\n\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;\n\n\n\nconst ReaderRevenue = _ref => {\n  var _configuration$platfo;\n\n  let {\n    configuration,\n    onUpdate,\n    className\n  } = _ref;\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: 'newspack/v1/wizard/newspack-reader-revenue-wizard'\n    }).then(response => onUpdate({ ...(0,lodash__WEBPACK_IMPORTED_MODULE_2__.pick)(response, ['donation_data', 'stripe_data', 'platform_data']),\n      hasLoaded: true\n    }));\n  }, []);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: classnames__WEBPACK_IMPORTED_MODULE_1___default()(className, {\n      'o-50': !configuration.hasLoaded\n    })\n  }, ((_configuration$platfo = configuration.platform_data) === null || _configuration$platfo === void 0 ? void 0 : _configuration$platfo.platform) === 'nrh' ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('Looks like this Newspack instance is already configured to use News Revenue Hub as the Reader Revenue platform. To edit these settings, visit the Reader Revenue section from the Newspack dashboard.', 'newspack')) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_readerRevenue_views_donation__WEBPACK_IMPORTED_MODULE_5__.DontationAmounts, {\n    data: configuration.donation_data || {},\n    onChange: donation_data => onUpdate({\n      donation_data\n    })\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h2\", null, __('Payment gateway', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_readerRevenue_views_stripe_setup__WEBPACK_IMPORTED_MODULE_6__.StripeKeysSettings, {\n    data: configuration.stripe_data || {},\n    onChange: stripe_data => onUpdate({\n      stripe_data\n    })\n  })));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (ReaderRevenue);\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/services/ReaderRevenue.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/services/index.js":
/*!******************************************************!*\
  !*** ./assets/wizards/setup/views/services/index.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _ReaderRevenue__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./ReaderRevenue */ \"./assets/wizards/setup/views/services/ReaderRevenue.js\");\n/* harmony import */ var _engagement_views_newsletters__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../engagement/views/newsletters */ \"./assets/wizards/engagement/views/newsletters/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/setup/views/services/style.scss\");\n\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n/**\n * Internal dependencies\n */\n\n\n\n\n\nconst SERVICES_LIST = {\n  'reader-revenue': {\n    label: __('Reader Revenue', 'newspack'),\n    description: __('Encourage site visitors to contribute to your publishing through donations', 'newspack'),\n    Component: _ReaderRevenue__WEBPACK_IMPORTED_MODULE_5__[\"default\"],\n    configuration: {\n      is_service_enabled: false\n    }\n  },\n  newsletters: {\n    label: __('Newsletters', 'newspack'),\n    description: __('Create email newsletters and send them to your Mailchimp mail lists, all without leaving your website', 'newspack'),\n    Component: _engagement_views_newsletters__WEBPACK_IMPORTED_MODULE_6__.NewspackNewsletters,\n    configuration: {\n      is_service_enabled: false\n    }\n  },\n  'google-ad-sense': {\n    label: __('Google AdSense', 'newspack'),\n    description: __('A simple way to place adverts on your news site automatically based on where they best perform', 'newspack'),\n    href: 'admin.php?page=googlesitekit-splash',\n    actionText: __('Configure', 'newspack'),\n    configuration: {\n      is_service_enabled: false\n    }\n  },\n  'google-ad-manager': {\n    label: __('Google Ad Manager', 'newspack'),\n    description: __('An advanced ad inventory creation and management platform, allowing you to be specific about ad placements', 'newspack'),\n    configuration: {\n      is_service_enabled: false\n    }\n  }\n};\n\nconst Services = _ref => {\n  let {\n    renderPrimaryButton\n  } = _ref;\n  const [services, updateServices] = _components_src__WEBPACK_IMPORTED_MODULE_4__.hooks.useObjectState(SERVICES_LIST);\n  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);\n  const slugs = (0,lodash__WEBPACK_IMPORTED_MODULE_1__.keys)(services);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/wizard/newspack-setup-wizard/services'\n    }).then(response => {\n      updateServices(response);\n      setIsLoading(false);\n    });\n  }, []);\n\n  const saveSettings = async () => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n    path: '/newspack/v1/wizard/newspack-setup-wizard/services',\n    method: 'POST',\n    data: (0,lodash__WEBPACK_IMPORTED_MODULE_1__.mapValues)(services, (0,lodash__WEBPACK_IMPORTED_MODULE_1__.property)('configuration'))\n  });\n\n  const adManagerActive = services['google-ad-manager'].configuration.is_service_enabled;\n  const adSenseActive = services['google-ad-sense'].configuration.is_service_enabled;\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,lodash__WEBPACK_IMPORTED_MODULE_1__.values)(services).map((service, i) => {\n    const serviceSlug = slugs[i];\n    const ServiceComponent = service.Component;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ActionCard, {\n      isMedium: true,\n      key: i,\n      title: service.label,\n      description: service.description,\n      className: serviceSlug,\n      toggleChecked: service.configuration.is_service_enabled,\n      hasGreyHeader: service.configuration.is_service_enabled,\n      toggleOnChange: is_service_enabled => updateServices({\n        [serviceSlug]: {\n          configuration: {\n            is_service_enabled\n          }\n        }\n      }),\n      disabled: isLoading || serviceSlug === 'google-ad-manager' && adSenseActive || serviceSlug === 'google-ad-sense' && adManagerActive,\n      href: service.configuration.is_service_enabled && service.href,\n      actionText: service.configuration.is_service_enabled && service.actionText\n    }, service.configuration.is_service_enabled && ServiceComponent ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(ServiceComponent, {\n      className: \"newspack-action-card__region-children__inner\",\n      configuration: service.configuration,\n      onUpdate: configuration => updateServices({\n        [serviceSlug]: {\n          configuration\n        }\n      })\n    }) : null);\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: \"newspack-buttons-card\"\n  }, renderPrimaryButton({\n    onClick: saveSettings\n  })));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizardScreen)(Services, {\n  hidePrimaryButton: true\n}));\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/services/index.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/settings/index.js":
/*!******************************************************!*\
  !*** ./assets/wizards/setup/views/settings/index.js ***!
  \******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * Internal dependencies\n */\n\n\nconst pageTitleTemplate = document.title.replace(newspack_aux_data.site_title, '__SITE_TITLE__');\n/**\n * Settings Setup Screen.\n */\n\nconst Settings = _ref => {\n  let {\n    setError,\n    wizardApiFetch,\n    renderPrimaryButton\n  } = _ref;\n  const [{\n    currencies = [],\n    countries = [],\n    wpseoFields = []\n  }, setOptions] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});\n  const [profileData, updateProfileData] = _components_src__WEBPACK_IMPORTED_MODULE_3__.hooks.useObjectState({});\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    wizardApiFetch({\n      path: '/newspack/v1/profile/',\n      method: 'GET'\n    }).then(response => {\n      setOptions({\n        currencies: response.currencies,\n        countries: response.countries,\n        wpseoFields: response.wpseo_fields\n      });\n      updateProfileData(response.profile);\n    }).catch(setError);\n  }, []);\n\n  const updateProfile = () => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_2___default()({\n    path: '/newspack/v1/profile/',\n    method: 'POST',\n    data: {\n      profile: profileData\n    }\n  });\n\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (typeof profileData.site_title === 'string') {\n      document.title = pageTitleTemplate.replace('__SITE_TITLE__', profileData.site_title);\n    }\n  }, [profileData.site_title]);\n\n  const renderSetting = _ref2 => {\n    let {\n      options,\n      label,\n      key,\n      type,\n      placeholder,\n      className\n    } = _ref2;\n\n    if (options) {\n      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n        label: label,\n        value: profileData[key],\n        onChange: updateProfileData(key),\n        options: options,\n        className: className\n      });\n    }\n\n    if (type === 'image') {\n      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ImageUpload, {\n        label: label,\n        style: {\n          width: '136px',\n          height: '136px'\n        },\n        image: profileData[key],\n        info: __('The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least 512 pixels wide and tall.', 'newspack'),\n        isCovering: true,\n        onChange: updateProfileData(key)\n      });\n    }\n\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n      label: label,\n      value: profileData[key] || '',\n      onChange: updateProfileData(key),\n      placeholder: placeholder,\n      className: className\n    });\n  };\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SectionHeader, {\n    title: __('Site profile', 'newspack'),\n    description: __('Add and manage the basic information', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Grid, {\n    columns: 3,\n    gutter: 32,\n    className: \"newspack-site-profile\"\n  }, renderSetting({\n    key: 'site_icon',\n    label: __('Site Icon', 'newspack'),\n    type: 'image'\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Card, {\n    noBorder: true\n  }, renderSetting({\n    key: 'site_title',\n    label: __('Site Title', 'newspack')\n  }), renderSetting({\n    key: 'tagline',\n    label: __('Tagline', 'newspack')\n  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Card, {\n    noBorder: true\n  }, renderSetting({\n    options: countries,\n    key: 'countrystate',\n    label: __('Country', 'newspack')\n  }), renderSetting({\n    options: currencies,\n    key: 'currency',\n    label: __('Currency')\n  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SectionHeader, {\n    title: __('Social accounts', 'newspack'),\n    description: __('Allow visitors to quickly access your social profiles', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Grid, {\n    columns: 3,\n    gutter: 32,\n    rowGap: 16\n  }, wpseoFields.map(seoField => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, {\n    key: seoField.key\n  }, renderSetting({ ...seoField\n  })))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: \"newspack-buttons-card\"\n  }, renderPrimaryButton({\n    onClick: updateProfile\n  })));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(Settings, {\n  hidePrimaryButton: true\n}));\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/settings/index.js?");

/***/ }),

/***/ "./assets/wizards/setup/views/welcome/index.js":
/*!*****************************************************!*\
  !*** ./assets/wizards/setup/views/welcome/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ \"@wordpress/api-fetch\");\n/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/icon/index.js\");\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/info.js\");\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/check.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n\n\n/**\n * External dependencies.\n */\n\n/**\n * WordPress dependencies\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\nconst {\n  useHistory\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__[\"default\"];\nconst POST_COUNT = newspack_aux_data.is_e2e ? 12 : 40;\nconst STARTER_CONTENT_REQUEST_COUNT = POST_COUNT + 3;\nconst ERROR_TYPES = {\n  plugin_configuration: {\n    message: __('Installation', 'newspack')\n  },\n  starter_content: {\n    message: __('Demo content', 'newspack')\n  }\n};\n\nconst starterContentFetch = endpoint => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n  path: `/newspack/v1/wizard/newspack-setup-wizard/starter-content/${endpoint}`,\n  method: 'post'\n});\n\nconst Welcome = _ref => {\n  let {\n    buttonAction\n  } = _ref;\n  const [installationProgress, setInstallationProgress] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);\n  const [softwareInfo, setSoftwareInfo] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);\n  const [isSSL, setIsSSL] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);\n  const [shouldInstallStarterContent, setShouldInstallStarterContent] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(true);\n  const [errors, setErrors] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);\n\n  const addError = errorInfo => error => setErrors(_errors => [..._errors, { ...errorInfo,\n    error\n  }]);\n\n  const total = (shouldInstallStarterContent ? STARTER_CONTENT_REQUEST_COUNT : 0) + softwareInfo.length;\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    document.body.classList.add('newspack_page_newspack-setup-wizard__welcome');\n    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n      path: '/newspack/v1/wizard/newspack-setup-wizard/initial-check/'\n    }).then(res => {\n      setSoftwareInfo(res.plugins);\n      setIsSSL(res.is_ssl);\n    });\n    return () => document.body.classList.remove('newspack_page_newspack-setup-wizard__welcome');\n  }, []);\n\n  const increment = () => setInstallationProgress(progress => progress + 1);\n\n  const install = async () => {\n    // Reset state.\n    setErrors([]);\n    setInstallationProgress(0); // Wait 1ms to avoid an immediate \"done\" state if there's no need to install anything.\n\n    await new Promise(resolve => setTimeout(resolve, 1)); // Plugins and theme.\n\n    const softwarePromises = softwareInfo.map(item => {\n      if (item.Status === 'active') {\n        increment();\n        return () => Promise.resolve();\n      }\n\n      return () => _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({\n        path: `/newspack/v1/plugins/${item.Slug}/configure/`,\n        method: 'POST'\n      }).then(increment).catch(addError({\n        info: ERROR_TYPES.plugin_configuration,\n        item: `${__('Failed to install', 'newspack')} ${item.Name}`\n      }));\n    });\n\n    for (let i = 0; i < softwarePromises.length; i++) {\n      await softwarePromises[i]();\n    }\n\n    if (shouldInstallStarterContent) {\n      // Starter content.\n      await starterContentFetch(`categories`).then(increment).catch(addError({\n        info: ERROR_TYPES.starter_content,\n        item: __('Failed to create the categories.', 'newspack')\n      }));\n      await Promise.allSettled((0,lodash__WEBPACK_IMPORTED_MODULE_1__.times)(POST_COUNT, n => starterContentFetch(`post/${n}`).then(increment).catch(addError({\n        info: ERROR_TYPES.starter_content,\n        item: __('Failed to create a post.', 'newspack')\n      }))));\n      await starterContentFetch(`homepage`).then(increment).catch(addError({\n        info: ERROR_TYPES.starter_content,\n        item: __('Failed to create the homepage.', 'newspack')\n      }));\n      await starterContentFetch(`theme`).then(increment).catch(addError({\n        info: ERROR_TYPES.starter_content,\n        item: __('Failed to activate the theme.', 'newspack')\n      }));\n    }\n  };\n\n  const history = useHistory();\n  const nextRouteAddress = buttonAction.href;\n  const hasErrors = errors.length > 0;\n  const isInit = installationProgress === 0;\n  const isDone = installationProgress === total;\n  const redirectCounterRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)();\n  const REDIRECT_COUNTER_DURATION = 5;\n  const [redirectCounter, setRedirectCounter] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(REDIRECT_COUNTER_DURATION);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (redirectCounter === 0) {\n      clearInterval(redirectCounterRef.current);\n      history.push(nextRouteAddress.replace('#', ''));\n    }\n  }, [redirectCounter]);\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (isDone && redirectCounter === REDIRECT_COUNTER_DURATION) {\n      // Trigger redirect countdown.\n      redirectCounterRef.current = setInterval(() => {\n        setRedirectCounter(counter => counter - 1);\n      }, 1000);\n    }\n  }, [isDone, redirectCounter]);\n\n  const getHeadingText = () => {\n    if (hasErrors) {\n      return __('Installation error', 'newspack');\n    }\n\n    if (isInit) {\n      return __('Welcome to WordPress for your Newsroom!', 'newspack');\n    }\n\n    if (isDone) {\n      return __('Installation complete', 'newspack');\n    }\n\n    return __('Installing…', 'newspack');\n  };\n\n  const getInfoText = () => {\n    if (hasErrors) {\n      return __('There has been an error during the installation. Please retry or manually install required plugins to continue with the configuration of your Newspack site.', 'newspack');\n    }\n\n    if (isInit) {\n      return __('We will help you get set up by installing the most relevant plugins first before requiring a few details from you in order to build your Newspack site.', 'newspack');\n    }\n\n    if (isDone) {\n      return __('Click the button below to start configuring your Newspack site.', 'newspack');\n    }\n\n    if (shouldInstallStarterContent) {\n      return __('We are now installing core plugins and pre-populating your site with categories and placeholder stories to help you pre-configure it. All placeholder content can be deleted later.', 'newspack');\n    }\n\n    return __('We are now installing core plugins.', 'newspack');\n  };\n\n  const getHeadingIcon = () => {\n    if (hasErrors) {\n      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_icons__WEBPACK_IMPORTED_MODULE_6__[\"default\"], {\n        className: \"newspack--error\",\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"default\"]\n      });\n    }\n\n    if (isDone) {\n      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_icons__WEBPACK_IMPORTED_MODULE_6__[\"default\"], {\n        className: \"newspack--success\",\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_8__[\"default\"]\n      });\n    }\n  };\n\n  const renderErrorBox = (error, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ActionCard, {\n    isSmall: true,\n    key: i,\n    title: error.info.message + ': ' + error.item,\n    actionText: __('Retry', 'newspack'),\n    onClick: install\n  });\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: \"newspack-logo__wrapper\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.NewspackLogo, {\n    centered: true,\n    height: 72\n  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Card, {\n    isMedium: true,\n    className: errors.length === 0 && installationProgress > 0 && !isDone ? 'loading' : null\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h1\", null, getHeadingIcon(), getHeadingText()), errors.length === 0 && installationProgress > 0 ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ProgressBar, {\n    completed: installationProgress,\n    total: total\n  }) : null, isSSL === false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n    isError: true,\n    noticeText: __(\"This site does not use HTTPS. Newspack can't be installed.\", 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, getInfoText(), isDone && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"br\", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"i\", null, __('Automatic redirection in', 'newspack'), \" \", redirectCounter, ' ', __('seconds…', 'newspack')))), errors.length ? errors.map(renderErrorBox) : null, (isInit || isDone) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Card, {\n    noBorder: true,\n    className: \"newspack-card__footer\"\n  }, isInit ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.CheckboxControl, {\n    checked: shouldInstallStarterContent,\n    label: __('Install demo content', 'newspack'),\n    onChange: setShouldInstallStarterContent\n  }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Button, {\n    disabled: !isSSL,\n    isPrimary: true,\n    onClick: isInit ? install : null,\n    href: isDone ? nextRouteAddress : null\n  }, isInit ? __('Get Started', 'newspack') : __('Continue', 'newspack'))))));\n};\n\nconst WelcomeWizardScreen = (0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizardScreen)(Welcome); // eslint-disable-next-line react/display-name\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (props => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(WelcomeWizardScreen, (0,lodash__WEBPACK_IMPORTED_MODULE_1__.omit)(props, ['routes', 'headerText', 'buttonText'])));\n\n//# sourceURL=webpack://newspack/./assets/wizards/setup/views/welcome/index.js?");

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
/******/ 			"setup": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/setup/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;