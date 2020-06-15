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
/******/ 	return __webpack_require__(__webpack_require__.s = "./newspack-theme/js/src/amp-fallback.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./newspack-theme/js/src/amp-fallback.js":
/*!***********************************************!*\
  !*** ./newspack-theme/js/src/amp-fallback.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * File amp-fallback.js.\n *\n * AMP fallback JavaScript.\n */\n(function () {\n  // Search toggle.\n  var headerContain = document.getElementById('masthead'),\n      headerSearch = document.getElementById('header-search'),\n      headerSearchInput = headerSearch.getElementsByTagName('input')[0],\n      searchToggle = document.getElementById('search-toggle'),\n      searchToggleTextContain = searchToggle.getElementsByTagName('span')[0],\n      searchToggleTextDefault = searchToggleTextContain.innerText;\n  searchToggle.addEventListener('click', function () {\n    // Toggle the search visibility.\n    headerContain.classList.toggle('hide-header-search'); // Toggle screen reader text label and aria settings.\n\n    if (searchToggleTextDefault === searchToggleTextContain.innerText) {\n      searchToggleTextContain.innerText = newspackScreenReaderText.close_search;\n      headerSearch.setAttribute('aria-expanded', 'true');\n      searchToggle.setAttribute('aria-expanded', 'true');\n      headerSearchInput.focus();\n    } else {\n      searchToggleTextContain.innerText = searchToggleTextDefault;\n      headerSearch.setAttribute('aria-expanded', 'false');\n      searchToggle.setAttribute('aria-expanded', 'false');\n      searchToggle.focus();\n    }\n  }, false); // Mobile menu fallback.\n\n  var menuToggle = document.getElementsByClassName('mobile-menu-toggle'),\n      body = document.getElementsByTagName('body')[0],\n      mobileSidebar = document.getElementById('mobile-sidebar-fallback'),\n      menuOpenButton = headerContain.getElementsByClassName('mobile-menu-toggle')[0],\n      menuCloseButton = mobileSidebar.getElementsByClassName('mobile-menu-toggle')[0];\n\n  for (var i = 0; i < menuToggle.length; i++) {\n    menuToggle[i].addEventListener('click', function () {\n      if (body.classList.contains('menu-opened')) {\n        body.classList.remove('menu-opened');\n        menuOpenButton.focus();\n      } else {\n        body.classList.add('menu-opened');\n        menuCloseButton.focus();\n      }\n    }, false);\n  } // Desktop menu fallback.\n\n\n  var desktopToggle = document.getElementsByClassName('desktop-menu-toggle'),\n      desktopSidebar = document.getElementById('desktop-sidebar-fallback'),\n      desktopOpenButton = headerContain.getElementsByClassName('desktop-menu-toggle')[0],\n      desktopCloseButton = desktopSidebar.getElementsByClassName('desktop-menu-toggle')[0];\n\n  for (var _i = 0; _i < desktopToggle.length; _i++) {\n    desktopToggle[_i].addEventListener('click', function () {\n      if (body.classList.contains('desktop-menu-opened')) {\n        body.classList.remove('desktop-menu-opened');\n        desktopOpenButton.focus();\n      } else {\n        body.classList.add('desktop-menu-opened');\n        desktopCloseButton.focus();\n      }\n    }, false);\n  } // 'Sub page' menu fallback.\n\n\n  var subpageToggle = document.getElementsByClassName('subpage-toggle');\n\n  if (0 < subpageToggle.length) {\n    (function () {\n      var subpageSidebar = document.getElementById('subpage-sidebar-fallback'),\n          subpageOpenButton = headerContain.getElementsByClassName('subpage-toggle')[0],\n          subpageCloseButton = subpageSidebar.getElementsByClassName('subpage-toggle')[0];\n\n      for (var _i2 = 0; _i2 < subpageToggle.length; _i2++) {\n        subpageToggle[_i2].addEventListener('click', function () {\n          if (body.classList.contains('subpage-sidebar-opened')) {\n            body.classList.remove('subpage-sidebar-opened');\n            subpageOpenButton.focus();\n          } else {\n            body.classList.add('subpage-sidebar-opened');\n            subpageCloseButton.focus();\n          }\n        }, false);\n      }\n    })();\n  } // Comments toggle fallback.\n\n\n  var commentsToggle = document.getElementById('comments-toggle'); // Make sure comments exist before going any further.\n\n  if (null !== commentsToggle) {\n    var commentsWrapper = document.getElementById('comments-wrapper'),\n        commentsToggleTextContain = commentsToggle.getElementsByTagName('span')[0];\n    commentsToggle.addEventListener('click', function () {\n      if (commentsWrapper.classList.contains('comments-hide')) {\n        commentsWrapper.classList.remove('comments-hide');\n        commentsToggleTextContain.innerText = newspackScreenReaderText.collapse_comments;\n      } else {\n        commentsWrapper.classList.add('comments-hide');\n        commentsToggleTextContain.innerText = newspackScreenReaderText.expand_comments;\n      }\n    }, false);\n  } // Checkout toggle fallback.\n\n\n  var orderDetailToggle = document.getElementById('toggle-order-details'); // Make sure checkout details exist before going any further.\n\n  if (null !== orderDetailToggle) {\n    var orderDetailWrapper = document.getElementById('order-details-wrapper'),\n        orderDetailToggleTextContain = orderDetailToggle.getElementsByTagName('span')[0];\n    orderDetailToggle.addEventListener('click', function () {\n      if (orderDetailWrapper.classList.contains('order-details-hidden')) {\n        orderDetailWrapper.classList.remove('order-details-hidden');\n        orderDetailToggle.classList.remove('order-details-hidden');\n        orderDetailToggleTextContain.innerText = newspackScreenReaderText.hide_order_details;\n      } else {\n        orderDetailWrapper.classList.add('order-details-hidden');\n        orderDetailToggle.classList.add('order-details-hidden');\n        orderDetailToggleTextContain.innerText = newspackScreenReaderText.show_order_details;\n      }\n    }, false);\n  }\n})();\n\n//# sourceURL=webpack:///./newspack-theme/js/src/amp-fallback.js?");

/***/ })

/******/ })));