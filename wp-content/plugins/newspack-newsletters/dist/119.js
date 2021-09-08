(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[119],{

/***/ "./node_modules/codemirror/mode/z80/z80.js":
/*!*************************************************!*\
  !*** ./node_modules/codemirror/mode/z80/z80.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// CodeMirror, copyright (c) by Marijn Haverbeke and others\n// Distributed under an MIT license: https://codemirror.net/LICENSE\n\n(function(mod) {\n  if (true) // CommonJS\n  mod(__webpack_require__(/*! ../../lib/codemirror */ \"./node_modules/codemirror/lib/codemirror.js\"));\n  else {}\n})(function(CodeMirror) {\n\"use strict\";\n\nCodeMirror.defineMode('z80', function(_config, parserConfig) {\n  var ez80 = parserConfig.ez80;\n  var keywords1, keywords2;\n  if (ez80) {\n    keywords1 = /^(exx?|(ld|cp)([di]r?)?|[lp]ea|pop|push|ad[cd]|cpl|daa|dec|inc|neg|sbc|sub|and|bit|[cs]cf|x?or|res|set|r[lr]c?a?|r[lr]d|s[lr]a|srl|djnz|nop|[de]i|halt|im|in([di]mr?|ir?|irx|2r?)|ot(dmr?|[id]rx|imr?)|out(0?|[di]r?|[di]2r?)|tst(io)?|slp)(\\.([sl]?i)?[sl])?\\b/i;\n    keywords2 = /^(((call|j[pr]|rst|ret[in]?)(\\.([sl]?i)?[sl])?)|(rs|st)mix)\\b/i;\n  } else {\n    keywords1 = /^(exx?|(ld|cp|in)([di]r?)?|pop|push|ad[cd]|cpl|daa|dec|inc|neg|sbc|sub|and|bit|[cs]cf|x?or|res|set|r[lr]c?a?|r[lr]d|s[lr]a|srl|djnz|nop|rst|[de]i|halt|im|ot[di]r|out[di]?)\\b/i;\n    keywords2 = /^(call|j[pr]|ret[in]?|b_?(call|jump))\\b/i;\n  }\n\n  var variables1 = /^(af?|bc?|c|de?|e|hl?|l|i[xy]?|r|sp)\\b/i;\n  var variables2 = /^(n?[zc]|p[oe]?|m)\\b/i;\n  var errors = /^([hl][xy]|i[xy][hl]|slia|sll)\\b/i;\n  var numbers = /^([\\da-f]+h|[0-7]+o|[01]+b|\\d+d?)\\b/i;\n\n  return {\n    startState: function() {\n      return {\n        context: 0\n      };\n    },\n    token: function(stream, state) {\n      if (!stream.column())\n        state.context = 0;\n\n      if (stream.eatSpace())\n        return null;\n\n      var w;\n\n      if (stream.eatWhile(/\\w/)) {\n        if (ez80 && stream.eat('.')) {\n          stream.eatWhile(/\\w/);\n        }\n        w = stream.current();\n\n        if (stream.indentation()) {\n          if ((state.context == 1 || state.context == 4) && variables1.test(w)) {\n            state.context = 4;\n            return 'var2';\n          }\n\n          if (state.context == 2 && variables2.test(w)) {\n            state.context = 4;\n            return 'var3';\n          }\n\n          if (keywords1.test(w)) {\n            state.context = 1;\n            return 'keyword';\n          } else if (keywords2.test(w)) {\n            state.context = 2;\n            return 'keyword';\n          } else if (state.context == 4 && numbers.test(w)) {\n            return 'number';\n          }\n\n          if (errors.test(w))\n            return 'error';\n        } else if (stream.match(numbers)) {\n          return 'number';\n        } else {\n          return null;\n        }\n      } else if (stream.eat(';')) {\n        stream.skipToEnd();\n        return 'comment';\n      } else if (stream.eat('\"')) {\n        while (w = stream.next()) {\n          if (w == '\"')\n            break;\n\n          if (w == '\\\\')\n            stream.next();\n        }\n        return 'string';\n      } else if (stream.eat('\\'')) {\n        if (stream.match(/\\\\?.'/))\n          return 'number';\n      } else if (stream.eat('.') || stream.sol() && stream.eat('#')) {\n        state.context = 5;\n\n        if (stream.eatWhile(/\\w/))\n          return 'def';\n      } else if (stream.eat('$')) {\n        if (stream.eatWhile(/[\\da-f]/i))\n          return 'number';\n      } else if (stream.eat('%')) {\n        if (stream.eatWhile(/[01]/))\n          return 'number';\n      } else {\n        stream.next();\n      }\n      return null;\n    }\n  };\n});\n\nCodeMirror.defineMIME(\"text/x-z80\", \"z80\");\nCodeMirror.defineMIME(\"text/x-ez80\", { name: \"z80\", ez80: true });\n\n});\n\n\n//# sourceURL=webpack:///./node_modules/codemirror/mode/z80/z80.js?");

/***/ })

}]);