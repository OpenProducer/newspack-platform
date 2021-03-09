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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/assets/front-end/curated-list.js":
/*!**********************************************!*\
  !*** ./src/assets/front-end/curated-list.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * VIEW\n * JavaScript used on front of site.\n */\nvar fetchRetryCount = 3;\n/**\n * Load More Button Handling\n *\n * Calls Array.prototype.forEach for IE11 compatibility.\n *\n * @see https://developer.mozilla.org/en-US/docs/Web/API/NodeList\n */\n\nArray.prototype.forEach.call(document.querySelectorAll('.newspack-listings__curated-list.has-more-button'), buildLoadMoreHandler);\nArray.prototype.forEach.call(document.querySelectorAll('.newspack-listings__curated-list.show-sort-ui'), buildSortHandler);\n/**\n * Builds a function to handle clicks on the load more button.\n * Creates internal state via closure to ensure all state is\n * isolated to a single Block + button instance.\n *\n * @param {HTMLElement} blockWrapperEl the button that was clicked\n */\n\nfunction buildLoadMoreHandler(blockWrapperEl) {\n  var btnEl = blockWrapperEl.querySelector('[data-next]');\n\n  if (!btnEl) {\n    return;\n  }\n\n  var postsContainerEl = blockWrapperEl.querySelector('.newspack-listings__list-container');\n  var btnText = btnEl.textContent.trim();\n  var loadingText = blockWrapperEl.querySelector('.loading').textContent; // Set initial state flags.\n\n  var isFetching = false;\n  btnEl.addEventListener('click', function () {\n    // Early return if still fetching or no more posts to render.\n    if (isFetching) {\n      return false;\n    }\n\n    isFetching = true;\n    blockWrapperEl.classList.remove('is-error');\n    blockWrapperEl.classList.add('is-loading');\n\n    if (loadingText) {\n      btnEl.textContent = loadingText;\n    }\n\n    var requestURL = btnEl.getAttribute('data-next');\n    fetchWithRetry({\n      url: requestURL,\n      onSuccess: onSuccess,\n      onError: onError\n    }, fetchRetryCount);\n    /**\n     * @param {Object} data Post data\n     * @param {string} next URL to fetch next batch of posts\n     */\n\n    function onSuccess(data, next) {\n      // Validate received data.\n      if (!isPostsDataValid(data)) {\n        return onError();\n      }\n\n      if (data.length) {\n        // Render posts' HTML from string.\n        var postsHTML = data.map(function (item) {\n          return item.html;\n        }).join('');\n        postsContainerEl.insertAdjacentHTML('beforeend', postsHTML);\n      }\n\n      if (next) {\n        // Save next URL as button's attribute.\n        btnEl.setAttribute('data-next', next);\n      } // Remove next button if we're done.\n\n\n      if (!data.length || !next) {\n        blockWrapperEl.classList.remove('has-more-button');\n      }\n\n      isFetching = false;\n      blockWrapperEl.classList.remove('is-loading');\n      btnEl.textContent = btnText;\n    }\n    /**\n     * Handle fetching error\n     */\n\n\n    function onError() {\n      isFetching = false;\n      blockWrapperEl.classList.remove('is-loading');\n      blockWrapperEl.classList.add('is-error');\n      btnEl.textContent = btnText;\n    }\n  });\n}\n/**\n * Builds a function to handle sorting of listing items.\n * Creates internal state via closure to ensure all state is\n * isolated to a single Block + button instance.\n *\n * @param {HTMLElement} blockWrapperEl the button that was clicked\n */\n\n\nfunction buildSortHandler(blockWrapperEl) {\n  var sortUi = blockWrapperEl.querySelector('.newspack-listings__sort-ui');\n  var sortBy = blockWrapperEl.querySelector('.newspack-listings__sort-select-control');\n  var sortOrder = blockWrapperEl.querySelectorAll('[name=\"newspack-listings__sort-order\"]');\n  var sortOrderContainer = blockWrapperEl.querySelector('.newspack-listings__sort-order-container');\n\n  if (!sortUi || !sortBy || !sortOrder.length || !sortOrderContainer) {\n    return;\n  }\n\n  var btnEl = blockWrapperEl.querySelector('[data-next]');\n  var triggers = Array.prototype.concat.call(Array.prototype.slice.call(sortOrder), [sortBy]);\n  var postsContainerEl = blockWrapperEl.querySelector('.newspack-listings__list-container');\n  var restURL = sortUi.getAttribute('data-url');\n  var hasMoreButton = blockWrapperEl.classList.contains('has-more-button'); // Set initial state flags and data.\n\n  var isFetching = false;\n  var _sortBy = sortUi.querySelector('[selected]').value;\n  var _order = sortUi.querySelector('[checked]').value;\n\n  var sortHandler = function sortHandler(e) {\n    // Early return if still fetching or no more posts to render.\n    if (isFetching) {\n      return false;\n    }\n\n    isFetching = true;\n    blockWrapperEl.classList.remove('is-error');\n    blockWrapperEl.classList.add('is-loading');\n\n    if (e.target.tagName.toLowerCase() === 'select') {\n      _sortBy = e.target.value;\n    } else {\n      _order = e.target.value;\n    } // Enable disabled sort order radio buttons.\n\n\n    if ('post__in' === e.target.value) {\n      sortOrderContainer.classList.add('is-hidden');\n    } else {\n      sortOrderContainer.classList.remove('is-hidden');\n    }\n\n    var requestURL = \"\".concat(restURL, \"&\").concat(encodeURIComponent('query[sortBy]'), \"=\").concat(_sortBy, \"&\").concat(encodeURIComponent('query[order]'), \"=\").concat(_order);\n\n    if (hasMoreButton && btnEl) {\n      blockWrapperEl.classList.add('has-more-button');\n      btnEl.setAttribute('data-next', requestURL);\n    }\n\n    fetchWithRetry({\n      url: requestURL,\n      onSuccess: onSuccess,\n      onError: onError\n    }, fetchRetryCount);\n    /**\n     * @param {Object} data Post data\n     * @param {string} next URL to fetch next batch of posts\n     */\n\n    function onSuccess(data, next) {\n      // Validate received data.\n      if (!isPostsDataValid(data)) {\n        return onError();\n      }\n\n      if (data.length) {\n        // Clear all existing list items.\n        postsContainerEl.textContent = ''; // Render posts' HTML from string.\n\n        var postsHTML = data.map(function (item) {\n          return item.html;\n        }).join('');\n        postsContainerEl.insertAdjacentHTML('beforeend', postsHTML);\n      }\n\n      if (next && btnEl) {\n        // Save next URL as button's attribute.\n        btnEl.setAttribute('data-next', next);\n      }\n\n      isFetching = false;\n      blockWrapperEl.classList.remove('is-loading');\n    }\n    /**\n     * Handle fetching error\n     */\n\n\n    function onError() {\n      isFetching = false;\n      blockWrapperEl.classList.remove('is-loading');\n      blockWrapperEl.classList.add('is-error');\n    }\n  };\n\n  triggers.forEach(function (trigger) {\n    return trigger.addEventListener('change', sortHandler);\n  });\n}\n/**\n * Wrapper for XMLHttpRequest that performs given number of retries when error\n * occurs.\n *\n * @param {Object} options XMLHttpRequest options\n * @param {number} n retry count before throwing\n */\n\n\nfunction fetchWithRetry(options, n) {\n  var xhr = new XMLHttpRequest();\n\n  xhr.onreadystatechange = function () {\n    // Return if the request is completed.\n    if (xhr.readyState !== 4) {\n      return;\n    } // Call onSuccess with parsed JSON if the request is successful.\n\n\n    if (xhr.status >= 200 && xhr.status < 300) {\n      var data = JSON.parse(xhr.responseText);\n      var next = xhr.getResponseHeader('next-url');\n      return options.onSuccess(data, next);\n    } // Call onError if the request has failed n + 1 times (or if n is undefined).\n\n\n    if (!n) {\n      return options.onError();\n    } // Retry fetching if request has failed and n > 0.\n\n\n    return fetchWithRetry(options, n - 1);\n  };\n\n  xhr.open('GET', options.url);\n  xhr.send();\n}\n/**\n * Validates the \"Load more\" posts endpoint schema:\n * {\n * \t\"type\": \"array\",\n * \t\"items\": {\n * \t\t\"type\": \"object\",\n * \t\t\"properties\": {\n * \t\t\t\"html\": {\n * \t\t\t\t\"type\": \"string\"\n * \t\t\t}\n * \t\t},\n * \t\t\"required\": [\"html\"]\n * \t},\n * }\n *\n * @param {Object} data posts endpoint payload\n */\n\n\nfunction isPostsDataValid(data) {\n  var isValid = false;\n\n  if (data && Array.isArray(data)) {\n    isValid = true;\n\n    if (data.length && !(hasOwnProp(data[0], 'html') && typeof data[0].html === 'string')) {\n      isValid = false;\n    }\n  }\n\n  return isValid;\n}\n/**\n * Checks if object has own property.\n *\n * @param {Object} obj Object\n * @param {string} prop Property to check\n */\n\n\nfunction hasOwnProp(obj, prop) {\n  return Object.prototype.hasOwnProperty.call(obj, prop);\n}\n\n//# sourceURL=webpack:///./src/assets/front-end/curated-list.js?");

/***/ }),

/***/ "./src/assets/front-end/view.scss":
/*!****************************************!*\
  !*** ./src/assets/front-end/view.scss ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin\n\n//# sourceURL=webpack:///./src/assets/front-end/view.scss?");

/***/ }),

/***/ "./src/assets/index.js":
/*!*****************************!*\
  !*** ./src/assets/index.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _front_end_view_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./front-end/view.scss */ \"./src/assets/front-end/view.scss\");\n/* harmony import */ var _front_end_view_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_front_end_view_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _front_end_curated_list__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./front-end/curated-list */ \"./src/assets/front-end/curated-list.js\");\n/* harmony import */ var _front_end_curated_list__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_front_end_curated_list__WEBPACK_IMPORTED_MODULE_1__);\n/**\n * Custom styles for listings pages.\n */\n\n/**\n * Front-end block JS.\n */\n\n\n\n//# sourceURL=webpack:///./src/assets/index.js?");

/***/ }),

/***/ 0:
/*!**************************!*\
  !*** multi ./src/assets ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("module.exports = __webpack_require__(/*! /home/circleci/project/src/assets */\"./src/assets/index.js\");\n\n\n//# sourceURL=webpack:///multi_./src/assets?");

/***/ })

/******/ })));