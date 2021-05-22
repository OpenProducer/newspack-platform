(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[29],{

/***/ "./node_modules/codemirror/mode/apl/apl.js":
/*!*************************************************!*\
  !*** ./node_modules/codemirror/mode/apl/apl.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// CodeMirror, copyright (c) by Marijn Haverbeke and others\n// Distributed under an MIT license: https://codemirror.net/LICENSE\n\n(function(mod) {\n  if (true) // CommonJS\n    mod(__webpack_require__(/*! ../../lib/codemirror */ \"./node_modules/codemirror/lib/codemirror.js\"));\n  else {}\n})(function(CodeMirror) {\n\"use strict\";\n\nCodeMirror.defineMode(\"apl\", function() {\n  var builtInOps = {\n    \".\": \"innerProduct\",\n    \"\\\\\": \"scan\",\n    \"/\": \"reduce\",\n    \"⌿\": \"reduce1Axis\",\n    \"⍀\": \"scan1Axis\",\n    \"¨\": \"each\",\n    \"⍣\": \"power\"\n  };\n  var builtInFuncs = {\n    \"+\": [\"conjugate\", \"add\"],\n    \"−\": [\"negate\", \"subtract\"],\n    \"×\": [\"signOf\", \"multiply\"],\n    \"÷\": [\"reciprocal\", \"divide\"],\n    \"⌈\": [\"ceiling\", \"greaterOf\"],\n    \"⌊\": [\"floor\", \"lesserOf\"],\n    \"∣\": [\"absolute\", \"residue\"],\n    \"⍳\": [\"indexGenerate\", \"indexOf\"],\n    \"?\": [\"roll\", \"deal\"],\n    \"⋆\": [\"exponentiate\", \"toThePowerOf\"],\n    \"⍟\": [\"naturalLog\", \"logToTheBase\"],\n    \"○\": [\"piTimes\", \"circularFuncs\"],\n    \"!\": [\"factorial\", \"binomial\"],\n    \"⌹\": [\"matrixInverse\", \"matrixDivide\"],\n    \"<\": [null, \"lessThan\"],\n    \"≤\": [null, \"lessThanOrEqual\"],\n    \"=\": [null, \"equals\"],\n    \">\": [null, \"greaterThan\"],\n    \"≥\": [null, \"greaterThanOrEqual\"],\n    \"≠\": [null, \"notEqual\"],\n    \"≡\": [\"depth\", \"match\"],\n    \"≢\": [null, \"notMatch\"],\n    \"∈\": [\"enlist\", \"membership\"],\n    \"⍷\": [null, \"find\"],\n    \"∪\": [\"unique\", \"union\"],\n    \"∩\": [null, \"intersection\"],\n    \"∼\": [\"not\", \"without\"],\n    \"∨\": [null, \"or\"],\n    \"∧\": [null, \"and\"],\n    \"⍱\": [null, \"nor\"],\n    \"⍲\": [null, \"nand\"],\n    \"⍴\": [\"shapeOf\", \"reshape\"],\n    \",\": [\"ravel\", \"catenate\"],\n    \"⍪\": [null, \"firstAxisCatenate\"],\n    \"⌽\": [\"reverse\", \"rotate\"],\n    \"⊖\": [\"axis1Reverse\", \"axis1Rotate\"],\n    \"⍉\": [\"transpose\", null],\n    \"↑\": [\"first\", \"take\"],\n    \"↓\": [null, \"drop\"],\n    \"⊂\": [\"enclose\", \"partitionWithAxis\"],\n    \"⊃\": [\"diclose\", \"pick\"],\n    \"⌷\": [null, \"index\"],\n    \"⍋\": [\"gradeUp\", null],\n    \"⍒\": [\"gradeDown\", null],\n    \"⊤\": [\"encode\", null],\n    \"⊥\": [\"decode\", null],\n    \"⍕\": [\"format\", \"formatByExample\"],\n    \"⍎\": [\"execute\", null],\n    \"⊣\": [\"stop\", \"left\"],\n    \"⊢\": [\"pass\", \"right\"]\n  };\n\n  var isOperator = /[\\.\\/⌿⍀¨⍣]/;\n  var isNiladic = /⍬/;\n  var isFunction = /[\\+−×÷⌈⌊∣⍳\\?⋆⍟○!⌹<≤=>≥≠≡≢∈⍷∪∩∼∨∧⍱⍲⍴,⍪⌽⊖⍉↑↓⊂⊃⌷⍋⍒⊤⊥⍕⍎⊣⊢]/;\n  var isArrow = /←/;\n  var isComment = /[⍝#].*$/;\n\n  var stringEater = function(type) {\n    var prev;\n    prev = false;\n    return function(c) {\n      prev = c;\n      if (c === type) {\n        return prev === \"\\\\\";\n      }\n      return true;\n    };\n  };\n  return {\n    startState: function() {\n      return {\n        prev: false,\n        func: false,\n        op: false,\n        string: false,\n        escape: false\n      };\n    },\n    token: function(stream, state) {\n      var ch, funcName;\n      if (stream.eatSpace()) {\n        return null;\n      }\n      ch = stream.next();\n      if (ch === '\"' || ch === \"'\") {\n        stream.eatWhile(stringEater(ch));\n        stream.next();\n        state.prev = true;\n        return \"string\";\n      }\n      if (/[\\[{\\(]/.test(ch)) {\n        state.prev = false;\n        return null;\n      }\n      if (/[\\]}\\)]/.test(ch)) {\n        state.prev = true;\n        return null;\n      }\n      if (isNiladic.test(ch)) {\n        state.prev = false;\n        return \"niladic\";\n      }\n      if (/[¯\\d]/.test(ch)) {\n        if (state.func) {\n          state.func = false;\n          state.prev = false;\n        } else {\n          state.prev = true;\n        }\n        stream.eatWhile(/[\\w\\.]/);\n        return \"number\";\n      }\n      if (isOperator.test(ch)) {\n        return \"operator apl-\" + builtInOps[ch];\n      }\n      if (isArrow.test(ch)) {\n        return \"apl-arrow\";\n      }\n      if (isFunction.test(ch)) {\n        funcName = \"apl-\";\n        if (builtInFuncs[ch] != null) {\n          if (state.prev) {\n            funcName += builtInFuncs[ch][1];\n          } else {\n            funcName += builtInFuncs[ch][0];\n          }\n        }\n        state.func = true;\n        state.prev = false;\n        return \"function \" + funcName;\n      }\n      if (isComment.test(ch)) {\n        stream.skipToEnd();\n        return \"comment\";\n      }\n      if (ch === \"∘\" && stream.peek() === \".\") {\n        stream.next();\n        return \"function jot-dot\";\n      }\n      stream.eatWhile(/[\\w\\$_]/);\n      state.prev = true;\n      return \"keyword\";\n    }\n  };\n});\n\nCodeMirror.defineMIME(\"text/apl\", \"apl\");\n\n});\n\n\n//# sourceURL=webpack:///./node_modules/codemirror/mode/apl/apl.js?");

/***/ })

}]);