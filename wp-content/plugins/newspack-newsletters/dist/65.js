(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[65],{

/***/ "./node_modules/codemirror/mode/livescript/livescript.js":
/*!***************************************************************!*\
  !*** ./node_modules/codemirror/mode/livescript/livescript.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// CodeMirror, copyright (c) by Marijn Haverbeke and others\n// Distributed under an MIT license: https://codemirror.net/LICENSE\n\n/**\n * Link to the project's GitHub page:\n * https://github.com/duralog/CodeMirror\n */\n\n(function(mod) {\n  if (true) // CommonJS\n    mod(__webpack_require__(/*! ../../lib/codemirror */ \"./node_modules/codemirror/lib/codemirror.js\"));\n  else {}\n})(function(CodeMirror) {\n  \"use strict\";\n\n  CodeMirror.defineMode('livescript', function(){\n    var tokenBase = function(stream, state) {\n      var next_rule = state.next || \"start\";\n      if (next_rule) {\n        state.next = state.next;\n        var nr = Rules[next_rule];\n        if (nr.splice) {\n          for (var i$ = 0; i$ < nr.length; ++i$) {\n            var r = nr[i$];\n            if (r.regex && stream.match(r.regex)) {\n              state.next = r.next || state.next;\n              return r.token;\n            }\n          }\n          stream.next();\n          return 'error';\n        }\n        if (stream.match(r = Rules[next_rule])) {\n          if (r.regex && stream.match(r.regex)) {\n            state.next = r.next;\n            return r.token;\n          } else {\n            stream.next();\n            return 'error';\n          }\n        }\n      }\n      stream.next();\n      return 'error';\n    };\n    var external = {\n      startState: function(){\n        return {\n          next: 'start',\n          lastToken: {style: null, indent: 0, content: \"\"}\n        };\n      },\n      token: function(stream, state){\n        while (stream.pos == stream.start)\n          var style = tokenBase(stream, state);\n        state.lastToken = {\n          style: style,\n          indent: stream.indentation(),\n          content: stream.current()\n        };\n        return style.replace(/\\./g, ' ');\n      },\n      indent: function(state){\n        var indentation = state.lastToken.indent;\n        if (state.lastToken.content.match(indenter)) {\n          indentation += 2;\n        }\n        return indentation;\n      }\n    };\n    return external;\n  });\n\n  var identifier = '(?![\\\\d\\\\s])[$\\\\w\\\\xAA-\\\\uFFDC](?:(?!\\\\s)[$\\\\w\\\\xAA-\\\\uFFDC]|-[A-Za-z])*';\n  var indenter = RegExp('(?:[({[=:]|[-~]>|\\\\b(?:e(?:lse|xport)|d(?:o|efault)|t(?:ry|hen)|finally|import(?:\\\\s*all)?|const|var|let|new|catch(?:\\\\s*' + identifier + ')?))\\\\s*$');\n  var keywordend = '(?![$\\\\w]|-[A-Za-z]|\\\\s*:(?![:=]))';\n  var stringfill = {\n    token: 'string',\n    regex: '.+'\n  };\n  var Rules = {\n    start: [\n      {\n        token: 'comment.doc',\n        regex: '/\\\\*',\n        next: 'comment'\n      }, {\n        token: 'comment',\n        regex: '#.*'\n      }, {\n        token: 'keyword',\n        regex: '(?:t(?:h(?:is|row|en)|ry|ypeof!?)|c(?:on(?:tinue|st)|a(?:se|tch)|lass)|i(?:n(?:stanceof)?|mp(?:ort(?:\\\\s+all)?|lements)|[fs])|d(?:e(?:fault|lete|bugger)|o)|f(?:or(?:\\\\s+own)?|inally|unction)|s(?:uper|witch)|e(?:lse|x(?:tends|port)|val)|a(?:nd|rguments)|n(?:ew|ot)|un(?:less|til)|w(?:hile|ith)|o[fr]|return|break|let|var|loop)' + keywordend\n      }, {\n        token: 'constant.language',\n        regex: '(?:true|false|yes|no|on|off|null|void|undefined)' + keywordend\n      }, {\n        token: 'invalid.illegal',\n        regex: '(?:p(?:ackage|r(?:ivate|otected)|ublic)|i(?:mplements|nterface)|enum|static|yield)' + keywordend\n      }, {\n        token: 'language.support.class',\n        regex: '(?:R(?:e(?:gExp|ferenceError)|angeError)|S(?:tring|yntaxError)|E(?:rror|valError)|Array|Boolean|Date|Function|Number|Object|TypeError|URIError)' + keywordend\n      }, {\n        token: 'language.support.function',\n        regex: '(?:is(?:NaN|Finite)|parse(?:Int|Float)|Math|JSON|(?:en|de)codeURI(?:Component)?)' + keywordend\n      }, {\n        token: 'variable.language',\n        regex: '(?:t(?:hat|il|o)|f(?:rom|allthrough)|it|by|e)' + keywordend\n      }, {\n        token: 'identifier',\n        regex: identifier + '\\\\s*:(?![:=])'\n      }, {\n        token: 'variable',\n        regex: identifier\n      }, {\n        token: 'keyword.operator',\n        regex: '(?:\\\\.{3}|\\\\s+\\\\?)'\n      }, {\n        token: 'keyword.variable',\n        regex: '(?:@+|::|\\\\.\\\\.)',\n        next: 'key'\n      }, {\n        token: 'keyword.operator',\n        regex: '\\\\.\\\\s*',\n        next: 'key'\n      }, {\n        token: 'string',\n        regex: '\\\\\\\\\\\\S[^\\\\s,;)}\\\\]]*'\n      }, {\n        token: 'string.doc',\n        regex: '\\'\\'\\'',\n        next: 'qdoc'\n      }, {\n        token: 'string.doc',\n        regex: '\"\"\"',\n        next: 'qqdoc'\n      }, {\n        token: 'string',\n        regex: '\\'',\n        next: 'qstring'\n      }, {\n        token: 'string',\n        regex: '\"',\n        next: 'qqstring'\n      }, {\n        token: 'string',\n        regex: '`',\n        next: 'js'\n      }, {\n        token: 'string',\n        regex: '<\\\\[',\n        next: 'words'\n      }, {\n        token: 'string.regex',\n        regex: '//',\n        next: 'heregex'\n      }, {\n        token: 'string.regex',\n        regex: '\\\\/(?:[^[\\\\/\\\\n\\\\\\\\]*(?:(?:\\\\\\\\.|\\\\[[^\\\\]\\\\n\\\\\\\\]*(?:\\\\\\\\.[^\\\\]\\\\n\\\\\\\\]*)*\\\\])[^[\\\\/\\\\n\\\\\\\\]*)*)\\\\/[gimy$]{0,4}',\n        next: 'key'\n      }, {\n        token: 'constant.numeric',\n        regex: '(?:0x[\\\\da-fA-F][\\\\da-fA-F_]*|(?:[2-9]|[12]\\\\d|3[0-6])r[\\\\da-zA-Z][\\\\da-zA-Z_]*|(?:\\\\d[\\\\d_]*(?:\\\\.\\\\d[\\\\d_]*)?|\\\\.\\\\d[\\\\d_]*)(?:e[+-]?\\\\d[\\\\d_]*)?[\\\\w$]*)'\n      }, {\n        token: 'lparen',\n        regex: '[({[]'\n      }, {\n        token: 'rparen',\n        regex: '[)}\\\\]]',\n        next: 'key'\n      }, {\n        token: 'keyword.operator',\n        regex: '\\\\S+'\n      }, {\n        token: 'text',\n        regex: '\\\\s+'\n      }\n    ],\n    heregex: [\n      {\n        token: 'string.regex',\n        regex: '.*?//[gimy$?]{0,4}',\n        next: 'start'\n      }, {\n        token: 'string.regex',\n        regex: '\\\\s*#{'\n      }, {\n        token: 'comment.regex',\n        regex: '\\\\s+(?:#.*)?'\n      }, {\n        token: 'string.regex',\n        regex: '\\\\S+'\n      }\n    ],\n    key: [\n      {\n        token: 'keyword.operator',\n        regex: '[.?@!]+'\n      }, {\n        token: 'identifier',\n        regex: identifier,\n        next: 'start'\n      }, {\n        token: 'text',\n        regex: '',\n        next: 'start'\n      }\n    ],\n    comment: [\n      {\n        token: 'comment.doc',\n        regex: '.*?\\\\*/',\n        next: 'start'\n      }, {\n        token: 'comment.doc',\n        regex: '.+'\n      }\n    ],\n    qdoc: [\n      {\n        token: 'string',\n        regex: \".*?'''\",\n        next: 'key'\n      }, stringfill\n    ],\n    qqdoc: [\n      {\n        token: 'string',\n        regex: '.*?\"\"\"',\n        next: 'key'\n      }, stringfill\n    ],\n    qstring: [\n      {\n        token: 'string',\n        regex: '[^\\\\\\\\\\']*(?:\\\\\\\\.[^\\\\\\\\\\']*)*\\'',\n        next: 'key'\n      }, stringfill\n    ],\n    qqstring: [\n      {\n        token: 'string',\n        regex: '[^\\\\\\\\\"]*(?:\\\\\\\\.[^\\\\\\\\\"]*)*\"',\n        next: 'key'\n      }, stringfill\n    ],\n    js: [\n      {\n        token: 'string',\n        regex: '[^\\\\\\\\`]*(?:\\\\\\\\.[^\\\\\\\\`]*)*`',\n        next: 'key'\n      }, stringfill\n    ],\n    words: [\n      {\n        token: 'string',\n        regex: '.*?\\\\]>',\n        next: 'key'\n      }, stringfill\n    ]\n  };\n  for (var idx in Rules) {\n    var r = Rules[idx];\n    if (r.splice) {\n      for (var i = 0, len = r.length; i < len; ++i) {\n        var rr = r[i];\n        if (typeof rr.regex === 'string') {\n          Rules[idx][i].regex = new RegExp('^' + rr.regex);\n        }\n      }\n    } else if (typeof rr.regex === 'string') {\n      Rules[idx].regex = new RegExp('^' + r.regex);\n    }\n  }\n\n  CodeMirror.defineMIME('text/x-livescript', 'livescript');\n\n});\n\n\n//# sourceURL=webpack:///./node_modules/codemirror/mode/livescript/livescript.js?");

/***/ })

}]);