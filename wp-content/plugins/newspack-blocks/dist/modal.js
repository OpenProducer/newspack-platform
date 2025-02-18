(()=>{"use strict";function e(e,t=!1){const n=e.querySelectorAll('button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])');if(0===n.length)return!1;const o=n?.[0];let c;o.focus(),t?(document.addEventListener("keydown",(function(e){if(("Tab"===e.key||9===e.keyCode)&&e.shiftKey&&document.activeElement===o){const n=t.contentWindow.document,o=n.getElementById("customer_details"),a=n.getElementById("after_customer_details"),r=n.getElementById("checkout-after-success");null!==r?c=r:null!==o.offsetParent?c=n.getElementById("checkout_continue"):null!==a.offsetParent&&(c=n.getElementById("checkout_back")),c.focus(),e.preventDefault()}})),document.getElementById("newspack-a11y-last-element").addEventListener("focus",(()=>{o.focus()}))):(c=n[n.length-1],document.addEventListener("keydown",(function(e){("Tab"===e.key||9===e.keyCode)&&(e.shiftKey?document.activeElement===o&&(c.focus(),e.preventDefault()):document.activeElement===c&&(o.focus(),e.preventDefault()))})))}const t=(e,t={})=>({...t,action:e}),n=(e,t="np_modal_checkout_interaction")=>{"function"==typeof window.gtag&&e&&window.gtag("event",t,e)},o=e=>{if("function"!=typeof window.gtag)return;let o="opened";const{action_type:c,amount:a="",currency:r,is_variable:i="",price:s="",product_id:l,product_type:d,recurrence:u,referrer:p,variation_id:_=""}=e,m={action_type:c,currency:r,product_id:l,product_type:d,referrer:p};(a||s)&&(m.amount=a||s),i&&(m.is_variable=i),_&&(m.variation_id=_),u&&(m.recurrence=u),i&&!_&&(o="opened_variations");const y=t(o,m);n(y)},c=e=>{if("function"!=typeof window.gtag)return;e=e||(e=>{const t=document.getElementById("newspack_modal_checkout"),n=!!t&&t.getAttribute("data-order-details");return!!n&&JSON.parse(n)||{}})();const{action_type:o,amount:c="",currency:a,price:r="",product_id:i,product_type:s,recurrence:l,referrer:d,variation_id:u=""}=e,p={action_type:o,currency:a,product_id:i,product_type:s,recurrence:l,referrer:d};(c||r)&&(p.amount=c||r),u&&(p.variation_id=u);const _=t("dismissed",p);n(_)};function a(e,t=null){const n=document.createElement("input");return n.type="hidden",n.name=e,t&&(n.value=t),n}const r=newspackBlocksModal.newspack_class_prefix,i="newspack_modal_checkout_iframe",s="newspack_modal_checkout_container",l=`${r}__modal`,d="newspack-blocks__modal-variation";let u={},p=!1;const _=e=>{e.overlayId&&window.newspackReaderActivation?.overlays&&window.newspackReaderActivation?.overlays.remove(e.overlayId),e.setAttribute("data-state","closed"),document.body.style.overflow="auto"};var m;window.onpageshow=e=>{e.persisted&&(document.querySelectorAll(".modal-processing").forEach((e=>e.classList.remove("modal-processing"))),document.querySelectorAll(".non-modal-checkout-loading").forEach((e=>e.classList.remove("non-modal-checkout-loading"))),document.querySelectorAll(`.${l}-container`).forEach((e=>_(e))))},m=()=>{const t=document.querySelector("#newspack_modal_checkout");if(!t)return;const n=t.querySelector(`.${l}__content`),m=a("modal_checkout","1"),y=n.querySelector(`.${r}__spinner`);let f=document.querySelector(".newspack-reader__account-link")?.[0];const h="600px",k=document.createElement("iframe");function w(){const n=k.contentWindow?.location;if(window.newspackReaderActivation&&n?.href?.includes("order-received")){const e=window.newspackReaderActivation,t=new Proxy(new URLSearchParams(n.search),{get:(e,t)=>e.get(t)});t.email&&(e.setReaderEmail(t.email),e.setAuthenticated(!0))}const o=k?.contentDocument?.querySelector(`#${s}`),c=()=>{E.observe(o),"none"!==y.style.display&&(y.style.display="none"),"visible"!==k.style.visibility&&(k.style.visibility="visible"),k._ready=!0};o&&(o.checkoutComplete?(B("small"),L(newspackBlocksModal.labels.thankyou_modal_title),c(),e(t.querySelector(`.${l}`))):(B(),L(newspackBlocksModal.labels.checkout_modal_title),k.contentWindow?.newspackBlocksModalCheckout?.checkout_nonce&&(t.checkout_nonce=k.contentWindow.newspackBlocksModalCheckout.checkout_nonce)),o.checkoutReady?c():o.addEventListener("checkout-ready",c))}k.name=i,k.style.height=h,k.style.visibility="hidden",k.addEventListener("load",w);const g=e=>new Promise(((t,n)=>{const o=new URLSearchParams(e);o.append("action","modal_checkout_request"),fetch(newspackBlocksModal.ajax_url+"?"+o.toString()).then((e=>{e.ok||n(e),e.json().then((e=>{t(e.url)})).catch(n)})).catch(n)})),v=()=>"undefined"!=typeof newspack_ras_config&&!newspack_ras_config?.is_logged_in&&!window?.newspackReaderActivation?.getReader?.()?.authenticated&&newspackBlocksModal?.is_registration_required&&window?.newspackReaderActivation?.openAuthModal,b=t=>{const n=!newspackBlocksModal.has_unsupported_payment_gateway;n||t.preventDefault();const s=t.target;s.classList.add("modal-processing");const l=s.dataset.product;if(l){const e=JSON.parse(l);Object.keys(e).forEach((t=>{0===s.querySelectorAll('input[name="'+t+'"]').length&&s.appendChild(a(t,e[t]))}))}const m=new FormData(s);m.get("variation_id")||(f=t.submitter);const y=document.querySelectorAll(`.${d}`);if(y.forEach((e=>{(v()||n)&&_(e)})),m.get("is_variable")&&!m.get("variation_id")){const n=[...y].find((e=>e.dataset.productId===m.get("product_id")));if(n){n.querySelectorAll(`form[target="${i}"]`).forEach((e=>{["after_success_behavior","after_success_url","after_success_button_label"].forEach((t=>{0===e.querySelectorAll('input[name="'+t+'"]').length&&e.appendChild(a(t,m.get(t)))}));const t=e.dataset.product;if(t){const n=JSON.parse(t);Object.keys(n).forEach((t=>{0===e.querySelectorAll('input[name="'+t+'"]').length&&e.appendChild(a(t,n[t]))}))}})),t.preventDefault(),s.classList.remove("modal-processing"),$(n),e(n,!1);const c=s.getAttribute("data-product");return u=c?JSON.parse(c):{},p||o(u),void document.getElementById("newspack_modal_checkout").setAttribute("data-order-details",JSON.stringify(u))}}if(!n&&!v())return g(m).then((e=>{window.location.href=e})),void((!m.get("is_variable")||m.get("variation_id"))&&s.querySelectorAll("button[type=submit]:focus").forEach((e=>{e.classList.add("non-modal-checkout-loading");const t=e.innerHTML;e.innerHTML="<span>"+t+"</span>"})));s.classList.remove("modal-processing");const h=m.get("newspack_donate"),k=m.get("newspack_checkout");if(k){const e=s.getAttribute("data-product");u=e?JSON.parse(e):{}}else if(h){const e=m.get("donation_frequency");let t="",n="";for(const n of m.keys())n.indexOf("donation_value_"+e)>=0&&"other"!==m.get(n)&&""!==m.get(n)&&(t=m.get(n));const o=JSON.parse(m.get("frequency_ids"));for(const t in o)t===e&&(n=o[t].toString());u={amount:t,action_type:"donation",currency:m.get("donation_currency"),product_id:n,product_type:"donation",recurrence:e,referrer:m.get("_wp_http_referer")}}if(p||o(u),p=!0,v()){t.preventDefault();let e="",o="0",a="";if(h){const e=m.get("donation_frequency"),t=s.querySelectorAll(`.donation-tier__${e}, .donation-frequency__${e}`);if(t?.length){const n=m.get("donation_tier_index");if(n){const o=JSON.parse(t?.[n].dataset.product);o.hasOwnProperty(`donation_price_summary_${e}`)&&(a=o[`donation_price_summary_${e}`])}else{const n=s.querySelectorAll(`input[name="donation_value_${e}"], input[name="donation_value_${e}_untiered"]`);n?.length&&(n.forEach((e=>{e.checked&&"other"!==e.value&&(o=e.value)})),t.forEach((t=>{const n=JSON.parse(t.dataset.product);if(n.hasOwnProperty(`donation_price_summary_${e}`)){const t=n[`donation_price_summary_${e}`];new RegExp(`(?<=\\D)${o}(?=\\D)`).test(t)&&(a=t)}if("0"===o&&a){let t=m.get(`donation_value_${e}_other`);t||(t=m.get(`donation_value_${e}_untiered`)),t&&(a=a.replace("0",t))}})))}}}else if(k){const e=s.querySelector('input[name="product_price_summary"]');e&&(a=e.value)}a&&(e=`<div class="order-details-summary ${r}__box ${r}__box--text-center"><p><strong>${a}</strong></p></div>`);const i=g(m);i.then((e=>{window.newspackReaderActivation?.setPendingCheckout?.(e)})),window.newspackReaderActivation.openAuthModal({title:newspackBlocksModal.labels.auth_modal_title,onSuccess:(e,t)=>{i.then((e=>{if(t?.registered&&n&&(e+=`&${newspackBlocksModal.checkout_registration_flag}=1`),n){const t=S(e);M(t)}else g(m).then(window.location.href=e)})).catch((e=>{console.warn("Unable to generate cart:",e),q()}))},onError:()=>{q()},onDismiss:()=>{c(u),p=!1,document.getElementById("newspack_modal_checkout").removeAttribute("data-order-details")},skipSuccess:!0,skipNewslettersSignup:!0,labels:{signin:{title:newspackBlocksModal.labels.signin_modal_title},register:{title:newspackBlocksModal.labels.register_modal_title}},content:e,trigger:t.submitter,closeOnSuccess:n})}else A(),document.getElementById("newspack_modal_checkout").setAttribute("data-order-details",JSON.stringify(u))},S=e=>{const t=document.createElement("form");t.method="POST",t.action=e,t.target=i,t.style.display="none";const n=document.createElement("button");return n.setAttribute("type","submit"),t.appendChild(n),document.body.appendChild(t),t.addEventListener("submit",b),t},E=new ResizeObserver((e=>{if(!e||!e.length)return;if(k.scrollIntoView({behavior:"smooth",block:"start"}),!k.contentDocument)return;const t=e[0].contentRect;if(t){const e=t.top+t.bottom;if(0===e)return void(k.style.visibility="hidden");n.style.height=e+"px",k.style.height=e+"px"}})),q=()=>{const e=k?.contentDocument?.querySelector(`#${s}`),o=e?.querySelector('input[name="after_success_url"]'),a=e?.querySelector('input[name="after_success_behavior"]'),r=document?.querySelector(".newspack-newsletters-signup-modal");e?.checkoutComplete||(()=>{const e=new FormData;newspackBlocksModal.has_unsupported_payment_gateway||e.append("modal_checkout","1"),e.append("action","abandon_modal_checkout"),e.append("_wpnonce",t.checkout_nonce),t.checkout_nonce=null,fetch(newspackBlocksModal.ajax_url,{method:"POST",body:e})})();const i=!(k.contentDocument&&o&&a&&e?.checkoutComplete);if((i||r)&&(y.style.display="flex",k&&n.contains(k)&&(k._ready=!1,k.src="about:blank",k.style.height=h,k.style.visibility="hidden",n.style.height=h,n.removeChild(k)),E&&E.disconnect(),document.querySelectorAll(`.${l}-container`).forEach((e=>_(e))),f&&f.focus()),e?.checkoutComplete){const e=()=>{if(o&&a){const e=o.getAttribute("value"),t=a.getAttribute("value");"custom"===t?window.location.href=e:"referrer"===t&&window.history.back()}window?.newspackReaderActivation?.setPendingCheckout?.(),p=!1};window?.newspackReaderActivation?.openNewslettersSignupModal?window.newspackReaderActivation.openNewslettersSignupModal({onSuccess:e,onError:e,closeOnSuccess:i}):e(),i&&(B(),L(newspackBlocksModal.labels.checkout_modal_title))}else window?.newspackReaderActivation?.setPendingCheckout?.(),c(),p=!1,document.getElementById("newspack_modal_checkout").removeAttribute("data-order-details")},A=()=>{y.style.display="flex",$(t),n.appendChild(k),t.addEventListener("click",(e=>{e.target===t&&q()})),e(t,k),function(e){k._readyTimer&&clearTimeout(k._readyTimer);let t=!1;function n(){t||(t=!0,clearTimeout(k._readyTimer),e.call(this))}function o(){"complete"===this.readyState&&n.call(this)}!function e(){if(k._ready)return void clearTimeout(k._readyTimer);const t=k.contentDocument||k.contentWindow?.document;t&&0!==t.URL.indexOf("about:")?"complete"===t?.readyState?n.call(t):(t.addEventListener("DOMContentLoaded",n),t.addEventListener("readystatechange",o)):k._readyTimer=setTimeout(e,10)}()}(w)},$=e=>{window.newspackReaderActivation?.overlays&&(e.overlayId=window.newspackReaderActivation?.overlays.add()),e.setAttribute("data-state","open"),document.body.style.overflow="hidden"},L=e=>{const n=t.querySelector(`.${l}__header h2`);n&&(n.innerText=e)},B=(e="default")=>{const n=t.querySelector(`.${l}`);n&&("small"===e?n.classList.add(`${l}--small`):n.classList.remove(`${l}--small`))};window.newspackCloseModalCheckout=q,t.querySelectorAll(`.${l}__close`).forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),q()}))})),document.querySelectorAll(".newspack-blocks__modal-variation").forEach((e=>{e.addEventListener("click",(t=>{t.target===e&&q()})),e.querySelectorAll(`.${l}__close`).forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),q()}))}))})),document.addEventListener("keydown",(function(e){"Escape"===e.key&&q()})),document.querySelectorAll(".wpbnbd.wpbnbd--platform-wc, .wp-block-newspack-blocks-checkout-button, .newspack-blocks__modal-variation").forEach((e=>{e.querySelectorAll("form").forEach((e=>{newspackBlocksModal.has_unsupported_payment_gateway||e.appendChild(m.cloneNode()),e.target=i,e.addEventListener("submit",b)}))}));const M=e=>{e.requestSubmit(e.querySelector('button[type="submit"]'))};(()=>{const e=new URLSearchParams(window.location.search);if(!e.has("checkout"))return;const t=e.get("type");if("donate"===t){const t=e.get("layout"),n=e.get("frequency"),o=e.get("amount"),c=e.get("other");t&&n&&o&&((e,t,n,o=null)=>{let c;document.querySelectorAll(".wpbnbd.wpbnbd--platform-wc form").forEach((a=>{const r=a.querySelector(`input[name="donation_frequency"][value="${t}"]`);if(r)if("tiered"===e){const e=document.querySelector(`button[data-frequency-slug="${t}"]`);if(!e)return;e.click();const o=a.querySelector(`button[type="submit"][name="donation_value_${t}"][value="${n}"]`);if(!o)return;o.click()}else{const i="untiered"===e?a.querySelector(`input[name="donation_value_${t}_untiered"]`):a.querySelector(`input[name="donation_value_${t}"][value="${n}"]`);if(r&&i){if(r.checked=!0,"untiered"===e)i.value=n;else if("other"===n){i.click();const e=a.querySelector(`input[name="donation_value_${t}_other"]`);e&&o&&(e.value=o)}else i.checked=!0;c=a}}})),c&&M(c)})(t,n,o,c)}else if("checkout_button"===t){const t=e.get("product_id"),n=e.get("variation_id");t&&((e,t=null)=>{let n;if(t&&t!==e){const o=[...document.querySelectorAll(`.${d}`)].find((t=>t.dataset.productId===e));o&&o.querySelectorAll(`form[target="${i}"]`).forEach((e=>{const o=JSON.parse(e.dataset.product);o?.variation_id===Number(t)&&(n=e)}))}else document.querySelectorAll(".wp-block-newspack-blocks-checkout-button").forEach((t=>{const o=t.querySelector("form");if(!o)return;const c=JSON.parse(o.dataset.product);c?.product_id===e&&(n=o)}));n&&M(n)})(t,n)}else{const e=window.newspackReaderActivation?.getPendingCheckout?.();if(e){const t=S(e);M(t)}}window.history.replaceState(null,null,window.location.pathname)})()},"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",m):m())})();