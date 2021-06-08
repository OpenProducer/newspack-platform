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
/******/ 		"componentsDemo": 0
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
/******/ 	deferredModules.push(["./assets/wizards/componentsDemo/index.js","commons"]);
/******/ 	// run deferred modules when ready
/******/ 	return checkDeferredModules();
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/wizards/componentsDemo/index.js":
/*!************************************************!*\
  !*** ./assets/wizards/componentsDemo/index.js ***!
  \************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ \"./node_modules/@babel/runtime/helpers/classCallCheck.js\");\n/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ \"./node_modules/@babel/runtime/helpers/createClass.js\");\n/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ \"./node_modules/@babel/runtime/helpers/inherits.js\");\n/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/createSuper */ \"./node_modules/@babel/runtime/helpers/createSuper.js\");\n/* harmony import */ var _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../shared/js/public-path */ \"./assets/shared/js/public-path.js\");\n/* harmony import */ var _shared_js_public_path__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_shared_js_public_path__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/icons */ \"./node_modules/@wordpress/icons/build-module/index.js\");\n/* harmony import */ var _components_src__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../components/src */ \"./assets/components/src/index.js\");\n\n\n\n\n\n\n/* eslint-disable jsx-a11y/anchor-is-valid */\n\n/**\n * Components Demo\n */\n\n/**\n * WordPress dependencies.\n */\n\n\n\n\n/**\n * Internal dependencies.\n */\n\n\n\nvar ComponentsDemo = /*#__PURE__*/function (_Component) {\n  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2___default()(ComponentsDemo, _Component);\n\n  var _super = _babel_runtime_helpers_createSuper__WEBPACK_IMPORTED_MODULE_3___default()(ComponentsDemo);\n\n  /**\n   * constructor. Demo of how the parent interacts with the components, and controls their values.\n   */\n  function ComponentsDemo() {\n    var _this;\n\n    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, ComponentsDemo);\n\n    _this = _super.apply(this, arguments);\n    _this.state = {\n      selectedPostForAutocompleteWithSuggestions: [],\n      selectedPostsForAutocompleteWithSuggestionsMultiSelect: [],\n      inputTextValue1: 'Input value',\n      inputTextValue2: '',\n      inputNumValue: 0,\n      image: null,\n      selectValue1: '2nd',\n      selectValue2: '',\n      modalShown: false,\n      toggleGroupChecked: false,\n      color1: '#3366ff'\n    };\n    return _this;\n  }\n  /**\n   * Render the example stub.\n   */\n\n\n  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(ComponentsDemo, [{\n    key: \"render\",\n    value: function render() {\n      var _this2 = this;\n\n      var _this$state = this.state,\n          selectedPostForAutocompleteWithSuggestions = _this$state.selectedPostForAutocompleteWithSuggestions,\n          selectedPostsForAutocompleteWithSuggestionsMultiSelect = _this$state.selectedPostsForAutocompleteWithSuggestionsMultiSelect,\n          inputTextValue1 = _this$state.inputTextValue1,\n          inputTextValue2 = _this$state.inputTextValue2,\n          inputNumValue = _this$state.inputNumValue,\n          selectValue1 = _this$state.selectValue1,\n          selectValue2 = _this$state.selectValue2,\n          modalShown = _this$state.modalShown,\n          actionCardToggleChecked = _this$state.actionCardToggleChecked,\n          toggleGroupChecked = _this$state.toggleGroupChecked,\n          color1 = _this$state.color1;\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard__header\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard__header__inner\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h1\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Components')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Demo of all the Newspack components')))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"div\", {\n        className: \"newspack-wizard newspack-wizard__content\"\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Autocomplete with Suggestions (single-select)', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"AutocompleteWithSuggestions\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Search for a post', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Begin typing post title, click autocomplete result to select.', 'newspack'),\n        onChange: function onChange(items) {\n          return _this2.setState({\n            selectedPostForAutocompleteWithSuggestions: items\n          });\n        },\n        selectedItems: selectedPostForAutocompleteWithSuggestions\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"hr\", null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Autocomplete with Suggestions (multi-select)', 'newspack')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"AutocompleteWithSuggestions\"], {\n        hideHelp: true,\n        multiSelect: true,\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Search widgets', 'newspack'),\n        help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Begin typing post title, click autocomplete result to select.', 'newspack'),\n        onChange: function onChange(items) {\n          return _this2.setState({\n            selectedPostsForAutocompleteWithSuggestionsMultiSelect: items\n          });\n        },\n        postTypes: [{\n          slug: 'page',\n          label: 'Pages'\n        }, {\n          slug: 'post',\n          label: 'Posts'\n        }],\n        postTypeLabel: 'widget',\n        postTypeLabelPlural: 'widgets',\n        selectedItems: selectedPostsForAutocompleteWithSuggestionsMultiSelect\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Plugin toggles')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"PluginToggle\"], {\n        plugins: {\n          woocommerce: {\n            shouldRefreshAfterUpdate: true\n          },\n          'fb-instant-articles': {\n            actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Configure Instant Articles'),\n            href: '/wp-admin/admin.php?page=newspack'\n          }\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Web Previews')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"WebPreview\"], {\n        url: \"//newspack.pub/\",\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Preview Newspack Blog', 'newspack'),\n        isPrimary: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"WebPreview\"], {\n        url: \"//newspack.pub/\",\n        renderButton: function renderButton(_ref) {\n          var showPreview = _ref.showPreview;\n          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n            href: \"#\",\n            onClick: showPreview\n          }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Preview Newspack Blog', 'newspack'));\n        }\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Color picker')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ColorPicker\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Color Picker'),\n        color: color1,\n        onChange: function onChange(color) {\n          return _this2.setState({\n            color1: color\n          });\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ToggleGroup\"], {\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Example Toggle Group'),\n        description: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is the description of a toggle group.'),\n        checked: toggleGroupChecked,\n        onChange: function onChange(checked) {\n          return _this2.setState({\n            toggleGroupChecked: checked\n          });\n        }\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is the content of the toggle group')))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Handoff Buttons')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Handoff\"], {\n        modalTitle: \"Manage AMP\",\n        modalBody: \"Click to go to the AMP dashboard. There will be a notification bar at the top with a link to return to Newspack.\",\n        plugin: \"amp\",\n        isTertiary: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Handoff\"], {\n        plugin: \"jetpack\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Handoff\"], {\n        plugin: \"google-site-kit\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Handoff\"], {\n        plugin: \"woocommerce\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Handoff\"], {\n        plugin: \"wordpress-seo\",\n        isPrimary: true,\n        editLink: \"/wp-admin/admin.php?page=wpseo_dashboard#top#features\"\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Specific Yoast Page')))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Modal')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isPrimary: true,\n        onClick: function onClick() {\n          return _this2.setState({\n            modalShown: true\n          });\n        }\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Open modal'))), modalShown && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Modal\"], {\n        title: \"This is the modal title\",\n        onRequestClose: function onRequestClose() {\n          return _this2.setState({\n            modalShown: false\n          });\n        }\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"p\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Based on industry research, we advise to test the modal component, and continuing this sentence so we can see how the text wraps is one good way of doing that.')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isPrimary: true,\n        onClick: function onClick() {\n          return _this2.setState({\n            modalShown: false\n          });\n        }\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Dismiss')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isSecondary: true,\n        onClick: function onClick() {\n          return _this2.setState({\n            modalShown: false\n          });\n        }\n      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Also dismiss'))))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Notice')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is an info notice.')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is an error notice.'),\n        isError: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is a help notice.'),\n        isHelp: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is a success notice.'),\n        isSuccess: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Notice\"], {\n        noticeText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('This is a warning notice.'),\n        isWarning: true\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Plugin installer')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"PluginInstaller\"], {\n        plugins: ['woocommerce', 'amp', 'wordpress-seo'],\n        canUninstall: true,\n        onStatus: function onStatus(_ref2) {\n          var complete = _ref2.complete,\n              pluginInfo = _ref2.pluginInfo;\n          console.log(complete ? 'All plugins installed successfully' : 'Plugin installation incomplete', pluginInfo);\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Plugin installer (small)')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"PluginInstaller\"], {\n        plugins: ['woocommerce', 'amp', 'wordpress-seo'],\n        isSmall: true,\n        canUninstall: true,\n        onStatus: function onStatus(_ref3) {\n          var complete = _ref3.complete,\n              pluginInfo = _ref3.pluginInfo;\n          console.log(complete ? 'All plugins installed successfully' : 'Plugin installation incomplete', pluginInfo);\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"PluginInstaller\"], {\n        plugins: ['woocommerce', 'amp', 'wordpress-seo'],\n        onStatus: function onStatus(_ref4) {\n          var complete = _ref4.complete,\n              pluginInfo = _ref4.pluginInfo;\n          console.log(complete ? 'All plugins installed successfully' : 'Plugin installation incomplete', pluginInfo);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"PluginInstaller\"], {\n        plugins: ['woocommerce', 'amp', 'wordpress-seo'],\n        isSmall: true,\n        onStatus: function onStatus(_ref5) {\n          var complete = _ref5.complete,\n              pluginInfo = _ref5.pluginInfo;\n          console.log(complete ? 'All plugins installed successfully' : 'Plugin installation incomplete', pluginInfo);\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example One\",\n        description: \"Has an action button.\",\n        actionText: \"Install\",\n        onClick: function onClick() {\n          console.log('Install clicked');\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Two\",\n        description: \"Has action button and secondary button (visible on hover).\",\n        actionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Edit'),\n        secondaryActionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Delete'),\n        onClick: function onClick() {\n          console.log('Edit clicked');\n        },\n        onSecondaryActionClick: function onSecondaryActionClick() {\n          console.log('Delete clicked');\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Three\",\n        description: \"Waiting/in-progress state, no action button.\",\n        actionText: \"Installing...\",\n        isWaiting: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Four\",\n        description: \"Error notification\",\n        actionText: \"Install\",\n        onClick: function onClick() {\n          console.log('Install clicked');\n        },\n        notification: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, \"Plugin cannot be installed \", Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n          href: \"#\"\n        }, \"Retry\"), \" | \", Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n          href: \"#\"\n        }, \"Documentation\")),\n        notificationLevel: \"error\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Five\",\n        description: \"Warning notification, action button\",\n        notification: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Fragment\"], null, \"There is a new version available. \", Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n          href: \"#\"\n        }, \"View details\"), \" or\", ' ', Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"a\", {\n          href: \"#\"\n        }, \"update now\")),\n        notificationLevel: \"warning\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Six\",\n        description: \"Static text, no button\",\n        actionText: \"Active\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Seven\",\n        description: \"Static text, secondary action button.\",\n        actionText: \"Active\",\n        secondaryActionText: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Delete'),\n        onSecondaryActionClick: function onSecondaryActionClick() {\n          console.log('Delete clicked');\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Eight\",\n        description: \"Image with link and action button.\",\n        actionText: \"Set Up\",\n        onClick: function onClick() {\n          console.log('Set Up');\n        },\n        image: \"//s1.wp.com/wp-content/themes/h4/landing/marketing/pages/hp-jan-2019/media/man-with-shadow.jpg\",\n        imageLink: \"https://wordpress.com\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Example Nine\",\n        description: \"Action Card with Toggle Control.\",\n        actionText: actionCardToggleChecked && 'Set Up',\n        onClick: function onClick() {\n          console.log('Set Up');\n        },\n        toggleOnChange: function toggleOnChange(checked) {\n          return _this2.setState({\n            actionCardToggleChecked: checked\n          });\n        },\n        toggleChecked: actionCardToggleChecked\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        badge: \"Premium\",\n        title: \"Example Ten\",\n        description: \"An example of an action card with a badge.\",\n        actionText: \"Install\",\n        onClick: function onClick() {\n          console.log('Install clicked');\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        isSmall: true,\n        title: \"Example Eleven (small)\",\n        description: \"An example of a small action card.\",\n        actionText: \"Install\",\n        onClick: function onClick() {\n          console.log('Install clicked');\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Handoff\",\n        description: \"An example of an action card with Handoff.\",\n        actionText: \"Configure\",\n        handoff: \"jetpack\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ActionCard\"], {\n        title: \"Handoff\",\n        description: \"An example of an action card with Handoff and EditLink.\",\n        actionText: \"Configure\",\n        handoff: \"jetpack\",\n        editLink: \"admin.php?page=jetpack#/settings\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Checkboxes')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"CheckboxControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Checkbox is tested?'),\n        onChange: function onChange() {\n          console.log(\"Yep, it's tested\");\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"CheckboxControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Checkbox w/Tooltip'),\n        onChange: function onChange() {\n          console.log(\"Yep, it's tested\");\n        },\n        tooltip: \"This is tooltip text\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"CheckboxControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Checkbox w/Help'),\n        onChange: function onChange() {\n          console.log(\"Yep, it's tested\");\n        },\n        help: \"This is help text\"\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Image Uploader')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ImageUpload\"], {\n        image: this.state.image,\n        onChange: function onChange(image) {\n          _this2.setState({\n            image: image\n          });\n\n          console.log('Image:');\n          console.log(image);\n        }\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Text Inputs')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"TextControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Text Input with value'),\n        value: inputTextValue1,\n        onChange: function onChange(value) {\n          return _this2.setState({\n            inputTextValue1: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"TextControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Text Input empty'),\n        value: inputTextValue2,\n        onChange: function onChange(value) {\n          return _this2.setState({\n            inputTextValue2: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"TextControl\"], {\n        type: \"number\",\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Number Input'),\n        value: inputNumValue,\n        onChange: function onChange(value) {\n          return _this2.setState({\n            inputNumValue: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"TextControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Text Input disabled'),\n        disabled: true\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Progress bar')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ProgressBar\"], {\n        completed: \"2\",\n        total: \"3\"\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ProgressBar\"], {\n        completed: \"2\",\n        total: \"5\",\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Progress made')\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ProgressBar\"], {\n        completed: \"0\",\n        total: \"5\",\n        displayFraction: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ProgressBar\"], {\n        completed: \"3\",\n        total: \"8\",\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Progress made'),\n        displayFraction: true\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Select dropdowns')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"SelectControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Label for Select with a preselection'),\n        value: selectValue1,\n        options: [{\n          value: '',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('- Select -'),\n          disabled: true\n        }, {\n          value: '1st',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('First')\n        }, {\n          value: '2nd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Second')\n        }, {\n          value: '3rd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Third')\n        }],\n        onChange: function onChange(value) {\n          return _this2.setState({\n            selectValue1: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"SelectControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Label for Select with no preselection'),\n        value: selectValue2,\n        options: [{\n          value: '',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('- Select -'),\n          disabled: true\n        }, {\n          value: '1st',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('First')\n        }, {\n          value: '2nd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Second')\n        }, {\n          value: '3rd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Third')\n        }],\n        onChange: function onChange(value) {\n          return _this2.setState({\n            selectValue2: value\n          });\n        }\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"SelectControl\"], {\n        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Label for disabled Select'),\n        disabled: true,\n        options: [{\n          value: '',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('- Select -'),\n          disabled: true\n        }, {\n          value: '1st',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('First')\n        }, {\n          value: '2nd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Second')\n        }, {\n          value: '3rd',\n          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Third')\n        }]\n      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Buttons')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h3\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Default')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isPrimary: true\n      }, \"isPrimary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isSecondary: true\n      }, \"isSecondary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isTertiary: true\n      }, \"isTertiary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isQuaternary: true\n      }, \"isQuaternary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isLink: true\n      }, \"isLink\")), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h3\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Disabled')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isPrimary: true,\n        disabled: true\n      }, \"isPrimary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isSecondary: true,\n        disabled: true\n      }, \"isSecondary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isTertiary: true,\n        disabled: true\n      }, \"isTertiary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isQuaternary: true,\n        disabled: true\n      }, \"isQuaternary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isLink: true,\n        disabled: true\n      }, \"isLink\")), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h3\", null, \"isSmall\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], {\n        buttonsCard: true,\n        noBorder: true\n      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isPrimary: true,\n        isSmall: true\n      }, \"isPrimary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isSecondary: true,\n        isSmall: true\n      }, \"isSecondary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isTertiary: true,\n        isSmall: true\n      }, \"isTertiary\"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Button\"], {\n        isQuaternary: true,\n        isSmall: true\n      }, \"isQuaternary\"))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Card\"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(\"h2\", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('ButtonCard')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ButtonCard\"], {\n        href: \"admin.php?page=newspack-site-design-wizard\",\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Site Design', 'newspack'),\n        desc: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Branding, color, typography, layouts', 'newspack'),\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"typography\"],\n        chevron: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ButtonCard\"], {\n        href: \"#\",\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Start a new site', 'newspack'),\n        desc: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])(\"You don't have content to import\", 'newspack'),\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"plus\"],\n        className: \"br--top\",\n        grouped: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ButtonCard\"], {\n        href: \"#\",\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Migrate an existing site', 'newspack'),\n        desc: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('You have content to import', 'newspack'),\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"reusableBlock\"],\n        className: \"br--bottom\",\n        grouped: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ButtonCard\"], {\n        href: \"#\",\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Add a new Podcast', 'newspack'),\n        desc: \"isSmall\",\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"audio\"],\n        className: \"br--top\",\n        isSmall: true,\n        grouped: true\n      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"ButtonCard\"], {\n        href: \"#\",\n        title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_6__[\"__\"])('Add a new Font', 'newspack'),\n        desc: \"isSmall + chevron\",\n        icon: _wordpress_icons__WEBPACK_IMPORTED_MODULE_7__[\"typography\"],\n        className: \"br--bottom\",\n        chevron: true,\n        isSmall: true,\n        grouped: true\n      }))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(_components_src__WEBPACK_IMPORTED_MODULE_8__[\"Footer\"], null));\n    }\n  }]);\n\n  return ComponentsDemo;\n}(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"Component\"]);\n\nObject(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"render\"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_4__[\"createElement\"])(ComponentsDemo, null), document.getElementById('newspack-components-demo'));\n\n//# sourceURL=webpack:///./assets/wizards/componentsDemo/index.js?");

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

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = window[\"React\"]; }());\n\n//# sourceURL=webpack:///external_%22React%22?");

/***/ })

/******/ })));