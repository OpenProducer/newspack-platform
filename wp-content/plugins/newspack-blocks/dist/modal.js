(()=>{"use strict";let e;function t(){const t=document.querySelector('iframe[name="newspack_modal_checkout"]');t&&(t.src="about:blank"),document.body.classList.remove("newspack-modal-checkout-open"),e&&e.disconnect(),Array.from(document.querySelectorAll(".newspack-blocks-modal")).forEach((e=>{e.style.display="none",e.overlayId&&window.newspackReaderActivation?.overlays&&window.newspackReaderActivation?.overlays.remove(e.overlayId)}))}window.newspackCloseModalCheckout=t;const o=".newspack-blocks-modal";var c;c=()=>{const c=document.querySelector(".newspack-blocks-checkout-modal");if(!c)return;const n=document.querySelector(`${o}__spinner`),a="newspack_modal_checkout",r=document.createElement("input");r.type="hidden",r.name="modal_checkout",r.value="1";const l=c.querySelector(`${o}__content`),d=l.clientHeight+"px",s=document.createElement("iframe");s.name=a,l.appendChild(s),c.addEventListener("click",(e=>{e.target===c&&t()})),c.querySelectorAll(`${o}__close`).forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),l.style.height=d,n.style.display="flex",t()}))}));const i=document.querySelectorAll(".newspack-blocks-variation-modal");i.forEach((e=>{e.addEventListener("click",(o=>{o.target===e&&t()})),e.querySelectorAll(".newspack-blocks-modal__close").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),t()}))}))})),document.querySelectorAll(".wpbnbd.wpbnbd--platform-wc,.wp-block-newspack-blocks-checkout-button,.newspack-blocks-variation-modal").forEach((t=>{t.querySelectorAll("form").forEach((t=>{t.appendChild(r.cloneNode()),t.target=a;const o=t.querySelector('input[name="after_success_url"]'),d=t.querySelector('input[name="after_success_behavior"]');d&&o&&"referrer"===d.getAttribute("value")&&o.setAttribute("value",document.referrer||window.location.href),t.addEventListener("submit",(o=>{const a=new FormData(t);if(i.forEach((e=>e.style.display="none")),a.get("is_variable")&&!a.get("variation_id")){const e=[...i].find((e=>e.dataset.productId===a.get("product_id")));if(e)return e.querySelectorAll('form[target="newspack_modal_checkout"]').forEach((e=>{["after_success_behavior","after_success_url","after_success_button_label"].forEach((t=>{const o=document.createElement("input");o.type="hidden",o.name=t,o.value=a.get(t),e.appendChild(o)}))})),o.preventDefault(),document.body.classList.add("newspack-modal-checkout-open"),void(e.style.display="block")}n.style.display="flex",c.style.display="block",document.body.classList.add("newspack-modal-checkout-open"),window.newspackReaderActivation?.overlays&&(c.overlayId=window.newspackReaderActivation?.overlays.add()),e=new ResizeObserver((e=>{if(!e||!e.length)return;const t=e[0].contentRect;t&&(l.style.height=t.top+t.bottom+"px",n.style.display="none")})),s.addEventListener("load",(()=>{const t=s.contentWindow.location;if(window.newspackReaderActivation&&t.href.indexOf("order-received")>-1){const e=window.newspackReaderActivation,o=new Proxy(new URLSearchParams(t.search),{get:(e,t)=>e.get(t)});o.email&&(e.setReaderEmail(o.email),e.setAuthenticated(!0))}const o=s.contentDocument.querySelector("#newspack_modal_checkout");o&&e.observe(o),[...s.contentDocument.querySelectorAll(".modal-continue, .edit-billing-link")].forEach((e=>{e.addEventListener("click",(()=>n.style.display="flex"))}));const c=s.contentDocument.querySelector("form.checkout");c&&[...c.querySelectorAll(".woocommerce-billing-fields input")].forEach((e=>{e.addEventListener("keyup",(e=>{"Enter"===e.key&&(n.style.display="flex",c.submit())}))}))}))}))}))}))},"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",c):c())})();