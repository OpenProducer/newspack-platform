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
/******/ 	return __webpack_require__(__webpack_require__.s = "./newspack-theme/js/src/style-packs-customizer.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./newspack-theme/js/src/style-packs-customizer.js":
/*!*********************************************************!*\
  !*** ./newspack-theme/js/src/style-packs-customizer.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * File style-packs-customizer.js.\n *\n * Based on functionality in Radcliffe 2:\n * https://github.com/Automattic/themes/blob/master/radcliffe-2/assets/js/style-packs-customizer.js\n *\n * Contains the customizer bindings for style packs.\n */\n(function ($) {\n  // Style packs data.\n  var config = stylePacksData;\n  var body = document.body;\n  loadPreviewStylesheets(); // Active style pack.\n\n  wp.customize('active_style_pack', function (value) {\n    var currentStyle = value();\n    value.bind(function (to) {\n      applyStyle(to, currentStyle);\n      fireEvent('change', {\n        from: currentStyle,\n        to: to\n      });\n      body.classList.remove(getBodyClass(currentStyle));\n      body.classList.add(getBodyClass(to));\n      currentStyle = to;\n    });\n  });\n  /**\n   * Fire style_packs event\n   */\n\n  function fireEvent(evt, payload) {\n    $(document).trigger(['style_packs', evt].join('.'), payload);\n  }\n  /**\n   * Create DOM link element\n   */\n\n\n  function createLink(id, uri) {\n    var link = document.createElement('link');\n    link.setAttribute('rel', 'stylesheet');\n    link.setAttribute('id', id);\n    link.setAttribute('href', uri);\n    return link;\n  }\n  /**\n   * Get body class\n   */\n\n\n  function getBodyClass(style) {\n    return stylePacksData.body_class_format.replace('%s', style);\n  }\n  /**\n   * Apply styles to document head\n   */\n\n\n  function applyStyle(style, prevStyle) {\n    if (prevStyle) {\n      removeStyle(prevStyle);\n    }\n\n    var styleData = config.styles[style];\n\n    if (styleData) {\n      var link = createLink(styleData.id, styleData.uri);\n\n      if ('' !== stylePacksData.default_css_id) {\n        document.getElementById(stylePacksData.default_css_id).insertAdjacentElement('afterend', link);\n      } else {\n        document.head.appendChild(link);\n      }\n    }\n\n    _.each(config.fonts[style], function (uri, id) {\n      var link = createLink(id, uri);\n\n      if ('' !== stylePacksData.default_css_id) {\n        document.getElementById(stylePacksData.default_css_id).insertAdjacentElement('afterend', link);\n      } else {\n        document.head.appendChild(link);\n      }\n    });\n  }\n  /**\n   * Remove styles from document head\n   */\n\n\n  function removeStyle(style) {\n    if (config.styles[style]) {\n      $('head #' + config.styles[style].id).remove();\n    }\n\n    _.each(config.fonts[style], function (uri, id) {\n      $('head #' + id).remove();\n    });\n  }\n  /**\n   * Load preview stylesheets to document head\n   */\n\n\n  function loadPreviewStylesheets() {\n    var style = config.preview_style,\n        data = config.styles[style];\n\n    _.each(config.fonts[style], function (uri, id) {\n      if ('' !== stylePacksData.default_css_id) {\n        document.getElementById(stylePacksData.default_css_id).insertAdjacentElement('afterend', createLink(id, uri));\n      } else {\n        document.head.appendChild(createLink(id, uri));\n      }\n    });\n\n    if (data) {\n      if ('' !== stylePacksData.default_css_id) {\n        document.getElementById(stylePacksData.default_css_id).insertAdjacentElement('afterend', createLink(data.id, data.uri));\n      } else {\n        document.head.appendChild(createLink(data.id, data.uri));\n      }\n    }\n  }\n})(jQuery);\n\n//# sourceURL=webpack:///./newspack-theme/js/src/style-packs-customizer.js?");

/***/ })

/******/ })));