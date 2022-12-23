/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./newspack-theme/js/src/logo-customize-controls.js":
/*!**********************************************************!*\
  !*** ./newspack-theme/js/src/logo-customize-controls.js ***!
  \**********************************************************/
/***/ (function() {

eval("/* globals jQuery */\n\n/**\n * File customize-controls.js.\n *\n * Brings logo resizing technology to the Customizer.\n *\n * Contains handlers to change Customizer controls.\n */\n(function ($) {\n  'use strict';\n\n  const api = wp.customize;\n  api.bind('ready', function () {\n    $(window).load(function () {\n      if (false === api.control('custom_logo').setting()) {\n        $('#customize-control-logo_size').hide();\n      }\n    });\n  });\n\n  // Check logo changes\n  api('custom_logo', function (value) {\n    value.bind(function (to) {\n      if ('' === to) {\n        api.control('logo_size').deactivate();\n      } else {\n        $('#customize-control-logo_size').show();\n        api.control('logo_size').activate();\n        api.control('logo_size').setting(50);\n        api.control('logo_size').setting.preview();\n      }\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/logo-customize-controls.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./newspack-theme/js/src/logo-customize-controls.js"]();
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;