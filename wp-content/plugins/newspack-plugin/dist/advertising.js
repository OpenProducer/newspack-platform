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

/***/ "./node_modules/@wordpress/icons/build-module/library/pencil.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@wordpress/icons/build-module/library/pencil.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/primitives */ \"@wordpress/primitives\");\n/* harmony import */ var _wordpress_primitives__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__);\n\n\n/**\n * WordPress dependencies\n */\n\nconst pencil = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.SVG, {\n  xmlns: \"http://www.w3.org/2000/svg\",\n  viewBox: \"0 0 24 24\"\n}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_primitives__WEBPACK_IMPORTED_MODULE_1__.Path, {\n  d: \"M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z\"\n}));\n/* harmony default export */ __webpack_exports__[\"default\"] = (pencil);\n//# sourceMappingURL=pencil.js.map\n\n//# sourceURL=webpack://newspack/./node_modules/@wordpress/icons/build-module/library/pencil.js?");

/***/ }),

/***/ "./assets/wizards/advertising/style.scss":
/*!***********************************************!*\
  !*** ./assets/wizards/advertising/style.scss ***!
  \***********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/style.scss?");

/***/ }),

/***/ "./assets/wizards/advertising/views/settings/ad-picker/style.scss":
/*!************************************************************************!*\
  !*** ./assets/wizards/advertising/views/settings/ad-picker/style.scss ***!
  \************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/settings/ad-picker/style.scss?");

/***/ }),

/***/ "./assets/wizards/advertising/components/ad-unit-size-control/index.js":
/*!*****************************************************************************!*\
  !*** ./assets/wizards/advertising/components/ad-unit-size-control/index.js ***!
  \*****************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"DEFAULT_SIZES\": function() { return /* binding */ DEFAULT_SIZES; }\n/* harmony export */ });\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ \"lodash\");\n/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Ad Unit Size Control.\n *\n * Select from a subset of sizes, or enter custom width and height.\n */\n\n/**\n * External dependencies.\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n/**\n * Interactive Advertising Bureau's standard ad sizes.\n */\n\nconst DEFAULT_SIZES = [[970, 250], [970, 90], [728, 90], [300, 600], [300, 250], [300, 1050], [160, 600], [320, 50], [320, 100], [120, 60], 'fluid'];\n/**\n * Ad Unit Size Control.\n */\n\nconst AdUnitSizeControl = _ref => {\n  let {\n    value,\n    selectedOptions,\n    onChange\n  } = _ref;\n  const [isCustom, setIsCustom] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);\n  const options = DEFAULT_SIZES.filter(size => JSON.stringify(value) === JSON.stringify(size) || !selectedOptions.find(selectedOption => JSON.stringify(selectedOption) === JSON.stringify(size)));\n  const sizeIndex = isCustom ? -1 : options.findIndex(size => {\n    if (typeof value === 'string') {\n      return value === size;\n    } else if (Array.isArray(value)) {\n      return size[0] === value[0] && size[1] === value[1];\n    }\n\n    return false;\n  });\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n    label: __('Size', 'newspack'),\n    value: sizeIndex,\n    options: [...options.map((size, index) => ({\n      label: Array.isArray(size) ? `${size[0]} x ${size[1]}` : (0,lodash__WEBPACK_IMPORTED_MODULE_1__.startCase)(size),\n      value: index\n    })), {\n      label: __('Custom', 'newspack'),\n      value: -1\n    }],\n    onChange: index => {\n      const size = options[index];\n      setIsCustom(!size);\n      onChange(size || []);\n    },\n    hideLabelFromVision: true\n  }), value === 'fluid' && !isCustom ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: \"newspack-advertising-wizard__ad-unit-fluid\"\n  }, __('Fluid is a native ad size that allows more flexibility when styling your ad. It automatically sizes the ad by filling the width of the enclosing column and adjusting the height as appropriate.', 'newspack')) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: __('Width', 'newspack'),\n    value: value[0],\n    onChange: newWidth => onChange([newWidth, value[1]]),\n    disabled: !isCustom && sizeIndex !== -1,\n    type: \"number\",\n    hideLabelFromVision: true\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.TextControl, {\n    label: __('Height', 'newspack'),\n    value: value[1],\n    onChange: newHeight => onChange([value[0], newHeight]),\n    disabled: !isCustom && sizeIndex !== -1,\n    type: \"number\",\n    hideLabelFromVision: true\n  })));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (AdUnitSizeControl);\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/components/ad-unit-size-control/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/advertising/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./views */ \"./assets/wizards/advertising/views/index.js\");\n/* harmony import */ var _components_ad_unit_size_control__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./components/ad-unit-size-control */ \"./assets/wizards/advertising/components/ad-unit-size-control/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/advertising/style.scss\");\n\n\n/**\n * Advertising\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;\n\n\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__[\"default\"];\nconst CREATE_AD_ID_PARAM = 'create';\n\nclass AdvertisingWizard extends _wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Component {\n  /**\n   * Constructor.\n   */\n  constructor() {\n    var _this;\n\n    super(...arguments);\n    _this = this;\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"onWizardReady\", () => {\n      this.fetchAdvertisingData();\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"updateWithAPI\", requestConfig => this.props.wizardApiFetch(requestConfig).then(response => new Promise(resolve => {\n      this.setState({\n        advertisingData: { ...response,\n          adUnits: response.ad_units.reduce((result, value) => {\n            result[value.id] = value;\n            return result;\n          }, {})\n        }\n      }, () => {\n        this.props.setError();\n        resolve(this.state);\n      });\n    })).catch(this.props.setError));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"fetchAdvertisingData\", function () {\n      let quiet = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;\n      return _this.updateWithAPI({\n        path: '/newspack/v1/wizard/advertising',\n        quiet\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"toggleService\", (service, enabled) => this.updateWithAPI({\n      path: '/newspack/v1/wizard/advertising/service/' + service,\n      method: enabled ? 'POST' : 'DELETE',\n      quiet: true\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"togglePlacement\", (placement, enabled) => this.updateWithAPI({\n      path: '/newspack/v1/wizard/advertising/placement/' + placement,\n      method: enabled ? 'POST' : 'DELETE',\n      quiet: true\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"savePlacement\", (placement, data) => this.updateWithAPI({\n      path: '/newspack/v1/wizard/advertising/placement/' + placement,\n      method: 'post',\n      data: {\n        ad_unit: data.adUnit,\n        service: data.service\n      },\n      quiet: true\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"onAdUnitChange\", adUnit => {\n      const {\n        advertisingData\n      } = this.state;\n      advertisingData.adUnits[adUnit.id] = adUnit;\n      this.setState({\n        advertisingData\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"saveAdUnit\", id => this.updateWithAPI({\n      path: '/newspack/v1/wizard/advertising/ad_unit/' + (id || 0),\n      method: 'post',\n      data: this.state.advertisingData.adUnits[id],\n      quiet: true\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"deleteAdUnit\", id => {\n      // eslint-disable-next-line no-alert\n      if (confirm(__('Are you sure you want to archive this ad unit?', 'newspack'))) {\n        return this.updateWithAPI({\n          path: '/newspack/v1/wizard/advertising/ad_unit/' + id,\n          method: 'delete',\n          quiet: true\n        });\n      }\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"updateAdSuppression\", suppressionConfig => this.updateWithAPI({\n      path: '/newspack/v1/wizard/advertising/suppression',\n      method: 'post',\n      data: {\n        config: suppressionConfig\n      },\n      quiet: true\n    }));\n\n    this.state = {\n      advertisingData: {\n        adUnits: [],\n        placements: {\n          global_above_header: {},\n          global_below_header: {},\n          global_above_footer: {},\n          sticky: {}\n        },\n        services: {\n          google_ad_manager: {\n            status: {}\n          },\n          google_adsense: {},\n          wordads: {}\n        },\n        suppression: false\n      }\n    };\n  }\n  /**\n   * wizardReady will be called when all plugin requirements are met.\n   */\n\n\n  /**\n   * Render\n   */\n  render() {\n    const {\n      advertisingData\n    } = this.state;\n    const {\n      pluginRequirements,\n      wizardApiFetch\n    } = this.props;\n    const {\n      services,\n      placements,\n      adUnits\n    } = advertisingData;\n    const tabs = [{\n      label: __('Ad Providers', 'newspack'),\n      path: '/',\n      exact: true\n    }, {\n      label: __('Global Settings', 'newspack'),\n      path: '/settings'\n    }, {\n      label: __('Suppression', 'newspack'),\n      path: '/suppression'\n    }];\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(HashRouter, {\n      hashType: \"slash\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Switch, null, pluginRequirements, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Services, {\n        headerText: __('Advertising', 'newspack'),\n        subHeaderText: __('Monetize your content through advertising', 'newspack'),\n        services: services,\n        toggleService: (service, value) => this.toggleService(service, value),\n        tabbedNavigation: tabs\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/settings\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Settings, {\n        headerText: __('Advertising', 'newspack'),\n        subHeaderText: __('Monetize your content through advertising', 'newspack'),\n        wizardApiFetch: wizardApiFetch,\n        placements: placements,\n        adUnits: adUnits,\n        services: services,\n        onChange: (placement, data) => this.savePlacement(placement, data),\n        togglePlacement: (placement, value) => this.togglePlacement(placement, value),\n        tabbedNavigation: tabs\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/google_ad_manager\",\n      exact: true,\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.AdUnits, {\n        headerText: __('Google Ad Manager', 'newspack'),\n        subHeaderText: __('Monetize your content through advertising', 'newspack'),\n        adUnits: adUnits,\n        service: 'google_ad_manager',\n        serviceData: services.google_ad_manager,\n        onDelete: id => this.deleteAdUnit(id),\n        buttonText: __('Add an ad unit', 'newspack'),\n        buttonAction: `#/google_ad_manager/${CREATE_AD_ID_PARAM}`,\n        secondaryButtonText: __('Back to advertising options', 'newspack'),\n        secondaryButtonAction: \"#/\",\n        wizardApiFetch: wizardApiFetch,\n        fetchAdvertisingData: this.fetchAdvertisingData,\n        updateWithAPI: this.updateWithAPI,\n        updateAdUnit: adUnit => {\n          this.onAdUnitChange(adUnit);\n          this.saveAdUnit(adUnit.id);\n        }\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: `/google_ad_manager/${CREATE_AD_ID_PARAM}`,\n      render: routeProps => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.AdUnit, {\n        headerText: __('Add an ad unit', 'newspack'),\n        subHeaderText: __('Setting up ad units allows you to place ads on your site through our Google Ad Manager Gutenberg block.', 'newspack'),\n        adUnit: adUnits[0] || {\n          id: 0,\n          name: '',\n          code: '',\n          sizes: [_components_ad_unit_size_control__WEBPACK_IMPORTED_MODULE_7__.DEFAULT_SIZES[0]],\n          fluid: false\n        },\n        service: 'google_ad_manager',\n        serviceData: services.google_ad_manager,\n        wizardApiFetch: wizardApiFetch,\n        onChange: this.onAdUnitChange,\n        onSave: id => this.saveAdUnit(id).then(() => {\n          routeProps.history.push('/google_ad_manager');\n        })\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/google_ad_manager/:id\",\n      render: routeProps => {\n        const adId = routeProps.match.params.id;\n        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.AdUnit, {\n          headerText: __('Edit Ad Unit', 'newspack'),\n          subHeaderText: __('Allows you to place ads on your site through our Ads block', 'newspack'),\n          adUnit: adUnits[adId] || {},\n          service: 'google_ad_manager',\n          onChange: this.onAdUnitChange,\n          onSave: id => this.saveAdUnit(id).then(() => {\n            routeProps.history.push('/google_ad_manager');\n          })\n        });\n      }\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/suppression\",\n      render: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Suppression, {\n        headerText: __('Ad Suppression', 'newspack'),\n        subHeaderText: __('Allows you to manage site-wide ad suppression', 'newspack'),\n        tabbedNavigation: tabs,\n        config: advertisingData.suppression,\n        onChange: config => this.updateAdSuppression(config)\n      })\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Redirect, {\n      to: \"/\"\n    }))));\n  }\n\n}\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizard)(AdvertisingWizard, ['newspack-ads'])), document.getElementById('newspack-advertising-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/ad-unit/index.js":
/*!***********************************************************!*\
  !*** ./assets/wizards/advertising/views/ad-unit/index.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/trash.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_ad_unit_size_control__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/ad-unit-size-control */ \"./assets/wizards/advertising/components/ad-unit-size-control/index.js\");\n\n\n/**\n * New/Edit Ad Unit Screen\n */\n\n/**\n * WordPress dependencies.\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n/**\n * Internal dependencies.\n */\n\n\n\n/**\n * New/Edit Ad Unit Screen.\n */\n\nclass AdUnit extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {\n  /**\n   * Handle an update to an ad unit field.\n   *\n   * @param {string|Object} adUnitChangesOrKey Ad Unit field name or object containing changes.\n   * @param {any}           value              New value for field.\n   *\n   */\n  handleOnChange(adUnitChangesOrKey, value) {\n    const {\n      adUnit,\n      onChange,\n      service\n    } = this.props;\n    const adUnitChanges = typeof adUnitChangesOrKey === 'string' ? {\n      [adUnitChangesOrKey]: value\n    } : adUnitChangesOrKey;\n    onChange({ ...adUnit,\n      ad_service: service,\n      ...adUnitChanges\n    });\n  }\n\n  getSizeOptions() {\n    const {\n      adUnit\n    } = this.props;\n    const sizes = adUnit.sizes && Array.isArray(adUnit.sizes) ? adUnit.sizes : [];\n    let sizeOptions = [...sizes];\n\n    if (adUnit.fluid) {\n      sizeOptions = [...sizeOptions, 'fluid'];\n    }\n\n    return sizeOptions;\n  }\n\n  getNextAvailableSize() {\n    return _components_ad_unit_size_control__WEBPACK_IMPORTED_MODULE_3__.DEFAULT_SIZES.find(size => !this.getSizeOptions().includes(size)) || [];\n  }\n  /**\n   * Render.\n   */\n\n\n  render() {\n    const {\n      adUnit,\n      onSave,\n      service\n    } = this.props;\n    const {\n      id,\n      code,\n      fluid = false,\n      name = ''\n    } = adUnit;\n    const isLegacy = adUnit.is_legacy;\n    const isExistingAdUnit = id !== 0;\n    const sizes = adUnit.sizes && Array.isArray(adUnit.sizes) ? adUnit.sizes : [];\n    const isInvalidSize = !fluid && sizes.length === 0;\n    const sizeOptions = this.getSizeOptions();\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Card, {\n      headerActions: true,\n      noBorder: true\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h2\", null, __('Ad Unit Details', 'newspack'))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n      gutter: 32\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('Name', 'newspack'),\n      value: name || '',\n      onChange: value => this.handleOnChange('name', value)\n    }), (isExistingAdUnit || isLegacy) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n      label: __('Code', 'newspack'),\n      value: code || '',\n      className: \"code\",\n      help: isLegacy ? undefined : __(\"Identifies the ad unit in the associated ad tag. Once you've created the ad unit, you can't change the code.\", 'newspack'),\n      disabled: !isLegacy,\n      onChange: value => this.handleOnChange('code', value)\n    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Card, {\n      headerActions: true,\n      noBorder: true\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h2\", null, sizeOptions.length > 1 ? __('Ad Unit Sizes', 'newspack') : __('Ad Unit Size', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n      isSecondary: true,\n      isSmall: true,\n      onClick: () => this.handleOnChange('sizes', [...sizes, this.getNextAvailableSize()])\n    }, __('Add New Size', 'newspack'))), isInvalidSize && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Notice, {\n      isWarning: true,\n      noticeText: __('The ad unit must have at least one valid size or fluid size enabled.', 'newspack')\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n      columns: 4,\n      gutter: 8,\n      className: \"newspack-grid__thead\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"strong\", null, __('Size', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"strong\", null, __('Width', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"strong\", null, __('Height', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"span\", {\n      className: \"screen-reader-text\"\n    }, __('Action', 'newspack'))), sizeOptions.map((size, index) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n      columns: 4,\n      gutter: 8,\n      className: \"newspack-grid__tbody\",\n      key: index\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_ad_unit_size_control__WEBPACK_IMPORTED_MODULE_3__[\"default\"], {\n      selectedOptions: sizeOptions,\n      value: size,\n      onChange: value => {\n        const adUnitChanges = {};\n        const prevValue = sizeOptions[index];\n\n        if (prevValue === 'fluid') {\n          adUnitChanges.fluid = false;\n        }\n\n        if (value === 'fluid') {\n          sizes.splice(index, 1);\n          adUnitChanges.fluid = true;\n        } else {\n          sizes[index] = value;\n        }\n\n        adUnitChanges.sizes = sizes;\n        this.handleOnChange(adUnitChanges);\n      }\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n      isQuaternary: true,\n      onClick: () => {\n        if (size === 'fluid') {\n          this.handleOnChange('fluid', false);\n        } else {\n          sizes.splice(index, 1);\n          this.handleOnChange('sizes', sizes);\n        }\n      },\n      icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_4__[\"default\"],\n      disabled: sizeOptions.length <= 1,\n      label: __('Delete', 'newspack'),\n      showTooltip: true\n    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n      className: \"newspack-buttons-card\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n      disabled: name.length === 0 || isLegacy && code.length === 0 || isInvalidSize,\n      isPrimary: true,\n      onClick: () => onSave(id)\n    }, __('Save', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n      isSecondary: true,\n      href: `#/${service}`\n    }, __('Cancel', 'newspack'))));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(AdUnit));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/ad-unit/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/ad-units/index.js":
/*!************************************************************!*\
  !*** ./assets/wizards/advertising/views/ad-units/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/esm/extends.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/pencil.js\");\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/library/trash.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _service_account_connection__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./service-account-connection */ \"./assets/wizards/advertising/views/ad-units/service-account-connection.js\");\n\n\n\n/**\n * Ad Unit Management Screens.\n */\n\n/**\n * WordPress dependencies\n */\n\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Advertising management screen.\n */\n\nconst AdUnits = _ref => {\n  var _serviceData$status, _serviceData$status2, _serviceData$created_;\n\n  let {\n    adUnits,\n    onDelete,\n    updateAdUnit,\n    wizardApiFetch,\n    updateWithAPI,\n    service,\n    serviceData,\n    fetchAdvertisingData\n  } = _ref;\n  const gamErrorMessage = serviceData !== null && serviceData !== void 0 && (_serviceData$status = serviceData.status) !== null && _serviceData$status !== void 0 && _serviceData$status.error ? `${__('Google Ad Manager Error', 'newspack')}: ${serviceData.status.error}` : false;\n  const [networkCode, setNetworkCode] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(serviceData.status.network_code);\n\n  const saveNetworkCode = async () => {\n    await wizardApiFetch({\n      path: '/newspack/v1/wizard/advertising/network_code/',\n      method: 'POST',\n      data: {\n        network_code: networkCode\n      },\n      quiet: true\n    });\n    fetchAdvertisingData(true);\n  };\n\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {\n    setNetworkCode(serviceData.status.network_code);\n  }, [serviceData.status.network_code]);\n  const {\n    can_use_service_account,\n    can_use_oauth,\n    connection_mode\n  } = serviceData.status;\n  const isLegacy = 'legacy' === connection_mode;\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, false === ((_serviceData$status2 = serviceData.status) === null || _serviceData$status2 === void 0 ? void 0 : _serviceData$status2.is_network_code_matched) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n    noticeText: __('Your GAM network code is different than the network code the site was configured with. Legacy ad units are likely to not load.', 'newspack'),\n    isWarning: true\n  }), gamErrorMessage && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n    noticeText: gamErrorMessage,\n    isError: true\n  }), ((_serviceData$created_ = serviceData.created_targeting_keys) === null || _serviceData$created_ === void 0 ? void 0 : _serviceData$created_.length) > 0 && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n    noticeText: [__('Created custom targeting keys:') + '\\u00A0', serviceData.created_targeting_keys.join(', ') + '. \\u00A0', (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.ExternalLink, {\n      href: `https://admanager.google.com/${serviceData.network_code}#inventory/custom_targeting/list`,\n      key: \"google-ad-manager-custom-targeting-link\"\n    }, __('Visit your GAM dashboard'))],\n    isSuccess: true\n  }), isLegacy && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (can_use_service_account || can_use_oauth) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Notice, {\n    noticeText: __('Currently operating in legacy mode.', 'newspack'),\n    isWarning: true\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n    className: \"flex items-end\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.TextControl, {\n    label: __('Network Code', 'newspack'),\n    value: networkCode,\n    onChange: setNetworkCode,\n    withMargin: false\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"span\", {\n    className: \"pl3\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Button, {\n    onClick: saveNetworkCode,\n    isPrimary: true\n  }, __('Save', 'newspack'))))), !isLegacy && networkCode && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"strong\", null, __('Connected GAM network code:', 'newspack'), \" \"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"code\", null, networkCode)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"p\", null, __('Set up multiple ad units to use on your homepage, articles and other places throughout your site.', 'newspack'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"br\", null), __('You can place ads through our Newspack Ad Block in the Editor, Newspack Ad widget, and using the global placements.', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Card, {\n    noBorder: true\n  }, Object.values(adUnits).filter(adUnit => adUnit.id !== 0).sort((a, b) => b.name.localeCompare(a.name)).sort(a => a.is_legacy ? 1 : -1).map(adUnit => {\n    const editLink = `#${service}/${adUnit.id}`;\n    const buttonProps = {\n      isQuaternary: true,\n      isSmall: true,\n      tooltipPosition: 'bottom center'\n    };\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.ActionCard, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n      key: adUnit.id,\n      title: adUnit.name,\n      isSmall: true,\n      titleLink: editLink,\n      className: \"mv0\"\n    }, adUnit.is_legacy ? {} : {\n      toggleChecked: adUnit.status === 'ACTIVE',\n      toggleOnChange: value => {\n        adUnit.status = value ? 'ACTIVE' : 'INACTIVE';\n        updateAdUnit(adUnit);\n      }\n    }, {\n      description: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"span\", null, adUnit.is_legacy ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"i\", null, __('Legacy ad unit.', 'newspack')), \" |\", ' ') : null, __('Sizes:', 'newspack'), ' ', adUnit.sizes.map((size, i) => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"code\", {\n        key: i\n      }, size.join('x'))), adUnit.fluid && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"code\", null, __('Fluid', 'newspack'))),\n      actionText: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"div\", {\n        className: \"flex items-center\"\n      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Button, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        href: editLink,\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_6__[\"default\"],\n        label: __('Edit the ad unit', 'newspack')\n      }, buttonProps)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_4__.Button, (0,_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__[\"default\"])({\n        onClick: () => onDelete(adUnit.id),\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"default\"],\n        label: __('Archive the ad unit', 'newspack')\n      }, buttonProps)))\n    }));\n  })), can_use_service_account && connection_mode !== 'oauth' && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_service_account_connection__WEBPACK_IMPORTED_MODULE_5__[\"default\"], {\n    className: \"mt3\",\n    updateWithAPI: updateWithAPI,\n    isConnected: serviceData.status.connected\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizardScreen)(AdUnits));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/ad-units/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/ad-units/service-account-connection.js":
/*!*********************************************************************************!*\
  !*** ./assets/wizards/advertising/views/ad-units/service-account-connection.js ***!
  \*********************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\nconst ServiceAccountConnection = _ref => {\n  let {\n    updateWithAPI,\n    isConnected,\n    ...props\n  } = _ref;\n  const credentialsInputFile = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useRef)(null);\n  const [fileError, setFileError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('');\n\n  const updateGAMCredentials = credentials => updateWithAPI({\n    path: '/newspack/v1/wizard/advertising/credentials',\n    method: 'post',\n    data: {\n      credentials\n    },\n    quiet: true\n  });\n\n  const removeGAMCredentials = () => updateWithAPI({\n    path: '/newspack/v1/wizard/advertising/credentials',\n    method: 'delete',\n    quiet: true\n  });\n\n  const handleCredentialsFile = event => {\n    if (event.target.files.length && event.target.files[0]) {\n      const reader = new FileReader();\n      reader.readAsText(event.target.files[0], 'UTF-8');\n\n      reader.onload = function (ev) {\n        let credentials;\n\n        try {\n          credentials = JSON.parse(ev.target.result);\n        } catch (error) {\n          setFileError(__('Invalid JSON file', 'newspack'));\n          return;\n        }\n\n        updateGAMCredentials(credentials);\n      };\n\n      reader.onerror = function () {\n        setFileError(__('Unable to read file', 'newspack'));\n      };\n    }\n  };\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", props, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"h2\", null, __('Service Account connection', 'newspack')), isConnected ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"div\", {\n    className: \"mb3\"\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n    onClick: () => credentialsInputFile.current.click(),\n    isSecondary: true\n  }, __('Update Service Account credentials', 'newspack')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Button, {\n    className: \"ml3\",\n    onClick: removeGAMCredentials,\n    isDestructive: true\n  }, __('Remove Service Account credentials', 'newspack'))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ButtonCard, {\n    onClick: () => credentialsInputFile.current.click(),\n    title: __('Connect your Google Ad Manager account', 'newspack'),\n    desc: [__('Upload your Service Account credentials file to connect your GAM account with Newspack Ads.', 'newspack'), fileError && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Notice, {\n      noticeText: fileError,\n      isError: true\n    })],\n    chevron: true\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"input\", {\n    type: \"file\",\n    accept: \".json\",\n    ref: credentialsInputFile,\n    style: {\n      display: 'none'\n    },\n    onChange: handleCredentialsFile\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (ServiceAccountConnection);\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/ad-units/service-account-connection.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/index.js":
/*!***************************************************!*\
  !*** ./assets/wizards/advertising/views/index.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Services\": function() { return /* reexport safe */ _services__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"Settings\": function() { return /* reexport safe */ _settings__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; },\n/* harmony export */   \"AdUnits\": function() { return /* reexport safe */ _ad_units__WEBPACK_IMPORTED_MODULE_2__[\"default\"]; },\n/* harmony export */   \"AdUnit\": function() { return /* reexport safe */ _ad_unit__WEBPACK_IMPORTED_MODULE_3__[\"default\"]; },\n/* harmony export */   \"Suppression\": function() { return /* reexport safe */ _suppression__WEBPACK_IMPORTED_MODULE_4__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _services__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./services */ \"./assets/wizards/advertising/views/services/index.js\");\n/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./settings */ \"./assets/wizards/advertising/views/settings/index.js\");\n/* harmony import */ var _ad_units__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ad-units */ \"./assets/wizards/advertising/views/ad-units/index.js\");\n/* harmony import */ var _ad_unit__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ad-unit */ \"./assets/wizards/advertising/views/ad-unit/index.js\");\n/* harmony import */ var _suppression__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./suppression */ \"./assets/wizards/advertising/views/suppression/index.js\");\n\n\n\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/services/index.js":
/*!************************************************************!*\
  !*** ./assets/wizards/advertising/views/services/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * Ad Services view.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n/**\n * Advertising management screen.\n */\n\nconst Services = _ref => {\n  var _google_ad_manager$cr, _google_ad_manager$cr2;\n\n  let {\n    services,\n    toggleService\n  } = _ref;\n  const {\n    wordads,\n    google_adsense,\n    google_ad_manager\n  } = services;\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(\"p\", null, __('Please enable and configure the ad providers you’d like to use to get started.')), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n    title: __('WordAds from WordPress.com'),\n    badge: __('Jetpack Premium'),\n    description: __('A managed ad optimization platform where the top 50 ad networks (DSPs and exchanges) compete for your traffic, with flexible placement options, and support from WordPress.com.'),\n    actionText: wordads && wordads.enabled && __('Configure'),\n    toggle: true,\n    toggleChecked: wordads && wordads.enabled,\n    toggleOnChange: value => toggleService('wordads', value),\n    href: wordads && '#/ad-placements',\n    notification: wordads.upgrade_required && [__('Upgrade Jetpack to enable WordAds.') + '\\u00A0', (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ExternalLink, {\n      href: \"/wp-admin/admin.php?page=jetpack#/plans\",\n      key: \"jetpack-link\"\n    }, __('Click to upgrade'))],\n    notificationLevel: 'info'\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n    title: __('Google AdSense'),\n    description: __('A simple way to place adverts on your news site automatically based on where they best perform.'),\n    actionText: google_adsense && google_adsense.enabled && __('Configure'),\n    toggle: true,\n    toggleChecked: google_adsense && google_adsense.enabled,\n    toggleOnChange: value => toggleService('google_adsense', value),\n    handoff: \"google-site-kit\",\n    editLink: \"admin.php?page=googlesitekit-module-adsense\"\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n    title: __('Google Ad Manager'),\n    description: __('An advanced ad inventory creation and management platform, allowing you to be specific about ad placements.'),\n    actionText: google_ad_manager && google_ad_manager.enabled && __('Configure'),\n    toggle: true,\n    toggleChecked: google_ad_manager && google_ad_manager.enabled,\n    toggleOnChange: value => toggleService('google_ad_manager', value),\n    titleLink: google_ad_manager ? '#/google_ad_manager' : null,\n    href: google_ad_manager && '#/google_ad_manager',\n    notification: google_ad_manager.status.error ? [google_ad_manager.status.error] : ((_google_ad_manager$cr = google_ad_manager.created_targeting_keys) === null || _google_ad_manager$cr === void 0 ? void 0 : _google_ad_manager$cr.length) > 0 && [__('Created custom targeting keys:') + '\\u00A0', google_ad_manager.created_targeting_keys.join(', ') + '. \\u00A0', // eslint-disable-next-line react/jsx-indent\n    (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ExternalLink, {\n      href: `https://admanager.google.com/${google_ad_manager.network_code}#inventory/custom_targeting/list`,\n      key: \"google-ad-manager-custom-targeting-link\"\n    }, __('Visit your GAM dashboard'))],\n    notificationLevel: (_google_ad_manager$cr2 = google_ad_manager.created_targeting_keys) !== null && _google_ad_manager$cr2 !== void 0 && _google_ad_manager$cr2.length ? 'success' : 'error'\n  }));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(Services));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/services/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/settings/ad-picker/index.js":
/*!**********************************************************************!*\
  !*** ./assets/wizards/advertising/views/settings/ad-picker/index.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/advertising/views/settings/ad-picker/style.scss\");\n\n\n\n/**\n * Ad Services view.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n/**\n * Ad Picker\n */\n\nclass AdPicker extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"adUnitsForSelect\", adUnits => {\n      return [{\n        label: '---',\n        value: null\n      }, ...Object.values(adUnits).map(adUnit => {\n        return {\n          label: adUnit.name,\n          value: adUnit.id,\n          disabled: adUnit.status === 'INACTIVE'\n        };\n      })];\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"adServicesForSelect\", services => {\n      return [{\n        label: '---',\n        value: null\n      }, ...Object.keys(services).map(key => services[key].enabled && {\n        label: services[key].label,\n        value: key\n      }).filter(option => option)];\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"needsAdUnit\", value => {\n      const {\n        services\n      } = this.props;\n      const {\n        service\n      } = value;\n      return 'google_ad_manager' === service && services.google_ad_manager && services.google_ad_manager.enabled;\n    });\n  }\n\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      adUnits,\n      onChange,\n      services,\n      value\n    } = this.props;\n    const {\n      service,\n      ad_unit: adUnit\n    } = value;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.Grid, {\n      gutter: 32\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n      label: __('Ad Provider', 'newspack'),\n      value: service || '',\n      options: this.adServicesForSelect(services),\n      onChange: _service => onChange({ ...value,\n        service: _service\n      })\n    }), this.needsAdUnit(value) && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {\n      label: __('Ad Unit', 'newspack'),\n      value: adUnit || '',\n      options: this.adUnitsForSelect(adUnits),\n      onChange: _adUnit => onChange({ ...value,\n        adUnit: _adUnit\n      })\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (AdPicker);\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/settings/ad-picker/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/settings/index.js":
/*!************************************************************!*\
  !*** ./assets/wizards/advertising/views/settings/index.js ***!
  \************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _ad_picker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./ad-picker */ \"./assets/wizards/advertising/views/settings/ad-picker/index.js\");\n\n\n\n/**\n * Ad Settings view.\n */\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__;\n\n\n/**\n * Advertising management screen.\n */\n\nclass Settings extends _wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Component {\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"adUnitsForSelect\", adUnits => {\n      return [{\n        label: __('Select an ad unit', 'newspack'),\n        value: null\n      }, ...Object.values(adUnits).map(adUnit => {\n        return {\n          label: adUnit.name,\n          value: adUnit.id\n        };\n      })];\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"adServicesForSelect\", services => {\n      return [{\n        label: __('Select an ad provider', 'newspack'),\n        value: null\n      }, ...Object.keys(services).map(key => {\n        return {\n          label: services[key].label,\n          value: key\n        };\n      })];\n    });\n  }\n\n  /**\n   * Render.\n   */\n  render() {\n    const {\n      togglePlacement,\n      placements,\n      adUnits,\n      services,\n      onChange\n    } = this.props;\n    const {\n      global_above_header,\n      global_below_header,\n      global_above_footer,\n      sticky\n    } = placements;\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.SectionHeader, {\n      title: __('Pre-defined ad placements', 'newspack'),\n      description: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.Fragment, null, __('Define global advertising placements to serve ad units on your site', 'newspack'), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(\"br\", null), __('Enable the individual pre-defined ad placements to select which ads to serve', 'newspack'))\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      isMedium: true,\n      title: __('Global: Above Header', 'newspack'),\n      description: __('Choose an ad unit to display above the header', 'newspack'),\n      toggleChecked: global_above_header && global_above_header.enabled,\n      hasGreyHeader: global_above_header && global_above_header.enabled,\n      toggleOnChange: value => togglePlacement('global_above_header', value)\n    }, global_above_header && global_above_header.enabled ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_ad_picker__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n      adUnits: adUnits,\n      services: services,\n      value: global_above_header,\n      onChange: value => onChange('global_above_header', value)\n    }) : null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      isMedium: true,\n      title: __('Global: Below Header', 'newspack'),\n      description: __('Choose an ad unit to display below the header', 'newspack'),\n      toggleChecked: global_below_header && global_below_header.enabled,\n      hasGreyHeader: global_below_header && global_below_header.enabled,\n      toggleOnChange: value => togglePlacement('global_below_header', value)\n    }, global_below_header && global_below_header.enabled ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_ad_picker__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n      adUnits: adUnits,\n      services: services,\n      value: global_below_header,\n      onChange: value => onChange('global_below_header', value)\n    }) : null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      isMedium: true,\n      title: __('Global: Above Footer', 'newspack'),\n      description: __('Choose an ad unit to display above the footer', 'newspack'),\n      toggleChecked: global_above_footer && global_above_footer.enabled,\n      hasGreyHeader: global_above_footer && global_above_footer.enabled,\n      toggleOnChange: value => togglePlacement('global_above_footer', value)\n    }, global_above_footer && global_above_footer.enabled ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_ad_picker__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n      adUnits: adUnits,\n      services: services,\n      value: global_above_footer,\n      onChange: value => onChange('global_above_footer', value)\n    }) : null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.ActionCard, {\n      isMedium: true,\n      title: __('Sticky', 'newspack'),\n      description: __('Choose a sticky ad unit to display at the bottom of the viewport', 'newspack'),\n      toggleChecked: sticky && sticky.enabled,\n      hasGreyHeader: sticky && sticky.enabled,\n      toggleOnChange: value => togglePlacement('sticky', value)\n    }, sticky && sticky.enabled ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_ad_picker__WEBPACK_IMPORTED_MODULE_4__[\"default\"], {\n      adUnits: adUnits,\n      services: services,\n      value: sticky,\n      onChange: value => onChange('sticky', value)\n    }) : null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_3__.PluginSettings, {\n      pluginSlug: \"newspack-ads\",\n      title: __('General Settings', 'newspack'),\n      description: __('Configure display and advanced settings for your ads.', 'newspack')\n    }));\n  }\n\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_3__.withWizardScreen)(Settings));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/settings/index.js?");

/***/ }),

/***/ "./assets/wizards/advertising/views/suppression/index.js":
/*!***************************************************************!*\
  !*** ./assets/wizards/advertising/views/suppression/index.js ***!
  \***************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n\n\n/**\n * WordPress dependencies\n */\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\nconst Suppression = _ref => {\n  let {\n    config,\n    onChange\n  } = _ref;\n\n  if (config === false) {\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Waiting, null);\n  }\n\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 64\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Tag Archive Pages', 'newspack'),\n    description: __('Suppress ads on automatically generated pages displaying a list of posts with a tag.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.CategoryAutocomplete, {\n    disabled: config.tag_archive_pages === true,\n    value: config.specific_tag_archive_pages.map(v => parseInt(v)),\n    onChange: selected => {\n      onChange({ ...config,\n        specific_tag_archive_pages: selected.map(item => item.id)\n      });\n    },\n    label: __('Specific tags archive pages', 'newspack '),\n    taxonomy: \"tags\"\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {\n    disabled: config === false,\n    checked: config === null || config === void 0 ? void 0 : config.tag_archive_pages,\n    onChange: tag_archive_pages => {\n      onChange({ ...config,\n        tag_archive_pages\n      });\n    },\n    label: __('All tag archive pages', 'newspack')\n  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Category Archive Pages', 'newspack'),\n    description: __('Suppress ads on automatically generated pages displaying a list of posts of a category.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.CategoryAutocomplete, {\n    disabled: config.category_archive_pages === true,\n    value: config.specific_category_archive_pages.map(v => parseInt(v)),\n    onChange: selected => {\n      onChange({ ...config,\n        specific_category_archive_pages: selected.map(item => item.id)\n      });\n    },\n    label: __('Specific category archive pages', 'newspack ')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {\n    disabled: config === false,\n    checked: config === null || config === void 0 ? void 0 : config.category_archive_pages,\n    onChange: category_archive_pages => {\n      onChange({ ...config,\n        category_archive_pages\n      });\n    },\n    label: __('All category archive pages', 'newspack')\n  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Author Archive Pages', 'newspack'),\n    description: __('Suppress ads on automatically generated pages displaying a list of posts by an author.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {\n    disabled: config === false,\n    checked: config === null || config === void 0 ? void 0 : config.author_archive_pages,\n    onChange: author_archive_pages => {\n      onChange({ ...config,\n        author_archive_pages\n      });\n    },\n    label: __('Suppress ads on author archive pages', 'newspack')\n  })));\n};\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(Suppression));\n\n//# sourceURL=webpack://newspack/./assets/wizards/advertising/views/suppression/index.js?");

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
/******/ 			"advertising": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/advertising/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;