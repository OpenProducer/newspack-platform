(self.webpackChunkwebpackWcBlocksMainJsonp=self.webpackChunkwebpackWcBlocksMainJsonp||[]).push([[345],{5916:(t,e,o)=>{"use strict";o.r(e),o.d(e,{Block:()=>h,default:()=>w});var r=o(1609),s=o(7723),c=o(851),n=o(2796),l=o(2150),i=o(1616),u=o(5703),a=o(7143);const d="woocommerce/product-type-template-state",p="SWITCH_PRODUCT_TYPE",y="SET_PRODUCT_TYPES",g="REGISTER_LISTENER",v="UNREGISTER_LISTENER",f=(0,u.getSetting)("productTypes",{});var m;const k=Object.keys(f).map((t=>({slug:t,label:f[t]}))),T={productTypes:{list:k,current:null===(m=k[0])||void 0===m?void 0:m.slug},listeners:[]},b={switchProductType:t=>({type:p,current:t}),setProductTypes:t=>({type:y,productTypes:t}),registerListener:t=>({type:g,listener:t}),unregisterListener:t=>({type:v,listener:t})},S=(0,a.createReduxStore)(d,{reducer:(t=T,e)=>{switch(e.type){case y:return{...t,productTypes:{...t.productTypes,list:e.productTypes||[]}};case p:return{...t,productTypes:{...t.productTypes,current:e.current}};case g:return{...t,listeners:[...t.listeners,e.listener||""]};case v:return{...t,listeners:t.listeners.filter((t=>t!==e.listener))};default:return t}},actions:b,selectors:{getProductTypes:t=>t.productTypes.list,getCurrentProductType:t=>t.productTypes.list.find((e=>e.slug===t.productTypes.current)),getRegisteredListeners:t=>t.listeners}});(0,a.select)(d)||(0,a.register)(S),o(9644);const h=t=>{const{className:e}=t,o=(0,l.p)(t),{parentClassName:i}=(0,n.useInnerBlockLayoutContext)(),{product:d}=(0,n.useProductDataContext)(),{text:p,class:y}=d.stock_availability,{selectedProductType:g}=(0,a.useSelect)((t=>{const{getCurrentProductType:e}=t(S);return{selectedProductType:e()}}),[]);if(!((t,e,o)=>{if(0!==t.id)return""!==e;const r=(0,u.getSetting)("productTypesWithoutStockIndicator",["external","grouped","variable"]),s=o||(null==t?void 0:t.type);return!r.includes(s)})(d,p,null==g?void 0:g.slug))return null;const v=0===d.id,f=d.low_stock_remaining;return(0,r.createElement)("div",{className:(0,c.A)(e,{[`${i}__stock-indicator`]:i,[`wc-block-components-product-stock-indicator--${y}`]:y,"wc-block-components-product-stock-indicator--in-stock":v,"wc-block-components-product-stock-indicator--low-stock":!!f,...t.isDescendantOfAllProducts&&{[o.className]:o.className,"wc-block-components-product-stock-indicator wp-block-woocommerce-product-stock-indicator":!0}}),...t.isDescendantOfAllProducts&&{style:o.style}},v?(0,s.__)("In stock","woocommerce"):p)},w=t=>{const{product:e}=(0,n.useProductDataContext)();return 0===e.id?(0,r.createElement)(h,{...t}):(0,i.withProductDataContext)(h)(t)}},2150:(t,e,o)=>{"use strict";o.d(e,{p:()=>l});var r=o(851),s=o(3993),c=o(3924),n=o(104);const l=t=>{const e=(t=>{const e=(0,s.isObject)(t)?t:{style:{}};let o=e.style;return(0,s.isString)(o)&&(o=JSON.parse(o)||{}),(0,s.isObject)(o)||(o={}),{...e,style:o}})(t),o=(0,n.BK)(e),l=(0,n.aR)(e),i=(0,n.fo)(e),u=(0,c.x)(e);return{className:(0,r.A)(u.className,o.className,l.className,i.className),style:{...u.style,...o.style,...l.style,...i.style}}}},3924:(t,e,o)=>{"use strict";o.d(e,{x:()=>s});var r=o(3993);const s=t=>{const e=(0,r.isObject)(t.style.typography)?t.style.typography:{},o=(0,r.isString)(e.fontFamily)?e.fontFamily:"";return{className:t.fontFamily?`has-${t.fontFamily}-font-family`:o,style:{fontSize:t.fontSize?`var(--wp--preset--font-size--${t.fontSize})`:e.fontSize,fontStyle:e.fontStyle,fontWeight:e.fontWeight,letterSpacing:e.letterSpacing,lineHeight:e.lineHeight,textDecoration:e.textDecoration,textTransform:e.textTransform}}}},104:(t,e,o)=>{"use strict";o.d(e,{BK:()=>u,aR:()=>a,fo:()=>d});var r=o(851),s=o(1194),c=o(9786),n=o(3993);function l(t={}){const e={};return(0,c.getCSSRules)(t,{selector:""}).forEach((t=>{e[t.key]=t.value})),e}function i(t,e){return t&&e?`has-${(0,s.c)(e)}-${t}`:""}function u(t){var e,o,s,c,u,a;const{backgroundColor:d,textColor:p,gradient:y,style:g}=t,v=i("background-color",d),f=i("color",p),m=function(t){if(t)return`has-${t}-gradient-background`}(y),k=m||(null==g||null===(e=g.color)||void 0===e?void 0:e.gradient);return{className:(0,r.A)(f,m,{[v]:!k&&!!v,"has-text-color":p||(null==g||null===(o=g.color)||void 0===o?void 0:o.text),"has-background":d||(null==g||null===(s=g.color)||void 0===s?void 0:s.background)||y||(null==g||null===(c=g.color)||void 0===c?void 0:c.gradient),"has-link-color":(0,n.isObject)(null==g||null===(u=g.elements)||void 0===u?void 0:u.link)?null==g||null===(a=g.elements)||void 0===a||null===(a=a.link)||void 0===a?void 0:a.color:void 0}),style:l({color:(null==g?void 0:g.color)||{}})}}function a(t){var e;const o=(null===(e=t.style)||void 0===e?void 0:e.border)||{};return{className:function(t){var e;const{borderColor:o,style:s}=t,c=o?i("border-color",o):"";return(0,r.A)({"has-border-color":!!o||!(null==s||null===(e=s.border)||void 0===e||!e.color),[c]:!!c})}(t),style:l({border:o})}}function d(t){var e;return{className:void 0,style:l({spacing:(null===(e=t.style)||void 0===e?void 0:e.spacing)||{}})}}},9644:()=>{}}]);