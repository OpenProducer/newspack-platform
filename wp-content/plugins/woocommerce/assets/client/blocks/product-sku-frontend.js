(self.webpackChunkwebpackWcBlocksFrontendJsonp=self.webpackChunkwebpackWcBlocksFrontendJsonp||[]).push([[724],{3975:(e,t,o)=>{"use strict";o.r(t),o.d(t,{default:()=>d});var s=o(1609),l=o(851),n=o(2796),r=o(1616),a=o(3249),c=o(4715),i=o(7723);o(7663);const u=({setAttributes:e,parentClassName:t,sku:o,className:n,style:r,prefix:a,suffix:u})=>(0,s.createElement)("div",{className:(0,l.A)(n,"wp-block-post-terms",{[`${t}__product-sku`]:t}),style:r},(0,s.createElement)(c.RichText,{className:"wc-block-components-product-sku__prefix",tagName:"span",placeholder:(0,i.__)("Prefix","woocommerce"),value:a,onChange:t=>e({prefix:t})}),(0,s.createElement)("span",null," ",o),(0,s.createElement)(c.RichText,{className:"wc-block-components-product-sku__suffix",tagName:"span",placeholder:" "+(0,i.__)("Suffix","woocommerce"),value:u,onChange:t=>e({suffix:t})})),d=(0,r.withProductDataContext)((e=>{const{className:t}=e,o=(0,a.p)(e),{parentClassName:r}=(0,n.useInnerBlockLayoutContext)(),{product:c}=(0,n.useProductDataContext)(),d=c.sku;return e.isDescendentOfSingleProductTemplate?(0,s.createElement)(u,{setAttributes:e.setAttributes,parentClassName:r,className:t,sku:(0,i.__)("Product SKU","woocommerce"),prefix:e.prefix,suffix:e.suffix}):d?(0,s.createElement)(u,{setAttributes:e.setAttributes,className:t,parentClassName:r,sku:d,prefix:e.prefix,suffix:e.suffix,...e.isDescendantOfAllProducts&&{className:(0,l.A)(t,"wc-block-components-product-sku wp-block-woocommerce-product-sku",o.className),style:{...o.style}}}):null}))},3249:(e,t,o)=>{"use strict";o.d(t,{p:()=>i});var s=o(851),l=o(3993),n=o(1194),r=o(9786);function a(e={}){const t={};return(0,r.getCSSRules)(e,{selector:""}).forEach((e=>{t[e.key]=e.value})),t}function c(e,t){return e&&t?`has-${(0,n.c)(t)}-${e}`:""}const i=e=>{const t=(e=>{const t=(0,l.isObject)(e)?e:{style:{}};let o=t.style;return(0,l.isString)(o)&&(o=JSON.parse(o)||{}),(0,l.isObject)(o)||(o={}),{...t,style:o}})(e),o=function(e){var t,o,n,r,i,u,d;const{backgroundColor:f,textColor:m,gradient:p,style:y}=e,k=c("background-color",f),v=c("color",m),b=function(e){if(e)return`has-${e}-gradient-background`}(p),x=b||(null==y||null===(t=y.color)||void 0===t?void 0:t.gradient);return{className:(0,s.A)(v,b,{[k]:!x&&!!k,"has-text-color":m||(null==y||null===(o=y.color)||void 0===o?void 0:o.text),"has-background":f||(null==y||null===(n=y.color)||void 0===n?void 0:n.background)||p||(null==y||null===(r=y.color)||void 0===r?void 0:r.gradient),"has-link-color":(0,l.isObject)(null==y||null===(i=y.elements)||void 0===i?void 0:i.link)?null==y||null===(u=y.elements)||void 0===u||null===(d=u.link)||void 0===d?void 0:d.color:void 0}),style:a({color:(null==y?void 0:y.color)||{}})}}(t),n=function(e){var t;const o=(null===(t=e.style)||void 0===t?void 0:t.border)||{};return{className:function(e){var t;const{borderColor:o,style:l}=e,n=o?c("border-color",o):"";return(0,s.A)({"has-border-color":!!o||!(null==l||null===(t=l.border)||void 0===t||!t.color),[n]:!!n})}(e),style:a({border:o})}}(t),r=function(e){var t;return{className:void 0,style:a({spacing:(null===(t=e.style)||void 0===t?void 0:t.spacing)||{}})}}(t),i=(e=>{const t=(0,l.isObject)(e.style.typography)?e.style.typography:{},o=(0,l.isString)(t.fontFamily)?t.fontFamily:"";return{className:e.fontFamily?`has-${e.fontFamily}-font-family`:o,style:{fontSize:e.fontSize?`var(--wp--preset--font-size--${e.fontSize})`:t.fontSize,fontStyle:t.fontStyle,fontWeight:t.fontWeight,letterSpacing:t.letterSpacing,lineHeight:t.lineHeight,textDecoration:t.textDecoration,textTransform:t.textTransform}}})(t);return{className:(0,s.A)(i.className,o.className,n.className,r.className),style:{...i.style,...o.style,...n.style,...r.style}}}},7663:()=>{}}]);