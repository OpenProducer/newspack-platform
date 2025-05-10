/******/ (function(modules) { // webpackBootstrap
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
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/analytics-advanced-tracking.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "../node_modules/webpack/buildin/global.js":
/*!*************************************************!*\
  !*** ../node_modules/webpack/buildin/global.js ***!
  \*************************************************/
/*! no static exports found */
/*! ModuleConcatenation bailout: Module is not an ECMAScript module */
/***/ (function(module, exports) {

eval("var g;\n\n// This works in non-strict mode\ng = (function() {\n\treturn this;\n})();\n\ntry {\n\t// This works if eval is allowed (see CSP)\n\tg = g || new Function(\"return this\")();\n} catch (e) {\n\t// This works if the window reference is available\n\tif (typeof window === \"object\") g = window;\n}\n\n// g can still be undefined, but nothing to do about it...\n// We return undefined, instead of nothing here, so it's\n// easier to handle this case. if(!global) { ...}\n\nmodule.exports = g;\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi4vbm9kZV9tb2R1bGVzL3dlYnBhY2svYnVpbGRpbi9nbG9iYWwuanMuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi4vbm9kZV9tb2R1bGVzL3dlYnBhY2svYnVpbGRpbi9nbG9iYWwuanM/YTQyYiJdLCJzb3VyY2VzQ29udGVudCI6WyJ2YXIgZztcblxuLy8gVGhpcyB3b3JrcyBpbiBub24tc3RyaWN0IG1vZGVcbmcgPSAoZnVuY3Rpb24oKSB7XG5cdHJldHVybiB0aGlzO1xufSkoKTtcblxudHJ5IHtcblx0Ly8gVGhpcyB3b3JrcyBpZiBldmFsIGlzIGFsbG93ZWQgKHNlZSBDU1ApXG5cdGcgPSBnIHx8IG5ldyBGdW5jdGlvbihcInJldHVybiB0aGlzXCIpKCk7XG59IGNhdGNoIChlKSB7XG5cdC8vIFRoaXMgd29ya3MgaWYgdGhlIHdpbmRvdyByZWZlcmVuY2UgaXMgYXZhaWxhYmxlXG5cdGlmICh0eXBlb2Ygd2luZG93ID09PSBcIm9iamVjdFwiKSBnID0gd2luZG93O1xufVxuXG4vLyBnIGNhbiBzdGlsbCBiZSB1bmRlZmluZWQsIGJ1dCBub3RoaW5nIHRvIGRvIGFib3V0IGl0Li4uXG4vLyBXZSByZXR1cm4gdW5kZWZpbmVkLCBpbnN0ZWFkIG9mIG5vdGhpbmcgaGVyZSwgc28gaXQnc1xuLy8gZWFzaWVyIHRvIGhhbmRsZSB0aGlzIGNhc2UuIGlmKCFnbG9iYWwpIHsgLi4ufVxuXG5tb2R1bGUuZXhwb3J0cyA9IGc7XG4iXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///../node_modules/webpack/buildin/global.js\n");

/***/ }),

/***/ "./js/analytics-advanced-tracking.js":
/*!*******************************************!*\
  !*** ./js/analytics-advanced-tracking.js ***!
  \*******************************************/
/*! no exports provided */
/*! ModuleConcatenation bailout: Module is an entry point */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _analytics_advanced_tracking_set_up_advanced_tracking__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./analytics-advanced-tracking/set-up-advanced-tracking */ \"./js/analytics-advanced-tracking/set-up-advanced-tracking.js\");\n/**\n * Analytics advanced tracking script to be inserted into the frontend via PHP.\n *\n * Site Kit by Google, Copyright 2021 Google LLC\n *\n * Licensed under the Apache License, Version 2.0 (the \"License\");\n * you may not use this file except in compliance with the License.\n * You may obtain a copy of the License at\n *\n *     https://www.apache.org/licenses/LICENSE-2.0\n *\n * Unless required by applicable law or agreed to in writing, software\n * distributed under the License is distributed on an \"AS IS\" BASIS,\n * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.\n * See the License for the specific language governing permissions and\n * limitations under the License.\n */\n\n// This file should not use any dependencies because it is used in the frontend.\n\n/**\n * Internal dependencies\n */\n\n\n/**\n * Sends a tracking event to Analytics via gtag.\n *\n * @since 1.18.0\n *\n * @param {string} action   Event action / event name.\n * @param {Object} metadata Additional event metadata to send, or `null`.\n */\nfunction sendEvent( action, metadata ) {\n\twindow.gtag( 'event', action, metadata || undefined ); // eslint-disable-line no-restricted-globals\n}\n\nconst events = window._googlesitekitAnalyticsTrackingData || []; // eslint-disable-line no-restricted-globals\nif ( Array.isArray( events ) ) {\n\tObject(_analytics_advanced_tracking_set_up_advanced_tracking__WEBPACK_IMPORTED_MODULE_0__[\"default\"])( events, sendEvent );\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9hbmFseXRpY3MtYWR2YW5jZWQtdHJhY2tpbmcuanMuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9qcy9hbmFseXRpY3MtYWR2YW5jZWQtdHJhY2tpbmcuanM/MDUzMCJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEFuYWx5dGljcyBhZHZhbmNlZCB0cmFja2luZyBzY3JpcHQgdG8gYmUgaW5zZXJ0ZWQgaW50byB0aGUgZnJvbnRlbmQgdmlhIFBIUC5cbiAqXG4gKiBTaXRlIEtpdCBieSBHb29nbGUsIENvcHlyaWdodCAyMDIxIEdvb2dsZSBMTENcbiAqXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogICAgIGh0dHBzOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICovXG5cbi8vIFRoaXMgZmlsZSBzaG91bGQgbm90IHVzZSBhbnkgZGVwZW5kZW5jaWVzIGJlY2F1c2UgaXQgaXMgdXNlZCBpbiB0aGUgZnJvbnRlbmQuXG5cbi8qKlxuICogSW50ZXJuYWwgZGVwZW5kZW5jaWVzXG4gKi9cbmltcG9ydCBzZXRVcEFkdmFuY2VkVHJhY2tpbmcgZnJvbSAnLi9hbmFseXRpY3MtYWR2YW5jZWQtdHJhY2tpbmcvc2V0LXVwLWFkdmFuY2VkLXRyYWNraW5nJztcblxuLyoqXG4gKiBTZW5kcyBhIHRyYWNraW5nIGV2ZW50IHRvIEFuYWx5dGljcyB2aWEgZ3RhZy5cbiAqXG4gKiBAc2luY2UgMS4xOC4wXG4gKlxuICogQHBhcmFtIHtzdHJpbmd9IGFjdGlvbiAgIEV2ZW50IGFjdGlvbiAvIGV2ZW50IG5hbWUuXG4gKiBAcGFyYW0ge09iamVjdH0gbWV0YWRhdGEgQWRkaXRpb25hbCBldmVudCBtZXRhZGF0YSB0byBzZW5kLCBvciBgbnVsbGAuXG4gKi9cbmZ1bmN0aW9uIHNlbmRFdmVudCggYWN0aW9uLCBtZXRhZGF0YSApIHtcblx0d2luZG93Lmd0YWcoICdldmVudCcsIGFjdGlvbiwgbWV0YWRhdGEgfHwgdW5kZWZpbmVkICk7IC8vIGVzbGludC1kaXNhYmxlLWxpbmUgbm8tcmVzdHJpY3RlZC1nbG9iYWxzXG59XG5cbmNvbnN0IGV2ZW50cyA9IHdpbmRvdy5fZ29vZ2xlc2l0ZWtpdEFuYWx5dGljc1RyYWNraW5nRGF0YSB8fCBbXTsgLy8gZXNsaW50LWRpc2FibGUtbGluZSBuby1yZXN0cmljdGVkLWdsb2JhbHNcbmlmICggQXJyYXkuaXNBcnJheSggZXZlbnRzICkgKSB7XG5cdHNldFVwQWR2YW5jZWRUcmFja2luZyggZXZlbnRzLCBzZW5kRXZlbnQgKTtcbn1cbiJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./js/analytics-advanced-tracking.js\n");

/***/ }),

/***/ "./js/analytics-advanced-tracking/set-up-advanced-tracking.js":
/*!********************************************************************!*\
  !*** ./js/analytics-advanced-tracking/set-up-advanced-tracking.js ***!
  \********************************************************************/
/*! exports provided: default */
/*! ModuleConcatenation bailout: Module uses injected variables (global) */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* WEBPACK VAR INJECTION */(function(global) {/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"default\", function() { return setUpAdvancedTracking; });\n/**\n * Analytics advanced tracking logic, to be used in the frontend.\n *\n * Site Kit by Google, Copyright 2021 Google LLC\n *\n * Licensed under the Apache License, Version 2.0 (the \"License\");\n * you may not use this file except in compliance with the License.\n * You may obtain a copy of the License at\n *\n *     https://www.apache.org/licenses/LICENSE-2.0\n *\n * Unless required by applicable law or agreed to in writing, software\n * distributed under the License is distributed on an \"AS IS\" BASIS,\n * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.\n * See the License for the specific language governing permissions and\n * limitations under the License.\n */\n\n/**\n * Sets up advanced tracking.\n *\n * This will for each provided event configuration add a DOM event listener that,\n * when triggered, results in a call to the provided sendEvent function.\n *\n * @since 1.18.0\n *\n * @param {Object[]} eventConfigurations List of event configuration objects. Each event object must have properties\n *                                       `action`, `on`, `selector`, and optionally `metadata`.\n * @param {Function} sendEvent           Function that handles the event. It will receive the event action as first\n *                                       parameter and the event metadata (may be `null`) as second parameter.\n * @return {Function} Returns parameter-less function to destroy the tracking, i.e. remove all added listeners.\n */\nfunction setUpAdvancedTracking(\n\teventConfigurations,\n\tsendEvent\n) {\n\tconst toRemove = [];\n\n\teventConfigurations.forEach( ( eventConfig ) => {\n\t\tconst handleDOMEvent = ( domEvent ) => {\n\t\t\tif ( 'DOMContentLoaded' === eventConfig.on ) {\n\t\t\t\tsendEvent( eventConfig.action, eventConfig.metadata );\n\t\t\t} else if (\n\t\t\t\tmatches( domEvent.target, eventConfig.selector ) ||\n\t\t\t\tmatches( domEvent.target, eventConfig.selector.concat( ' *' ) )\n\t\t\t) {\n\t\t\t\tsendEvent( eventConfig.action, eventConfig.metadata );\n\t\t\t}\n\t\t};\n\n\t\tglobal.document.addEventListener(\n\t\t\teventConfig.on,\n\t\t\thandleDOMEvent,\n\t\t\ttrue\n\t\t);\n\n\t\ttoRemove.push( [ eventConfig.on, handleDOMEvent, true ] );\n\t} );\n\n\treturn () => {\n\t\ttoRemove.forEach( ( listenerArgs ) => {\n\t\t\tdocument.removeEventListener.apply( document, listenerArgs );\n\t\t} );\n\t};\n}\n\n/**\n * Checks whether the given element matches the given selector.\n *\n * @since 1.18.0\n *\n * @param {Element} el       A DOM element.\n * @param {string}  selector A selector to check for.\n * @return {boolean} True if the DOM element matches the selector, false otherwise.\n */\nfunction matches( el, selector ) {\n\t// Use fallbacks for older browsers.\n\t// See https://developer.mozilla.org/en-US/docs/Web/API/Element/matches#Polyfill.\n\tconst matcher =\n\t\tel.matches ||\n\t\tel.matchesSelector ||\n\t\tel.webkitMatchesSelector ||\n\t\tel.mozMatchesSelector ||\n\t\tel.msMatchesSelector ||\n\t\tel.oMatchesSelector ||\n\t\tfunction ( s ) {\n\t\t\tconst elements = (\n\t\t\t\tthis.document || this.ownerDocument\n\t\t\t).querySelectorAll( s );\n\t\t\tlet i = elements.length;\n\t\t\twhile ( --i >= 0 && elements.item( i ) !== this ) {}\n\t\t\treturn i > -1;\n\t\t};\n\n\tif ( matcher ) {\n\t\treturn matcher.call( el, selector );\n\t}\n\n\treturn false;\n}\n\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../node_modules/webpack/buildin/global.js */ \"../node_modules/webpack/buildin/global.js\")))//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9qcy9hbmFseXRpY3MtYWR2YW5jZWQtdHJhY2tpbmcvc2V0LXVwLWFkdmFuY2VkLXRyYWNraW5nLmpzLmpzIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vanMvYW5hbHl0aWNzLWFkdmFuY2VkLXRyYWNraW5nL3NldC11cC1hZHZhbmNlZC10cmFja2luZy5qcz8yNDE3Il0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQW5hbHl0aWNzIGFkdmFuY2VkIHRyYWNraW5nIGxvZ2ljLCB0byBiZSB1c2VkIGluIHRoZSBmcm9udGVuZC5cbiAqXG4gKiBTaXRlIEtpdCBieSBHb29nbGUsIENvcHlyaWdodCAyMDIxIEdvb2dsZSBMTENcbiAqXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogICAgIGh0dHBzOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICovXG5cbi8qKlxuICogU2V0cyB1cCBhZHZhbmNlZCB0cmFja2luZy5cbiAqXG4gKiBUaGlzIHdpbGwgZm9yIGVhY2ggcHJvdmlkZWQgZXZlbnQgY29uZmlndXJhdGlvbiBhZGQgYSBET00gZXZlbnQgbGlzdGVuZXIgdGhhdCxcbiAqIHdoZW4gdHJpZ2dlcmVkLCByZXN1bHRzIGluIGEgY2FsbCB0byB0aGUgcHJvdmlkZWQgc2VuZEV2ZW50IGZ1bmN0aW9uLlxuICpcbiAqIEBzaW5jZSAxLjE4LjBcbiAqXG4gKiBAcGFyYW0ge09iamVjdFtdfSBldmVudENvbmZpZ3VyYXRpb25zIExpc3Qgb2YgZXZlbnQgY29uZmlndXJhdGlvbiBvYmplY3RzLiBFYWNoIGV2ZW50IG9iamVjdCBtdXN0IGhhdmUgcHJvcGVydGllc1xuICogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBgYWN0aW9uYCwgYG9uYCwgYHNlbGVjdG9yYCwgYW5kIG9wdGlvbmFsbHkgYG1ldGFkYXRhYC5cbiAqIEBwYXJhbSB7RnVuY3Rpb259IHNlbmRFdmVudCAgICAgICAgICAgRnVuY3Rpb24gdGhhdCBoYW5kbGVzIHRoZSBldmVudC4gSXQgd2lsbCByZWNlaXZlIHRoZSBldmVudCBhY3Rpb24gYXMgZmlyc3RcbiAqICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcGFyYW1ldGVyIGFuZCB0aGUgZXZlbnQgbWV0YWRhdGEgKG1heSBiZSBgbnVsbGApIGFzIHNlY29uZCBwYXJhbWV0ZXIuXG4gKiBAcmV0dXJuIHtGdW5jdGlvbn0gUmV0dXJucyBwYXJhbWV0ZXItbGVzcyBmdW5jdGlvbiB0byBkZXN0cm95IHRoZSB0cmFja2luZywgaS5lLiByZW1vdmUgYWxsIGFkZGVkIGxpc3RlbmVycy5cbiAqL1xuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gc2V0VXBBZHZhbmNlZFRyYWNraW5nKFxuXHRldmVudENvbmZpZ3VyYXRpb25zLFxuXHRzZW5kRXZlbnRcbikge1xuXHRjb25zdCB0b1JlbW92ZSA9IFtdO1xuXG5cdGV2ZW50Q29uZmlndXJhdGlvbnMuZm9yRWFjaCggKCBldmVudENvbmZpZyApID0+IHtcblx0XHRjb25zdCBoYW5kbGVET01FdmVudCA9ICggZG9tRXZlbnQgKSA9PiB7XG5cdFx0XHRpZiAoICdET01Db250ZW50TG9hZGVkJyA9PT0gZXZlbnRDb25maWcub24gKSB7XG5cdFx0XHRcdHNlbmRFdmVudCggZXZlbnRDb25maWcuYWN0aW9uLCBldmVudENvbmZpZy5tZXRhZGF0YSApO1xuXHRcdFx0fSBlbHNlIGlmIChcblx0XHRcdFx0bWF0Y2hlcyggZG9tRXZlbnQudGFyZ2V0LCBldmVudENvbmZpZy5zZWxlY3RvciApIHx8XG5cdFx0XHRcdG1hdGNoZXMoIGRvbUV2ZW50LnRhcmdldCwgZXZlbnRDb25maWcuc2VsZWN0b3IuY29uY2F0KCAnIConICkgKVxuXHRcdFx0KSB7XG5cdFx0XHRcdHNlbmRFdmVudCggZXZlbnRDb25maWcuYWN0aW9uLCBldmVudENvbmZpZy5tZXRhZGF0YSApO1xuXHRcdFx0fVxuXHRcdH07XG5cblx0XHRnbG9iYWwuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcihcblx0XHRcdGV2ZW50Q29uZmlnLm9uLFxuXHRcdFx0aGFuZGxlRE9NRXZlbnQsXG5cdFx0XHR0cnVlXG5cdFx0KTtcblxuXHRcdHRvUmVtb3ZlLnB1c2goIFsgZXZlbnRDb25maWcub24sIGhhbmRsZURPTUV2ZW50LCB0cnVlIF0gKTtcblx0fSApO1xuXG5cdHJldHVybiAoKSA9PiB7XG5cdFx0dG9SZW1vdmUuZm9yRWFjaCggKCBsaXN0ZW5lckFyZ3MgKSA9PiB7XG5cdFx0XHRkb2N1bWVudC5yZW1vdmVFdmVudExpc3RlbmVyLmFwcGx5KCBkb2N1bWVudCwgbGlzdGVuZXJBcmdzICk7XG5cdFx0fSApO1xuXHR9O1xufVxuXG4vKipcbiAqIENoZWNrcyB3aGV0aGVyIHRoZSBnaXZlbiBlbGVtZW50IG1hdGNoZXMgdGhlIGdpdmVuIHNlbGVjdG9yLlxuICpcbiAqIEBzaW5jZSAxLjE4LjBcbiAqXG4gKiBAcGFyYW0ge0VsZW1lbnR9IGVsICAgICAgIEEgRE9NIGVsZW1lbnQuXG4gKiBAcGFyYW0ge3N0cmluZ30gIHNlbGVjdG9yIEEgc2VsZWN0b3IgdG8gY2hlY2sgZm9yLlxuICogQHJldHVybiB7Ym9vbGVhbn0gVHJ1ZSBpZiB0aGUgRE9NIGVsZW1lbnQgbWF0Y2hlcyB0aGUgc2VsZWN0b3IsIGZhbHNlIG90aGVyd2lzZS5cbiAqL1xuZnVuY3Rpb24gbWF0Y2hlcyggZWwsIHNlbGVjdG9yICkge1xuXHQvLyBVc2UgZmFsbGJhY2tzIGZvciBvbGRlciBicm93c2Vycy5cblx0Ly8gU2VlIGh0dHBzOi8vZGV2ZWxvcGVyLm1vemlsbGEub3JnL2VuLVVTL2RvY3MvV2ViL0FQSS9FbGVtZW50L21hdGNoZXMjUG9seWZpbGwuXG5cdGNvbnN0IG1hdGNoZXIgPVxuXHRcdGVsLm1hdGNoZXMgfHxcblx0XHRlbC5tYXRjaGVzU2VsZWN0b3IgfHxcblx0XHRlbC53ZWJraXRNYXRjaGVzU2VsZWN0b3IgfHxcblx0XHRlbC5tb3pNYXRjaGVzU2VsZWN0b3IgfHxcblx0XHRlbC5tc01hdGNoZXNTZWxlY3RvciB8fFxuXHRcdGVsLm9NYXRjaGVzU2VsZWN0b3IgfHxcblx0XHRmdW5jdGlvbiAoIHMgKSB7XG5cdFx0XHRjb25zdCBlbGVtZW50cyA9IChcblx0XHRcdFx0dGhpcy5kb2N1bWVudCB8fCB0aGlzLm93bmVyRG9jdW1lbnRcblx0XHRcdCkucXVlcnlTZWxlY3RvckFsbCggcyApO1xuXHRcdFx0bGV0IGkgPSBlbGVtZW50cy5sZW5ndGg7XG5cdFx0XHR3aGlsZSAoIC0taSA+PSAwICYmIGVsZW1lbnRzLml0ZW0oIGkgKSAhPT0gdGhpcyApIHt9XG5cdFx0XHRyZXR1cm4gaSA+IC0xO1xuXHRcdH07XG5cblx0aWYgKCBtYXRjaGVyICkge1xuXHRcdHJldHVybiBtYXRjaGVyLmNhbGwoIGVsLCBzZWxlY3RvciApO1xuXHR9XG5cblx0cmV0dXJuIGZhbHNlO1xufVxuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0EiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./js/analytics-advanced-tracking/set-up-advanced-tracking.js\n");

/***/ })

/******/ });