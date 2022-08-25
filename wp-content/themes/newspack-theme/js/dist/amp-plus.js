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

/***/ "./newspack-theme/js/src/amp-plus.js":
/*!*******************************************!*\
  !*** ./newspack-theme/js/src/amp-plus.js ***!
  \*******************************************/
/***/ (() => {

eval("/**\n * Sticky header & sticky ad handling.\n *\n * If the site uses sticky header and a sticky ad, the ad should\n * be offset by the header height in order to stack the sticky\n * elements on top of each other.\n */\n(function () {\n  const stickyAd = document.querySelector('.h-stk .stick-to-top:last-child');\n  const siteHeader = document.querySelector('.h-stk .site-header');\n\n  if (stickyAd && siteHeader) {\n    stickyAd.style.top = `calc(${siteHeader.offsetHeight}px + 1rem)`;\n  }\n})(); // AMP sticky ad polyfills.\n\n\n(function () {\n  const body = document.body;\n  const stickyAdClose = document.querySelector('.newspack_sticky_ad__close');\n  const stickyAd = document.querySelector('.newspack_global_ad.sticky');\n\n  if (stickyAdClose && stickyAd) {\n    window.googletag = window.googletag || {\n      cmd: []\n    };\n    window.googletag.cmd.push(function () {\n      const initialBodyPadding = body.style.paddingBottom; // Add padding to body to accommodate the sticky ad.\n\n      window.googletag.pubads().addEventListener('slotRenderEnded', event => {\n        const renderedSlotId = event.slot.getSlotElementId();\n        const stickyAdSlot = stickyAd.querySelector('#' + renderedSlotId);\n\n        if (stickyAdSlot && body.clientWidth <= 600) {\n          stickyAd.style.display = 'flex';\n          body.style.paddingBottom = stickyAd.clientHeight + 'px';\n        }\n      });\n      stickyAdClose.addEventListener('click', () => {\n        stickyAd.parentElement.removeChild(stickyAd); // Reset body padding.\n\n        body.style.paddingBottom = initialBodyPadding;\n      });\n    });\n  }\n})();\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/amp-plus.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./newspack-theme/js/src/amp-plus.js"]();
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;