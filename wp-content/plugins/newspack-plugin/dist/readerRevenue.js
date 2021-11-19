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

/***/ "./assets/wizards/readerRevenue/constants.js":
/*!***************************************************!*\
  !*** ./assets/wizards/readerRevenue/constants.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"NRH\": function() { return /* binding */ NRH; },\n/* harmony export */   \"NEWSPACK\": function() { return /* binding */ NEWSPACK; },\n/* harmony export */   \"STRIPE\": function() { return /* binding */ STRIPE; }\n/* harmony export */ });\n/**\n * Reader Revenue constants.\n */\nconst NRH = 'nrh';\nconst NEWSPACK = 'wc';\nconst STRIPE = 'stripe';\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/constants.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/index.js":
/*!***********************************************!*\
  !*** ./assets/wizards/readerRevenue/index.js ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/esm/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/url */ \"@wordpress/url\");\n/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./views */ \"./assets/wizards/readerRevenue/views/index.js\");\n/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./constants */ \"./assets/wizards/readerRevenue/constants.js\");\n\n\n\n/**\n * Reader Revenue\n */\n\n/**\n * External dependencies.\n */\n\n\n/**\n * WordPress dependencies.\n */\n\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__.__;\n\n/**\n * Internal dependencies.\n */\n\n\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_8__[\"default\"];\n\nconst headerText = __('Reader revenue', 'newspack');\n\nconst subHeaderText = __('Generate revenue from your customers.', 'newspack');\n\nclass ReaderRevenueWizard extends _wordpress_element__WEBPACK_IMPORTED_MODULE_4__.Component {\n  /**\n   * Constructor.\n   */\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"onWizardReady\", () => this.fetch());\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"handleDataUpdate\", data => {\n      this.setState({\n        errorMessages: data.donation_data.errors,\n        data: {\n          locationData: data.location_data,\n          stripeData: data.stripe_data,\n          donationData: data.donation_data,\n          countryStateFields: data.country_state_fields,\n          currencyFields: data.currency_fields,\n          donationPage: data.donation_page,\n          salesforceData: data.salesforce_settings,\n          platformData: data.platform_data,\n          pluginStatus: data.plugin_status,\n          isSSL: data.is_ssl\n        }\n      }, () => {\n        this.props.setError();\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"fetch\", () => {\n      const {\n        setError,\n        wizardApiFetch\n      } = this.props;\n      return wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-reader-revenue-wizard'\n      }).then(this.handleDataUpdate).catch(setError);\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"update\", (screen, data) => {\n      const {\n        setError,\n        wizardApiFetch\n      } = this.props;\n      return wizardApiFetch({\n        path: '/newspack/v1/wizard/newspack-reader-revenue-wizard/' + screen,\n        method: 'POST',\n        data\n      }).then(this.handleDataUpdate).catch(setError);\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"handleSalesforce\", async () => {\n      const {\n        wizardApiFetch\n      } = this.props;\n      const {\n        data\n      } = this.state;\n      const {\n        salesforceData\n      } = data;\n      const salesforceIsConnected = !!salesforceData.refresh_token; // If Salesforce is already connected, button should reset settings.\n\n      if (salesforceIsConnected) {\n        const defaultSettings = {\n          client_id: '',\n          client_secret: '',\n          access_token: '',\n          refresh_token: '',\n          instance_url: ''\n        };\n        this.setState({\n          data: { ...data,\n            salesforceData: defaultSettings\n          }\n        });\n        return this.update('salesforce', defaultSettings);\n      } // Otherwise, attempt to establish a connection with Salesforce.\n\n\n      const {\n        client_id,\n        client_secret\n      } = salesforceData;\n\n      if (client_id && client_secret) {\n        const loginUrl = (0,_wordpress_url__WEBPACK_IMPORTED_MODULE_6__.addQueryArgs)('https://login.salesforce.com/services/oauth2/authorize', {\n          response_type: 'code',\n          client_id: encodeURIComponent(client_id),\n          client_secret: encodeURIComponent(client_secret),\n          redirect_uri: encodeURI(window.location.href)\n        }); // Save credentials to options table.\n\n        await this.update('salesforce', salesforceData); // Validate credentials before redirecting.\n\n        const valid = await wizardApiFetch({\n          path: '/newspack/v1/wizard/salesforce/validate',\n          method: 'POST',\n          data: {\n            client_id,\n            client_secret,\n            redirect_uri: window.location.href\n          }\n        });\n\n        if (valid) {\n          return window.location.assign(loginUrl);\n        }\n\n        this.setState({\n          data: { ...data,\n            salesforceData: { ...salesforceData,\n              error: 'invalid_credentials'\n            }\n          }\n        });\n      }\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__[\"default\"])(this, \"navigationForPlatform\", (platform, data) => {\n      const platformField = {\n        label: __('Platform', 'newspack'),\n        path: '/',\n        exact: true\n      };\n\n      if (!platform) {\n        return [platformField];\n      }\n\n      const donationField = {\n        label: __('Donations', 'newspack'),\n        path: '/donations',\n        exact: true\n      };\n\n      if (_constants__WEBPACK_IMPORTED_MODULE_10__.NEWSPACK === platform) {\n        const {\n          pluginStatus\n        } = data;\n\n        if (!pluginStatus) {\n          return [];\n        }\n\n        return [donationField, {\n          label: __('Stripe Gateway', 'newspack'),\n          path: '/stripe-setup'\n        }, {\n          label: __('Salesforce', 'newspack'),\n          path: '/salesforce',\n          exact: true\n        }, {\n          label: __('Address', 'newspack'),\n          path: '/location-setup'\n        }, platformField];\n      } else if (_constants__WEBPACK_IMPORTED_MODULE_10__.NRH === platform) {\n        return [donationField, {\n          label: __('NRH Settings', 'newspack'),\n          path: '/settings',\n          exact: true\n        }, platformField];\n      } else if (_constants__WEBPACK_IMPORTED_MODULE_10__.STRIPE === platform) {\n        return [donationField, {\n          label: __('Stripe Settings', 'newspack'),\n          path: '/stripe-setup'\n        }, {\n          label: __('Emails', 'newspack'),\n          path: '/emails'\n        }, platformField];\n      }\n\n      return [];\n    });\n\n    this.state = {\n      errorMessages: [],\n      data: {\n        locationData: {},\n        stripeData: {},\n        donationData: {},\n        salesforceData: {},\n        platformData: {},\n        pluginStatus: false\n      }\n    };\n  }\n  /**\n   * wizardReady will be called when all plugin requirements are met.\n   */\n\n\n  /**\n   * Render\n   */\n  render() {\n    const {\n      pluginRequirements,\n      wizardApiFetch\n    } = this.props;\n    const {\n      data,\n      errorMessages\n    } = this.state;\n    const {\n      countryStateFields,\n      currencyFields,\n      locationData,\n      stripeData,\n      donationData,\n      donationPage,\n      salesforceData,\n      platformData,\n      pluginStatus\n    } = data;\n    const {\n      platform\n    } = platformData;\n    const salesforceIsConnected = !!salesforceData.refresh_token;\n    const tabbedNavigation = this.navigationForPlatform(platform, data);\n    const sharedProps = {\n      headerText,\n      subHeaderText,\n      tabbedNavigation\n    };\n\n    if (errorMessages) {\n      sharedProps.renderAboveContent = () => (0,lodash__WEBPACK_IMPORTED_MODULE_3__.values)(errorMessages).map((error, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_7__.Notice, {\n        key: i,\n        isError: true,\n        noticeText: error\n      }));\n    }\n\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(HashRouter, {\n      hashType: \"slash\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Switch, null, pluginRequirements, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.Platform, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        data: { ...platformData,\n          stripeData\n        },\n        pluginStatus: pluginStatus,\n        onChange: _platformData => this.update('', _platformData),\n        onReady: () => {\n          this.setState({\n            data: { ...data,\n              pluginStatus: true\n            }\n          });\n        }\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/settings\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.NRHSettings, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        data: platformData,\n        buttonText: __('Update', 'newspack'),\n        buttonAction: () => this.update('', platformData),\n        onChange: _platformData => this.setState({\n          data: { ...data,\n            platformData: _platformData\n          }\n        })\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/location-setup\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.LocationSetup, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        data: locationData,\n        countryStateFields: countryStateFields,\n        currencyFields: currencyFields,\n        buttonText: __('Save Settings', 'newspack'),\n        buttonAction: () => this.update('location', locationData),\n        onChange: _locationData => this.setState({\n          data: { ...data,\n            locationData: _locationData\n          }\n        })\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/stripe-setup\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.StripeSetup, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        displayStripeSettingsOnly: _constants__WEBPACK_IMPORTED_MODULE_10__.STRIPE === platform,\n        data: { ...stripeData,\n          isSSL: data.isSSL\n        },\n        currencyFields: currencyFields,\n        buttonText: __('Save Settings', 'newspack'),\n        buttonAction: () => this.update('stripe', stripeData),\n        onChange: _stripeData => this.setState({\n          data: { ...data,\n            stripeData: _stripeData\n          }\n        })\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/emails\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.Emails, sharedProps)\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/donations\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.Donation, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        data: donationData,\n        headerText: __('Set up donations'),\n        subHeaderText: __('Configure your landing page and your suggested donation presets.'),\n        donationPage: donationPage,\n        buttonText: __('Save Settings'),\n        buttonAction: () => this.update('donations', donationData),\n        onChange: _donationData => this.setState({\n          data: { ...data,\n            donationData: _donationData\n          }\n        })\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Route, {\n      path: \"/salesforce\",\n      render: routeProps => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(_views__WEBPACK_IMPORTED_MODULE_9__.Salesforce, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        routeProps: routeProps,\n        data: salesforceData,\n        headerText: __('Configure Salesforce', 'newspack'),\n        isConnected: salesforceIsConnected,\n        subHeaderText: __('Connect your site with a Salesforce account to capture donor contact information.', 'newspack'),\n        buttonText: salesforceIsConnected ? __('Reset', 'newspack') : __('Connect', 'newspack'),\n        buttonAction: this.handleSalesforce,\n        buttonDisabled: !salesforceIsConnected && (!salesforceData.client_id || !salesforceData.client_secret),\n        onChange: _salesforceData => this.setState({\n          data: { ...data,\n            salesforceData: _salesforceData\n          }\n        }),\n        wizardApiFetch: wizardApiFetch\n      }, sharedProps))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)(Redirect, {\n      to: \"/\"\n    }))));\n  }\n\n}\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_4__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_7__.withWizard)(ReaderRevenueWizard, ['newspack-blocks'])), document.getElementById('newspack-reader-revenue-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/emails/index.js":
/*!************************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/emails/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/esm/extends.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n/* globals newspack_reader_revenue*/\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * External dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n/**\n * Internal dependencies\n */\n\n\nconst EMAILS = (0,lodash__WEBPACK_IMPORTED_MODULE_3__.values)(newspack_reader_revenue.emails);\n\nconst Emails = () => {\n  const [pluginsReady, setPluginsReady] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);\n\n  if (false === pluginsReady) {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n      isError: true\n    }, __('Newspack uses Newspack Newsletters to handle editing email-type content. Please activate this plugin to proceed.', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n      isError: true\n    }, __('Until this feature is configured, default Stripe receipts will be used.', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.PluginInstaller, {\n      style: pluginsReady ? {\n        display: 'none'\n      } : {},\n      plugins: ['newspack-newsletters'],\n      onStatus: res => setPluginsReady(res.complete),\n      onInstalled: () => window.location.reload(),\n      withoutFooterButton: true\n    }));\n  }\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, EMAILS.map(email => {\n    const isActive = email.status === 'publish';\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ActionCard, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n      key: email.post_id,\n      title: email.label,\n      titleLink: email.edit_link,\n      href: email.edit_link,\n      description: email.description,\n      actionText: __('Edit', 'newspack')\n    }, isActive ? {} : {\n      notification: __('This email is not active – the default Stripe receipt will be used. Edit and publish the email to activate it.', 'newspack'),\n      notificationLevel: 'error'\n    }, {\n      secondaryActionText: __('Send a test email', 'newspack')\n    }));\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizardScreen)(Emails));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/emails/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/index.js":
/*!*****************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/index.js ***!
  \*****************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Donation\": function() { return /* reexport safe */ _donation__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"LocationSetup\": function() { return /* reexport safe */ _location_setup__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; },\n/* harmony export */   \"NRHSettings\": function() { return /* reexport safe */ _nrh_settings__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; },\n/* harmony export */   \"Platform\": function() { return /* reexport safe */ _platform__WEBPACK_IMPORTED_MODULE_3__[\"default\"]; },\n/* harmony export */   \"StripeSetup\": function() { return /* reexport safe */ _stripe_setup__WEBPACK_IMPORTED_MODULE_4__[\"default\"]; },\n/* harmony export */   \"Emails\": function() { return /* reexport safe */ _emails__WEBPACK_IMPORTED_MODULE_5__[\"default\"]; },\n/* harmony export */   \"Salesforce\": function() { return /* reexport safe */ _salesforce__WEBPACK_IMPORTED_MODULE_6__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _donation__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./donation */ \"./assets/wizards/readerRevenue/views/donation/index.js\");\n/* harmony import */ var _location_setup__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./location-setup */ \"./assets/wizards/readerRevenue/views/location-setup/index.js\");\n/* harmony import */ var _nrh_settings__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./nrh-settings */ \"./assets/wizards/readerRevenue/views/nrh-settings/index.js\");\n/* harmony import */ var _platform__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./platform */ \"./assets/wizards/readerRevenue/views/platform/index.js\");\n/* harmony import */ var _stripe_setup__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./stripe-setup */ \"./assets/wizards/readerRevenue/views/stripe-setup/index.js\");\n/* harmony import */ var _emails__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./emails */ \"./assets/wizards/readerRevenue/views/emails/index.js\");\n/* harmony import */ var _salesforce__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./salesforce */ \"./assets/wizards/readerRevenue/views/salesforce/index.js\");\n\n\n\n\n\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/location-setup/index.js":
/*!********************************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/location-setup/index.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Location Setup Screen\n */\n\n/**\n * WordPress dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Location Setup Screen Component\n */\n\nclass LocationSetup extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      countryStateFields,\n      currencyFields,\n      data,\n      onChange\n    } = this.props;\n    const {\n      address1 = '',\n      address2 = '',\n      city = '',\n      countrystate = '',\n      currency = '',\n      postcode = ''\n    } = data;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {\n      label: __('Where is your business based?'),\n      value: countrystate,\n      options: countryStateFields,\n      onChange: _countrystate => onChange({ ...data,\n        countrystate: _countrystate\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('Address'),\n      value: address1,\n      onChange: _address1 => onChange({ ...data,\n        address1: _address1\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('Address line 2'),\n      value: address2,\n      onChange: _address2 => onChange({ ...data,\n        address2: _address2\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('City'),\n      value: city,\n      onChange: _city => onChange({ ...data,\n        city: _city\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('Postcode / Zip'),\n      value: postcode,\n      onChange: _postcode => onChange({ ...data,\n        postcode: _postcode\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {\n      label: 'Which currency does your business use?',\n      value: currency,\n      options: currencyFields,\n      onChange: _currency => onChange({ ...data,\n        currency: _currency\n      })\n    }));\n  }\n\n}\n\nLocationSetup.defaultProps = {\n  countryStateFields: [{}],\n  currencyFields: [{}],\n  data: {},\n  onChange: () => null\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(LocationSetup));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/location-setup/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/nrh-settings/index.js":
/*!******************************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/nrh-settings/index.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * News Revenue Hub Settings Screen\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * News Revenue Hub Settings Screen Component\n */\n\nclass NRHSettings extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      data,\n      onChange\n    } = this.props;\n    const {\n      nrh_organization_id,\n      nrh_salesforce_campaign_id\n    } = data;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('NRH Organization ID (required)', 'newspack'),\n      value: nrh_organization_id || '',\n      onChange: _nrh_organization_id => onChange({ ...data,\n        nrh_organization_id: _nrh_organization_id\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('NRH Salesforce Campaign ID', 'newspack'),\n      value: nrh_salesforce_campaign_id || '',\n      onChange: _nrh_salesforce_campaign_id => onChange({ ...data,\n        nrh_salesforce_campaign_id: _nrh_salesforce_campaign_id\n      })\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(NRHSettings));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/nrh-settings/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/platform/index.js":
/*!**************************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/platform/index.js ***!
  \**************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _constants__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../constants */ \"./assets/wizards/readerRevenue/constants.js\");\n\n\n/**\n * Platform Selection Screen\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n\nconst {\n  withRouter\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_3__[\"default\"];\n/**\n * Platform Selection  Screen Component\n */\n\nconst Platform = _ref => {\n  let {\n    data,\n    onChange,\n    onReady,\n    pluginStatus\n  } = _ref;\n  const {\n    platform\n  } = data;\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {\n    label: __('Select Reader Revenue Platform', 'newspack'),\n    value: platform,\n    options: [{\n      label: __('-- Select Your Platform --', 'newspack'),\n      value: ''\n    }, {\n      label: __('Newspack', 'newspack'),\n      value: _constants__WEBPACK_IMPORTED_MODULE_4__.NEWSPACK\n    }, {\n      label: __('News Revenue Hub', 'newspack'),\n      value: _constants__WEBPACK_IMPORTED_MODULE_4__.NRH\n    }, {\n      label: __('Stripe', 'newspack'),\n      value: _constants__WEBPACK_IMPORTED_MODULE_4__.STRIPE,\n      disabled: data.stripeData.can_use_stripe_platform === false\n    }],\n    onChange: _platform => {\n      if (_platform.length) {\n        onChange({ ...data,\n          platform: _platform\n        });\n      }\n    }\n  })), _constants__WEBPACK_IMPORTED_MODULE_4__.NEWSPACK === platform && !pluginStatus && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.PluginInstaller, {\n    plugins: ['woocommerce', 'woocommerce-subscriptions', 'woocommerce-name-your-price', 'woocommerce-gateway-stripe'],\n    onStatus: _ref2 => {\n      let {\n        complete\n      } = _ref2;\n\n      if (complete) {\n        onReady();\n      }\n    },\n    withoutFooterButton: true\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(withRouter(Platform)));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/platform/index.js?");

/***/ }),

/***/ "./assets/wizards/readerRevenue/views/salesforce/index.js":
/*!****************************************************************!*\
  !*** ./assets/wizards/readerRevenue/views/salesforce/index.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! qs */ \"./node_modules/qs/lib/index.js\");\n/* harmony import */ var qs__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(qs__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Salesforce Settings Screen\n */\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies.\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n/**\n * Internal dependencies.\n */\n\n\n/**\n * Salesforce Settings Screen Component\n */\n\nclass Salesforce extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  constructor() {\n    super(...arguments);\n    this.state = {\n      error: null\n    };\n  }\n  /**\n   * On component mount.\n   */\n\n\n  componentDidMount() {\n    const query = (0,qs__WEBPACK_IMPORTED_MODULE_1__.parse)(window.location.search);\n    const authorizationCode = query.code;\n    const redirectURI = window.location.origin + window.location.pathname + '?page=' + query['?page'] + window.location.hash;\n\n    if (authorizationCode) {\n      // Remove `code` param from URL without adding history.\n      window.history.replaceState({}, '', redirectURI);\n      this.getTokens(authorizationCode, redirectURI);\n    }\n  }\n  /**\n   * On component update.\n   */\n\n\n  componentDidUpdate(prevProps) {\n    const {\n      isConnected\n    } = this.props; // Clear any state errors on reset.\n\n    if (prevProps.isConnected && !isConnected) {\n      return this.setState({\n        error: null\n      });\n    } // If we're already connected, check status of refresh token.\n\n\n    if (!prevProps.isConnected && isConnected) {\n      return this.checkConnectionStatus();\n    }\n  }\n  /**\n   * Use auth code to request access and refresh tokens for Salesforce API.\n   * Saves tokens to options table.\n   * https://help.salesforce.com/articleView?id=remoteaccess_oauth_web_server_flow.htm&type=5\n   *\n   * @param {string} authorizationCode Auth code fetched from Salesforce.\n   * @return {void}\n   */\n\n\n  async getTokens(authorizationCode, redirectURI) {\n    const {\n      data,\n      onChange,\n      wizardApiFetch\n    } = this.props;\n\n    try {\n      // Get the tokens.\n      const response = await wizardApiFetch({\n        path: '/newspack/v1/wizard/salesforce/tokens',\n        method: 'POST',\n        data: {\n          code: authorizationCode,\n          redirect_uri: redirectURI\n        }\n      });\n      const {\n        access_token,\n        client_id,\n        client_secret,\n        instance_url,\n        refresh_token\n      } = response; // Update values in parent state.\n\n      if (access_token && refresh_token) {\n        return onChange({ ...data,\n          access_token,\n          client_id,\n          client_secret,\n          instance_url,\n          refresh_token\n        });\n      }\n    } catch (e) {\n      this.setState({\n        error: __('We couldn’t establish a connection to Salesforce. Please verify your Consumer Key and Secret and try connecting again.', 'newspack')\n      });\n    }\n  }\n  /**\n   * Check validity of refresh token and show an error message if it's no longer active.\n   * The refresh token is valid until it's manually revoked in the Salesforce dashboard,\n   * or the Connected App is deleted there.\n   */\n\n\n  async checkConnectionStatus() {\n    const {\n      wizardApiFetch\n    } = this.props;\n    const response = await wizardApiFetch({\n      path: '/newspack/v1/wizard/salesforce/connection-status',\n      method: 'POST'\n    });\n\n    if (response.error) {\n      this.setState({\n        error: response.error\n      });\n    }\n  }\n  /**\n   * Render.\n   */\n\n\n  render() {\n    const {\n      data,\n      isConnected,\n      onChange\n    } = this.props;\n    const {\n      client_id = '',\n      client_secret = '',\n      error\n    } = data;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Grid, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Card, {\n      noBorder: true\n    }, this.state.error && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n      noticeText: this.state.error,\n      isWarning: true\n    }), isConnected && !this.state.error && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n      noticeText: __('Your site is connected to Salesforce.', 'newspack'),\n      isSuccess: true\n    }), !isConnected && !this.state.error && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('To connect with Salesforce, create or choose a Connected App for this site in your Salesforce dashboard. Make sure to paste the the full URL for this page into the “Callback URL” field in the Connected App’s settings. ', 'newspack'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ExternalLink, {\n      href: \"https://help.salesforce.com/articleView?id=connected_app_create.htm\"\n    }, __('Learn how to create a Connected App', 'newspack'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('Enter your Consumer Key and Secret, then click “Connect” to authorize access to your Salesforce account.', 'newspack'))), isConnected && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('To reconnect your site in case of issues, or to connect to a different Salesforce account, click “Reset\". You will need to re-enter your Consumer Key and Secret before you can re-connect to Salesforce.', 'newspack'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Card, {\n      noBorder: true\n    }, error && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n      noticeText: __('We couldn’t connect to Salesforce. Please verify that you entered the correct Consumer Key and Secret and try again. If you just created your Connected App or edited the Callback URL settings, it may take up to an hour before we can establish a connection.', 'newspack'),\n      isError: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.TextControl, {\n      disabled: isConnected,\n      label: (isConnected ? __('Your', 'newspack') : __('Enter your', 'newspack')) + __(' Salesforce Consumer Key', 'newspack'),\n      value: client_id,\n      onChange: value => {\n        if (isConnected) {\n          return;\n        }\n\n        onChange({ ...data,\n          client_id: value\n        });\n      }\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.TextControl, {\n      disabled: isConnected,\n      label: (isConnected ? __('Your', 'newspack') : __('Enter your', 'newspack')) + __(' Salesforce Consumer Secret', 'newspack'),\n      value: client_secret,\n      onChange: value => {\n        if (isConnected) {\n          return;\n        }\n\n        onChange({ ...data,\n          client_secret: value\n        });\n      }\n    })));\n  }\n\n}\n\nSalesforce.defaultProps = {\n  data: {},\n  onChange: () => null\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizardScreen)(Salesforce));\n\n//# sourceURL=webpack://newspack/./assets/wizards/readerRevenue/views/salesforce/index.js?");

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
/******/ 			"readerRevenue": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/readerRevenue/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;