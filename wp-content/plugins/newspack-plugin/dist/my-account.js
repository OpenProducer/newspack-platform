(()=>{"use strict";var e;e=function(){const e=document.querySelector(".subscription_details .button.cancel");if(e){const t=e=>{const t=newspack_my_account?.labels?.cancel_subscription_message||"Are you sure you want to cancel this subscription?";confirm(t)||e.preventDefault()};e.addEventListener("click",t)}},"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",e):e())})();