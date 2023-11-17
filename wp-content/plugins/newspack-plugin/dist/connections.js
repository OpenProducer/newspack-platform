!function(){"use strict";var e,t={4845:function(e,t,n){n.r(t),n(5674);var a=n(9307),s=n(5736),c=n(264),r=n(9674),o=n(6989),l=n.n(o);const i=s.__,p={jetpack:{pluginSlug:"jetpack",editLink:"admin.php?page=jetpack#/settings",name:"Jetpack",fetchStatus:()=>l()({path:"/newspack/v1/plugins/jetpack"}).then((e=>({jetpack:{status:e.Configured?e.Status:"inactive"}})))},"google-site-kit":{pluginSlug:"google-site-kit",editLink:"admin.php?page=googlesitekit-splash",name:i("Site Kit by Google","newspack"),fetchStatus:()=>l()({path:"/newspack/v1/plugins/google-site-kit"}).then((e=>({"google-site-kit":{status:e.Configured?e.Status:"inactive"}})))}},u=e=>e.pluginSlug?(0,a.createElement)(c.OQ,{plugin:e.pluginSlug,editLink:e.editLink,compact:!0,isLink:!0},i("Connect","newspack")):e.url?(0,a.createElement)(c.zx,{isLink:!0,href:e.url,target:"_blank"},i("Connect","newspack")):"unavailable_site_id"===e.error?.code?(0,a.createElement)("span",{className:"i newspack-error"},i("Jetpack connection required","newspack")):void 0;var d=e=>{let{setError:t}=e;const[n,s]=c.PT.useObjectState(p),r=Object.values(n);return(0,a.useEffect)((()=>{r.forEach((async e=>{const n=await e.fetchStatus().catch(t);s(n)}))}),[]),(0,a.createElement)(a.Fragment,null,r.map((e=>{const t="inactive"===e.status,n=!e.status;return(0,a.createElement)(c.fM,{key:e.name,title:e.name,description:`${i("Status:","newspack")} ${i(n?"Loading…":t?"Not connected":"Connected","newspack")}`,actionText:t?u(e):null,checkbox:t||n?"unchecked":"checked",badge:e.badge,indent:e.indent,isMedium:!0})})))},m=n(8635),h=n(9630),w=n(5609);const k=s.__;var E=e=>{let{setError:t}=e;const[n,r]=(0,a.useState)({}),[o,i]=(0,a.useState)(!1),[p,u]=(0,a.useState)(),[d,m]=(0,a.useState)(!1),E=(0,a.useRef)(null),g=Boolean(n&&n.username),b=e=>t(e.message||k("Something went wrong.","newspack")),f=()=>{i(!1),u()};(0,a.useEffect)((()=>{m(!0),l()({path:"/newspack/v1/oauth/mailchimp"}).then((e=>{r(e)})).catch(b).finally((()=>m(!1)))}),[]),(0,a.useEffect)((()=>{o&&E.current.querySelector("input").focus()}),[o]);const v=()=>{t(),m(!0),l()({path:"/newspack/v1/oauth/mailchimp",method:"POST",data:{api_key:p}}).then((e=>{r(e)})).catch((e=>{t(e.message||k("Something went wrong during verification of your Mailchimp API key.","newspack"))})).finally((()=>{m(!1),f()}))};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.fM,{title:"Mailchimp",description:`${k("Status:","newspack")} ${d?k("Loading…","newspack"):g?(0,s.sprintf)(k("Connected as %s","newspack"),n.username):k("Not connected","newspack")}`,checkbox:g?"checked":"unchecked",actionText:(0,a.createElement)(c.zx,{isLink:!0,isDestructive:g,onClick:g?()=>{m(!0),l()({path:"/newspack/v1/oauth/mailchimp",method:"DELETE"}).then((()=>{r({}),m(!1)})).catch(b)}:()=>i(!0),disabled:d},k(g?"Disconnect":"Connect","newspack")),isMedium:!0}),o&&(0,a.createElement)(c.u_,{title:k("Add Mailchimp API Key","newspack"),onRequestClose:f},(0,a.createElement)("div",{ref:E},(0,a.createElement)(c.rj,{columns:1,gutter:8},(0,a.createElement)(c.w4,{placeholder:"123457103961b1f4dc0b2b2fd59c137b-us1",label:k("Mailchimp API Key","newspack"),hideLabelFromVision:!0,value:p,onChange:u,onKeyDown:e=>{h.ENTER===e.keyCode&&""!==p&&(e.preventDefault(),v())}}),(0,a.createElement)("p",null,(0,a.createElement)(w.ExternalLink,{href:"https://mailchimp.com/help/about-api-keys/#Find_or_generate_your_API_key"},k("Find or generate your API key","newspack"))))),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isSecondary:!0,onClick:f},k("Cancel","newspack")),(0,a.createElement)(c.zx,{isPrimary:!0,disabled:!p,onClick:v},k(d?"Connecting…":g?"Connected":"Connect","newspack")))))},g=n(4184),b=n.n(g),f=n(7361),v=n.n(f);const y=s.__,_=[{service:"mailchimp",label:y("Mailchimp","newspack")},{service:"stripe",label:y("Stripe","newspack")},{service:"double_click_publishers",label:y("Google Ad Manager","newspack")},{service:"facebook_pages",label:y("Facebook Pages","newspack")}];var C=e=>{let{setError:t}=e;const[n,s]=(0,a.useState)(),[r,o]=(0,a.useState)(!1),[i,p]=(0,a.useState)(null),u=e=>t(e.message||y("Something went wrong.","newspack")),d=r||!(void 0!==n)||!i;return(0,a.useEffect)((()=>{o(!0),l()({path:"/newspack/v1/oauth/fivetran"}).then((e=>{s(e.connections_statuses),p(e.has_accepted_tos)})).catch(u).finally((()=>o(!1)))}),[]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",null,y("In order to use the this features, you must read and accept","newspack")," ",(0,a.createElement)("a",{href:"https://newspack.com/terms-of-service/"},y("Newspack Terms of Service","newspack")),":"),(0,a.createElement)(w.CheckboxControl,{className:b()("mt1",{"o-50":null===i}),checked:i,disabled:null===i,onChange:e=>{l()({path:"/newspack/v1/oauth/fivetran-tos",method:"POST",data:{has_accepted:e}}),p(e)},label:y("I've read and accept Newspack Terms of Service","newspack")}),_.map((e=>{const t=((e,t)=>{const n=void 0!==t,a=v()(t,[e.service,"setup_state"]),s=v()(t,[e.service,"sync_state"]),c=v()(t,[e.service,"schema_status"]),r=c&&"ready"!==c||"paused"===s;let o="-";return a?"ready"===c?o=`${a}, ${s}`:r&&(o=`${a}, ${s}. ${y("Sync is in progress – please check back in a while.","newspack")}`):n&&(o=y("Not connected","newspack")),{label:o,isConnected:"connected"===a,isPending:r}})(e,n);return(0,a.createElement)(c.fM,{key:e.service,title:e.label,description:`${y("Status:","newspack")} ${t.label}`,isPending:t.isPending,actionText:(0,a.createElement)(w.Button,{disabled:d,onClick:()=>(e=>{let{service:t}=e;o(!0),l()({path:`/newspack/v1/oauth/fivetran/${t}`,method:"POST",data:{service:t}}).then((e=>{let{url:t}=e;return window.location=t})).catch(u)})(e),isLink:!0},t.isConnected?y("Re-connect","newspack"):y("Connect","newspack")),checkbox:t.isConnected?"checked":"unchecked",isMedium:!0})})))};const S=s.__;var x=()=>{const[e,t]=(0,a.useState)(null),[n,s]=(0,a.useState)(!1),[r,o]=(0,a.useState)({}),[i,p]=(0,a.useState)({});(0,a.useEffect)((()=>{(async()=>{s(!0);try{const e=await l()({path:"/newspack/v1/recaptcha"});o(e),p(e)}catch(e){t(e.message||S("Error fetching settings.","newspack-plugin"))}finally{s(!1)}})()}),[]);const u=async e=>{t(null),s(!0);try{o(await l()({path:"/newspack/v1/recaptcha",method:"POST",data:e})),p({})}catch(e){t(e?.message||S("Error updating settings.","newspack-plugin"))}finally{s(!1)}};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.M$,{id:"recaptcha",title:S("reCAPTCHA v3","newspack-plugin")}),(0,a.createElement)(c.fM,{isMedium:!0,title:S("Enable reCAPTCHA v3","newspack-plugin"),description:()=>(0,a.createElement)(a.Fragment,null,S("Enabling reCAPTCHA v3 can help protect your site against bot attacks and credit card testing.","newspack-plugin")," ",(0,a.createElement)(w.ExternalLink,{href:"https://www.google.com/recaptcha/admin/create"},S("Get started"))),hasGreyHeader:!!r.use_captcha,toggleChecked:!!r.use_captcha,toggleOnChange:()=>u({use_captcha:!r.use_captcha}),actionContent:r.use_captcha&&(0,a.createElement)(c.zx,{variant:"primary",disabled:n||!Object.keys(i).length,onClick:()=>u(i)},S("Save Settings","newspack-plugin")),disabled:n},r.use_captcha&&(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(c.qX,{isError:!0,noticeText:e}),r.use_captcha&&(!r.site_key||!r.site_secret)&&(0,a.createElement)(c.qX,{noticeText:S("You must enter a valid site key and secret to use reCAPTCHA.","newspack-plugin")}),(0,a.createElement)(c.rj,{noMargin:!0,rowGap:16},(0,a.createElement)(c.w4,{value:i?.site_key,label:S("Site Key","newspack-plugin"),onChange:e=>p({...i,site_key:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(c.w4,{type:"password",value:i?.site_secret,label:S("Site Secret","newspack-plugin"),onChange:e=>p({...i,site_secret:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(c.w4,{type:"number",step:"0.05",min:"0",max:"1",value:i?.threshold,label:S("Threshold","newspack-plugin"),onChange:e=>p({...i,threshold:e}),disabled:n,help:(0,a.createElement)(w.ExternalLink,{href:"https://developers.google.com/recaptcha/docs/v3#interpreting_the_score"},S("Learn more about the threshold value","newspack-plugin"))})))))},T=n(6292),N=n.n(T),P=n(793),M=n(8184),A=n(444),q=(0,a.createElement)(A.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(A.Path,{d:"M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"})),L=n(9522),O=n(7259),$=n(1984);const j=s.__,F=e=>{let t=e.slice(8);return e.length>45&&(t=`${e.slice(8,38)}...${e.slice(-10)}`),t},R=e=>{const{label:t,url:n}=e;return(0,a.createElement)(a.Fragment,null,t&&(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__label"},t),(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__url"},F(n)))},I=e=>e.requests.some((e=>e.errors.length)),z=e=>{let{disabled:t,position:n="bottom left",isSystem:s,onEdit:r=(()=>{}),onDelete:o=(()=>{}),onView:l=(()=>{})}=e;const[i,p]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{p(!1)}),[t]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.zx,{className:i&&"popover-active",onClick:()=>p(!i),icon:O.Z,disabled:t,label:j("Endpoint Actions","newspack"),tooltipPosition:n}),i&&(0,a.createElement)(c.J2,{position:n,onFocusOutside:()=>p(!1),onKeyDown:e=>h.ESCAPE===e.keyCode&&p(!1)},(0,a.createElement)(w.MenuItem,{onClick:()=>p(!1),className:"screen-reader-text"},j("Close Endpoint Actions","newspack")),(0,a.createElement)(w.MenuItem,{onClick:l,className:"newspack-button"},j("View Requests","newspack")),!s&&(0,a.createElement)(w.MenuItem,{onClick:r,className:"newspack-button"},j("Edit","newspack")),!s&&(0,a.createElement)(w.MenuItem,{onClick:o,className:"newspack-button",isDestructive:!0},j("Remove","newspack"))))},Z=e=>{let{disabled:t,onConfirm:n,onClose:s,title:r,description:o}=e;return(0,a.createElement)(c.u_,{title:r,onRequestClose:s},(0,a.createElement)("p",null,o),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isSecondary:!0,onClick:s,disabled:t},j("Cancel","newspack")),(0,a.createElement)(c.zx,{isPrimary:!0,onClick:n,disabled:t},j("Confirm","newspack"))))};var D=()=>{const[e,t]=(0,a.useState)(!1),[n,r]=(0,a.useState)(!1),[o,i]=(0,a.useState)([]),[p,u]=(0,a.useState)([]),[d,m]=(0,a.useState)(!1),[h,k]=(0,a.useState)(!1),[E,g]=(0,a.useState)(!1),[b,f]=(0,a.useState)(!1),[v,y]=(0,a.useState)(!1),[_,C]=(0,a.useState)(!1),[S,x]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{l()({path:"/newspack/v1/data-events/actions"}).then((e=>{i(e)})).catch((e=>{r(e)}))}),[]),(0,a.useEffect)((()=>{t(!0),l()({path:"/newspack/v1/webhooks/endpoints"}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1)}))}),[]),(0,a.useEffect)((()=>{C(!1),y(!1),x(!1)}),[b]),newspack_connections_data.can_use_webhooks?(0,a.createElement)(c.Zb,{noBorder:!0,className:"mt64"},!1!==n&&(0,a.createElement)(c.qX,{isError:!0,noticeText:n.message}),(0,a.createElement)("div",{className:"flex justify-between items-end"},(0,a.createElement)(c.M$,{title:j("Webhook Endpoints","newspack"),description:j("Register webhook endpoints to integrate reader activity data to third-party services or private APIs","newspack"),noMargin:!0}),(0,a.createElement)(c.zx,{variant:"primary",onClick:()=>f({global:!0}),disabled:e},j("Add New Endpoint","newspack"))),p.length>0&&(0,a.createElement)(a.Fragment,null,p.map((e=>(0,a.createElement)(c.fM,{isMedium:!0,className:"newspack-webhooks__endpoint mt16",toggleChecked:!e.disabled,toggleOnChange:()=>k(e),key:e.id,title:R(e),disabled:e.system,description:()=>e.disabled&&e.disabled_error?j("This endpoint is disabled due excessive request errors: ","newspack")+e.disabled_error:(0,a.createElement)(a.Fragment,null,j("Actions:","newspack")," ",e.global?(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__action"},j("global","newspack")):e.actions.map((e=>(0,a.createElement)("span",{key:e,className:"newspack-webhooks__endpoint__action"},e)))),actionText:(0,a.createElement)(z,{onEdit:()=>f(e),onDelete:()=>m(e),onView:()=>g(e),isSystem:e.system})})))),!1!==d&&(0,a.createElement)(Z,{title:j("Remove Endpoint","newspack"),description:(0,s.sprintf)(j("Are you sure you want to remove the endpoint %s?","newspack"),`"${F(d.url)}"`),onClose:()=>m(!1),onConfirm:()=>{return e=d,t(!0),void l()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"DELETE"}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1),m(!1)}));var e},disabled:e}),!1!==h&&(0,a.createElement)(Z,{title:h.disabled?j("Enable Endpoint","newspack"):j("Disable Endpoint","newspack"),description:h.disabled?(0,s.sprintf)(j("Are you sure you want to enable the endpoint %s?","newspack"),`"${F(h.url)}"`):(0,s.sprintf)(j("Are you sure you want to disable the endpoint %s?","newspack"),`"${F(h.url)}"`),endpoint:h,onClose:()=>k(!1),onConfirm:()=>{return e=h,t(!0),void l()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"POST",data:{disabled:!e.disabled}}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1),k(!1)}));var e},disabled:e}),!1!==E&&(0,a.createElement)(c.u_,{title:j("Latest Requests","newspack"),onRequestClose:()=>g(!1)},(0,a.createElement)("p",null,(0,s.sprintf)(j("Most recent requests for %s","newspack"),(e=>{const{label:t,url:n}=e;return t||F(n)})(E))),E.requests.length>0?(0,a.createElement)("table",{className:"newspack-webhooks__requests "+(I(E)?"has-error":"")},(0,a.createElement)("tr",null,(0,a.createElement)("th",null),(0,a.createElement)("th",{colSpan:"2"},j("Action","newspack")),I(E)&&(0,a.createElement)("th",{colSpan:"2"},j("Error","newspack"))),E.requests.map((e=>{return(0,a.createElement)("tr",{key:e.id},(0,a.createElement)("td",{className:`status status--${e.status}`},(0,a.createElement)($.Z,{icon:(t=e.status,{pending:P.Z,finished:M.Z,killed:q}[t]||L.Z)})),(0,a.createElement)("td",{className:"action-name"},e.action_name),(0,a.createElement)("td",{className:"scheduled"},"pending"===e.status?(0,s.sprintf)(j("sending in %s","newspack"),N()(1e3*parseInt(e.scheduled)).fromNow(!0)):(0,s.sprintf)(j("processed %s","newspack"),N()(1e3*parseInt(e.scheduled)).fromNow())),I(E)&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("td",{className:"error"},e.errors&&e.errors.length>0?e.errors[e.errors.length-1]:"--"),(0,a.createElement)("td",null,(0,a.createElement)("span",{className:"error-count"},(0,s.sprintf)(j("Attempt #%s","newspack"),e.errors.length)))));var t}))):(0,a.createElement)(c.qX,{noticeText:j("This endpoint didn't received any requests yet.","newspack")})),!1!==b&&(0,a.createElement)(c.u_,{title:j("Webhook Endpoint","newspack"),onRequestClose:()=>{f(!1),y(!1)}},!1!==v&&(0,a.createElement)(c.qX,{isError:!0,noticeText:v.message}),!0===b.disabled&&(0,a.createElement)(c.qX,{noticeText:j("This webhook endpoint is currently disabled.","newspack"),className:"mt0"}),b.disabled&&b.disabled_error&&(0,a.createElement)(c.qX,{isError:!0,noticeText:j("Request Error: ","newspack")+b.disabled_error,className:"mt0"}),S&&(0,a.createElement)(c.qX,{isError:!0,noticeText:j("Test Error: ","newspack")+S.message,className:"mt0"}),(0,a.createElement)(c.rj,{columns:1,gutter:16,className:"mt0"},(0,a.createElement)(c.w4,{label:j("URL","newspack"),help:j("The URL to send requests to. It's required for the URL to be under a valid TLS/SSL certificate. You can use the test button below to verify the endpoint response.","newspack"),className:"code",value:b.url,onChange:e=>f({...b,url:e}),disabled:e}),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},_&&(0,a.createElement)("div",{className:"newspack-webhooks__test-response status--"+(_.success?"success":"error")},(0,a.createElement)("span",{className:"message"},_.message),(0,a.createElement)("span",{className:"code"},_.code)),(0,a.createElement)(c.zx,{isSecondary:!0,onClick:()=>{return e=b.url,t(!0),x(!1),C(!1),void l()({path:"/newspack/v1/webhooks/endpoints/test",method:"POST",data:{url:e}}).then((e=>{C(e)})).catch((e=>{x(e)})).finally((()=>{t(!1)}));var e},disabled:e||!b.url},j("Send a test request","newspack")))),(0,a.createElement)("hr",null),(0,a.createElement)(c.w4,{label:j("Label (optional)","newspack"),help:j("A label to help you identify this endpoint. It will not be sent to the endpoint.","newspack"),value:b.label,onChange:e=>f({...b,label:e}),disabled:e}),(0,a.createElement)(c.rj,{columns:1,gutter:16},(0,a.createElement)("h3",null,j("Actions","newspack")),(0,a.createElement)(w.CheckboxControl,{checked:b.global,onChange:e=>f({...b,global:e}),label:j("Global","newspack"),help:j("Leave this checked if you want this endpoint to receive data from all actions.","newspack"),disabled:e}),o.length>0&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("p",null,j("If this endpoint is not global, select which actions should trigger this endpoint:","newspack")),(0,a.createElement)(c.rj,{columns:2,gutter:16},o.map(((t,n)=>(0,a.createElement)(w.CheckboxControl,{key:n,disabled:b.global||e,label:t,checked:b.actions&&b.actions.includes(t),indeterminate:b.global,onChange:()=>{const e=b.actions||[];e.includes(t)?e.splice(e.indexOf(t),1):e.push(t),f({...b,actions:e})}}))))),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isPrimary:!0,onClick:()=>{var e;e=b,t(!0),y(!1),l()({path:`/newspack/v1/webhooks/endpoints/${e.id||""}`,method:"POST",data:e}).then((e=>{u(e),f(!1)})).catch((e=>{y(e)})).finally((()=>{t(!1)}))},disabled:e},j("Save","newspack")))))):null};const B=s.__;const X=s.__,{HashRouter:G,Redirect:H,Route:K,Switch:V}=r.Z,J=(0,c.a4)((()=>{const[e,t]=(0,a.useState)();return(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(c.qX,{isError:!0,noticeText:e}),(0,a.createElement)(c.M$,{title:B("Plugins","newspack")}),(0,a.createElement)(d,null),(0,a.createElement)(c.M$,{title:B("APIs","newspack")}),newspack_connections_data.can_connect_google&&(0,a.createElement)(m.Z,{setError:e=>t(B("Google: ","newspack-plugin")+e)}),(0,a.createElement)(E,{setError:e=>t(B("Mailchimp: ","newspack-plugin")+e)}),newspack_connections_data.can_connect_fivetran&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.M$,{title:"Fivetran"}),(0,a.createElement)(C,{setError:e=>t(B("FiveTran: ","newspack-plugin")+e)})),(0,a.createElement)(x,{setError:e=>t(B("reCAPTCHA: ","newspack-plugin")+e)}),(0,a.createElement)(D,null))}));(0,a.render)((0,a.createElement)((0,c.uF)((e=>{let{pluginRequirements:t,wizardApiFetch:n,startLoading:s,doneLoading:c}=e;const r={headerText:X("Connections","newspack"),subHeaderText:X("Connections to third-party services","newspack"),wizardApiFetch:n,startLoading:s,doneLoading:c};return(0,a.createElement)(G,{hashType:"slash"},(0,a.createElement)(V,null,t,(0,a.createElement)(K,{exact:!0,path:"/",render:()=>(0,a.createElement)(J,r)}),(0,a.createElement)(H,{to:"/"})))}))),document.getElementById("newspack-connections-wizard"))},9196:function(e){e.exports=window.React},6292:function(e){e.exports=window.moment},6989:function(e){e.exports=window.wp.apiFetch},5609:function(e){e.exports=window.wp.components},9818:function(e){e.exports=window.wp.data},9307:function(e){e.exports=window.wp.element},2694:function(e){e.exports=window.wp.hooks},2629:function(e){e.exports=window.wp.htmlEntities},5736:function(e){e.exports=window.wp.i18n},9630:function(e){e.exports=window.wp.keycodes},444:function(e){e.exports=window.wp.primitives},6483:function(e){e.exports=window.wp.url}},n={};function a(e){var s=n[e];if(void 0!==s)return s.exports;var c=n[e]={id:e,loaded:!1,exports:{}};return t[e].call(c.exports,c,c.exports,a),c.loaded=!0,c.exports}a.m=t,e=[],a.O=function(t,n,s,c){if(!n){var r=1/0;for(p=0;p<e.length;p++){n=e[p][0],s=e[p][1],c=e[p][2];for(var o=!0,l=0;l<n.length;l++)(!1&c||r>=c)&&Object.keys(a.O).every((function(e){return a.O[e](n[l])}))?n.splice(l--,1):(o=!1,c<r&&(r=c));if(o){e.splice(p--,1);var i=s();void 0!==i&&(t=i)}}return t}c=c||0;for(var p=e.length;p>0&&e[p-1][2]>c;p--)e[p]=e[p-1];e[p]=[n,s,c]},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.nmd=function(e){return e.paths=[],e.children||(e.children=[]),e},a.j=806,function(){var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e}(),function(){var e={806:0};a.O.j=function(t){return 0===e[t]};var t=function(t,n){var s,c,r=n[0],o=n[1],l=n[2],i=0;if(r.some((function(t){return 0!==e[t]}))){for(s in o)a.o(o,s)&&(a.m[s]=o[s]);if(l)var p=l(a)}for(t&&t(n);i<r.length;i++)c=r[i],a.o(e,c)&&e[c]&&e[c][0](),e[r[i]]=0;return a.O(p)},n=self.webpackChunkwebpack=self.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var s=a.O(void 0,[351],(function(){return a(4845)}));s=a.O(s);var c=window;for(var r in s)c[r]=s[r];s.__esModule&&Object.defineProperty(c,"__esModule",{value:!0})}();