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

/***/ "./newspack-theme/js/src/customize-preview.js":
/*!****************************************************!*\
  !*** ./newspack-theme/js/src/customize-preview.js ***!
  \****************************************************/
/***/ (function() {

eval("/* globals jQuery */\n\n/**\n * File customizer.js.\n *\n * Theme Customizer enhancements for a better user experience.\n *\n * Contains handlers to make Theme Customizer preview reload changes asynchronously.\n */\n(function ($) {\n  // Hide site tagline\n  wp.customize('header_display_tagline', function (value) {\n    value.bind(function (to) {\n      if (false === to) {\n        $('body').addClass('hide-site-tagline').removeClass('show-site-tagline');\n      } else {\n        $('body').removeClass('hide-site-tagline').addClass('show-site-tagline');\n      }\n    });\n  }); // Hide Front Page Title\n\n  wp.customize('hide_front_page_title', function (value) {\n    value.bind(function (to) {\n      if (true === to) {\n        $('body').addClass('hide-homepage-title');\n      } else {\n        $('body').removeClass('hide-homepage-title');\n      }\n    });\n  }); // Hide Author Bio\n\n  wp.customize('show_author_bio', function (value) {\n    value.bind(function (to) {\n      if (false === to) {\n        $('body').addClass('hide-author-bio');\n      } else {\n        $('body').removeClass('hide-author-bio');\n      }\n    });\n  }); // Hide Author email\n\n  wp.customize('show_author_email', function (value) {\n    value.bind(function (to) {\n      if (false === to) {\n        $('body').addClass('hide-author-email');\n      } else {\n        $('body').removeClass('hide-author-email');\n      }\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack://newspack/./newspack-theme/js/src/customize-preview.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./newspack-theme/js/src/customize-preview.js"]();
/******/ 	var __webpack_export_target__ = window;
/******/ 	for(var i in __webpack_exports__) __webpack_export_target__[i] = __webpack_exports__[i];
/******/ 	if(__webpack_exports__.__esModule) Object.defineProperty(__webpack_export_target__, "__esModule", { value: true });
/******/ 	
/******/ })()
;