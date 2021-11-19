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

/***/ "./assets/wizards/site-design/views/theme-settings/style.scss":
/*!********************************************************************!*\
  !*** ./assets/wizards/site-design/views/theme-settings/style.scss ***!
  \********************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/site-design/views/theme-settings/style.scss?");

/***/ }),

/***/ "./assets/wizards/site-design/index.js":
/*!*********************************************!*\
  !*** ./assets/wizards/site-design/index.js ***!
  \*********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/esm/defineProperty.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../components/src/proxied-imports/router */ \"./assets/components/src/proxied-imports/router.js\");\n/* harmony import */ var _views__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./views */ \"./assets/wizards/site-design/views/index.js\");\n\n\n/**\n * WordPress dependencies.\n */\n\n\n\n/**\n * Internal dependencies.\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__;\n\n\n\nconst {\n  HashRouter,\n  Redirect,\n  Route,\n  Switch\n} = _components_src_proxied_imports_router__WEBPACK_IMPORTED_MODULE_5__[\"default\"];\n/**\n * Site Design Wizard.\n */\n\nclass SiteDesignWizard extends _wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Component {\n  constructor() {\n    super(...arguments);\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"componentDidMount\", () => {\n      const {\n        setError,\n        wizardApiFetch\n      } = this.props;\n      const params = {\n        path: '/newspack/v1/wizard/newspack-setup-wizard/theme',\n        method: 'GET'\n      };\n      wizardApiFetch(params).then(response => this.setState({\n        themeMods: response.theme_mods\n      })).catch(setError);\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"setThemeMods\", themeModUpdates => this.setState({\n      themeMods: { ...this.state.themeMods,\n        ...themeModUpdates\n      }\n    }));\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"updateThemeMods\", () => {\n      const {\n        setError,\n        wizardApiFetch\n      } = this.props;\n      const {\n        themeMods\n      } = this.state;\n      const params = {\n        path: '/newspack/v1/wizard/newspack-setup-wizard/theme/',\n        method: 'POST',\n        data: {\n          theme_mods: themeMods\n        },\n        quiet: true\n      };\n      wizardApiFetch(params).then(response => {\n        const {\n          theme,\n          theme_mods\n        } = response;\n        this.setState({\n          theme,\n          themeMods: theme_mods\n        });\n      }).catch(error => {\n        console.log('[Theme Update Error]', error);\n        setError({\n          error\n        });\n      });\n    });\n\n    (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__[\"default\"])(this, \"state\", {});\n  }\n\n  /**\n   * Render\n   */\n  render() {\n    const {\n      pluginRequirements,\n      wizardApiFetch,\n      setError\n    } = this.props;\n    const tabbedNavigation = [{\n      label: __('Design'),\n      path: '/',\n      exact: true\n    }, {\n      label: __('Settings'),\n      path: '/settings',\n      exact: true\n    }];\n    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(HashRouter, {\n      hashType: \"slash\"\n    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Switch, null, pluginRequirements, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/\",\n      exact: true,\n      render: () => {\n        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.Main, {\n          headerText: __('Site Design', 'newspack'),\n          subHeaderText: __('Configure your Newspack theme', 'newspack'),\n          tabbedNavigation: tabbedNavigation,\n          wizardApiFetch: wizardApiFetch,\n          setError: setError,\n          isPartOfSetup: false\n        });\n      }\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Route, {\n      path: \"/settings\",\n      exact: true,\n      render: () => {\n        const {\n          themeMods\n        } = this.state;\n        return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(_views__WEBPACK_IMPORTED_MODULE_6__.ThemeSettings, {\n          headerText: __('Site Design', 'newspack'),\n          subHeaderText: __('Configure your Newspack theme', 'newspack'),\n          tabbedNavigation: tabbedNavigation,\n          themeMods: themeMods,\n          setThemeMods: this.setThemeMods,\n          buttonText: __('Save', 'newspack'),\n          buttonAction: this.updateThemeMods,\n          secondaryButtonText: __('Advanced settings', 'newspack'),\n          secondaryButtonAction: \"/wp-admin/customize.php\"\n        });\n      }\n    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)(Redirect, {\n      to: \"/\"\n    }))));\n  }\n\n}\n\n(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.render)((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.createElement)((0,_components_src__WEBPACK_IMPORTED_MODULE_4__.withWizard)(SiteDesignWizard)), document.getElementById('newspack-site-design-wizard'));\n\n//# sourceURL=webpack://newspack/./assets/wizards/site-design/index.js?");

/***/ }),

/***/ "./assets/wizards/site-design/views/index.js":
/*!***************************************************!*\
  !*** ./assets/wizards/site-design/views/index.js ***!
  \***************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"Main\": function() { return /* reexport safe */ _main__WEBPACK_IMPORTED_MODULE_0__[\"default\"]; },\n/* harmony export */   \"ThemeSettings\": function() { return /* reexport safe */ _theme_settings__WEBPACK_IMPORTED_MODULE_1__[\"default\"]; }\n/* harmony export */ });\n/* harmony import */ var _main__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main */ \"./assets/wizards/site-design/views/main/index.js\");\n/* harmony import */ var _theme_settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./theme-settings */ \"./assets/wizards/site-design/views/theme-settings/index.js\");\n\n\n\n//# sourceURL=webpack://newspack/./assets/wizards/site-design/views/index.js?");

/***/ }),

/***/ "./assets/wizards/site-design/views/theme-settings/index.js":
/*!******************************************************************!*\
  !*** ./assets/wizards/site-design/views/theme-settings/index.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../components/src */ \"./assets/components/src/index.js\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ \"./assets/wizards/site-design/views/theme-settings/style.scss\");\n\n\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\nconst __ = _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__;\n\n\n/**\n * Theme Settings Screen.\n */\n\nconst ThemeSettings = props => {\n  const [imageThumbnail, setImageThumbnail] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);\n  const {\n    themeMods,\n    setThemeMods\n  } = props;\n  const {\n    show_author_bio: authorBio = true,\n    show_author_email: authorEmail = false,\n    author_bio_length: authorBioLength = 200,\n    featured_image_default: featuredImageDefault,\n    newspack_image_credits_placeholder_url: imageCreditsPlaceholderUrl,\n    newspack_image_credits_class_name: imageCreditsClassName = '',\n    newspack_image_credits_prefix_label: imageCreditsPrefix = ''\n  } = themeMods;\n  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {\n    if (imageCreditsPlaceholderUrl) {\n      setImageThumbnail(imageCreditsPlaceholderUrl);\n    }\n  }, [imageCreditsPlaceholderUrl]);\n  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Author Bio', 'newspack'),\n    description: __('Control how author bios are displayed on posts.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    gutter: 32\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ToggleGroup, {\n    title: __('Author bio', 'newspack'),\n    description: __('Display an author bio under individual posts.', 'newspack'),\n    checked: authorBio,\n    onChange: value => setThemeMods({\n      show_author_bio: value\n    })\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {\n    isDark: true,\n    label: __('Author email', 'newspack'),\n    help: __('Display the author email with bio on individual posts.', 'newspack'),\n    checked: authorEmail,\n    onChange: value => setThemeMods({\n      show_author_email: value\n    })\n  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, authorBio && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n    label: __('Length', 'newspack'),\n    help: __('Truncates the author bio on single posts to this approximate character length, but without breaking a word. The full bio appears on the author archive page.', 'newspack'),\n    type: \"number\",\n    value: authorBioLength,\n    onChange: value => setThemeMods({\n      author_bio_length: value\n    })\n  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Featured Image', 'newspack'),\n    description: __('Set a default featured image position for new posts.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.RadioControl, {\n    label: __('Default position', 'newspack'),\n    selected: featuredImageDefault || 'large',\n    options: [{\n      label: __('Large', 'newspack'),\n      value: 'large'\n    }, {\n      label: __('Small', 'newspack'),\n      value: 'small'\n    }, {\n      label: __('Behind article title', 'newspack'),\n      value: 'behind'\n    }, {\n      label: __('Beside article title', 'newspack'),\n      value: 'beside'\n    }, {\n      label: __('Hidden', 'newspack'),\n      value: 'hidden'\n    }],\n    onChange: value => setThemeMods({\n      featured_image_default: value\n    })\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.SectionHeader, {\n    title: __('Media Credits', 'newspack'),\n    description: __('Control how credits are displayed alongside media attachments.', 'newspack')\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    gutter: 32\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n    label: __('Credit Class Name', 'newspack'),\n    help: __('A CSS class name to be applied to all image credit elements. Leave blank to display no class name.', 'newspack'),\n    value: imageCreditsClassName,\n    onChange: value => setThemeMods({\n      newspack_image_credits_class_name: value\n    })\n  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.TextControl, {\n    label: __('Credit Label', 'newspack'),\n    help: __('A label to prefix all media credits. Leave blank to display no prefix.', 'newspack'),\n    value: imageCreditsPrefix,\n    onChange: value => setThemeMods({\n      newspack_image_credits_prefix_label: value\n    })\n  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.Grid, {\n    columns: 1,\n    gutter: 16\n  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_src__WEBPACK_IMPORTED_MODULE_2__.ImageUpload, {\n    image: imageThumbnail ? {\n      url: imageThumbnail\n    } : null,\n    label: __('Placeholder Image', 'newspack'),\n    buttonLabel: __('Select', 'newspack'),\n    onChange: image => {\n      setImageThumbnail((image === null || image === void 0 ? void 0 : image.url) || null);\n      setThemeMods({\n        newspack_image_credits_placeholder: (image === null || image === void 0 ? void 0 : image.id) || null\n      });\n    },\n    help: __('A placeholder image to be displayed in place of images without credits. If none is chosen, the image will be displayed normally whether or not it has a credit.', 'newspack')\n  }))));\n};\n\nThemeSettings.defaultProps = {\n  themeMods: {},\n  setThemeMods: () => null\n};\n/* harmony default export */ __webpack_exports__[\"default\"] = ((0,_components_src__WEBPACK_IMPORTED_MODULE_2__.withWizardScreen)(ThemeSettings));\n\n//# sourceURL=webpack://newspack/./assets/wizards/site-design/views/theme-settings/index.js?");

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
/******/ 			"site-design": 0
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
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["commons"], function() { return __webpack_require__("./assets/wizards/site-design/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;