var e=Object.defineProperty,t=(t,n,r)=>(((t,n,r)=>{n in t?e(t,n,{enumerable:!0,configurable:!0,writable:!0,value:r}):t[n]=r})(t,"symbol"!=typeof n?n+"":n,r),r);import{bb as n}from"./index.9c98a0a7.js";
/*!-----------------------------------------------------------------------------
 * Copyright (c) Microsoft Corporation. All rights reserved.
 * Version: 0.33.0(4b1abad427e58dbedc1215d99a0902ffc885fcd4)
 * Released under the MIT license
 * https://github.com/microsoft/monaco-editor/blob/main/LICENSE.txt
 *-----------------------------------------------------------------------------*/var r=Object.defineProperty,i=Object.getOwnPropertyDescriptor,o=Object.getOwnPropertyNames,a=Object.prototype.hasOwnProperty,s={};((e,t,n,s)=>{if(t&&"object"==typeof t||"function"==typeof t)for(let c of o(t))a.call(e,c)||!n&&"default"===c||r(e,c,{get:()=>t[c],enumerable:!(s=i(t,c))||s.enumerable})})(s,n);var c,u,d,g,l,h,f,p,m,v,_,b,k,w,C,y,E,x,A,I,S,T,R,j,D,P,M,L,F,O,N,W,U,V,K,z,H,q,X,B,$,Q,G,J,Y,Z,ee,te,ne,re=class{constructor(e){t(this,"_defaults"),t(this,"_idleCheckInterval"),t(this,"_lastUsedTime"),t(this,"_configChangeListener"),t(this,"_worker"),t(this,"_client"),this._defaults=e,this._worker=null,this._client=null,this._idleCheckInterval=window.setInterval((()=>this._checkIfIdle()),3e4),this._lastUsedTime=0,this._configChangeListener=this._defaults.onDidChange((()=>this._stopWorker()))}_stopWorker(){this._worker&&(this._worker.dispose(),this._worker=null),this._client=null}dispose(){clearInterval(this._idleCheckInterval),this._configChangeListener.dispose(),this._stopWorker()}_checkIfIdle(){if(!this._worker)return;Date.now()-this._lastUsedTime>12e4&&this._stopWorker()}_getClient(){return this._lastUsedTime=Date.now(),this._client||(this._worker=s.editor.createWebWorker({moduleId:"vs/language/json/jsonWorker",label:this._defaults.languageId,createData:{languageSettings:this._defaults.diagnosticsOptions,languageId:this._defaults.languageId,enableSchemaRequest:this._defaults.diagnosticsOptions.enableSchemaRequest}}),this._client=this._worker.getProxy()),this._client}getLanguageServiceWorker(...e){let t;return this._getClient().then((e=>{t=e})).then((t=>{if(this._worker)return this._worker.withSyncedResources(e)})).then((e=>t))}};(u=c||(c={})).MIN_VALUE=-2147483648,u.MAX_VALUE=2147483647,(g=d||(d={})).MIN_VALUE=0,g.MAX_VALUE=2147483647,(h=l||(l={})).create=function(e,t){return e===Number.MAX_VALUE&&(e=d.MAX_VALUE),t===Number.MAX_VALUE&&(t=d.MAX_VALUE),{line:e,character:t}},h.is=function(e){var t=e;return rt.objectLiteral(t)&&rt.uinteger(t.line)&&rt.uinteger(t.character)},(p=f||(f={})).create=function(e,t,n,r){if(rt.uinteger(e)&&rt.uinteger(t)&&rt.uinteger(n)&&rt.uinteger(r))return{start:l.create(e,t),end:l.create(n,r)};if(l.is(e)&&l.is(t))return{start:e,end:t};throw new Error("Range#create called with invalid arguments["+e+", "+t+", "+n+", "+r+"]")},p.is=function(e){var t=e;return rt.objectLiteral(t)&&l.is(t.start)&&l.is(t.end)},(v=m||(m={})).create=function(e,t){return{uri:e,range:t}},v.is=function(e){var t=e;return rt.defined(t)&&f.is(t.range)&&(rt.string(t.uri)||rt.undefined(t.uri))},(b=_||(_={})).create=function(e,t,n,r){return{targetUri:e,targetRange:t,targetSelectionRange:n,originSelectionRange:r}},b.is=function(e){var t=e;return rt.defined(t)&&f.is(t.targetRange)&&rt.string(t.targetUri)&&(f.is(t.targetSelectionRange)||rt.undefined(t.targetSelectionRange))&&(f.is(t.originSelectionRange)||rt.undefined(t.originSelectionRange))},(w=k||(k={})).create=function(e,t,n,r){return{red:e,green:t,blue:n,alpha:r}},w.is=function(e){var t=e;return rt.numberRange(t.red,0,1)&&rt.numberRange(t.green,0,1)&&rt.numberRange(t.blue,0,1)&&rt.numberRange(t.alpha,0,1)},(y=C||(C={})).create=function(e,t){return{range:e,color:t}},y.is=function(e){var t=e;return f.is(t.range)&&k.is(t.color)},(x=E||(E={})).create=function(e,t,n){return{label:e,textEdit:t,additionalTextEdits:n}},x.is=function(e){var t=e;return rt.string(t.label)&&(rt.undefined(t.textEdit)||V.is(t))&&(rt.undefined(t.additionalTextEdits)||rt.typedArray(t.additionalTextEdits,V.is))},(I=A||(A={})).Comment="comment",I.Imports="imports",I.Region="region",(T=S||(S={})).create=function(e,t,n,r,i){var o={startLine:e,endLine:t};return rt.defined(n)&&(o.startCharacter=n),rt.defined(r)&&(o.endCharacter=r),rt.defined(i)&&(o.kind=i),o},T.is=function(e){var t=e;return rt.uinteger(t.startLine)&&rt.uinteger(t.startLine)&&(rt.undefined(t.startCharacter)||rt.uinteger(t.startCharacter))&&(rt.undefined(t.endCharacter)||rt.uinteger(t.endCharacter))&&(rt.undefined(t.kind)||rt.string(t.kind))},(j=R||(R={})).create=function(e,t){return{location:e,message:t}},j.is=function(e){var t=e;return rt.defined(t)&&m.is(t.location)&&rt.string(t.message)},(P=D||(D={})).Error=1,P.Warning=2,P.Information=3,P.Hint=4,(L=M||(M={})).Unnecessary=1,L.Deprecated=2,(F||(F={})).is=function(e){var t=e;return null!=t&&rt.string(t.href)},(N=O||(O={})).create=function(e,t,n,r,i,o){var a={range:e,message:t};return rt.defined(n)&&(a.severity=n),rt.defined(r)&&(a.code=r),rt.defined(i)&&(a.source=i),rt.defined(o)&&(a.relatedInformation=o),a},N.is=function(e){var t,n=e;return rt.defined(n)&&f.is(n.range)&&rt.string(n.message)&&(rt.number(n.severity)||rt.undefined(n.severity))&&(rt.integer(n.code)||rt.string(n.code)||rt.undefined(n.code))&&(rt.undefined(n.codeDescription)||rt.string(null===(t=n.codeDescription)||void 0===t?void 0:t.href))&&(rt.string(n.source)||rt.undefined(n.source))&&(rt.undefined(n.relatedInformation)||rt.typedArray(n.relatedInformation,R.is))},(U=W||(W={})).create=function(e,t){for(var n=[],r=2;r<arguments.length;r++)n[r-2]=arguments[r];var i={title:e,command:t};return rt.defined(n)&&n.length>0&&(i.arguments=n),i},U.is=function(e){var t=e;return rt.defined(t)&&rt.string(t.title)&&rt.string(t.command)},(K=V||(V={})).replace=function(e,t){return{range:e,newText:t}},K.insert=function(e,t){return{range:{start:e,end:e},newText:t}},K.del=function(e){return{range:e,newText:""}},K.is=function(e){var t=e;return rt.objectLiteral(t)&&rt.string(t.newText)&&f.is(t.range)},(H=z||(z={})).create=function(e,t,n){var r={label:e};return void 0!==t&&(r.needsConfirmation=t),void 0!==n&&(r.description=n),r},H.is=function(e){var t=e;return void 0!==t&&rt.objectLiteral(t)&&rt.string(t.label)&&(rt.boolean(t.needsConfirmation)||void 0===t.needsConfirmation)&&(rt.string(t.description)||void 0===t.description)},(q||(q={})).is=function(e){return"string"==typeof e},(B=X||(X={})).replace=function(e,t,n){return{range:e,newText:t,annotationId:n}},B.insert=function(e,t,n){return{range:{start:e,end:e},newText:t,annotationId:n}},B.del=function(e,t){return{range:e,newText:"",annotationId:t}},B.is=function(e){var t=e;return V.is(t)&&(z.is(t.annotationId)||q.is(t.annotationId))},(Q=$||($={})).create=function(e,t){return{textDocument:e,edits:t}},Q.is=function(e){var t=e;return rt.defined(t)&&ce.is(t.textDocument)&&Array.isArray(t.edits)},(J=G||(G={})).create=function(e,t,n){var r={kind:"create",uri:e};return void 0===t||void 0===t.overwrite&&void 0===t.ignoreIfExists||(r.options=t),void 0!==n&&(r.annotationId=n),r},J.is=function(e){var t=e;return t&&"create"===t.kind&&rt.string(t.uri)&&(void 0===t.options||(void 0===t.options.overwrite||rt.boolean(t.options.overwrite))&&(void 0===t.options.ignoreIfExists||rt.boolean(t.options.ignoreIfExists)))&&(void 0===t.annotationId||q.is(t.annotationId))},(Z=Y||(Y={})).create=function(e,t,n,r){var i={kind:"rename",oldUri:e,newUri:t};return void 0===n||void 0===n.overwrite&&void 0===n.ignoreIfExists||(i.options=n),void 0!==r&&(i.annotationId=r),i},Z.is=function(e){var t=e;return t&&"rename"===t.kind&&rt.string(t.oldUri)&&rt.string(t.newUri)&&(void 0===t.options||(void 0===t.options.overwrite||rt.boolean(t.options.overwrite))&&(void 0===t.options.ignoreIfExists||rt.boolean(t.options.ignoreIfExists)))&&(void 0===t.annotationId||q.is(t.annotationId))},(te=ee||(ee={})).create=function(e,t,n){var r={kind:"delete",uri:e};return void 0===t||void 0===t.recursive&&void 0===t.ignoreIfNotExists||(r.options=t),void 0!==n&&(r.annotationId=n),r},te.is=function(e){var t=e;return t&&"delete"===t.kind&&rt.string(t.uri)&&(void 0===t.options||(void 0===t.options.recursive||rt.boolean(t.options.recursive))&&(void 0===t.options.ignoreIfNotExists||rt.boolean(t.options.ignoreIfNotExists)))&&(void 0===t.annotationId||q.is(t.annotationId))},(ne||(ne={})).is=function(e){var t=e;return t&&(void 0!==t.changes||void 0!==t.documentChanges)&&(void 0===t.documentChanges||t.documentChanges.every((function(e){return rt.string(e.kind)?G.is(e)||Y.is(e)||ee.is(e):$.is(e)})))};var ie,oe,ae,se,ce,ue,de,ge,le,he,fe,pe,me,ve,_e,be,ke,we,Ce,ye,Ee,xe,Ae,Ie,Se,Te,Re,je,De,Pe,Me,Le,Fe,Oe,Ne,We,Ue,Ve,Ke,ze,He,qe,Xe,Be,$e,Qe,Ge,Je,Ye,Ze,et,tt=function(){function e(e,t){this.edits=e,this.changeAnnotations=t}return e.prototype.insert=function(e,t,n){var r,i;if(void 0===n?r=V.insert(e,t):q.is(n)?(i=n,r=X.insert(e,t,n)):(this.assertChangeAnnotations(this.changeAnnotations),i=this.changeAnnotations.manage(n),r=X.insert(e,t,i)),this.edits.push(r),void 0!==i)return i},e.prototype.replace=function(e,t,n){var r,i;if(void 0===n?r=V.replace(e,t):q.is(n)?(i=n,r=X.replace(e,t,n)):(this.assertChangeAnnotations(this.changeAnnotations),i=this.changeAnnotations.manage(n),r=X.replace(e,t,i)),this.edits.push(r),void 0!==i)return i},e.prototype.delete=function(e,t){var n,r;if(void 0===t?n=V.del(e):q.is(t)?(r=t,n=X.del(e,t)):(this.assertChangeAnnotations(this.changeAnnotations),r=this.changeAnnotations.manage(t),n=X.del(e,r)),this.edits.push(n),void 0!==r)return r},e.prototype.add=function(e){this.edits.push(e)},e.prototype.all=function(){return this.edits},e.prototype.clear=function(){this.edits.splice(0,this.edits.length)},e.prototype.assertChangeAnnotations=function(e){if(void 0===e)throw new Error("Text edit change is not configured to manage change annotations.")},e}(),nt=function(){function e(e){this._annotations=void 0===e?Object.create(null):e,this._counter=0,this._size=0}return e.prototype.all=function(){return this._annotations},Object.defineProperty(e.prototype,"size",{get:function(){return this._size},enumerable:!1,configurable:!0}),e.prototype.manage=function(e,t){var n;if(q.is(e)?n=e:(n=this.nextId(),t=e),void 0!==this._annotations[n])throw new Error("Id "+n+" is already in use.");if(void 0===t)throw new Error("No annotation provided for id "+n);return this._annotations[n]=t,this._size++,n},e.prototype.nextId=function(){return this._counter++,this._counter.toString()},e}();!function(){function e(e){var t=this;this._textEditChanges=Object.create(null),void 0!==e?(this._workspaceEdit=e,e.documentChanges?(this._changeAnnotations=new nt(e.changeAnnotations),e.changeAnnotations=this._changeAnnotations.all(),e.documentChanges.forEach((function(e){if($.is(e)){var n=new tt(e.edits,t._changeAnnotations);t._textEditChanges[e.textDocument.uri]=n}}))):e.changes&&Object.keys(e.changes).forEach((function(n){var r=new tt(e.changes[n]);t._textEditChanges[n]=r}))):this._workspaceEdit={}}Object.defineProperty(e.prototype,"edit",{get:function(){return this.initDocumentChanges(),void 0!==this._changeAnnotations&&(0===this._changeAnnotations.size?this._workspaceEdit.changeAnnotations=void 0:this._workspaceEdit.changeAnnotations=this._changeAnnotations.all()),this._workspaceEdit},enumerable:!1,configurable:!0}),e.prototype.getTextEditChange=function(e){if(ce.is(e)){if(this.initDocumentChanges(),void 0===this._workspaceEdit.documentChanges)throw new Error("Workspace edit is not configured for document changes.");var t={uri:e.uri,version:e.version};if(!(r=this._textEditChanges[t.uri])){var n={textDocument:t,edits:i=[]};this._workspaceEdit.documentChanges.push(n),r=new tt(i,this._changeAnnotations),this._textEditChanges[t.uri]=r}return r}if(this.initChanges(),void 0===this._workspaceEdit.changes)throw new Error("Workspace edit is not configured for normal text edit changes.");var r;if(!(r=this._textEditChanges[e])){var i=[];this._workspaceEdit.changes[e]=i,r=new tt(i),this._textEditChanges[e]=r}return r},e.prototype.initDocumentChanges=function(){void 0===this._workspaceEdit.documentChanges&&void 0===this._workspaceEdit.changes&&(this._changeAnnotations=new nt,this._workspaceEdit.documentChanges=[],this._workspaceEdit.changeAnnotations=this._changeAnnotations.all())},e.prototype.initChanges=function(){void 0===this._workspaceEdit.documentChanges&&void 0===this._workspaceEdit.changes&&(this._workspaceEdit.changes=Object.create(null))},e.prototype.createFile=function(e,t,n){if(this.initDocumentChanges(),void 0===this._workspaceEdit.documentChanges)throw new Error("Workspace edit is not configured for document changes.");var r,i,o;if(z.is(t)||q.is(t)?r=t:n=t,void 0===r?i=G.create(e,n):(o=q.is(r)?r:this._changeAnnotations.manage(r),i=G.create(e,n,o)),this._workspaceEdit.documentChanges.push(i),void 0!==o)return o},e.prototype.renameFile=function(e,t,n,r){if(this.initDocumentChanges(),void 0===this._workspaceEdit.documentChanges)throw new Error("Workspace edit is not configured for document changes.");var i,o,a;if(z.is(n)||q.is(n)?i=n:r=n,void 0===i?o=Y.create(e,t,r):(a=q.is(i)?i:this._changeAnnotations.manage(i),o=Y.create(e,t,r,a)),this._workspaceEdit.documentChanges.push(o),void 0!==a)return a},e.prototype.deleteFile=function(e,t,n){if(this.initDocumentChanges(),void 0===this._workspaceEdit.documentChanges)throw new Error("Workspace edit is not configured for document changes.");var r,i,o;if(z.is(t)||q.is(t)?r=t:n=t,void 0===r?i=ee.create(e,n):(o=q.is(r)?r:this._changeAnnotations.manage(r),i=ee.create(e,n,o)),this._workspaceEdit.documentChanges.push(i),void 0!==o)return o}}(),(oe=ie||(ie={})).create=function(e){return{uri:e}},oe.is=function(e){var t=e;return rt.defined(t)&&rt.string(t.uri)},(se=ae||(ae={})).create=function(e,t){return{uri:e,version:t}},se.is=function(e){var t=e;return rt.defined(t)&&rt.string(t.uri)&&rt.integer(t.version)},(ue=ce||(ce={})).create=function(e,t){return{uri:e,version:t}},ue.is=function(e){var t=e;return rt.defined(t)&&rt.string(t.uri)&&(null===t.version||rt.integer(t.version))},(ge=de||(de={})).create=function(e,t,n,r){return{uri:e,languageId:t,version:n,text:r}},ge.is=function(e){var t=e;return rt.defined(t)&&rt.string(t.uri)&&rt.string(t.languageId)&&rt.integer(t.version)&&rt.string(t.text)},(he=le||(le={})).PlainText="plaintext",he.Markdown="markdown",function(e){e.is=function(t){var n=t;return n===e.PlainText||n===e.Markdown}}(le||(le={})),(fe||(fe={})).is=function(e){var t=e;return rt.objectLiteral(e)&&le.is(t.kind)&&rt.string(t.value)},(me=pe||(pe={})).Text=1,me.Method=2,me.Function=3,me.Constructor=4,me.Field=5,me.Variable=6,me.Class=7,me.Interface=8,me.Module=9,me.Property=10,me.Unit=11,me.Value=12,me.Enum=13,me.Keyword=14,me.Snippet=15,me.Color=16,me.File=17,me.Reference=18,me.Folder=19,me.EnumMember=20,me.Constant=21,me.Struct=22,me.Event=23,me.Operator=24,me.TypeParameter=25,(_e=ve||(ve={})).PlainText=1,_e.Snippet=2,(be||(be={})).Deprecated=1,(we=ke||(ke={})).create=function(e,t,n){return{newText:e,insert:t,replace:n}},we.is=function(e){var t=e;return t&&rt.string(t.newText)&&f.is(t.insert)&&f.is(t.replace)},(ye=Ce||(Ce={})).asIs=1,ye.adjustIndentation=2,(Ee||(Ee={})).create=function(e){return{label:e}},(xe||(xe={})).create=function(e,t){return{items:e||[],isIncomplete:!!t}},(Ie=Ae||(Ae={})).fromPlainText=function(e){return e.replace(/[\\`*_{}[\]()#+\-.!]/g,"\\$&")},Ie.is=function(e){var t=e;return rt.string(t)||rt.objectLiteral(t)&&rt.string(t.language)&&rt.string(t.value)},(Se||(Se={})).is=function(e){var t=e;return!!t&&rt.objectLiteral(t)&&(fe.is(t.contents)||Ae.is(t.contents)||rt.typedArray(t.contents,Ae.is))&&(void 0===e.range||f.is(e.range))},(Te||(Te={})).create=function(e,t){return t?{label:e,documentation:t}:{label:e}},(Re||(Re={})).create=function(e,t){for(var n=[],r=2;r<arguments.length;r++)n[r-2]=arguments[r];var i={label:e};return rt.defined(t)&&(i.documentation=t),rt.defined(n)?i.parameters=n:i.parameters=[],i},(De=je||(je={})).Text=1,De.Read=2,De.Write=3,(Pe||(Pe={})).create=function(e,t){var n={range:e};return rt.number(t)&&(n.kind=t),n},(Le=Me||(Me={})).File=1,Le.Module=2,Le.Namespace=3,Le.Package=4,Le.Class=5,Le.Method=6,Le.Property=7,Le.Field=8,Le.Constructor=9,Le.Enum=10,Le.Interface=11,Le.Function=12,Le.Variable=13,Le.Constant=14,Le.String=15,Le.Number=16,Le.Boolean=17,Le.Array=18,Le.Object=19,Le.Key=20,Le.Null=21,Le.EnumMember=22,Le.Struct=23,Le.Event=24,Le.Operator=25,Le.TypeParameter=26,(Fe||(Fe={})).Deprecated=1,(Oe||(Oe={})).create=function(e,t,n,r,i){var o={name:e,kind:t,location:{uri:r,range:n}};return i&&(o.containerName=i),o},(We=Ne||(Ne={})).create=function(e,t,n,r,i,o){var a={name:e,detail:t,kind:n,range:r,selectionRange:i};return void 0!==o&&(a.children=o),a},We.is=function(e){var t=e;return t&&rt.string(t.name)&&rt.number(t.kind)&&f.is(t.range)&&f.is(t.selectionRange)&&(void 0===t.detail||rt.string(t.detail))&&(void 0===t.deprecated||rt.boolean(t.deprecated))&&(void 0===t.children||Array.isArray(t.children))&&(void 0===t.tags||Array.isArray(t.tags))},(Ve=Ue||(Ue={})).Empty="",Ve.QuickFix="quickfix",Ve.Refactor="refactor",Ve.RefactorExtract="refactor.extract",Ve.RefactorInline="refactor.inline",Ve.RefactorRewrite="refactor.rewrite",Ve.Source="source",Ve.SourceOrganizeImports="source.organizeImports",Ve.SourceFixAll="source.fixAll",(ze=Ke||(Ke={})).create=function(e,t){var n={diagnostics:e};return null!=t&&(n.only=t),n},ze.is=function(e){var t=e;return rt.defined(t)&&rt.typedArray(t.diagnostics,O.is)&&(void 0===t.only||rt.typedArray(t.only,rt.string))},(qe=He||(He={})).create=function(e,t,n){var r={title:e},i=!0;return"string"==typeof t?(i=!1,r.kind=t):W.is(t)?r.command=t:r.edit=t,i&&void 0!==n&&(r.kind=n),r},qe.is=function(e){var t=e;return t&&rt.string(t.title)&&(void 0===t.diagnostics||rt.typedArray(t.diagnostics,O.is))&&(void 0===t.kind||rt.string(t.kind))&&(void 0!==t.edit||void 0!==t.command)&&(void 0===t.command||W.is(t.command))&&(void 0===t.isPreferred||rt.boolean(t.isPreferred))&&(void 0===t.edit||ne.is(t.edit))},(Be=Xe||(Xe={})).create=function(e,t){var n={range:e};return rt.defined(t)&&(n.data=t),n},Be.is=function(e){var t=e;return rt.defined(t)&&f.is(t.range)&&(rt.undefined(t.command)||W.is(t.command))},(Qe=$e||($e={})).create=function(e,t){return{tabSize:e,insertSpaces:t}},Qe.is=function(e){var t=e;return rt.defined(t)&&rt.uinteger(t.tabSize)&&rt.boolean(t.insertSpaces)},(Je=Ge||(Ge={})).create=function(e,t,n){return{range:e,target:t,data:n}},Je.is=function(e){var t=e;return rt.defined(t)&&f.is(t.range)&&(rt.undefined(t.target)||rt.string(t.target))},(Ze=Ye||(Ye={})).create=function(e,t){return{range:e,parent:t}},Ze.is=function(e){var t=e;return void 0!==t&&f.is(t.range)&&(void 0===t.parent||Ze.is(t.parent))},function(e){function t(e,n){if(e.length<=1)return e;var r=e.length/2|0,i=e.slice(0,r),o=e.slice(r);t(i,n),t(o,n);for(var a=0,s=0,c=0;a<i.length&&s<o.length;){var u=n(i[a],o[s]);e[c++]=u<=0?i[a++]:o[s++]}for(;a<i.length;)e[c++]=i[a++];for(;s<o.length;)e[c++]=o[s++];return e}e.create=function(e,t,n,r){return new at(e,t,n,r)},e.is=function(e){var t=e;return!!(rt.defined(t)&&rt.string(t.uri)&&(rt.undefined(t.languageId)||rt.string(t.languageId))&&rt.uinteger(t.lineCount)&&rt.func(t.getText)&&rt.func(t.positionAt)&&rt.func(t.offsetAt))},e.applyEdits=function(e,n){for(var r=e.getText(),i=t(n,(function(e,t){var n=e.range.start.line-t.range.start.line;return 0===n?e.range.start.character-t.range.start.character:n})),o=r.length,a=i.length-1;a>=0;a--){var s=i[a],c=e.offsetAt(s.range.start),u=e.offsetAt(s.range.end);if(!(u<=o))throw new Error("Overlapping edit");r=r.substring(0,c)+s.newText+r.substring(u,r.length),o=c}return r}}(et||(et={}));var rt,it,ot,at=function(){function e(e,t,n,r){this._uri=e,this._languageId=t,this._version=n,this._content=r,this._lineOffsets=void 0}return Object.defineProperty(e.prototype,"uri",{get:function(){return this._uri},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"languageId",{get:function(){return this._languageId},enumerable:!1,configurable:!0}),Object.defineProperty(e.prototype,"version",{get:function(){return this._version},enumerable:!1,configurable:!0}),e.prototype.getText=function(e){if(e){var t=this.offsetAt(e.start),n=this.offsetAt(e.end);return this._content.substring(t,n)}return this._content},e.prototype.update=function(e,t){this._content=e.text,this._version=t,this._lineOffsets=void 0},e.prototype.getLineOffsets=function(){if(void 0===this._lineOffsets){for(var e=[],t=this._content,n=!0,r=0;r<t.length;r++){n&&(e.push(r),n=!1);var i=t.charAt(r);n="\r"===i||"\n"===i,"\r"===i&&r+1<t.length&&"\n"===t.charAt(r+1)&&r++}n&&t.length>0&&e.push(t.length),this._lineOffsets=e}return this._lineOffsets},e.prototype.positionAt=function(e){e=Math.max(Math.min(e,this._content.length),0);var t=this.getLineOffsets(),n=0,r=t.length;if(0===r)return l.create(0,e);for(;n<r;){var i=Math.floor((n+r)/2);t[i]>e?r=i:n=i+1}var o=n-1;return l.create(o,e-t[o])},e.prototype.offsetAt=function(e){var t=this.getLineOffsets();if(e.line>=t.length)return this._content.length;if(e.line<0)return 0;var n=t[e.line],r=e.line+1<t.length?t[e.line+1]:this._content.length;return Math.max(Math.min(n+e.character,r),n)},Object.defineProperty(e.prototype,"lineCount",{get:function(){return this.getLineOffsets().length},enumerable:!1,configurable:!0}),e}();it=rt||(rt={}),ot=Object.prototype.toString,it.defined=function(e){return void 0!==e},it.undefined=function(e){return void 0===e},it.boolean=function(e){return!0===e||!1===e},it.string=function(e){return"[object String]"===ot.call(e)},it.number=function(e){return"[object Number]"===ot.call(e)},it.numberRange=function(e,t,n){return"[object Number]"===ot.call(e)&&t<=e&&e<=n},it.integer=function(e){return"[object Number]"===ot.call(e)&&-2147483648<=e&&e<=2147483647},it.uinteger=function(e){return"[object Number]"===ot.call(e)&&0<=e&&e<=2147483647},it.func=function(e){return"[object Function]"===ot.call(e)},it.objectLiteral=function(e){return null!==e&&"object"==typeof e},it.typedArray=function(e,t){return Array.isArray(e)&&e.every(t)};var st=class{constructor(e,n,r){t(this,"_disposables",[]),t(this,"_listener",Object.create(null)),this._languageId=e,this._worker=n;const i=e=>{let t,n=e.getLanguageId();n===this._languageId&&(this._listener[e.uri.toString()]=e.onDidChangeContent((()=>{window.clearTimeout(t),t=window.setTimeout((()=>this._doValidate(e.uri,n)),500)})),this._doValidate(e.uri,n))},o=e=>{s.editor.setModelMarkers(e,this._languageId,[]);let t=e.uri.toString(),n=this._listener[t];n&&(n.dispose(),delete this._listener[t])};this._disposables.push(s.editor.onDidCreateModel(i)),this._disposables.push(s.editor.onWillDisposeModel(o)),this._disposables.push(s.editor.onDidChangeModelLanguage((e=>{o(e.model),i(e.model)}))),this._disposables.push(r((e=>{s.editor.getModels().forEach((e=>{e.getLanguageId()===this._languageId&&(o(e),i(e))}))}))),this._disposables.push({dispose:()=>{s.editor.getModels().forEach(o);for(let e in this._listener)this._listener[e].dispose()}}),s.editor.getModels().forEach(i)}dispose(){this._disposables.forEach((e=>e&&e.dispose())),this._disposables.length=0}_doValidate(e,t){this._worker(e).then((t=>t.doValidation(e.toString()))).then((n=>{const r=n.map((e=>function(e,t){let n="number"==typeof t.code?String(t.code):t.code;return{severity:ct(t.severity),startLineNumber:t.range.start.line+1,startColumn:t.range.start.character+1,endLineNumber:t.range.end.line+1,endColumn:t.range.end.character+1,message:t.message,code:n,source:t.source}}(0,e)));let i=s.editor.getModel(e);i&&i.getLanguageId()===t&&s.editor.setModelMarkers(i,t,r)})).then(void 0,(e=>{}))}};function ct(e){switch(e){case D.Error:return s.MarkerSeverity.Error;case D.Warning:return s.MarkerSeverity.Warning;case D.Information:return s.MarkerSeverity.Info;case D.Hint:return s.MarkerSeverity.Hint;default:return s.MarkerSeverity.Info}}var ut=class{constructor(e,t){this._worker=e,this._triggerCharacters=t}get triggerCharacters(){return this._triggerCharacters}provideCompletionItems(e,t,n,r){const i=e.uri;return this._worker(i).then((e=>e.doComplete(i.toString(),dt(t)))).then((n=>{if(!n)return;const r=e.getWordUntilPosition(t),i=new s.Range(t.lineNumber,r.startColumn,t.lineNumber,r.endColumn),o=n.items.map((e=>{const t={label:e.label,insertText:e.insertText||e.label,sortText:e.sortText,filterText:e.filterText,documentation:e.documentation,detail:e.detail,command:(n=e.command,n&&"editor.action.triggerSuggest"===n.command?{id:n.command,title:n.title,arguments:n.arguments}:void 0),range:i,kind:ht(e.kind)};var n,r;return e.textEdit&&(void 0!==(r=e.textEdit).insert&&void 0!==r.replace?t.range={insert:lt(e.textEdit.insert),replace:lt(e.textEdit.replace)}:t.range=lt(e.textEdit.range),t.insertText=e.textEdit.newText),e.additionalTextEdits&&(t.additionalTextEdits=e.additionalTextEdits.map(ft)),e.insertTextFormat===ve.Snippet&&(t.insertTextRules=s.languages.CompletionItemInsertTextRule.InsertAsSnippet),t}));return{isIncomplete:n.isIncomplete,suggestions:o}}))}};function dt(e){if(e)return{character:e.column-1,line:e.lineNumber-1}}function gt(e){if(e)return{start:{line:e.startLineNumber-1,character:e.startColumn-1},end:{line:e.endLineNumber-1,character:e.endColumn-1}}}function lt(e){if(e)return new s.Range(e.start.line+1,e.start.character+1,e.end.line+1,e.end.character+1)}function ht(e){const t=s.languages.CompletionItemKind;switch(e){case pe.Text:return t.Text;case pe.Method:return t.Method;case pe.Function:return t.Function;case pe.Constructor:return t.Constructor;case pe.Field:return t.Field;case pe.Variable:return t.Variable;case pe.Class:return t.Class;case pe.Interface:return t.Interface;case pe.Module:return t.Module;case pe.Property:return t.Property;case pe.Unit:return t.Unit;case pe.Value:return t.Value;case pe.Enum:return t.Enum;case pe.Keyword:return t.Keyword;case pe.Snippet:return t.Snippet;case pe.Color:return t.Color;case pe.File:return t.File;case pe.Reference:return t.Reference}return t.Property}function ft(e){if(e)return{range:lt(e.range),text:e.newText}}var pt=class{constructor(e){this._worker=e}provideHover(e,t,n){let r=e.uri;return this._worker(r).then((e=>e.doHover(r.toString(),dt(t)))).then((e=>{if(e)return{range:lt(e.range),contents:vt(e.contents)}}))}};function mt(e){return"string"==typeof e?{value:e}:(t=e)&&"object"==typeof t&&"string"==typeof t.kind?"plaintext"===e.kind?{value:e.value.replace(/[\\`*_{}[\]()#+\-.!]/g,"\\$&")}:{value:e.value}:{value:"```"+e.language+"\n"+e.value+"\n```\n"};var t}function vt(e){if(e)return Array.isArray(e)?e.map(mt):[mt(e)]}var _t=class{constructor(e){this._worker=e}provideDocumentHighlights(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.findDocumentHighlights(r.toString(),dt(t)))).then((e=>{if(e)return e.map((e=>({range:lt(e.range),kind:bt(e.kind)})))}))}};function bt(e){switch(e){case je.Read:return s.languages.DocumentHighlightKind.Read;case je.Write:return s.languages.DocumentHighlightKind.Write;case je.Text:return s.languages.DocumentHighlightKind.Text}return s.languages.DocumentHighlightKind.Text}var kt=class{constructor(e){this._worker=e}provideDefinition(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.findDefinition(r.toString(),dt(t)))).then((e=>{if(e)return[wt(e)]}))}};function wt(e){return{uri:s.Uri.parse(e.uri),range:lt(e.range)}}var Ct=class{constructor(e){this._worker=e}provideReferences(e,t,n,r){const i=e.uri;return this._worker(i).then((e=>e.findReferences(i.toString(),dt(t)))).then((e=>{if(e)return e.map(wt)}))}},yt=class{constructor(e){this._worker=e}provideRenameEdits(e,t,n,r){const i=e.uri;return this._worker(i).then((e=>e.doRename(i.toString(),dt(t),n))).then((e=>function(e){if(!e||!e.changes)return;let t=[];for(let n in e.changes){const r=s.Uri.parse(n);for(let i of e.changes[n])t.push({resource:r,edit:{range:lt(i.range),text:i.newText}})}return{edits:t}}(e)))}};var Et=class{constructor(e){this._worker=e}provideDocumentSymbols(e,t){const n=e.uri;return this._worker(n).then((e=>e.findDocumentSymbols(n.toString()))).then((e=>{if(e)return e.map((e=>({name:e.name,detail:"",containerName:e.containerName,kind:xt(e.kind),range:lt(e.location.range),selectionRange:lt(e.location.range),tags:[]})))}))}};function xt(e){let t=s.languages.SymbolKind;switch(e){case Me.File:return t.Array;case Me.Module:return t.Module;case Me.Namespace:return t.Namespace;case Me.Package:return t.Package;case Me.Class:return t.Class;case Me.Method:return t.Method;case Me.Property:return t.Property;case Me.Field:return t.Field;case Me.Constructor:return t.Constructor;case Me.Enum:return t.Enum;case Me.Interface:return t.Interface;case Me.Function:return t.Function;case Me.Variable:return t.Variable;case Me.Constant:return t.Constant;case Me.String:return t.String;case Me.Number:return t.Number;case Me.Boolean:return t.Boolean;case Me.Array:return t.Array}return t.Function}var At=class{constructor(e){this._worker=e}provideLinks(e,t){const n=e.uri;return this._worker(n).then((e=>e.findDocumentLinks(n.toString()))).then((e=>{if(e)return{links:e.map((e=>({range:lt(e.range),url:e.target})))}}))}},It=class{constructor(e){this._worker=e}provideDocumentFormattingEdits(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.format(r.toString(),null,Tt(t)).then((e=>{if(e&&0!==e.length)return e.map(ft)}))))}},St=class{constructor(e){this._worker=e}provideDocumentRangeFormattingEdits(e,t,n,r){const i=e.uri;return this._worker(i).then((e=>e.format(i.toString(),gt(t),Tt(n)).then((e=>{if(e&&0!==e.length)return e.map(ft)}))))}};function Tt(e){return{tabSize:e.tabSize,insertSpaces:e.insertSpaces}}var Rt=class{constructor(e){this._worker=e}provideDocumentColors(e,t){const n=e.uri;return this._worker(n).then((e=>e.findDocumentColors(n.toString()))).then((e=>{if(e)return e.map((e=>({color:e.color,range:lt(e.range)})))}))}provideColorPresentations(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.getColorPresentations(r.toString(),t.color,gt(t.range)))).then((e=>{if(e)return e.map((e=>{let t={label:e.label};return e.textEdit&&(t.textEdit=ft(e.textEdit)),e.additionalTextEdits&&(t.additionalTextEdits=e.additionalTextEdits.map(ft)),t}))}))}},jt=class{constructor(e){this._worker=e}provideFoldingRanges(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.getFoldingRanges(r.toString(),t))).then((e=>{if(e)return e.map((e=>{const t={start:e.startLine+1,end:e.endLine+1};return void 0!==e.kind&&(t.kind=function(e){switch(e){case A.Comment:return s.languages.FoldingRangeKind.Comment;case A.Imports:return s.languages.FoldingRangeKind.Imports;case A.Region:return s.languages.FoldingRangeKind.Region}return}(e.kind)),t}))}))}};var Dt,Pt=class{constructor(e){this._worker=e}provideSelectionRanges(e,t,n){const r=e.uri;return this._worker(r).then((e=>e.getSelectionRanges(r.toString(),t.map(dt)))).then((e=>{if(e)return e.map((e=>{const t=[];for(;e;)t.push({range:lt(e.range)}),e=e.parent;return t}))}))}};function Mt(e){return 32===e||9===e||11===e||12===e||160===e||5760===e||e>=8192&&e<=8203||8239===e||8287===e||12288===e||65279===e}function Lt(e){return 10===e||13===e||8232===e||8233===e}function Ft(e){return e>=48&&e<=57}(Dt||(Dt={})).DEFAULT={allowTrailingComma:!1};var Ot=function(e,t){void 0===t&&(t=!1);var n=e.length,r=0,i="",o=0,a=16,s=0,c=0,u=0,d=0,g=0;function l(t,n){for(var i=0,o=0;i<t||!n;){var a=e.charCodeAt(r);if(a>=48&&a<=57)o=16*o+a-48;else if(a>=65&&a<=70)o=16*o+a-65+10;else{if(!(a>=97&&a<=102))break;o=16*o+a-97+10}r++,i++}return i<t&&(o=-1),o}function h(){if(i="",g=0,o=r,c=s,d=u,r>=n)return o=n,a=17;var t=e.charCodeAt(r);if(Mt(t)){do{r++,i+=String.fromCharCode(t),t=e.charCodeAt(r)}while(Mt(t));return a=15}if(Lt(t))return r++,i+=String.fromCharCode(t),13===t&&10===e.charCodeAt(r)&&(r++,i+="\n"),s++,u=r,a=14;switch(t){case 123:return r++,a=1;case 125:return r++,a=2;case 91:return r++,a=3;case 93:return r++,a=4;case 58:return r++,a=6;case 44:return r++,a=5;case 34:return r++,i=function(){for(var t="",i=r;;){if(r>=n){t+=e.substring(i,r),g=2;break}var o=e.charCodeAt(r);if(34===o){t+=e.substring(i,r),r++;break}if(92!==o){if(o>=0&&o<=31){if(Lt(o)){t+=e.substring(i,r),g=2;break}g=6}r++}else{if(t+=e.substring(i,r),++r>=n){g=2;break}switch(e.charCodeAt(r++)){case 34:t+='"';break;case 92:t+="\\";break;case 47:t+="/";break;case 98:t+="\b";break;case 102:t+="\f";break;case 110:t+="\n";break;case 114:t+="\r";break;case 116:t+="\t";break;case 117:var a=l(4,!0);a>=0?t+=String.fromCharCode(a):g=4;break;default:g=5}i=r}}return t}(),a=10;case 47:var h=r-1;if(47===e.charCodeAt(r+1)){for(r+=2;r<n&&!Lt(e.charCodeAt(r));)r++;return i=e.substring(h,r),a=12}if(42===e.charCodeAt(r+1)){r+=2;for(var p=n-1,m=!1;r<p;){var v=e.charCodeAt(r);if(42===v&&47===e.charCodeAt(r+1)){r+=2,m=!0;break}r++,Lt(v)&&(13===v&&10===e.charCodeAt(r)&&r++,s++,u=r)}return m||(r++,g=1),i=e.substring(h,r),a=13}return i+=String.fromCharCode(t),r++,a=16;case 45:if(i+=String.fromCharCode(t),++r===n||!Ft(e.charCodeAt(r)))return a=16;case 48:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:return i+=function(){var t=r;if(48===e.charCodeAt(r))r++;else for(r++;r<e.length&&Ft(e.charCodeAt(r));)r++;if(r<e.length&&46===e.charCodeAt(r)){if(!(++r<e.length&&Ft(e.charCodeAt(r))))return g=3,e.substring(t,r);for(r++;r<e.length&&Ft(e.charCodeAt(r));)r++}var n=r;if(r<e.length&&(69===e.charCodeAt(r)||101===e.charCodeAt(r)))if((++r<e.length&&43===e.charCodeAt(r)||45===e.charCodeAt(r))&&r++,r<e.length&&Ft(e.charCodeAt(r))){for(r++;r<e.length&&Ft(e.charCodeAt(r));)r++;n=r}else g=3;return e.substring(t,n)}(),a=11;default:for(;r<n&&f(t);)r++,t=e.charCodeAt(r);if(o!==r){switch(i=e.substring(o,r)){case"true":return a=8;case"false":return a=9;case"null":return a=7}return a=16}return i+=String.fromCharCode(t),r++,a=16}}function f(e){if(Mt(e)||Lt(e))return!1;switch(e){case 125:case 93:case 123:case 91:case 34:case 58:case 44:case 47:return!1}return!0}return{setPosition:function(e){r=e,i="",o=0,a=16,g=0},getPosition:function(){return r},scan:t?function(){var e;do{e=h()}while(e>=12&&e<=15);return e}:h,getToken:function(){return a},getTokenValue:function(){return i},getTokenOffset:function(){return o},getTokenLength:function(){return r-o},getTokenStartLine:function(){return c},getTokenStartCharacter:function(){return o-d},getTokenError:function(){return g}}};function Nt(e){return{getInitialState:()=>new Ut(null,null,!1,null),tokenize:(t,n)=>function(e,t,n,r=0){let i=0,o=!1;switch(n.scanError){case 2:t='"'+t,i=1;break;case 1:t="/*"+t,i=2}const a=Ot(t);let s=n.lastWasColon,c=n.parents;const u={tokens:[],endState:n.clone()};for(;;){let d=r+a.getPosition(),g="";const l=a.scan();if(17===l)break;if(d===r+a.getPosition())throw new Error("Scanner did not advance, next 3 characters are: "+t.substr(a.getPosition(),3));switch(o&&(d-=i),o=i>0,l){case 1:c=Wt.push(c,0),g="delimiter.bracket.json",s=!1;break;case 2:c=Wt.pop(c),g="delimiter.bracket.json",s=!1;break;case 3:c=Wt.push(c,1),g="delimiter.array.json",s=!1;break;case 4:c=Wt.pop(c),g="delimiter.array.json",s=!1;break;case 6:g="delimiter.colon.json",s=!0;break;case 5:g="delimiter.comma.json",s=!1;break;case 8:case 9:case 7:g="keyword.json",s=!1;break;case 10:const e=1===(c?c.type:0);g=s||e?"string.value.json":"string.key.json",s=!1;break;case 11:g="number.json",s=!1}if(e)switch(l){case 12:g="comment.line.json";break;case 13:g="comment.block.json"}u.endState=new Ut(n.getStateData(),a.getTokenError(),s,c),u.tokens.push({startIndex:d,scopes:g})}return u}(e,t,n)}}var Wt=class{constructor(e,t){this.parent=e,this.type=t}static pop(e){return e?e.parent:null}static push(e,t){return new Wt(e,t)}static equals(e,t){if(!e&&!t)return!0;if(!e||!t)return!1;for(;e&&t;){if(e===t)return!0;if(e.type!==t.type)return!1;e=e.parent,t=t.parent}return!0}},Ut=class{constructor(e,n,r,i){t(this,"_state"),t(this,"scanError"),t(this,"lastWasColon"),t(this,"parents"),this._state=e,this.scanError=n,this.lastWasColon=r,this.parents=i}clone(){return new Ut(this._state,this.scanError,this.lastWasColon,this.parents)}equals(e){return e===this||!!(e&&e instanceof Ut)&&(this.scanError===e.scanError&&this.lastWasColon===e.lastWasColon&&Wt.equals(this.parents,e.parents))}getStateData(){return this._state}setStateData(e){this._state=e}};var Vt=class extends st{constructor(e,t,n){super(e,t,n.onDidChange),this._disposables.push(s.editor.onWillDisposeModel((e=>{this._resetSchema(e.uri)}))),this._disposables.push(s.editor.onDidChangeModelLanguage((e=>{this._resetSchema(e.model.uri)})))}_resetSchema(e){this._worker().then((t=>{t.resetSchema(e.toString())}))}};function Kt(e){const t=[],n=[],r=new re(e);t.push(r);const i=(...e)=>r.getLanguageServiceWorker(...e);function o(){const{languageId:t,modeConfiguration:r}=e;Ht(n),r.documentFormattingEdits&&n.push(s.languages.registerDocumentFormattingEditProvider(t,new It(i))),r.documentRangeFormattingEdits&&n.push(s.languages.registerDocumentRangeFormattingEditProvider(t,new St(i))),r.completionItems&&n.push(s.languages.registerCompletionItemProvider(t,new ut(i,[" ",":",'"']))),r.hovers&&n.push(s.languages.registerHoverProvider(t,new pt(i))),r.documentSymbols&&n.push(s.languages.registerDocumentSymbolProvider(t,new Et(i))),r.tokens&&n.push(s.languages.setTokensProvider(t,Nt(!0))),r.colors&&n.push(s.languages.registerColorProvider(t,new Rt(i))),r.foldingRanges&&n.push(s.languages.registerFoldingRangeProvider(t,new jt(i))),r.diagnostics&&n.push(new Vt(t,i,e)),r.selectionRanges&&n.push(s.languages.registerSelectionRangeProvider(t,new Pt(i)))}o(),t.push(s.languages.setLanguageConfiguration(e.languageId,qt));let a=e.modeConfiguration;return e.onDidChange((e=>{e.modeConfiguration!==a&&(a=e.modeConfiguration,o())})),t.push(zt(n)),zt(t)}function zt(e){return{dispose:()=>Ht(e)}}function Ht(e){for(;e.length;)e.pop().dispose()}var qt={wordPattern:/(-?\d*\.\d\w*)|([^\[\{\]\}\:\"\,\s]+)/g,comments:{lineComment:"//",blockComment:["/*","*/"]},brackets:[["{","}"],["[","]"]],autoClosingPairs:[{open:"{",close:"}",notIn:["string"]},{open:"[",close:"]",notIn:["string"]},{open:'"',close:'"',notIn:["string"]}]};export{ut as CompletionAdapter,kt as DefinitionAdapter,st as DiagnosticsAdapter,Rt as DocumentColorAdapter,It as DocumentFormattingEditProvider,_t as DocumentHighlightAdapter,At as DocumentLinkAdapter,St as DocumentRangeFormattingEditProvider,Et as DocumentSymbolAdapter,jt as FoldingRangeAdapter,pt as HoverAdapter,Ct as ReferenceAdapter,yt as RenameAdapter,Pt as SelectionRangeAdapter,re as WorkerManager,dt as fromPosition,gt as fromRange,Kt as setupMode,lt as toRange,ft as toTextEdit};
