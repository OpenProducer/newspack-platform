/*! For license information please see print-shipping-label-banner.js.LICENSE.txt */
(()=>{var e={65082:(e,t,n)=>{"use strict";var i=n(63588);function s(){}function o(){}o.resetWarningCache=s,e.exports=function(){function e(e,t,n,s,o,r){if(r!==i){var c=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw c.name="Invariant Violation",c}}function t(){return e}e.isRequired=e;var n={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:t,element:e,elementType:e,instanceOf:t,node:e,objectOf:t,oneOf:t,oneOfType:t,shape:t,exact:t,checkPropTypes:o,resetWarningCache:s};return n.PropTypes=n,n}},69596:(e,t,n)=>{e.exports=n(65082)()},63588:e=>{"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},93359:(e,t,n)=>{"use strict";var i=n(99196),s=Symbol.for("react.element"),o=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),r=i.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,c={key:!0,ref:!0,__self:!0,__source:!0};function a(e,t,n){var i,a={},l=null,p=null;for(i in void 0!==n&&(l=""+n),void 0!==t.key&&(l=""+t.key),void 0!==t.ref&&(p=t.ref),t)o.call(t,i)&&!c.hasOwnProperty(i)&&(a[i]=t[i]);if(e&&e.defaultProps)for(i in t=e.defaultProps)void 0===a[i]&&(a[i]=t[i]);return{$$typeof:s,type:e,key:l,ref:p,props:a,_owner:r.current}}t.jsx=a,t.jsxs=a},81514:(e,t,n)=>{"use strict";e.exports=n(93359)},99196:e=>{"use strict";e.exports=window.React}},t={};function n(i){var s=t[i];if(void 0!==s)return s.exports;var o=t[i]={exports:{}};return e[i](o,o.exports,n),o.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var i in t)n.o(t,i)&&!n.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var i={};(()=>{"use strict";n.r(i);const e=window.wp.element,t=window.wc.data;function s(e){return s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},s(e)}function o(e,t,n){return(t=function(e){var t=function(e){if("object"!==s(e)||null===e)return e;var t=e[Symbol.toPrimitive];if(void 0!==t){var n=t.call(e,"string");if("object"!==s(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(e)}(e);return"symbol"===s(t)?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}const r=window.wp.i18n,c=window.wp.components,a=window.wp.compose;var l=n(99196);function p(e){return e.startsWith("{{/")?{type:"componentClose",value:e.replace(/\W/g,"")}:e.endsWith("/}}")?{type:"componentSelfClosing",value:e.replace(/\W/g,"")}:e.startsWith("{{")?{type:"componentOpen",value:e.replace(/\W/g,"")}:{type:"string",value:e}}function d(e,t){let n,i,s=[];for(let o=0;o<e.length;o++){const r=e[o];if("string"!==r.type){if(void 0===t[r.value])throw new Error(`Invalid interpolation, missing component node: \`${r.value}\``);if("object"!=typeof t[r.value])throw new Error(`Invalid interpolation, component node must be a ReactElement or null: \`${r.value}\``);if("componentClose"===r.type)throw new Error(`Missing opening component token: \`${r.value}\``);if("componentOpen"===r.type){n=t[r.value],i=o;break}s.push(t[r.value])}else s.push(r.value)}if(n){const o=function(e,t){const n=t[e];let i=0;for(let s=e+1;s<t.length;s++){const e=t[s];if(e.value===n.value){if("componentOpen"===e.type){i++;continue}if("componentClose"===e.type){if(0===i)return s;i--}}}throw new Error("Missing closing component token `"+n.value+"`")}(i,e),r=d(e.slice(i+1,o),t),c=(0,l.cloneElement)(n,{},r);if(s.push(c),o<e.length-1){const n=d(e.slice(o+1),t);s=s.concat(n)}}return s=s.filter(Boolean),0===s.length?null:1===s.length?s[0]:(0,l.createElement)(l.Fragment,null,...s)}function m(e){const{mixedString:t,components:n,throwErrors:i}=e;if(!n)return t;if("object"!=typeof n){if(i)throw new Error(`Interpolation Error: unable to process \`${t}\` because components is not an object`);return t}const s=function(e){return e.split(/(\{\{\/?\s*\w+\s*\/?\}\})/g).map(p)}(t);try{return d(s,n)}catch(e){if(i)throw new Error(`Interpolation Error: unable to process \`${t}\` because of error \`${e.message}\``);return t}}var u=n(69596),h=n.n(u);const w=window.wp.data,g=window.wc.tracks,b=window.wc.wcSettings,_=window.wc.components;var y=n(81514);class f extends e.Component{constructor(...e){super(...e),o(this,"setDismissed",(e=>{this.props.updateOptions({woocommerce_shipping_dismissed_timestamp:e})})),o(this,"hideBanner",(()=>{document.getElementById("woocommerce-admin-print-label").style.display="none"})),o(this,"remindMeLaterClicked",(()=>{const{onCloseAll:e,trackElementClicked:t}=this.props;this.setDismissed(Date.now()),e(),this.hideBanner(),t("shipping_banner_dismiss_modal_remind_me_later")})),o(this,"closeForeverClicked",(()=>{const{onCloseAll:e,trackElementClicked:t}=this.props;this.setDismissed(-1),e(),this.hideBanner(),t("shipping_banner_dismiss_modal_close_forever")}))}render(){const{onClose:e,visible:t}=this.props;return t?(0,y.jsxs)(c.Modal,{title:(0,r.__)("Are you sure?","woocommerce"),onRequestClose:e,className:"wc-admin-shipping-banner__dismiss-modal",children:[(0,y.jsx)("p",{className:"wc-admin-shipping-banner__dismiss-modal-help-text",children:(0,r.__)("With WooCommerce Shipping you can Print shipping labels from your WooCommerce dashboard at the lowest USPS rates.","woocommerce")}),(0,y.jsxs)("div",{className:"wc-admin-shipping-banner__dismiss-modal-actions",children:[(0,y.jsx)(c.Button,{isSecondary:!0,onClick:this.remindMeLaterClicked,children:(0,r.__)("Remind me later","woocommerce")}),(0,y.jsx)(c.Button,{isPrimary:!0,onClick:this.closeForeverClicked,children:(0,r.__)("I don't need this","woocommerce")})]})]}):null}}const v=(0,a.compose)((0,w.withDispatch)((e=>{const{updateOptions:n}=e(t.optionsStore);return{updateOptions:n}})))(f),S=(0,e.forwardRef)((function({icon:t,size:n=24,...i},s){return(0,e.cloneElement)(t,{width:n,height:n,...i,ref:s})})),k=window.wp.primitives,C=(0,y.jsx)(k.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"-2 -2 24 24",children:(0,y.jsx)(k.Path,{d:"M10 2c4.42 0 8 3.58 8 8s-3.58 8-8 8-8-3.58-8-8 3.58-8 8-8zm1.13 9.38l.35-6.46H8.52l.35 6.46h2.26zm-.09 3.36c.24-.23.37-.55.37-.96 0-.42-.12-.74-.36-.97s-.59-.35-1.06-.35-.82.12-1.07.35-.37.55-.37.97c0 .41.13.73.38.96.26.23.61.34 1.06.34s.8-.11 1.05-.34z"})}),E="download",x="install",P="activate",j="setup",B="start",L={[E]:(0,r.__)("download","woocommerce"),[x]:(0,r.__)("install","woocommerce"),[P]:(0,r.__)("activate","woocommerce"),[j]:(0,r.__)("set up","woocommerce"),[B]:(0,r.__)("start","woocommerce")};function I({isSetupError:e,errorReason:t}){return e?(0,y.jsxs)("div",{className:"wc-admin-shipping-banner-install-error",children:[(0,y.jsx)(S,{icon:C,className:"warning-icon"}),(e=>{const t=e in L?L[e]:L[j];return(0,r.sprintf)((0,r.__)("Unable to %s the plugin. Refresh the page and try again.","woocommerce"),t)})(t)]}):null}const O=window.wp.apiFetch;var A=n.n(O);const R=(0,b.getSetting)("wcAssetUrl",""),W="woocommerce-shipping",T="woocommerce-services";class M extends e.Component{constructor(e){super(e),o(this,"isSetupError",(()=>this.state.wcsSetupError)),o(this,"closeDismissModal",(()=>{this.setState({isDismissModalOpen:!1}),this.trackElementClicked("shipping_banner_dismiss_modal_close_button")})),o(this,"openDismissModal",(()=>{this.setState({isDismissModalOpen:!0}),this.trackElementClicked("shipping_banner_dimiss")})),o(this,"hideBanner",(()=>{this.setState({showShippingBanner:!1})})),o(this,"createShippingLabelClicked",(()=>{const{activePlugins:e}=this.props;this.setState({isShippingLabelButtonBusy:!0}),this.trackElementClicked("shipping_banner_create_label"),e.includes(W)?this.acceptTosAndGetWCSAssets():this.installAndActivatePlugins(W)})),o(this,"woocommerceServiceLinkClicked",(()=>{this.trackElementClicked("shipping_banner_woocommerce_service_link")})),o(this,"trackBannerEvent",((e,t={})=>{const{activePlugins:n,isJetpackConnected:i}=this.props;(0,g.recordEvent)(e,{banner_name:"wcadmin_install_wcs_prompt",jetpack_installed:n.includes("jetpack"),jetpack_connected:i,wcs_installed:n.includes(W),...t})})),o(this,"trackImpression",(()=>{this.trackBannerEvent("banner_impression")})),o(this,"trackElementClicked",(e=>{this.trackBannerEvent("banner_element_clicked",{element:e})})),o(this,"acceptTosAndGetWCSAssets",(()=>A()({path:"/wcshipping/v1/tos",method:"POST",data:{accepted:!0}}).then((()=>function(e){const t=`wcshipping/v1/config/label-purchase/${e}`;return A()({path:t,method:"GET"})}(this.props.orderId))).then((e=>(window.WCShipping_Config=e.config,e))).then((()=>A()({path:"/wcshipping/v1/assets",method:"GET"}))).then((e=>this.loadWcsAssets(e))).catch((()=>{this.setState({wcsSetupError:!0})})))),this.state={showShippingBanner:!0,isDismissModalOpen:!1,setupErrorReason:j,wcsAssetsLoaded:!1,wcsAssetsLoading:!1,wcsSetupError:!1,isShippingLabelButtonBusy:!1,isWcsModalOpen:!1}}componentDidMount(){const{showShippingBanner:e}=this.state;e&&this.trackImpression()}async installAndActivatePlugins(e){const{installPlugins:t,activatePlugins:n,isRequesting:i,activePlugins:s,isWcstCompatible:o,isIncompatibleWCShippingInstalled:r}=this.props;if(i)return!1;!0===(await t([e])).success?!0===(await n([e])).success?r?window.location.reload(!0):!s.includes(W)&&o?this.acceptTosAndGetWCSAssets():this.setState({showShippingBanner:!1}):this.setState({setupErrorReason:P,wcsSetupError:!0}):this.setState({setupErrorReason:x,wcsSetupError:!0})}generateMetaBoxHtml(e,t,n){return`\n<div id="${e}" class="postbox">\n\t<div class="postbox-header">\n\t\t<h2 class="hndle"><span>${t}</span></h2>\n\t\t<div class="handle-actions">\n\t\t\t<button type="button" class="handlediv" aria-expanded="true">\n\t\t\t\t<span class="screen-reader-text">${(0,r.__)("Toggle panel:","woocommerce")} ${t}</span>\n\t\t\t\t<span class="toggle-indicator" aria-hidden="true"></span>\n\t\t\t</button>\n\t\t</div>\n\t</div>\n\t<div class="inside">\n\t\t<div class="wcc-root woocommerce woocommerce-shipping-shipping-label" id="woocommerce-shipping-shipping-label-${n.context}"></div>\n\t</div>\n</div>\n`}loadWcsAssets({assets:e}){if(this.state.wcsAssetsLoaded||this.state.wcsAssetsLoading)return void this.openWcsModal();this.setState({wcsAssetsLoading:!0});const t="woocommerce-order-label",n="woocommerce-order-shipment-tracking",i=e.wcshipping_create_label_script,s=e.wcshipping_create_label_style,o=e.wcshipping_shipment_tracking_script,c=e.wcshipping_shipment_tracking_style,{activePlugins:a}=this.props;document.getElementById(t)?.remove();const l=this.generateMetaBoxHtml(t,(0,r.__)("Shipping Label","woocommerce"),{context:"shipping_label"});document.getElementById("woocommerce-order-data").insertAdjacentHTML("beforebegin",l),document.getElementById(n)?.remove();const p=this.generateMetaBoxHtml(n,(0,r.__)("Shipment Tracking","woocommerce"),{context:"shipment_tracking"});document.getElementById("woocommerce-order-actions").insertAdjacentHTML("afterend",p),window.jQuery&&(window.jQuery("#normal-sortables").sortable("refresh"),window.jQuery("#side-sortables").sortable("refresh"),window.jQuery("#woocommerce-order-label").hide()),document.querySelectorAll('script[src*="/woocommerce-services/"]').forEach((e=>e.remove?.())),document.querySelectorAll('link[href*="/woocommerce-services/"]').forEach((e=>e.remove?.())),Promise.all([new Promise(((e,t)=>{const n=document.createElement("script");n.src=i,n.async=!0,n.onload=e,n.onerror=t,document.body.appendChild(n)})),new Promise(((e,t)=>{const n=document.createElement("script");n.src=o,n.async=!0,n.onload=e,n.onerror=t,document.body.appendChild(n)})),new Promise(((e,t)=>{if(""!==s){const n=document.createElement("link");n.rel="stylesheet",n.type="text/css",n.href=s,n.media="all",n.onload=e,n.onerror=t,n.id="wcshipping-injected-styles",document.head.appendChild(n)}else e()})),new Promise(((e,t)=>{if(""!==c){const n=document.createElement("link");n.rel="stylesheet",n.type="text/css",n.href=c,n.media="all",n.onload=e,n.onerror=t,n.id="wcshipping-injected-styles",document.head.appendChild(n)}else e()}))]).then((()=>{this.setState({wcsAssetsLoaded:!0,wcsAssetsLoading:!1,isShippingLabelButtonBusy:!1}),window.jQuery&&window.jQuery("#woocommerce-order-label").show(),document.getElementById("woocommerce-admin-print-label").style.display="none",a.includes(T)||this.openWcsModal()}))}openWcsModal(){const e="#woocommerce-shipping-shipping-label-shipping_label button";if(window.MutationObserver){var t,n;new window.MutationObserver(((t,n)=>{const i=document.querySelector(e);i&&(i.click(),n.disconnect())})).observe(null!==(t=null!==(n=document.getElementById("woocommerce-shipping-shipping-label-shipping_label"))&&void 0!==n?n:document.getElementById("wpbody-content"))&&void 0!==t?t:document.body,{childList:!0,subtree:!0})}else{const t=setInterval((()=>{const n=document.querySelector(e);n&&(n.click(),clearInterval(t))}),300)}}render(){const{isDismissModalOpen:e,showShippingBanner:t,isShippingLabelButtonBusy:n}=this.state,{isWcstCompatible:i}=this.props;if(!t&&!i)return document.getElementById("woocommerce-admin-print-label").classList.add("error"),(0,y.jsx)("p",{children:(0,y.jsx)("strong",{children:m({mixedString:(0,r.__)("Please {{pluginPageLink}}update{{/pluginPageLink}} the WooCommerce Shipping & Tax plugin to the latest version to ensure compatibility with WooCommerce Shipping.","woocommerce"),components:{pluginPageLink:(0,y.jsx)(_.Link,{href:(0,b.getAdminLink)("plugins.php"),target:"_blank",type:"wp-admin"})}})})});if(!t)return null;const{actionButtonLabel:s,headline:o}=this.props;return(0,y.jsxs)("div",{children:[(0,y.jsxs)("div",{className:"wc-admin-shipping-banner-container",children:[(0,y.jsx)("img",{className:"wc-admin-shipping-banner-illustration",src:R+"images/shippingillustration.svg",alt:(0,r.__)("Shipping ","woocommerce")}),(0,y.jsxs)("div",{className:"wc-admin-shipping-banner-blob",children:[(0,y.jsx)("h3",{children:o}),(0,y.jsx)("p",{children:m({mixedString:(0,r.sprintf)((0,r.__)('By clicking "%s", {{wcsLink}}WooCommerce Shipping{{/wcsLink}} will be installed and you agree to its {{tosLink}}Terms of Service{{/tosLink}}.',"woocommerce"),s),components:{tosLink:(0,y.jsx)(c.ExternalLink,{href:"https://wordpress.com/tos",target:"_blank",type:"external"}),wcsLink:(0,y.jsx)(c.ExternalLink,{href:"https://woocommerce.com/products/shipping/?utm_medium=product",target:"_blank",type:"external",onClick:this.woocommerceServiceLinkClicked})}})}),(0,y.jsx)(I,{isSetupError:this.isSetupError(),errorReason:this.state.setupErrorReason})]}),(0,y.jsx)(c.Button,{disabled:n,isPrimary:!0,isBusy:n,onClick:this.createShippingLabelClicked,children:s}),(0,y.jsx)("button",{onClick:this.openDismissModal,type:"button",className:"notice-dismiss",disabled:this.state.isShippingLabelButtonBusy,children:(0,y.jsx)("span",{className:"screen-reader-text",children:(0,r.__)("Close Print Label Banner.","woocommerce")})})]}),(0,y.jsx)(v,{visible:e,onClose:this.closeDismissModal,onCloseAll:this.hideBanner,trackElementClicked:this.trackElementClicked})]})}}M.propTypes={isJetpackConnected:h().bool.isRequired,activePlugins:h().array.isRequired,activatePlugins:h().func.isRequired,installPlugins:h().func.isRequired,isRequesting:h().bool.isRequired,orderId:h().number.isRequired,isWcstCompatible:h().bool.isRequired};const D=(0,a.compose)((0,w.withSelect)((e=>{const{isPluginsRequesting:n,isJetpackConnected:i,getActivePlugins:s}=e(t.pluginsStore),o=n("activatePlugins")||n("installPlugins"),c=s(),a=c.includes(T)?(0,r.__)("Install WooCommerce Shipping","woocommerce"):(0,r.__)("Create shipping label","woocommerce"),l=c.includes(T)?(0,r.__)("Print discounted shipping labels with a click, now with the dedicated plugin!","woocommerce"):(0,r.__)("Print discounted shipping labels with a click.","woocommerce");return{isRequesting:o,isJetpackConnected:i(),activePlugins:c,actionButtonLabel:a,headline:l,orderId:parseInt(window.wcShippingCoreData.order_id,10),isWcstCompatible:[1,"1"].includes(window.wcShippingCoreData.is_wcst_compatible),isIncompatibleWCShippingInstalled:[1,"1"].includes(window.wcShippingCoreData.is_incompatible_wcshipping_installed)}})),(0,w.withDispatch)((e=>{const{activatePlugins:n,installPlugins:i}=e(t.pluginsStore);return{activatePlugins:n,installPlugins:i}})))(M),q=["wcAdminSettings","preloadSettings"],N=(0,b.getSetting)("admin",{}),$=Object.keys(N).reduce(((e,t)=>(q.includes(t)||(e[t]=N[t]),e)),{}),U={onboarding:{profile:"Deprecated: wcSettings.admin.onboarding.profile is deprecated. It is planned to be released in WooCommerce 10.0.0. Please use `getProfileItems` from the onboarding store. See https://github.com/woocommerce/woocommerce/tree/trunk/packages/js/data/src/onboarding for more information.",euCountries:"Deprecated: wcSettings.admin.onboarding.euCountries is deprecated. Please use `/wc/v3/data/continents/eu` from the REST API. See https://woocommerce.github.io/woocommerce-rest-api-docs/#list-all-continents for more information.",localInfo:'Deprecated: wcSettings.admin.onboarding.localInfo is deprecated. Please use `include WC()->plugin_path() . "/i18n/locale-info.php"` instead.',currencySymbols:'"Deprecated: wcSettings.admin.onboarding.currencySymbols is deprecated. Please use get_woocommerce_currency_symbols() function instead.'}};function H(e,t=!1,n=e=>e,i=U){if(q.includes(e))throw new Error((0,r.__)("Mutable settings should be accessed via data store.","woocommerce"));return n($.hasOwnProperty(e)?$[e]:t,t)}(0,b.getSetting)("adminUrl"),(0,b.getSetting)("countries"),(0,b.getSetting)("currency"),(0,b.getSetting)("locale"),(0,b.getSetting)("siteTitle"),(0,b.getSetting)("wcAssetUrl"),H("orderStatuses");const F=document.getElementById("wc-admin-shipping-banner-root"),G=F.dataset.args&&JSON.parse(F.dataset.args)||{},Q=(0,t.withPluginsHydration)({...H("plugins"),jetpackStatus:H("dataEndpoints",{}).jetpackStatus})(D);(0,e.createRoot)(F).render((0,y.jsx)(Q,{itemsCount:G.items}))})(),(window.wc=window.wc||{}).printShippingLabelBanner=i})();