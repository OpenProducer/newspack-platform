(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[42],{

/***/ "./node_modules/codemirror/mode/diff/diff.js":
/*!***************************************************!*\
  !*** ./node_modules/codemirror/mode/diff/diff.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// CodeMirror, copyright (c) by Marijn Haverbeke and others\n// Distributed under an MIT license: https://codemirror.net/LICENSE\n\n(function(mod) {\n  if (true) // CommonJS\n    mod(__webpack_require__(/*! ../../lib/codemirror */ \"./node_modules/codemirror/lib/codemirror.js\"));\n  else {}\n})(function(CodeMirror) {\n\"use strict\";\n\nCodeMirror.defineMode(\"diff\", function() {\n\n  var TOKEN_NAMES = {\n    '+': 'positive',\n    '-': 'negative',\n    '@': 'meta'\n  };\n\n  return {\n    token: function(stream) {\n      var tw_pos = stream.string.search(/[\\t ]+?$/);\n\n      if (!stream.sol() || tw_pos === 0) {\n        stream.skipToEnd();\n        return (\"error \" + (\n          TOKEN_NAMES[stream.string.charAt(0)] || '')).replace(/ $/, '');\n      }\n\n      var token_name = TOKEN_NAMES[stream.peek()] || stream.skipToEnd();\n\n      if (tw_pos === -1) {\n        stream.skipToEnd();\n      } else {\n        stream.pos = tw_pos;\n      }\n\n      return token_name;\n    }\n  };\n});\n\nCodeMirror.defineMIME(\"text/x-diff\", \"diff\");\n\n});\n\n\n//# sourceURL=webpack:///./node_modules/codemirror/mode/diff/diff.js?");

/***/ })

}]);