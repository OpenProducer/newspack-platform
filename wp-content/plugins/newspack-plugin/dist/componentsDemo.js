!function(){"use strict";var e,t={8366:function(e,t,n){n.r(t);var a=n(9307),l=(n(5674),n(5736)),c=n(444),o=(0,a.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(c.Path,{d:"M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"})),i=(0,a.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(c.Path,{d:"M7 7.2h8.2L13.5 9l1.1 1.1 3.6-3.6-3.5-4-1.1 1 1.9 2.3H7c-.9 0-1.7.3-2.3.9-1.4 1.5-1.4 4.2-1.4 5.6v.2h1.5v-.3c0-1.1 0-3.5 1-4.5.3-.3.7-.5 1.2-.5zm13.8 4V11h-1.5v.3c0 1.1 0 3.5-1 4.5-.3.3-.7.5-1.3.5H8.8l1.7-1.7-1.1-1.1L5.9 17l3.5 4 1.1-1-1.9-2.3H17c.9 0 1.7-.3 2.3-.9 1.5-1.4 1.5-4.2 1.5-5.6z"})),s=(0,a.createElement)(c.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)(c.Path,{d:"M17.7 4.3c-1.2 0-2.8 0-3.8 1-.6.6-.9 1.5-.9 2.6V14c-.6-.6-1.5-1-2.5-1C8.6 13 7 14.6 7 16.5S8.6 20 10.5 20c1.5 0 2.8-1 3.3-2.3.5-.8.7-1.8.7-2.5V7.9c0-.7.2-1.2.5-1.6.6-.6 1.8-.6 2.8-.6h.3V4.3h-.4z"})),r=n(6922),p=n(2601),m=n(3520);const u=l.__;class d extends a.Component{constructor(){super(...arguments),this.state={selectedPostForAutocompleteWithSuggestions:[],selectedPostsForAutocompleteWithSuggestionsMultiSelect:[],image:null,selectValue1:"2nd",selectValue2:"",selectValue3:"",selectValues:[],modalShown:!1,color1:"#3366ff"}}render(){const{selectedPostForAutocompleteWithSuggestions:e,selectedPostsForAutocompleteWithSuggestionsMultiSelect:t,selectValue1:n,selectValue2:l,selectValue3:c,modalShown:d,actionCardToggleChecked:w,color1:k}=this.state;return(0,a.createElement)(a.Fragment,null,newspack_aux_data.is_debug_mode&&(0,a.createElement)(m.qX,{debugMode:!0}),(0,a.createElement)("div",{className:"newspack-wizard__header"},(0,a.createElement)("div",{className:"newspack-wizard__header__inner"},(0,a.createElement)("div",{className:"newspack-wizard__title"},(0,a.createElement)(m.zx,{isLink:!0,href:newspack_urls.dashboard,label:u("Return to Dashboard","newspack"),showTooltip:!0,icon:r.Z,iconSize:36},(0,a.createElement)(m.VQ,{size:36})),(0,a.createElement)("div",null,(0,a.createElement)("h2",null,u("Components Demo","newspack")),(0,a.createElement)("span",null,u("Simple components used for composing the UI of Newspack","newspack")))))),(0,a.createElement)("div",{className:"newspack-wizard newspack-wizard__content"},(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Autocomplete with Suggestions (single-select)","newspack")),(0,a.createElement)(m.ah,{label:u("Search for a post","newspack"),help:u("Begin typing post title, click autocomplete result to select.","newspack"),onChange:e=>this.setState({selectedPostForAutocompleteWithSuggestions:e}),selectedItems:e}),(0,a.createElement)("hr",null),(0,a.createElement)("h2",null,u("Autocomplete with Suggestions (multi-select)","newspack")),(0,a.createElement)(m.ah,{hideHelp:!0,multiSelect:!0,label:u("Search widgets","newspack"),help:u("Begin typing post title, click autocomplete result to select.","newspack"),onChange:e=>this.setState({selectedPostsForAutocompleteWithSuggestionsMultiSelect:e}),postTypes:[{slug:"page",label:"Pages"},{slug:"post",label:"Posts"}],postTypeLabel:"widget",postTypeLabelPlural:"widgets",selectedItems:t})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Plugin toggles","newspack")),(0,a.createElement)(m.Wy,{plugins:{woocommerce:{shouldRefreshAfterUpdate:!0},"fb-instant-articles":{actionText:u("Configure Instant Articles"),href:"/wp-admin/admin.php?page=newspack"}}})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Web Previews","newspack")),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0,className:"items-center"},(0,a.createElement)(m.BA,{url:"//newspack.pub/",label:u("Preview Newspack Blog","newspack"),variant:"primary"}),(0,a.createElement)(m.BA,{url:"//newspack.pub/",renderButton:e=>{let{showPreview:t}=e;return(0,a.createElement)("a",{href:"#",onClick:t},u("Preview Newspack Blog","newspack"))}}))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Waiting","newspack")),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.rj,{columns:1,gutter:16,className:"w-100"},(0,a.createElement)(m.Pi,null),(0,a.createElement)("div",{className:"flex items-center"},(0,a.createElement)(m.Pi,{isLeft:!0}),u("Spinner on the left","newspack")),(0,a.createElement)("div",{className:"flex items-center"},(0,a.createElement)(m.Pi,{isRight:!0}),u("Spinner on the right","newspack")),(0,a.createElement)(m.Pi,{isCenter:!0})))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Color picker","newspack")),(0,a.createElement)(m.zH,{label:u("Color Picker","newspack"),color:k,onChange:e=>this.setState({color1:e})})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Handoff Buttons","newspack")),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.OQ,{modalTitle:u("Manage AMP","newspack"),modalBody:u("Click to go to the AMP dashboard. There will be a notification bar at the top with a link to return to Newspack.","newspack"),plugin:"amp",isTertiary:!0}),(0,a.createElement)(m.OQ,{plugin:"jetpack"}),(0,a.createElement)(m.OQ,{plugin:"google-site-kit"}),(0,a.createElement)(m.OQ,{plugin:"woocommerce"}),(0,a.createElement)(m.OQ,{plugin:"wordpress-seo",isPrimary:!0,editLink:"/wp-admin/admin.php?page=wpseo_dashboard#top#features"},u("Specific Yoast Page","newspack")))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Modal","newspack")),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.zx,{isPrimary:!0,onClick:()=>this.setState({modalShown:!0})},u("Open modal","newspack"))),d&&(0,a.createElement)(m.u_,{title:u("This is the modal title","newspack"),onRequestClose:()=>this.setState({modalShown:!1})},(0,a.createElement)("p",null,u("Based on industry research, we advise to test the modal component, and continuing this sentence so we can see how the text wraps is one good way of doing that.","newspack")),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(m.zx,{isPrimary:!0,onClick:()=>this.setState({modalShown:!1})},u("Dismiss","newspack")),(0,a.createElement)(m.zx,{isSecondary:!0,onClick:()=>this.setState({modalShown:!1})},u("Also dismiss","newspack"))))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Notice","newspack")),(0,a.createElement)(m.qX,{noticeText:u("This is an info notice.","newspack")}),(0,a.createElement)(m.qX,{noticeText:u("This is an error notice.","newspack"),isError:!0}),(0,a.createElement)(m.qX,{noticeText:u("This is a help notice.","newspack"),isHelp:!0}),(0,a.createElement)(m.qX,{noticeText:u("This is a success notice.","newspack"),isSuccess:!0}),(0,a.createElement)(m.qX,{noticeText:u("This is a warning notice.","newspack"),isWarning:!0})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Plugin installer","newspack")),(0,a.createElement)(m.xf,{plugins:["woocommerce","amp","wordpress-seo"],canUninstall:!0,onStatus:e=>{let{complete:t,pluginInfo:n}=e;console.log(t?"All plugins installed successfully":"Plugin installation incomplete",n)}})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Plugin installer (small)","newspack")),(0,a.createElement)(m.xf,{plugins:["woocommerce","amp","wordpress-seo"],isSmall:!0,canUninstall:!0,onStatus:e=>{let{complete:t,pluginInfo:n}=e;console.log(t?"All plugins installed successfully":"Plugin installation incomplete",n)}})),(0,a.createElement)(m.fM,{title:u("Example One","newspack"),description:u("Has an action button.","newspack"),actionText:u("Install","newspack"),onClick:()=>{console.log("Install clicked")}}),(0,a.createElement)(m.fM,{title:u("Example Two","newspack"),description:u("Has action button and secondary button.","newspack"),actionText:u("Edit","newspack"),secondaryActionText:u("Delete","newspack"),secondaryDestructive:!0,onClick:()=>{console.log("Edit clicked")},onSecondaryActionClick:()=>{console.log("Delete clicked")}}),(0,a.createElement)(m.fM,{title:u("Example Three","newspack"),description:u("Waiting/in-progress state, no action button.","newspack"),actionText:u("Installing…","newspack"),isWaiting:!0}),(0,a.createElement)(m.fM,{title:u("Example Four","newspack"),description:u("Error notification","newspack"),actionText:u("Install","newspack"),onClick:()=>{console.log("Install clicked")},notification:(0,a.createElement)(a.Fragment,null,"Plugin cannot be installed ",(0,a.createElement)("a",{href:"#"},"Retry")," | ",(0,a.createElement)("a",{href:"#"},"Documentation")),notificationLevel:"error"}),(0,a.createElement)(m.fM,{title:u("Example Five","newspack"),description:u("Warning notification, action button","newspack"),notification:(0,a.createElement)(a.Fragment,null,"There is a new version available. ",(0,a.createElement)("a",{href:"#"},"View details")," or"," ",(0,a.createElement)("a",{href:"#"},"update now")),notificationLevel:"warning"}),(0,a.createElement)(m.fM,{title:u("Example Six","newspack"),description:u("Static text, no button","newspack"),actionText:u("Active","newspack")}),(0,a.createElement)(m.fM,{title:u("Example Seven","newspack"),description:u("Static text, secondary action button.","newspack"),actionText:u("Active","newspack"),secondaryActionText:u("Delete","newspack"),secondaryDestructive:!0,onSecondaryActionClick:()=>{console.log("Delete clicked")}}),(0,a.createElement)(m.fM,{title:u("Example Eight","newspack"),description:u("Image with link and action button.","newspack"),actionText:u("Configure","newspack"),onClick:()=>{console.log("Configure clicked")},image:"https://i0.wp.com/newspack.pub/wp-content/uploads/2020/06/pexels-photo-3183150.jpeg",imageLink:"https://newspack.pub"}),(0,a.createElement)(m.fM,{title:u("Example Nine","newspack"),description:u("Action Card with Toggle Control.","newspack"),actionText:w&&u("Configure","newspack"),onClick:()=>{console.log("Configure clicked")},toggleOnChange:e=>this.setState({actionCardToggleChecked:e}),toggleChecked:w}),(0,a.createElement)(m.fM,{badge:u("Premium","newspack"),title:u("Example Ten","newspack"),description:u("An example of an action card with a badge.","newspack"),actionText:u("Install","newspack"),onClick:()=>{console.log("Install clicked")}}),(0,a.createElement)(m.fM,{isSmall:!0,title:u("Example Eleven","newspack"),description:u("An example of a small action card.","newspack"),actionText:u("Installing","newspack"),onClick:()=>{console.log("Install clicked")}}),(0,a.createElement)(m.fM,{title:u("Example Twelve","newspack"),description:u("Action card with an unchecked checkbox.","newspack"),actionText:u("Configure","newspack"),onClick:()=>{console.log("Configure")},checkbox:"unchecked"}),(0,a.createElement)(m.fM,{title:u("Example Thirteen","newspack"),description:u("Action card with a checked checkbox.","newspack"),secondaryActionText:u("Disconnect","newspack"),onSecondaryActionClick:()=>{console.log("Disconnect")},checkbox:"checked"}),(0,a.createElement)(m.fM,{badge:[u("Premium","newspack"),u("Archived","newspack")],title:u("Example Fourteen","newspack"),description:u("An example of an action card with two badges.","newspack"),actionText:u("Install","newspack"),onClick:()=>{console.log("Install clicked")}}),(0,a.createElement)(m.fM,{title:u("Handoff","newspack"),description:u("An example of an action card with Handoff.","newspack"),actionText:u("Configure","newspack"),handoff:"jetpack"}),(0,a.createElement)(m.fM,{title:u("Handoff","newspack"),description:u(" An example of an action card with Handoff and EditLink.","newspack"),actionText:u("Configure","newspack"),handoff:"jetpack",editLink:"admin.php?page=jetpack#/settings"}),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Image Uploader","newspack")),(0,a.createElement)(m.Ur,{image:this.state.image,onChange:e=>{this.setState({image:e}),console.log("Image:"),console.log(e)}})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Progress bar","newspack")),(0,a.createElement)(m.ko,{completed:"2",total:"3"}),(0,a.createElement)(m.ko,{completed:"2",total:"5",label:u("Progress made","newspack")}),(0,a.createElement)(m.ko,{completed:"0",total:"5",displayFraction:!0}),(0,a.createElement)(m.ko,{completed:"3",total:"8",label:u("Progress made","newspack"),displayFraction:!0})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Select dropdowns","newspack")),(0,a.createElement)(m.rj,{columns:1,gutter:16},(0,a.createElement)(m.Yw,{label:u("Label for Select with a preselection","newspack"),value:n,options:[{value:null,label:u("- Select -","newspack"),disabled:!0},{value:"1st",label:u("First","newspack")},{value:"2nd",label:u("Second","newspack")},{value:"3rd",label:u("Third","newspack")}],onChange:e=>this.setState({selectValue1:e})}),(0,a.createElement)(m.Yw,{label:u("Label for Select with no preselection","newspack"),value:l,options:[{value:null,label:u("- Select -","newspack"),disabled:!0},{value:"1st",label:u("First","newspack")},{value:"2nd",label:u("Second","newspack")},{value:"3rd",label:u("Third","newspack")}],onChange:e=>this.setState({selectValue2:e})}),(0,a.createElement)(m.Yw,{label:u("Label for disabled Select","newspack"),disabled:!0,options:[{value:null,label:u("- Select -","newspack"),disabled:!0},{value:"1st",label:u("First","newspack")},{value:"2nd",label:u("Second","newspack")},{value:"3rd",label:u("Third","newspack")}]}),(0,a.createElement)(m.Yw,{label:u("Small","newspack"),value:c,isSmall:!0,options:[{value:null,label:u("- Select -","newspack"),disabled:!0},{value:"1st",label:u("First","newspack")},{value:"2nd",label:u("Second","newspack")},{value:"3rd",label:u("Third","newspack")}],onChange:e=>this.setState({selectValue3:e})}),(0,a.createElement)(m.Yw,{multiple:!0,label:u("Multi-select","newspack"),value:this.state.selectValues,options:[{value:"1st",label:u("First","newspack")},{value:"2nd",label:u("Second","newspack")},{value:"3rd",label:u("Third","newspack")},{value:"4th",label:u("Fourth","newspack")},{value:"5th",label:u("Fifth","newspack")},{value:"6th",label:u("Sixth","newspack")},{value:"7th",label:u("Seventh","newspack")}],onChange:e=>this.setState({selectValues:e})}),(0,a.createElement)(m.qX,{noticeText:(0,a.createElement)(a.Fragment,null,u("Selected:","newspack")," ",this.state.selectValues.length>0?this.state.selectValues.join(", "):u("none","newspack"))}))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Buttons","newspack")),(0,a.createElement)(m.rj,{columns:1,gutter:16},(0,a.createElement)("p",null,(0,a.createElement)("strong",null,u("Default","newspack"))),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.zx,{variant:"primary"},"Primary"),(0,a.createElement)(m.zx,{variant:"secondary"},"Secondary"),(0,a.createElement)(m.zx,{variant:"tertiary"},"Tertiary"),(0,a.createElement)(m.zx,null,"Default"),(0,a.createElement)(m.zx,{isLink:!0},"isLink")),(0,a.createElement)("p",null,(0,a.createElement)("strong",null,u("Disabled","newspack"))),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.zx,{variant:"primary",disabled:!0},"Primary"),(0,a.createElement)(m.zx,{variant:"secondary",disabled:!0},"Secondary"),(0,a.createElement)(m.zx,{variant:"tertiary",disabled:!0},"Tertiary"),(0,a.createElement)(m.zx,{disabled:!0},"Default"),(0,a.createElement)(m.zx,{isLink:!0,disabled:!0},"isLink")),(0,a.createElement)("p",null,(0,a.createElement)("strong",null,u("Small","newspack"))),(0,a.createElement)(m.Zb,{buttonsCard:!0,noBorder:!0},(0,a.createElement)(m.zx,{variant:"primary",isSmall:!0},"isPrimary"),(0,a.createElement)(m.zx,{variant:"secondary",isSmall:!0},"isSecondary"),(0,a.createElement)(m.zx,{variant:"tertiary",isSmall:!0},"isTertiary"),(0,a.createElement)(m.zx,{isSmall:!0},"Default"),(0,a.createElement)(m.zx,{isLink:!0,isSmall:!0},"isLink")))),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,"ButtonCard"),(0,a.createElement)(m.Wu,{href:"admin.php?page=newspack-site-design-wizard",title:u("Site Design","newspack"),desc:u("Customize the look and feel of your site","newspack"),icon:p.Z,chevron:!0}),(0,a.createElement)(m.Wu,{href:"#",title:u("Start a new site","newspack"),desc:u("You don't have content to import","newspack"),icon:o,className:"br--top",grouped:!0}),(0,a.createElement)(m.Wu,{href:"#",title:u("Migrate an existing site","newspack"),desc:u("You have content to import","newspack"),icon:i,className:"br--bottom",grouped:!0}),(0,a.createElement)(m.Wu,{href:"#",title:u("Add a new Podcast","newspack"),desc:"newspack",icon:s,className:"br--top",isSmall:!0,grouped:!0}),(0,a.createElement)(m.Wu,{href:"#",title:u("Add a new Font","newspack"),desc:"newspack",icon:p.Z,className:"br--bottom",chevron:!0,isSmall:!0,grouped:!0})),(0,a.createElement)(m.Zb,null,(0,a.createElement)("h2",null,u("Plugin Settings Section","newspack")),(0,a.createElement)(m.d5.Section,{sectionKey:"example",title:u("Example plugin settings","newspack"),description:u("Example plugin settings description","newspack"),active:!0,fields:[{key:"example_field",type:"string",description:"Example Text Field",help:"Example text field help text",value:"Example Value"},{key:"example_checkbox_field",type:"boolean",description:"Example checkbox Field",help:"Example checkbox field help text",value:!1},{key:"example_options_field",type:"string",description:"Example options field",help:"Example options field help text",options:[{value:"example_value_1",name:"Example Value 1"},{value:"example_value_2",name:"Example Value 2"}]},{key:"example_multi_options_field",type:"string",description:"Example multiple options field",help:"Example multiple options field help text",multiple:!0,options:[{value:"example_value_1",name:"Example Value 1"},{value:"example_value_2",name:"Example Value 2"}]}],onUpdate:e=>{console.log("Plugin Settings Section Updated",e)},onChange:(e,t)=>{console.log("Plugin Settings Section Changed",{key:e,val:t})}}))),(0,a.createElement)(m.$_,null))}}(0,a.render)((0,a.createElement)(d,null),document.getElementById("newspack-components-demo"))},9196:function(e){e.exports=window.React},2819:function(e){e.exports=window.lodash},6292:function(e){e.exports=window.moment},6989:function(e){e.exports=window.wp.apiFetch},5609:function(e){e.exports=window.wp.components},9818:function(e){e.exports=window.wp.data},9307:function(e){e.exports=window.wp.element},2694:function(e){e.exports=window.wp.hooks},2629:function(e){e.exports=window.wp.htmlEntities},5736:function(e){e.exports=window.wp.i18n},9630:function(e){e.exports=window.wp.keycodes},444:function(e){e.exports=window.wp.primitives},6483:function(e){e.exports=window.wp.url}},n={};function a(e){var l=n[e];if(void 0!==l)return l.exports;var c=n[e]={exports:{}};return t[e].call(c.exports,c,c.exports,a),c.exports}a.m=t,e=[],a.O=function(t,n,l,c){if(!n){var o=1/0;for(p=0;p<e.length;p++){n=e[p][0],l=e[p][1],c=e[p][2];for(var i=!0,s=0;s<n.length;s++)(!1&c||o>=c)&&Object.keys(a.O).every((function(e){return a.O[e](n[s])}))?n.splice(s--,1):(i=!1,c<o&&(o=c));if(i){e.splice(p--,1);var r=l();void 0!==r&&(t=r)}}return t}c=c||0;for(var p=e.length;p>0&&e[p-1][2]>c;p--)e[p]=e[p-1];e[p]=[n,l,c]},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.j=815,function(){var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e}(),function(){var e={815:0};a.O.j=function(t){return 0===e[t]};var t=function(t,n){var l,c,o=n[0],i=n[1],s=n[2],r=0;if(o.some((function(t){return 0!==e[t]}))){for(l in i)a.o(i,l)&&(a.m[l]=i[l]);if(s)var p=s(a)}for(t&&t(n);r<o.length;r++)c=o[r],a.o(e,c)&&e[c]&&e[c][0](),e[o[r]]=0;return a.O(p)},n=self.webpackChunkwebpack=self.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var l=a.O(void 0,[351],(function(){return a(8366)}));l=a.O(l);var c=window;for(var o in l)c[o]=l[o];l.__esModule&&Object.defineProperty(c,"__esModule",{value:!0})}();