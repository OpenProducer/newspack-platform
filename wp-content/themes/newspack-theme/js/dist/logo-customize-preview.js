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

/***/ "./newspack-theme/js/src/logo-customize-preview.js":
/*!*********************************************************!*\
  !*** ./newspack-theme/js/src/logo-customize-preview.js ***!
  \*********************************************************/
/***/ (() => {

eval("/* globals jQuery */\n\n/**\n * File customize-preview.js.\n *\n * Brings logo resizing technology to the Customizer.\n *\n * Contains handlers to make Customizer preview changes asynchronously.\n */\n(function ($) {\n  const api = wp.customize;\n  const Logo = new NewspackLogo();\n  let resizeTimer;\n  api('custom_logo', function (value) {\n    handleLogoDetection(value());\n    value.bind(handleLogoDetection);\n  });\n  api('logo_size', function (value) {\n    Logo.resize(value());\n    value.bind(Logo.resize);\n  });\n\n  /**\n   */\n  function handleLogoDetection(to, initial) {\n    if ('' === to) {\n      Logo.remove();\n    } else if (undefined === initial) {\n      Logo.add();\n    } else {\n      Logo.change();\n    }\n    initial = to;\n  }\n\n  /**\n   */\n  function NewspackLogo() {\n    let hasLogo = null;\n    const min = 48;\n    const self = {\n      resize(to) {\n        if (hasLogo) {\n          const img = new Image();\n          const logo = $('.site-header .custom-logo');\n          let size = {\n            width: parseInt(logo.attr('width'), 10),\n            height: parseInt(logo.attr('height'), 10)\n          };\n          const cssMax = {\n            width: parseInt(logo.css('max-width'), 10),\n            height: parseInt(logo.css('max-height'), 10)\n          };\n          const max = new Object();\n          max.width = $.isNumeric(cssMax.width) ? cssMax.width : 600;\n          max.height = $.isNumeric(cssMax.height) ? cssMax.height : size.height;\n          img.onload = function () {\n            let output = new Object();\n            if (size.width >= size.height) {\n              // landscape or square, calculate height as short side\n              output = logo_min_max(size.height, size.width, max.height, max.width, to, min);\n              size = {\n                height: output.a,\n                width: output.b\n              };\n            } else if (size.width < size.height) {\n              // portrait, calculate height as long side\n              output = logo_min_max(size.width, size.height, max.width, max.height, to, min);\n              size = {\n                height: output.b,\n                width: output.a\n              };\n            }\n            logo.css({\n              width: size.width,\n              height: size.height\n            });\n          };\n          img.src = logo.attr('src');\n          clearTimeout(resizeTimer);\n          resizeTimer = setTimeout(function () {\n            $(document.body).resize();\n          }, 500);\n        }\n      },\n      add() {\n        const intId = setInterval(function () {\n          const logo = $('.custom-logo[src]');\n          if (logo.length) {\n            clearInterval(intId);\n            hasLogo = true;\n          }\n        }, 500);\n      },\n      change() {\n        const oldlogo = $('.custom-logo').attr('src');\n        const intId = setInterval(function () {\n          const logo = $('.custom-logo').attr('src');\n          if (logo !== oldlogo) {\n            clearInterval(intId);\n            hasLogo = true;\n            self.resize(50);\n          }\n        }, 100);\n      },\n      remove() {\n        hasLogo = null;\n      }\n    };\n    return self;\n  }\n\n  /**\n   * Get logo size\n   *\n   * @param {number} a    short side,\n   * @param {number} b    long side\n   * @param {number} amax short css max\n   * @param {number} bmax long css max\n   * @param {number} p    percent\n   * @param {number} m    minimum short side\n   */\n  function logo_min_max(a, b, amax, bmax, p, m) {\n    const max = new Object();\n    const size = new Object();\n    const ratio = b / a;\n    max.b = bmax >= b ? b : bmax;\n    max.a = amax >= max.b / ratio ? Math.floor(max.b / ratio) : amax;\n    const pixelsPerPercentagePoint = (max.a - m) / 100;\n\n    // at 0%, the minimum is set, scale up from there\n    size.a = Math.floor(m + p * pixelsPerPercentagePoint);\n    // long side is calculated from the image ratio\n    size.b = Math.floor(size.a * ratio);\n    return size;\n  }\n})(jQuery);\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/logo-customize-preview.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./newspack-theme/js/src/logo-customize-preview.js"]();
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;