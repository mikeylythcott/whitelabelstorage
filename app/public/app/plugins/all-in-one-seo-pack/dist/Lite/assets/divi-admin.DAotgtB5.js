import"./js/vue2.B3EPUlhG.js";import{y as a,o as i,c,D as l,Y as u}from"./js/vue.esm-bundler.DzelZkHk.js";import{l as p}from"./js/index.DmkhSBhC.js";import{l as m}from"./js/index.DX4OhBfI.js";import{l as d}from"./js/index.3BJ3ZnWB.js";import{u as f,l as _}from"./js/links.CKSg78-h.js";import{C as A}from"./js/Caret.Cuasz9Up.js";import{_ as b}from"./js/_plugin-vue_export-helper.BN1snXvA.js";import"./js/translations.B4Iz5cbd.js";import"./js/default-i18n.BtxsUzQk.js";import"./js/constants.DARe-ccJ.js";import"./js/isArrayLikeObject.CkjpbQo7.js";const h={setup(){return{rootStore:f()}},components:{CoreAlert:A},data(){return{strings:{alert:this.$t.sprintf(this.$t.__("The options below are disabled because you are using %1$s to manage your SEO. They can be changed in the %2$sSearch Appearance menu%3$s.",this.$td),"All in One SEO",`<a href="${this.rootStore.aioseo.urls.aio.searchAppearance}" target="_blank">`,"</a>")}}}},y={class:"aioseo-divi-seo-admin-notice-container"};function g(o,t,n,e,r,x){const s=a("core-alert");return i(),c("div",y,[l(s,{innerHTML:r.strings.alert},null,8,["innerHTML"])])}const S=b(h,[["render",g]]),w=()=>{const o=document.querySelectorAll("#wrap-seo .et-tab-content");for(let t=0;t<o.length;t++){const n=document.createElement("div");n.setAttribute("id",`aioseo-divi-seo-admin-notice-container-${t}`),o[t].insertBefore(n,o[t].firstChild);let e=u({...S,name:"Standalone/DiviAdmin"});e=p(e),e=m(e),e=d(e),_(e),e.mount(`#${n.getAttribute("id")}`)}},$=()=>{const o=document.querySelectorAll('#wrap-seo input[type="text"], #wrap-seo textarea');for(const e of o)e.style.pointerEvents="none",e.setAttribute("readonly",!0);const t=document.querySelectorAll("#wrap-seo select");for(const e of t)e.style.pointerEvents="none",e.setAttribute("disabled",!0);const n=document.querySelectorAll("#wrap-seo .et-checkbox");for(const e of n)e.setAttribute("disabled",!0),e.nextElementSibling.style.pointerEvents="none"},v=()=>{const o=window.aioseo.urls.aio.searchAppearance,t=document.querySelector('a[href="#wrap-seo"]');if(!o||!t)return;const n=t.cloneNode(!0);n.setAttribute("href",o),t.parentNode.replaceChild(n,t)};window.addEventListener("load",()=>{w(),$(),v()});
