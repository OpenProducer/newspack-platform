(globalThis.webpackChunknewspack_newsletters=globalThis.webpackChunknewspack_newsletters||[]).push([[9770],{4856:(e,t,n)=>{!function(e){"use strict";function t(e,t){if(!e.hasOwnProperty(t))throw new Error("Undefined state "+t+" in simple mode")}function n(e,t){if(!e)return/(?:)/;var n="";return e instanceof RegExp?(e.ignoreCase&&(n="i"),e.unicode&&(n+="u"),e=e.source):e=String(e),new RegExp((!1===t?"":"^")+"(?:"+e+")",n)}function r(e,r){(e.next||e.push)&&t(r,e.next||e.push),this.regex=n(e.regex),this.token=function(e){if(!e)return null;if(e.apply)return e;if("string"==typeof e)return e.replace(/\./g," ");for(var t=[],n=0;n<e.length;n++)t.push(e[n]&&e[n].replace(/\./g," "));return t}(e.token),this.data=e}function a(e,t){return function(n,r){if(r.pending){var a=r.pending.shift();return 0==r.pending.length&&(r.pending=null),n.pos+=a.text.length,a.token}if(r.local){if(r.local.end&&n.match(r.local.end)){var o=r.local.endToken||null;return r.local=r.localState=null,o}var s;return o=r.local.mode.token(n,r.localState),r.local.endScan&&(s=r.local.endScan.exec(n.current()))&&(n.pos=n.start+s.index),o}for(var l=e[r.state],c=0;c<l.length;c++){var d=l[c],u=(!d.data.sol||n.sol())&&n.match(d.regex);if(u){d.data.next?r.state=d.data.next:d.data.push?((r.stack||(r.stack=[])).push(r.state),r.state=d.data.push):d.data.pop&&r.stack&&r.stack.length&&(r.state=r.stack.pop()),d.data.mode&&i(t,r,d.data.mode,d.token),d.data.indent&&r.indent.push(n.indentation()+t.indentUnit),d.data.dedent&&r.indent.pop();var f=d.token;if(f&&f.apply&&(f=f(u)),u.length>2&&d.token&&"string"!=typeof d.token){for(var p=2;p<u.length;p++)u[p]&&(r.pending||(r.pending=[])).push({text:u[p],token:d.token[p-1]});return n.backUp(u[0].length-(u[1]?u[1].length:0)),f[0]}return f&&f.join?f[0]:f}}return n.next(),null}}function o(e,t){if(e===t)return!0;if(!e||"object"!=typeof e||!t||"object"!=typeof t)return!1;var n=0;for(var r in e)if(e.hasOwnProperty(r)){if(!t.hasOwnProperty(r)||!o(e[r],t[r]))return!1;n++}for(var r in t)t.hasOwnProperty(r)&&n--;return 0==n}function i(t,r,a,i){var s;if(a.persistent)for(var l=r.persistentStates;l&&!s;l=l.next)(a.spec?o(a.spec,l.spec):a.mode==l.mode)&&(s=l);var c=s?s.mode:a.mode||e.getMode(t,a.spec),d=s?s.state:e.startState(c);a.persistent&&!s&&(r.persistentStates={mode:c,spec:a.spec,state:d,next:r.persistentStates}),r.localState=d,r.local={mode:c,end:a.end&&n(a.end),endScan:a.end&&!1!==a.forceEnd&&n(a.end,!1),endToken:i&&i.join?i[i.length-1]:i}}function s(t,n){return function(r,a,o){if(r.local&&r.local.mode.indent)return r.local.mode.indent(r.localState,a,o);if(null==r.indent||r.local||n.dontIndentStates&&function(e,t){for(var n=0;n<t.length;n++)if(t[n]===e)return!0}(r.state,n.dontIndentStates)>-1)return e.Pass;var i=r.indent.length-1,s=t[r.state];e:for(;;){for(var l=0;l<s.length;l++){var c=s[l];if(c.data.dedent&&!1!==c.data.dedentIfLineStart){var d=c.regex.exec(a);if(d&&d[0]){i--,(c.next||c.push)&&(s=t[c.next||c.push]),a=a.slice(d[0].length);continue e}}}break}return i<0?0:r.indent[i]}}e.defineSimpleMode=function(t,n){e.defineMode(t,(function(t){return e.simpleMode(t,n)}))},e.simpleMode=function(n,o){t(o,"start");var i={},l=o.meta||{},c=!1;for(var d in o)if(d!=l&&o.hasOwnProperty(d))for(var u=i[d]=[],f=o[d],p=0;p<f.length;p++){var g=f[p];u.push(new r(g,o)),(g.indent||g.dedent)&&(c=!0)}var x={startState:function(){return{state:"start",pending:null,local:null,localState:null,indent:c?[]:null}},copyState:function(t){var n={state:t.state,pending:t.pending,local:t.local,localState:null,indent:t.indent&&t.indent.slice(0)};t.localState&&(n.localState=e.copyState(t.local.mode,t.localState)),t.stack&&(n.stack=t.stack.slice(0));for(var r=t.persistentStates;r;r=r.next)n.persistentStates={mode:r.mode,spec:r.spec,state:r.state==t.localState?n.localState:e.copyState(r.mode,r.state),next:n.persistentStates};return n},token:a(i,n),innerMode:function(e){return e.local&&{mode:e.local.mode,state:e.localState}},indent:s(i,l)};if(l)for(var k in l)l.hasOwnProperty(k)&&(x[k]=l[k]);return x}}(n(5237))},7389:(e,t,n)=>{!function(e){"use strict";e.defineSimpleMode("rust",{start:[{regex:/b?"/,token:"string",next:"string"},{regex:/b?r"/,token:"string",next:"string_raw"},{regex:/b?r#+"/,token:"string",next:"string_raw_hash"},{regex:/'(?:[^'\\]|\\(?:[nrt0'"]|x[\da-fA-F]{2}|u\{[\da-fA-F]{6}\}))'/,token:"string-2"},{regex:/b'(?:[^']|\\(?:['\\nrt0]|x[\da-fA-F]{2}))'/,token:"string-2"},{regex:/(?:(?:[0-9][0-9_]*)(?:(?:[Ee][+-]?[0-9_]+)|\.[0-9_]+(?:[Ee][+-]?[0-9_]+)?)(?:f32|f64)?)|(?:0(?:b[01_]+|(?:o[0-7_]+)|(?:x[0-9a-fA-F_]+))|(?:[0-9][0-9_]*))(?:u8|u16|u32|u64|i8|i16|i32|i64|isize|usize)?/,token:"number"},{regex:/(let(?:\s+mut)?|fn|enum|mod|struct|type|union)(\s+)([a-zA-Z_][a-zA-Z0-9_]*)/,token:["keyword",null,"def"]},{regex:/(?:abstract|alignof|as|async|await|box|break|continue|const|crate|do|dyn|else|enum|extern|fn|for|final|if|impl|in|loop|macro|match|mod|move|offsetof|override|priv|proc|pub|pure|ref|return|self|sizeof|static|struct|super|trait|type|typeof|union|unsafe|unsized|use|virtual|where|while|yield)\b/,token:"keyword"},{regex:/\b(?:Self|isize|usize|char|bool|u8|u16|u32|u64|f16|f32|f64|i8|i16|i32|i64|str|Option)\b/,token:"atom"},{regex:/\b(?:true|false|Some|None|Ok|Err)\b/,token:"builtin"},{regex:/\b(fn)(\s+)([a-zA-Z_][a-zA-Z0-9_]*)/,token:["keyword",null,"def"]},{regex:/#!?\[.*\]/,token:"meta"},{regex:/\/\/.*/,token:"comment"},{regex:/\/\*/,token:"comment",next:"comment"},{regex:/[-+\/*=<>!]+/,token:"operator"},{regex:/[a-zA-Z_]\w*!/,token:"variable-3"},{regex:/[a-zA-Z_]\w*/,token:"variable"},{regex:/[\{\[\(]/,indent:!0},{regex:/[\}\]\)]/,dedent:!0}],string:[{regex:/"/,token:"string",next:"start"},{regex:/(?:[^\\"]|\\(?:.|$))*/,token:"string"}],string_raw:[{regex:/"/,token:"string",next:"start"},{regex:/[^"]*/,token:"string"}],string_raw_hash:[{regex:/"#+/,token:"string",next:"start"},{regex:/(?:[^"]|"(?!#))*/,token:"string"}],comment:[{regex:/.*?\*\//,token:"comment",next:"start"},{regex:/.*/,token:"comment"}],meta:{dontIndentStates:["comment"],electricInput:/^\s*\}$/,blockCommentStart:"/*",blockCommentEnd:"*/",lineComment:"//",fold:"brace"}}),e.defineMIME("text/x-rustsrc","rust"),e.defineMIME("text/rust","rust")}(n(5237),n(4856))}}]);