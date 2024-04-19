(()=>{"use strict";var e,t={4845:(e,t,n)=>{n.r(t),n(5674);var a=n(9307),s=n(5736),l=n(1732),c=n(9674),r=n(6989),i=n.n(r);const o=s.__,p={jetpack:{pluginSlug:"jetpack",editLink:"admin.php?page=jetpack#/settings",name:"Jetpack",fetchStatus:()=>i()({path:"/newspack/v1/plugins/jetpack"}).then((e=>({jetpack:{status:e.Configured?e.Status:"inactive"}})))},"google-site-kit":{pluginSlug:"google-site-kit",editLink:"admin.php?page=googlesitekit-splash",name:o("Site Kit by Google","newspack-plugin"),fetchStatus:()=>i()({path:"/newspack/v1/plugins/google-site-kit"}).then((e=>({"google-site-kit":{status:e.Configured?e.Status:"inactive"}})))}},u=e=>e.pluginSlug?(0,a.createElement)(l.OQ,{plugin:e.pluginSlug,editLink:e.editLink,compact:!0,isLink:!0},o("Connect","newspack-plugin")):e.url?(0,a.createElement)(l.zx,{isLink:!0,href:e.url,target:"_blank"},o("Connect","newspack-plugin")):"unavailable_site_id"===e.error?.code?(0,a.createElement)("span",{className:"i newspack-error"},o("Jetpack connection required","newspack-plugin")):void 0,d=({setError:e})=>{const[t,n]=l.PT.useObjectState(p),s=Object.values(t);return(0,a.useEffect)((()=>{s.forEach((async t=>{const a=await t.fetchStatus().catch(e);n(a)}))}),[]),(0,a.createElement)(a.Fragment,null,s.map((e=>{const t="inactive"===e.status,n=!e.status;return(0,a.createElement)(l.fM,{key:e.name,title:e.name,description:`${o("Status:","newspack-plugin")} ${n?o("Loading…","newspack-plugin"):t?"google-site-kit"===e.pluginSlug?o("Not connected for this user","newspack-plugin"):o("Not connected","newspack-plugin"):o("Connected","newspack-plugin")}`,actionText:t?u(e):null,checkbox:t||n?"unchecked":"checked",badge:e.badge,indent:e.indent,isMedium:!0})})))};var m=n(8635),g=n(9630),h=n(5609);const w=s.__,k=({setError:e})=>{const[t,n]=(0,a.useState)({}),[c,r]=(0,a.useState)(!1),[o,p]=(0,a.useState)(),[u,d]=(0,a.useState)(!1),m=(0,a.useRef)(null),k=Boolean(t&&t.username),E=t=>e(t.message||w("Something went wrong.","newspack-plugin")),b=()=>{r(!1),p()};(0,a.useEffect)((()=>{d(!0),i()({path:"/newspack/v1/oauth/mailchimp"}).then((e=>{n(e)})).catch(E).finally((()=>d(!1)))}),[]),(0,a.useEffect)((()=>{c&&m.current.querySelector("input").focus()}),[c]);const f=()=>{e(),d(!0),i()({path:"/newspack/v1/oauth/mailchimp",method:"POST",data:{api_key:o}}).then((e=>{n(e)})).catch((t=>{e(t.message||w("Something went wrong during verification of your Mailchimp API key.","newspack-plugin"))})).finally((()=>{d(!1),b()}))};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.fM,{title:"Mailchimp",description:`${w("Status:","newspack-plugin")} ${u?w("Loading…","newspack-plugin"):k?(0,s.sprintf)(w("Connected as %s","newspack-plugin"),t.username):w("Not connected","newspack-plugin")}`,checkbox:k?"checked":"unchecked",actionText:(0,a.createElement)(l.zx,{isLink:!0,isDestructive:k,onClick:k?()=>{d(!0),i()({path:"/newspack/v1/oauth/mailchimp",method:"DELETE"}).then((()=>{n({}),d(!1)})).catch(E)}:()=>r(!0),disabled:u},w(k?"Disconnect":"Connect","newspack-plugin")),isMedium:!0}),c&&(0,a.createElement)(l.u_,{title:w("Add Mailchimp API Key","newspack-plugin"),onRequestClose:b},(0,a.createElement)("div",{ref:m},(0,a.createElement)(l.rj,{columns:1,gutter:8},(0,a.createElement)(l.w4,{placeholder:"123457103961b1f4dc0b2b2fd59c137b-us1",label:w("Mailchimp API Key","newspack-plugin"),hideLabelFromVision:!0,value:o,onChange:p,onKeyDown:e=>{g.ENTER===e.keyCode&&""!==o&&(e.preventDefault(),f())}}),(0,a.createElement)("p",null,(0,a.createElement)(h.ExternalLink,{href:"https://mailchimp.com/help/about-api-keys/#Find_or_generate_your_API_key"},w("Find or generate your API key","newspack-plugin"))))),(0,a.createElement)(l.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(l.zx,{isSecondary:!0,onClick:b},w("Cancel","newspack-plugin")),(0,a.createElement)(l.zx,{isPrimary:!0,disabled:!o,onClick:f},w(u?"Connecting…":k?"Connected":"Connect","newspack-plugin")))))};var E=n(3967),b=n.n(E),f=n(7361),v=n.n(f);const y=s.__,_=[{service:"stripe",label:y("Stripe","newspack-plugin")}],C=({setError:e})=>{const[t,n]=(0,a.useState)(),[s,c]=(0,a.useState)(!1),[r,o]=(0,a.useState)(null),p=t=>e(t.message||y("Something went wrong.","newspack-plugin")),u=s||!(void 0!==t)||!r;return(0,a.useEffect)((()=>{c(!0),i()({path:"/newspack/v1/oauth/fivetran"}).then((e=>{n(e.connections_statuses),o(e.has_accepted_tos)})).catch(p).finally((()=>c(!1)))}),[]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",null,y("In order to use the this features, you must read and accept","newspack-plugin")," ",(0,a.createElement)("a",{href:"https://newspack.com/terms-of-service/"},y("Newspack Terms of Service","newspack-plugin")),":"),(0,a.createElement)(h.CheckboxControl,{className:b()("mt1",{"o-50":null===r}),checked:r,disabled:null===r,onChange:e=>{i()({path:"/newspack/v1/oauth/fivetran-tos",method:"POST",data:{has_accepted:e}}),o(e)},label:y("I've read and accept Newspack Terms of Service","newspack-plugin")}),_.map((e=>{const n=((e,t)=>{const n=void 0!==t,a=v()(t,[e.service,"setup_state"]),s=v()(t,[e.service,"sync_state"]),l=v()(t,[e.service,"schema_status"]),c=l&&"ready"!==l||"paused"===s;let r="-";return a?"ready"===l?r=`${a}, ${s}`:c&&(r=`${a}, ${s}. ${y("Sync is in progress – please check back in a while.","newspack-plugin")}`):n&&(r=y("Not connected","newspack-plugin")),{label:r,isConnected:"connected"===a,isPending:c}})(e,t);return(0,a.createElement)(l.fM,{key:e.service,title:e.label,description:`${y("Status:","newspack-plugin")} ${n.label}`,isPending:n.isPending,actionText:(0,a.createElement)(h.Button,{disabled:u,onClick:()=>(({service:e})=>{c(!0),i()({path:`/newspack/v1/oauth/fivetran/${e}`,method:"POST",data:{service:e}}).then((({url:e})=>window.location=e)).catch(p)})(e),isLink:!0},n.isConnected?y("Re-connect","newspack-plugin"):y("Connect","newspack-plugin")),checkbox:n.isConnected?"checked":"unchecked",isMedium:!0})})))},S=s.__,x=()=>{const[e,t]=(0,a.useState)(null),[n,s]=(0,a.useState)(!1),[c,r]=(0,a.useState)({}),[o,p]=(0,a.useState)({});(0,a.useEffect)((()=>{(async()=>{s(!0);try{const e=await i()({path:"/newspack/v1/recaptcha"});r(e),p(e)}catch(e){t(e.message||S("Error fetching settings.","newspack-plugin"))}finally{s(!1)}})()}),[]);const u=async e=>{t(null),s(!0);try{const t=await i()({path:"/newspack/v1/recaptcha",method:"POST",data:e});r(t),p(t)}catch(e){t(e?.message||S("Error updating settings.","newspack-plugin"))}finally{s(!1)}};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.M$,{id:"recaptcha",title:S("reCAPTCHA v3","newspack-plugin")}),(0,a.createElement)(l.fM,{isMedium:!0,title:S("Enable reCAPTCHA v3","newspack-plugin"),description:()=>(0,a.createElement)(a.Fragment,null,S("Enabling reCAPTCHA v3 can help protect your site against bot attacks and credit card testing.","newspack-plugin")," ",(0,a.createElement)(h.ExternalLink,{href:"https://www.google.com/recaptcha/admin/create"},S("Get started","newspack-plugin"))),hasGreyHeader:!!c.use_captcha,toggleChecked:!!c.use_captcha,toggleOnChange:()=>u({use_captcha:!c.use_captcha}),actionContent:c.use_captcha&&(0,a.createElement)(l.zx,{variant:"primary",disabled:n||!Object.keys(o).length,onClick:()=>u(o)},S("Save Settings","newspack-plugin")),disabled:n},c.use_captcha&&(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(l.qX,{isError:!0,noticeText:e}),c.use_captcha&&(!c.site_key||!c.site_secret)&&(0,a.createElement)(l.qX,{noticeText:S("You must enter a valid site key and secret to use reCAPTCHA.","newspack-plugin")}),(0,a.createElement)(l.rj,{noMargin:!0,rowGap:16},(0,a.createElement)(l.w4,{value:o?.site_key||"",label:S("Site Key","newspack-plugin"),onChange:e=>p({...o,site_key:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(l.w4,{type:"password",value:o?.site_secret||"",label:S("Site Secret","newspack-plugin"),onChange:e=>p({...o,site_secret:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(l.w4,{type:"number",step:"0.05",min:"0",max:"1",value:parseFloat(o?.threshold||""),label:S("Threshold","newspack-plugin"),onChange:e=>p({...o,threshold:e}),disabled:n,help:(0,a.createElement)(h.ExternalLink,{href:"https://developers.google.com/recaptcha/docs/v3#interpreting_the_score"},S("Learn more about the threshold value","newspack-plugin"))})))))};var T=n(6292),N=n.n(T),P=n(793),A=n(8184),M=n(444);const q=(0,a.createElement)(M.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(M.Path,{d:"M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"}));var L=n(9522),O=n(7259),$=n(1984);const j=s.__,F=e=>{let t=e.slice(8);return e.length>45&&(t=`${e.slice(8,38)}...${e.slice(-10)}`),t},I=e=>{const{label:t,url:n}=e;return(0,a.createElement)(a.Fragment,null,t&&(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__label"},t),(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__url"},F(n)))},R=e=>e.requests.some((e=>e.errors.length)),z=({disabled:e,position:t="bottom left",isSystem:n,onEdit:s=(()=>{}),onDelete:c=(()=>{}),onView:r=(()=>{})})=>{const[i,o]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{o(!1)}),[e]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.zx,{className:i&&"popover-active",onClick:()=>o(!i),icon:O.Z,disabled:e,label:j("Endpoint Actions","newspack-plugin"),tooltipPosition:t}),i&&(0,a.createElement)(l.J2,{position:t,onFocusOutside:()=>o(!1),onKeyDown:e=>g.ESCAPE===e.keyCode&&o(!1)},(0,a.createElement)(h.MenuItem,{onClick:()=>o(!1),className:"screen-reader-text"},j("Close Endpoint Actions","newspack-plugin")),(0,a.createElement)(h.MenuItem,{onClick:r,className:"newspack-button"},j("View Requests","newspack-plugin")),!n&&(0,a.createElement)(h.MenuItem,{onClick:s,className:"newspack-button"},j("Edit","newspack-plugin")),!n&&(0,a.createElement)(h.MenuItem,{onClick:c,className:"newspack-button",isDestructive:!0},j("Remove","newspack-plugin"))))},Z=({disabled:e,onConfirm:t,onClose:n,title:s,description:c})=>(0,a.createElement)(l.u_,{title:s,onRequestClose:n},(0,a.createElement)("p",null,c),(0,a.createElement)(l.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(l.zx,{isSecondary:!0,onClick:n,disabled:e},j("Cancel","newspack-plugin")),(0,a.createElement)(l.zx,{isPrimary:!0,onClick:t,disabled:e},j("Confirm","newspack-plugin")))),B=()=>{const[e,t]=(0,a.useState)(!1),[n,c]=(0,a.useState)(!1),[r,o]=(0,a.useState)([]),[p,u]=(0,a.useState)([]),[d,m]=(0,a.useState)(!1),[g,w]=(0,a.useState)(!1),[k,E]=(0,a.useState)(!1),[b,f]=(0,a.useState)(!1),[v,y]=(0,a.useState)(!1),[_,C]=(0,a.useState)(!1),[S,x]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{i()({path:"/newspack/v1/data-events/actions"}).then((e=>{o(e)})).catch((e=>{c(e)}))}),[]),(0,a.useEffect)((()=>{t(!0),i()({path:"/newspack/v1/webhooks/endpoints"}).then((e=>{u(e)})).catch((e=>{c(e)})).finally((()=>{t(!1)}))}),[]),(0,a.useEffect)((()=>{C(!1),y(!1),x(!1)}),[b]),(0,a.createElement)(l.Zb,{noBorder:!0,className:"mt64"},!1!==n&&(0,a.createElement)(l.qX,{isError:!0,noticeText:n.message}),(0,a.createElement)("div",{className:"flex justify-between items-end"},(0,a.createElement)(l.M$,{title:j("Webhook Endpoints","newspack-plugin"),description:j("Register webhook endpoints to integrate reader activity data to third-party services or private APIs","newspack-plugin"),noMargin:!0}),(0,a.createElement)(l.zx,{variant:"primary",onClick:()=>f({global:!0}),disabled:e},j("Add New Endpoint","newspack-plugin"))),p.length>0&&(0,a.createElement)(a.Fragment,null,p.map((e=>(0,a.createElement)(l.fM,{isMedium:!0,className:"newspack-webhooks__endpoint mt16",toggleChecked:!e.disabled,toggleOnChange:()=>w(e),key:e.id,title:I(e),disabled:e.system,description:()=>e.disabled&&e.disabled_error?j("This endpoint is disabled due to excessive request errors","newspack-plugin")+": "+e.disabled_error:(0,a.createElement)(a.Fragment,null,j("Actions:","newspack-plugin")," ",e.global?(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__action"},j("global","newspack-plugin")):e.actions.map((e=>(0,a.createElement)("span",{key:e,className:"newspack-webhooks__endpoint__action"},e)))),actionText:(0,a.createElement)(z,{onEdit:()=>f(e),onDelete:()=>m(e),onView:()=>E(e),isSystem:e.system})})))),!1!==d&&(0,a.createElement)(Z,{title:j("Remove Endpoint","newspack-plugin"),description:(0,s.sprintf)(j("Are you sure you want to remove the endpoint %s?","newspack-plugin"),`"${F(d.url)}"`),onClose:()=>m(!1),onConfirm:()=>{return e=d,t(!0),void i()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"DELETE"}).then((e=>{u(e)})).catch((e=>{c(e)})).finally((()=>{t(!1),m(!1)}));var e},disabled:e}),!1!==g&&(0,a.createElement)(Z,{title:g.disabled?j("Enable Endpoint","newspack-plugin"):j("Disable Endpoint","newspack-plugin"),description:g.disabled?(0,s.sprintf)(j("Are you sure you want to enable the endpoint %s?","newspack-plugin"),`"${F(g.url)}"`):(0,s.sprintf)(j("Are you sure you want to disable the endpoint %s?","newspack-plugin"),`"${F(g.url)}"`),endpoint:g,onClose:()=>w(!1),onConfirm:()=>{return e=g,t(!0),void i()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"POST",data:{disabled:!e.disabled}}).then((e=>{u(e)})).catch((e=>{c(e)})).finally((()=>{t(!1),w(!1)}));var e},disabled:e}),!1!==k&&(0,a.createElement)(l.u_,{title:j("Latest Requests","newspack-plugin"),onRequestClose:()=>E(!1)},(0,a.createElement)("p",null,(0,s.sprintf)(j("Most recent requests for %s","newspack-plugin"),(e=>{const{label:t,url:n}=e;return t||F(n)})(k))),k.requests.length>0?(0,a.createElement)("table",{className:"newspack-webhooks__requests "+(R(k)?"has-error":"")},(0,a.createElement)("tr",null,(0,a.createElement)("th",null),(0,a.createElement)("th",{colSpan:"2"},j("Action","newspack-plugin")),R(k)&&(0,a.createElement)("th",{colSpan:"2"},j("Error","newspack-plugin"))),k.requests.map((e=>{return(0,a.createElement)("tr",{key:e.id},(0,a.createElement)("td",{className:`status status--${e.status}`},(0,a.createElement)($.Z,{icon:(t=e.status,{pending:P.Z,finished:A.Z,killed:q}[t]||L.Z)})),(0,a.createElement)("td",{className:"action-name"},e.action_name),(0,a.createElement)("td",{className:"scheduled"},"pending"===e.status?(0,s.sprintf)(j("sending in %s","newspack-plugin"),N()(1e3*parseInt(e.scheduled)).fromNow(!0)):(0,s.sprintf)(j("processed %s","newspack-plugin"),N()(1e3*parseInt(e.scheduled)).fromNow())),R(k)&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("td",{className:"error"},e.errors&&e.errors.length>0?e.errors[e.errors.length-1]:"--"),(0,a.createElement)("td",null,(0,a.createElement)("span",{className:"error-count"},(0,s.sprintf)(j("Attempt #%s","newspack-plugin"),e.errors.length)))));var t}))):(0,a.createElement)(l.qX,{noticeText:j("This endpoint hasn't received any requests yet.","newspack-plugin")})),!1!==b&&(0,a.createElement)(l.u_,{title:j("Webhook Endpoint","newspack-plugin"),onRequestClose:()=>{f(!1),y(!1)}},!1!==v&&(0,a.createElement)(l.qX,{isError:!0,noticeText:v.message}),!0===b.disabled&&(0,a.createElement)(l.qX,{noticeText:j("This webhook endpoint is currently disabled.","newspack-plugin"),className:"mt0"}),b.disabled&&b.disabled_error&&(0,a.createElement)(l.qX,{isError:!0,noticeText:j("Request Error: ","newspack-plugin")+b.disabled_error,className:"mt0"}),S&&(0,a.createElement)(l.qX,{isError:!0,noticeText:j("Test Error: ","newspack-plugin")+S.message,className:"mt0"}),(0,a.createElement)(l.rj,{columns:1,gutter:16,className:"mt0"},(0,a.createElement)(l.w4,{label:j("URL","newspack-plugin"),help:j("The URL to send requests to. It's required for the URL to be under a valid TLS/SSL certificate. You can use the test button below to verify the endpoint response.","newspack-plugin"),className:"code",value:b.url,onChange:e=>f({...b,url:e}),disabled:e}),(0,a.createElement)(l.w4,{label:j("Authentication token (optional)","newspack-plugin"),help:j("If your endpoint requires a token authentication, enter it here. It will be sent as a Bearer token in the Authorization header.","newspack-plugin"),value:b.bearer_token,onChange:e=>f({...b,bearer_token:e}),disabled:e}),(0,a.createElement)(l.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},_&&(0,a.createElement)("div",{className:"newspack-webhooks__test-response status--"+(_.success?"success":"error")},(0,a.createElement)("span",{className:"message"},_.message),(0,a.createElement)("span",{className:"code"},_.code)),(0,a.createElement)(l.zx,{isSecondary:!0,onClick:()=>{return e=b.url,n=b.bearer_token,t(!0),x(!1),C(!1),void i()({path:"/newspack/v1/webhooks/endpoints/test",method:"POST",data:{url:e,bearer_token:n}}).then((e=>{C(e)})).catch((e=>{x(e)})).finally((()=>{t(!1)}));var e,n},disabled:e||!b.url},j("Send a test request","newspack-plugin")))),(0,a.createElement)("hr",null),(0,a.createElement)(l.w4,{label:j("Label (optional)","newspack-plugin"),help:j("A label to help you identify this endpoint. It will not be sent to the endpoint.","newspack-plugin"),value:b.label,onChange:e=>f({...b,label:e}),disabled:e}),(0,a.createElement)(l.rj,{columns:1,gutter:16},(0,a.createElement)("h3",null,j("Actions","newspack-plugin")),(0,a.createElement)(h.CheckboxControl,{checked:b.global,onChange:e=>f({...b,global:e}),label:j("Global","newspack-plugin"),help:j("Leave this checked if you want this endpoint to receive data from all actions.","newspack-plugin"),disabled:e}),r.length>0&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("p",null,j("If this endpoint is not global, select which actions should trigger this endpoint:","newspack-plugin")),(0,a.createElement)(l.rj,{columns:2,gutter:16},r.map(((t,n)=>(0,a.createElement)(h.CheckboxControl,{key:n,disabled:b.global||e,label:t,checked:b.actions&&b.actions.includes(t)||!1,indeterminate:b.global,onChange:()=>{const e=b.actions||[];e.includes(t)?e.splice(e.indexOf(t),1):e.push(t),f({...b,actions:e})}}))))),(0,a.createElement)(l.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(l.zx,{isPrimary:!0,onClick:()=>{var e;e=b,t(!0),y(!1),i()({path:`/newspack/v1/webhooks/endpoints/${e.id||""}`,method:"POST",data:e}).then((e=>{u(e),f(!1)})).catch((e=>{y(e)})).finally((()=>{t(!1)}))},disabled:e},j("Save","newspack-plugin"))))))},D=s.__,X=s.__,{HashRouter:H,Redirect:G,Route:K,Switch:V}=c.Z,J=(0,l.a4)((()=>{const[e,t]=(0,a.useState)(),n=e=>n=>t(n?e+n:null);return(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(l.qX,{isError:!0,noticeText:e}),(0,a.createElement)(l.M$,{title:D("Plugins","newspack-plugin")}),(0,a.createElement)(d,null),(0,a.createElement)(l.M$,{title:D("APIs","newspack-plugin")}),newspack_connections_data.can_connect_google&&(0,a.createElement)(m.Z,{setError:n(D("Google: ","newspack-plugin"))}),(0,a.createElement)(k,{setError:n(D("Mailchimp: ","newspack-plugin"))}),newspack_connections_data.can_connect_fivetran&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.M$,{title:"Fivetran"}),(0,a.createElement)(C,{setError:n(D("Fivetran: ","newspack-plugin"))})),(0,a.createElement)(x,{setError:n(D("reCAPTCHA: ","newspack-plugin"))}),newspack_connections_data.can_use_webhooks&&(0,a.createElement)(B,null))}));(0,a.render)((0,a.createElement)((0,l.uF)((({pluginRequirements:e,wizardApiFetch:t,startLoading:n,doneLoading:s})=>{const l={headerText:X("Connections","newspack-plugin"),subHeaderText:X("Connections to third-party services","newspack-plugin"),wizardApiFetch:t,startLoading:n,doneLoading:s};return(0,a.createElement)(H,{hashType:"slash"},(0,a.createElement)(V,null,e,(0,a.createElement)(K,{exact:!0,path:"/",render:()=>(0,a.createElement)(J,l)}),(0,a.createElement)(G,{to:"/"})))}))),document.getElementById("newspack-connections-wizard"))},9196:e=>{e.exports=window.React},6292:e=>{e.exports=window.moment},6989:e=>{e.exports=window.wp.apiFetch},5609:e=>{e.exports=window.wp.components},9818:e=>{e.exports=window.wp.data},9307:e=>{e.exports=window.wp.element},2694:e=>{e.exports=window.wp.hooks},2629:e=>{e.exports=window.wp.htmlEntities},5736:e=>{e.exports=window.wp.i18n},9630:e=>{e.exports=window.wp.keycodes},444:e=>{e.exports=window.wp.primitives},6483:e=>{e.exports=window.wp.url}},n={};function a(e){var s=n[e];if(void 0!==s)return s.exports;var l=n[e]={id:e,loaded:!1,exports:{}};return t[e].call(l.exports,l,l.exports,a),l.loaded=!0,l.exports}a.m=t,e=[],a.O=(t,n,s,l)=>{if(!n){var c=1/0;for(p=0;p<e.length;p++){for(var[n,s,l]=e[p],r=!0,i=0;i<n.length;i++)(!1&l||c>=l)&&Object.keys(a.O).every((e=>a.O[e](n[i])))?n.splice(i--,1):(r=!1,l<c&&(c=l));if(r){e.splice(p--,1);var o=s();void 0!==o&&(t=o)}}return t}l=l||0;for(var p=e.length;p>0&&e[p-1][2]>l;p--)e[p]=e[p-1];e[p]=[n,s,l]},a.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return a.d(t,{a:t}),t},a.d=(e,t)=>{for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),a.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.nmd=e=>(e.paths=[],e.children||(e.children=[]),e),a.j=806,(()=>{var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e})(),(()=>{var e={806:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var s,l,[c,r,i]=n,o=0;if(c.some((t=>0!==e[t]))){for(s in r)a.o(r,s)&&(a.m[s]=r[s]);if(i)var p=i(a)}for(t&&t(n);o<c.length;o++)l=c[o],a.o(e,l)&&e[l]&&e[l][0](),e[c[o]]=0;return a.O(p)},n=globalThis.webpackChunkwebpack=globalThis.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var s=a.O(void 0,[351],(()=>a(4845)));s=a.O(s);var l=window;for(var c in s)l[c]=s[c];s.__esModule&&Object.defineProperty(l,"__esModule",{value:!0})})();