/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./newspack-theme/js/src/amp-fallback-newspack-sponsors.js":
/*!*****************************************************************!*\
  !*** ./newspack-theme/js/src/amp-fallback-newspack-sponsors.js ***!
  \*****************************************************************/
/***/ (() => {

eval("/* globals newspackScreenReaderText */\n\n/**\n * File amp-fallback.js.\n *\n * AMP fallback JavaScript.\n */\n(function () {\n  // Support info toggle.\n  const supportToggle = document.getElementById('sponsor-info-toggle');\n\n  if (null !== supportToggle) {\n    const supportLabel = supportToggle.parentNode,\n          supportInfo = document.getElementById('sponsor-info'),\n          supportToggleTextContain = supportToggle.getElementsByTagName('span')[0],\n          supportToggleTextDefault = supportToggleTextContain.innerText;\n    supportToggle.addEventListener('click', function () {\n      supportLabel.classList.toggle('show-info'); // Toggle screen reader text label and aria settings.\n\n      if (supportToggleTextDefault === supportToggleTextContain.innerText) {\n        supportToggleTextContain.innerText = newspackScreenReaderText.close_info;\n        supportInfo.setAttribute('aria-expanded', 'true');\n        supportToggle.setAttribute('aria-expanded', 'true');\n      } else {\n        supportToggleTextContain.innerText = supportToggleTextDefault;\n        supportInfo.setAttribute('aria-expanded', 'false');\n        supportToggle.setAttribute('aria-expanded', 'false');\n      }\n    }, false);\n  }\n})();\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/amp-fallback-newspack-sponsors.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./newspack-theme/js/src/amp-fallback-newspack-sponsors.js"]();
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;