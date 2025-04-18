"use strict";(globalThis.webpackChunk_wcAdmin_webpackJsonp=globalThis.webpackChunk_wcAdmin_webpackJsonp||[]).push([[8443],{62671:(e,t,r)=>{r.d(t,{Z:()=>v});var a=r(65736),s=r(69307),n=r(94333),i=r(69771),o=r(9818),l=r(92819),c=r(69596),u=r.n(c),d=r(86020),m=r(67221),p=r(81921),y=r(17844),g=r(10431);function h(e,t,r={}){if(!e||0===e.length)return null;const a=e.slice(0),s=a.pop();if(s.showFilters(t,r)){const e=(0,g.flattenFilters)(s.filters),r=t[s.param]||s.defaultValue||"all";return(0,l.find)(e,{value:r})}return h(a,t,r)}function f(e){return t=>(0,i.format)(e,t)}function b(e){if(e?.data?.intervals?.length>1){const t=e.data.intervals[0].date_start,r=e.data.intervals[e.data.intervals.length-1].date_end;if((0,p.containsLeapYear)(t,r))return!0}return!1}var D=r(81514);class R extends s.Component{shouldComponentUpdate(e){return e.isRequesting!==this.props.isRequesting||e.primaryData.isRequesting!==this.props.primaryData.isRequesting||e.secondaryData.isRequesting!==this.props.secondaryData.isRequesting||!(0,l.isEqual)(e.query,this.props.query)}getItemChartData(){const{primaryData:e,selectedChart:t}=this.props;return e.data.intervals.map((function(e){const r={};return e.subtotals.segments.forEach((function(e){if(e.segment_label){const a=r[e.segment_label]?e.segment_label+" (#"+e.segment_id+")":e.segment_label;r[e.segment_id]={label:a,value:e.subtotals[t.key]||0}}})),{date:(0,i.format)("Y-m-d\\TH:i:s",e.date_start),...r}}))}getTimeChartData(){const{query:e,primaryData:t,secondaryData:r,selectedChart:a,defaultDateRange:s}=this.props,n=(0,p.getIntervalForQuery)(e,s),{primary:o,secondary:l}=(0,p.getCurrentDates)(e,s);return function(e,t,r,a,s,n,o){const l=b(e),c=b(t),u=[...e.data.intervals],d=[...t.data.intervals],m=[];for(let e=0;e<u.length;e++){const t=u[e],y=(0,i.format)("Y-m-d\\TH:i:s",t.date_start),g=`${r.label} (${r.range})`,h=t.date_start,f=t.subtotals[n]||0,b=d[e],D=`${a.label} (${a.range})`;let R=(0,p.getPreviousDate)(t.date_start,r.after,a.after,s,o).format("YYYY-MM-DD HH:mm:ss"),v=b&&b.subtotals[n]||0;if("day"===o&&l&&!c&&d?.[e]){const r=new Date(t.date_start),a=new Date(d[e].date_start);(0,p.isLeapYear)(r.getFullYear())&&1===r.getMonth()&&29===r.getDate()&&2===a.getMonth()&&1===a.getDate()&&(R="-",v=0,d.splice(e,0,d[e]))}m.push({date:y,primary:{label:g,labelDate:h,value:f},secondary:{label:D,labelDate:R,value:v}})}return m}(t,r,o,l,e.compare,a.key,n)}getTimeChartTotals(){const{primaryData:e,secondaryData:t,selectedChart:r}=this.props;return{primary:(0,l.get)(e,["data","totals",r.key],null),secondary:(0,l.get)(t,["data","totals",r.key],null)}}renderChart(e,t,r,s){const{emptySearchResults:n,filterParam:i,interactiveLegend:o,itemsLabel:l,legendPosition:c,path:u,query:y,selectedChart:g,showHeaderControls:h,primaryData:b,defaultDateRange:R}=this.props,v=(0,p.getIntervalForQuery)(y,R),q=(0,p.getAllowedIntervalsForQuery)(y,R),C=(0,p.getDateFormatsForInterval)(v,b.data.intervals.length,{type:"php"}),_=n?(0,a.__)("No data for the current search","woocommerce"):(0,a.__)("No data for the selected date range","woocommerce"),{formatAmount:T,getCurrencyConfig:w}=this.context;return(0,D.jsx)(d.Chart,{allowedIntervals:q,data:r,dateParser:"%Y-%m-%dT%H:%M:%S",emptyMessage:_,filterParam:i,interactiveLegend:o,interval:v,isRequesting:t,itemsLabel:l,legendPosition:c,legendTotals:s,mode:e,path:u,query:y,screenReaderFormat:f(C.screenReaderFormat),showHeaderControls:h,title:g.label,tooltipLabelFormat:f(C.tooltipLabelFormat),tooltipTitle:"time-comparison"===e&&g.label||null,tooltipValueFormat:(0,m.getTooltipValueFormat)(g.type,T),chartType:(0,p.getChartTypeForQuery)(y),valueType:g.type,xFormat:f(C.xFormat),x2Format:f(C.x2Format),currency:w()})}renderItemComparison(){const{isRequesting:e,primaryData:t}=this.props;if(t.isError)return(0,D.jsx)(d.AnalyticsError,{});const r=e||t.isRequesting,a=this.getItemChartData();return this.renderChart("item-comparison",r,a)}renderTimeComparison(){const{isRequesting:e,primaryData:t,secondaryData:r}=this.props;if(!t||t.isError||r.isError)return(0,D.jsx)(d.AnalyticsError,{});const a=e||t.isRequesting||r.isRequesting,s=this.getTimeChartData(),n=this.getTimeChartTotals();return this.renderChart("time-comparison",a,s,n)}render(){const{mode:e}=this.props;return"item-comparison"===e?this.renderItemComparison():this.renderTimeComparison()}}R.contextType=y.CurrencyContext,R.propTypes={filters:u().array,isRequesting:u().bool,itemsLabel:u().string,limitProperties:u().array,mode:u().string,path:u().string.isRequired,primaryData:u().object,query:u().object.isRequired,secondaryData:u().object,selectedChart:u().shape({key:u().string.isRequired,label:u().string.isRequired,order:u().oneOf(["asc","desc"]),orderby:u().string,type:u().oneOf(["average","number","currency"]).isRequired}).isRequired},R.defaultProps={isRequesting:!1,primaryData:{data:{intervals:[]},isError:!1,isRequesting:!1},secondaryData:{data:{intervals:[]},isError:!1,isRequesting:!1}};const v=(0,n.compose)((0,o.withSelect)(((e,t)=>{const{charts:r,endpoint:a,filters:s,isRequesting:n,limitProperties:i,query:o,advancedFilters:c}=t,u=i||[a],d=h(s,o),p=(0,l.get)(d,["settings","param"]),y=t.mode||function(e,t){if(e&&t){const r=(0,l.get)(e,["settings","param"]);if(!r||Object.keys(t).includes(r))return(0,l.get)(e,["chartMode"])}return null}(d,o)||"time-comparison",{woocommerce_default_date_range:g}=e(m.settingsStore).getSetting("wc_admin","wcAdminSettings"),f=e(m.REPORTS_STORE_NAME),b={mode:y,filterParam:p,defaultDateRange:g};if(n)return b;const D=u.some((e=>o[e]&&o[e].length));if(o.search&&!D)return{...b,emptySearchResults:!0};const R=r&&r.map((e=>e.key)),v=(0,m.getReportChartData)({endpoint:a,dataType:"primary",query:o,selector:f,limitBy:u,filters:s,advancedFilters:c,defaultDateRange:g,fields:R});if("item-comparison"===y)return{...b,primaryData:v};const q=(0,m.getReportChartData)({endpoint:a,dataType:"secondary",query:o,selector:f,limitBy:u,filters:s,advancedFilters:c,defaultDateRange:g,fields:R});return{...b,primaryData:v,secondaryData:q}})))(R)},17853:(e,t,r)=>{r.d(t,{Z:()=>b});var a=r(65736),s=r(69307),n=r(94333),i=r(9818),o=r(69596),l=r.n(o),c=r(10431),u=r(86020),d=r(81595),m=r(67221),p=r(81921),y=r(14599),g=r(17844),h=r(81514);class f extends s.Component{formatVal(e,t){const{formatAmount:r,getCurrencyConfig:a}=this.context;return"currency"===t?r(e):(0,d.formatValue)(a(),t,e)}getValues(e,t){const{emptySearchResults:r,summaryData:a}=this.props,{totals:s}=a,n=s.primary?s.primary[e]:0,i=s.secondary?s.secondary[e]:0,o=r?0:n,l=r?0:i;return{delta:(0,d.calculateDelta)(o,l),prevValue:this.formatVal(l,t),value:this.formatVal(o,t)}}render(){const{charts:e,query:t,selectedChart:r,summaryData:s,endpoint:n,report:i,defaultDateRange:o}=this.props,{isError:l,isRequesting:d}=s;if(l)return(0,h.jsx)(u.AnalyticsError,{});if(d)return(0,h.jsx)(u.SummaryListPlaceholder,{numberOfItems:e.length});const{compare:m}=(0,p.getDateParamsFromQuery)(t,o);return(0,h.jsx)(u.SummaryList,{children:({onToggle:t})=>e.map((e=>{const{key:s,order:o,orderby:l,label:d,type:p,isReverseTrend:g,labelTooltipText:f}=e,b={chart:s};l&&(b.orderby=l),o&&(b.order=o);const D=(0,c.getNewPath)(b),R=r.key===s,{delta:v,prevValue:q,value:C}=this.getValues(s,p);return(0,h.jsx)(u.SummaryNumber,{delta:v,href:D,label:d,reverseTrend:g,prevLabel:"previous_period"===m?(0,a.__)("Previous period:","woocommerce"):(0,a.__)("Previous year:","woocommerce"),prevValue:q,selected:R,value:C,labelTooltipText:f,onLinkClickCallback:()=>{t&&t(),(0,y.recordEvent)("analytics_chart_tab_click",{report:i||n,key:s})}},s)}))})}}f.propTypes={charts:l().array.isRequired,endpoint:l().string.isRequired,limitProperties:l().array,query:l().object.isRequired,selectedChart:l().shape({key:l().string.isRequired,label:l().string.isRequired,order:l().oneOf(["asc","desc"]),orderby:l().string,type:l().oneOf(["average","number","currency"]).isRequired}).isRequired,summaryData:l().object,report:l().string},f.defaultProps={summaryData:{totals:{primary:{},secondary:{}},isError:!1}},f.contextType=g.CurrencyContext;const b=(0,n.compose)((0,i.withSelect)(((e,t)=>{const{charts:r,endpoint:a,limitProperties:s,query:n,filters:i,advancedFilters:o}=t,l=s||[a],c=l.some((e=>n[e]&&n[e].length));if(n.search&&!c)return{emptySearchResults:!0};const u=r&&r.map((e=>e.key)),{woocommerce_default_date_range:d}=e(m.settingsStore).getSetting("wc_admin","wcAdminSettings");return{summaryData:(0,m.getSummaryNumbers)({endpoint:a,query:n,select:e,limitBy:l,filters:i,advancedFilters:o,defaultDateRange:d,fields:u}),defaultDateRange:d}})))(f)},67327:(e,t,r)=>{r.d(t,{Z:()=>s});var a=r(92819);function s(e,t=[]){return(0,a.find)(t,{key:e})||t[0]}}}]);