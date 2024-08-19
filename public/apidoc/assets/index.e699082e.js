var a=Object.defineProperty,t=Object.defineProperties,e=Object.getOwnPropertyDescriptors,n=Object.getOwnPropertySymbols,l=Object.prototype.hasOwnProperty,o=Object.prototype.propertyIsEnumerable,r=(t,e,n)=>e in t?a(t,e,{enumerable:!0,configurable:!0,writable:!0,value:n}):t[e]=n;import{w as i,R as s,C as c,T as d,d as u,P as p,u as v,f,a as m,_ as g,c as b,b as y,r as h,i as C,e as x,g as _,h as T,j as O,k,l as S,m as E,n as A,o as I,p as j,q as D,s as P,t as R,v as G,x as w,y as B,z as L,A as M,B as z,F as K,D as H,E as N,G as F,H as V,I as q,J}from"./index.9c98a0a7.js";import{A as W}from"./index.62336d3a.js";var Q=i(s),U=i(c),X=d.TabPane,Y=u({name:"ACard",props:{prefixCls:String,title:p.any,extra:p.any,bordered:{type:Boolean,default:!0},bodyStyle:{type:Object,default:void 0},headStyle:{type:Object,default:void 0},loading:{type:Boolean,default:!1},hoverable:{type:Boolean,default:!1},type:{type:String},size:{type:String},actions:p.any,tabList:{type:Array},tabBarExtraContent:p.any,activeTabKey:String,defaultActiveTabKey:String,cover:p.any,onTabChange:{type:Function}},slots:["title","extra","tabBarExtraContent","actions","cover","customTab"],setup:function(a,t){var e=t.slots,n=v("card",a),l=n.prefixCls,o=n.direction,r=n.size,i=function(a){return a.map((function(t,e){return C(t)&&!x(t)||!C(t)?b("li",{style:{width:"".concat(100/a.length,"%")},key:"action-".concat(e)},[b("span",null,[t])]):null}))},s=function(t){var e;null===(e=a.onTabChange)||void 0===e||e.call(a,t)};return function(){var t,n,c,u,p,v,C,x,T,O=a.headStyle,k=void 0===O?{}:O,S=a.bodyStyle,E=void 0===S?{}:S,A=a.loading,I=a.bordered,j=void 0===I||I,D=a.type,P=a.tabList,R=a.hoverable,G=a.activeTabKey,w=a.defaultActiveTabKey,B=a.tabBarExtraContent,L=void 0===B?f(null===(c=e.tabBarExtraContent)||void 0===c?void 0:c.call(e)):B,M=a.title,z=void 0===M?f(null===(u=e.title)||void 0===u?void 0:u.call(e)):M,K=a.extra,H=void 0===K?f(null===(p=e.extra)||void 0===p?void 0:p.call(e)):K,N=a.actions,F=void 0===N?f(null===(v=e.actions)||void 0===v?void 0:v.call(e)):N,V=a.cover,q=void 0===V?f(null===(C=e.cover)||void 0===C?void 0:C.call(e)):V,J=m(null===(x=e.default)||void 0===x?void 0:x.call(e)),W=l.value,Y=(g(t={},"".concat(W),!0),g(t,"".concat(W,"-loading"),A),g(t,"".concat(W,"-bordered"),j),g(t,"".concat(W,"-hoverable"),!!R),g(t,"".concat(W,"-contain-grid"),function(){var a;return(arguments.length>0&&void 0!==arguments[0]?arguments[0]:[]).forEach((function(t){t&&_(t.type)&&t.type.__ANT_CARD_GRID&&(a=!0)})),a}(J)),g(t,"".concat(W,"-contain-tabs"),P&&P.length),g(t,"".concat(W,"-").concat(r.value),r.value),g(t,"".concat(W,"-type-").concat(D),!!D),g(t,"".concat(W,"-rtl"),"rtl"===o.value),t),Z=0===E.padding||"0px"===E.padding?{padding:"24px"}:void 0,$=b("div",{class:"".concat(W,"-loading-block")},null),aa=b("div",{class:"".concat(W,"-loading-content"),style:Z},[b(Q,{gutter:8},{default:function(){return[b(U,{span:22},{default:function(){return[$]}})]}}),b(Q,{gutter:8},{default:function(){return[b(U,{span:8},{default:function(){return[$]}}),b(U,{span:15},{default:function(){return[$]}})]}}),b(Q,{gutter:8},{default:function(){return[b(U,{span:6},{default:function(){return[$]}}),b(U,{span:18},{default:function(){return[$]}})]}}),b(Q,{gutter:8},{default:function(){return[b(U,{span:13},{default:function(){return[$]}}),b(U,{span:9},{default:function(){return[$]}})]}}),b(Q,{gutter:8},{default:function(){return[b(U,{span:4},{default:function(){return[$]}}),b(U,{span:3},{default:function(){return[$]}}),b(U,{span:16},{default:function(){return[$]}})]}})]),ta=void 0!==G,ea=(g(n={size:"large"},ta?"activeKey":"defaultActiveKey",ta?G:w),g(n,"onChange",s),g(n,"class","".concat(W,"-head-tabs")),n),na=P&&P.length?b(d,ea,{default:function(){return[P.map((function(a){var t=a.tab,n=a.slots,l=null==n?void 0:n.tab;y(!n,"Card","tabList slots is deprecated, Please use `customTab` instead.");var o=void 0!==t?t:e[l]?e[l](a):null;return o=h(e,"customTab",a,(function(){return[o]})),b(X,{tab:o,key:a.key,disabled:a.disabled},null)}))]},rightExtra:L?function(){return L}:null}):null;(z||H||na)&&(T=b("div",{class:"".concat(W,"-head"),style:k},[b("div",{class:"".concat(W,"-head-wrapper")},[z&&b("div",{class:"".concat(W,"-head-title")},[z]),H&&b("div",{class:"".concat(W,"-extra")},[H])]),na]));var la=q?b("div",{class:"".concat(W,"-cover")},[q]):null,oa=b("div",{class:"".concat(W,"-body"),style:E},[A?aa:J]),ra=F&&F.length?b("ul",{class:"".concat(W,"-actions")},[i(F)]):null;return b("div",{class:Y,ref:"cardContainerRef"},[T,la,J&&J.length?oa:null,ra])}}}),Z=u({name:"ACardMeta",props:{prefixCls:String,title:p.any,description:p.any,avatar:p.any},slots:["title","description","avatar"],setup:function(a,t){var e=t.slots,n=v("card",a).prefixCls;return function(){var t=g({},"".concat(n.value,"-meta"),!0),l=T(e,a,"avatar"),o=T(e,a,"title"),r=T(e,a,"description"),i=l?b("div",{class:"".concat(n.value,"-meta-avatar")},[l]):null,s=o?b("div",{class:"".concat(n.value,"-meta-title")},[o]):null,c=r?b("div",{class:"".concat(n.value,"-meta-description")},[r]):null,d=s||c?b("div",{class:"".concat(n.value,"-meta-detail")},[s,c]):null;return b("div",{class:t},[i,d])}}}),$=u({name:"ACardGrid",__ANT_CARD_GRID:!0,props:{prefixCls:String,hoverable:{type:Boolean,default:!0}},setup:function(a,t){var e=t.slots,n=v("card",a).prefixCls,l=O((function(){var t;return g(t={},"".concat(n.value,"-grid"),!0),g(t,"".concat(n.value,"-grid-hoverable"),a.hoverable),t}));return function(){var a;return b("div",{class:l.value},[null===(a=e.default)||void 0===a?void 0:a.call(e)])}}});Y.Meta=Z,Y.Grid=$,Y.install=function(a){return a.component(Y.name,Y),a.component(Z.name,Z),a.component($.name,$),a};var aa="3.1.2";const ta={class:"home-page"},ea={class:"home-page-content"},na={class:"readme"},la={key:0},oa={key:1},ra={class:"number-block"},ia=z("APP"),sa=z("API"),ca=z("DOCS"),da={key:0,class:"method-list"},ua={class:"info"},pa={class:"name"},va={class:"value"},fa={key:0,class:"tags-wraper"},ma={key:1},ga={key:0,class:"author-list"},ba={key:1},ya={key:0,class:"footer-version"},ha=u((Ca=((a,t)=>{for(var e in t||(t={}))l.call(t,e)&&r(a,e,t[e]);if(n)for(var e of n(t))o.call(t,e)&&r(a,e,t[e]);return a})({},{name:"Home"}),t(Ca,e({setup(a){const t=aa,{t:e}=M(),n=S(),l=E(),o=A({simpleImage:I.PRESENTED_IMAGE_SIMPLE,groupColumns:[{title:e("common.name"),dataIndex:"title"},{title:e("common.controller"),dataIndex:"controller"},{title:e("common.api"),dataIndex:"api"}],groupData:[]});return j((()=>{!function(a){const t=n.appObject[n.appKey];if(!(t&&t.groups&&t.groups.length))return[];const e=a.controllerGroupTotal?a.controllerGroupTotal:{},l=a.apiGroupTotal?a.apiGroupTotal:{};o.groupData=function a(t){return t&&t.length?t.map((t=>{const n={title:t.title,controller:e[t.name],api:l[t.name]};return t.children&&t.children.length&&(n.children=a(t.children)),n})):[]}(t.groups)}(l.dashboard)})),(a,r)=>{const i=W,s=Z,c=U,d=Y,u=q,p=I,v=Q,f=J,m=N;return D(),P("div",ta,[R("div",ea,[R("div",na,[G(n).serverConfig.title?(D(),P("h1",la,w(G(n).serverConfig.title),1)):G(n).feConfig.TITLE?(D(),P("h1",oa,w(G(n).feConfig.TITLE),1)):B("",!0),R("p",null,w(G(n).serverConfig.desc),1)]),b(v,{gutter:16},{default:L((()=>[b(c,{xs:24,sm:24,md:8,lg:8,xl:8},{default:L((()=>[R("div",ra,[b(s,{class:"number-block-item"},{avatar:L((()=>[b(i,{class:"color-orange",size:50},{default:L((()=>[ia])),_:1})])),title:L((()=>[R("div",null,w(G(l).dashboard.appCount),1)])),description:L((()=>[R("div",null,w(G(e)("home.appCount")),1)])),_:1}),b(s,{class:"number-block-item"},{avatar:L((()=>[b(i,{class:"color-green",size:50},{default:L((()=>[sa])),_:1})])),title:L((()=>[R("div",null,w(G(l).dashboard.apiCount),1)])),description:L((()=>[R("div",null,w(G(e)("home.apiCount")),1)])),_:1}),b(s,{class:"number-block-item"},{avatar:L((()=>[b(i,{class:"color-blue",size:50},{default:L((()=>[ca])),_:1})])),title:L((()=>[R("div",null,w(G(l).dashboard.docsCount),1)])),description:L((()=>[R("div",null,w(G(e)("home.docsCount")),1)])),_:1})])])),_:1}),b(c,{xs:24,sm:24,md:8,lg:8,xl:8},{default:L((()=>[b(d,{class:"mb-sm",bodyStyle:{padding:"10px"}},{title:L((()=>[z(w(G(e)("home.methodCount")),1)])),default:L((()=>[Object.keys(G(l).dashboard.apiMethodTotal).length?(D(),P("div",da,[R("ul",null,[(D(!0),P(K,null,H(G(l).dashboard.apiMethodTotal,((a,t)=>(D(),P("li",{key:t},[R("div",ua,[R("div",pa,w(t),1),R("div",va,w(a),1)]),R("div",{class:"bg",style:F({backgroundColor:G(n).feConfig.METHOD_COLOR&&G(n).feConfig.METHOD_COLOR[t]?G(n).feConfig.METHOD_COLOR[t]:""})},null,4)])))),128))])])):B("",!0)])),_:1})])),_:1}),b(c,{xs:24,sm:24,md:8,lg:8,xl:8},{default:L((()=>[b(d,{class:"mb-sm",bodyStyle:{padding:"10px"}},{title:L((()=>[z(w(G(e)("common.tag")),1)])),default:L((()=>[Object.keys(G(l).dashboard.apiTagTotal).length?(D(),P("div",fa,[(D(!0),P(K,null,H(G(l).dashboard.apiTagTotal,((a,t)=>(D(),V(u,{key:t},{default:L((()=>[z(w(t)+" "+w(a),1)])),_:2},1024)))),128))])):(D(),P("div",ma,[b(p,{image:o.simpleImage,description:G(e)("common.notdata")},null,8,["image","description"])]))])),_:1})])),_:1})])),_:1}),b(v,{gutter:16},{default:L((()=>[b(c,{xs:24,sm:24,md:8,lg:8,xl:8},{default:L((()=>[b(d,{class:"mb-sm"},{title:L((()=>[z(w(G(e)("common.author")),1)])),default:L((()=>[Object.keys(G(l).dashboard.apiAuthorTotal).length?(D(),P("div",ga,[R("ul",null,[(D(!0),P(K,null,H(G(l).dashboard.apiAuthorTotal,((a,t)=>(D(),P("li",{key:t},[R("h4",null,w(t),1),b(f,{format:()=>a,percent:parseInt(a/G(l).dashboard.apiCount*100+"")},null,8,["format","percent"])])))),128))])])):(D(),P("div",ba,[b(p,{image:o.simpleImage,description:G(e)("common.notdata")},null,8,["image","description"])]))])),_:1})])),_:1}),b(c,{xs:24,sm:24,md:16,lg:16,xl:16},{default:L((()=>[b(d,{class:"mb-sm",bodyStyle:{padding:"10px"}},{title:L((()=>[z(w(G(e)("common.group")),1)])),default:L((()=>[R("div",null,[b(m,{columns:o.groupColumns,pagination:!1,size:"small","data-source":o.groupData,locale:{emptyText:G(e)("common.notdata")},rowKey:"name"},null,8,["columns","data-source","locale"])])])),_:1})])),_:1})])),_:1}),!1!==G(n).feConfig.SHOW_VERSION?(D(),P("div",ya,"Version："+w(G(t)),1)):B("",!0)])])}}}))));var Ca,xa=k(ha,[["__scopeId","data-v-3d449da3"]]);export{xa as default};