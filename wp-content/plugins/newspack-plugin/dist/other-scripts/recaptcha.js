(()=>{"use strict";const e=250,t=.1;function a(e){"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",e):e())}function c(a){let c;const r=new IntersectionObserver((t=>{t.forEach((t=>{t.isIntersecting?c||(c=setTimeout((()=>{a(),r.unobserve(t.target)}),e||0)):c&&(clearTimeout(c),c=!1)}))}),{threshold:t});return r}function r(e,t="submit"){if(e){const a=newspack_recaptcha_data?.site_key;return grecaptcha.execute(a,{action:t}).then((t=>{e.value=t}))}}function n(e){const t=parseInt(e.getAttribute("data-recaptcha-widget-id"));isNaN(t)||grecaptcha.reset(t)}window.newspack_grecaptcha=window.newspack_grecaptcha||{destroy:function(e=[]){(e.length?e:[...document.querySelectorAll("form[data-newspack-recaptcha]")]).forEach((e=>{!function(e){const t=e.querySelector('input[name="g-recaptcha-response"]');t&&t.parentElement.removeChild(t)}(e)}))},render:p,version:newspack_recaptcha_data.version};const o="v2"===newspack_recaptcha_data.version.substring(0,2),i="v3"===newspack_recaptcha_data.version,s=newspack_recaptcha_data.site_key,d="v2_invisible"===newspack_recaptcha_data.version;function u(e,t=null,a=null){e.removeAttribute("data-recaptcha-validated");const r={sitekey:s,size:d?"invisible":"normal",isolated:!0},o=()=>{e.removeAttribute("data-recaptcha-validated");const t=parseInt(e.getAttribute("data-recaptcha-retry-count"))||0;if(t<3)n(e),grecaptcha.execute(e.getAttribute("data-recaptcha-widget-id")),e.setAttribute("data-recaptcha-retry-count",t+1);else{const t=wp.i18n.__("There was an error connecting with reCAPTCHA. Please reload the page and try again.","newspack-plugin");a?a(t):function(e,t){const a=document.createElement("p");a.textContent=t;const c=document.createElement("div");c.classList.add("newspack-recaptcha-error"),c.appendChild(a),e.parentElement.classList.contains("newspack-newsletters-subscribe")?e.append(c):(c.classList.add("newspack-ui__notice","newspack-ui__notice--error"),e.insertBefore(c,e.firstChild))}(e,t)}};if(jQuery&&(jQuery(document).on("updated_checkout",(()=>u(e,t,a))),jQuery(document.body).on("checkout_error",(()=>u(e,t,a)))),e.hasAttribute("data-recaptcha-widget-id"))return void n(e);const i=document.createElement("div");i.classList.add("grecaptcha-container"),document.body.append(i);const p=grecaptcha.render(i,{...r,callback:a=>{t?.();let c=e.querySelector('[name="g-recaptcha-response"]');c||(c=document.createElement("input"),c.type="hidden",c.name="g-recaptcha-response",e.appendChild(c)),c.value=a,e.setAttribute("data-recaptcha-validated","1");const r=e.querySelector("#place_order");r?r.click():e.requestSubmit(e.querySelector('input[type="submit"], button[type="submit"]')),n(e)},"error-callback":o,"expired-callback":o});e.setAttribute("data-recaptcha-widget-id",p),(()=>{e.removeAttribute("data-submit-button-click"),c((()=>u(e,t,a))).observe(e,{attributes:!0});const r=t=>{e.hasAttribute("data-recaptcha-validated")||e.hasAttribute("data-skip-recaptcha")?e.removeAttribute("data-recaptcha-validated"):(t.preventDefault(),t.stopImmediatePropagation(),function(e){const t=e.querySelectorAll(".newspack-recaptcha-error");for(const e of t)e.parentElement.removeChild(e)}(e),grecaptcha.execute(p))};e.addEventListener("submit",r,!0);const n=e.querySelector("#place_order_clone");n&&n.addEventListener("click",(e=>{e.preventDefault(),e.stopImmediatePropagation(),r(e)}),!0)})()}function p(e=[],t=null,n=null){if(!grecaptcha)return a((()=>grecaptcha.ready((()=>p(e,t,n)))));(e.length?e:[...document.querySelectorAll("form[data-newspack-recaptcha],form#add_payment_method,form.checkout")]).forEach((e=>{c((()=>{o&&u(e,t,n),i&&function(e){let t=e.querySelector('input[name="g-recaptcha-response"]');if(!t){t=document.createElement("input"),t.type="hidden",t.name="g-recaptcha-response",e.appendChild(t);const a=e.getAttribute("data-newspack-recaptcha")||"submit";r(t,a),setInterval((()=>r(t,a)),3e4),jQuery&&(jQuery(document).on("updated_checkout",(()=>r(t,a))),jQuery(document.body).on("checkout_error",(()=>r(t,a))))}}(e)})).observe(e,{attributes:!0})}))}a((function(){grecaptcha.ready(p)}))})();