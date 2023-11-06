import{a as M,m as x,b as F,c as q,o as i,d as r,r as g,e as n,f,g as V,S as D,h as G,F as X,l as c,V as E,i as T,G as S,T as R,j as Y,M as J,O as K,k as Q,t as Z,n as j,p as b,P as ee,q as B,w,v as y,s as p,u as P,x as $,y as I,z as te,W as ne,A as se,B as W,C as O,D as ae,E as oe,H as le,I as ie,J as re}from"./vendor-ba1a9126.js";window.axios=M;M.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";const L={computed:{geoserverUrl(){return this.config.geoserver_base_url+"/geoserver/aurin/ows"}},methods:{getWfsFeatureInfo:function(e,t,s){const o=new URLSearchParams({service:"WFS",version:"2.0.0",featureId:s,request:"GetFeature",typeNames:e+":"+t,outputFormat:"application/json"});return this.geoserverUrl+"?"+o.toString()}}},_=(e,t)=>{const s=e.__vccOpts||e;for(const[o,a]of t)s[o]=a;return s},ue={mixins:[L],data(){return{}},computed:{...x({wetlands:"wetlandNames"}),...x(["selectedWetland"])},methods:{...F(["storeWetland"])},mounted(){let e=this,t={sourceId:"wetlands",templates:{item({item:s}){return s.name}},getItems({query:s}){return e.wetlands.filter(function(o){return o.name.match(new RegExp(s,"i"))})},getItemInputValue({item:s}){return s.name},onSelect({item:s}){if(!e.selectedWetland||e.selectedWetland.getId()!=="wetlands."+s.id){const o=e.getWfsFeatureInfo("aurin","wetlands",s.id);e.storeWetland(o)}}};q({container:"#wetland-search",placeholder:"Search by Wetland Name",getSources({query:s}){return[t]}})}},de={id:"wetland-search"};function ce(e,t,s,o,a,l){return i(),r("div",de)}const pe=_(ue,[["render",ce]]);function A(e,t){e.getView().fit(t,{padding:[50,50,50,50]})}function z(e){return e.getId().split(".")[1]}const he={methods:{zoomToFullExtent(e){return A(e,e.get("MAP_EXTENT"))}},components:{WetlandSearch:pe},props:["protectionStatus","landUse","map"],emits:["update:protectionStatus","update:landUse"],mounted(){}},me={class:"map-controls"},_e={class:"row"},fe={class:"col-auto"},ge={class:"row justify-content-center align-items-center"},be={class:"col-auto"},ve=n("i",{class:"bi bi-arrows-angle-expand"},null,-1),Se=[ve],we={class:"col"},ye={class:"row justify-content-center align-items-center"},Ce={class:"col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1"},We={class:"form-floating"},xe=["value"],Ae=V('<option value="Conservation Park">Conservation Park</option><option value="Heritage River">Heritage River</option><option value="Natural Catchment Area">Natural Catchment Area</option><option value="Nature Conservation Reserve">Nature Conservation Reserve</option><option value="Natural Features Reserve">Natural Features Reserve</option><option value="National Park">National Park</option><option value="Private Nature Reserve">Private Nature Reserve</option><option value="Ramsar">Ramsar Site</option><option value="Reference Area">Reference Area</option><option value="State Park">State Park</option><option value="Other">Other</option><option value="none">No protection</option><option value="any">Any protection</option><option value="all">No Filter</option>',14),Fe=[Ae],Pe=n("label",{for:"protectionStatus"},"Filter by protection status",-1),$e={class:"col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1"},Ie={class:"form-floating"},Le=["value"],Ne=V('<option value="Dryland cropping">Dryland cropping</option><option value="Dryland horticulture">Dryland horticulture</option><option value="Grazing modified pastures">Grazing modified pastures</option><option value="Grazing native vegetation">Grazing native vegetation</option><option value="Intensive animal and plant production">Intensive animal and plant production </option><option value="Irrigated cropping">Irrigated cropping</option><option value="Irrigated horticulture">Irrigated horticulture</option><option value="Irrigated pastures">Irrigated pastures</option><option value="Mining and waste">Mining and waste</option><option value="Nature conservation">Nature conservation</option><option value="Other minimal use">Other minimal use</option><option value="Plantation forestry">Plantation forestry</option><option value="Production forestry">Production forestry</option><option value="Rural residential and farm infrastructure">Rural residential and farm infrastructure </option><option value="Urban intensive uses">Urban intensive uses</option><option value="Water">Water</option><option value="No data">No data</option><option value="all">No Filter</option>',18),ke=[Ne],Ue=n("label",{for:"landUse"},"Filter by catchment land use",-1),Ee={class:"col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1"};function Te(e,t,s,o,a,l){const d=g("WetlandSearch");return i(),r("div",me,[n("form",null,[n("div",_e,[n("div",fe,[n("div",ge,[n("div",be,[n("button",{class:"btn btn-sm btn-light",type:"button",onClick:t[0]||(t[0]=h=>l.zoomToFullExtent(s.map))},Se)])])]),n("div",we,[n("div",ye,[n("div",Ce,[n("div",We,[n("select",{class:"form-select","aria-label":"Filter by protection status",name:"protection_status",id:"protectionStatus",value:s.protectionStatus,onInput:t[1]||(t[1]=h=>e.$emit("update:protectionStatus",h.target.value))},Fe,40,xe),Pe])]),n("div",$e,[n("div",Ie,[n("select",{class:"form-select","aria-label":"Filter by catchment land use",name:"landuse",id:"landUse",value:s.landUse,onInput:t[2]||(t[2]=h=>e.$emit("update:landUse",h.target.value))},ke,40,Le),Ue])]),n("div",Ee,[f(d)])])])])])])}const Re=_(he,[["render",Te]]),Me=new D({stroke:new G({color:"#3f3f3f",width:2}),fill:new X({color:"rgba(255,184,0,0.6)"})}),Ve=[144.1168836932806,-38.56752038512003,146.16343402067832,-37.2082605301041],De={components:{MapControls:Re},data(){return{sidebar:null,wetlandName:"",layers:{wetlands:null,selected:null},viewparams:{wetlands:{protection:"all",landuse:"all"}},map:null}},computed:{...x(["selectedWetland"])},mixins:[L],methods:{buildViewParams(e){return c.reduce(e,function(t,s,o){return t=(t!==""?t+";":t)+o+":"+s,t},"")},updateProtectionStatus(e){this.viewparams.wetlands.protection=e;let t=this.layers.wetlands.getSource();t.updateParams(c.merge(t.getParams(),{VIEWPARAMS:this.buildViewParams(this.viewparams.wetlands)}))},updateLandUse(e){this.viewparams.wetlands.landuse=e;let t=this.layers.wetlands.getSource();t.updateParams(c.merge(t.getParams(),{VIEWPARAMS:this.buildViewParams(this.viewparams.wetlands)}))},selectFeature(e){let t=this;const s=t.layers.wetlands.getSource().getFeatureInfoUrl(e.coordinate,t.map.getView().getResolution(),"EPSG:3857",{INFO_FORMAT:"application/json"});this.storeWetland(s)},renderSelectedFeature(e){let t=this;t.layers.selected.getSource().clear(),e&&(t.layers.selected.getSource().addFeature(e),A(t.map,e.getGeometry().getExtent()),t.pushWetlandInfoRoute(e))},pushWetlandInfoRoute(e){let t=z(e);t!==this.$route.params.id&&this.$router.push({name:"wetland-report",params:{id:t}})},pushHomeRoute(){this.$router.push({name:"intro"})},...F(["storeWetland"])},watch:{selectedWetland(e){e?this.pushWetlandInfoRoute(e):this.pushHomeRoute(),this.renderSelectedFeature(e)}},mounted(){let e=this;const t=new E({source:new T({url:e.geoserverUrl+"?service=WFS&version=1.1.0&request=GetFeature&typeName=aurin:mw_boundary_simplified&outputFormat=application/json",format:new S}),style:new D({stroke:new G({color:"#000",width:"1"})})});e.layers.wetlands=new R({source:new Y({url:e.geoserverUrl,params:{LAYERS:"aurin:wetlands",TILED:!0,VIEWPARAMS:this.buildViewParams(this.viewparams.wetlands)}})}),e.layers.selected=new E({source:new T({}),style:Me}),e.map=new J({target:"map",layers:[new R({source:new K}),t,this.layers.wetlands,this.layers.selected],view:new Q({center:[0,0],zoom:2})}),e.map.set("MAP_EXTENT",Z(Ve,"EPSG:7844","EPSG:3857")),A(e.map,e.map.get("MAP_EXTENT")),e.map.on("singleclick",e.selectFeature)}},Ge={class:"col-4 sidebar"},je={class:"col-8 viewport"},Be={id:"viewport"},Oe=n("div",{id:"map"},null,-1);function ze(e,t,s,o,a,l){const d=g("router-view"),h=g("MapControls");return i(),r(b,null,[n("div",Ge,[(i(),j(d,{name:"sidebar",key:e.$route.path,feature:e.selectedWetland},null,8,["feature"]))]),n("div",je,[n("div",Be,[f(h,{protectionStatus:this.viewparams.wetlands.protection,landUse:this.viewparams.wetlands.landuse,"onUpdate:protectionStatus":l.updateProtectionStatus,"onUpdate:landUse":l.updateLandUse,map:a.map},null,8,["protectionStatus","landUse","onUpdate:protectionStatus","onUpdate:landUse","map"]),Oe])])],64)}const He=_(De,[["render",ze]]),qe={name:"SidebarXhrContent",data(){return{content:"Loading ..."}},watch:{},computed:{contentPath(){return this.$route.name?"/content/"+this.$route.name:null}},mounted(){const e=this;e.contentPath&&axios.get(e.contentPath).then(function(t){e.content=t.data}).then(function(){var t=[].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));t.map(function(s){return new ee(s)})})}},Xe=["innerHTML"];function Ye(e,t,s,o,a,l){return i(),r("div",{innerHTML:a.content},null,8,Xe)}const v=_(qe,[["render",Ye]]);const Je={props:["landUseData","index"],data(){return{showAllData:!1}},methods:{toggleTable(){this.showAllData=!this.showAllData},showDataRow(e){return e===0||this.showAllData}},mounted(){let e=[],t=this,s=t.landUseData;c.forEach(s.data,function(a){let l={name:a.usage,hoverinfo:"name",x:[],y:[s.label],type:"bar",orientation:"h"};l.x.push(a.percentage),e.push(l)});let o=t.$el.getElementsByClassName("landUseChart")[0];B.newPlot(o,e,{showlegend:!1,margin:{t:0,b:0,l:0,r:0,pad:0},barmode:"stack",height:20,yaxis:{zeroline:!1,showgrid:!1,visible:!1},xaxis:{visible:!1,range:[0,100]}},{displayModeBar:!1,responsive:!0})}},Ke=n("i",{class:"bi bi-clipboard-data"},null,-1),Qe=[Ke],Ze=n("div",{class:"landUseChart"},null,-1),et={class:"landUseTable"},tt={class:"table"},nt={class:"text-end"};function st(e,t,s,o,a,l){return i(),r("div",null,[w(n("button",{type:"button",class:"btn btn-sm float-end",onClick:t[0]||(t[0]=d=>l.toggleTable())},Qe,512),[[y,s.landUseData.data.length>1]]),n("h3",null,p(s.landUseData.label),1),Ze,n("div",et,[n("table",tt,[(i(!0),r(b,null,P(s.landUseData.data,(d,h)=>w((i(),r("tr",null,[n("td",null,p(d.usage),1),n("td",nt,p(d.percentage),1)],512)),[[y,l.showDataRow(h)]])),256))])])])}const at=_(Je,[["render",st]]);const ot={props:["seasonalCounts","showChart","index"],data(){return{chartTitle:"Latham's Snipe Project Counts By Season"}},methods:{drawSeasonalCountsChart(){let e=[],t=this,s={x:[],y:[],type:"bar",orientation:"v"};t.seasonalCounts.forEach(function(a){s.x.push(a.season),s.y.push(a.count)}),e.push(s);let o=t.$el.getElementsByClassName("seasonalCountChart")[0];B.newPlot(o,e,{showlegend:!1,margin:{t:0,l:20,r:30,pad:0},autosize:!0,height:400},{displayModeBar:!1,responsive:!0})}},mounted(){this.drawSeasonalCountsChart()}},lt=e=>($("data-v-d4579635"),e=e(),I(),e),it=lt(()=>n("div",{class:"seasonalCountChart"},null,-1)),rt=[it];function ut(e,t,s,o,a,l){return i(),r("div",null,rt)}const dt=_(ot,[["render",ut],["__scopeId","data-v-d4579635"]]);const ct={name:"SpeciesList",emits:["closed"],props:{visible:Boolean,speciesList:Array,title:String,id:String},data(){return{modal:null}},watch:{visible:function(){this.toggleModal()}},methods:{toggleModal(){this.visible?this.modal.show():this.modal.hide()},isThreatened(e){return c.isArray(e.conservation)?"No":"Yes"},commonNames(e){let t;return e.hasOwnProperty("common_names")?t=e.common_names.join(", "):t=e.common_name,t}},mounted(){const e=this;let t=document.getElementById(this.id);this.modal=new te(t),t.addEventListener("hide.bs.modal",function(s){e.$emit("closed")}),this.toggleModal()}},N=e=>($("data-v-389ae8d7"),e=e(),I(),e),pt=["id"],ht={class:"modal-dialog modal-xl"},mt={class:"modal-content"},_t={class:"modal-header"},ft={class:"modal-title"},gt=N(()=>n("button",{type:"button",class:"btn-close","data-bs-dismiss":"modal","aria-label":"Close"},null,-1)),bt={class:"modal-body"},vt={class:"table table-bordered"},St=N(()=>n("tr",null,[n("th",null,"Scientific Name"),n("th",null,"Common names"),n("th",null,"Threatened?"),n("th",null,"More info")],-1)),wt=["href"],yt=N(()=>n("i",{class:"bi bi-box-arrow-up-right"},null,-1)),Ct=[yt];function Wt(e,t,s,o,a,l){return i(),r("div",null,[n("div",{id:s.id,class:"modal",tabindex:"-1"},[n("div",ht,[n("div",mt,[n("div",_t,[n("h5",ft,p(s.title),1),gt]),n("div",bt,[n("table",vt,[St,(i(!0),r(b,null,P(s.speciesList,d=>(i(),r("tr",null,[n("td",null,p(d.scientific_name),1),n("td",null,p(l.commonNames(d)),1),n("td",null,p(l.isThreatened(d)),1),n("td",null,[n("a",{href:"https://bie.ala.org.au/species/"+d.guid,target:"_blank"},Ct,8,wt)])]))),256))])])])])],8,pt)])}const xt=_(ct,[["render",Wt],["__scopeId","data-v-389ae8d7"]]);const At={name:"WetlandReport",components:{SpeciesList:xt,SeasonalCountsChart:dt,LandUseChart:at},props:["feature","id"],mixins:[L],data(){return{showWaterbirdList:!1,showFrogList:!1,landuseEndpointMap:{"Vicmap Planning Zones":"planning/zones","Vicmap Planning Overlays":"planning/overlays","VLUIS Property Classification":"vluis/property","VLUIS Land Use":"vluis/alum","VLUIS Land Cover":"vluis/landcover","Catchment Land use":"catchment"},stateAbbreviations:{Victoria:"vic",Queensland:"qld","New South Wales":"nsw",Tasmania:"tas","Australian Capital Territory":"act","Northern Territory":"nt","Western Australia":"wa","South Australia":"sa"},content:"",alaWaterbirdSpecies:null,alaFrogSpecies:null,landuse:[],snipe:{seasonalCounts:[],alaSeasonalCounts:[]},showSeasonalCountChart:!1,showAlaSeasonalCountChart:!1}},watch:{feature(e){e&&this.renderWetlandInfo(e)}},computed:{wetlandName(){return this.feature.get("name")},protectionStatus(){return this.feature===null||!this.feature.get("protection_status")?"Unknown":this.feature.get("protection_status")[0]===null?"None":this.feature.get("protection_status").join(", ")},featureStateAbbreviations(){return c.values(c.pick(this.stateAbbreviations,this.feature.get("states")))},threatenedAlaWaterbirdSpecies(){let e=this;return e.alaWaterbirdSpecies?c.filter(e.alaWaterbirdSpecies,function(t){let s=["aus",...e.featureStateAbbreviations],o=Object.keys(t.conservation);return c.intersection(s,o).length>0}):null},threatenedAlaFrogSpecies(){let e=this;return e.alaFrogSpecies?c.filter(e.alaFrogSpecies,function(t){let s=["aus",...e.featureStateAbbreviations],o=Object.keys(t.conservation);return c.intersection(s,o).length>0}):null},maxSnipeSeasonCount(){return this.snipe.seasonalCounts.length>0?c.maxBy(this.snipe.seasonalCounts,e=>parseInt(e.count)):null},maxSnipeAlaSeasonCount(){return this.snipe.alaSeasonalCounts.length>0?c.maxBy(this.snipe.alaSeasonalCounts,e=>parseInt(e.count)):null},maxSnipeSeasonLabel(){let e="?";return this.snipe.seasonalCounts.length>0&&(e="No data",this.maxSnipeSeasonCount&&(e=this.maxSnipeSeasonCount.season+": "+this.maxSnipeSeasonCount.count)),e},maxSnipeAlaSeasonLabel(){let e="?";return this.snipe.alaSeasonalCounts.length>0&&(e="No data",this.maxSnipeAlaSeasonCount&&(e=this.maxSnipeAlaSeasonCount.season+": "+this.maxSnipeAlaSeasonCount.count)),e}},methods:{featureToWkt(e,t){let s={featureProjection:"EPSG:3857"};return typeof t<"u"&&(s.dataProjection="EPSG:"+t),new ne().writeFeature(e,s)},bufferedFeature(e){let t=.35,s=e.clone();s.getGeometry().transform("EPSG:3857","EPSG:7844");let o=new S().writeFeatureObject(s),a=se(o,t,{units:"kilometers"});return a=new S().readFeature(a),a.getGeometry().transform("EPSG:7844","EPSG:3857"),a},fetchAlaWaterbirds(e){let t=this;e?axios.post("/app/area/ala-birds",{wkt:t.featureToWkt(e,7844)}).then(function(s){t.alaWaterbirdSpecies=s.data}):t.alaWaterbirdSpecies=null},fetchAlaFrogs(e){let t=this;e?axios.post("/app/area/ala-frogs",{wkt:t.featureToWkt(e,7844)}).then(function(s){t.alaFrogSpecies=s.data}):t.alaFrogSpecies=null},fetchLandUsage(e){let t=this;t.landuse.length=0,e&&c.forIn(t.landuseEndpointMap,(s,o)=>{t.fetchLandUsePercentage(e,o,s)})},async fetchLandUsePercentage(e,t,s){let o=this;await axios.post("/app/landuse/"+s,{wkt:o.featureToWkt(o.bufferedFeature(e),7844)}).then(function(a){o.landUsePushAndSort({label:t,data:a.data})})},landUsePushAndSort(e){let t=this;t.landuse.push(e),t.landuse=c.sortBy(t.landuse,"label")},async fetchLathamsSnipeSeasonalCounts(e){let t=this;t.snipe.seasonalCounts.length=0,e&&(t.snipe.seasonalCounts=await axios.post("/app/snipe/seasonal-counts",{wkt:t.featureToWkt(e,7844)}).then(function(s){return s.data}))},async fetchSnipeAlaSeasonalCounts(e){let t=this;t.snipe.alaSeasonalCounts.length=0,e&&(t.snipe.alaSeasonalCounts=await axios.post("/app/snipe/ala-seasonal-counts",{wkt:t.featureToWkt(e,7844)}).then(function(s){return s.data}))},renderWetlandInfo(e){this.fetchAlaWaterbirds(e),this.fetchAlaFrogs(e),this.fetchLathamsSnipeSeasonalCounts(e),this.fetchSnipeAlaSeasonalCounts(e),this.fetchLandUsage(e)},...F(["storeWetland"])},mounted(){const e=this;if(e.feature&&z(e.feature)===e.id)e.renderWetlandInfo(e.feature);else{const t=e.getWfsFeatureInfo("aurin","wetlands",e.id);e.storeWetland(t)}}},u=e=>($("data-v-6d1d7f9d"),e=e(),I(),e),Ft=u(()=>n("h1",null,"Wetland Values Report",-1)),Pt=u(()=>n("p",null," The wetland values report tool provides a summary of the waterbird diversity at a selected wetland. Dominant land uses are reported within 350m of the wetland boundary. ",-1)),$t={class:"table"},It=u(()=>n("td",null,"Wetland name",-1)),Lt=u(()=>n("td",null,"Protection Status",-1)),Nt=u(()=>n("h2",null,"ALA Records",-1)),kt={class:"table"},Ut=u(()=>n("tr",null,[n("th",{colspan:"3"},"Waterbirds")],-1)),Et=u(()=>n("button",{class:"btn btn-light btn-sm",title:"View species listing/info"},[n("i",{class:"bi bi-list-columns"})],-1)),Tt=[Et],Rt=u(()=>n("tr",null,[n("th",{colspan:"3"},"Frogs")],-1)),Mt=u(()=>n("button",{class:"btn btn-light btn-sm",title:"View species listing/info"},[n("i",{class:"bi bi-list-columns"})],-1)),Vt=[Mt],Dt=u(()=>n("h2",null,"Latham's snipe data",-1)),Gt=u(()=>n("p",null," Latham's Snipe is a mysterious migratory waterbird (shorebird) that is cryptic in its plumage and behaviour. This means it is often overlooked in monitoring and assessment. As a result, its wetland habitats are frequently threatened by land use change. ",-1)),jt={class:"table"},Bt=u(()=>n("td",null,"Maximum number of Latham's Snipe",-1)),Ot=u(()=>n("i",{class:"bi bi-bar-chart"},null,-1)),zt=[Ot],Ht={key:0},qt={colspan:"3"},Xt=u(()=>n("td",null,"Maximum number of Latham's Snipe (ALA)",-1)),Yt=u(()=>n("i",{class:"bi bi-bar-chart"},null,-1)),Jt=[Yt],Kt={key:1},Qt={colspan:"3"},Zt=u(()=>n("h2",null,"Land use",-1)),en=u(()=>n("p",null," The built environment surrounding a wetland will influence its value for biodiversity. A wetland surrounded by housing or industry may provide less habitat for wetland birds than a wetland located near other wetlands. But it may also provide a refuge for birds transiting through an urban landscape. ",-1)),tn={class:"table"},nn={key:0},sn=u(()=>n("td",null,"?",-1)),an=[sn],on={key:1},ln={colspan:"2"},rn={class:"w-100"},un=u(()=>n("thead",null,[n("tr",null,[n("th",null,"Source / Primary classification"),n("th",null,"%")])],-1)),dn={colspan:"2"};function cn(e,t,s,o,a,l){const d=g("seasonal-counts-chart"),h=g("land-use-chart"),U=g("species-list");return i(),r(b,null,[Ft,Pt,s.feature?(i(),r(b,{key:0},[n("table",$t,[n("tbody",null,[n("tr",null,[It,n("td",null,p(l.wetlandName),1)]),n("tr",null,[Lt,n("td",null,p(l.protectionStatus),1)])])]),Nt,n("table",kt,[n("tbody",null,[Ut,n("tr",null,[n("td",null,p(a.alaWaterbirdSpecies==null?"?":a.alaWaterbirdSpecies.length)+" species",1),n("td",null,p(l.threatenedAlaWaterbirdSpecies==null?"?":l.threatenedAlaWaterbirdSpecies.length)+" threatened",1),n("td",{class:"text-end",onClick:t[0]||(t[0]=m=>a.showWaterbirdList=!0)},Tt)]),Rt,n("tr",null,[n("td",null,p(a.alaFrogSpecies==null?"?":a.alaFrogSpecies.length)+" species",1),n("td",null,p(l.threatenedAlaFrogSpecies==null?"?":l.threatenedAlaFrogSpecies.length)+" threatened",1),n("td",{class:"text-end",onClick:t[1]||(t[1]=m=>a.showFrogList=!0)},Vt)])])]),Dt,Gt,n("table",jt,[n("tbody",null,[n("tr",null,[Bt,n("td",null,p(l.maxSnipeSeasonLabel),1),n("td",null,[w(n("button",{type:"button",class:"btn btn-sm float-end",onClick:t[2]||(t[2]=m=>a.showSeasonalCountChart=!a.showSeasonalCountChart)},zt,512),[[y,a.snipe.seasonalCounts.length&&l.maxSnipeSeasonCount]])])]),a.showSeasonalCountChart?(i(),r("tr",Ht,[n("td",qt,[f(d,{"seasonal-counts":a.snipe.seasonalCounts,index:1},null,8,["seasonal-counts"])])])):W("",!0),n("tr",null,[Xt,n("td",null,p(l.maxSnipeAlaSeasonLabel),1),n("td",null,[w(n("button",{type:"button",class:"btn btn-sm float-end",onClick:t[3]||(t[3]=m=>a.showAlaSeasonalCountChart=!a.showAlaSeasonalCountChart)},Jt,512),[[y,a.snipe.alaSeasonalCounts.length&&l.maxSnipeAlaSeasonCount]])])]),a.showAlaSeasonalCountChart?(i(),r("tr",Kt,[n("td",Qt,[f(d,{"seasonal-counts":a.snipe.alaSeasonalCounts,index:2},null,8,["seasonal-counts"])])])):W("",!0)])]),Zt,en,n("table",tn,[n("tbody",null,[a.landuse.length?(i(),r("tr",on,[n("td",ln,[n("table",rn,[un,(i(!0),r(b,null,P(a.landuse,(m,H)=>(i(),r("tbody",null,[n("tr",null,[n("td",dn,[(i(),j(h,{landUseData:m,key:m.label,index:H},null,8,["landUseData","index"]))])])]))),256))])])])):(i(),r("tr",nn,an))])]),f(U,{visible:a.showWaterbirdList,"species-list":a.alaWaterbirdSpecies,title:"Waterbirds",id:"list-waterbirds",onClosed:t[4]||(t[4]=m=>a.showWaterbirdList=!1)},null,8,["visible","species-list"]),f(U,{visible:a.showFrogList,"species-list":a.alaFrogSpecies,title:"Frogs",id:"list-frogs",onClosed:t[5]||(t[5]=m=>a.showFrogList=!1)},null,8,["visible","species-list"])],64)):W("",!0)],64)}const pn=_(At,[["render",cn],["__scopeId","data-v-6d1d7f9d"]]);O.defs("EPSG:7844","+proj=longlat +ellps=GRS80 +no_defs +type=crs");ae(O);const hn=oe({history:le(),routes:[{path:"/",name:"intro",components:{sidebar:v}},{path:"/about",name:"about",components:{sidebar:v}},{path:"/terms",name:"terms",components:{sidebar:v}},{path:"/contact",name:"contact",components:{sidebar:v}},{path:"/wetland/:id",name:"wetland-report",components:{sidebar:pn},props:!0}]}),k=ie({state(){return{selectedWetland:null,speciesInfo:null,wetlandNames:[]}},mutations:{storeWetlandNames(e,t){e.wetlandNames=t},selectWetland(e,t){let s=null;t!==null&&(["protection_status","states"].forEach(function(a){t.properties[a]=JSON.parse(t.properties[a])}),s=new S().readFeature(t)),e.selectedWetland=s},updateSpeciesInfo(e,t){e.speciesInfo=t}},actions:{async storeWetland(e,t){let s=await axios.get(t).then(function(o){let a=null;return o.data.features.length&&(a=o.data.features[0]),a});e.commit("selectWetland",s)}}});axios.get("/app/wetlands").then(function(e){k.commit("storeWetlandNames",e.data)});axios.get("/app/species-info").then(function(e){k.commit("updateSpeciesInfo",e.data)});const C=re({components:{"map-viewport":He}});C.config.globalProperties.config=await axios.get("/app/config").then(function(e){return e.data});C.use(k);C.use(hn);C.mount("#app");
