(function(e){function t(){function r(e){var o=e<1e12?n?performance.now()+performance.timing.navigationStart:t():e||t();if(o-s>=1e3){c._updateTargets();s=o}i(r)}this.regional=[];this.regional[""]={labels:["Years","Months","Weeks","Days","Hours","Minutes","Seconds"],labels1:["Year","Month","Week","Day","Hour","Minute","Second"],compactLabels:["y","m","w","d"],whichLabels:null,digits:["0","1","2","3","4","5","6","7","8","9"],timeSeparator:":",isRTL:false};this._defaults={text_size:"35",animate_sec:false,color_down:"#3A3A3A",color_up:"#635b63",color_txt:"#ffffff",color_sw:"#000000",ujic_txt:true,ujic_url:false,until:null,since:null,timezone:null,serverSync:null,format:"dHMS",layout:"",compact:false,significant:0,description:"",expiryUrl:"",expiryText:"",alwaysExpire:false,onExpiry:null,onTick:null,tickInterval:1};e.extend(this._defaults,this.regional[""]);this._serverSyncs=[];var t=typeof Date.now=="function"?Date.now:function(){return(new Date).getTime()};var n=window.performance&&typeof window.performance.now=="function";var i=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||null;var s=0;if(!i||e.noRequestAnimationFrame){e.noRequestAnimationFrame=null;setInterval(function(){c._updateTargets()},980)}else{s=window.animationStartTime||window.webkitAnimationStartTime||window.mozAnimationStartTime||window.oAnimationStartTime||window.msAnimationStartTime||t();i(r)}}function l(t,n){if(t=="option"&&(n.length==0||n.length==1&&typeof n[0]=="string")){return true}return e.inArray(t,f)>-1}var n=0;var r=1;var i=2;var s=3;var o=4;var u=5;var a=6;e.extend(t.prototype,{markerClassName:"hasCountdown",propertyName:"countdown",_rtlClass:"countdown_rtl",_sectionClass:"countdown_section",_amountClass:"countdown_amount",_rowClass:"countdown_row",_holdingClass:"countdown_holding",_showClass:"countdown_show",_descrClass:"countdown_descr",_timerTargets:[],setDefaults:function(t){this._resetExtraLabels(this._defaults,t);e.extend(this._defaults,t||{})},UTCDate:function(e,t,n,r,i,s,o,u){if(typeof t=="object"&&t.constructor==Date){u=t.getMilliseconds();o=t.getSeconds();s=t.getMinutes();i=t.getHours();r=t.getDate();n=t.getMonth();t=t.getFullYear()}var a=new Date;a.setUTCFullYear(t);a.setUTCDate(1);a.setUTCMonth(n||0);a.setUTCDate(r||1);a.setUTCHours(i||0);a.setUTCMinutes((s||0)-(Math.abs(e)<30?e*60:e));a.setUTCSeconds(o||0);a.setUTCMilliseconds(u||0);return a},periodsToSeconds:function(e){return e[0]*31557600+e[1]*2629800+e[2]*604800+e[3]*86400+e[4]*3600+e[5]*60+e[6]},_attachPlugin:function(t,n){t=e(t);if(t.hasClass(this.markerClassName)){return}var r={options:e.extend({},this._defaults),_periods:[0,0,0,0,0,0,0]};t.addClass(this.markerClassName).data(this.propertyName,r);this._optionPlugin(t,n)},_addTarget:function(e){if(!this._hasTarget(e)){this._timerTargets.push(e)}},_hasTarget:function(t){return e.inArray(t,this._timerTargets)>-1},_removeTarget:function(t){this._timerTargets=e.map(this._timerTargets,function(e){return e==t?null:e})},_updateTargets:function(){for(var e=this._timerTargets.length-1;e>=0;e--){this._updateCountdown(this._timerTargets[e])}},_optionPlugin:function(t,n,r){t=e(t);var i=t.data(this.propertyName);if(!n||typeof n=="string"&&r==null){var s=n;n=(i||{}).options;return n&&s?n[s]:n}if(!t.hasClass(this.markerClassName)){return}n=n||{};if(typeof n=="string"){var s=n;n={};n[s]=r}if(n.layout){n.layout=n.layout.replace(/</g,"<").replace(/>/g,">")}this._resetExtraLabels(i.options,n);var o=i.options.timezone!=n.timezone;e.extend(i.options,n);this._adjustSettings(t,i,n.until!=null||n.since!=null||o);var u=new Date;if(i._since&&i._since<u||i._until&&i._until>u){this._addTarget(t[0])}this._updateCountdown(t,i)},_updateCountdown:function(t,n){var r=e(t);n=n||r.data(this.propertyName);if(!n){return}r.html(this._generateHTML(n)).toggleClass(this._rtlClass,n.options.isRTL);var i=n.options.ujic_url;var s=n.options.until;var o=new Date;var u=parseInt(o.getTime()/1e3);var a=parseInt(s.getTime()/1e3)-2;if(i=="false")i=false;if(i&&u>a){window.location.replace(i)}var f=n.options.color_down;var l=n.options.color_up;jQuery("#ujiCountdown .countdown_amount").css("background-image","linear-gradient(bottom, "+f+" 50%, "+l+" 50%)");jQuery("#ujiCountdown .countdown_amount").css("background-image","-o-linear-gradient(bottom, "+f+" 50%, "+l+" 50%)");jQuery("#ujiCountdown .countdown_amount").css("background-image","-moz-linear-gradient(bottom, "+f+" 50%, "+l+" 50%)");jQuery("#ujiCountdown .countdown_amount").css("background-image","-webkit-linear-gradient(bottom, "+f+" 50%, "+l+" 50%)");jQuery("#ujiCountdown .countdown_amount").css("background-image","-ms-linear-gradient(bottom, "+f+" 50%, "+l+" 50%)");jQuery("#ujiCountdown .countdown_amount").css("filter","progid:DXImageTransform.Microsoft.Gradient(startColorstr='"+f+"', endColorstr='"+l+"')");var c=n.options.color_txt;var h=n.options.color_sw;jQuery("#ujiCountdown .countdown_amount").css("color",c);jQuery("#ujiCountdown .countdown_amount").css("text-shadow","1px 1px 1px "+h);var p=n.options.ujic_txt;if(p){jQuery("#ujiCountdown .countdown_txt").css("display","block")}else{jQuery("#ujiCountdown .countdown_txt").css("display","none")}var d=n.options.text_size;var v=0;switch(parseInt(d)){case 10:v=5;break;case 11:v=3;break;case 12:v=3;break;case 13:v=1;break}jQuery("#ujiCountdown .countdown_amount").css("font",d+"px/1.5 'Open Sans Condensed',sans-serif");if(d<15){jQuery("#ujiCountdown .countdown_amount").css({padding:"2px 5px","margin-right":"1px"});jQuery("#ujiCountdown .countdown_section").css("margin","0px 6px 0px 0px");jQuery("#ujiCountdown .countdown_txt").css("font","9px 'Open Sans Condensed',sans-serif")}var m=n.options.animate_sec;if(m){var g=jQuery("#"+ujic_id).find("#uji_sec").find(".countdown_section").width();jQuery("#"+ujic_id).find("#uji_sec").find(".countdown_section").css({width:g+"px"});jQuery("#"+ujic_id).find("#uji_sec").find(".countdown_amount").eq(1).css({top:"-74px",position:"absolute",opacity:1});jQuery("#"+ujic_id).find("#uji_sec").find(".countdown_amount").eq(1).animate({top:v+"px",opacity:1},700,function(){jQuery("#"+ujic_id).find("#uji_sec").find(".countdown_amount").eq(1).animate({opacity:0},300)})}if(e.isFunction(n.options.onTick)){var y=n._hold!="lap"?n._periods:this._calculatePeriods(n,n._show,n.options.significant,new Date);if(n.options.tickInterval==1||this.periodsToSeconds(y)%n.options.tickInterval==0){n.options.onTick.apply(t,[y])}}var b=n._hold!="pause"&&(n._since?n._now.getTime()<n._since.getTime():n._now.getTime()>=n._until.getTime());if(b&&!n._expiring){n._expiring=true;if(this._hasTarget(t)||n.options.alwaysExpire){this._removeTarget(t);if(e.isFunction(n.options.onExpiry)){n.options.onExpiry.apply(t,[])}if(n.options.expiryText){var w=n.options.layout;n.options.layout=n.options.expiryText;this._updateCountdown(t,n);n.options.layout=w}if(n.options.expiryUrl){window.location=n.options.expiryUrl}}n._expiring=false}else if(n._hold=="pause"){this._removeTarget(t)}r.data(this.propertyName,n)},_resetExtraLabels:function(e,t){var n=false;for(var r in t){if(r!="whichLabels"&&r.match(/[Ll]abels/)){n=true;break}}if(n){for(var r in e){if(r.match(/[Ll]abels[02-9]|compactLabels1/)){e[r]=null}}}},_adjustSettings:function(t,n,r){var i;var s=0;var o=null;for(var u=0;u<this._serverSyncs.length;u++){if(this._serverSyncs[u][0]==n.options.serverSync){o=this._serverSyncs[u][1];break}}if(o!=null){s=n.options.serverSync?o:0;i=new Date}else{var a=e.isFunction(n.options.serverSync)?n.options.serverSync.apply(t,[]):null;i=new Date;s=a?i.getTime()-a.getTime():0;this._serverSyncs.push([n.options.serverSync,s])}var f=n.options.timezone;f=f==null?-i.getTimezoneOffset():f;if(r||!r&&n._until==null&&n._since==null){n._since=n.options.since;if(n._since!=null){n._since=this.UTCDate(f,this._determineTime(n._since,null));if(n._since&&s){n._since.setMilliseconds(n._since.getMilliseconds()+s)}}n._until=this.UTCDate(f,this._determineTime(n.options.until,i));if(s){n._until.setMilliseconds(n._until.getMilliseconds()+s)}}n._show=this._determineShow(n)},_destroyPlugin:function(t){t=e(t);if(!t.hasClass(this.markerClassName)){return}this._removeTarget(t[0]);t.removeClass(this.markerClassName).empty().removeData(this.propertyName)},_pausePlugin:function(e){this._hold(e,"pause")},_lapPlugin:function(e){this._hold(e,"lap")},_resumePlugin:function(e){this._hold(e,null)},_hold:function(t,n){var r=e.data(t,this.propertyName);if(r){if(r._hold=="pause"&&!n){r._periods=r._savePeriods;var i=r._since?"-":"+";r[r._since?"_since":"_until"]=this._determineTime(i+r._periods[0]+"y"+i+r._periods[1]+"o"+i+r._periods[2]+"w"+i+r._periods[3]+"d"+i+r._periods[4]+"h"+i+r._periods[5]+"m"+i+r._periods[6]+"s");this._addTarget(t)}r._hold=n;r._savePeriods=n=="pause"?r._periods:null;e.data(t,this.propertyName,r);this._updateCountdown(t,r)}},_getTimesPlugin:function(t){var n=e.data(t,this.propertyName);return!n?null:n._hold=="pause"?n._savePeriods:!n._hold?n._periods:this._calculatePeriods(n,n._show,n.options.significant,new Date)},_determineTime:function(e,t){var n=function(e){var t=new Date;t.setTime(t.getTime()+e*1e3);return t};var r=function(e){e=e.toLowerCase();var t=new Date;var n=t.getFullYear();var r=t.getMonth();var i=t.getDate();var s=t.getHours();var o=t.getMinutes();var u=t.getSeconds();var a=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;var f=a.exec(e);while(f){switch(f[2]||"s"){case"s":u+=parseInt(f[1],10);break;case"m":o+=parseInt(f[1],10);break;case"h":s+=parseInt(f[1],10);break;case"d":i+=parseInt(f[1],10);break;case"w":i+=parseInt(f[1],10)*7;break;case"o":r+=parseInt(f[1],10);i=Math.min(i,c._getDaysInMonth(n,r));break;case"y":n+=parseInt(f[1],10);i=Math.min(i,c._getDaysInMonth(n,r));break}f=a.exec(e)}return new Date(n,r,i,s,o,u,0)};var i=e==null?t:typeof e=="string"?r(e):typeof e=="number"?n(e):e;if(i)i.setMilliseconds(0);return i},_getDaysInMonth:function(e,t){return 32-(new Date(e,t,32)).getDate()},_normalLabels:function(e){return e},_generateHTML:function(t){var f=this;t._periods=t._hold?t._periods:this._calculatePeriods(t,t._show,t.options.significant,new Date);var l=false;var c=0;var h=t.options.significant;var p=e.extend({},t._show);for(var d=n;d<=a;d++){l|=t._show[d]=="?"&&t._periods[d]>0;p[d]=t._show[d]=="?"&&!l?null:t._show[d];c+=p[d]?1:0;h-=t._periods[d]>0?1:0}var v=[false,false,false,false,false,false,false];for(var d=a;d>=n;d--){if(t._show[d]){if(t._periods[d]){v[d]=true}else{v[d]=h>0;h--}}}var m=t.options.compact?t.options.compactLabels:t.options.labels;var g=t.options.whichLabels||this._normalLabels;var y=function(e){var n=t.options["compactLabels"+g(t._periods[e])];return p[e]?f._translateDigits(t,t._periods[e])+(n?n[e]:m[e])+" ":""};var b=function(e){var n=t.options["labels"+g(t._periods[e])];if(!x&&p[e]||x&&v[e]){var r="";if(t._periods[e].toString().length==1){r='<span class="countdown_amount">'+0+"</span>"+'<span class="countdown_amount">'+t._periods[e]+"</span>"}else{for(var i=0;i<t._periods[e].toString().length;i++){r+='<span class="countdown_amount">'+t._periods[e].toString().charAt(i)+"</span>"}}return'<span class="countdown_section">'+r+'<span class="countdown_txt">'+(n?n[e]:m[e])+"</span></span>"}else{return""}};var w=t.options.layout;var E=t.options.compact;var x=t.options.significant;var T=t.options.description;return w?this._buildLayout(t,p,w,E,x,v):(E?'<span class="countdown_row countdown_amount'+(t._hold?" countdown_holding":"")+'">'+y(n)+y(r)+y(i)+y(s)+(p[o]?this._minDigits(t._periods[o],2):"")+(p[u]?(p[o]?timeSeparator:"")+this._minDigits(t._periods[u],2):"")+(p[a]?(p[o]||p[u]?timeSeparator:"")+this._minDigits(t._periods[a],2):""):'<span class="countdown_row countdown_show'+(x||c)+(t._hold?" countdown_holding":"")+'">'+b(n)+b(r)+b(i)+b(s)+b(o)+b(u)+'<span id="uji_sec">'+b(a))+"</span>"+"</span>"+(T?'<span class="countdown_row countdown_descr">'+T+"</span>":"")},_buildLayout:function(t,f,l,c,h,p){var d=t.options[c?"compactLabels":"labels"];var v=t.options.whichLabels||this._normalLabels;var m=function(e){return(t.options[(c?"compactLabels":"labels")+v(t._periods[e])]||d)[e]};var g=function(e,n){return t.options.digits[Math.floor(e/n)%10]};var y={desc:t.options.description,sep:t.options.timeSeparator,yl:m(n),yn:this._minDigits(t,t._periods[n],1),ynn:this._minDigits(t,t._periods[n],2),ynnn:this._minDigits(t,t._periods[n],3),y1:g(t._periods[n],1),y10:g(t._periods[n],10),y100:g(t._periods[n],100),y1000:g(t._periods[n],1e3),ol:m(r),on:this._minDigits(t,t._periods[r],1),onn:this._minDigits(t,t._periods[r],2),onnn:this._minDigits(t,t._periods[r],3),o1:g(t._periods[r],1),o10:g(t._periods[r],10),o100:g(t._periods[r],100),o1000:g(t._periods[r],1e3),wl:m(i),wn:this._minDigits(t,t._periods[i],1),wnn:this._minDigits(t,t._periods[i],2),wnnn:this._minDigits(t,t._periods[i],3),w1:g(t._periods[i],1),w10:g(t._periods[i],10),w100:g(t._periods[i],100),w1000:g(t._periods[i],1e3),dl:m(s),dn:this._minDigits(t,t._periods[s],1),dnn:this._minDigits(t,t._periods[s],2),dnnn:this._minDigits(t,t._periods[s],3),d1:g(t._periods[s],1),d10:g(t._periods[s],10),d100:g(t._periods[s],100),d1000:g(t._periods[s],1e3),hl:m(o),hn:this._minDigits(t,t._periods[o],1),hnn:this._minDigits(t,t._periods[o],2),hnnn:this._minDigits(t,t._periods[o],3),h1:g(t._periods[o],1),h10:g(t._periods[o],10),h100:g(t._periods[o],100),h1000:g(t._periods[o],1e3),ml:m(u),mn:this._minDigits(t,t._periods[u],1),mnn:this._minDigits(t,t._periods[u],2),mnnn:this._minDigits(t,t._periods[u],3),m1:g(t._periods[u],1),m10:g(t._periods[u],10),m100:g(t._periods[u],100),m1000:g(t._periods[u],1e3),sl:m(a),sn:this._minDigits(t,t._periods[a],1),snn:this._minDigits(t,t._periods[a],2),snnn:this._minDigits(t,t._periods[a],3),s1:g(t._periods[a],1),s10:g(t._periods[a],10),s100:g(t._periods[a],100),s1000:g(t._periods[a],1e3)};var b=l;for(var w=n;w<=a;w++){var E="yowdhms".charAt(w);var x=new RegExp("\\{"+E+"<\\}([\\s\\S]*)\\{"+E+">\\}","g");b=b.replace(x,!h&&f[w]||h&&p[w]?"$1":"")}e.each(y,function(e,t){var n=new RegExp("\\{"+e+"\\}","g");b=b.replace(n,t)});return b},_minDigits:function(e,t,n){t=""+t;if(t.length>=n){return this._translateDigits(e,t)}t="0000000000"+t;return this._translateDigits(e,t.substr(t.length-n))},_translateDigits:function(e,t){return(""+t).replace(/[0-9]/g,function(t){return e.options.digits[t]})},_determineShow:function(e){var t=e.options.format;var f=[];f[n]=t.match("y")?"?":t.match("Y")?"!":null;f[r]=t.match("o")?"?":t.match("O")?"!":null;f[i]=t.match("w")?"?":t.match("W")?"!":null;f[s]=t.match("d")?"?":t.match("D")?"!":null;f[o]=t.match("h")?"?":t.match("H")?"!":null;f[u]=t.match("m")?"?":t.match("M")?"!":null;f[a]=t.match("s")?"?":t.match("S")?"!":null;return f},_calculatePeriods:function(e,t,f,l){e._now=l;e._now.setMilliseconds(0);var h=new Date(e._now.getTime());if(e._since){if(l.getTime()<e._since.getTime()){e._now=l=h}else{l=e._since}}else{h.setTime(e._until.getTime());if(l.getTime()>e._until.getTime()){e._now=l=h}}var p=[0,0,0,0,0,0,0];if(t[n]||t[r]){var d=c._getDaysInMonth(l.getFullYear(),l.getMonth());var v=c._getDaysInMonth(h.getFullYear(),h.getMonth());var m=h.getDate()==l.getDate()||h.getDate()>=Math.min(d,v)&&l.getDate()>=Math.min(d,v);var g=function(e){return(e.getHours()*60+e.getMinutes())*60+e.getSeconds()};var y=Math.max(0,(h.getFullYear()-l.getFullYear())*12+h.getMonth()-l.getMonth()+(h.getDate()<l.getDate()&&!m||m&&g(h)<g(l)?-1:0));p[n]=t[n]?Math.floor(y/12):0;p[r]=t[r]?y-p[n]*12:0;l=new Date(l.getTime());var b=l.getDate()==d;var w=c._getDaysInMonth(l.getFullYear()+p[n],l.getMonth()+p[r]);if(l.getDate()>w){l.setDate(w)}l.setFullYear(l.getFullYear()+p[n]);l.setMonth(l.getMonth()+p[r]);if(b){l.setDate(w)}}var E=Math.floor((h.getTime()-l.getTime())/1e3);var x=function(e,n){p[e]=t[e]?Math.floor(E/n):0;E-=p[e]*n};x(i,604800);x(s,86400);x(o,3600);x(u,60);x(a,1);if(E>0&&!e._since){var T=[1,12,4.3482,7,24,60,60];var N=a;var C=1;for(var k=a;k>=n;k--){if(t[k]){if(p[N]>=C){p[N]=0;E=1}if(E>0){p[k]++;E=0;N=k;C=1}}C*=T[k]}}if(f){for(var k=n;k<=a;k++){if(f&&p[k]){f--}else if(!f){p[k]=0}}}return p}});var f=["getTimes"];e.fn.countdown=function(e){var t=Array.prototype.slice.call(arguments,1);if(l(e,t)){return c["_"+e+"Plugin"].apply(c,[this[0]].concat(t))}return this.each(function(){if(typeof e=="string"){if(!c["_"+e+"Plugin"]){throw"Unknown command: "+e}c["_"+e+"Plugin"].apply(c,[this].concat(t))}else{c._attachPlugin(this,e||{})}})};var c=e.countdown=new t})(jQuery)