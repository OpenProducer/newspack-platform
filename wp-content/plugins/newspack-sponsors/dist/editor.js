(function(e, a) { for(var i in a) e[i] = a[i]; }(window, /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/editor/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _defineProperty(obj, key, value) {\n  if (key in obj) {\n    Object.defineProperty(obj, key, {\n      value: value,\n      enumerable: true,\n      configurable: true,\n      writable: true\n    });\n  } else {\n    obj[key] = value;\n  }\n\n  return obj;\n}\n\nmodule.exports = _defineProperty;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/defineProperty.js?");

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/extends.js":
/*!********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/extends.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("function _extends() {\n  module.exports = _extends = Object.assign || function (target) {\n    for (var i = 1; i < arguments.length; i++) {\n      var source = arguments[i];\n\n      for (var key in source) {\n        if (Object.prototype.hasOwnProperty.call(source, key)) {\n          target[key] = source[key];\n        }\n      }\n    }\n\n    return target;\n  };\n\n  return _extends.apply(this, arguments);\n}\n\nmodule.exports = _extends;\n\n//# sourceURL=webpack:///./node_modules/@babel/runtime/helpers/extends.js?");

/***/ }),

/***/ "./src/editor/index.js":
/*!*****************************!*\
  !*** ./src/editor/index.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ \"@wordpress/hooks\");\n/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/plugins */ \"@wordpress/plugins\");\n/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _sidebar__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./sidebar */ \"./src/editor/sidebar/index.js\");\n/* harmony import */ var _taxonomy_panel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./taxonomy-panel */ \"./src/editor/taxonomy-panel/index.js\");\n/**\n * WordPress dependencies\n */\n\n\n/**\n * Internal dependencies\n */\n\n\n\n/**\n * Filter the PostTaxonomies component.\n */\n\nObject(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__[\"addFilter\"])('editor.PostTaxonomyType', 'newspack-sponsors-editor', _taxonomy_panel__WEBPACK_IMPORTED_MODULE_3__[\"TaxonomyPanel\"]);\n/**\n * Register plugin editor settings.\n */\n\nObject(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_1__[\"registerPlugin\"])('newspack-sponsors-editor', {\n  render: _sidebar__WEBPACK_IMPORTED_MODULE_2__[\"Sidebar\"],\n  icon: null\n});\n\n//# sourceURL=webpack:///./src/editor/index.js?");

/***/ }),

/***/ "./src/editor/sidebar/index.js":
/*!*************************************!*\
  !*** ./src/editor/sidebar/index.js ***!
  \*************************************/
/*! exports provided: Sidebar */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"Sidebar\", function() { return Sidebar; });\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ \"./node_modules/@babel/runtime/helpers/defineProperty.js\");\n/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/compose */ \"@wordpress/compose\");\n/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/edit-post */ \"@wordpress/edit-post\");\n/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_6__);\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./style.scss */ \"./src/editor/sidebar/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_7__);\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n\n\n/**\n * Internal dependencies\n */\n\n\n\nvar SidebarComponent = function SidebarComponent(props) {\n  if (props.postType !== 'newspack_spnsrs_cpt') {\n    return null;\n  }\n\n  var meta = props.meta,\n      title = props.title,\n      updateMetaValue = props.updateMetaValue;\n  var newspack_sponsor_url = meta.newspack_sponsor_url,\n      newspack_sponsor_flag_override = meta.newspack_sponsor_flag_override,\n      newspack_sponsor_byline_prefix = meta.newspack_sponsor_byline_prefix,\n      newspack_sponsor_sponsorship_scope = meta.newspack_sponsor_sponsorship_scope,\n      newspack_sponsor_only_direct = meta.newspack_sponsor_only_direct,\n      newspack_sponsor_disclaimer_override = meta.newspack_sponsor_disclaimer_override;\n  var _window$newspack_spon = window.newspack_sponsors_data,\n      settings = _window$newspack_spon.settings,\n      defaults = _window$newspack_spon.defaults;\n  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_6__[\"PluginDocumentSettingPanel\"], {\n    className: \"newspack-sponsors\",\n    name: \"newspack-sponsors\",\n    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsor Settings', 'newspack-sponsors')\n  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"SelectControl\"], {\n    className: \"newspack-sponsors__select-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsorship Scope', 'newspack-sponsors'),\n    value: newspack_sponsor_sponsorship_scope || 'native',\n    options: [{\n      value: 'native',\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Native content', 'newspack-sponsors')\n    }, {\n      value: 'underwritten',\n      label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Underwritten content', 'newspack-sponsors')\n    }],\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_sponsorship_scope', value);\n    },\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Generally, native content is authored by the sponsor, while underwritten content is authored by editorial staff but supported by the sponsor. This option allows you to select a different visual treatment for native vs. underwitten content.', 'newspack-sponsors')\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"TextControl\"], {\n    className: \"newspack-sponsors__text-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsor URL', 'newspack-sponsors'),\n    placeholder: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('URL to link to for this sponsor', 'newspack-sponsors'),\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Required if you want to link the sponsor logo to an external URL.', 'newspack-sponsors'),\n    type: \"url\",\n    value: newspack_sponsor_url,\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_url', value);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"TextControl\"], {\n    className: \"newspack-sponsors__text-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsor Flag Override (Optional)', 'newspack-sponsors'),\n    placeholder: settings.flag || defaults.flag,\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('The label for the flag that appears in lieu of category flags. If not empty, this field will override the site-wide setting.', 'newspack-sponsors'),\n    type: \"url\",\n    value: newspack_sponsor_flag_override,\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_flag_override', value);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"TextareaControl\"], {\n    className: \"newspack-sponsors__textarea-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsor Disclaimer Override (Optional)', 'newspack-sponsors'),\n    placeholder: (settings.disclaimer || defaults.disclaimer).replace('[sponsor name]', title),\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Text shown to explain sponsorship by this sponsor. If not empty, this field will override the site-wide setting.', 'newspack-sponsors'),\n    value: newspack_sponsor_disclaimer_override,\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_disclaimer_override', value);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"TextControl\"], {\n    className: \"newspack-sponsors__text-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Sponsor Byline Prefix (Optional)', 'newspack-sponsors'),\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('The prefix for the sponsor attribution that appears in lieu of author byline. If not empty, this field will override the site-wide setting.', 'newspack-sponsors'),\n    placeholder: settings.byline || defaults.byline,\n    type: \"url\",\n    value: newspack_sponsor_byline_prefix,\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_byline_prefix', value);\n    }\n  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__[\"ToggleControl\"], {\n    className: \"newspack-sponsors__toggle-control\",\n    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Show on posts only if a direct sponsor?', 'newspack-newsletters'),\n    help: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('If this option is enabled, this sponsor will only be shown on single posts if assigned as a direct sponsor. It will still appear on category and tag archive pages, if applicable.'),\n    checked: newspack_sponsor_only_direct,\n    onChange: function onChange(value) {\n      return updateMetaValue('newspack_sponsor_only_direct', value);\n    }\n  }));\n};\n\nvar mapStateToProps = function mapStateToProps(select) {\n  var _select = select('core/editor'),\n      getCurrentPostType = _select.getCurrentPostType,\n      getEditedPostAttribute = _select.getEditedPostAttribute;\n\n  return {\n    meta: getEditedPostAttribute('meta'),\n    postType: getCurrentPostType(),\n    title: getEditedPostAttribute('title')\n  };\n};\n\nvar mapDispatchToProps = function mapDispatchToProps(dispatch) {\n  var _dispatch = dispatch('core/editor'),\n      editPost = _dispatch.editPost;\n\n  return {\n    updateMetaValue: function updateMetaValue(key, value) {\n      return editPost({\n        meta: _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_0___default()({}, key, value)\n      });\n    }\n  };\n};\n\nvar Sidebar = Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_4__[\"compose\"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_5__[\"withSelect\"])(mapStateToProps), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_5__[\"withDispatch\"])(mapDispatchToProps)])(SidebarComponent);\n\n//# sourceURL=webpack:///./src/editor/sidebar/index.js?");

/***/ }),

/***/ "./src/editor/sidebar/style.scss":
/*!***************************************!*\
  !*** ./src/editor/sidebar/style.scss ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/editor/sidebar/style.scss?");

/***/ }),

/***/ "./src/editor/taxonomy-panel/index.js":
/*!********************************************!*\
  !*** ./src/editor/taxonomy-panel/index.js ***!
  \********************************************/
/*! exports provided: TaxonomyPanel */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"TaxonomyPanel\", function() { return TaxonomyPanel; });\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/extends */ \"./node_modules/@babel/runtime/helpers/extends.js\");\n/* harmony import */ var _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ \"@wordpress/element\");\n/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/data */ \"@wordpress/data\");\n/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__);\n\n\n\n/**\n * WordPress dependencies\n */\n\n\n\n/**\n * Filters the PostTaxonomies component to add explanations unique to Newspack Sponsor posts.\n *\n * @param {Function} PostTaxonomies The original PostTaxonomies component to filter.\n *                                         https://github.com/WordPress/gutenberg/tree/master/packages/editor/src/components/post-taxonomies\n * @return {Function} The filtered component.\n */\n\nvar TaxonomyPanel = function TaxonomyPanel(PostTaxonomies) {\n  return function (props) {\n    var postType = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_3__[\"select\"])('core/editor').getCurrentPostType();\n\n    if ('newspack_spnsrs_cpt' !== postType && 'post' !== postType) {\n      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(PostTaxonomies, props);\n    }\n\n    var slug = props.slug,\n        taxonomy = props.taxonomy;\n    var hierarchical = taxonomy.hierarchical,\n        labels = taxonomy.labels;\n    var message = Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"sprintf\"])(Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])( // Translators: explanation for applying sponsors to a taxonomy term.\n    '%s one or more post %s to associate this sponsor with those %s.', 'newspack-sponsors'), hierarchical ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Select ', 'newspack-sponsors') : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__[\"__\"])('Add ', 'newspack-sponsors'), labels.name.toLowerCase(), labels.name.toLowerCase());\n    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"Fragment\"], null, 'newspack_spnsrs_cpt' === postType && (slug === 'category' || slug === 'post_tag') && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"p\", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(\"em\", null, message)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__[\"createElement\"])(PostTaxonomies, _babel_runtime_helpers_extends__WEBPACK_IMPORTED_MODULE_0___default()({}, props, {\n      // Remove \"Add new sponsors\" link since sponsor terms are shadow terms of sponsor posts.\n      hasCreateAction: 'newspack_spnsrs_tax' !== slug\n    })));\n  };\n};\n\n//# sourceURL=webpack:///./src/editor/taxonomy-panel/index.js?");

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"components\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22components%22%5D%7D?");

/***/ }),

/***/ "@wordpress/compose":
/*!******************************************!*\
  !*** external {"this":["wp","compose"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"compose\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22compose%22%5D%7D?");

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"data\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22data%22%5D%7D?");

/***/ }),

/***/ "@wordpress/edit-post":
/*!*******************************************!*\
  !*** external {"this":["wp","editPost"]} ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"editPost\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22editPost%22%5D%7D?");

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"element\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22element%22%5D%7D?");

/***/ }),

/***/ "@wordpress/hooks":
/*!****************************************!*\
  !*** external {"this":["wp","hooks"]} ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"hooks\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22hooks%22%5D%7D?");

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"i18n\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22i18n%22%5D%7D?");

/***/ }),

/***/ "@wordpress/plugins":
/*!******************************************!*\
  !*** external {"this":["wp","plugins"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("(function() { module.exports = this[\"wp\"][\"plugins\"]; }());\n\n//# sourceURL=webpack:///external_%7B%22this%22:%5B%22wp%22,%22plugins%22%5D%7D?");

/***/ })

/******/ })));