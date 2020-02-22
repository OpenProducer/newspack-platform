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
/******/ 	return __webpack_require__(__webpack_require__.s = "./newspack-theme/js/src/logo-customize-preview.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./newspack-theme/js/src/logo-customize-preview.js":
/*!*********************************************************!*\
  !*** ./newspack-theme/js/src/logo-customize-preview.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * File customize-preview.js.\n *\n * Brings logo resizing technology to the Customizer.\n *\n * Contains handlers to make Customizer preview changes asynchronously.\n */\n(function ($) {\n  var api = wp.customize;\n  var Logo = new NewspackLogo();\n  var resizeTimer;\n  api('custom_logo', function (value) {\n    handleLogoDetection(value());\n    value.bind(handleLogoDetection);\n  });\n  api('logo_size', function (value) {\n    Logo.resize(value());\n    value.bind(Logo.resize);\n  });\n  /**\n   *\n   */\n\n  function handleLogoDetection(to, initial) {\n    if ('' === to) {\n      Logo.remove();\n    } else if (undefined === initial) {\n      Logo.add();\n    } else {\n      Logo.change();\n    }\n\n    initial = to;\n  }\n  /**\n   *\n   */\n\n\n  function NewspackLogo() {\n    var hasLogo = null;\n    var min = 48;\n    var self = {\n      resize: function resize(to) {\n        if (hasLogo) {\n          var img = new Image();\n          var logo = $('.custom-logo');\n          var size = {\n            width: parseInt(logo.attr('width'), 10),\n            height: parseInt(logo.attr('height'), 10)\n          };\n          var cssMax = {\n            width: parseInt(logo.css('max-width'), 10),\n            height: parseInt(logo.css('max-height'), 10)\n          };\n          var max = new Object();\n          max.width = $.isNumeric(cssMax.width) ? cssMax.width : 600;\n          max.height = $.isNumeric(cssMax.height) ? cssMax.height : size.height;\n\n          img.onload = function () {\n            var output = new Object();\n\n            if (size.width >= size.height) {\n              // landscape or square, calculate height as short side\n              output = logo_min_max(size.height, size.width, max.height, max.width, to, min);\n              size = {\n                height: output.a,\n                width: output.b\n              };\n            } else if (size.width < size.height) {\n              // portrait, calculate height as long side\n              output = logo_min_max(size.width, size.height, max.width, max.height, to, min);\n              size = {\n                height: output.b,\n                width: output.a\n              };\n            }\n\n            logo.css({\n              width: size.width,\n              height: size.height\n            });\n          };\n\n          img.src = logo.attr('src');\n          clearTimeout(resizeTimer);\n          resizeTimer = setTimeout(function () {\n            $(document.body).resize();\n          }, 500);\n        }\n      },\n      add: function add() {\n        var intId = setInterval(function () {\n          var logo = $('.custom-logo[src]');\n\n          if (logo.length) {\n            clearInterval(intId);\n            hasLogo = true;\n          }\n        }, 500);\n      },\n      change: function change() {\n        var oldlogo = $('.custom-logo').attr('src');\n        var intId = setInterval(function () {\n          var logo = $('.custom-logo').attr('src');\n\n          if (logo !== oldlogo) {\n            clearInterval(intId);\n            hasLogo = true;\n            self.resize(50);\n          }\n        }, 100);\n      },\n      remove: function remove() {\n        hasLogo = null;\n      }\n    };\n    return self;\n  }\n  /**\n   * Get logo size\n   *\n   * @param {number} a short side,\n   * @param {number} b long side\n   * @param {number} amax short css max\n   * @param {number} bmax long css max\n   * @param {number} p percent\n   * @param {number} m minimum short side\n   */\n\n\n  function logo_min_max(a, b, amax, bmax, p, m) {\n    var max = new Object();\n    var size = new Object();\n    var ratio = b / a;\n    max.b = bmax >= b ? b : bmax;\n    max.a = amax >= max.b / ratio ? Math.floor(max.b / ratio) : amax;\n    var pixelsPerPercentagePoint = (max.a - m) / 100; // at 0%, the minimum is set, scale up from there\n\n    size.a = Math.floor(m + p * pixelsPerPercentagePoint); // long side is calculated from the image ratio\n\n    size.b = Math.floor(size.a * ratio);\n    return size;\n  }\n})(jQuery);\n\n//# sourceURL=webpack:///./newspack-theme/js/src/logo-customize-preview.js?");

/***/ })

/******/ })));