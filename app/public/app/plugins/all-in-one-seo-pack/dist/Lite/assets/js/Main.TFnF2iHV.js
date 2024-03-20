import{C as x,u as b,m as O,n as P,a as G,f as I,h as Z,e as W}from"./links.CKSg78-h.js";import{a as C}from"./allowed.B_mIy271.js";import"./default-i18n.BtxsUzQk.js";import{u as D,C as F,a as Y,N as j}from"./Index.BcrR8EtF.js";import{y as u,o as i,c as r,D as d,d as h,a as e,t as o,E as _,l as g,m as l,H as v,F as L,L as N,x as z}from"./vue.esm-bundler.DzelZkHk.js";import{_ as k}from"./_plugin-vue_export-helper.BN1snXvA.js";import{U as Q}from"./Url.DUcj4Th3.js";import{C as X}from"./Card.C6Yzm1Gr.js";import{C as J,S as K,a as tt,b as st}from"./SitemapsPro.CHVOSq3k.js";import{C as et}from"./GettingStarted.02zePTQw.js";import{C as it}from"./Overview.BnN5s2e9.js";import{C as ot}from"./SeoSetup.BmdaILLj.js";import{p as nt}from"./popup.Dv7cb5WI.js";import{u as E,S as U}from"./SeoSiteScore.9LP7E1ph.js";import{C as rt}from"./Blur.B433XVqJ.js";import{C as at}from"./Index.S3yt8Lmc.js";import{C as ct}from"./Tooltip.DcUmvaHX.js";import{C as lt}from"./Index.Ck0NNxBQ.js";import{G as dt,a as ht}from"./Row.ou4tdPuA.js";import{S as ut}from"./Book.iWCUYtMr.js";import{S as mt,a as _t}from"./Build.CQX7DDZt.js";import{a as gt}from"./index.DX4OhBfI.js";import{S as pt}from"./History.D1Nc95hv.js";import{S as ft}from"./Message.Jt04sTfY.js";import{S as St}from"./Rocket.DfBIByRc.js";import{S as kt}from"./Statistics.CP5lE97B.js";import{S as vt}from"./VideoCamera.PtujQl9J.js";import"./isArrayLikeObject.CkjpbQo7.js";import"./license.8zyTf6Rb.js";import"./upperFirst.Cx8cdEgZ.js";import"./_stringToArray.DnK4tKcY.js";import"./toString.EVG10Qqs.js";/* empty css             */import"./params.B3T1WKlC.js";import"./Ellipse.HvxcRElJ.js";import"./Caret.Cuasz9Up.js";import"./Header.CI74i-Kf.js";import"./addons.Bhqo_sme.js";import"./ScrollAndHighlight.DCpqKtXJ.js";import"./LogoGear.oMlhtqmB.js";import"./AnimatedNumber.BZqhDXvl.js";import"./numbers.ursUutt1.js";import"./Logo.CuK32Muc.js";import"./Support.B5EAN5JN.js";import"./Tabs.Cl9YKSoz.js";import"./TruSeoScore.-L7x872T.js";import"./Information.Dx9dnFtu.js";import"./Slide.BfXXFx9A.js";import"./Date.Bc79vv_Y.js";import"./constants.DARe-ccJ.js";import"./Exclamation.BU2oeqa4.js";import"./Gear.CzHv0eD2.js";import"./Index.6gbvf_mk.js";import"./DonutChartWithLegend.BDrgOxPz.js";const $t={setup(){const{strings:t}=E();return{analyzerStore:x(),rootStore:b(),composableStrings:t}},components:{CoreSiteScore:at},mixins:[U],props:{score:Number,loading:Boolean,summary:{type:Object,default(){return{}}}},data(){return{allowed:C,strings:O(this.composableStrings,{anErrorOccurred:this.$t.__("An error occurred while analyzing your site.",this.$td),criticalIssues:this.$t.__("Important Issues",this.$td),warnings:this.$t.__("Warnings",this.$td),recommendedImprovements:this.$t.__("Recommended Improvements",this.$td),goodResults:this.$t.__("Good Results",this.$td),completeSiteAuditChecklist:this.$t.__("Complete Site Audit Checklist",this.$td)})}},computed:{getError(){switch(this.analyzerStore.analyzeError){case"invalid-url":return this.$t.__("The URL provided is invalid.",this.$td);case"missing-content":return this.$t.__("We were unable to parse the content for this site.",this.$td);case"invalid-token":return this.$t.sprintf(this.$t.__("Your site is not connected. Please connect to %1$s, then try again.",this.$td),"AIOSEO")}return this.analyzerStore.analyzeError}}},Ct={class:"aioseo-site-score-dashboard"},yt={key:0,class:"aioseo-seo-site-score-score"},wt={key:1,class:"aioseo-seo-site-score-recommendations"},bt={class:"critical"},At={class:"round red"},Lt={class:"recommended"},Nt={class:"round blue"},zt={class:"good"},xt={class:"round green"},Ot={key:0,class:"links"},Et=["href"],Ut=["href"],Tt={key:2,class:"analyze-errors"},Mt=e("br",null,null,-1);function Vt(t,a,p,n,s,m){const f=u("core-site-score");return i(),r("div",Ct,[n.analyzerStore.analyzeError?h("",!0):(i(),r("div",yt,[d(f,{loading:p.loading,score:p.score,description:t.description,strokeWidth:1.75},null,8,["loading","score","description"])])),n.analyzerStore.analyzeError?h("",!0):(i(),r("div",wt,[e("div",bt,[e("span",At,o(p.summary.critical||0),1),_(" "+o(s.strings.criticalIssues),1)]),e("div",Lt,[e("span",Nt,o(p.summary.recommended||0),1),_(" "+o(s.strings.recommendedImprovements),1)]),e("div",zt,[e("span",xt,o(p.summary.good||0),1),_(" "+o(s.strings.goodResults),1)]),s.allowed("aioseo_seo_analysis_settings")?(i(),r("div",Ot,[e("a",{href:n.rootStore.aioseo.urls.aio.seoAnalysis},o(s.strings.completeSiteAuditChecklist),9,Et),e("a",{href:n.rootStore.aioseo.urls.aio.seoAnalysis,class:"no-underline"},"→",8,Ut)])):h("",!0)])),n.analyzerStore.analyzeError?(i(),r("div",Tt,[e("strong",null,o(s.strings.anErrorOccurred),1),Mt,e("p",null,o(m.getError),1)])):h("",!0)])}const Ht=k($t,[["render",Vt]]),Rt={setup(){const{strings:t}=E();return{analyzerStore:x(),connectStore:P(),optionsStore:G(),rootStore:b(),strings:t}},components:{CoreBlur:rt,CoreSiteScoreDashboard:Ht},mixins:[U],data(){return{score:0}},computed:{getSummary(){return{recommended:this.analyzerStore.recommendedCount(),critical:this.analyzerStore.criticalCount(),good:this.analyzerStore.goodCount()}}},methods:{openPopup(t){nt(t,this.connectWithAioseo,600,630,!0,["token"],this.completedCallback,this.closedCallback)},completedCallback(t){return this.connectStore.saveConnectToken(t.token)},closedCallback(t){t&&this.analyzerStore.runSiteAnalyzer(),this.analyzerStore.analyzing=!0}},mounted(){!this.optionsStore.internalOptions.internal.siteAnalysis.score&&this.optionsStore.internalOptions.internal.siteAnalysis.connectToken&&(this.analyzerStore.analyzing=!0,this.analyzerStore.runSiteAnalyzer())}},qt={class:"aioseo-seo-site-score"},Bt={key:1,class:"aioseo-seo-site-score-cta"};function Pt(t,a,p,n,s,m){const f=u("core-site-score-dashboard"),y=u("core-blur");return i(),r("div",qt,[n.optionsStore.internalOptions.internal.siteAnalysis.connectToken?h("",!0):(i(),g(y,{key:0},{default:l(()=>[d(f,{score:85,description:t.description},null,8,["description"])]),_:1})),n.optionsStore.internalOptions.internal.siteAnalysis.connectToken?h("",!0):(i(),r("div",Bt,[e("a",{href:"#",onClick:a[0]||(a[0]=v(S=>m.openPopup(n.rootStore.aioseo.urls.connect),["prevent"]))},o(t.connectWithAioseo),1),_(" "+o(n.strings.toSeeYourSiteScore),1)])),n.optionsStore.internalOptions.internal.siteAnalysis.connectToken?(i(),g(f,{key:2,score:n.optionsStore.internalOptions.internal.siteAnalysis.score,description:t.description,loading:n.analyzerStore.analyzing,summary:m.getSummary},null,8,["score","description","loading","summary"])):h("",!0)])}const Gt=k(Rt,[["render",Pt]]),It={},Zt={viewBox:"0 0 28 28",fill:"none",xmlns:"http://www.w3.org/2000/svg",class:"aioseo-svg-clipboard-checkmark"},Wt=e("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M17.29 4.66668H22.1667C23.45 4.66668 24.5 5.71668 24.5 7.00001V23.3333C24.5 24.6167 23.45 25.6667 22.1667 25.6667H5.83333C5.67 25.6667 5.51833 25.655 5.36667 25.6317C4.91167 25.5383 4.50333 25.305 4.18833 24.99C3.97833 24.7683 3.80333 24.5233 3.68667 24.2433C3.57 23.9633 3.5 23.6483 3.5 23.3333V7.00001C3.5 6.67334 3.57 6.37001 3.68667 6.10168C3.80333 5.82168 3.97833 5.56501 4.18833 5.35501C4.50333 5.04001 4.91167 4.80668 5.36667 4.71334C5.51833 4.67834 5.67 4.66668 5.83333 4.66668H10.71C11.2 3.31334 12.4833 2.33334 14 2.33334C15.5167 2.33334 16.8 3.31334 17.29 4.66668ZM19.355 10.01L21 11.6667L11.6667 21L7 16.3334L8.645 14.6884L11.6667 17.6984L19.355 10.01ZM14 4.37501C14.4783 4.37501 14.875 4.77168 14.875 5.25001C14.875 5.72834 14.4783 6.12501 14 6.12501C13.5217 6.12501 13.125 5.72834 13.125 5.25001C13.125 4.77168 13.5217 4.37501 14 4.37501ZM5.83333 23.3333H22.1667V7.00001H5.83333V23.3333Z",fill:"currentColor"},null,-1),Dt=[Wt];function Ft(t,a){return i(),r("svg",Zt,Dt)}const Yt=k(It,[["render",Ft]]),jt={},Qt={viewBox:"0 0 28 28",fill:"none",xmlns:"http://www.w3.org/2000/svg",class:"aioseo-location-pin"},Xt=e("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M13.9999 2.33331C17.8616 2.33331 20.9999 5.47165 20.9999 9.33331C20.9999 14.5833 13.9999 22.1666 13.9999 22.1666C13.9999 22.1666 6.99992 14.5833 6.99992 9.33331C6.99992 5.47165 10.1383 2.33331 13.9999 2.33331ZM22.1666 25.6666V23.3333H5.83325V25.6666H22.1666ZM9.33325 9.33331C9.33325 6.75498 11.4216 4.66665 13.9999 4.66665C16.5783 4.66665 18.6666 6.75498 18.6666 9.33331C18.6666 11.8183 16.2399 15.7033 13.9999 18.5616C11.7599 15.715 9.33325 11.8183 9.33325 9.33331ZM11.6666 9.33331C11.6666 8.04998 12.7166 6.99998 13.9999 6.99998C15.2833 6.99998 16.3333 8.04998 16.3333 9.33331C16.3333 10.6166 15.2949 11.6666 13.9999 11.6666C12.7166 11.6666 11.6666 10.6166 11.6666 9.33331Z",fill:"currentColor"},null,-1),Jt=[Xt];function Kt(t,a){return i(),r("svg",Qt,Jt)}const ts=k(jt,[["render",Kt]]),ss={},es={viewBox:"0 0 28 28",fill:"none",xmlns:"http://www.w3.org/2000/svg",class:"aioseo-title-and-meta"},is=e("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M22.75 4.08334L21 2.33334L19.25 4.08334L17.5 2.33334L15.75 4.08334L14 2.33334L12.25 4.08334L10.5 2.33334L8.75 4.08334L7 2.33334L5.25 4.08334L3.5 2.33334V25.6667L5.25 23.9167L7 25.6667L8.75 23.9167L10.5 25.6667L12.25 23.9167L14 25.6667L15.75 23.9167L17.5 25.6667L19.25 23.9167L21 25.6667L22.75 23.9167L24.5 25.6667V2.33334L22.75 4.08334ZM22.1667 22.2717H5.83333V5.72833H22.1667V22.2717ZM21 17.5H7V19.8333H21V17.5ZM7 12.8333H21V15.1667H7V12.8333ZM21 8.16668H7V10.5H21V8.16668Z",fill:"currentColor"},null,-1),os=[is];function ns(t,a){return i(),r("svg",es,os)}const rs=k(ss,[["render",ns]]),as={setup(){const{strings:t}=D();return{licenseStore:I(),notificationsStore:Z(),rootStore:b(),settingsStore:W(),composableStrings:t}},components:{CoreCard:X,CoreFeatureCard:J,CoreGettingStarted:et,CoreMain:F,CoreNotificationCards:Y,CoreOverview:it,CoreSeoSetup:ot,CoreSeoSiteScore:Gt,CoreTooltip:ct,Cta:lt,GridColumn:dt,GridRow:ht,SvgBook:ut,SvgBuild:mt,SvgCircleQuestionMark:gt,SvgClipboardCheckmark:Yt,SvgHistory:pt,SvgLinkAssistant:K,SvgLocationPin:ts,SvgMessage:ft,SvgRedirect:tt,SvgRocket:St,SvgShare:_t,SvgSitemapsPro:st,SvgStatistics:kt,SvgTitleAndMeta:rs,SvgVideoCamera:vt},mixins:[j,Q],data(){return{allowed:C,dismissed:!1,visibleNotifications:2,strings:O(this.composableStrings,{pageName:this.$t.__("Dashboard",this.$td),noNewNotificationsThisMoment:this.$t.__("There are no new notifications at this moment.",this.$td),seeAllDismissedNotifications:this.$t.__("See all dismissed notifications.",this.$td),seoSiteScore:this.$t.__("SEO Site Score",this.$td),seoOverview:this.$t.sprintf(this.$t.__("%1$s Overview",this.$td),"AIOSEO"),seoSetup:this.$t.__("SEO Setup",this.$td),support:this.$t.__("Support",this.$td),readSeoUserGuide:this.$t.sprintf(this.$t.__("Read the %1$s user guide",this.$td),"All in One SEO"),accessPremiumSupport:this.$t.__("Access our Premium Support",this.$td),viewChangelog:this.$t.__("View the Changelog",this.$td),watchVideoTutorials:this.$t.__("Watch video tutorials",this.$td),gettingStarted:this.$t.__("Getting started? Read the Beginners Guide",this.$td),quicklinks:this.$t.__("Quicklinks",this.$td),quicklinksTooltip:this.$t.__("You can use these quicklinks to quickly access our settings pages to adjust your site's SEO settings.",this.$td),manage:this.$t.__("Manage",this.$td),searchAppearance:this.$t.__("Search Appearance",this.$td),manageSearchAppearance:this.$t.__("Configure how your website content will look in Google, Bing and other search engines.",this.$td),seoAnalysis:this.$t.__("SEO Analysis",this.$td),manageSeoAnalysis:this.$t.__("Check how your site scores with our SEO analyzer and compare against your competitor's site.",this.$td),localSeo:this.$t.__("Local SEO",this.$td),manageLocalSeo:this.$t.__("Improve local SEO rankings with schema for business address, open hours, contact, and more.",this.$td),socialNetworks:this.$t.__("Social Networks",this.$td),manageSocialNetworks:this.$t.__("Setup Open Graph for Facebook, Twitter, etc. to show the right content / thumbnail preview.",this.$td),tools:this.$t.__("Tools",this.$td),manageTools:this.$t.__("Fine-tune your site with our powerful tools including Robots.txt editor, import/export and more.",this.$td),sitemap:this.$t.__("Sitemaps",this.$td),manageSitemap:this.$t.__("Manage all of your sitemap settings, including XML, Video, News and more.",this.$td),linkAssistant:this.$t.__("Link Assistant",this.$td),manageLinkAssistant:this.$t.__("Manage existing links, get relevant suggestions for adding internal links to older content, discover orphaned posts and more.",this.$td),redirects:this.$t.__("Redirection Manager",this.$td),manageRedirects:this.$t.__("Easily create and manage redirects for your broken links to avoid confusing search engines and users, as well as losing valuable backlinks.",this.$td),searchStatistics:this.$t.__("Search Statistics",this.$td),manageSearchStatistics:this.$t.__("Track how your site is performing in search rankings and generate reports with actionable insights.",this.$td),ctaHeaderText:this.$t.sprintf(this.$t.__("Get more features in %1$s %2$s:",this.$td),"AIOSEO","Pro"),ctaButton:this.$t.sprintf(this.$t.__("Upgrade to %1$s and Save %2$s",this.$td),"Pro",this.$constants.DISCOUNT_PERCENTAGE),dismissAll:this.$t.__("Dismiss All",this.$td),relaunchSetupWizard:this.$t.__("Relaunch Setup Wizard",this.$td)})}},computed:{moreNotifications(){return this.remainingNotificationsCount===1?this.$t.__("You have 1 more notification",this.$td):this.$t.sprintf(this.$t.__("You have %1$s more notifications",this.$td),this.remainingNotificationsCount)},remainingNotificationsCount(){return this.notifications.length-this.visibleNotifications},filteredNotifications(){return[...this.notifications].splice(0,this.visibleNotifications)},supportOptions(){const t=[{icon:"svg-book",text:this.strings.readSeoUserGuide,link:this.$links.utmUrl("dashboard-support-box","user-guide","doc-categories/getting-started/"),blank:!0},{icon:"svg-message",text:this.strings.accessPremiumSupport,link:this.$links.utmUrl("dashboard-support-box","premium-support","contact/"),blank:!0},{icon:"svg-history",text:this.strings.viewChangelog,link:this.$links.utmUrl("dashboard-support-box","changelog","changelog/"),blank:!0},{icon:"svg-book",text:this.strings.gettingStarted,link:this.$links.utmUrl("dashboard-support-box","beginners-guide","docs/quick-start-guide/"),blank:!0}];return C("aioseo_setup_wizard")?this.settingsStore.settings.showSetupWizard?t:t.concat({icon:"svg-rocket",text:this.strings.relaunchSetupWizard,link:this.rootStore.aioseo.urls.aio.wizard,blank:!1}):t},quickLinks(){return[{icon:"svg-title-and-meta",description:this.strings.manageSearchAppearance,name:this.strings.searchAppearance,manageUrl:this.rootStore.aioseo.urls.aio.searchAppearance,access:"aioseo_search_appearance_settings"},{icon:"svg-clipboard-checkmark",description:this.strings.manageSeoAnalysis,name:this.strings.seoAnalysis,manageUrl:this.rootStore.aioseo.urls.aio.seoAnalysis,access:"aioseo_seo_analysis_settings"},{icon:"svg-location-pin",description:this.strings.manageLocalSeo,name:this.strings.localSeo,manageUrl:this.rootStore.aioseo.urls.aio.localSeo,access:"aioseo_local_seo_settings"},{icon:"svg-share",description:this.strings.manageSocialNetworks,name:this.strings.socialNetworks,manageUrl:this.rootStore.aioseo.urls.aio.socialNetworks,access:"aioseo_social_networks_settings"},{icon:"svg-statistics",description:this.strings.manageSearchStatistics,name:this.strings.searchStatistics,manageUrl:this.rootStore.aioseo.urls.aio.searchStatistics,access:"aioseo_search_statistics_settings"},{icon:"svg-sitemaps-pro",description:this.strings.manageSitemap,name:this.strings.sitemap,manageUrl:this.rootStore.aioseo.urls.aio.sitemaps,access:"aioseo_sitemap_settings"},{icon:"svg-link-assistant",description:this.strings.manageLinkAssistant,name:this.strings.linkAssistant,manageUrl:this.rootStore.aioseo.urls.aio.linkAssistant,access:"aioseo_link_assistant_settings"},{icon:"svg-redirect",description:this.strings.manageRedirects,name:this.strings.redirects,manageUrl:this.rootStore.aioseo.urls.aio.redirects,access:"aioseo_redirects_settings"}].filter(t=>C(t.access))}},methods:{processDismissAllNotifications(){const t=[];this.notifications.forEach(a=>{t.push(a.slug)}),this.notificationsStore.dismissNotifications(t)}}},cs={class:"aioseo-dashboard"},ls={key:0,class:"dashboard-getting-started"},ds={class:"aioseo-quicklinks-title"},hs={class:"feature-card-body"},us={class:"feature-card-header"},ms={class:"feature-card-description"},_s={key:0,class:"learn-more"},gs=["href"],ps=["href"],fs={key:0,class:"notifications-count"},Ss={class:"no-dashboard-notifications"},ks={key:0,class:"notification-footer"},vs={class:"more-notifications"},$s=["href","target"];function Cs(t,a,p,n,s,m){const f=u("core-getting-started"),y=u("core-seo-setup"),S=u("core-card"),T=u("core-overview"),M=u("svg-circle-question-mark"),V=u("core-tooltip"),$=u("grid-column"),A=u("grid-row"),H=u("core-seo-site-score"),R=u("core-notification-cards"),q=u("cta"),B=u("core-main");return i(),r("div",cs,[d(B,{"page-name":s.strings.pageName,"show-tabs":!1,"show-save-button":!1},{default:l(()=>[e("div",null,[n.settingsStore.settings.showSetupWizard&&s.allowed("aioseo_setup_wizard")?(i(),r("div",ls,[d(f)])):h("",!0),d(A,null,{default:l(()=>[d($,{md:"6"},{default:l(()=>[n.rootStore.aioseo.setupWizard.isCompleted?h("",!0):(i(),g(S,{key:0,slug:"dashboardSeoSetup","header-text":s.strings.seoSetup},{default:l(()=>[d(y)]),_:1},8,["header-text"])),d(S,{slug:"dashboardOverview","header-text":s.strings.seoOverview},{default:l(()=>[d(T)]),_:1},8,["header-text"]),m.quickLinks.length>0?(i(),g(A,{key:1,class:"aioseo-quicklinks-cards-row"},{default:l(()=>[d($,null,{default:l(()=>[e("div",ds,[_(o(s.strings.quicklinks)+" ",1),d(V,null,{tooltip:l(()=>[_(o(s.strings.quicklinksTooltip),1)]),default:l(()=>[d(M)]),_:1})])]),_:1}),(i(!0),r(L,null,N(m.quickLinks,(c,w)=>(i(),g($,{key:w,lg:"6",class:"aioseo-quicklinks-cards"},{default:l(()=>[e("div",hs,[e("div",us,[(i(),g(z(c.icon))),_(" "+o(c.name),1)]),e("div",ms,[_(o(c.description)+" ",1),c.manageUrl&&s.allowed(c.access)?(i(),r("div",_s,[e("a",{href:t.getHref(c.manageUrl)},o(s.strings.manage),9,gs),e("a",{href:t.getHref(c.manageUrl),class:"no-underline"}," → ",8,ps)])):h("",!0)])])]),_:2},1024))),128))]),_:1})):h("",!0)]),_:1}),d($,{md:"6"},{default:l(()=>[d(S,{slug:"dashboardSeoSiteScore","header-text":s.strings.seoSiteScore},{default:l(()=>[d(H)]),_:1},8,["header-text"]),d(S,{class:"dashboard-notifications",slug:"dashboardNotifications"},{header:l(()=>[t.notificationsCount?(i(),r("div",fs," ("+o(t.notificationsCount)+") ",1)):h("",!0),e("div",null,o(t.notificationTitle),1),s.dismissed?(i(),r("a",{key:1,class:"show-dismissed-notifications",href:"#",onClick:a[0]||(a[0]=v(c=>s.dismissed=!1,["prevent"]))},o(s.strings.activeNotifications),1)):h("",!0)]),default:l(()=>[d(R,{notifications:m.filteredNotifications,dismissedCount:n.notificationsStore.dismissedNotificationsCount,onToggleDismissed:a[2]||(a[2]=c=>s.dismissed=!s.dismissed)},{"no-notifications":l(()=>[e("div",Ss,[e("div",null,o(s.strings.noNewNotificationsThisMoment),1),n.notificationsStore.dismissedNotificationsCount?(i(),r("a",{key:0,href:"#",onClick:a[1]||(a[1]=v(c=>s.dismissed=!0,["prevent"]))},o(s.strings.seeAllDismissedNotifications),1)):h("",!0)])]),_:1},8,["notifications","dismissedCount"]),t.notifications.length>s.visibleNotifications?(i(),r("div",ks,[e("div",vs,[e("a",{href:"#",onClick:a[3]||(a[3]=v((...c)=>n.notificationsStore.toggleNotifications&&n.notificationsStore.toggleNotifications(...c),["stop","prevent"]))},o(m.moreNotifications),1),e("a",{class:"no-underline",href:"#",onClick:a[4]||(a[4]=v((...c)=>n.notificationsStore.toggleNotifications&&n.notificationsStore.toggleNotifications(...c),["stop","prevent"]))}," → ")])])):h("",!0)]),_:1}),d(S,{class:"dashboard-support",slug:"dashboardSupport","header-text":s.strings.support},{default:l(()=>[(i(!0),r(L,null,N(m.supportOptions,(c,w)=>(i(),r("div",{key:w,class:"aioseo-settings-row"},[e("a",{href:c.link,target:c.blank?"_blank":null},[(i(),g(z(c.icon))),_(" "+o(c.text),1)],8,$s)]))),128))]),_:1},8,["header-text"]),n.licenseStore.isUnlicensed?(i(),g(q,{key:0,class:"dashboard-cta",type:3,floating:!1,"cta-link":t.$links.utmUrl("dashboard-cta"),"feature-list":t.$constants.UPSELL_FEATURE_LIST,"button-text":s.strings.ctaButton,"learn-more-link":t.$links.getUpsellUrl("dashboard-cta",null,t.$isPro?"pricing":"liteUpgrade")},{"header-text":l(()=>[_(o(s.strings.ctaHeaderText),1)]),_:1},8,["cta-link","feature-list","button-text","learn-more-link"])):h("",!0)]),_:1})]),_:1})])]),_:1},8,["page-name"])])}const ye=k(as,[["render",Cs]]);export{ye as default};
