(globalThis.webpackChunknewspack_newsletters=globalThis.webpackChunknewspack_newsletters||[]).push([[3604],{3604:(e,t,n)=>{!function(e){"use strict";e.registerHelper("wordChars","r",/[\w.]/),e.defineMode("r",(function(t){function n(e){for(var t={},n=0;n<e.length;++n)t[e[n]]=!0;return t}var r=["NULL","NA","Inf","NaN","NA_integer_","NA_real_","NA_complex_","NA_character_","TRUE","FALSE"],a=["list","quote","bquote","eval","return","call","parse","deparse"],i=["if","else","repeat","while","function","for","in","next","break"];e.registerHelper("hintWords","r",r.concat(a,i));var c,o=n(r),l=n(a),u=n(i),s=n(["if","else","repeat","while","function","for"]),f=/[+\-*\/^<>=!&|~$:]/;function p(e,t){c=null;var n,r=e.next();if("#"==r)return e.skipToEnd(),"comment";if("0"==r&&e.eat("x"))return e.eatWhile(/[\da-f]/i),"number";if("."==r&&e.eat(/\d/))return e.match(/\d*(?:e[+\-]?\d+)?/),"number";if(/\d/.test(r))return e.match(/\d*(?:\.\d+)?(?:e[+\-]\d+)?L?/),"number";if("'"==r||'"'==r)return t.tokenize=(n=r,function(e,t){if(e.eat("\\")){var r=e.next();return"x"==r?e.match(/^[a-f0-9]{2}/i):("u"==r||"U"==r)&&e.eat("{")&&e.skipTo("}")?e.next():"u"==r?e.match(/^[a-f0-9]{4}/i):"U"==r?e.match(/^[a-f0-9]{8}/i):/[0-7]/.test(r)&&e.match(/^[0-7]{1,2}/),"string-2"}for(var a;null!=(a=e.next());){if(a==n){t.tokenize=p;break}if("\\"==a){e.backUp(1);break}}return"string"}),"string";if("`"==r)return e.match(/[^`]+`/),"variable-3";if("."==r&&e.match(/.(?:[.]|\d+)/))return"keyword";if(/[a-zA-Z\.]/.test(r)){e.eatWhile(/[\w\.]/);var a=e.current();return o.propertyIsEnumerable(a)?"atom":u.propertyIsEnumerable(a)?(s.propertyIsEnumerable(a)&&!e.match(/\s*if(\s+|$)/,!1)&&(c="block"),"keyword"):l.propertyIsEnumerable(a)?"builtin":"variable"}return"%"==r?(e.skipTo("%")&&e.next(),"operator variable-2"):"<"==r&&e.eat("-")||"<"==r&&e.match("<-")||"-"==r&&e.match(/>>?/)?"operator arrow":"="==r&&t.ctx.argList?"arg-is":f.test(r)?"$"==r?"operator dollar":(e.eatWhile(f),"operator"):/[\(\){}\[\];]/.test(r)?(c=r,";"==r?"semi":null):null}function d(e,t,n){e.ctx={type:t,indent:e.indent,flags:0,column:n.column(),prev:e.ctx}}function m(e,t){var n=e.ctx;e.ctx={type:n.type,indent:n.indent,flags:n.flags|t,column:n.column,prev:n.prev}}function x(e){e.indent=e.ctx.indent,e.ctx=e.ctx.prev}return{startState:function(){return{tokenize:p,ctx:{type:"top",indent:-t.indentUnit,flags:2},indent:0,afterIdent:!1}},token:function(e,t){if(e.sol()&&(3&t.ctx.flags||(t.ctx.flags|=2),4&t.ctx.flags&&x(t),t.indent=e.indentation()),e.eatSpace())return null;var n=t.tokenize(e,t);return"comment"==n||2&t.ctx.flags||m(t,1),";"!=c&&"{"!=c&&"}"!=c||"block"!=t.ctx.type||x(t),"{"==c?d(t,"}",e):"("==c?(d(t,")",e),t.afterIdent&&(t.ctx.argList=!0)):"["==c?d(t,"]",e):"block"==c?d(t,"block",e):c==t.ctx.type?x(t):"block"==t.ctx.type&&"comment"!=n&&m(t,4),t.afterIdent="variable"==n||"keyword"==n,n},indent:function(e,n){if(e.tokenize!=p)return 0;var r=n&&n.charAt(0),a=e.ctx,i=r==a.type;return 4&a.flags&&(a=a.prev),"block"==a.type?a.indent+("{"==r?0:t.indentUnit):1&a.flags?a.column+(i?0:1):a.indent+(i?0:t.indentUnit)},lineComment:"#"}})),e.defineMIME("text/x-rsrc","r")}(n(5237))}}]);