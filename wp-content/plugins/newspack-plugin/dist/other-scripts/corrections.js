(()=>{"use strict";var t,e={9630:(t,e,r)=>{var n=r(7723),o=r(7143);(0,r(1622).Qc)((()=>{const t=document.querySelector(".corrections-metabox-container");if(t){const e=t.querySelector("input.activate-corrections-checkbox"),r=t.querySelector(".display-corrections");r.style.display=e.checked?"block":"none",e.addEventListener("change",(()=>{e.checked?r.style.display="block":r.style.display="none"})),t.querySelectorAll(".existing-corrections button.delete-correction").forEach((e=>{e.addEventListener("click",(e=>{const r=e.target.closest(".correction");if(r){r.remove();const e=r.getAttribute("name").replace("existing-corrections[","").replace("]","");if(e){const r=t.querySelector(".deleted-corrections"),n=document.createElement("input");n.type="hidden",n.name="deleted-corrections[]",n.value=e,r.appendChild(n)}}}))}));let c=0;t.querySelector("button.add-correction").addEventListener("click",(()=>{const e=t.querySelector(".new-corrections"),r=document.createElement("div");r.classList.add("correction"),r.innerHTML=`\n\t\t\t\t<fieldset name="new-corrections[${c}]">\n\t\t\t\t<p>${(0,n.__)("Article Correction","newspack-plugin")}</p>\n\t\t\t\t<textarea name="new-corrections[${c}][content]" rows="3" cols="60"></textarea>\n\t\t\t\t<br/>\n\t\t\t\t<p>${(0,n.__)("Date:","newspack-plugin")} <input type="date" name="new-corrections[${c}][date]"></p>\n\t\t\t\t<p>${(0,n.__)("Type:","newspack-plugin")}\n\t\t\t\t\t<select name="new-corrections[${c}][type]">\n\t\t\t\t\t\t<option value="correction">${(0,n.__)("Correction","newspack-plugin")}</option>\n\t\t\t\t\t\t<option value="clarification">${(0,n.__)("Clarification","newspack-plugin")}</option>\n\t\t\t\t\t</select>\n\t\t\t\t</p>\n\t\t\t\t<button class="delete-correction">X</button>\n\t\t\t\t</fieldset>\n\t\t\t`,e.appendChild(r),r.querySelector("button.delete-correction").addEventListener("click",(()=>{r.remove()})),c++}));let i=!1;const a=(0,o.subscribe)((()=>{if(!c)return;const t=(0,o.select)("core/editor").isSavingPost(),e=(0,o.select)("core/editor").isAutosavingPost();!t||e||i||(i=!0),!t&&i&&(a(),window.location.href=window.location.href)}))}}))},7143:t=>{t.exports=window.wp.data},7723:t=>{t.exports=window.wp.i18n}},r={};function n(t){var o=r[t];if(void 0!==o)return o.exports;var c=r[t]={id:t,loaded:!1,exports:{}};return e[t].call(c.exports,c,c.exports,n),c.loaded=!0,c.exports}n.m=e,t=[],n.O=(e,r,o,c)=>{if(!r){var i=1/0;for(p=0;p<t.length;p++){for(var[r,o,c]=t[p],a=!0,l=0;l<r.length;l++)(!1&c||i>=c)&&Object.keys(n.O).every((t=>n.O[t](r[l])))?r.splice(l--,1):(a=!1,c<i&&(i=c));if(a){t.splice(p--,1);var s=o();void 0!==s&&(e=s)}}return e}c=c||0;for(var p=t.length;p>0&&t[p-1][2]>c;p--)t[p]=t[p-1];t[p]=[r,o,c]},n.n=t=>{var e=t&&t.__esModule?()=>t.default:()=>t;return n.d(e,{a:e}),e},n.d=(t,e)=>{for(var r in e)n.o(e,r)&&!n.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:e[r]})},n.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(t){if("object"==typeof window)return window}}(),n.o=(t,e)=>Object.prototype.hasOwnProperty.call(t,e),n.r=t=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.nmd=t=>(t.paths=[],t.children||(t.children=[]),t),n.j=10,(()=>{var t;n.g.importScripts&&(t=n.g.location+"");var e=n.g.document;if(!t&&e&&(e.currentScript&&(t=e.currentScript.src),!t)){var r=e.getElementsByTagName("script");if(r.length)for(var o=r.length-1;o>-1&&(!t||!/^http(s?):/.test(t));)t=r[o--].src}if(!t)throw new Error("Automatic publicPath is not supported in this browser");t=t.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),n.p=t+"../"})(),(()=>{var t={10:0};n.O.j=e=>0===t[e];var e=(e,r)=>{var o,c,[i,a,l]=r,s=0;if(i.some((e=>0!==t[e]))){for(o in a)n.o(a,o)&&(n.m[o]=a[o]);if(l)var p=l(n)}for(e&&e(r);s<i.length;s++)c=i[s],n.o(t,c)&&t[c]&&t[c][0](),t[c]=0;return n.O(p)},r=globalThis.webpackChunknewspack=globalThis.webpackChunknewspack||[];r.forEach(e.bind(null,0)),r.push=e.bind(null,r.push.bind(r))})();var o=n.O(void 0,[223],(()=>n(9630)));o=n.O(o)})();