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
/******/ 	return __webpack_require__(__webpack_require__.s = "./newspack-theme/js/src/customize-controls.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./newspack-theme/js/src/customize-controls.js":
/*!*****************************************************!*\
  !*** ./newspack-theme/js/src/customize-controls.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("/**\n * File customizer.js.\n *\n * Theme Customizer enhancements for a better user experience.\n *\n * Contains handlers to make Theme Customizer preview reload changes asynchronously.\n */\n(function ($) {\n  wp.customize.bind('ready', function () {\n    // Only show the color hue control when there's a custom primary color.\n    wp.customize('theme_colors', function (setting) {\n      wp.customize.control('primary_color_hex', function (control) {\n        var visibility = function visibility() {\n          if ('custom' === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('secondary_color_hex', function (control) {\n        var visibility = function visibility() {\n          if ('custom' === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('header_color', function (control) {\n        var visibility = function visibility() {\n          if ('custom' === setting.get()) {\n            // Make sure the site is set to use a solid header background.\n            if (true === wp.customize.value('header_solid_background')()) {\n              control.container.slideDown(180);\n            }\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('header_color_hex', function (control) {\n        var visibility = function visibility() {\n          if ('custom' === setting.get()) {\n            // Make sure the site is set to use a solid header background.\n            if (true === wp.customize.value('header_solid_background')() && 'custom' === wp.customize.value('header_color')()) {\n              control.container.slideDown(180);\n            }\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Controls to show/hide when the Solid Background is toggled.\n\n    wp.customize('header_solid_background', function (setting) {\n      wp.customize.control('header_color', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            if ('custom' === wp.customize.value('theme_colors')()) {\n              control.container.slideDown(180);\n            }\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('header_color_hex', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            if ('custom' === wp.customize.value('header_color')() && 'custom' === wp.customize.value('theme_colors')()) {\n              control.container.slideDown(180);\n            }\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Controls to show/hide when the Solid Background is toggled.\n\n    wp.customize('header_color', function (setting) {\n      wp.customize.control('header_color_hex', function (control) {\n        var visibility = function visibility() {\n          if ('custom' === setting.get()) {\n            if (true === wp.customize.value('header_solid_background')() && 'custom' === wp.customize.value('theme_colors')()) {\n              control.container.slideDown(180);\n            }\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Only show the rest of the author controls when the bio is visible.\n\n    wp.customize('show_author_bio', function (setting) {\n      wp.customize.control('show_author_email', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('show_author_social', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Only show Slide-out Sidebar options when enabled.\n\n    wp.customize('header_show_slideout', function (setting) {\n      wp.customize.control('slideout_label', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n      wp.customize.control('slideout_widget_mobile', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Only show Alternative Logo option if 'simple subpage header' is picked\n\n    wp.customize('header_sub_simplified', function (setting) {\n      wp.customize.control('newspack_alternative_logo', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Only show Author Bio truncate options when enabled.\n\n    wp.customize('author_bio_truncate', function (setting) {\n      wp.customize.control('author_bio_length', function (control) {\n        var visibility = function visibility() {\n          if (true === setting.get()) {\n            control.container.slideDown(180);\n          } else {\n            control.container.slideUp(180);\n          }\n        };\n\n        visibility();\n        setting.bind(visibility);\n      });\n    }); // Lets you jump to specific sections in the Customizer\n\n    $(['control', 'section', 'panel']).each(function (i, type) {\n      $('a[rel=\"goto-' + type + '\"]').click(function (e) {\n        e.preventDefault();\n        var id = $(this).attr('href').replace('#', '');\n\n        if (wp.customize[type].has(id)) {\n          wp.customize[type].instance(id).focus();\n        }\n      });\n    });\n  });\n})(jQuery);\n\n//# sourceURL=webpack:///./newspack-theme/js/src/customize-controls.js?");

/***/ })

/******/ })));