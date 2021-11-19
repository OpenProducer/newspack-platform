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

/***/ "./assets/wizards/analytics/style.scss":
/*!*********************************************!*\
  !*** ./assets/wizards/analytics/style.scss ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/style.scss?");

/***/ }),

/***/ "./assets/components/src/consts.js":
/*!*****************************************!*\
  !*** ./assets/components/src/consts.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"NEWSPACK_SITE_URL\": function() { return /* binding */ NEWSPACK_SITE_URL; },\n/* harmony export */   \"NEWSPACK_SUPPORT_URL\": function() { return /* binding */ NEWSPACK_SUPPORT_URL; }\n/* harmony export */ });\nconst NEWSPACK_SITE_URL = 'https://newspack.pub';\nconst NEWSPACK_SUPPORT_URL = `${NEWSPACK_SITE_URL}/support`;\n\n//# sourceURL=webpack://newspack/./assets/components/src/consts.js?");

/***/ }),

/***/ "./assets/wizards/analytics/index.js":
/*!*******************************************!*\
  !*** ./assets/wizards/analytics/index.js ***!
  \*******************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./views */ \"./assets/wizards/analytics/views/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/analytics/style.scss\");\n\n/**\n * Analytics\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_4__[\"default\"];\nconst TABS = [{\n  label: __('Plugins', 'newspack'),\n  path: '/',\n  exact: true\n}, {\n  label: __('Custom Dimensions', 'newspack'),\n  path: '/custom-dimensions'\n}, {\n  label: __('Custom Events', 'newspack'),\n  path: '/custom-events'\n}];\n\nclass AnalyticsWizard extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {\n  /**\n   * Render\n   */\n  render() {\n    const {\n      pluginRequirements,\n      wizardApiFetch,\n      isLoading\n    } = this.props;\n    const sharedProps = {\n      headerText: __('Analytics', 'newspack'),\n      subHeaderText: __('Track traffic and activity', 'newspack'),\n      tabbedNavigation: TABS,\n      wizardApiFetch,\n      isLoading\n    };\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(HashRouter, {\n      hashType: \"slash\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Switch, null, pluginRequirements, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Route, {\n      path: \"/custom-dimensions\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_views__WEBPACK_IMPORTED_MODULE_5__.CustomDimensions, sharedProps)\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Route, {\n      path: \"/custom-events\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_views__WEBPACK_IMPORTED_MODULE_5__.CustomEvents, sharedProps)\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Route, {\n      path: \"/\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_views__WEBPACK_IMPORTED_MODULE_5__.Plugins, sharedProps)\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(Redirect, {\n      to: \"/\"\n    }))));\n  }\n\n}\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizard)(AnalyticsWizard, ['google-site-kit'])), document.getElementById('newspack-analytics-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/custom-dimensions/index.js":
/*!*******************************************************************!*\
  !*** ./assets/wizards/analytics/views/custom-dimensions/index.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\nconst SCOPES_OPTIONS = [{\n  value: 'HIT',\n  label: __('Hit', 'newspack')\n}, {\n  value: 'SESSION',\n  label: __('Session', 'newspack')\n}, {\n  value: 'USER',\n  label: __('User', 'newspack')\n}, {\n  value: 'PRODUCT',\n  label: __('Product', 'newspack')\n}];\n/**\n * Analytics Custom Dimensions screen.\n */\n\nclass CustomDimensions extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"state\", {\n      error: newspack_analytics_wizard_data.analyticsConnectionError,\n      customDimensions: newspack_analytics_wizard_data.customDimensions,\n      newDimensionName: '',\n      newDimensionScope: SCOPES_OPTIONS[0].value\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"handleAPIError\", _ref => {\n      let {\n        message: error\n      } = _ref;\n      this.setState({\n        error\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"handleCustomDimensionCreation\", () => {\n      const {\n        wizardApiFetch\n      } = this.props;\n      const {\n        customDimensions,\n        newDimensionName,\n        newDimensionScope\n      } = this.state;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/analytics/custom-dimensions',\n        method: 'POST',\n        data: {\n          name: newDimensionName,\n          scope: newDimensionScope\n        }\n      }).then(newCustomDimension => {\n        this.setState({\n          customDimensions: [...customDimensions, newCustomDimension]\n        });\n      }).catch(this.handleAPIError);\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"handleCustomDimensionSetting\", dimensionId => role => {\n      const {\n        wizardApiFetch\n      } = this.props;\n      wizardApiFetch({\n        path: `/newspack/v1/wizard/analytics/custom-dimensions/${dimensionId}`,\n        method: 'POST',\n        data: {\n          role\n        }\n      }).then(res => {\n        this.setState({\n          customDimensions: res\n        });\n      }).catch(this.handleAPIError);\n    });\n  }\n\n  render() {\n    const {\n      error,\n      customDimensions,\n      newDimensionName,\n      newDimensionScope\n    } = this.state;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n      className: \"newspack__analytics-configuration\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SectionHeader, {\n      title: __('User-defined custom dimensions', 'newspack'),\n      description: __(\"Collect and analyze data that Google Analytics doesn't automatically track\", 'newspack')\n    }), error ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Notice, {\n      noticeText: error,\n      isError: true,\n      rawHTML: true\n    }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"table\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"thead\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tr\", null, [__('Name', 'newspack'), __('ID', 'newspack'), __('Role', 'newspack')].map((colName, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"th\", {\n      key: i\n    }, colName)))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tbody\", null, customDimensions.map(dimension => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tr\", {\n      key: dimension.id\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"strong\", null, dimension.name)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"code\", null, dimension.id)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n      options: newspack_analytics_wizard_data.customDimensionsOptions,\n      value: dimension.role || '',\n      onChange: this.handleCustomDimensionSetting(dimension.id),\n      className: \"newspack__analytics-configuration__select\"\n    })))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Card, {\n      isMedium: true\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"h2\", null, __('Create new custom dimension', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n      className: \"newspack__analytics-configuration__form\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n      value: newDimensionName,\n      onChange: val => this.setState({\n        newDimensionName: val\n      }),\n      label: __('Name', 'newspack')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n      value: newDimensionScope,\n      onChange: val => this.setState({\n        newDimensionScope: val\n      }),\n      label: __('Scope', 'newspack'),\n      options: SCOPES_OPTIONS\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Button, {\n      onClick: this.handleCustomDimensionCreation,\n      disabled: newDimensionName.length === 0,\n      isPrimary: true\n    }, __('Create', 'newspack'))))));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(CustomDimensions));\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/views/custom-dimensions/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/custom-events/index.js":
/*!***************************************************************!*\
  !*** ./assets/wizards/analytics/views/custom-events/index.js ***!
  \***************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/icon/index.js\");\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/check.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_consts__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../../../components/src/consts */ \"./assets/components/src/consts.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ \"./node_modules/classnames/index.js\");\n/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);\n\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * External dependencies\n */\n\n/**\n * WordPress dependencies\n */\n\n\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__;\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * External dependencies\n */\n\n\n/**\n * Not implemented:\n * - visibility, ini-load: require the element to be AMP element,\n * - scroll: requires some more UI for scroll parameters, can be implemented later.\n */\n\nconst TRIGGER_OPTIONS = [{\n  value: 'click',\n  label: __('Click', 'newspack')\n}, {\n  value: 'submit',\n  label: __('Submit', 'newspack')\n}];\nconst NEW_EVENT_TEMPLATE = {\n  event_name: '',\n  event_category: '',\n  event_label: '',\n  on: TRIGGER_OPTIONS[0].value,\n  element: '',\n  amp_element: '',\n  non_interaction: true,\n  is_active: true\n};\n\nconst validateEvent = event => Boolean(event.event_name && event.event_category && event.on && event.element);\n\nconst NTG_EVENTS_ENDPOINT = '/newspack/v1/wizard/analytics/ntg';\n/**\n * Analytics Custom Events screen.\n */\n\nclass CustomEvents extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"state\", {\n      error: newspack_analytics_wizard_data.analyticsConnectionError,\n      customEvents: newspack_analytics_wizard_data.customEvents,\n      editedEvent: NEW_EVENT_TEMPLATE,\n      editedEventId: null,\n      ntgEventsStatus: {}\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"handleAPIError\", _ref => {\n      let {\n        message: error\n      } = _ref;\n      return this.setState({\n        error\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"updateCustomEvents\", updatedEvents => {\n      const {\n        wizardApiFetch\n      } = this.props;\n      wizardApiFetch({\n        path: '/newspack/v1/wizard/analytics/custom-events',\n        method: 'POST',\n        data: {\n          events: updatedEvents\n        }\n      }).then(_ref2 => {\n        let {\n          events\n        } = _ref2;\n        return this.setState({\n          customEvents: events,\n          editedEvent: NEW_EVENT_TEMPLATE,\n          editedEventId: null\n        });\n      }).catch(this.handleAPIError);\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"handleCustomEventEdit\", () => {\n      const {\n        customEvents,\n        editedEvent,\n        editedEventId\n      } = this.state;\n\n      if (editedEventId === 'new') {\n        this.updateCustomEvents([...customEvents, editedEvent]);\n      } else {\n        this.updateCustomEvents(customEvents.map(event => {\n          if (event.id === editedEventId) {\n            return editedEvent;\n          }\n\n          return event;\n        }));\n      }\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"updateEditedEvent\", key => value => this.setState(_ref3 => {\n      let {\n        editedEvent\n      } = _ref3;\n      return {\n        editedEvent: { ...editedEvent,\n          [key]: value\n        }\n      };\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"setEditModal\", editedEventId => () => {\n      const editedEvent = editedEventId !== null && (0,lodash__WEBPACK_IMPORTED_MODULE_2__.find)(this.state.customEvents, ['id', editedEventId]);\n      this.setState({\n        editedEventId,\n        ...(editedEvent ? {\n          editedEvent\n        } : {\n          editedEvent: NEW_EVENT_TEMPLATE\n        })\n      });\n    });\n  }\n\n  componentDidMount() {\n    this.props.wizardApiFetch({\n      path: NTG_EVENTS_ENDPOINT\n    }).then(ntgEventsStatus => this.setState({\n      ntgEventsStatus\n    }));\n  }\n\n  render() {\n    const {\n      error,\n      customEvents,\n      editedEvent,\n      editedEventId\n    } = this.state;\n    const {\n      isLoading\n    } = this.props;\n    const isCreatingEvent = editedEventId === 'new';\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n      className: \"newspack__analytics-configuration\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n      className: \"newspack__analytics-configuration__header\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.SectionHeader, {\n      title: __('User-defined custom events', 'newspack'),\n      description: __('Collect and analyze specific user interactions', 'newspack'),\n      noMargin: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n      onClick: this.setEditModal('new'),\n      isPrimary: true,\n      isSmall: true\n    }, __('Add New Custom Event', 'newspack'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Notice, {\n      rawHTML: true,\n      isInfo: true,\n      noticeText: `${__('This is an advanced feature, read more about it on our', 'newspack')} <a href=\"${_components_src_consts__WEBPACK_IMPORTED_MODULE_6__.NEWSPACK_SUPPORT_URL}/analytics\">${__('support page', 'newspack')}</a>.`\n    }), error ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Notice, {\n      noticeText: error,\n      isError: true,\n      rawHTML: true\n    }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"table\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"thead\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tr\", null, [__('Active', 'newspack'), __('Action', 'newspack'), __('Category', 'newspack'), __('Label', 'newspack'), __('Trigger', 'newspack'), __('Edit', 'newspack')].map((colName, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"th\", {\n      key: i\n    }, colName)))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tbody\", null, customEvents.map(event => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"tr\", {\n      key: event.id\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"span\", {\n      className: classnames__WEBPACK_IMPORTED_MODULE_7___default()('newspack-checkbox-icon', event.is_active && 'newspack-checkbox-icon--checked')\n    }, event.is_active && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_icons__WEBPACK_IMPORTED_MODULE_8__[\"default\"], {\n      icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_9__[\"default\"]\n    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, event.event_name), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, event.event_category), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, event.event_label), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"code\", null, event.on)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"td\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n      isSmall: true,\n      isLink: true,\n      onClick: this.setEditModal(event.id)\n    }, __('Edit', 'newspack'))))))), editedEventId !== null && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Modal, {\n      title: isCreatingEvent ? __('New custom event', 'newspack') : __('Editing custom event', 'newspack'),\n      onRequestClose: this.setEditModal(null),\n      isWide: true\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Grid, {\n      gutter: 32\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n      disabled: isLoading,\n      value: editedEvent.event_name,\n      onChange: this.updateEditedEvent('event_name'),\n      label: __('Action', 'newspack'),\n      required: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n      disabled: isLoading,\n      value: editedEvent.event_category,\n      onChange: this.updateEditedEvent('event_category'),\n      label: __('Category', 'newspack'),\n      required: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n      disabled: isLoading,\n      value: editedEvent.event_label,\n      onChange: this.updateEditedEvent('event_label'),\n      label: __('Label', 'newspack')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.SelectControl, {\n      disabled: isLoading,\n      value: editedEvent.on,\n      onChange: this.updateEditedEvent('on'),\n      label: __('Trigger', 'newspack'),\n      options: TRIGGER_OPTIONS,\n      required: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n      disabled: isLoading,\n      value: editedEvent.element,\n      onChange: this.updateEditedEvent('element'),\n      label: __('Selector', 'newspack'),\n      className: \"code\",\n      required: true\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.TextControl, {\n      disabled: isLoading,\n      value: editedEvent.amp_element,\n      onChange: this.updateEditedEvent('amp_element'),\n      label: __('AMP Selector', 'newspack'),\n      className: \"code\"\n    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.CheckboxControl, {\n      disabled: isLoading,\n      checked: editedEvent.non_interaction,\n      onChange: this.updateEditedEvent('non_interaction'),\n      label: __('Non-interaction event', 'newspack')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.CheckboxControl, {\n      disabled: isLoading,\n      checked: editedEvent.is_active,\n      onChange: this.updateEditedEvent('is_active'),\n      label: __('Active', 'newspack')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Card, {\n      buttonsCard: true,\n      noBorder: true,\n      className: \"justify-end\"\n    }, !isCreatingEvent && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n      isSecondary: true,\n      disabled: isLoading,\n      onClick: () => this.updateCustomEvents(this.state.customEvents.filter(_ref4 => {\n        let {\n          id\n        } = _ref4;\n        return editedEvent.id !== id;\n      }))\n    }, __('Delete', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.Button, {\n      onClick: this.handleCustomEventEdit,\n      disabled: !validateEvent(editedEvent) || isLoading,\n      isPrimary: true\n    }, isCreatingEvent ? __('Add', 'newspack') : __('Update', 'newspack')))))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_5__.ActionCard, {\n      isMedium: true,\n      title: __('News Tagging Guide custom events', 'newspack'),\n      description: [__('Free tool that helps you make the most of Google Analytics by capturing better data.', 'newspack') + '\\u00A0', (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ExternalLink, {\n        href: \"https://newsinitiative.withgoogle.com/training/datatools/ntg\",\n        key: \"info-link\"\n      }, __('More info', 'newspack'))],\n      toggle: true,\n      disabled: this.state.ntgEventsStatus.enabled === undefined,\n      toggleChecked: this.state.ntgEventsStatus.enabled,\n      toggleOnChange: () => this.props.wizardApiFetch({\n        path: NTG_EVENTS_ENDPOINT,\n        method: this.state.ntgEventsStatus.enabled ? 'DELETE' : 'POST',\n        quiet: true\n      }).then(ntgEventsStatus => this.setState({\n        ntgEventsStatus\n      }))\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_5__.withWizardScreen)(CustomEvents));\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/views/custom-events/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/index.js":
/*!*************************************************!*\
  !*** ./assets/wizards/analytics/views/index.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Plugins\": function() { return /* reexport safe */ _plugins__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"CustomDimensions\": function() { return /* reexport safe */ _custom_dimensions__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; },\n/* harmony export */   \"CustomEvents\": function() { return /* reexport safe */ _custom_events__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins */ \"./assets/wizards/analytics/views/plugins/index.js\");\n/* harmony import */ var _custom_dimensions__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./custom-dimensions */ \"./assets/wizards/analytics/views/custom-dimensions/index.js\");\n/* harmony import */ var _custom_events__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./custom-events */ \"./assets/wizards/analytics/views/custom-events/index.js\");\n\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/views/index.js?");

/***/ }),

/***/ "./assets/wizards/analytics/views/plugins/index.js":
/*!*********************************************************!*\
  !*** ./assets/wizards/analytics/views/plugins/index.js ***!
  \*********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/* global newspack_analytics_wizard_data */\n\n/**\n * Analytics Plugins View\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * Analytics Plugins screen.\n */\n\nclass Plugins extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Render.\n   */\n  render() {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ActionCard, {\n      title: __('Google Analytics'),\n      description: __('Configure and view site analytics'),\n      actionText: __('View'),\n      handoff: \"google-site-kit\",\n      editLink: newspack_analytics_wizard_data.analyticsConnectionError ? undefined : 'admin.php?page=googlesitekit-module-analytics'\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(Plugins));\n\n//# sourceURL=webpack://newspack/./assets/wizards/analytics/views/plugins/index.js?");

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
/******/ 			"analytics": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/analytics/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;