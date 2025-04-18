(()=>{"use strict";const e={default:(e,t)=>e.value===t.value,list__in:(e,t)=>{let i=t.value;return"string"==typeof i&&(i=t.value.split(",").map((e=>e.trim()))),!!Array.isArray(i)&&(Array.isArray(e.value)?e.value.some((e=>i.some((t=>t==e)))):!(!e.value||!i.some((t=>t==e.value))))},list__not_in:(e,t)=>{let i=t.value;return"string"==typeof i&&(i=t.value.split(",").map((e=>e.trim()))),!(Array.isArray(i)&&(Array.isArray(e.value)?e.value.some((e=>i.some((t=>t==e)))):e.value&&i.some((t=>t==e.value))))},range:(e,t)=>{const{min:i,max:r}=t.value;return!(!e.value||i&&e.value<i||r&&e.value>r)}};window.newspackPopupsCriteria=window.newspackPopupsCriteria||{criteria:{}},window.newspackPopupsCriteria.criteria=window.newspackPopupsCriteria.criteria||{};const t={};function i(i,r={}){if(!i)throw new Error("Criteria must have an ID.");const n={id:i,matchingFunction:"default",...r,...t[i]};return n.getValue=e=>{if("function"==typeof n.matchingAttribute)return n.matchingAttribute(e);if("string"==typeof n.matchingAttribute){if("function"==typeof e?.store?.get)return e.store.get(n.matchingAttribute);console.warn(`Reader data library not loaded. Unable to fetch value for '${n.id}'`)}return n.value},n._matched={},n.matches=t=>{const i=JSON.stringify(t);if(void 0!==n._matched[i])return n._matched[i];const r=window.newspackReaderActivation;return r||console.warn("Reader activation script not loaded."),(t=>{n._configured||(n._configured=!0,n.matchingAttribute||(n.matchingAttribute=n.id),"string"==typeof n.matchingFunction&&e[n.matchingFunction]&&(n.matchingFunction=e[n.matchingFunction].bind(null,n)),"function"==typeof n.matchingFunction?("function"==typeof t?.on&&t.on("data",(()=>{n._matched={}})),n.value=n.getValue(t)):console.warn(`Unable to configure matching function for criteria ${n.id}.`))})(r),n._matched[i]=n.matchingFunction(t,r),n._matched[i]},window.newspackPopupsCriteria.criteria||(window.newspackPopupsCriteria.criteria={}),window.newspackPopupsCriteria.criteria[i]=n,n}function r(e){return e?window.newspackPopupsCriteria.criteria[e]:window.newspackPopupsCriteria.criteria}function n(e,i){let n=r(e);n||(t[e]=t[e]||{},n=t[e]),n._matched={},n.matchingAttribute=i}function a(e,i){let n=r(e);n||(t[e]=t[e]||{},n=t[e]),n._matched={},n.matchingFunction=i}if(n("articles_read",(e=>e.getUniqueActivitiesBy("article_view","post_id").filter((e=>e.timestamp>Date.now()-2592e6)).length)),n("articles_read_in_session",(e=>{const t=e.getUniqueActivitiesBy("article_view","post_id");if(!t.length)return 0;if(t.sort(((e,t)=>t.timestamp-e.timestamp)),t[0].timestamp<Date.now()-18e5)return 0;let i=0;for(;i<t.length&&t[i+1]&&t[i].timestamp-t[i+1].timestamp<18e5;)i++;return 1+i})),a("favorite_categories",((e,t)=>{let i=!1;const r=t.getUniqueActivitiesBy("article_view","post_id");if(1>=r.length)return i;const n=r.reduce(((e,t)=>(t.data?.categories?.length&&e.push(...t.data.categories),e)),[]).reduce(((e,t)=>(e[t]=(e[t]||0)+1,e)),{}),a=Object.entries(n);return a.sort(((e,t)=>t[1]-e[1])),a&&a.length?((!a[1]||a[0][1]>a[1][1])&&-1<e.value.indexOf(parseInt(a[0][0]))&&(i=!0),i):i})),a("donation",((e,{store:t})=>{switch(e.value){case"donors":return t.get("is_donor");case"non-donors":return!t.get("is_donor");case"formers-donors":return t.get("is_former_donor")}})),a("newsletter",((e,{store:t})=>{switch(e.value){case"subscribers":return t.get("is_newsletter_subscriber");case"non-subscribers":return!t.get("is_newsletter_subscriber")}})),a("user_account",((e,{store:t})=>{switch(e.value){case"with-account":return newspackPopupsCriteria.is_non_preview_user||t.get("reader")?.email;case"without-account":return!newspackPopupsCriteria.is_non_preview_user&&!t.get("reader")?.email}})),newspackPopupsCriteria.criteria={},newspackPopupsCriteria?.config)for(const e in newspackPopupsCriteria.config)i(e,newspackPopupsCriteria.config[e])})();